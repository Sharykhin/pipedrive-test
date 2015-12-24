<?php

namespace App\Http\Controllers;

use App\Interfaces\ResponseInterface;
use GuzzleHttp\Client;

/**
 * Class ApiController
 * @package App\Http\Controllers
 */
class ApiController extends Controller
{
    /** @var ResponseInterface  */
    protected $response;

    protected $client;

    /**
     * @param ResponseInterface $response
     * @param Client $client
     */
    public function __construct(ResponseInterface $response, Client $client)
    {
        $this->response = $response;
        $this->client = $client;
    }

    /**
     * @return mixed
     */
    public function test()
    {
        return $this->response->json(true, ['message'=>'api works']);
    }
}