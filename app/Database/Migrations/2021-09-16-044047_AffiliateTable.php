<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AffiliateTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_affiliate' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'users_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'leader' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'co_leader' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'code_reff' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'create_at' => [
                'type' => 'DATETIME',
            ],
            'update_at' => [
                'type' => 'DATETIME',
            ],
            'is_active' => [
                'type' => 'INT',
                'constraint' => 1,
            ],
        ]);

        $this->forge->addKey('id_affiliate', true);
        $this->forge->createTable('affiliate');
    }

    public function down()
    {
        $this->forge->dropDatabase('affiliate', true);
    }

}
