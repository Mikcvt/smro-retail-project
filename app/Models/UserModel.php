<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = [
        'firstname',
        'lastname',
        'name',
        'email',
        'password_hash',
        'role',
        'is_active',
        'age',
        'contact_no',
        'address',
        'profile_image',
    ];

    /**
     * Validation Rules
     */
    protected $validationRules = [
        'name' => [
            'rules' => 'required|min_length[2]|max_length[100]',
            'errors' => [
                'required' => 'Name is required',
                'min_length' => 'Name must be at least 2 characters',
                'max_length' => 'Name cannot exceed 100 characters',
            ],
        ],

        'email' => [
            'rules' => 'required|valid_email|is_unique[users.email]',
            'errors' => [
                'required' => 'Email is required',
                'valid_email' => 'Invalid email format',
                'is_unique' => 'Email already exists',
            ],
        ],

        'password_hash' => [
            'rules' => 'required|min_length[6]',
            'errors' => [
                'required' => 'Password is required',
                'min_length' => 'Password must be at least 6 characters',
            ],
        ],

        'role' => [
            'rules' => 'required|in_list[superadmin,manager,staff]',
            'errors' => [
                'required' => 'Role is required',
                'in_list' => 'Invalid role selected',
            ],
        ],
    ];

    protected $beforeInsert = ['hashPassword'];

    /**
     * Automatically hash password before saving
     */
    protected function hashPassword(array $data): array
    {
        if (!isset($data['data']['password_hash'])) {
            return $data;
        }

        $data['data']['password_hash'] = password_hash(
            $data['data']['password_hash'],
            PASSWORD_BCRYPT
        );

        return $data;
    }
}