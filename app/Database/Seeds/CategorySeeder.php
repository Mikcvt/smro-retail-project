<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'T-Shirts',
                'description' => 'Casual and printed tops',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Hoodies',
                'description' => 'Sweaters and hooded apparel',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Pants',
                'description' => 'Jeans, trousers, and joggers',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Shoes',
                'description' => 'Footwear for all occasions',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Accessories',
                'description' => 'Bags, caps, belts, and more',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('categories')->insertBatch($data);
    }
}