<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CouponTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'type_coupon' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'code_coupon' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'value' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'course_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'quantity' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'date_added' => [
                'type' => 'DATETIME'
            ],
            'last_modified' => [
                'type' => 'DATETIME'
            ],
            'is_active' => [
                'type' => 'INT',
                'constraint' => 1
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id');
        $this->forge->createTable('coupon');
    }

    public function down()
    {
        $this->forge->dropTable('coupon', true);
    }
}
