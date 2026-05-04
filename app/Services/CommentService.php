<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class CommentService
{
    public function create(User $user, Post $post, array $data): Comment
    {
        return Comment::query()->create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'parent_id' => $data['parent_id'] ?? null,
            'body' => $data['body'],
        ])->load(['user', 'replies.user']);
    }

    public function update(User $user, Comment $comment, array $data): Comment
    {
        abort_if($comment->user_id !== $user->id, 403, 'You can only edit your own comments.');

        $comment->update(['body' => $data['body']]);

        return $comment->load(['user', 'replies.user']);
    }

    public function delete(User $user, Comment $comment): void
    {
        abort_if($comment->user_id !== $user->id, 403, 'You can only delete your own comments.');

        $comment->delete();
    }
}
