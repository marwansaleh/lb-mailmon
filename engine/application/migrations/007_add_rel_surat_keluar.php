<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Migration_add_rel_surat_keluar
 *
 * @author marwansaleh
 */
class Migration_add_rel_surat_keluar extends MY_Migration {
    protected $_table_name = 'rel_surat_keluar';
    protected $_primary_key = 'id';
    protected $_index_keys = array('nomor_surat','pengirim','perihal');
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
            'type' => 'INT',
            'constraint' => 11,
            'null' => FALSE
        ),
        'bidang_pengirim' => array(
            'type' => 'INT',
            'constraint' => 3,
            'null' => FALSE
        ),
        'penerima' => array(
            'type' => 'VARCHAR',
            'constraint' => 50,
            'null' => FALSE
        ),
        'sifat_surat' => array(
            'type' => 'TINYINT',
            'constraint' => 1,
            'default' => 0
        ),
        'isi_surat' => array(
            'type' => 'TEXT',
            'null' => FALSE
        ),
        'header' => array(
            'type' => 'TEXT',
            'null' => FALSE
        ),
        'footer' => array(
            'type' => 'TEXT',
            'null' => FALSE
        ),
        'pagestyle'      => array(
            'type' => 'TEXT',
            'null' => FALSE
        ),
        'status' => array(
            'type' => 'TINYINT',
            'constraint' => 1,
            'default' => 0
        ),
        'posisi_akhir' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => FALSE
        ),
        'signer' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => FALSE
        ),
        'tindasan' => array(
            'type'  => 'VARCHAR',
            'constraint' => 20,
            'null' => TRUE
        ),
        'created' => array(
            'type' => 'INT',
            'constraint' => 11,
            'default' => 0
        ),
        'created_by' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => FALSE
        ),
        'modified' => array(
            'type' => 'INT',
            'constraint' => 11,
            'default' => 0
        ),
        'modified_by' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => FALSE
        ),
    );
}

/*
 * filename : 007_add_rel_surat_keluar.php
 * location : /application/migrations/007_add_rel_surat_keluar.php
 */
