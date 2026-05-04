<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'provider' => $this->provider,
            'status' => $this->status,
            'amount' => (float) $this->amount,
            'transactionReference' => $this->transaction_reference,
            'paymentMethod' => PaymentMethodResource::make($this->whenLoaded('paymentMethod')),
            'createdAt' => $this->created_at?->toISOString(),
        ];
    }
}
