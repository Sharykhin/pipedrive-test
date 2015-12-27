<?php

$app->post('/organizationRelationships', [
    'as'=>'organizationRelationships.create',
    'uses' => 'OrganizationRelationshipsController@create'
]);
