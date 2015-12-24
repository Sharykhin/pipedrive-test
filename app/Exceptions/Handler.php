<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use App\Interfaces\ResponseInterface;
use Illuminate\Http\JsonResponse;

class Handler extends ExceptionHandler
{
    /** @var ResponseInterface  */
    private $response;

    /**
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {

        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($request->wantsJson()) {
            // Define the response
            $response = [
                'error' => 'Sorry, something went wrong.'
            ];

            // If the app is in debug mode
            if (env('APP_DEBUG')) {
                // Add the exception class name, message and stack trace to response
                $response['exception'] = get_class($e); // Reflection might be better here
                $response['message'] = $e->getMessage();
                $response['trace'] = $e->getTrace();
            }
            $status = 400;

            if ($e instanceof HttpException) {
                $status = $e->getStatusCode();
            }

            return new JsonResponse($this->response->setErrors($response)->getResponse(), $status);
        }

        // Default to the parent class' implementation of handler
        return parent::render($request, $e);
    }
}
