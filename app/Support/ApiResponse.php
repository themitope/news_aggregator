<?php

namespace App\Support;

use Illuminate\Http\Response;

class ApiResponse
{
    /**
     * Error response
     */
    public static function error(string $message, int $code = 400): Response
    {
        return new Response([
            'statusCode' => $code,
            'message' => $message,
        ], $code);
    }

    /**
     * Success response
     */
    public static function success(string $message = null, $data = null, int $code = 200): Response
    {
        return new Response([
            'statusCode' => $code,
            'message' => $message ?? 'Success processing request',
            'data' => $data,
        ], $code);
    }

     /**
     * Success response
     */
    public static function paginate(string $message = null, $data = null, int $code = 200): Response
    {
        return new Response([
            'statusCode' => $code,
            'message' => $message ?? 'Success processing request',
            'data' => $data['data'] ?? $data,
            'pagination' => $data['pagination'] ?? null
        ], $code);
    }
}
