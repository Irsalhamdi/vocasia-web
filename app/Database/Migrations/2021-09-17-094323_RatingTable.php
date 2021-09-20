<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RatingTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'rating' => [
                'type' => 'DOUBLE',
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'ratable_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'ratable_type' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'date_added' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'last_modified' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'review' => [
                'type' => 'LONGTEXT',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id');
        $this->forge->addForeignKey('ratable_id', 'courses', 'id');
        $this->forge->createTable('rating');
    }

    public function down()
    {
        $this->forge->dropTable('rating', true);
    }
}
