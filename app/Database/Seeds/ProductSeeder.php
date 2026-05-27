<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Get category IDs dynamically (important for FK safety)
        $categories = $this->db->table('categories')
            ->get()
            ->getResultArray();

        $categoryMap = [];
        foreach ($categories as $cat) {
            $categoryMap[$cat['name']] = $cat['id'];
        }

        $data = [
            [
                'category_id' => $categoryMap['T-Shirts'],
                'name' => 'Minimalist Crewneck Tee',
                'description' => 'Soft premium cotton with a clean, modern silhouette.',
                'brand' => 'Asteria Studio',
                'base_price' => 549.00,
                'image_path' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'category_id' => $categoryMap['T-Shirts'],
                'name' => 'Signature Logo Tee',
                'description' => 'Limited edition streetwear tee with refined branding.',
                'brand' => 'Noir Atelier',
                'base_price' => 725.00,
                'image_path' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'category_id' => $categoryMap['Hoodies'],
                'name' => 'Fleece Oversized Hoodie',
                'description' => 'Structured, heavyweight fleece for premium comfort.',
                'brand' => 'LuxeStreet',
                'base_price' => 1699.00,
                'image_path' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'category_id' => $categoryMap['Hoodies'],
                'name' => 'Techwear Zip Hoodie',
                'description' => 'Water-resistant shell with streamlined utility details.',
                'brand' => 'Nebula',
                'base_price' => 1899.00,
                'image_path' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'category_id' => $categoryMap['Pants'],
                'name' => 'Tailored Chino Pants',
                'description' => 'Clean tapered fit with polished, wrinkle-resistant fabric.',
                'brand' => 'Monochrome',
                'base_price' => 1399.00,
                'image_path' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'category_id' => $categoryMap['Pants'],
                'name' => 'Premium Denim Jeans',
                'description' => 'High-rise denim with clean finish and comfortable stretch.',
                'brand' => 'Denim District',
                'base_price' => 1899.00,
                'image_path' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'category_id' => $categoryMap['Shoes'],
                'name' => 'Leather Derby Shoes',
                'description' => 'Classic polished leather footwear for modern tailoring.',
                'brand' => 'Crown Footwear',
                'base_price' => 2599.00,
                'image_path' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'category_id' => $categoryMap['Shoes'],
                'name' => 'Minimalist Slide Sneakers',
                'description' => 'Sleek low-profile sneaker with premium cushioning.',
                'brand' => 'Flux',
                'base_price' => 1849.00,
                'image_path' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'category_id' => $categoryMap['Accessories'],
                'name' => 'Premium Canvas Tote',
                'description' => 'Structured tote with reinforced straps and minimal branding.',
                'brand' => 'Canvas Co.',
                'base_price' => 749.00,
                'image_path' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'category_id' => $categoryMap['Accessories'],
                'name' => 'Signature Leather Belt',
                'description' => 'Vegetable-tanned belt with brushed metal hardware.',
                'brand' => 'Velour',
                'base_price' => 849.00,
                'image_path' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('products')->insertBatch($data);
    }
}