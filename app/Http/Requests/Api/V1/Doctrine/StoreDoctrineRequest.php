<?php

namespace App\Http\Requests\Api\V1\Doctrine;

use App\Http\Requests\BaseRequest;

class StoreDoctrineRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:180'],
            'topic' => ['required', 'string', 'max:120'],
            'sourceLanguage' => ['nullable', 'string', 'max:80'],
            'source_language' => ['nullable', 'string', 'max:80'],
            'language' => ['required', 'string', 'max:80'],
            'excerpt' => ['required', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'translator' => ['nullable', 'string', 'max:160'],
            'aiAvailable' => ['nullable', 'boolean'],
            'ai_available' => ['nullable', 'boolean'],
            'featured' => ['nullable', 'boolean'],
        ];
    }
}
