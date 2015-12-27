<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Pool;
use App\Interfaces\PipeDriveHttpClientInterface;
use GuzzleHttp\Promise;

class PipeDriveHttpClient implements  PipeDriveHttpClientInterface
{
    const TOKEN = '750ca8539cec9e4e1a534276cea3957403588e2d';
    const PIPEDRIVE_API = 'https://api.pipedrive.com/v1/';

    private $endpoint = null;

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

    public function setUrl($url)
    {

    }

    public function request($endpoint, array $properties = [], $method = 'GET')
    {
        $requestProperties = array_merge_recursive($this->defaultProperties, $properties);
        return $this->client->request($method, $endpoint, $requestProperties);
    }

    public function poolRequests(array $endpoints)
    {
        $requests = function ($total) use ($endpoints) {
            $uri = self::PIPEDRIVE_API;
            for ($i = 0; $i < $total; $i++) {
                yield new Request('GET', $uri . $endpoints[$i]);
            }
        };

        $pool = new Pool($this->client, $requests(sizeof($endpoints)), [
            'concurrency' => 5,
            'fulfilled' => function ($response, $index) {
                // this is delivered each successful response
            },
            'rejected' => function ($reason, $index) {
                // this is delivered each failed request
            },
        ]);
    }

    public function getMultipleRequests(array $requestConfigs)
    {
        $required = ['method','url'];
        // Initiate each request but do not block
        $promises = [];
        foreach($requestConfigs as $index => $requestConfig) {
            if (count(array_intersect_key(array_flip($required), $requestConfig)) !== count($required)) {
                throw new \Exception('Each request configuration in getMultipleRequests method must have url and method properties');
            }
            $requestProperties = array_merge_recursive($this->defaultProperties, $requestConfig);
            $promises[$index] = $this->client->requestAsync(
                $requestProperties['method'],
                self::PIPEDRIVE_API . $requestConfig['url'] . '?' . http_build_query($requestProperties['query']),
                $requestProperties
            );
        }

        // Wait on all of the requests to complete.
        $results = Promise\unwrap($promises);
        $data = [];
        foreach($results as $index => $result) {
            $data[$index] = json_decode($result->getBody()->getContents(), true);
        }
        return $data;
    }


}