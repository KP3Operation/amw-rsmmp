<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (UserAlreadyExistException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], 409);
            }
        });

        // General axception handler
        $this->renderable(function (RestApiException $e, Request $request) {
            if ($request->is('api/*')) {
                $response = [
                    'message' => $e->getMessage(),
                ];

                if (config('app.debug')) {
                    $response = [
                        'message' => $e->getMessage(),
                        'exception' => RestApiException::class,
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTrace(),
                    ];
                }

                return response()->json($response, $e->getCode());
            }
        });

        $this->renderable(function (SimrsException $e, Request $request) {
            if ($request->is('api/*')) {
                $response = [
                    'message' => $e->getMessage(),
                ];

                if (config('app.debug')) {
                    $response = [
                        'message' => $e->getMessage(),
                        'exception' => SimrsException::class,
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTrace(),
                    ];
                }

                return response()->json($response, $e->getCode());
            }
        });

        $this->renderable(function (WatzapException $e, Request $request) {
            if ($request->is('api/*')) {
                $response = [
                    'message' => $e->getMessage(),
                ];

                if (config('app.debug')) {
                    $response = [
                        'message' => $e->getMessage(),
                        'exception' => WatzapException::class,
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTrace(),
                    ];
                }

                return response()->json($response, $e->getCode());
            }
        });
    }
}
