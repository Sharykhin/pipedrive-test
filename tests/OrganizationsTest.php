<?php

class OrganizationsTest extends TestCase
{
    private function getTestOrganization()
    {
        $response = $this->call('POST','/api/v1/organizations', ['name' => 'Banana']);
        $responseContent = json_decode($response->content(), true);
        return $responseContent['data']['id'];
    }

    public function testCreatingOrganization()
    {
        $response = $this->call('POST','/api/v1/organizations', ['name' => 'Banana']);
        $responseContent = json_decode($response->content(), true);
        $this->assertTrue($responseContent['success']);
        $this->assertNull($responseContent['error']);
        $this->assertEquals('Banana', $responseContent['data']['name']);
        $this->seeInDatabase('organizations', ['name' => 'Banana']);
        return $responseContent['data']['id'];
    }


    public function testGettingOrganizationById()
    {
        $id = $this->getTestOrganization();
        $response = $this->call('GET', '/api/v1/organizations/' . $id);
        $responseContent = json_decode($response->content(), true);
        $this->assertTrue($responseContent['success']);
        $this->assertNull($responseContent['error']);
        $this->assertEquals('Banana', $responseContent['data']['name']);
        return $responseContent['data']['id'];
    }


    public function testUpdatingOrganization()
    {
        $id = $this->getTestOrganization();
        $response = $this->call('PUT', '/api/v1/organizations/' . $id, ['name' => 'Yellow Banana']);
        $responseContent = json_decode($response->content(), true);
        $this->assertTrue($responseContent['success']);
        $this->assertNull($responseContent['error']);
        $this->assertEquals('Yellow Banana', $responseContent['data']['name']);
        return $responseContent['data']['id'];
    }

    public function testDeletingOrganization()
    {
        $id = $this->getTestOrganization();
        $response = $this->call('DELETE', '/api/v1/organizations/' . $id);
        $responseContent = json_decode($response->content(), true);
        $this->assertTrue($responseContent['success']);
        $this->assertNull($responseContent['error']);
        $this->assertEquals($id, $responseContent['data']['id']);
    }
}
