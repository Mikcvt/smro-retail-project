<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@smro.com',
                'password_hash' => password_hash('admin123', PASSWORD_BCRYPT),
                'role' => 'superadmin',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'name' => 'Manager One',
                'email' => 'manager1@smro.com',
                'password_hash' => password_hash('manager123', PASSWORD_BCRYPT),
                'role' => 'manager',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'name' => 'Manager Two',
                'email' => 'manager2@smro.com',
                'password_hash' => password_hash('manager123', PASSWORD_BCRYPT),
                'role' => 'manager',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'name' => 'Staff One',
                'email' => 'staff1@smro.com',
                'password_hash' => password_hash('staff123', PASSWORD_BCRYPT),
                'role' => 'staff',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'name' => 'Staff Two',
                'email' => 'staff2@smro.com',
                'password_hash' => password_hash('staff123', PASSWORD_BCRYPT),
                'role' => 'staff',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'name' => 'Staff Three',
                'email' => 'staff3@smro.com',
                'password_hash' => password_hash('staff123', PASSWORD_BCRYPT),
                'role' => 'staff',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}