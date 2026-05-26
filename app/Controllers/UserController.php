<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\UserModel;

class UserController extends BaseController
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
        $userModel = new UserModel();
        $users = $userModel->where('role !=', 'superadmin')->orderBy('created_at', 'DESC')->findAll();
        return view('users/index', ['users' => $users]);
    }

    public function create(): string
    {
        $this->requireSuperAdmin();
        return view('users/create');
    }

    public function store()
    {
        $this->requireSuperAdmin();

        $rules = [
            'firstname'  => 'required|min_length[2]|max_length[100]',
            'lastname'   => 'required|min_length[2]|max_length[100]',
            'email'      => 'required|valid_email|is_unique[users.email]',
            'role'       => 'required|in_list[manager,staff]',
            'password'   => 'required|min_length[8]',
            'age'        => 'permit_empty|integer|greater_than[17]|less_than[100]',
            'contact_no' => 'permit_empty|max_length[20]',
            'address'    => 'permit_empty|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $firstname = $this->request->getPost('firstname');
        $lastname  = $this->request->getPost('lastname');

        $userModel = new UserModel();
        $userModel->insert([
            'firstname'     => $firstname,
            'lastname'      => $lastname,
            'name'          => $firstname . ' ' . $lastname,
            'email'         => $this->request->getPost('email'),
            'password_hash' => $this->request->getPost('password'),
            'role'          => $this->request->getPost('role'),
            'age'           => $this->request->getPost('age') ?: null,
            'contact_no'    => $this->request->getPost('contact_no') ?: null,
            'address'       => $this->request->getPost('address') ?: null,
            'is_active'     => 1,
        ]);

        return redirect()->to(site_url('users'))->with('success', 'Account created successfully.');
    }

    public function edit(int $id): string
    {
        $this->requireSuperAdmin();
        $userModel = new UserModel();
        $user = $userModel->find($id);
        if (!$user) {
            return redirect()->to(site_url('users'))->with('error', 'User not found.');
        }
        return view('users/edit', ['user' => $user]);
    }

    public function update(int $id)
    {
        $this->requireSuperAdmin();

        $userModel = new UserModel();
        $user = $userModel->find($id);
        if (!$user) {
            return redirect()->to(site_url('users'))->with('error', 'User not found.');
        }

        $rules = [
            'firstname'  => 'required|min_length[2]|max_length[100]',
            'lastname'   => 'required|min_length[2]|max_length[100]',
            'email'      => "required|valid_email|is_unique[users.email,id,{$id}]",
            'role'       => 'required|in_list[manager,staff]',
            'age'        => 'permit_empty|integer|greater_than[17]|less_than[100]',
            'contact_no' => 'permit_empty|max_length[20]',
            'address'    => 'permit_empty|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $firstname = $this->request->getPost('firstname');
        $lastname  = $this->request->getPost('lastname');

        $data = [
            'firstname'  => $firstname,
            'lastname'   => $lastname,
            'name'       => $firstname . ' ' . $lastname,
            'email'      => $this->request->getPost('email'),
            'role'       => $this->request->getPost('role'),
            'age'        => $this->request->getPost('age') ?: null,
            'contact_no' => $this->request->getPost('contact_no') ?: null,
            'address'    => $this->request->getPost('address') ?: null,
            'is_active'  => $this->request->getPost('is_active') ? 1 : 0,
        ];

        $newPassword = $this->request->getPost('password');
        if (!empty($newPassword)) {
            $data['password_hash'] = $newPassword;
        }

        $userModel->update($id, $data);
        return redirect()->to(site_url('users'))->with('success', 'Account updated successfully.');
    }

    public function delete(int $id)
    {
        $this->requireSuperAdmin();
        $userModel = new UserModel();
        $userModel->delete($id);
        return redirect()->to(site_url('users'))->with('success', 'Account deleted.');
    }
}
