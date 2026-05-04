<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ResponseHelper
{
    protected function responseSucceed(mixed $data = [], string $message = 'Success', int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'code' => $code,
            'data' => $data,
        ], $code);
    }

    protected function responseError(string $message = 'Failed', int $code = Response::HTTP_BAD_REQUEST, mixed $errors = null): JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'code' => $code,
            'errors' => $errors,
        ], $code);
    }

    protected function responseWithPagination(mixed $data, mixed $model, string $message = 'Success', int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'code' => $code,
            'data' => $data,
            'pagination_data' => [
                'current_page' => $model->currentPage(),
                'per_page' => $model->perPage(),
                'total' => $model->total(),
                'from' => $model->firstItem(),
                'to' => $model->lastItem(),
                'last_page' => $model->lastPage(),
                'next_page_url' => $model->nextPageUrl(),
                'prev_page_url' => $model->previousPageUrl(),
            ],
        ], $code);
    }
}
