<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReturnsAndReturnItemsTables extends Migration
{
    public function up()
    {
        /**
         * =========================
         * RETURNS TABLE (HEADER)
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

            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],

            'reason' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],

            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
                'default' => 'pending',
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('sale_id');
        $this->forge->addKey('user_id');

        $this->forge->addForeignKey(
            'sale_id',
            'sales',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'user_id',
            'users',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->createTable('returns');

        /**
         * =========================
         * RETURN ITEMS TABLE (DETAILS)
         * =========================
         */
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],

            'return_id' => [
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

            'reason' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('return_id');
        $this->forge->addKey('variant_id');

        $this->forge->addForeignKey(
            'return_id',
            'returns',
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

        $this->forge->createTable('return_items');
    }

    public function down()
    {
        $this->forge->dropTable('return_items');
        $this->forge->dropTable('returns');
    }
}