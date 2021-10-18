<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CartTabel extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'TYPE' => 'INT',
                'auto_increment' => true,
            ],
            'id_user' => [
                'TYPE' => 'INT',
            ],
            'cart_item' => [
                'TYPE' => 'INT',
            ],
            'cart_price' => [
                'TYPE' => 'DOUBLE',
            ],
            'create_at' => [
                'TYPE' => 'INT',
            ],
            'update_at' => [
                'TYPE' => 'INT',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_user', 'users', 'id');
        $this->forge->addForeignKey('cart_item', 'courses', 'id');
        $this->forge->createTable('carts');
    }

    public function down()
    {
        $this->forge->dropTable('carts', true);
    }
}
