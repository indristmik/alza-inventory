<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'          => 'Admin Gudang',
                'username'      => 'admin',
                'password_hash' => password_hash('admin123', PASSWORD_BCRYPT),
                'role'          => 'admin',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'name'          => 'Owner Toko',
                'username'      => 'owner',
                'password_hash' => password_hash('owner123', PASSWORD_BCRYPT),
                'role'          => 'owner',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ]
        ];

        // Memasukkan data ke tabel users
        $this->db->table('users')->insertBatch($data);
    }
}