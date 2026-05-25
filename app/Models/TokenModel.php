<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class TokenModel extends Model
{
    protected $table = 'tokens';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $useTimestamps = false;

    protected $allowedFields = [
        'user_id',
        'token',
        'expires_at',
        'created_at',
    ];

    protected $validationRules = [
        'user_id' => [
            'rules' => 'required|is_not_unique[users.id]',
            'errors' => [
                'required' => 'User is required',
                'is_not_unique' => 'Invalid user',
            ],
        ],

        'token' => [
            'rules' => 'required|min_length[32]|max_length[64]',
            'errors' => [
                'required' => 'Token is required',
                'min_length' => 'Token too short',
                'max_length' => 'Token too long',
            ],
        ],

        'expires_at' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Expiration date is required',
            ],
        ],
    ];
}