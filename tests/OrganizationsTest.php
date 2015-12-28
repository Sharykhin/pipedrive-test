<?php

class OrganizationsTest extends TestCase
{
    /**
     * @before
     */
    public function clearAll()
    {
        $this->call('GET', '/api/v1/clearAll');
    }
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreatingOrganization()
    {
        $response = $this->call('POST','/api/v1/organizations', ['name' => 'Banana']);
        $responseContent = json_decode($response->content(), true);
        $this->assertTrue($responseContent['success']);
        $this->assertNull($responseContent['error']);
        $this->assertEquals('Banana', $responseContent['data']['name']);
        $this->seeInDatabase('organizations', ['name' => 'Banana']);
    }
}
