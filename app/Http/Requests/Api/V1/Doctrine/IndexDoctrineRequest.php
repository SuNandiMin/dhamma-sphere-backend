<?php

namespace App\Http\Requests\Api\V1\Doctrine;

use App\Http\Requests\BaseRequest;

class IndexDoctrineRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'topic' => ['nullable', 'string', 'max:120'],
            'language' => ['nullable', 'string', 'max:80'],
            'featured' => ['nullable', 'boolean'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
