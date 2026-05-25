<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

/**
 * ProductVariantModel
 *
 * Manages size/color/SKU variants and their stock quantities.
 * Member 3 — reference copy; defer to Member 2's version once merged.
 */
class ProductVariantModel extends Model
{
    protected $table            = 'product_variants';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = [
        'product_id',
        'size',
        'color',
        'sku',
        'stock_quantity',
        'price_modifier',
    ];

    protected $validationRules = [
        'product_id'     => 'required|integer',
        'size'           => 'required|max_length[10]',
        'color'          => 'required|max_length[50]',
        'sku'            => 'required|max_length[64]|is_unique[product_variants.sku,id,{id}]',
        'stock_quantity' => 'required|integer|greater_than_equal_to[0]',
        'price_modifier' => 'permit_empty|decimal',
    ];

    protected $validationMessages = [
        'sku' => [
            'is_unique' => 'This SKU already exists. Each variant must have a unique SKU.',
        ],
    ];

    protected $skipValidation = false;
}