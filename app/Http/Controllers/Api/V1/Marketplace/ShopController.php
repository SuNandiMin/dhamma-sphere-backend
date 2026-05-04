<?php

namespace App\Http\Controllers\Api\V1\Marketplace;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Shop\UpdateShopRequest;
use App\Http\Resources\Api\V1\OrderResource;
use App\Http\Resources\Api\V1\ShopProfileResource;
use App\Services\ShopService;
use App\Traits\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    use ResponseHelper;

    public function __construct(private readonly ShopService $shopService)
    {
    }

    public function profile(Request $request): JsonResponse
    {
        return $this->responseSucceed(new ShopProfileResource($this->shopService->ensureShopProfile($request->user())->load('user')));
    }

    public function updateProfile(UpdateShopRequest $request): JsonResponse
    {
        $shop = $this->shopService->updateProfile($request->user(), $request->validated());

        return $this->responseSucceed(new ShopProfileResource($shop), 'Shop profile updated.');
    }

    public function analytics(Request $request): JsonResponse
    {
        return $this->responseSucceed($this->shopService->analytics($request->user()));
    }

    public function orders(Request $request): JsonResponse
    {
        return $this->responseSucceed(OrderResource::collection($this->shopService->orders($request->user())));
    }
}
