<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RoleUserTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'role_name' => [
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
        $this->forge->createTable('role_user');
    }

    public function down()
    {
        $this->forge->dropTable('role_user', true);
    }
}
