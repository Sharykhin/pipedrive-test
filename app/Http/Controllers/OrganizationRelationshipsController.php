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
    }
}