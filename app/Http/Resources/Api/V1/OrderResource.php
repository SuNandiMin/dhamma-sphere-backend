<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->order_number,
            'status' => $this->status,
            'total' => (float) $this->total,
            'paymentMethod' => $this->payment_method,
            'customerName' => $this->customer_name,
            'customerEmail' => $this->customer_email,
            'shippingAddress' => $this->shipping_address,
            'createdAt' => $this->created_at?->toDateString(),
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'payments' => PaymentResource::collection($this->whenLoaded('payments')),
        ];
    }
}
