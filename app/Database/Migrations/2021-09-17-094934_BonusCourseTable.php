<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

use function PHPSTORM_META\map;

class BonusCourseTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'course_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'thumbnail' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'type_file' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'file' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'date_added' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'last_modified' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('course_id', 'courses', 'id');
        $this->forge->createTable('bonus_course');
    }

    public function down()
    {
        $this->forge->dropTable('bonus_course', true);
    }
}
