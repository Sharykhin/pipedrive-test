<?php
$app->group(['prefix' => '/api/v1', 'namespace' => 'App\Http\Controllers'], function ($app) {
    $app->get('/test', [
        'as' => 'test',
        'uses' => 'ApiController@test'
    ]);

    $app->get('/org', [
        'as'=>'org',
        'uses' => 'OrganizationsController@index'
    ]);
});