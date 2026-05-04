<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'bookId' => $this->book_id,
            'title' => $this->title,
            'price' => (float) $this->price,
            'quantity' => $this->quantity,
            'book' => BookResource::make($this->whenLoaded('book')),
        ];
    }
}
