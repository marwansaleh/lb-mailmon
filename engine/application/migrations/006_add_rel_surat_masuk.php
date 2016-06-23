<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Migration_add_rel_surat_masuk
 *
 * @author marwansaleh
 */
class Migration_add_rel_surat_masuk extends MY_Migration {
    protected $_table_name = 'rel_surat_masuk';
    protected $_primary_key = 'id';
    protected $_index_keys = array('nomor_surat','pengirim');
    protected $_fields = array(
        'id'    => array (
            'type'  => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'auto_increment' => TRUE
        ),
        'tanggal_surat' => array(
            'type' => 'DATE',
            'null' => FALSE
        ),
        'tanggal_terima' => array(
            'type' => 'DATE',
            'null' => FALSE
        ),
        'nomor_surat' => array(
            'type' => 'VARCHAR',
            'constraint' => 25,
            'null' => TRUE
        ),
        'perihal' => array(
            'type' => 'VARCHAR',
            'constraint' => 254,
            'null' => TRUE
        ),
        'pengirim' => array(
            'type' => 'VARCHAR',
            'constraint' => 50,
            'null' => FALSE
        ),
        'penerima' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => FALSE
        ),
        'bidang' => array(
            'type' => 'INT',
            'constraint' => 2,
            'null' => FALSE
        ),
        'keterangan' => array(
            'type' => 'TEXT',
            'null' => TRUE
        ),
        'status' => array(
            'type' => 'TINYINT',
            'constraint' => 2,
            'default' => 0
        ),
        'created' => array(
            'type' => 'INT',
            'constraint' => 11,
            'default' => 0
        ),
        'created_by' => array(
            'type' => 'INT',
            'constraint' => 11,
            'default' => 0
        )
    );
}

/*
 * filename : 006_add_rel_surat_masuk.php
 * location : /application/migrations/006_add_rel_surat_masuk.php
 */
