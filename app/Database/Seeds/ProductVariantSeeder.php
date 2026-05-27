<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    public function run()
    {
        $products = $this->db->table('products')
            ->select('products.*, categories.name AS category_name')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->get()
            ->getResultArray();

        $data = [];

        foreach ($products as $product) {
            switch ($product['category_name']) {
                case 'Shoes':
                    $sizes = ['7', '8', '9', '10'];
                    $colors = ['White', 'Charcoal', 'Navy'];
                    break;
                case 'Accessories':
                    $sizes = ['One Size'];
                    $colors = ['Black', 'Sand', 'Olive'];
                    break;
                default:
                    $sizes = ['S', 'M', 'L', 'XL'];
                    $colors = ['Black', 'White', 'Gray', 'Olive'];
                    break;
            }

            foreach ($sizes as $size) {
                foreach ($colors as $color) {
                    $sku = strtoupper(str_replace(' ', '', substr($product['name'], 0, 3)))
                        . '-' . strtoupper(str_replace(' ', '', $size))
                        . '-' . strtoupper(substr(str_replace(' ', '', $color), 0, 3))
                        . '-' . $product['id'];

                    $priceModifier = 0;
                    if ($product['category_name'] !== 'Accessories') {
                        if ($size === 'L') {
                            $priceModifier += 150;
                        } elseif ($size === 'XL') {
                            $priceModifier += 250;
                        }
                    }

                    if ($color === 'White') {
                        $priceModifier += 25;
                    } elseif ($color === 'Olive' || $color === 'Sand') {
                        $priceModifier += 40;
                    }

                    if ($product['category_name'] === 'Shoes') {
                        if ($size === '9') {
                            $priceModifier += 80;
                        } elseif ($size === '10') {
                            $priceModifier += 150;
                        }
                    }

                    $data[] = [
                        'product_id' => $product['id'],
                        'size' => $size,
                        'color' => $color,
                        'sku' => $sku,
                        'stock_quantity' => rand(15, 80),
                        'price_modifier' => $priceModifier,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                }
            }
        }

        $this->db->table('product_variants')->insertBatch($data);
    }
}