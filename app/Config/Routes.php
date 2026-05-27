<?php

declare(strict_types=1);

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public routes
$routes->get('/', 'Home::index');
$routes->match(['get', 'post'], 'login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

// Protected routes
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('dashboard', 'AuthController::dashboard');

    // Profile & Support (all roles)
    $routes->get('profile', 'ProfileController::index');
    $routes->post('profile', 'ProfileController::update');
    $routes->get('support', 'SupportController::index');

    // Reports (manager + superadmin)
    $routes->get('reports', 'ReportsController::index');
    $routes->get('reports/export', 'ReportsController::export');

    // User Management (superadmin only)
    $routes->get('users', 'UserController::index');
    $routes->get('users/create', 'UserController::create');
    $routes->post('users', 'UserController::store');
    $routes->get('users/(:num)/edit', 'UserController::edit/$1');
    $routes->match(['post', 'put'], 'users/(:num)', 'UserController::update/$1');
    $routes->post('users/(:num)/delete', 'UserController::delete/$1');

    // Roles & Permissions (superadmin only)
    $routes->get('users/roles', 'RolesController::index');

    // Settings (superadmin only)
    $routes->get('settings', 'SettingsController::index');
    $routes->get('settings/audit', 'SettingsController::audit');

    // Resource group for products (Member 3)
    $routes->get('products/low-stock', 'ProductController::lowStock');
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
