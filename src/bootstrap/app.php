<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\AuthorizationException;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at:'*');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                $status=500;

                if ($e instanceof HttpExceptionInterface) {
                    $status=$e->getStatusCode();
                }else{
                    if($e instanceof AuthenticationException) $status=401;
                    if($e instanceof AuthorizationException) $status=403;
                    if($e instanceof ValidationException) $status=422;
                }

                return response()->json([
                    'status'=>$status,
                    'message'=>$e->getMessage(), 
                ],$status);
            }
        });
    })->create();
