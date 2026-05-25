<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductsTable extends Migration
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

            'category_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],

            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
            ],

            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'brand' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],

            'base_price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],

            'image_path' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],

            'is_active' => [
                'type' => 'BOOLEAN',
                'default' => 1,
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
        $this->forge->addKey('category_id');

        // Foreign Key Constraint
        $this->forge->addForeignKey(
            'category_id',
            'categories',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('products');
    }

    public function down()
    {
        $this->forge->dropTable('products');
    }
}