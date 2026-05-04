<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'logo' => $this->logo,
            'isActive' => $this->is_active,
            'owner' => UserResource::make($this->whenLoaded('user')),
            'booksCount' => $this->whenCounted('books'),
        ];
    }
}
