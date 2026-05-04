<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctrineResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'topic' => $this->topic,
            'sourceLanguage' => $this->source_language,
            'language' => $this->language,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'translator' => $this->translator,
            'aiAvailable' => $this->ai_available,
            'featured' => $this->featured,
            'postsCount' => $this->whenCounted('posts'),
            'createdAt' => $this->created_at?->toISOString(),
        ];
    }
}
