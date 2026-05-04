<?php

namespace App\Http\Controllers\Api\V1\Doctrine;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Doctrine\IndexDoctrineRequest;
use App\Http\Requests\Api\V1\Doctrine\StoreDoctrineRequest;
use App\Http\Requests\Api\V1\Doctrine\TranslateDoctrineRequest;
use App\Http\Requests\Api\V1\Doctrine\UpdateDoctrineRequest;
use App\Http\Resources\Api\V1\DoctrineResource;
use App\Models\Doctrine;
use App\Services\AiTranslationService;
use App\Services\DoctrineService;
use App\Traits\ResponseHelper;
use Illuminate\Http\JsonResponse;

class DoctrineController extends Controller
{
    use ResponseHelper;

    public function __construct(
        private readonly DoctrineService $doctrineService,
        private readonly AiTranslationService $aiTranslationService,
    ) {
    }

    public function index(IndexDoctrineRequest $request): JsonResponse
    {
        $doctrines = $this->doctrineService->list($request->validated());

        return $this->responseWithPagination(DoctrineResource::collection($doctrines), $doctrines);
    }

    public function options(): JsonResponse
    {
        return $this->responseSucceed($this->doctrineService->options());
    }

    public function show(Doctrine $doctrine): JsonResponse
    {
        return $this->responseSucceed(new DoctrineResource($doctrine->loadCount('posts')));
    }

    public function store(StoreDoctrineRequest $request): JsonResponse
    {
        $doctrine = $this->doctrineService->create($request->validated());

        return $this->responseSucceed(new DoctrineResource($doctrine), 'Doctrine created.', 201);
    }

    public function update(UpdateDoctrineRequest $request, Doctrine $doctrine): JsonResponse
    {
        $doctrine = $this->doctrineService->update($doctrine, $request->validated());

        return $this->responseSucceed(new DoctrineResource($doctrine), 'Doctrine updated.');
    }

    public function destroy(Doctrine $doctrine): JsonResponse
    {
        $doctrine->delete();

        return $this->responseSucceed(message: 'Doctrine deleted.');
    }

    public function translate(TranslateDoctrineRequest $request, Doctrine $doctrine): JsonResponse
    {
        abort_if(! $doctrine->ai_available, 422, 'AI translation is not available for this doctrine.');

        return $this->responseSucceed(
            $this->aiTranslationService->translate($doctrine, $request->language, $request->tone),
            'Translation generated.'
        );
    }
}
