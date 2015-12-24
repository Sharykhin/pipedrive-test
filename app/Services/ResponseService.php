<?php

namespace App\Services;

use App\Interfaces\ResponseInterface;
use Illuminate\Http\JsonResponse;

class ResponseService implements ResponseInterface
{
    private $response = [
        'success'=>true,
        'data'=>null,
        'errors'=>null,
        'additional_data'=>null
    ];

    public function setErrors($errors)
    {
        $this->response['success'] = false;
        $this->response['errors'] = $errors;
        return $this;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function json($success, array $data, $errors = null)
    {
        $this->response['success'] = $success;
        $this->response['data'] = $data;
        $this->response['errors'] = $errors;
        return $this->response();
    }

    public function successJson(array $data)
    {
        $this->response['data'] = $data;
        return $this->response();
    }

    public function failJson($errors)
    {
        $this->response['success'] = false;
        $this->response['errors'] = $errors;
        return $this->response();
    }

    private function response()
    {
        return new JsonResponse($this->response);
    }
}