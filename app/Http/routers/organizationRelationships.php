<?php

$app->post('/', [
    'middleware' => 'accept',
    'as'=>'organizationRelationships.create',
    'uses' => 'OrganizationRelationshipsController@create'
]);

$app->delete('/', [
    'as' => 'organizationRelationships.deleteAll',
    'uses' => 'OrganizationRelationshipsController@deleteAll'
]);

$app->get('/',[
    'as' => 'organizationRelationships.index',
    'uses' => 'OrganizationRelationshipsController@index'
]);
