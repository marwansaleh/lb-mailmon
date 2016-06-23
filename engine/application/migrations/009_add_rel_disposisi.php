<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Migration_add_rel_disposisi
 *
 * @author marwansaleh
 */
class Migration_add_rel_disposisi extends MY_Migration {
    protected $_table_name = 'rel_disposisi';
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
        'pengirim' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => FALSE
        ),
        'penerima' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => FALSE
        ),
        'keterangan' => array(
            'type' => 'TEXT',
            'null' => TRUE
        ),
        'waktu_kirim' => array(
            'type' => 'DATETIME',
            'null' => TRUE
        ),
        'diterima' => array(
            'type' => 'TINYINT',
            'constraint' => 1,
            'default' => 0
        ),
        'waktu_terima' => array(
            'type' => 'DATETIME',
            'null' => TRUE
        ),
        'status' => array(
            'type' => 'TINYINT',
            'constraint' => 1,
            'null' => FALSE
        ),
    );
}

/*
 * filename : 016_add_rel_disposisi.php
 * location : /application/migrations/016_add_rel_disposisi.php
 */
