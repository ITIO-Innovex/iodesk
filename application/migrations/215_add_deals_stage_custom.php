<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_deals_stage_custom extends CI_Migration {
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'deal_stage_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ],
            'order' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ],
            'company_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ],
            'checked' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
        ]);
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('it_crm_deals_stage_custom', true);
    }

    public function down()
    {
        $this->dbforge->drop_table('it_crm_deals_stage_custom', true);
    }
} 