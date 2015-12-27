<?php

$app->get('/', [
    'as'=>'organizations.index',
    'uses' => 'OrganizationsController@index'
]);

$app->get('/{id}',[
    'as' => 'organizations.getOne',
    'uses' => 'OrganizationsController@getOne'
]);

$app->post('/',[
    'as' => 'organizations.create',
    'uses' => 'OrganizationsController@create'
]);

$app->put('/{id}',[
    'as' => 'organizations.update',
    'uses' => 'OrganizationsController@update'
]);

$app->delete('/{id}',[
    'as' => 'organizations.delete',
    'uses' => 'OrganizationsController@delete'
]);