<?php

namespace App\Http\Controllers\Api\V1\Marketplace;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Book\IndexBookRequest;
use App\Http\Requests\Api\V1\Book\StoreBookRequest;
use App\Http\Requests\Api\V1\Book\UpdateBookRequest;
use App\Http\Requests\Api\V1\Book\UpdateStockRequest;
use App\Http\Resources\Api\V1\BookResource;
use App\Models\Book;
use App\Services\BookService;
use App\Traits\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookController extends Controller
{
    use ResponseHelper;

    public function __construct(private readonly BookService $bookService)
    {
    }

    public function index(IndexBookRequest $request): JsonResponse
    {
        $books = $this->bookService->list($request->validated());

        return $this->responseWithPagination(BookResource::collection($books), $books);
    }

    public function show(Book $book): JsonResponse
    {
        return $this->responseSucceed(new BookResource($book->load(['seller.shopProfile', 'shopProfile'])));
    }

    public function store(StoreBookRequest $request): JsonResponse
    {
        $book = $this->bookService->create($request->user(), $request->validated());

        return $this->responseSucceed(new BookResource($book), 'Book created.', 201);
    }

    public function update(UpdateBookRequest $request, Book $book): JsonResponse
    {
        $book = $this->bookService->update($request->user(), $book, $request->validated());

        return $this->responseSucceed(new BookResource($book), 'Book updated.');
    }

    public function updateStock(UpdateStockRequest $request, Book $book): JsonResponse
    {
        $book = $this->bookService->updateStock($request->user(), $book, $request->integer('stock'));

        return $this->responseSucceed(new BookResource($book), 'Stock updated.');
    }

    public function destroy(Request $request, Book $book): JsonResponse
    {
        $this->bookService->delete($request->user(), $book);

        return $this->responseSucceed(message: 'Book deleted.');
    }
}
