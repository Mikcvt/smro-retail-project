<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class SaleItemModel extends Model
{
    protected $table = 'sale_items';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $useTimestamps = false;

    protected $allowedFields = [
        'sale_id',
        'variant_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected $validationRules = [
        'sale_id' => 'required|is_not_unique[sales.id]',
        'variant_id' => 'required|is_not_unique[product_variants.id]',
        'quantity' => 'required|integer|greater_than[0]',
        'unit_price' => 'required|decimal',
        'subtotal' => 'required|decimal',
    ];
}