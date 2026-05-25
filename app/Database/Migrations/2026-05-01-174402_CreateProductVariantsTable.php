<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductVariantsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],

            'product_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],

            'size' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],

            'color' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],

            'sku' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => true,
            ],

            'stock_quantity' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],

            'price_modifier' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        // Primary Key
        $this->forge->addKey('id', true);

        // Index for FK performance
        $this->forge->addKey('product_id');

        // Foreign Key: products → variants
        $this->forge->addForeignKey(
            'product_id',
            'products',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('product_variants');
    }

    public function down()
    {
        $this->forge->dropTable('product_variants');
    }
}