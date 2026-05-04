<?php

namespace App\Http\Controllers\Api\V1\Marketplace;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PaymentMethodResource;
use App\Services\PaymentMethodService;
use App\Traits\ResponseHelper;
use Illuminate\Http\JsonResponse;

class PaymentMethodController extends Controller
{
    use ResponseHelper;

    public function __construct(private readonly PaymentMethodService $paymentMethodService)
    {
    }

    public function index(): JsonResponse
    {
        return $this->responseSucceed(PaymentMethodResource::collection($this->paymentMethodService->activeMethods()));
    }
}
