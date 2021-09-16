<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserDetailTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'id_user' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'biography' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'adress' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'bank_account_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'is_instructor' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_user', 'users', 'id');
        $this->forge->createTable('user_detail');
    }

    public function down()
    {
        $this->forge->dropTable('user_detail', true);
    }
}
