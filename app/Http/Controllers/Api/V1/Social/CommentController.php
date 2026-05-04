<?php

namespace App\Http\Controllers\Api\V1\Social;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Post\StoreCommentRequest;
use App\Http\Resources\Api\V1\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Services\CommentService;
use App\Traits\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use ResponseHelper;

    public function __construct(private readonly CommentService $commentService)
    {
    }

    public function store(StoreCommentRequest $request, Post $post): JsonResponse
    {
        $comment = $this->commentService->create($request->user(), $post, $request->validated());

        return $this->responseSucceed(new CommentResource($comment), 'Comment created.', 201);
    }

    public function update(StoreCommentRequest $request, Comment $comment): JsonResponse
    {
        $comment = $this->commentService->update($request->user(), $comment, $request->validated());

        return $this->responseSucceed(new CommentResource($comment), 'Comment updated.');
    }

    public function destroy(Request $request, Comment $comment): JsonResponse
    {
        $this->commentService->delete($request->user(), $comment);

        return $this->responseSucceed(message: 'Comment deleted.');
    }
}
