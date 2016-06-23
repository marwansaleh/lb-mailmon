<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Rel_outgoing_m
 *
 * @author marwansaleh
 */
class Rel_outgoing_m extends MY_Model {
    protected $_table_name = 'rel_surat_keluar';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'tanggal_surat DESC,perihal';
    protected $_timestamps = FALSE;
}

/*
 * file location: /application/models/Rel_outgoing_m.php
 */
