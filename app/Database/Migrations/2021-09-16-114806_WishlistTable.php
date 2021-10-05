<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class WishlistTable extends Migration
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
            'wishlist_item' => [
                'type' => 'INT',
                'constraint' => 11,

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
        $this->forge->createTable('wishlist');
    }

    public function down()
    {
        $this->forge->dropTable('wishlist', true);
    }
}
