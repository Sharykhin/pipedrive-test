<?php

namespace  App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use App\Services\PipeDrive\PipeDriveOrganization;
use Validator;
use DB;

/**
 * Class OrganizationsController
 * @package App\Http\Controllers
 */
class OrganizationsController extends ApiController
{
    private $orgService;

    public function __construct(PipeDriveOrganization $orgService)
    {
        $this->orgService = $orgService;
    }

    /**
     * return all organizations
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $organizations = Organization::all();
        return $this->successResponse($organizations);
    }

    /**
     * Return organization by ID
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOne($id)
    {
        $organization  = Organization::find($id);
        if (!$organization) {
            return $this->modelNotFoundResponse('Organization not found.');
        }
        return $this->successResponse($organization);
    }

    /**
     * Create a new organization
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:organizations|max:255',
        ]);

        if ($validator->fails()) {
            return $this->failResponse($validator->errors());
        }

        try {
            DB::beginTransaction();
                $organization = Organization::create($request->all());
                $this->orgService->create($request->all());
            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
            return $this->failResponse($e->getMessage());
        }

        return $this->successResponse($organization);
    }

    /**
     * Update organization by ID. Pay attention that name is unique.
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'unique:organizations|max:255',
        ]);

        if ($validator->fails()) {
            return $this->failResponse($validator->errors());
        }

        try {
            DB::beginTransaction();
            $organization  = Organization::find($id);
            $name = $organization->name;
            $organization->name = $request->input('name');
            $organization->save();
            $result = $this->orgService->findOne($name);
            if ($result) {
                $this->orgService->update($request->all(), $result['id']);
            }
            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
            return $this->failResponse($e->getMessage());
        }


        return $this->successResponse($organization);
    }

    /**
     * Remove one organization by ID
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $organization  = Organization::find($id);
        if (!$organization) {
            return $this->modelNotFoundResponse('Organization not found.');
        }
        try {
            DB::beginTransaction();
            $name = $organization->name;
            $result = $this->orgService->findOne($name);
            if ($result) {
                $this->orgService->delete($result['id']);
            }
            $organization->delete();
            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
            return $this->failResponse($e->getMessage());
        }
        return $this->successResponse(['id'=>$organization->id]);
    }

    public function deleteAll()
    {
        try {
            DB::beginTransaction();
            $organizations = Organization::all();
            foreach($organizations as $organization) {
                $organization->delete();
            }
            $organizations = $this->orgService->getAll();
            if ($organizations['success'] === true && !empty($organizations['data'])) {
                foreach($organizations['data'] as $organization) {
                    $this->orgService->delete($organization['id']);
                }
            }
            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
            return $this->failResponse($e->getMessage());
        }

        return $this->successResponse(['message'=>'All organization were deleted']);
    }
}