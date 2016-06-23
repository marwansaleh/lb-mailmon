<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Migration_Add_user_account
 *
 * @author marwansaleh
 */
class Migration_Add_user_account extends MY_Migration {
    protected $_table_name = 'auth_user_account';
    protected $_primary_key = 'id';
    protected $_index_keys = array('username','nik');
    protected $_fields = array(
        'id'    => array (
            'type'  => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'auto_increment' => TRUE
        ),
        'username' => array(
            'type'  => 'VARCHAR',
            'constraint' => 30,
            'null' => FALSE
        ),
        'password' => array(
            'type'  => 'VARCHAR',
            'constraint' => 48,
            'null' => FALSE
        ),
        'nama' => array(
            'type'  => 'VARCHAR',
            'constraint' => 50,
            'null' => FALSE
        ),
        'grup' => array(
            'type'  => 'INT',
            'constraint' => 2,
            'null' => FALSE
        ),
        'bidang' => array(
            'type'  => 'INT',
            'constraint' => 2,
            'null' => TRUE
        ),
        'nik' => array(
            'type'  => 'VARCHAR',
            'constraint' => 15,
            'null' => TRUE
        ),
        'email' => array(
            'type'  => 'VARCHAR',
            'constraint' => 50,
            'null' => TRUE
        ),
        'mobile' => array(
            'type'  => 'VARCHAR',
            'constraint' => 15,
            'null' => TRUE
        ),
        'telepon' => array(
            'type'  => 'VARCHAR',
            'constraint' => 15,
            'null' => TRUE
        ),
        'avatar' => array(
            'type' => 'VARCHAR',
            'constraint' => 254,
            'null' => TRUE
        ),
        'active' => array(
            'type'  => 'TINYINT',
            'constraint' => 1,
            'default' => 1
        ),
    );
    
    function up() {
        parent::up();
        
        $this->_seed(array(
            array(
                'username' => 'root',
                'password' => '6faac5916d1cd931ada00f508e126e13',
                'nama' => 'Root',
                'grup' => 1,
                'bidang' => 8,
                'nik' => '000000',
                'email' => '',
                'active' => 1
            )
        ));
    }
}

/*
 * filename : 001_add_user_account.php
 * location : /application/migrations/001_add_user_account.php
 */
