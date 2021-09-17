<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SocialAuthGoogleTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_oauth' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'first_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'last_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'profile_pic' => [
                'type' => 'LONGTEXT'
            ],
        ]);
        $this->forge->addKey('id_oauth', true);
        $this->forge->createTable('social_auth_google');
    }

    public function down()
    {
        $this->forge->dropTable('social_auth_google', true);
    }
}
