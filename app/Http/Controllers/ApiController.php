<?php

namespace App\Http\Controllers;

use App\Traits\ResponseJsonTrait;
use App;

/**
 * Class ApiController
 * @package App\Http\Controllers
 */
class ApiController extends Controller
{
    use ResponseJsonTrait;

    public function clearAll()
    {
        App::make('App\Http\Controllers\OrganizationsController')->deleteAll();
        App::make('App\Http\Controllers\OrganizationRelationshipsController')->deleteAll();
        return $this->successResponse(['message' => 'All data has been removed']);
    }
}