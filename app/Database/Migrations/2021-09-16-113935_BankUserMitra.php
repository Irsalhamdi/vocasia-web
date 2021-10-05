<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BankUserMitra extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'id_user' => [
                'type' => 'INT',
                'constraint' => 11,

            ],
            'id_bank' => [
                'type' => 'INT',
                'constraint' => 11,

            ],
            'bank_account' => [
                'type' => 'VARCHAR',
                'constraint' => 255,

            ],
            'create_at' => [
                'type' => 'INT',

            ],
            'update_at' => [
                'type' => 'INT',

            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_user', 'users', 'id');
        $this->forge->createTable('bank_users');
    }

    public function down()
    {
        $this->forge->dropTable('bank_users', true);
    }
}
