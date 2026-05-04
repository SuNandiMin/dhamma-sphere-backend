<?php

namespace App\Http\Requests\Api\V1\Post;

use App\Http\Requests\BaseRequest;

class StoreCommentRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'max:2500'],
            'parent_id' => ['nullable', 'integer', 'exists:comments,id'],
        ];
    }
}
