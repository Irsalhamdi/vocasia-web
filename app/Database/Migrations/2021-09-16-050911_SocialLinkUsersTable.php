<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SocialLinkUsersTable extends Migration
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
            'facebook_link' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'instragram' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'twitter_link' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_user', 'users', 'id');
        $this->forge->createTable('social_link_users');
    }

    public function down()
    {
        $this->forge->dropTable('social_link_users', true);
    }
}
