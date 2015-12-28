<?php

namespace  App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PipeDrive\PipeDriveOrganization;
use App\Models\Organization;
use App\Models\OrganizationRelationship;
use App\Services\OrganizationsService;
use App\Services\OrganizationRelationshipsService;
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

    private $orgRelationshipsService;

    public function __construct(
        PipeDriveOrganization $orgPipeDriveService,
        OrganizationsService $orgService,
        OrganizationRelationshipsService $orgRelationshipsService)
    {
        $this->orgPipeDriveService = $orgPipeDriveService;
        $this->orgService = $orgService;
        $this->orgRelationshipsService = $orgRelationshipsService;
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
        //OrganizationRelationship::with('organization', 'linked')->get()->toArray();
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
        // Step three: get all organizations by their names
        // We get an arrat with the following format: [id=>name]
        $localOrgs = Organization::findByNames($orgs);
        $localRelationships = $this->orgRelationshipsService->mapRelationshipData($localOrgs, $localRelationships);
        //TODO: Performance notice!
        die('wow wow wow. stop here please');
        foreach($localRelationships as $localRelationship) {
            OrganizationRelationship::create($localRelationship);
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
                    'rel_owner_org_name' => $data['org_name'],
                    'rel_linked_org_name' => $daughterData['org_name']
                ]);
                array_push($localRelationships, [
                    'org_id' => $data['org_name'],
                    'type'=>'parent',
                    'linked_org_id'=>$daughterData['org_name']
                ]);

                array_push($orgs, $daughterData['org_name']);

                if (isset($daughterData['daughters']) && !empty($daughterData['daughters'])) {
                    $this->parseRelationships($daughterData,  $orgs, $pipeDriveRelationships, $localRelationships);
                }

            }
        }
    }
}