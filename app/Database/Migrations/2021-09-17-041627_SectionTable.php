<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SectionTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'course_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'order' => [
                'type' => 'LONGTEXT'
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('course_id', 'courses', 'id');
        $this->forge->createTable('section');
    }

    public function down()
    {
        $this->forge->dropTable('section', true);
    }
}
