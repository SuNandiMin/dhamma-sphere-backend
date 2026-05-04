<?php

namespace App\Http\Requests\Api\V1\Book;

use App\Http\Requests\BaseRequest;

class IndexBookRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:120'],
            'seller_id' => ['nullable', 'integer', 'exists:users,id'],
            'featured' => ['nullable', 'boolean'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
