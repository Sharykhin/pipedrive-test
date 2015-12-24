<?php

$app->get('/test',[
    'as' => 'test',
    'uses' => 'ApiController@test'
]);