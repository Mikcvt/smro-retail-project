<?php

declare(strict_types=1);

namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;

class RolesController extends BaseController
{
    /**
     * Display Roles & Permissions management placeholder
     * Only accessible by superadmin
     */
    public function index(): string|RedirectResponse
    {
        $role = session('role');
        if ($role !== 'superadmin') {
            session()->setFlashdata('error', 'Access denied.');
            return redirect()->to(site_url('dashboard'));
        }

        // Simple scaffold data for the view — extend as needed
        $roles = [
            'superadmin' => [
                'label' => 'SuperAdmin',
                'description' => 'Full access to all system features',
            ],
            'manager' => [
                'label' => 'Manager',
                'description' => 'Manage inventory, sales, returns, and reports',
            ],
            'staff' => [
                'label' => 'Staff',
                'description' => 'Process sales and view sales history',
            ],
        ];

        return view('users/roles', compact('roles'));
    }
}
