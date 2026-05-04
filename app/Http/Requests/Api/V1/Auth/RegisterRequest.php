<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(['user', 'author', 'publisher'])],
            'shop_name' => ['nullable', 'string', 'max:160'],
            'shopName' => ['nullable', 'string', 'max:160'],
        ];
    }
}
