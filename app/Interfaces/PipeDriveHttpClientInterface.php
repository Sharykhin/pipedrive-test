<?php

namespace App\Interfaces;

interface PipeDriveHttpClientInterface
{
    public function request($endpoint, array $properties = [], $method = 'GET');
}