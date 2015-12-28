<?php

namespace App\Services;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use App\Interfaces\PipeDriveHttpClientInterface;
use GuzzleHttp\Promise;

/**
 * Class PipeDriveHttpClient
 * @package App\Services
 */
class PipeDriveHttpClient implements  PipeDriveHttpClientInterface
{
    const TOKEN = '750ca8539cec9e4e1a534276cea3957403588e2d';
    const PIPEDRIVE_API = 'https://api.pipedrive.com/v1/';

    private $defaultProperties = [
        'headers' => ['Content-Type' => 'application/json'],
        'query' => [
            'api_token' => self::TOKEN,
        ]
    ];

    /** @var \GuzzleHttp\Client  */
    private $client;


    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => self::PIPEDRIVE_API
        ]);
    }

    /**
     * Make a single request to PIPEDRIVE API
     * @param $endpoint
     * @param array $properties
     * @param string $method
     * @return mixed
     */
    public function request($endpoint, array $properties = [], $method = 'GET')
    {
        $requestProperties = array_merge_recursive($this->defaultProperties, $properties);
        return $this->client->request($method, $endpoint, $requestProperties);
    }

    /**
     * @param array $requestConfigs
     * @return array
     * @throws \Exception
     */
    public function multipleRequests(array $requestConfigs)
    {
        $required = ['method', 'url'];
        // Initiate each request but do not block
        $promises = [];
        $errors = [];
        $data = [];
        foreach($requestConfigs as $index => $requestConfig) {
            if (count(array_intersect_key(array_flip($required), $requestConfig)) !== count($required)) {
                throw new \Exception('Each request configuration in getMultipleRequests method must have url and method properties');
            }
            $requestProperties = array_merge_recursive($this->defaultProperties, $requestConfig);
            $promises[$index] = $this->client->requestAsync(
                $requestProperties['method'],
                $requestConfig['url'] . '?' . http_build_query($requestProperties['query']),
                $requestProperties
            );

            $promises[$index]->then(function (ResponseInterface $res) use ($index, &$data) {
                $data[$index] = json_decode($res->getBody()->getContents(), true);
            }, function (RequestException $e) use (&$errors) {
                $body = json_decode($e->getResponse()->getBody(), true);
                $errors[] = [
                    'error'=>$body['error'],
                    'error_info' => $body['error_info'],
                    'request_body' => json_decode($e->getRequest()->getBody(),true)
                    ];
            })->wait();
        }

        return ['data' => $data, 'errors' => $errors];
    }


}