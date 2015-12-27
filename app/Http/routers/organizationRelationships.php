<?php

$app->post('/', [
    'as'=>'organizationRelationships.create',
    'uses' => 'OrganizationRelationshipsController@create'
]);
