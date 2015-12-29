<?php

namespace App\Services\PipeDrive;

use App\Interfaces\PipeDriveHttpClientInterface;

/**
 * Class PipeDriveOrganizationRelationships
 * @package App\Services\PipeDrive
 */
class PipeDriveOrganizationRelationships
{
    private $client;

    const ENDPOINT = 'organizationRelationships';

    public function __construct(PipeDriveHttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Create relationship. Pay attention to that fact, that creating always uses multipleRequests
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $requestConfig = [];
        foreach($data as $index => $relationshipData) {
            $requestConfig[$index] = [
                'method' => 'POST',
                'url' => self::ENDPOINT,
                'json' => $relationshipData
           ];
        }

        $results = $this->client->multipleRequests($requestConfig);

        return $results;
    }

    /**
     * Delete an organization at PipeDrive
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $response = $this->client->request(self::ENDPOINT. '/' . $id, [], 'DELETE');

        if ($response['success'] === true) {
            return true;
        }

        return false;
    }

    /**
     * Return all relationship by organization ID
     * @param $org_id
     * @return mixed
     */
    public function getAll($org_id)
    {
        return $this->client->request(self::ENDPOINT, ['query' => ['org_id'=>$org_id] ], 'GET');
    }
}