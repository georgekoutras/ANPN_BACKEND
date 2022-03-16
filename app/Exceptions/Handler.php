<?php

namespace App\Exceptions;

use App\Core\Base\ErrorMapper;
use App\Exceptions\TokenRefreshException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
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

    public function report(Throwable $exception)
    {
        if ($exception instanceof TokenRefreshException){

            $exception->error_code = 1003;
            $exception->http_error_code = "498";
            $exception->description = $exception->getMessage();
            $exception->stack_trace = $exception->getTraceAsString();
        }
        parent::report($exception);

    }

    public function render($request, Throwable $exception)
    {

        if ($exception instanceof TokenRefreshException){
            return $this->renderTokenRefreshException($request, $exception);
        }

        return parent::render($request, $exception);
    }

    protected function renderTokenRefreshException(Request $request, TokenRefreshException $exception){
        $response = ['success' => false];
        $response['error'] = ['code' => 498, 'message' => "Token needs refresh"];

        return response()->json($response);

    }
}
