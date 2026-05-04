<?php

namespace App\Services;

use App\Models\Book;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BookService
{
    public function list(array $filters): LengthAwarePaginator
    {
        return Book::query()
            ->with(['seller.shopProfile', 'shopProfile'])
            ->when($filters['search'] ?? null, fn ($query, $search) => $query->where(function ($inner) use ($search): void {
                $inner->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            }))
            ->when($filters['category'] ?? null, fn ($query, $category) => $query->where('category', $category))
            ->when($filters['seller_id'] ?? null, fn ($query, $sellerId) => $query->where('seller_id', $sellerId))
            ->when(array_key_exists('featured', $filters), fn ($query) => $query->where('featured', (bool) $filters['featured']))
            ->latest()
            ->paginate(api_per_page($filters['per_page'] ?? null));
    }

    public function create(User $seller, array $data): Book
    {
        abort_if(! $seller->canSellBooks(), 403, 'Only authors and publishers can sell books.');

        $shopProfile = app(ShopService::class)->ensureShopProfile($seller);

        return Book::query()->create([
            ...$data,
            'seller_id' => $seller->id,
            'shop_profile_id' => $shopProfile->id,
            'featured' => $data['featured'] ?? false,
        ])->load(['seller.shopProfile', 'shopProfile']);
    }

    public function update(User $seller, Book $book, array $data): Book
    {
        abort_if($book->seller_id !== $seller->id, 403, 'You can only edit books in your shop.');

        $book->update($data);

        return $book->load(['seller.shopProfile', 'shopProfile']);
    }

    public function updateStock(User $seller, Book $book, int $stock): Book
    {
        abort_if($book->seller_id !== $seller->id, 403, 'You can only update stock in your shop.');

        $book->update(['stock' => $stock]);

        return $book->load(['seller.shopProfile', 'shopProfile']);
    }

    public function delete(User $seller, Book $book): void
    {
        abort_if($book->seller_id !== $seller->id, 403, 'You can only remove books in your shop.');

        $book->delete();
    }
}
