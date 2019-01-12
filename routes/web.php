<?php

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
    return "Api page for a project";
});

$router->get('/api', function () {
    return "Version 1.0 Created by Matic Lahajnar";
});

$router->post('/api/login', "AuthController@login");
$router->post('/api/register', "AuthController@register");
$router->get('/api/getAllRadars', "RadarController@getAllRadars");

