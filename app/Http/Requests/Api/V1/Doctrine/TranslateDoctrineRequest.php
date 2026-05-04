<?php

namespace App\Http\Requests\Api\V1\Doctrine;

use App\Http\Requests\BaseRequest;

class TranslateDoctrineRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'language' => ['required', 'string', 'max:80'],
            'tone' => ['nullable', 'string', 'max:80'],
        ];
    }
}
