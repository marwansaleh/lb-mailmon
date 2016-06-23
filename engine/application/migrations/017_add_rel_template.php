<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Migration_add_rel_template
 *
 * @author marwansaleh
 */
class Migration_add_rel_template extends MY_Migration {
    protected $_table_name = 'rel_template';
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
            'constraint' => 100,
            'null' => FALSE
        ),
        'tipe' => array(
            'type' => 'ENUM("outgoing","nodin")',
            'default' => 'outgoing'
        ),
        'header'    => array(
            'type' => 'TEXT',
            'null' => TRUE
        ),
        'footer'    => array(
            'type' => 'TEXT',
            'null' => TRUE
        ),
        'content'    => array(
            'type' => 'TEXT',
            'null' => FALSE
        ),
        'pagestyle'      => array(
            'type' => 'TEXT',
            'null' => TRUE
        ),
    );

    public function up() {
        parent::up();
        
        $data = array(
            array(
                'nama'          => 'Surat Keluar',
                'tipe'          => 'outgoing',
                'header'        => 'PT. BRIngin Sejahtera Makmur',
                'footer'        => NULL,
                'content'       => 'Surat keluar biasa',
                'pagestyle'     => NULL
            ),
            array(
                'nama'          => 'Nota Dinas',
                'tipe'          => 'nodin',
                'header'        => 'PT. BRIngin Sejahtera Makmur',
                'footer'        => NULL,
                'content'       => 'Surat nota dinas',
                'pagestyle'     => NULL
            )
        );
        
        $this->_seed($data);
    }
}

/*
 * filename : 017_add_rel_template.php
 * location : /application/migrations/017_add_rel_template.php
 */
