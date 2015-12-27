<?php

namespace App\Http\Controllers;

use App\Traits\ResponseJsonTrait;

/**
 * Class ApiController
 * @package App\Http\Controllers
 */
class ApiController extends Controller
{
    use ResponseJsonTrait;

    public function test()
    {
        return $this->successResponse(['user_id'=>12]);
    }

}