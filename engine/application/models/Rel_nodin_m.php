<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Rel_nodin_m
 *
 * @author marwansaleh
 */
class Rel_nodin_m extends MY_Model {
    protected $_table_name = 'rel_nota_dinas';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'tanggal_surat desc';
    protected $_timestamps = FALSE;
}

/*
 * file location: /application/models/Rel_nodin_m.php
 */
