<?php

namespace  App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PipeDrive\PipeDriveOrganization;
use Validator;
use DB;

/**
 * Class OrganizationRelationshipsController
 * @package App\Http\Controllers
 */
class OrganizationRelationshipsController extends ApiController
{

    private $orgService;

    public function __construct(PipeDriveOrganization $orgService)
    {
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

        $companies = [];
        $pipeDriveRelationships = [];
        $localRelationships = [];
        //Step one: go through the data and create three arrays
        array_push($companies, $request->input('org_name'));
        $this->parseRelationships($request->all(), $companies, $pipeDriveRelationships, $localRelationships);
        die(var_dump($localRelationships));

    }

    private function parseRelationships($data, &$companies, &$pipeDriveRelationships, &$localRelationships)
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

                array_push($companies, $daughterData['org_name']);

                if (isset($daughterData['daughters']) && !empty($daughterData['daughters'])) {
                    $this->parseRelationships($daughterData,  $companies, $pipeDriveRelationships, $localRelationships);
                }

            }
        }
    }
}