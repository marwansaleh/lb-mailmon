<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Auth_group_page_m
 *
 * @author Marwan
 * @email amazzura.biz@gmail.com
 */
class Auth_group_page_m extends MY_Model {
    protected $_table_name = 'rel_auth_group_page_privileges';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'page_id,group_id';
    protected $_timestamps = FALSE;
}

/*
 * file location: /application/models/Auth_group_page_m.php
 */
