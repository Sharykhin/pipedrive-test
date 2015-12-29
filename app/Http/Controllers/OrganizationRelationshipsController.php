<?php

namespace  App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PipeDrive\PipeDriveOrganization;
use App\Models\Organization;
use App\Models\OrganizationRelationship;
use App\Services\OrganizationsService;
use App\Services\OrganizationRelationshipsService;
use App\Services\PipeDrive\PipeDriveOrganizationRelationships;
use Validator;
use DB;

/**
 * Class OrganizationRelationshipsController
 * @package App\Http\Controllers
 */
class OrganizationRelationshipsController extends ApiController
{

    private $orgPipeDriveService;

    private $orgRelPipeDriveService;

    private $orgService;

    private $orgRelationshipsService;

    public function __construct(
        PipeDriveOrganization $orgPipeDriveService,
        OrganizationsService $orgService,
        OrganizationRelationshipsService $orgRelationshipsService,
        PipeDriveOrganizationRelationships $orgRelPipeDriveService)
    {
        $this->orgPipeDriveService = $orgPipeDriveService;
        $this->orgService = $orgService;
        $this->orgRelationshipsService = $orgRelationshipsService;
        $this->orgRelPipeDriveService = $orgRelPipeDriveService;
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'org_id' => 'required',
        ], [
            'org_id.required' => 'Missing required org_id'
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(400)->failResponse($validator->getMessageBag()->get('org_id')[0]);
        }
        $data = OrganizationRelationship::with('org_id','linked_org_id')
            ->where('org_id','=',$request->input('org_id'))
            ->get();

        return $this->successResponse($data);
    }

    /*
    {
        "org_name": "Paradise Island",
        "daughters": [
            {
                "org_name": "Banana tree",
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
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'org_name' => 'required|max:255',
            'daughters' => 'required|array'
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(400)->failResponse($validator->errors());
        }
        // initialize arrays for organization and relationships for storing in local database and pipedrive
        $orgs = [];
        $pipeDriveRelationships = [];
        $localRelationships = [];
        // Step one: go through the data and create three arrays
        array_push($orgs, $request->input('org_name'));
        $this->parseRelationships($request->all(), $orgs, $pipeDriveRelationships, $localRelationships);
        // Step two: check if all $orgs exist
        $searchResult = $this->orgPipeDriveService->find($orgs);
        if (!empty($searchResult['errors'])) {
            return $this->failResponse($searchResult['errors']);
        }
        $organizations = $searchResult['data'];
        $organizationsNotExist = $this->orgService->getNonExistingOrganizations($organizations, $orgs);
        $organizationsExist = $this->orgService->getExistingOrganizations($organizations);

        if (sizeof($organizationsNotExist) > 0) {
            return $this->failResponse('Organizations: ' . implode(',', $organizationsNotExist) . ' don\'t exist', [
                'error_info'=>'Use the following request: POST /api/v1/organizations to create appropriate organization'
            ]);
        }
        // Step three: get all organizations by their names
        // We get an arrat with the following format: [id=>name]
        $localOrgs = Organization::findByNames($orgs);
        $nonExistingOrgs = array_diff($orgs, $localOrgs);
        if (!empty($nonExistingOrgs)) {
            return $this->failResponse('Organizations: ' . implode(',', $nonExistingOrgs) . ' don\'t exist', [
                'error_info'=>'Use the following request: POST /api/v1/organizations to create appropriate organization'
            ]);
        }
        $localRelationships = $this->orgRelationshipsService->mapRelationshipData($localOrgs, $localRelationships, ['org_id','linked_org_id']);
        $pipeDriveRelationships = $this->orgRelationshipsService->mapRelationshipData($organizationsExist, $pipeDriveRelationships, ['org_id','rel_owner_org_id','rel_linked_org_id']);
        //TODO: Performance notice!
        try {
            DB::beginTransaction();
            $result = $this->orgRelPipeDriveService->create($pipeDriveRelationships);
            if (!empty($result['errors'])) {
                return $this->failResponse($result['errors']);
            }
            foreach($localRelationships as $localRelationship) {
                OrganizationRelationship::create($localRelationship);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->failResponse($e->getMessage());
        }
        //Prepare response of relationships
        $responseRelationship = [];
        foreach($result['data'] as $relationshipData) {
            $responseRelationship[] = $relationshipData['data'];
        }
        return $this->successResponse($responseRelationship);

    }

    /**
     * Delete all organizationRelationships from local database and PIPEDRIVE
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAll()
    {
        try {
            DB::beginTransaction();
            $relationShips = OrganizationRelationship::all();
            foreach($relationShips as $relationShip) {
                $relationShip->delete();
            }
            $organizations = $this->orgPipeDriveService->getAll();
            if ($organizations['success'] === true && !empty($organizations['data'])) {
                foreach($organizations['data'] as $organization) {
                    $relationShips =  $this->orgRelPipeDriveService->getAll($organization['id']);
                    if ($relationShips['success'] === true && !empty($relationShips['data'])) {
                        foreach($relationShips['data'] as $relationShip) {
                            $this->orgRelPipeDriveService->delete($relationShip['id']);
                        }
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->failResponse($e->getMessage());
        }

        return $this->successResponse(['message'=>'All organization relationships were deleted']);
    }

    /**
     * Iterate over post data and create different array for storing data in PIPEDRIVE and local database
     * @param $data
     * @param $orgs
     * @param $pipeDriveRelationships
     * @param $localRelationships
     * @throws \Exception
     */
    private function parseRelationships($data, &$orgs, &$pipeDriveRelationships, &$localRelationships)
    {
        if (isset($data['daughters']) && !empty($data['daughters'])) {
            foreach($data['daughters'] as $daughterData) {
                if (!isset($daughterData['org_name'])) {
                    throw new \Exception('Daughter object must have org_name');
                }
                array_push($pipeDriveRelationships, [
                    'org_id' => $data['org_name'],
                    'type'=>'parent',
                    'rel_owner_org_id' => $data['org_name'],
                    'rel_linked_org_id' => $daughterData['org_name']
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