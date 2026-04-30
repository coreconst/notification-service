<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // TODO: Add separate exception handler for each exception type
        $exceptions->renderable(function (Exception $e, Request $request) {
            $statusCode = match(true){
                $e instanceof NotFoundHttpException => 404,
                $e instanceof MethodNotAllowedHttpException => 405,
                $e instanceof ValidationException => 422,
                $e instanceof AuthorizationException => 403,
                default => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500
            };

            if ($statusCode < 100 || $statusCode >= 600) {
                $statusCode = 500;
            }

            return response()->json([
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], $statusCode);
        });
    })->create();
