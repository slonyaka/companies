<?php

/** @var \Laravel\Lumen\Routing\Router $router */


$router->group(['prefix' => 'api/user'], function () use ($router) {
    $router->post('sign-in', 'AuthController@signIn');
    $router->post('register', 'AuthController@register');
    $router->post('recover-password', 'AuthController@recoverPassword');
    $router->post('update-password', 'AuthController@updatePassword');

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->get('companies', 'CompanyController@get');
        $router->post('companies', 'CompanyController@create');
    });
});
