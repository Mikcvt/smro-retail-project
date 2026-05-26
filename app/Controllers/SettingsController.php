<?php

declare(strict_types=1);

namespace App\Controllers;

class SettingsController extends BaseController
{
    private function requireSuperAdmin(): void
    {
        if (session('role') !== 'superadmin') {
            redirect()->to(site_url('dashboard'))->with('error', 'Unauthorized.')->send();
            exit;
        }
    }

    public function index(): string
    {
        $this->requireSuperAdmin();
        return view('settings/index');
    }

    public function audit(): string
    {
        $this->requireSuperAdmin();
        return view('settings/audit');
    }
}
