<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PaymentBalanceTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pb' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'id_users' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'id_payment' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'pb_payment' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'pb_nominal' => [
                'type' => 'BIGINT',
            ],
            'pb_type' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'pb_affiliate' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'pb_bank' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'pb_norek' => [
                'type' => 'BIGINT',
            ],
            'pb_on_behalf_of' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'pb_status' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'pb_saldo' => [
                'type' => 'BIGINT',
            ],
            'pb_date' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'pb_date_done' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'pb_token' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
        ]);
        $this->forge->addKey('id_pb', true);
        $this->forge->addForeignKey('id_users', 'users', 'id');
        $this->forge->addForeignKey('id_payment', 'payment', 'id_payment');
        $this->forge->createTable('payment_balance');
    }

    public function down()
    {
        $this->forge->dropTable('payment_balance', true);
    }
}
