<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'category_id',
        'name',
        'description',
        'brand',
        'base_price',
        'image_path',
        'is_active',
    ];
    protected $useTimestamps = true;
    protected $validationRules    = [
        'name'        => 'required|min_length[3]|max_length[255]',
        'category_id' => 'required|is_natural_no_zero',
        'brand'       => 'required|max_length[100]',
        'base_price'  => 'required|decimal|greater_than[0]',
    ];
}