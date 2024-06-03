<?php


namespace App\Helpar;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{
    public static function Out(string $status, $message, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
        ], $statusCode);
    }
}