<?php

namespace  App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PipeDrive\PipeDriveOrganization;
use App\Services\OrganizationsService;
use Validator;
use DB;

/**
 * Class OrganizationRelationshipsController
 * @package App\Http\Controllers
 */
class OrganizationRelationshipsController extends ApiController
{

    private $orgPipeDriveService;

    private $orgService;

    public function __construct(PipeDriveOrganization $orgPipeDriveService, OrganizationsService $orgService)
    {
        $this->orgPipeDriveService = $orgPipeDriveService;
        $this->orgService = $orgService;
    }

    /*
    {
        "org_name": "Paradise Island",
        "daughters": [
            {
                "org_name:": "Banana tree",
                "daughters": [
                    {"org_name": "Yellow Banana"},
                    {"org_name": "Brown Banana"},
                    {"org_name": "Green Banana"}
                ]
            },
            {
                "org_name":"Nestle"
            }
        ]
    }
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'org_name' => 'required|max:255',
            'daughters' => 'required|array'
        ]);

        if ($validator->fails()) {
            return $this->failResponse($validator->errors());
        }

        $orgs = [];
        $pipeDriveRelationships = [];
        $localRelationships = [];
        // Step one: go through the data and create three arrays
        array_push($orgs, $request->input('org_name'));
        $this->parseRelationships($request->all(), $orgs, $pipeDriveRelationships, $localRelationships);
        // Step two: check if all $orgs exist
        $organizations = $this->orgPipeDriveService->find($orgs);

        $organizationsNotExist = $this->orgService->getNonExistingOrganizations($organizations, $orgs);
        $organizationsExist = $this->orgService->getExistingOrganizations($organizations);

        if (sizeof($organizationsNotExist) > 0) {
            return $this->failResponse($organizationsNotExist, [
                'error_info'=>'Use the following request: POST /api/v1/organizations to create appropriate organization'
            ]);
        }
    }

    private function parseRelationships($data, &$orgs, &$pipeDriveRelationships, &$localRelationships)
    {

        if (isset($data['daughters']) && !empty($data['daughters'])) {
            foreach($data['daughters'] as $daughterData) {
                if (!isset($daughterData['org_name'])) {
                    throw new \Exception('Daughter object must have org_name');
                }
                array_push($pipeDriveRelationships, [
                    'org_name' => $data['org_name'],
                    'type'=>'parent',
                    'linked_org_name'=>$daughterData['org_name']
                ]);
                array_push($localRelationships, [
                    'org_name' => $data['org_name'],
                    'type'=>'parent',
                    'linked_org_name'=>$daughterData['org_name']
                ]);

                array_push($orgs, $daughterData['org_name']);

                if (isset($daughterData['daughters']) && !empty($daughterData['daughters'])) {
                    $this->parseRelationships($daughterData,  $orgs, $pipeDriveRelationships, $localRelationships);
                }

            }
        }
    }
}