<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class ReturnModel extends Model
{
    protected $table = 'returns';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $useTimestamps = false;

    protected $allowedFields = [
        'sale_id',
        'user_id',
        'reason',
        'status',
        'created_at',
    ];

    protected $validationRules = [
        'sale_id' => [
            'rules' => 'required|is_not_unique[sales.id]',
            'errors' => [
                'required' => 'Sale is required',
                'is_not_unique' => 'Invalid sale reference',
            ],
        ],

        'user_id' => [
            'rules' => 'required|is_not_unique[users.id]',
            'errors' => [
                'required' => 'User is required',
                'is_not_unique' => 'Invalid user',
            ],
        ],

        'reason' => [
            'rules' => 'required|min_length[5]|max_length[255]',
        ],

        'status' => [
            'rules' => 'required|in_list[pending,approved,rejected]',
        ],
    ];
}