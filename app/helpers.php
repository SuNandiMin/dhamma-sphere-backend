<?php

use Illuminate\Support\Str;

if (! function_exists('api_per_page')) {
    function api_per_page(?int $perPage = null): int
    {
        return min(max($perPage ?? (int) request('per_page', 12), 1), 100);
    }
}

if (! function_exists('order_number')) {
    function order_number(string $prefix = 'ORD'): string
    {
        return $prefix.'-'.now()->format('YmdHis').'-'.Str::upper(Str::random(4));
    }
}

if (! function_exists('transaction_reference')) {
    function transaction_reference(string $prefix = 'PAY'): string
    {
        return $prefix.'-'.now()->format('YmdHis').'-'.Str::upper(Str::random(6));
    }
}
