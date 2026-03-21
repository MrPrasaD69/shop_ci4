<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->group('',['filter'=>'auth'], function($routes){
    $routes->get('/dashboard','HomeController::dashboard');
    $routes->get('/logout','LoginController::logout');
});

$routes->group('',['filter'=>'guest'],function($routes){
    $routes->get('/','LoginController::index');
    $routes->get('/login','LoginController::index');
    $routes->post('/attemptLogin','LoginController::attemptLogin');
});
