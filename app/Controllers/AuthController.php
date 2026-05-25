<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login()
    {
        if ($this->request->is('post')) {
            $rules = [
                'email'    => 'required|valid_email',
                'password' => 'required'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('error', 'Invalid input.');
            }

            $userModel = new UserModel();
            $user = $userModel->where('email', $this->request->getPost('email'))->first();

            if ($user && password_verify((string)$this->request->getPost('password'), $user['password_hash'])) {
                session()->set([
                    'user_id'      => $user['id'],
                    'name'         => $user['name'],
                    'email'        => $user['email'],
                    'role'         => $user['role'],
                    'is_logged_in' => true,
                ]);

                return redirect()->to('/dashboard');
            }

            return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
        }

        return view('auth/login');
    }

    public function register()
    {
        if ($this->request->is('post')) {
            $rules = [
                'name'             => 'required|min_length[2]|max_length[255]',
                'email'            => 'required|valid_email|is_unique[users.email]',
                'password'         => 'required|min_length[8]',
                'confirm_password' => 'required|matches[password]'
            ];

            if (!$this->validate($rules)) {
                return view('auth/register', ['validation' => $this->validator]);
            }

            $userModel = new UserModel();
            $data = [
                'name'          => $this->request->getPost('name'),
                'email'         => $this->request->getPost('email'),
                'password_hash' => $this->request->getPost('password'),
                'role'          => 'staff',
                'is_active'     => 1
            ];

            $userModel->insert($data);

            return redirect()->to('/login')->with('success', 'Registration successful. Please log in.');
        }

        return view('auth/register');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    public function dashboard()
    {
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/login');
        }

        $role = session()->get('role');

        if ($role === 'superadmin') {
            return view('dashboard/superadmin');
        } elseif ($role === 'manager') {
            return view('dashboard/manager');
        } elseif ($role === 'staff') {
            return view('dashboard/staff');
        }

        return redirect()->to('/login')->with('error', 'Invalid role.');
    }
}
