<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class InterestUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'interest_1' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'interest_2' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'interest_3' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('interest_users');
    }

    public function down()
    {
        $this->forge->dropDatabase('interest_users', true);
    }
}
