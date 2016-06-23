<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Bidang_m
 *
 * @author Marwan
 * @email amazzura.biz@gmail.com
 */
class Bidang_m extends MY_Model {
    protected $_table_name = 'auth_user_bidang';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'id';
    protected $_timestamps = FALSE;
    
    public function save($data, $id = NULL) {
        //check if username exists
        if (isset($data['nama'])){
            if ($id){
                if ($this->get_count(array('nama'=>$data['nama'], 'id!='=>$id))){
                    $this->_last_message = 'Bidang "'.$data['nama'].'" sudah ada di database';
                    return FALSE;
                }
            }else{
                if ($this->get_count(array('nama'=>$data['nama']))){
                    $this->_last_message = 'Bidang "'.$data['nama'].'" sudah ada di database';
                    return FALSE;
                }
            }
        }
        return parent::save($data, $id);
    }
}

/*
 * file location: /application/models/Bidang_m.php
 */
