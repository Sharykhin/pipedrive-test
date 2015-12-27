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
$app->group(['namespace' => 'App\Http\Controllers', 'prefix' => '/api/v1'], function ($app) {
    require __DIR__.'/routers/api.php';
});

$app->group(['namespace' => 'App\Http\Controllers', 'prefix' => '/api/v1/organizations'], function ($app) {
    require __DIR__.'/routers/organizations.php';
});
