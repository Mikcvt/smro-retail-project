<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class ReturnItemModel extends Model
{
    protected $table = 'return_items';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $useTimestamps = false;

    protected $allowedFields = [
        'return_id',
        'variant_id',
        'quantity',
        'reason',
    ];

    protected $validationRules = [
        'return_id' => [
            'rules' => 'required|is_not_unique[returns.id]',
            'errors' => [
                'required' => 'Return is required',
                'is_not_unique' => 'Invalid return reference',
            ],
        ],

        'variant_id' => [
            'rules' => 'required|is_not_unique[product_variants.id]',
            'errors' => [
                'required' => 'Variant is required',
                'is_not_unique' => 'Invalid product variant',
            ],
        ],

        'quantity' => [
            'rules' => 'required|integer|greater_than[0]',
        ],

        'reason' => [
            'rules' => 'permit_empty|max_length[255]',
        ],
    ];
}