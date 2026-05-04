<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Doctrine\DoctrineController;
use App\Http\Controllers\Api\V1\Marketplace\BookController;
use App\Http\Controllers\Api\V1\Marketplace\OrderController;
use App\Http\Controllers\Api\V1\Marketplace\PaymentMethodController;
use App\Http\Controllers\Api\V1\Marketplace\ShopController;
use App\Http\Controllers\Api\V1\Social\CommentController;
use App\Http\Controllers\Api\V1\Social\PostController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    //register and login routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/doctrines', [DoctrineController::class, 'index']);
    Route::get('/doctrines-options', [DoctrineController::class, 'options']);
    Route::get('/doctrines/{doctrine}', [DoctrineController::class, 'show']);
    Route::post('/doctrines/{doctrine}/translate', [DoctrineController::class, 'translate']);

    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/posts/{post}/like', [PostController::class, 'like']);
    Route::post('/posts/{post}/share', [PostController::class, 'share']);

    Route::get('/books', [BookController::class, 'index']);
    Route::get('/books/{book}', [BookController::class, 'show']);
    Route::get('/payment-methods', [PaymentMethodController::class, 'index']);

    Route::middleware('auth:api')->group(function (): void {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::post('/posts', [PostController::class, 'store']);
        Route::put('/posts/{post}', [PostController::class, 'update']);
        Route::delete('/posts/{post}', [PostController::class, 'destroy']);
        Route::post('/posts/{post}/comments', [CommentController::class, 'store']);
        Route::put('/comments/{comment}', [CommentController::class, 'update']);
        Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);

        Route::post('/checkout', [OrderController::class, 'checkout']);
        Route::get('/orders', [OrderController::class, 'index']);

        Route::middleware('role:author,publisher')->group(function (): void {
            Route::post('/doctrines', [DoctrineController::class, 'store']);
            Route::put('/doctrines/{doctrine}', [DoctrineController::class, 'update']);
            Route::delete('/doctrines/{doctrine}', [DoctrineController::class, 'destroy']);

            Route::get('/shop/profile', [ShopController::class, 'profile']);
            Route::put('/shop/profile', [ShopController::class, 'updateProfile']);
            Route::get('/shop/analytics', [ShopController::class, 'analytics']);
            Route::get('/shop/orders', [ShopController::class, 'orders']);

            Route::post('/books', [BookController::class, 'store']);
            Route::put('/books/{book}', [BookController::class, 'update']);
            Route::patch('/books/{book}/stock', [BookController::class, 'updateStock']);
            Route::delete('/books/{book}', [BookController::class, 'destroy']);
        });
    });
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/doctrines', [DoctrineController::class, 'index']);
Route::get('/doctrines-options', [DoctrineController::class, 'options']);
Route::get('/doctrines/{doctrine}', [DoctrineController::class, 'show']);
Route::post('/doctrines/{doctrine}/translate', [DoctrineController::class, 'translate']);
Route::get('/posts', [PostController::class, 'index']);
Route::post('/posts/{post}/like', [PostController::class, 'like']);
Route::post('/posts/{post}/share', [PostController::class, 'share']);
Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{book}', [BookController::class, 'show']);
Route::get('/payment-methods', [PaymentMethodController::class, 'index']);

Route::middleware('auth:api')->group(function (): void {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::put('/posts/{post}', [PostController::class, 'update']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);
    Route::post('/posts/{post}/comments', [CommentController::class, 'store']);
    Route::put('/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
    Route::post('/checkout', [OrderController::class, 'checkout']);
    Route::get('/orders', [OrderController::class, 'index']);

    Route::middleware('role:author,publisher')->group(function (): void {
        Route::post('/doctrines', [DoctrineController::class, 'store']);
        Route::put('/doctrines/{doctrine}', [DoctrineController::class, 'update']);
        Route::delete('/doctrines/{doctrine}', [DoctrineController::class, 'destroy']);
        Route::get('/shop/profile', [ShopController::class, 'profile']);
        Route::put('/shop/profile', [ShopController::class, 'updateProfile']);
        Route::get('/shop/analytics', [ShopController::class, 'analytics']);
        Route::get('/shop/orders', [ShopController::class, 'orders']);
        Route::post('/books', [BookController::class, 'store']);
        Route::put('/books/{book}', [BookController::class, 'update']);
        Route::patch('/books/{book}/stock', [BookController::class, 'updateStock']);
        Route::delete('/books/{book}', [BookController::class, 'destroy']);
    });
});
