<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PostService
{
    public function list(array $filters = []): LengthAwarePaginator
    {
        return Post::query()
            ->with(['user.shopProfile', 'doctrine', 'comments.user', 'comments.replies.user'])
            ->latest()
            ->paginate(api_per_page($filters['per_page'] ?? null));
    }

    public function create(User $user, array $data): Post
    {
        return Post::query()->create([
            'user_id' => $user->id,
            'doctrine_id' => $data['doctrineId'] ?? $data['doctrine_id'] ?? null,
            'body' => $data['body'],
        ])->load(['user.shopProfile', 'doctrine', 'comments']);
    }

    public function update(User $user, Post $post, array $data): Post
    {
        abort_if($post->user_id !== $user->id, 403, 'You can only modify your own posts.');

        $post->update(['body' => $data['body']]);

        return $post->load(['user.shopProfile', 'doctrine', 'comments.user', 'comments.replies.user']);
    }

    public function delete(User $user, Post $post): void
    {
        abort_if($post->user_id !== $user->id, 403, 'You can only delete your own posts.');

        $post->delete();
    }

    public function like(Post $post): Post
    {
        $post->increment('likes_count');

        return $post->refresh();
    }

    public function share(Post $post): Post
    {
        $post->increment('shares_count');

        return $post->refresh();
    }
}
