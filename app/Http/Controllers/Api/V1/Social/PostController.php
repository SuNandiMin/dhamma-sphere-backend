<?php

namespace App\Http\Controllers\Api\V1\Social;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Post\StorePostRequest;
use App\Http\Requests\Api\V1\Post\UpdatePostRequest;
use App\Http\Resources\Api\V1\PostResource;
use App\Models\Post;
use App\Services\PostService;
use App\Traits\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    use ResponseHelper;

    public function __construct(private readonly PostService $postService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $posts = $this->postService->list($request->all());

        return $this->responseWithPagination(PostResource::collection($posts), $posts);
    }

    public function store(StorePostRequest $request): JsonResponse
    {
        $post = $this->postService->create($request->user(), $request->validated());

        return $this->responseSucceed(new PostResource($post), 'Post created.', 201);
    }

    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        $post = $this->postService->update($request->user(), $post, $request->validated());

        return $this->responseSucceed(new PostResource($post), 'Post updated.');
    }

    public function destroy(Request $request, Post $post): JsonResponse
    {
        $this->postService->delete($request->user(), $post);

        return $this->responseSucceed(message: 'Post deleted.');
    }

    public function like(Post $post): JsonResponse
    {
        return $this->responseSucceed(new PostResource($this->postService->like($post)), 'Post liked.');
    }

    public function share(Post $post): JsonResponse
    {
        return $this->responseSucceed(new PostResource($this->postService->share($post)), 'Post shared.');
    }
}
