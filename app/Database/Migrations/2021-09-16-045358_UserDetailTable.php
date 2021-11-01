<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use phpDocumentor\Reflection\Types\Null_;

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
            'foto_profile' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'biography' => [
                'type' => 'LONGTEXT',
            ],
            'datebrith' => [
                'type' => 'DATE',
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
            'jenis_kel' => [
                'type' => 'ENUM',
                'constraint' => ['pria', 'wanita'],
                'default' => 'pria'
            ],
            'referal_code' => [
                'type' => 'VARCHAR',
                'constraint' => 60,
            ],
            [
                'create_at' => [
                    'type' => 'INT',
                ]
            ],
            [
                'update_at' => [
                    'type' => 'INT',
                ]
            ]
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
