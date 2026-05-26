<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login()
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to(site_url('dashboard'));
        }

        if ($this->request->is('post')) {
            $rules = [
                'email'    => 'required|valid_email',
                'password' => 'required'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('error', 'Invalid input.');
            }

            $userModel = new UserModel();
            $user = $userModel->where('email', $this->request->getPost('email'))
                              ->where('is_active', 1)
                              ->first();

            if ($user && password_verify((string) $this->request->getPost('password'), $user['password_hash'])) {
                $fullName = !empty($user['firstname'])
                    ? $user['firstname'] . ' ' . $user['lastname']
                    : $user['name'];

                session()->set([
                    'user_id'      => $user['id'],
                    'name'         => $fullName,
                    'firstname'    => $user['firstname'] ?? '',
                    'email'        => $user['email'],
                    'role'         => $user['role'],
                    'is_logged_in' => true,
                ]);

                return redirect()->to(site_url('dashboard'));
            }

            return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
        }

        return view('auth/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('login'));
    }

    public function dashboard()
    {
        if (!session()->get('is_logged_in')) {
            return redirect()->to(site_url('login'));
        }

        $role = session()->get('role');

        if ($role === 'superadmin') {
            return view('dashboard/superadmin');
        } elseif ($role === 'manager') {
            return view('dashboard/manager');
        } elseif ($role === 'staff') {
            return view('dashboard/staff');
        }

        return redirect()->to(site_url('login'))->with('error', 'Invalid role.');
    }
}
