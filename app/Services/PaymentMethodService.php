<?php

namespace App\Services;

use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Collection;

class PaymentMethodService
{
    public function activeMethods(): Collection
    {
        return PaymentMethod::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->get();
    }
}
