<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Migration_add_rel_nota_dinas
 *
 * @author marwansaleh
 */
class Migration_add_rel_nota_dinas extends MY_Migration {
    protected $_table_name = 'rel_nota_dinas';
    protected $_primary_key = 'id';
    protected $_index_keys = array('nomor_surat','perihal');
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
            'type' => 'INT',
            'constraint' => 11,
            'null' => FALSE
        ),
        'bidang_penerima' => array(
            'type' => 'INT',
            'constraint' => 3,
            'null' => FALSE
        ),
        'isi_surat' => array(
            'type' => 'TEXT',
            'null' => TRUE
        ),
        'header' => array(
            'type' => 'TEXT',
            'null' => TRUE
        ),
        'footer' => array(
            'type' => 'TEXT',
            'null' => TRUE
        ),
        'pagestyle'      => array(
            'type' => 'TEXT',
            'null' => TRUE
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
        'created' => array(
            'type' => 'INT',
            'constraint' => 11,
            'default' => 0
        )
    );
}

/*
 * filename : 010_add_rel_nota_dinas.php
 * location : /application/migrations/010_add_rel_nota_dinas.php
 */
