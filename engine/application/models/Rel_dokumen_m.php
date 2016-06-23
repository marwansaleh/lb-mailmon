<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Rel_dokumen_m
 *
 * @author marwansaleh
 */
class Rel_dokumen_m extends MY_Model {
    protected $_table_name = 'rel_dokumen_lampiran';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'uploaded DESC,tipe';
    protected $_timestamps = FALSE;
}

/*
 * file location: /application/models/Rel_dokumen_m.php
 */
