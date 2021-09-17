<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ExperienceInstructorTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'users_id' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'experience' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'content' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'audience' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('users_id', 'users', 'id');
        $this->forge->createTable('experience_instructor');
    }

    public function down()
    {
        $this->forge->dropTable('experience_instructor', true);
    }
}
