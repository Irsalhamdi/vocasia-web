<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class QuestionTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'quiz_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'title' => [
                'type' => 'TEXT',
            ],
            'type' => [
                'type' => 'TEXT'
            ],
            'number_option' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'options' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'correct_answers' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'order' => [
                'type' => 'LONGTEXT'
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('quiz_id','lesson','id');
        $this->forge->createTable('question');
    }

    public function down()
    {
        $this->forge->dropTable('question', true);
    }
}
