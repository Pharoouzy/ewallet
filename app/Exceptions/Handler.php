<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if($request->wantsJson()) {
            if($exception instanceof ValidationException) {
                return $this->convertValidationExceptionToResponse($exception, $request);
            }
            if($exception instanceof ModelNotFoundException) {
                $modelName = strtolower(class_basename($exception->getModel()));
                return errorResponse("Unable to find any {$modelName} with the specified identificator", [], 404);
            }
            if($exception instanceof AuthenticationException) {
                return $this->unauthenticated($request, $exception);
            }
            if($exception instanceof AuthorizationException) {
                return errorResponse($exception->getMessage(), [], 403);
            }
            if($exception instanceof MethodNotAllowedException) {
                $method = $request->method();
                return errorResponse("{$method} request method is not supported on this endpoint", [], 403);
            }
            if($exception instanceof NotFoundHttpException) {
                return errorResponse("The requested endpoint does not exist", [], 404);
            }
            if($exception instanceof QueryException) {
                $errorCode = $exception->errorInfo[1];
                if($errorCode == 1451) {
                    return errorResponse("Cannot remove this resource permanently; It's related with other resource", [], 409);
                }
            }

        }

        return parent::render($request, $exception);
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        if ($e->response) {
            return $e->response;
        }

        return $request->expectsJson()
            ? errorResponse($e->getMessage(), $e->errors(), $e->status)
            : $this->invalid($request, $e);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->wantsJson()
            ? errorResponse($exception->getMessage(), [], 401)
            : redirect()->guest($exception->redirectTo() ?? route('login'));
    }
}
