<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CategoryTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'code_category' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'name_category' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'parent_category' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'slug_category' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'font_awesome_class' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'thumbnail' => [
                'type' => 'LONGTEXT',
            ],
            'create_at' => [
                'type' => 'INT',
            ],
            'update_at' => [
                'type' => 'INT',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('category');
    }

    public function down()
    {
        $this->forge->dropTable('category', true);
    }
}
