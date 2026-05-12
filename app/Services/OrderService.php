<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function list(User $user, array $filters = []): LengthAwarePaginator
    {
        return Order::query()
            ->where('user_id', $user->id)
            ->with(['items.book', 'payments.paymentMethod'])
            ->latest()
            ->paginate(api_per_page($filters['per_page'] ?? null));
    }

    public function checkout(User $user, array $data): Order
    {
        return DB::transaction(function () use ($user, $data): Order {
            $paymentMethod = PaymentMethod::query()->where('code', $data['paymentMethod'])->where('is_active', true)->firstOrFail();
            $books = Book::query()
                ->whereIn('id', collect($data['items'])->pluck('id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $total = 4.50;

            foreach ($data['items'] as $item) {
                $book = $books->get($item['id']);

                if (! $book || $book->stock < $item['quantity']) {
                    $bookLabel = $book?->title ?? 'A book';

                    throw ValidationException::withMessages([
                        'items' => ["{$bookLabel} does not have enough stock."],
                    ]);
                }

                $total += (float) $book->price * $item['quantity'];
                $book->decrement('stock', $item['quantity']);
            }

            $isCashOnDelivery = $paymentMethod->code === 'cash_on_delivery';

            $order = Order::query()->create([
                'user_id' => $user->id,
                'order_number' => order_number(),
                'status' => $isCashOnDelivery ? 'Unpaid' : 'Paid',
                'total' => $total,
                'payment_method' => $paymentMethod->code,
                'customer_name' => $data['name'],
                'customer_email' => $data['email'],
                'shipping_address' => $data['address'],
                'note' => $data['note'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                $book = $books->get($item['id']);
                $order->items()->create([
                    'book_id' => $book->id,
                    'title' => $book->title,
                    'price' => $book->price,
                    'quantity' => $item['quantity'],
                ]);
            }

            Payment::query()->create([
                'order_id' => $order->id,
                'payment_method_id' => $paymentMethod->id,
                'provider' => $paymentMethod->provider,
                'status' => $isCashOnDelivery ? 'pending' : 'succeeded',
                'amount' => $total,
                'transaction_reference' => transaction_reference(),
                'payload' => [
                    'mode' => $paymentMethod->mode,
                    'customer_email' => $data['email'],
                ],
            ]);

            return $order->load(['items.book', 'payments.paymentMethod']);
        });
    }
}
