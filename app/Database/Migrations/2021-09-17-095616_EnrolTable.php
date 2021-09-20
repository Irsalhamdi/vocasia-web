<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EnrolTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' > true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'course_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'payment_id' => [
                'type' => 'INT',
            ],
            'create_at' => [
                'type' => 'DATETIME',
            ],
            'update_at' => [
                'type' => 'DATETIME',
            ],
            'last_modified' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'finish_date' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id');
        $this->forge->addForeignKey('course_id', 'courses', 'id');
        $this->forge->addForeignKey('payment_id', 'payment', 'id_payment');
        $this->forge->createTable('enrol');
    }

    public function down()
    {
        $this->forge->dropTable('enrol', true);
    }
}
