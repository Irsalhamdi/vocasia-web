<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CoursesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'title' => 'title',
            'short_description' => 'title',
            'description' => 'title',
            'bio_instructor' => 'title',
            'bio_status' => '1',
            'outcomes' => 'title',
            'language' => 'title',
            'category_id' => '1',
            'sub_category_id' => '1',
            'section' => 'title',
            'requirement' => 'title',
            'price' => '2000',
            'discount_flag' => '10',
            'discount_price' => '10',
            'level_course' => 'title',
            'user_id' => '1',
            'thumbnail' => 'title',
            'video_url' => 'title',
            'visibility' => 'title',
            'is_top_course' => 'title',
            'is_admin' => '1',
            'status_course' => 'pending',
            'course_overview_provider' => 'title',
            'meta_keyword' => 'title',
            'meta_description' => 'title',
            'is_free_course' => '1',
            'is_prakerja' => '1',
            'instructor_revenue' => '1',
            'create_at' => time(),
            'update_at' => time(),
        ];

        // Using Query Builder
        $this->db->table('courses')->insert($data);
    }
}
