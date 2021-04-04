<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponse;

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
        //return parent::render($request, $exception);

        if ($exception instanceof HttpException) {
            $code = $exception->getStatusCode();
            $message = "Ha ocurrido un error inesperado.";
            $content = Response::$statusTexts[$code];
            return \response(
                array('success' => false,
                    'message' => $message,
                    'code' => $code,
                    'content' => $content),
                $code
            );
        }

        if ($exception instanceof AuthorizationException) {
            return \response(
                array('success' => false,
                    'message' => 'El usuario no está autorizado para realizar la solicitud',
                    'code' => Response::HTTP_UNAUTHORIZED,
                    'content' => array()),
                Response::HTTP_UNAUTHORIZED
            );
        }

        if ($exception instanceof AuthenticationException) {
            return \response(
                array('success' => false,
                    'message' => 'El usuario no está autorizado para realizar la solicitud',
                    'code' => Response::HTTP_UNAUTHORIZED,
                    'content' => array()),
                Response::HTTP_UNAUTHORIZED
            );
        }

        if ($exception instanceof ValidationException) {
            $message = $exception->validator->errors()->first();
            return \response(
                array('success' => false,
                    'message' => $message,
                    'code' => Response::HTTP_BAD_REQUEST,
                    'content' => array()),
                Response::HTTP_BAD_REQUEST
            );
        }

        if ($exception instanceof ModelNotFoundException) {
            $message = $exception->getMessage();
            return \response(
                array('success' => false,
                    'message' => $message,
                    'code' => Response::HTTP_BAD_REQUEST,
                    'content' => array()),
                Response::HTTP_BAD_REQUEST
            );
        }

        if (env('APP_DEBUG', false)) {
            return parent::render($request, $exception);
        }

        return \response()->json($this->errorResponse("Ha ocurrido un error inesperado.", Response::HTTP_INTERNAL_SERVER_ERROR));
    }

}
