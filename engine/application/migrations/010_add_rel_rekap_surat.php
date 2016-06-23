<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Migration_add_rel_rekap_surat
 *
 * @author marwansaleh
 */
class Migration_add_rel_rekap_surat extends MY_Migration {
    protected $_table_name = 'rel_rekap_surat';
    protected $_primary_key = 'id';
    protected $_index_keys = array('nomor','tipe','perihal','tanggal');
    protected $_fields = array(
        'id'    => array (
            'type'  => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'auto_increment' => TRUE
        ),
        'surat'    => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => FALSE
        ),
        'nomor'    => array(
            'type' => 'VARCHAR',
            'constraint' => 25,
            'null' => FALSE
        ),
        'tipe'    => array(
            'type' => 'ENUM("incoming","outgoing","nodin")',
            'default' => 'incoming'
        ),
        'perihal'    => array(
            'type' => 'VARCHAR',
            'constraint' => 254,
            'null' => FALSE
        ),
        'tanggal'    => array(
            'type' => 'DATE',
            'null' => FALSE
        )
    );

}

/*
 * filename : 016_add_rel_rekap_surat.php
 * location : /application/migrations/016_add_rel_rekap_surat.php
 */
