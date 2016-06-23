<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of User_m
 *
 * @author Marwan
 * @email amazzura.biz@gmail.com
 */
class User_m extends MY_Model {
    protected $_table_name = 'auth_user_account';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'bidang, nik, nama';
    protected $_timestamps = FALSE;
    
    public function save($data, $id = NULL) {
        //check if username exists
        if (isset($data['username'])){
            if ($id){
                if ($this->get_count(array('username'=>$data['username'], 'id!='=>$id))){
                    $this->_last_message = 'Username "'.$data['username'].'" sudah ada di database';
                    return FALSE;
                }
            }else{
                if ($this->get_count(array('username'=>$data['username']))){
                    $this->_last_message = 'Username "'.$data['username'].'" sudah ada di database';
                    return FALSE;
                }
            }
        }
        return parent::save($data, $id);
    }
}

/*
 * file location: /application/models/User_m.php
 */
