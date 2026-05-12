<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PostService
{
    public function list(array $filters = []): LengthAwarePaginator
    {
        $query = Post::query()
            ->with(['user.shopProfile', 'doctrine', 'comments.user', 'comments.replies.user'])
            ->latest();

        $authId = auth('api')->id();
        if ($authId && Schema::hasTable('post_likes')) {
            $query->withExists(['likedUsers as liked_by_me' => function ($q) use ($authId): void {
                $q->whereKey($authId);
            }]);
        }

        return $query->paginate(api_per_page($filters['per_page'] ?? null));
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

    public function like(User $user, Post $post): Post
    {
        if (! Schema::hasTable('post_likes')) {
            abort(503, 'Run database migrations: php artisan migrate');
        }

        return DB::transaction(function () use ($user, $post): Post {
            $toggled = $post->likedUsers()->toggle([$user->id]);
            $liked = count($toggled['attached']) > 0;

            $post->update(['likes_count' => $post->likedUsers()->count()]);
            $post->refresh();

            $post->setAttribute('liked_by_me', $liked);

            return $post->load(['user.shopProfile', 'doctrine', 'comments.user', 'comments.replies.user']);
        });
    }

    public function share(Post $post): Post
    {
        $post->increment('shares_count');

        return $post->refresh();
    }
}
