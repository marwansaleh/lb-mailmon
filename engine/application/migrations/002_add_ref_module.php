<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Migration_add_ref_module
 *
 * @author marwansaleh
 */
class Migration_add_ref_module extends MY_Migration {
    protected $_table_name = 'ref_module';
    protected $_primary_key = 'id';
    protected $_fields = array(
        'id'    => array (
            'type'  => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'auto_increment' => TRUE
        ),
        'name'    => array (
            'type'  => 'VARCHAR',
            'constraint' => 50,
            'default' => 0
        ),
        'title'    => array (
            'type'  => 'VARCHAR',
            'constraint' => 100,
            'default' => 0
        ),
        'created'   => array(
            'type'  => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'default' => 0
        ),
        'created_by'   => array(
            'type'  => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'default' => 0
        ),
        'modified'   => array(
            'type'  => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'default' => 0
        ),
        'modified_by'   => array(
            'type'  => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'default' => 0
        )
    );
    
    public function up(){
        parent::up();
        //Need seeding ?
        $this->_seed(array(
            array(
                'id'            => CT_MODULE_DASHBOARD,
                'name'          => 'dashboard',
                'title'         => 'Dashboard',
                'created'       => time(),
                'created_by'    => 1,
                'modified'      => time(),
                'modified_by'   => 1
            ),
            array(
                'id'            => CT_MODULE_INCOMING,
                'name'          => 'incoming',
                'title'         => 'Surat Masuk',
                'created'       => time(),
                'created_by'    => 1,
                'modified'      => time(),
                'modified_by'   => 1
            ),
            array(
                'id'            => CT_MODULE_OUTGOING,
                'name'          => 'outgoing',
                'title'         => 'Surat Keluar',
                'created'       => time(),
                'created_by'    => 1,
                'modified'      => time(),
                'modified_by'   => 1
            ),
            array(
                'id'            => CT_MODULE_NODIN,
                'name'          => 'nodin',
                'title'         => 'Nota Dinas',
                'created'       => time(),
                'created_by'    => 1,
                'modified'      => time(),
                'modified_by'   => 1
            ),
            array(
                'id'            => CT_MODULE_SYSTEM,
                'name'          => 'system',
                'title'         => 'System Configuration',
                'created'       => time(),
                'created_by'    => 1,
                'modified'      => time(),
                'modified_by'   => 1
            ),
            array(
                'id'            => CT_MODULE_USERMGT,
                'name'          => 'usermgt',
                'title'         => 'User Management',
                'created'       => time(),
                'created_by'    => 1,
                'modified'      => time(),
                'modified_by'   => 1
            ),
            array(
                'id'            => CT_MODULE_DISPOSISI,
                'name'          => 'disposisi',
                'title'         => 'Disposisi',
                'created'       => time(),
                'created_by'    => 1,
                'modified'      => time(),
                'modified_by'   => 1
            ),
            array(
                'id'            => CT_MODULE_OTHER,
                'name'          => 'other',
                'title'         => 'Other features',
                'created'       => time(),
                'created_by'    => 1,
                'modified'      => time(),
                'modified_by'   => 1
            ),
        ));
    }
}

/*
 * filename : 003_add_ref_module.php
 * location : /application/migrations/003_add_ref_module.php
 */
