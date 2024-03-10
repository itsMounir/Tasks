<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\{
    Eloquent\ModelNotFoundException,
    QueryException,
    UniqueConstraintViolationException
};
use Illuminate\Validation\ValidationException;


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

    public function render($request, Exception|Throwable $e)
    {
        if ($e instanceof ValidationException) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
    }
        if ($e instanceof ModelNotFoundException) {
            return response()->json([
                'message' => explode('\\', $e->getModel())[2] . ' Not Found.',
            ], 404);
        }

        if ($e instanceof AuthorizationException) {
            return response()->json([
                'message' => 'This action is unauthorized.',
            ], 403);
        }

        if ($e instanceof UniqueConstraintViolationException) {
            return response()->json([
                'message' => 'This record already exists.',
            ]);
        }

        if ($e instanceof QueryException) {
            return response()->json([
                'message' => $e->getMessage(),
            ]);
        }

        return parent::render($request, $e);
    }
}
