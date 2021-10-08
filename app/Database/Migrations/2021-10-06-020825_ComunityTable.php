<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ComunityTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255

            ],
            'background' => [
                'type' => 'LONGTEXT',

            ],
            'category_id' => [
                'type' => 'INT',
            ],
            'create_at' => [
                'type' => 'INT',
            ],
            'update_at' => [
                'type' => 'INT',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('category_id', 'category', 'id');
        $this->forge->createTable('community');
    }
    public function down()
    {
        $this->forge->dropTable('community', true);
    }
}
