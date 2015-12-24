<?php
$app->group(['prefix' => '/api/v1', 'namespace' => 'App\Http\Controllers'], function ($app) {
    $app->get('/test', [
        'as' => 'test',
        'uses' => 'ApiController@test'
    ]);
});