<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Migration_add_rel_tindasan
 *
 * @author marwansaleh
 */
class Migration_add_rel_tindasan extends MY_Migration {
    protected $_table_name = 'rel_tindasan';
    protected $_primary_key = 'id';
    protected $_fields = array(
        'id'    => array (
            'type'  => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'auto_increment' => TRUE
        ),
        'mail'    => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => FALSE
        ),
        'tipe'    => array(
            'type' => 'ENUM("incoming","outgoing","nodin")',
            'default' => 'incoming'
        ),
        'penerima'    => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => FALSE
        )
    );

}

/*
 * filename : 016_add_rel_tindasan.php
 * location : /application/migrations/016_add_rel_tindasan.php
 */
