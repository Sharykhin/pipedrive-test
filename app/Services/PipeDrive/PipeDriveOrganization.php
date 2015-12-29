<?php

namespace App\Services\PipeDrive;

use App\Interfaces\PipeDriveHttpClientInterface;

/**
 * Class PipeDriveOrganization
 * @package App\Services\PipeDrive
 */
class PipeDriveOrganization
{
    private $client;

    const ENDPOINT = 'organizations';

    public function __construct(PipeDriveHttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Create an organization at PipeDrive
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public function create($data)
    {
        $exist = $this->findOne($data['name']);
        if (!$exist) {
            $result = $this->client->request(self::ENDPOINT,
                ['json' =>
                    ['name' => $data['name']]
                ], 'POST');

            if ($result['success'] === true) {
                return $result['data'];
            } else {
                throw new \Exception($result['error']);
            }
        }

        return true;
    }

    /**
     * Delete an organization at PipeDrive
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $result = $this->client->request(self::ENDPOINT. '/' . $id, [], 'DELETE');

        if ($result['success'] === true) {
            return true;
        }

        return false;
    }

    /**
     * Update an organization at PipeDrive
     * @param $data
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public function update($data, $id)
    {
        $result = $this->client->request(self::ENDPOINT. '/' . $id,
            ['json' =>
                ['name' => $data['name']]
            ], 'PUT');

        if ($result['success'] === true) {
            return true;
        } else {
            throw new \Exception($result['error']);
        }
    }

    /**
     * Find an organization by using full name, return first match
     * @param $term
     * @return bool
     * @throws \Exception
     */
    public function findOne($term)
    {
        $result = $this->client->request(self::ENDPOINT.'/find', [
            'query' => [
                'term' => $term
            ]
        ]);

        if ($result['success'] === true && !is_null($result['data'])) {
            return $result['data'][0];
        }

        return false;
    }

    /**
     * Find an organization by terms of find an array of organization if array of terms was passed
     * @param $terms
     * @param int $start
     * @return mixed
     */
    public function find($terms, $start = 0)
    {
        if (is_string($terms)) {

            $result = $this->client->request(self::ENDPOINT.'/find', [
                'query' => [
                    'term' => $terms,
                    'start' => $start
                ]
            ]);

            return $result;
        }
        if (is_array($terms)) {
            $requestConfig = [];
            foreach($terms as $index => $term) {
                $requestConfig[$index] = [
                    'method' => 'GET',
                    'url'=>self::ENDPOINT.'/find',
                    'query' => [
                        'term' => $term
                    ]
                ];
            }
            $results = $this->client->multipleRequests($requestConfig);

            return $results;
        }
    }

    public function getAll()
    {
        return $this->client->request(self::ENDPOINT);
    }
}