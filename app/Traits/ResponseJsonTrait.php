<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Response;
use \Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ResponseJsonTrait
{
    private $responseData = [];

    private $responseFields = ['success', 'data', 'error'];

    private $status = 200;

    public function jsonRaw($raw)
    {
        return new Response($raw, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    public function setStatusCode($statusCode)
    {
        $this->status = $statusCode;
        return $this;
    }

    private function applyResponseData()
    {
        $this->responseData = array_merge(array_combine($this->responseFields, func_get_args()), $this->responseData);
    }

    public function modelNotFoundResponse($error = 'Model not found')
    {
        $this->status = 404;
        $this->applyResponseData(false, null, $error);
        return $this->response();
    }

    public function successResponse($data)
    {
        $this->applyResponseData(true, $data, null);
        return $this->response();
    }

    public function failResponse($error)
    {
        $this->applyResponseData(false, null, $error);
        return $this->response();
    }

    public function exceptionResponse(Request $request, \Exception $e)
    {
        $error = 'Sorry, something went wrong.';

        $status = 400;

        if ($e instanceof NotFoundHttpException) {
            $error = 'Page not found';
        }

        if ($e instanceof HttpException) {
            $status = $e->getStatusCode();
        }
        if ($e->getMessage()) {
            $this->responseData['error_info'] = $e->getMessage();
        }

        $this->status = $status;
        $this->applyResponseData(false, null, $error);
        return $this->response();

    }

    private function response()
    {
        return new JsonResponse($this->responseData, $this->status);
    }
}