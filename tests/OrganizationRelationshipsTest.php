<?php

class OrganizationRelationshipsTest extends TestCase
{

    private $data = [
        'org_name' => 'Paradise Island',
        'daughters' => [
            [
                'org_name' => 'Banana tree',
                'daughters' => [
                    [
                        'org_name' => 'Yellow Banana'
                    ],
                    [
                        'org_name' => 'Brown Banana'
                    ],
                    [
                        'org_name' => 'Green Banana'
                    ]
                ]
            ],
            [
                'org_name' => 'Coca-Cola'
            ]
        ]
    ];

    private function initializeOrganizations()
    {
        $this->call('DELETE','/api/v1/organizationRelationships');
        $this->call('DELETE','/api/v1/organizations');

        $response = $this->call('POST','/api/v1/organizations', ['name' => 'Paradise Island']);
        $this->call('POST','/api/v1/organizations', ['name' => 'Banana tree']);
        $this->call('POST','/api/v1/organizations', ['name' => 'Yellow Banana']);
        $this->call('POST','/api/v1/organizations', ['name' => 'Brown Banana']);
        $this->call('POST','/api/v1/organizations', ['name' => 'Green Banana']);
        $this->call('POST','/api/v1/organizations', ['name' => 'Coca-Cola']);
        $responseContent = json_decode($response->content(), true);
        return $responseContent['data']['id'];
    }


    public function testCreatingRelationships()
    {
        $this->initializeOrganizations();

        $response = $this->call('POST',
            '/api/v1/organizationRelationships',
            [],[],[], ['CONTENT_TYPE' => 'application/json'],json_encode($this->data));

        $responseContent = json_decode($response->content(), true);

        $this->assertTrue($responseContent['success']);
        $this->assertNull($responseContent['error']);
        $this->assertCount(5, $responseContent['data']);
        foreach($responseContent['data'] as $dataItem) {
            $this->assertArrayHasKey('id', $dataItem);
            $this->assertArrayHasKey('type', $dataItem);
            $this->assertArrayHasKey('rel_owner_org_id', $dataItem);
            $this->assertArrayHasKey('rel_linked_org_id', $dataItem);
        }
        $this->assertEquals('Paradise Island', $responseContent['data'][0]['rel_owner_org_id']['name']);

        $this->call('DELETE','/api/v1/organizationRelationships');
        $this->call('DELETE','/api/v1/organizations');
    }

    public function testGettingRelationShips()
    {
        $orgId = $this->initializeOrganizations();

        $this->call('POST',
            '/api/v1/organizationRelationships',
            [],[],[], ['CONTENT_TYPE' => 'application/json'],json_encode($this->data));

        $response = $this->call('GET','/api/v1/organizationRelationships',['org_id'=>$orgId]);
        $responseContent = json_decode($response->content(), true);
        $this->assertTrue($responseContent['success']);
        $this->assertNull($responseContent['error']);
        $this->assertCount(2, $responseContent['data']);
        foreach($responseContent['data'] as $dataItem) {
            $this->assertArrayHasKey('id', $dataItem);
            $this->assertArrayHasKey('type', $dataItem);
            $this->assertArrayHasKey('org_id', $dataItem);
            $this->assertArrayHasKey('linked_org_id', $dataItem);
            $this->assertTrue(is_array($dataItem['org_id']));
            $this->assertTrue(is_array($dataItem['linked_org_id']));
            $this->assertNotEmpty($dataItem['org_id']);
            $this->assertNotEmpty($dataItem['linked_org_id']);
        }

        $this->call('DELETE', '/api/v1/organizationRelationships');
        $this->call('DELETE', '/api/v1/organizations');
    }
}