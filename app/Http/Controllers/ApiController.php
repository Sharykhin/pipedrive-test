<?php

namespace App\Http\Controllers;

use App\Interfaces\ResponseInterface;

/**
 * Class ApiController
 * @package App\Http\Controllers
 */
class ApiController extends Controller
{
    /** @var ResponseInterface  */
    protected $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @return mixed
     */
    public function test()
    {
        return $this->response->json(true, ['message'=>'api works']);
    }
}