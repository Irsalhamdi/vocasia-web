<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $data = [
                    'code_category' => 'kode1',
                    'name_category'    => 'nama1',
                    'parent_category'    => 'parent1',
                    'slug_category'    => url_title('slug category coba','_'),
                    'font_awesome_class'    => 'font awesome',
                    'thumbnail'    => 'thumbnail',
                    'create_at'    => time(),
                    'update_at'    => time()
                ];

                // Using Query Builder
                $this->db->table('category')->insert($data);
    }
}
