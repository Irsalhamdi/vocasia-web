<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CoursesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'short_description' => [
                'type' => 'LONGTEXT',
            ],
            'description' => [
                'type' => 'LONGTEXT',
            ],
            'bio_instructor' => [
                'type' => 'LONGTEXT',
            ],
            'bio_status' => [
                'type' => 'INT',
            ],
            'outcomes' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'language' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'category_id' => [
                'type' => 'INT',
            ],
            'sub_category_id' => [
                'type' => 'INT',
            ],
            'section' => [
                'type' => 'LONGTEXT',
            ],
            'requirement' => [
                'type' => 'LONGTEXT',
            ],
            'price' => [
                'type' => 'DOUBLE',
            ],
            'discount_flag' => [
                'type' => 'INT',
            ],
            'discount_price' => [
                'type' => 'INT',
            ],
            'level_course' => [
                'type' => 'VARCHAR',
                'constraint' => 50

            ],
            'user_id' => [
                'type' => 'INT',
            ],
            'thumbnail' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'video_url' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'visibility' => [
                'type' => 'INT',
            ],
            'is_top_course' => [
                'type' => 'INT',
            ],
            'is_admin' => [
                'type' => 'INT',
            ],
            'status_course' => [
                'type' => 'ENUM',
                'constraint' => ['publish', 'prepublish', 'pending'],
                'default' => 'pending'
            ],
            'course_overview_provider' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'meta_keyword' => [
                'type' => 'LONGTEXT',
            ],
            'meta_description' => [
                'type' => 'LONGTEXT',
            ],
            'is_free_course' => [
                'type' => 'INT',
            ],
            'is_prakerja' => [
                'type' => 'INT',
            ],
            'instructor_revenue' => [
                'type' => 'INT',
            ],
            'create_at' => [
                'type' => 'INT',
            ],
            'update_at' => [
                'type' => 'INT',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id');
        $this->forge->addForeignKey('category_id', 'category', 'id');
        $this->forge->createTable('courses', true);
    }

    public function down()
    {
        $this->forge->dropTable('courses', true);
    }
}
