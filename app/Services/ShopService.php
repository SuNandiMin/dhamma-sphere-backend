<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ShopProfile;
use App\Models\User;
use Illuminate\Support\Collection;

class ShopService
{
    public function ensureShopProfile(User $user): ShopProfile
    {
        abort_if(! $user->canSellBooks(), 403, 'Only authors and publishers can have a shop.');

        return $user->shopProfile()->firstOrCreate([], [
            'name' => $user->shop_name ?: "{$user->name}'s Shop",
            'is_active' => true,
        ]);
    }

    public function updateProfile(User $user, array $data): ShopProfile
    {
        $shop = $this->ensureShopProfile($user);

        $shop->update([
            'name' => $data['shopName'] ?? $data['shop_name'],
            'description' => $data['description'] ?? $shop->description,
            'logo' => $data['logo'] ?? $shop->logo,
        ]);

        $user->update(['shop_name' => $shop->name]);

        return $shop->load('user');
    }

    public function analytics(User $seller): array
    {
        $bookIds = $seller->books()->pluck('id');
        $orders = $this->sellerOrdersQuery($seller)->with('items')->get();

        $sellerItems = $orders->flatMap->items->whereIn('book_id', $bookIds);

        return [
            'booksListed' => $seller->books()->count(),
            'inventoryUnits' => $seller->books()->sum('stock'),
            'unitsSold' => $sellerItems->sum('quantity'),
            'revenue' => round($sellerItems->sum(fn ($item) => (float) $item->price * $item->quantity), 2),
            'ordersCount' => $orders->count(),
        ];
    }

    public function orders(User $seller): Collection
    {
        $bookIds = $seller->books()->pluck('id');

        return $this->sellerOrdersQuery($seller)
            ->with(['items' => fn ($query) => $query->whereIn('book_id', $bookIds)])
            ->latest()
            ->get();
    }

    private function sellerOrdersQuery(User $seller)
    {
        $bookIds = $seller->books()->pluck('id');

        return Order::query()->whereHas('items', fn ($query) => $query->whereIn('book_id', $bookIds));
    }
}
