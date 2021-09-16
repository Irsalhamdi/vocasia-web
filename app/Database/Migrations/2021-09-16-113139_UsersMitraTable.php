<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UsersMitraTable extends Migration
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
            'number_code' => [
                'type' => 'VARCHAR',
                'constraint' => 255,

            ],
            'collage' => [
                'type' => 'VARCHAR',
                'constraint' => 255,

            ],
            'major' => [
                'type' => 'VARCHAR',
                'constraint' => 255,

            ],
            'mitra_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,

            ],
            'create_at' => [
                'type' => 'DATETIME'
            ],
            'update_at' => [
                'type' => 'DATETIME'
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_user', 'users', 'id');
        $this->forge->createTable('users_mitra');
    }

    public function down()
    {
        $this->forge->dropTable('users_mitra');
    }
}
