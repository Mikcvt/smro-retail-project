<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class ProductVariantModel extends Model
{
    protected $table            = 'product_variants';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'product_id',
        'size',
        'color',
        'sku',
        'stock_quantity',
        'price_modifier',
    ];
    protected $useTimestamps = true;
    protected $validationRules    = [
        'product_id'     => 'required|is_natural_no_zero',
        'sku'            => 'required|max_length[50]|is_unique[product_variants.sku]',
        'stock_quantity' => 'required|integer|greater_than_equal_to[0]',
    ];
}