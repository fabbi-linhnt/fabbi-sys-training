<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Validation\ValidationException;

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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return response()->json(
                $this->getJsonMessage($exception),
                $this->getExceptionHTTPStatusCode($exception)
            );
        }

        return parent::render($request, $exception);
    }

    private function getJsonMessage($e) {
        return [
            'code' => 500,
            'message' => $e->getJsonMessage(),
            'errors' => $e->errors()
        ];
    }

    private function getExceptionHTTPStatusCode($e) {
        return method_exists($e, 'getStatusCode' ? $e->getStatusCode() : 500);
    }

}
