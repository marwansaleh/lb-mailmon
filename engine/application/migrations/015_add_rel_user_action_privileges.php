<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Migration_add_user_privileges
 *
 * @author marwansaleh
 */
class Migration_add_rel_user_action_privileges extends MY_Migration {
    protected $_table_name = 'rel_auth_user_action_privileges';
    protected $_primary_key = 'id';
    protected $_fields = array(
        'id'    => array (
            'type'  => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'auto_increment' => TRUE
        ),
        'user_id'    => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => FALSE
        ),
        'role_id'    => array(
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
 * filename : 013_add_rel_user_action_privileges.php
 * location : /application/migrations/013_add_rel_user_action_privileges.php
 */
