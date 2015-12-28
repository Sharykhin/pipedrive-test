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
if (!defined('API_V1_URL')) {
    define('API_V1_URL','/api/v1');
}

$app->get(API_V1_URL . '/test',function () use ($app) {
    return ['success'=>true, 'message'=>'api works'];
});

$app->get(API_V1_URL . '/clearAll', [
    'as' => 'clearAll',
    'uses' => 'App\Http\Controllers\ApiController@clearAll'
]);

$app->group(['namespace' => 'App\Http\Controllers', 'prefix' => API_V1_URL . '/organizations'], function ($app) {
    require __DIR__.'/routers/organizations.php';
});

$app->group(['namespace' => 'App\Http\Controllers', 'prefix' => API_V1_URL . '/organizationRelationships'], function ($app) {
    require __DIR__.'/routers/organizationRelationships.php';
});
