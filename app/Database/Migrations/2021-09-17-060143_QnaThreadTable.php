<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class QnaThreadTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_qna' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'sender' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'title' => [
                'type' => 'TEXT',
            ],
            'quest' => [
                'type' => 'TEXT'
            ],
            'id_course' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'id_lesson' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'up' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'user_id_up' => [
                'type' => 'INT'
            ],
            'date_add' => [
                'type' => 'DATETIME'
            ],
            'status' => [
                'type' => 'INT',
                'constraint' => 1
            ],
        ]);

        $this->forge->addKey('id_qna', true);
        $this->forge->addForeignKey('sender');
        $this->forge->addForeignKey('id_course','course','id');
        $this->forge->addForeignKey('id_lesson','lesson','id');
        $this->forge->addForeignKey('user_id_up','users','id_users');
        $this->forge->createTable('qna_thread');
    }

    public function down()
    {
        $this->forge->dropTable('qna_thread', true);
    }
}
