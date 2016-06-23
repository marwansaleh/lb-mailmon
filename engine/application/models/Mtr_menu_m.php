<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Mtr_menu_m
 *
 * @author marwansaleh
 */
class Mtr_menu_m extends MY_Model {
    protected $_table_name = 'ref_mainmenu';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'id,sort';
    protected $_timestamps = FALSE;
    
    public function get_all($parent=0, $deep=TRUE, $hidden=0){
        $this->load->model('mtr_module_m');
        $tb_module = $this->mtr_module_m->get_tablename();
        
        if (!$deep){
            $this->db->where(array('mm.parent'=>$parent));
        }
        
        $this->db->select('mm.*,md.name as module_name');
        $this->db->from($this->_table_name.' AS mm');
        $this->db->join($tb_module . ' AS md', 'mm.module=md.id');
        
        $this->db->order_by($this->_order_by);
        
        $result = $this->db->get();
        if (!$result){
            return NULL;
        }else{
            return $result->result();
        }
    }
}

/*
 * file location: /application/models/Mtr_menu_m.php
 */
