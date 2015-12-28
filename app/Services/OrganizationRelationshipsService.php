<?php

namespace App\Services;

/**
 * Class OrganizationRelationshipsService
 * @package App\Services
 */
class OrganizationRelationshipsService
{

    /**
     * Replace all names by their appropriate ids
     * @param $currentOrgs
     * @param $orgsRelationships
     * @param array $mapKeys
     * @return mixed
     */
    public function mapRelationshipData($currentOrgs, $orgsRelationships, array $mapKeys)
    {
        //TODO: Performance notice!
        // $localOrgs has a simple format: id => name, that's why array_search in this
        // particular case will be a quite lite operation.
        foreach($orgsRelationships as &$relationshipItem) {
            foreach($mapKeys as $key) {
                $relationshipItem[$key] = array_search($relationshipItem[$key], $currentOrgs);
            }
        }
        return $orgsRelationships;
    }
}