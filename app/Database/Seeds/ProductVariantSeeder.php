<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    public function run()
    {
        $products = $this->db->table('products')
            ->get()
            ->getResultArray();

        $sizes = ['S', 'M', 'L'];
        $colors = ['Black', 'White', 'Navy'];

        $data = [];

        foreach ($products as $product) {
            foreach ($sizes as $size) {
                foreach ($colors as $color) {

                    $sku = strtoupper(substr($product['name'], 0, 3))
                        . '-' . $size
                        . '-' . strtoupper(substr($color, 0, 3))
                        . '-' . $product['id'];

                    $data[] = [
                        'product_id' => $product['id'],
                        'size' => $size,
                        'color' => $color,
                        'sku' => $sku,
                        'stock_quantity' => rand(10, 50),
                        'price_modifier' => ($size === 'L') ? 20 : 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                }
            }
        }

        $this->db->table('product_variants')->insertBatch($data);
    }
}