<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $data = [
                    'first_name' => 'pertama',
                    'last_name'    => 'kedua',
                    'email'    => 'contoh@gmail.com',
                    'password'    => password_hash('12345', PASSWORD_DEFAULT),
                    'username'    => 'username',
                    'role_id'    => '1',
                    'is_verified'    => 'active',
                    'create_at'    => time(),
                    'update_at'    => time()
                ];

                // Using Query Builder
                $this->db->table('users')->insert($data);
    }
}
