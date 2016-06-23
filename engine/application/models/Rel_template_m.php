<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Rel_template_m
 *
 * @author marwansaleh
 */
class Rel_template_m extends MY_Model {
    protected $_table_name = 'rel_template';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'tipe,nama';
    protected $_timestamps = FALSE;
}

/*
 * file location: /application/models/Rel_template_m.php
 */
