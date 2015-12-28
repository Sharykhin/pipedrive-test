<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Response;
use \Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ResponseJsonTrait
 * @package App\Traits
 */
trait ResponseJsonTrait
{
    /** @var array response data which will be encoded into json */
    private $responseData = [];
    /** @var array default fields for response */
    private $responseFields = ['success', 'data', 'error'];
    /** @var int default status code */
    private $status = 200;

    /**
     * @param $raw
     * @return Response
     */
    public function jsonRaw($raw)
    {
        return new Response($raw, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->status = $statusCode;
        return $this;
    }

    /**
     * merge arguments with current responseData
     */
    private function applyResponseData()
    {
        $this->responseData = array_merge(array_combine($this->responseFields, func_get_args()), $this->responseData);
    }

    /**
     * @param string $error
     * @return JsonResponse
     */
    public function modelNotFoundResponse($error = 'Model not found')
    {
        $this->status = 404;
        $this->applyResponseData(false, null, $error);
        return $this->response();
    }

    /**
     * @param $data
     * @param array $additionalData
     * @return JsonResponse
     */
    public function successResponse($data, array $additionalData = [])
    {
        $this->responseData = array_merge_recursive($this->responseData, $additionalData);
        $this->applyResponseData(true, $data, null);
        return $this->response();
    }

    /**
     * @param $error
     * @param array $additionalData
     * @return JsonResponse
     */
    public function failResponse($error, array $additionalData = [])
    {
        $this->responseData = array_merge_recursive($this->responseData, $additionalData);
        $this->applyResponseData(false, null, $error);
        return $this->response();
    }

    /**
     * @param Request $request
     * @param \Exception $e
     * @return JsonResponse
     */
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

    /**
     * @return JsonResponse
     */
    private function response()
    {
        return new JsonResponse($this->responseData, $this->status);
    }
}