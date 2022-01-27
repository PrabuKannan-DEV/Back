<?php

use App\Http\Controllers\CustomerController;

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

$router->get('/customers','CustomerController@index');
$router->post('/customers','CustomerController@store');
$router->get('/customers/{customer}','CustomerController@show');

$router->get('/schemes','SchemeController@index');
$router->post('/schemes','SchemeController@store');
$router->get('/schemes/{scheme}','SchemeController@show');

$router->get('/users/{user}/deposits','EnrollmentController@index');
$router->get('/deposits','EnrollmentController@store');
$router->get('/enrollments/{enrollment}/withdraw','EnrollmentController@withdraw');
$router->get('/enrollments/{enrollment}','EnrollmentController@show');
