<?php

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('signup', 'AuthController@signup');

    $router->post('signin', 'AuthController@signin');
});

$router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($router) {
    $router->get('post', 'PostController@index');
    $router->get('post/{id}', 'PostController@show');
    $router->post('post', 'PostController@store');
    $router->put('post/{id}', 'PostController@update');
    $router->delete('post/{id}', 'PostController@destroy');
    $router->post('post/{id}/like', 'PostController@like');
});



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

$router->get('/', function () use ($router) {
    return $router->app->version();
});
