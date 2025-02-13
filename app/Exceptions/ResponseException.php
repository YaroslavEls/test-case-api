<?php

namespace App\Exceptions;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\MessageBag;

class ResponseException extends HttpResponseException
{
    public function __construct(int $httpCode, string $message, ?MessageBag $fails = null)
    {
        $response_data = [
            'success' => false,
            'message' => $message
        ];

        if ($fails) {
            $response_data['fails'] = $fails;
        }

        parent::__construct(response()->json($response_data, $httpCode));
    }
}
