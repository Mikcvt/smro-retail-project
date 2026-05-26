<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks temporarily
        $this->db->disableForeignKeyChecks();

        // Clear all existing users first
        $this->db->table('users')->truncate();

        // Re-enable foreign key checks
        $this->db->enableForeignKeyChecks();

        $this->db->table('users')->insert([
            'firstname'     => 'Super',
            'lastname'      => 'Admin',
            'name'          => 'Super Admin',
            'email'         => 'superadmin@smro.com',
            'password_hash' => password_hash('password', PASSWORD_BCRYPT),
            'role'          => 'superadmin',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);
    }
}
