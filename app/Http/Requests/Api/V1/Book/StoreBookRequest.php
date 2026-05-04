<?php

namespace App\Http\Requests\Api\V1\Book;

use App\Http\Requests\BaseRequest;

class StoreBookRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:180'],
            'author' => ['required', 'string', 'max:160'],
            'category' => ['required', 'string', 'max:120'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'cover' => ['nullable', 'url', 'max:1000'],
            'description' => ['required', 'string', 'max:5000'],
            'featured' => ['nullable', 'boolean'],
        ];
    }
}
