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

    /**
     * Validation Rules
     */
    protected $validationRules = [
        'category_id' => [
            'rules' => 'required|is_not_unique[categories.id]',
            'errors' => [
                'required' => 'Category is required',
                'is_not_unique' => 'Selected category does not exist',
            ],
        ],

        'name' => [
            'rules' => 'required|min_length[2]|max_length[150]',
            'errors' => [
                'required' => 'Product name is required',
                'min_length' => 'Product name too short',
                'max_length' => 'Product name too long',
            ],
        ],

        'base_price' => [
            'rules' => 'required|decimal|greater_than_equal_to[0]',
            'errors' => [
                'required' => 'Base price is required',
                'decimal' => 'Base price must be a valid number',
            ],
        ],
    ];

    /**
     * Get products with category name (JOIN example)
     */
    public function getProductsWithCategory()
    {
        return $this->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id')
            ->findAll();
    }

    /**
     * Get single product with category
     */
    public function getProductWithCategory(int $id)
    {
        return $this->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id')
            ->where('products.id', $id)
            ->first();
    }
}