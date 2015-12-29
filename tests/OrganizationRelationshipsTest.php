<?php

class OrganizationRelationshipsTest extends TestCase
{

    public function testCreatingRelationships()
    {
        $this->call('DELETE','/api/v1/organizationRelationships');
        $this->call('DELETE','/api/v1/organizations');

        $this->call('POST','/api/v1/organizations', ['name' => 'Paradise Island']);
        $this->call('POST','/api/v1/organizations', ['name' => 'Banana tree']);
        $this->call('POST','/api/v1/organizations', ['name' => 'Yellow Banana']);
        $this->call('POST','/api/v1/organizations', ['name' => 'Brown Banana']);
        $this->call('POST','/api/v1/organizations', ['name' => 'Green Banana']);
        $this->call('POST','/api/v1/organizations', ['name' => 'Coca-Cola']);

        $response = $this->call('POST', '/api/v1/organizationRelationships', [
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
        ]);
        $responseContent = json_decode($response->content(), true);
        $this->assertTrue($responseContent['success']);
        $this->assertNull($responseContent['error']);
        $this->assertCount(5, sizeof($responseContent['data']));
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
}