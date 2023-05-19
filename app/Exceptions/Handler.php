<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
    }

    public function render($request, Throwable $e)
    {
        $statusCode = match ($e::class) {
            NotFoundHttpException::class => Response::HTTP_NOT_FOUND,
            ModelNotFoundException::class => Response::HTTP_NOT_FOUND,
            default => Response::HTTP_BAD_REQUEST
        };

        $errors = match ($e::class) {
            NotFoundHttpException::class => $e->getMessage(),
            ValidationException::class => $e->errors(),
            default => $e->getMessage()
        };

        return response()->json([
            'status' => $statusCode,
            'errors' => $errors
        ]);
    }
}
