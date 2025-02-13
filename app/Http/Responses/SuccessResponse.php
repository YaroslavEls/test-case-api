<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class SuccessResponse implements Responsable
{
    public function __construct(
        protected int $httpCode,
        protected array $data
    ) {}

    public function toResponse($request)
    {
        $response_data = [
            'success' => true,
            ...$this->data
        ];

        return response()->json($response_data, $this->httpCode);
    }
}
