<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LessonSeeder extends Seeder
{
    public function run()
    {
        $data = [
                    'title' => 'lesson',
                    'duration'=> 'lesson',
                    'course_id'=> '3',
                    'section_id'=> '5',
                    'video_type'=> 'lesson',
                    'video_url'=> 'lesson',
                    'lesson_type'=> 'lesson',
                    'attachment'=> 'lesson',
                    'attachment_type'=> 'lesson',
                    'summary'=> 'lesson',
                    'is_skip'=> '1',
                    'order'=> '1',
                    'video_type_for_mobile'=> 'lesson',
                    'video_url_for_mobile'=> 'lesson',
                    'duration_for_mobile'=>'lesson',
                ];

                // Using Query Builder
                $this->db->table('lesson')->insert($data);
    }
}
