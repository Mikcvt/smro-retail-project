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
                'name' => 'Basic Cotton Tee',
                'description' => 'Comfortable everyday cotton shirt',
                'brand' => 'SMRO Basic',
                'base_price' => 299.00,
                'image_path' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'category_id' => $categoryMap['T-Shirts'],
                'name' => 'Graphic Street Tee',
                'description' => 'Urban-style printed shirt',
                'brand' => 'StreetWear',
                'base_price' => 399.00,
                'image_path' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'category_id' => $categoryMap['Hoodies'],
                'name' => 'Pullover Hoodie',
                'description' => 'Warm casual hoodie',
                'brand' => 'CozyFit',
                'base_price' => 899.00,
                'image_path' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'category_id' => $categoryMap['Hoodies'],
                'name' => 'Zip-up Hoodie',
                'description' => 'Full-zip lightweight hoodie',
                'brand' => 'UrbanWear',
                'base_price' => 999.00,
                'image_path' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'category_id' => $categoryMap['Pants'],
                'name' => 'Slim Fit Jeans',
                'description' => 'Modern slim jeans',
                'brand' => 'DenimCo',
                'base_price' => 799.00,
                'image_path' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'category_id' => $categoryMap['Pants'],
                'name' => 'Jogger Pants',
                'description' => 'Comfortable athletic joggers',
                'brand' => 'ActiveLife',
                'base_price' => 699.00,
                'image_path' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'category_id' => $categoryMap['Shoes'],
                'name' => 'Casual Sneakers',
                'description' => 'Everyday wear sneakers',
                'brand' => 'StepUp',
                'base_price' => 1299.00,
                'image_path' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'category_id' => $categoryMap['Shoes'],
                'name' => 'Running Shoes',
                'description' => 'Lightweight performance shoes',
                'brand' => 'RunFast',
                'base_price' => 1499.00,
                'image_path' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'category_id' => $categoryMap['Accessories'],
                'name' => 'Baseball Cap',
                'description' => 'Adjustable street cap',
                'brand' => 'HeadWear',
                'base_price' => 199.00,
                'image_path' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'category_id' => $categoryMap['Accessories'],
                'name' => 'Canvas Backpack',
                'description' => 'Durable everyday backpack',
                'brand' => 'CarryAll',
                'base_price' => 599.00,
                'image_path' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('products')->insertBatch($data);
    }
}