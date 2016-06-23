<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Migration_add_rel_dokumen_lampiran
 *
 * @author marwansaleh
 */
class Migration_add_rel_dokumen_lampiran extends MY_Migration {
    protected $_table_name = 'rel_dokumen_lampiran';
    protected $_primary_key = 'id';
    protected $_index_keys = array('tipe');
    protected $_fields = array(
        'id'    => array (
            'type'  => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'auto_increment' => TRUE
        ),
        'tipe' => array(
            'type' => 'ENUM("incoming","outgoing","nodin")',
            'default' => 'incoming'
        ),
        'mail' => array(
            'type'  => 'INT',
            'constraint' => 11,
            'null'  => FALSE
        ),
        'file_name' => array(
            'type' => 'VARCHAR',
            'constraint' => 254,
            'null' => FALSE
        ),
        'file_type' => array(
            'type' => 'VARCHAR',
            'constraint' => 100,
            'null' => FALSE
        ),
        'orig_name' => array(
            'type' => 'VARCHAR',
            'constraint' => 254,
            'null' => FALSE
        ),
        'file_size' => array(   //filesize in kilobytes
            'type' => 'INT',
            'constraint' => 11,
            'default' => 0
        ),
        'uploaded' => array(
            'type' => 'TIMESTAMP'
        )
    );
}

/*
 * filename : 015_add_rel_dokumen_lampiran.php
 * location : /application/migrations/015_add_rel_dokumen_lampiran.php
 */
