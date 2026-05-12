<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Comment;
use App\Models\Doctrine;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->updateOrCreate(
            ['email' => 'user@demo.com'],
            ['name' => 'Lay Reader', 'password' => Hash::make('password'), 'role' => 'user']
        );

        $author = User::query()->updateOrCreate(
            ['email' => 'author@demo.com'],
            ['name' => 'Venerable Suriya', 'password' => Hash::make('password'), 'role' => 'author', 'shop_name' => 'Suriya House']
        );

        $publisher = User::query()->updateOrCreate(
            ['email' => 'publisher@demo.com'],
            ['name' => 'Dhamma River Press', 'password' => Hash::make('password'), 'role' => 'publisher', 'shop_name' => 'Dhamma River Press']
        );

        $authorShop = $author->shopProfile()->firstOrCreate([], [
            'name' => 'Suriya House',
            'description' => 'Meditation and study books from Venerable Suriya.',
            'is_active' => true,
        ]);

        $publisherShop = $publisher->shopProfile()->firstOrCreate([], [
            'name' => 'Dhamma River Press',
            'description' => 'Publisher of Buddhist essays, translations, and commentaries.',
            'is_active' => true,
        ]);

        PaymentMethod::query()->updateOrCreate(['code' => 'stripe'], [
            'name' => 'Stripe',
            'provider' => 'stripe',
            'mode' => 'mock',
            'is_active' => true,
        ]);

        PaymentMethod::query()->updateOrCreate(['code' => 'cash_on_delivery'], [
            'name' => 'Cash on delivery',
            'provider' => 'manual',
            'mode' => 'manual',
            'is_active' => true,
        ]);

        $mockPayment = PaymentMethod::query()->updateOrCreate(['code' => 'wallet'], [
            'name' => 'Mindful wallet',
            'provider' => 'mock',
            'mode' => 'mock',
            'is_active' => true,
        ]);

        $this->call(DoctrineSeeder::class);

        $satipatthana = Doctrine::query()->updateOrCreate(['title' => 'Satipatthana Sutta'], [
            'topic' => 'Mindfulness',
            'source_language' => 'Pali',
            'language' => 'English',
            'excerpt' => 'A foundational teaching on the four establishments of mindfulness.',
            'content' => 'This discourse guides practitioners through contemplation of body, feeling, mind, and dhammas. It is presented here as a readable translation with contextual notes for modern study.',
            'translator' => 'Predefined Canonical Library',
            'ai_available' => true,
            'featured' => true,
        ]);

        $metta = Doctrine::query()->updateOrCreate(['title' => 'Metta Sutta'], [
            'topic' => 'Compassion',
            'source_language' => 'Pali',
            'language' => 'Burmese',
            'excerpt' => 'Teachings on boundless loving-kindness and safe communal living.',
            'content' => 'The Metta Sutta encourages a heart free from hostility, asking practitioners to radiate kindness in all directions while living humbly and wisely.',
            'translator' => 'Dhamma Sphere Editorial Team',
            'ai_available' => true,
            'featured' => true,
        ]);

        Doctrine::query()->updateOrCreate(['title' => 'Dhammapada Selections'], [
            'topic' => 'Wisdom',
            'source_language' => 'Pali',
            'language' => 'English',
            'excerpt' => 'Verses on mind, conduct, and liberation drawn from the Dhammapada.',
            'content' => 'These selected verses focus on disciplined thought, compassionate action, and the long arc of awakening. Each verse is grouped by practical themes for study circles.',
            'translator' => 'Monastic Review Board',
            'ai_available' => false,
            'featured' => false,
        ]);

        $awareness = Book::query()->updateOrCreate(['title' => 'The Gentle Path of Awareness'], [
            'seller_id' => $author->id,
            'shop_profile_id' => $authorShop->id,
            'author' => 'Venerable Suriya',
            'category' => 'Meditation',
            'price' => 18,
            'stock' => 23,
            'cover' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=900&q=80',
            'description' => 'An accessible meditation primer for daily practice, written for contemporary readers with rooted Buddhist guidance.',
            'featured' => true,
        ]);

        Book::query()->updateOrCreate(['title' => 'Compassion in Community'], [
            'seller_id' => $publisher->id,
            'shop_profile_id' => $publisherShop->id,
            'author' => 'Dhamma River Press',
            'category' => 'Ethics',
            'price' => 24,
            'stock' => 12,
            'cover' => 'https://images.unsplash.com/photo-1526243741027-444d633d7365?auto=format&fit=crop&w=900&q=80',
            'description' => 'Essays and translations exploring Buddhist ethics, communal harmony, and social responsibility.',
            'featured' => true,
        ]);

        $wisdom = Book::query()->updateOrCreate(['title' => 'Wisdom by the Lotus Window'], [
            'seller_id' => $author->id,
            'shop_profile_id' => $authorShop->id,
            'author' => 'M. K. Ananda',
            'category' => 'Wisdom',
            'price' => 21,
            'stock' => 8,
            'cover' => 'https://images.unsplash.com/photo-1516979187457-637abb4f9353?auto=format&fit=crop&w=900&q=80',
            'description' => 'A reflective collection of essays linking Buddhist doctrine to work, family life, and civic culture.',
            'featured' => false,
        ]);

        $post = Post::query()->firstOrCreate([
            'user_id' => $user->id,
            'doctrine_id' => $satipatthana->id,
            'body' => 'Today\'s reading of the Satipatthana Sutta reminded me how gentle attention can still be very exact. Curious how others practice this in daily work.',
        ], ['likes_count' => 0, 'shares_count' => 5]);

        $comment = Comment::query()->firstOrCreate([
            'post_id' => $post->id,
            'user_id' => $publisher->id,
            'body' => 'I use short pauses before meetings. That small breath changes the whole tone.',
        ]);

        Comment::query()->firstOrCreate([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'parent_id' => $comment->id,
            'body' => 'That is practical and kind. I am going to try it this week.',
        ]);

        Post::query()->firstOrCreate([
            'user_id' => $publisher->id,
            'doctrine_id' => $metta->id,
            'body' => 'We just added a bilingual commentary on loving-kindness verses and would love feedback from readers working across Burmese and English.',
        ], ['likes_count' => 0, 'shares_count' => 11]);

        $order = Order::query()->firstOrCreate([
            'user_id' => $user->id,
            'order_number' => 'ORD-1001',
        ], [
            'status' => 'Paid',
            'total' => 42,
            'payment_method' => 'mock',
        ]);

        $order->items()->firstOrCreate([
            'book_id' => $awareness->id,
            'title' => $awareness->title,
        ], [
            'price' => $awareness->price,
            'quantity' => 1,
        ]);

        $order->items()->firstOrCreate([
            'book_id' => $wisdom->id,
            'title' => $wisdom->title,
        ], [
            'price' => $wisdom->price,
            'quantity' => 1,
        ]);

        Payment::query()->firstOrCreate([
            'order_id' => $order->id,
            'transaction_reference' => 'MOCK-ORD-1001',
        ], [
            'payment_method_id' => $mockPayment->id,
            'provider' => 'mock',
            'status' => 'succeeded',
            'amount' => 42,
            'payload' => ['mode' => 'seeded'],
        ]);
    }
}
