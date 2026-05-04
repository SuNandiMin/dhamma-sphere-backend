<?php

namespace App\Http\Requests\Api\V1\Book;

use App\Http\Requests\BaseRequest;

class UpdateStockRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'stock' => ['required', 'integer', 'min:0'],
        ];
    }
}
