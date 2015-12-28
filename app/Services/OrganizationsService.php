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
        return array_reduce($organizationsExist, function(&$newData, $orgItem) {
            $newData[$orgItem['id']] = $orgItem['name'];
            return $newData;
        }, []);
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
                $organizationsNotExist[] = $names[$index];
            }
        }
        return $organizationsNotExist;
    }
}