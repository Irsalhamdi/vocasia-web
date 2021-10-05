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
            'user_id' => [
                'type' => 'INT',
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
                'type' => 'INT',
            ],
            'update_at' => [
                'type' => 'INT',
            ],
            'is_active' => [
                'type' => 'INT',
                'constraint' => 1,
            ],
        ]);

        $this->forge->addKey('id_affiliate', true);
        $this->forge->addForeignKey('user_id', 'users', 'id');
        $this->forge->createTable('affiliate');
    }

    public function down()
    {
        $this->forge->dropTable('affiliate', true);
    }
}
