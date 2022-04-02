<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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


$router->post('/login', 'AuthController@login');
$router->post('/register', 'AuthController@register');

$router->get('/gifts', 'GetDataNoAuth@index');
$router->get('/gifts/{id}', 'GetDataNoAuth@indexBy');
$router->post('/gifts', 'Master_Item@store');
$router->put('/gifts/{id}', 'Master_Item@update');
$router->patch('/gifts/{id}', 'Master_Item@update');
$router->delete('/gifts/{id}', 'Master_Item@delete');
$router->post('/gifts/{id}/redeem', 'Redeem@store');
$router->post('/gifts/{id}/rating', 'Reviews@store');