<?php

namespace App\Services;

class OrganizationsService
{

    public function getExistingOrganizations($organizations)
    {
        $organizationsExist = [];
        foreach($organizations as $organization) {
            if (!is_null($organization['data'])) {
                $organizationsExist = array_merge($organizationsExist, $organization['data']);
            }
        }
        return $organizationsExist;
    }

    public function getNonExistingOrganizations($organizations, $names)
    {
        $organizationsNotExist = [];
        foreach($organizations as $index=>$organization) {
            if (is_null($organization['data'])) {
                $organizationsNotExist[] = $names[$index] . ' does not exist';
            }
        }
        return $organizationsNotExist;
    }
}