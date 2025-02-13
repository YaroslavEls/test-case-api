<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use App\Services\UserCreationTokenService;

class TokenController extends Controller
{
    public function __construct(
        private UserCreationTokenService $tokenService
    ) {}

    public function __invoke(): SuccessResponse
    {
        $token = $this->tokenService->generate();

        return new SuccessResponse(200, ['token' => $token->token]);
    }
}
