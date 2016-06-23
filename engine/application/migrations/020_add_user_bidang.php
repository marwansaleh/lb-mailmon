<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Migration_Add_user_bidang
 *
 * @author marwansaleh
 */
class Migration_Add_user_bidang extends MY_Migration {
    protected $_table_name = 'auth_user_bidang';
    protected $_primary_key = 'id';
    protected $_index_keys = array('nama','sandi');
    protected $_fields = array(
        'id'    => array (
            'type'  => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'auto_increment' => TRUE
        ),
        'parent' => array(
            'type' => 'INT',
            'constraint' => 4,
            'default' => 0
        ),
        'nama'    => array(
            'type' => 'VARCHAR',
            'constraint' => 50
        ),
        'sandi'  => array(
            'type' => 'VARCHAR',
            'constraint' => 3
        )
    );
    
    public function up(){
        parent::up();
        //Need seeding ?
        $data = array(
            array(
                'nama'          => 'Ka Badan',
                'sandi'         => '10'
            ),
            array(
                'nama'          => 'Prodatin',
                'sandi'         => '10'
            ),
            array(
                'nama'          => 'Penataan Ruang',
                'sandi'         => '10'
            ),
            array(
                'nama'          => 'Ekonomi',
                'sandi'         => '10'
            ),
            array(
                'nama'          => 'Sosial Budaya',
                'sandi'         => '10'
            ),
            array(
                'nama'          => 'Pengendalian Evaluasi',
                'sandi'         => '10'
            ),
            array(
                'nama'          => 'Sekretariat',
                'sandi'         => '10'
            ),
            array(
                'nama'          => 'Lainnya',
                'sandi'         => '10'
            )
        );
        
        $this->_seed_extend($data, 0, 'parent');
    }
}

/*
 * filename : 003_add_user_bidang.php
 * location : /application/migrations/003_add_user_bidang.php
 */
