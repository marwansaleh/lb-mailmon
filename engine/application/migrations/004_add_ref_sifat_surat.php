<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Migration_add_ref_bidang
 *
 * @author marwansaleh
 */
class Migration_add_ref_sifat_surat extends MY_Migration {
    protected $_table_name = 'ref_sifat_surat';
    protected $_primary_key = 'id';
    protected $_index_keys = array('nama');
    protected $_fields = array(
        'id'    => array (
            'type'  => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'auto_increment' => TRUE
        ),
        'nama' => array(
            'type' => 'VARCHAR',
            'constraint' => 50,
            'null' => FALSE
        )
    );
    
    public function up(){
        parent::up();
        //Need seeding ?
        $this->_seed(array(
            array(
                'id'            => 1,
                'nama'          => 'Normal'
            ),
            array(
                'id'            => 2,
                'nama'          => 'Rahasia'
            ),
            array(
                'id'            => 3,
                'nama'          => 'Sangat Rahasia'
            )
        ));
    }
}

/*
 * filename : 004_add_ref_sifat_surat.php
 * location : /application/migrations/004_add_ref_sifat_surat.php
 */
