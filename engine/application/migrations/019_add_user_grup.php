<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Migration_Add_user_account
 *
 * @author marwansaleh
 */
class Migration_Add_user_grup extends MY_Migration {
    protected $_table_name = 'auth_user_group';
    protected $_primary_key = 'id';
    protected $_index_keys = array('nama');
    protected $_fields = array(
        'id'    => array (
            'type'  => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'auto_increment' => TRUE
        ),
        'nama'    => array(
            'type' => 'VARCHAR',
            'constraint' => 50
        )
    );
    
    public function up(){
        parent::up();
        
        //Need seeding ?
        $this->_seed(array(
            array(
                'id'            => 1,
                'nama'          => 'Admin'
            ),
            array(
                'id'            => 2,
                'nama'          => 'User'
            )
        ));
    }
}

/*
 * filename : 002_add_user_grup.php
 * location : /application/migrations/002_add_user_grup.php
 */
