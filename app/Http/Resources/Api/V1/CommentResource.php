<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'author' => $this->user?->name,
            'user' => UserResource::make($this->whenLoaded('user')),
            'body' => $this->body,
            'createdAt' => $this->created_at?->diffForHumans(),
            'replies' => CommentResource::collection($this->whenLoaded('replies')),
        ];
    }
}
