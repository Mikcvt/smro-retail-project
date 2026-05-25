<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSalesAndSaleItemsTables extends Migration
{
    public function up()
    {
        /**
         * =========================
         * SALES TABLE (HEADER)
         * =========================
         */
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],

            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],

            'reference_no' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => true,
            ],

            'total_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],

            'status' => [
                'type' => 'ENUM',
                'constraint' => ['completed', 'returned', 'void'],
                'default' => 'completed',
            ],

            'notes' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');

        $this->forge->addForeignKey(
            'user_id',
            'users',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->createTable('sales');

        /**
         * =========================
         * SALE ITEMS TABLE (DETAILS)
         * =========================
         */
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],

            'sale_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],

            'variant_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],

            'quantity' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 1,
            ],

            'unit_price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],

            'subtotal' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('sale_id');
        $this->forge->addKey('variant_id');

        $this->forge->addForeignKey(
            'sale_id',
            'sales',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'variant_id',
            'product_variants',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->createTable('sale_items');
    }

    public function down()
    {
        $this->forge->dropTable('sale_items');
        $this->forge->dropTable('sales');
    }
}