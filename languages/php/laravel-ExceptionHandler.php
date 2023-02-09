<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;

function render($request, Throwable $exception)
{
    if ($exception instanceof ModelNotFoundException && $request->wantsJson()) {
        return response()->json(['message' => 'Not Found'], 404);
    }
    return parent::render($request, $exception);
}