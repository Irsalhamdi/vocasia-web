<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class LessonTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'duration' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'course_id' => [
                'type' => 'INT',
            ],
            'section_id' => [
                'type' => 'INT',
            ],
            'video_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'video_url' => [
                'type' => 'TEXT',

            ],
            'lesson_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50

            ],
            'attachment' => [
                'type' => 'VARCHAR',
                'constraint' => 255

            ],
            'attachment_type' => [
                'type' => 'VARCHAR',
                'constraint' => 255

            ],
            'summary' => [
                'type' => 'VARCHAR',
                'constraint' => 255

            ],
            'is_skip' => [
                'type' => 'INT',

            ],
            'order' => [
                'type' => 'INT',

            ],
            'video_type_for_mobile' => [
                'type' => 'VARCHAR',
                'constraint' => 255

            ],
            'video_url_for_mobile' => [
                'type' => 'VARCHAR',
                'constraint' => 255

            ],
            'duration_for_mobile' => [
                'type' => 'VARCHAR',
                'constraint' => 255

            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('course_id', 'courses', 'id');
        $this->forge->createTable('lesson');
    }

    public function down()
    {
        $this->forge->dropTable('lesson', true);
    }
}
