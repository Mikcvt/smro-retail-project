<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\UserModel;

class ProfileController extends BaseController
{
    public function index(): string
    {
        $userModel = new UserModel();
        $user = $userModel->find(session('user_id'));
        return view('profile/index', ['user' => $user]);
    }

    public function update()
    {
        $rules = [
            'firstname'  => 'required|min_length[2]|max_length[100]',
            'lastname'   => 'required|min_length[2]|max_length[100]',
            'age'        => 'permit_empty|integer|greater_than[17]|less_than[100]',
            'contact_no' => 'permit_empty|max_length[20]',
            'address'    => 'permit_empty|max_length[255]',
            'profile_image' => 'permit_empty|is_image[profile_image]|mime_in[profile_image,image/jpeg,image/png,image/webp]|max_size[profile_image,2048]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $user = $userModel->find(session('user_id'));

        $imagePath = $user['profile_image'] ?? null;
        $file = $this->request->getFile('profile_image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploadDir = FCPATH . 'uploads/profiles/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $newName = $file->getRandomName();
            $file->move($uploadDir, $newName);
            try {
                \Config\Services::image()
                    ->withFile($uploadDir . $newName)
                    ->fit(300, 300, 'center')
                    ->save($uploadDir . $newName);
            } catch (\Throwable $e) {
                // If image processing fails (missing GD/imagick), keep the uploaded file as-is.
            }
            if ($imagePath) {
                @unlink($uploadDir . $imagePath);
            }
            $imagePath = $newName;
        }

        $firstname = $this->request->getPost('firstname');
        $lastname  = $this->request->getPost('lastname');

        $data = [
            'firstname'     => $firstname,
            'lastname'      => $lastname,
            'name'          => $firstname . ' ' . $lastname,
            'age'           => $this->request->getPost('age') ?: null,
            'contact_no'    => $this->request->getPost('contact_no') ?: null,
            'address'       => $this->request->getPost('address') ?: null,
            'profile_image' => $imagePath,
        ];

        $newPassword = $this->request->getPost('password');
        if (!empty($newPassword)) {
            if (strlen($newPassword) < 8) {
                return redirect()->back()->withInput()->with('error', 'Password must be at least 8 characters.');
            }
            $data['password_hash'] = password_hash($newPassword, PASSWORD_BCRYPT);
        }

        $userModel->update(session('user_id'), $data);

        // Update session name
        session()->set('name', $firstname . ' ' . $lastname);

        return redirect()->to(site_url('profile'))->with('success', 'Profile updated successfully.');
    }
}
