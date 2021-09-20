<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NotificationTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_notif' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'user_id_sender' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'order_id' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'courses_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'section_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'can_not' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'date_add' => [
                'type' => 'TIMESTAMP',
            ],
            'status_not' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'text_not' => [
                'type' => 'TEXT',
            ],
            'status' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
        ]);
        $this->forge->addKey('id_notif', true);
        $this->forge->createTable('notification');
    }

    public function down()
    {
        $this->forge->dropTable('notification', true);
    }
}
