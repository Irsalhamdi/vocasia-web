<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BankTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'code_bank' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'create_at' => [
                'type' => 'DATETIME',
            ],
            'update_at' => [
                'type' => 'DATETIME',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('bank');
    }

    public function down()
    {
        $this->forge->dropTable('bank', true);
    }
}
