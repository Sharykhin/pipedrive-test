<?php

namespace  App\Http\Controllers;

class OrganizationsController extends ApiController
{
    public function index()
    {
        $res = $this->client->request('GET',
            'https://api.pipedrive.com/v1/organizations?start=0&api_token=750ca8539cec9e4e1a534276cea3957403588e2d', [
                'headers' => ['Content-Type' => 'application/json'],
            ]);
        var_dump(json_decode($res->getBody()->getContents(), true));
    }
}