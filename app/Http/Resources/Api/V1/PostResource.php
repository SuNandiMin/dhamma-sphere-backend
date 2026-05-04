<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'author' => [
                'name' => $this->user?->name,
                'role' => $this->user?->role,
                'avatar' => strtoupper(substr($this->user?->name ?? 'U', 0, 1)),
            ],
            'user' => UserResource::make($this->whenLoaded('user')),
            'body' => $this->body,
            'doctrineId' => $this->doctrine_id,
            'doctrineTitle' => $this->doctrine?->title ?? 'General reflection',
            'doctrine' => DoctrineResource::make($this->whenLoaded('doctrine')),
            'likes' => $this->likes_count,
            'shares' => $this->shares_count,
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'createdAt' => $this->created_at?->diffForHumans(),
        ];
    }
}
