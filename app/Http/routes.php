<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$app->get('/api/v1/test',function () use ($app) {
    return ['success'=>true, 'message'=>'api works'];
});

$app->group(['namespace' => 'App\Http\Controllers', 'prefix' => '/api/v1/organizations'], function ($app) {
    require __DIR__.'/routers/organizations.php';
});

$app->group(['namespace' => 'App\Http\Controllers', 'prefix' => '/api/v1/organizationRelationships'], function ($app) {
    require __DIR__.'/routers/organizationRelationships.php';
});
