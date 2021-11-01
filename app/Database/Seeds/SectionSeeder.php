<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SectionSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'course_id' => '1',
            'title' => 'title',
            'order' => 'order',
        ];

        $this->db->table('section')->insert($data);
    }
}
