<?php

namespace App\Services;

/**
 * Class OrganizationsService
 * @package App\Services
 */
class OrganizationsService
{
    /**
     * @param $organizations
     * @return array
     */
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

    /**
     * @param $organizations
     * @param $names
     * @return array
     */
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