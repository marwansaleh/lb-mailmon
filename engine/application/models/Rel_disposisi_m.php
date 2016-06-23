<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Rel_disposisi_m
 *
 * @author marwansaleh
 */
class Rel_disposisi_m extends MY_Model {
    protected $_table_name = 'rel_disposisi';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'waktu_kirim desc, mail';
    protected $_timestamps = FALSE;
}

/*
 * file location: /application/models/Rel_disposisi_m.php
 */
