<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            //
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (AuthenticationException $e, Request $request) {
            Log::error('AuthenticationException: ' . $e->getMessage(), ['exception' => $e]);
            return ApiResponse::error(
                'You are not logged in. Please log in to access this resource.',
                ['hint' => 'Ensure you have an active session or provide a valid API token.'],
                401
            );
        });

        $exceptions->renderable(function (AccessDeniedHttpException $e, Request $request) {
            Log::error('AccessDeniedHttpException: ' . $e->getMessage(), ['exception' => $e]);
            return ApiResponse::error(
                'You do not have permission to perform this action.',
                ['hint' => 'Contact support if you believe this is an error.'],
                403
            );
        });

        $exceptions->renderable(function (RouteNotFoundException $e) {
            Log::error('RouteNotFoundException: ' . $e->getMessage(), ['exception' => $e]);
            return ApiResponse::error(
                'The route you are trying to access does not exist or is unauthorized.',
                ['hint' => 'Check the endpoint URL or verify your access token.'],
                401
            );
        });

        $exceptions->renderable(function (NotFoundHttpException $e, Request $request) {
            Log::error('NotFoundHttpException: ' . $e->getMessage(), ['exception' => $e]);
            return ApiResponse::error(
                'The resource you are trying to access could not be found.',
                ['hint' => 'Ensure the URL is correct or check if the resource exists.'],
                404
            );
        });

        $exceptions->renderable(function (HttpException $e, Request $request) {
            if ($e->getMessage() === 'Your email address is not verified.') {
                Log::error('HttpException (Email not verified): ' . $e->getMessage(), ['exception' => $e]);
                return ApiResponse::error(
                    'Your email address is not verified.',
                    ['hint' => 'Verify your email to continue.'],
                    403
                );
            }
        });

        $exceptions->renderable(function (ValidationException $e, Request $request) {
            Log::error('ValidationException: ' . $e->getMessage(), ['exception' => $e]);
            return ApiResponse::error(
                'Validation failed.',
                ['errors' => $e->errors()],
                422
            );
        });
    })
    ->create();
