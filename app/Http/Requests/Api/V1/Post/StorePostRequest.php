<?php

namespace App\Http\Requests\Api\V1\Post;

use App\Http\Requests\BaseRequest;

class StorePostRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'max:5000'],
            'doctrineId' => ['nullable', 'integer', 'exists:doctrines,id'],
            'doctrine_id' => ['nullable', 'integer', 'exists:doctrines,id'],
        ];
    }
}
