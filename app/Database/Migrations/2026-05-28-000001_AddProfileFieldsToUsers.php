<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProfileFieldsToUsers extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('users', [
            'firstname' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'id',
            ],
            'lastname' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'firstname',
            ],
            'age' => [
                'type'       => 'TINYINT',
                'constraint' => 3,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'lastname',
            ],
            'contact_no' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'age',
            ],
            'address' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'contact_no',
            ],
            'profile_image' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'address',
            ],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('users', ['firstname', 'lastname', 'age', 'contact_no', 'address', 'profile_image']);
    }
}
