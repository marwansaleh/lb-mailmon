<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Migration_add_ref_user_action_roles
 *
 * @author marwansaleh
 */
class Migration_add_ref_user_action_roles extends MY_Migration {
    protected $_table_name = 'ref_auth_action_roles';
    protected $_primary_key = 'id';
    protected $_fields = array(
        'id'    => array (
            'type'  => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'auto_increment' => TRUE
        ),
        'name'    => array(
            'type' => 'VARCHAR',
            'constraint' => 50
        ),
        'granted'    => array(
            'type' => 'TINYINT',
            'constraint' => 1,
            'default' => 0
        )
    );
    
    public function up(){
        parent::up();
        
        //Need seeding ?
        $this->_seed(array(
            array(
                'name'          => 'INCOMING_VIEW_ALL',
                'granted'       => 0
            ),
            array(
                'name'          => 'INCOMING_ADD',
                'granted'       => 1
            ),
            array(
                'name'          => 'INCOMING_DISPOSISI',
                'granted'       => 1
            ),
            array(
                'name'          => 'INCOMING_DELETE',
                'granted'       => 1
            ),
            array(
                'name'          => 'OUTGOING_DELETE',
                'granted'       => 0
            ),
            array(
                'name'          => 'OUTGOING_SIGN',
                'granted'       => 0
            )
        ));
    }
    
}

/*
 * filename : 011_add_ref_user_action_roles.php
 * location : /application/migrations/011_add_ref_user_action_roles.php
 */
