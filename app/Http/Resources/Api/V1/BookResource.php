<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'sellerRole' => $this->seller?->role,
            'seller' => UserResource::make($this->whenLoaded('seller')),
            'shopName' => $this->shopProfile?->name ?? $this->seller?->shop_name,
            'shopProfile' => ShopProfileResource::make($this->whenLoaded('shopProfile')),
            'category' => $this->category,
            'price' => (float) $this->price,
            'stock' => $this->stock,
            'cover' => $this->cover,
            'description' => $this->description,
            'featured' => $this->featured,
            'createdAt' => $this->created_at?->toISOString(),
        ];
    }
}
