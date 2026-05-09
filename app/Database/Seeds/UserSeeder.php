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
                'name'          => 'Super Admin',
                'email'         => 'superadmin@smro.com',
                'password_hash' => password_hash('password123', PASSWORD_BCRYPT),
                'role'          => 'superadmin',
                'is_active'     => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'name'          => 'Store Manager',
                'email'         => 'manager@smro.com',
                'password_hash' => password_hash('password123', PASSWORD_BCRYPT),
                'role'          => 'manager',
                'is_active'     => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'name'          => 'Retail Staff',
                'email'         => 'staff@smro.com',
                'password_hash' => password_hash('password123', PASSWORD_BCRYPT),
                'role'          => 'staff',
                'is_active'     => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
