<?php

declare(strict_types=1);

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public routes
$routes->get('/', static function () {
    if (session()->get('is_logged_in')) {
        return redirect()->to('/dashboard');
    }
    return redirect()->to('/login');
});
$routes->match(['get', 'post'], 'login', 'AuthController::login');
$routes->match(['get', 'post'], 'register', 'AuthController::register');
$routes->get('logout', 'AuthController::logout');

// Protected routes
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('dashboard', 'AuthController::dashboard');

    // Resource group for products (Member 3)
    $routes->resource('products', ['controller' => 'ProductController']);
    $routes->post('products/(:num)/stock', 'ProductController::adjustStock/$1');

    // Sales routes (Member 4)
    $routes->get('sales', 'SaleController::index');
    $routes->get('sales/create', 'SaleController::create');
    $routes->post('sales', 'SaleController::store');
    $routes->post('sales/(:num)/void', 'SaleController::void/$1');

    // Returns routes (Member 4)
    $routes->get('returns', 'ReturnController::index');
    $routes->get('returns/create', 'ReturnController::create');
    $routes->post('returns', 'ReturnController::store');
    $routes->post('returns/(:num)/approve', 'ReturnController::approve/$1');
    $routes->post('returns/(:num)/reject', 'ReturnController::reject/$1');

    // Internal AJAX lookup for return form
    $routes->get('api/sales/lookup', 'SaleController::lookup');
});

// API token generation — public, must be before the api_auth group (Member 4)
$routes->post('api/auth/token', 'ProductApiController::token');

// API routes — Bearer Token protected (Member 4)
$routes->group('api', ['filter' => 'api_auth'], static function ($routes) {
    $routes->get('products', 'ProductApiController::index');
    $routes->get('products/(:num)', 'ProductApiController::show/$1');
    $routes->get('products/(:num)/stock', 'ProductApiController::stock/$1');
});
