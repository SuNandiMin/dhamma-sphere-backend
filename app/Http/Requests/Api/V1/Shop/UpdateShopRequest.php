<?php

namespace App\Http\Requests\Api\V1\Shop;

use App\Http\Requests\BaseRequest;

class UpdateShopRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'shopName' => ['required_without:shop_name', 'string', 'max:160'],
            'shop_name' => ['required_without:shopName', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:2000'],
            'logo' => ['nullable', 'url', 'max:1000'],
        ];
    }
}
