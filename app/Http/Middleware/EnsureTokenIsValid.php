<?php

namespace App\Http\Middleware;

use App\Exceptions\ResponseException;
use App\Services\UserCreationTokenService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenIsValid
{
    public function __construct(
        private UserCreationTokenService $tokenService
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Token');
        
        if (!$token || !$this->tokenService->validate($token)) {
            $message = 'The token is invalid or has expired';
            throw new ResponseException(401, $message);
        }

        return $next($request);
    }
}
