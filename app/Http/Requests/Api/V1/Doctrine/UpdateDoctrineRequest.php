<?php

namespace App\Http\Requests\Api\V1\Doctrine;

class UpdateDoctrineRequest extends StoreDoctrineRequest
{
    public function rules(): array
    {
        return collect(parent::rules())
            ->map(fn (array $rules) => collect($rules)->map(fn ($rule) => $rule === 'required' ? 'sometimes' : $rule)->all())
            ->all();
    }
}
