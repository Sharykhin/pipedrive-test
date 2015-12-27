<?php


$app->get('/test',function () use ($app) {
    return ['success'=>true, 'message'=>'api works'];
});



