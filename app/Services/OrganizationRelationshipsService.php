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
     * @param $localOrgs
     * @param $localRelationships
     * @return mixed
     */
    public function mapRelationshipData($localOrgs, $localRelationships)
    {
        //TODO: Performance notice!
        // $localOrgs has a simple format: id => name, that's why array_search in this
        // particular case will be a quite lite operation.
        foreach($localRelationships as &$relationshipItem) {
            $relationshipItem['org_id'] = array_search($relationshipItem['org_id'], $localOrgs);
            $relationshipItem['linked_org_id'] = array_search($relationshipItem['linked_org_id'], $localOrgs);
        }
        return $localRelationships;
    }
}