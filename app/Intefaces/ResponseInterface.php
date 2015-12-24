<?php

namespace App\Interfaces;

interface ResponseInterface
{
    public function successJson(array $data);

    public function failJson($errors);

    public function json($success, array $data, $error = null);

}