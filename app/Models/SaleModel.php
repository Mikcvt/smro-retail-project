<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class SaleModel extends Model
{
    protected $table = 'sales';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $useTimestamps = false;

    protected $allowedFields = [
        'user_id',
        'reference_no',
        'total_amount',
        'status',
        'notes',
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

        'reference_no' => [
            'rules' => 'required|max_length[50]',
            'errors' => [
                'required' => 'Reference number required',
                'max_length' => 'Reference too long',
            ],
        ],

        'total_amount' => [
            'rules' => 'required|decimal|greater_than_equal_to[0]',
        ],

        'status' => [
            'rules' => 'required|in_list[completed,returned,void]',
        ],
    ];
}