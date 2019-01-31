<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\UserController;

//general routes
$api->post('auth/authorize', [
    'uses' => AuthenticationController::class . '@authenticate',
    'as' => 'sign_in'
]);

$api->group(['middleware' => 'api.auth',  'prefix' => 'auth'], function () use ($api) {
    $api->get('/logout', [
        'uses' => AuthenticationController::class . '@logout'
    ]);
    $api->get('/refresh', [
        'uses' => AuthenticationController::class . '@refresh'
    ]);
    $api->get('/payload', [
        'uses' => AuthenticationController::class . '@payload'
    ]);

});

$api->group(['middleware' => 'api.auth',  'prefix' => 'user'], function () use ($api) {
    $api->get('/{id}', [
        'uses' => UserController::class . '@get'
    ]);
    $api->post('/', [
        'uses' => UserController::class . '@post'
    ]);
    $api->put('/{id}', [
        'uses' => UserController::class . '@put'
    ]);
    $api->delete('/', [
        'uses' => UserController::class . '@delete'
    ]);

});

$api->get('/', function () use ($app) {
    return response()->json([
        "Project Name" => env("APP_NAME"),
        "API version" => env("API_VERSION"),
        "Lumen" => $app->version(),
        "PHP" => phpversion()
    ]);
});