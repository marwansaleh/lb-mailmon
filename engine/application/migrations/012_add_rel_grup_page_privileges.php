<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Migration_add_rel_grup_page_privileges
 *
 * @author marwansaleh
 */
class Migration_add_rel_grup_page_privileges extends MY_Migration {
    protected $_table_name = 'rel_auth_group_page_privileges';
    protected $_primary_key = 'id';
    protected $_fields = array(
        'id'    => array (
            'type'  => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'auto_increment' => TRUE
        ),
        'group_id'    => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => FALSE
        ),
        'page_id'    => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => FALSE
        ),
        'granted'    => array(
            'type' => 'TINYINT',
            'constraint' => 1,
            'default' => 0
        )
    );
    
}

/*
 * filename : 013_add_rel_grup_page_privileges.php
 * location : /application/migrations/013_add_rel_grup_page_privileges.php
 */
