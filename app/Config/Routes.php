<?php

declare(strict_types=1);

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public routes
$routes->get('/', 'Home::index');
$routes->match(['get', 'post'], 'login', 'AuthController::login');
$routes->match(['get', 'post'], 'register', 'AuthController::register');
$routes->get('logout', 'AuthController::logout');

// Protected routes
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('dashboard', 'AuthController::dashboard');

    // Resource group for products (Member 3)
    $routes->resource('products', ['controller' => 'ProductController']);

    // Route group for sales (Member 4)
    $routes->group('sales', static function ($routes) {
        // Sales routes will go here
    });

    // Route group for API with api_auth filter
    $routes->group('api', ['filter' => 'api_auth'], static function ($routes) {
        // API routes will go here
    });
});
