<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Bidang
 *
 * @author marwansaleh 1:31:58 PM
 */
class Bidang extends REST_Api {
    private $_remap_fields = array(
        'id'                    => 'id',
        'parent'                => 'parent',
        'nama'                  => 'nama',
        'sandi'                 => 'sandi'
    );
    
    function __construct($config = 'rest') {
        parent::__construct($config);
    }
    
    function index_get(){
        $this->load->model(array('bidang_m'));
        
        $draw = $this->get('draw') ? $this->get('draw') : 1;
        $length = $this->get('length') ? $this->get('length') : 10;
        $search = $this->get('search') ? $this->get('search') : NULL;
        $start = $this->get('start') ? $this->get('start') : 0;
        
        $totalRecords = $this->bidang_m->get_count();
        
        //set filtered if any
        if ($search && $search['value']){
            $this->db->like('nama', $search['value']);
        }
        //get filtered count
        $totalFiltered = $this->bidang_m->get_count();
        
        $result = array('draw' => $draw, 'start'=>$start, 'recordsTotal'=>$totalRecords, 'recordsFiltered'=>$totalFiltered, 'items'=>array());

        if ($totalRecords > 0){
            //set filtered if any
            if ($search && $search['value']){
                $this->db->like('nama', $search['value']);
            }
            //apply offset and limit of data
            $this->db->offset($start)->limit($length);
            
            $query_result = $this->bidang_m->get();
            if ($query_result){
                $items = array();
                foreach ($query_result as $item){
                    //manipulate result item before return
                    $items [] = $this->_proccess_item($item);
                }
                //manipulate result item before return
                $result['items'] = $this->remap_fields($this->_remap_fields, $items);
            }
        }
        $this->response($result);
        
    }
    
    function all_get(){
        $this->load->model(array('bidang_m'));
        
        $result = array('item_count'=>0, 'items'=>array());
        $query_result = $this->bidang_m->get();
        
        if ($query_result){
            $items = array();
            foreach ($query_result as $item){
                //manipulate result item before return
                $items [] = $this->_proccess_item($item);
            }
            //manipulate result item before return
            $result['items'] = $this->remap_fields($this->_remap_fields, $items);
            $result['item_count'] = count($result['items']);
        }
        $this->response($result);
        
    }
    
    function index_delete($id){
        $this->load->model(array('bidang_m'));
        $result = array('status'=>FALSE);
        
        if ($this->bidang_m->delete($id)){
            $result['status'] = TRUE;
        }else{
            $result['message'] = $this->bidang_m->get_last_message();
        }
        $this->response($result);
    }
    
    function save_post(){
        $this->load->model('bidang_m');
        $result = array('status'=>FALSE);
        
        $data = array(
            'nama'          => $this->post('nama'),
            'sandi'         => $this->post('sandi')
        );
        if ($this->bidang_m->save($data)){
            $result['status'] = TRUE;
        }else{
            $result['message'] = $this->bidang_m->get_last_message();
        }
        
        $this->response($result);
    }
    
    function save_put($id){
        $this->load->model('bidang_m');
        $result = array('status'=>FALSE);
        
        if ($id){
            $data = array(
                'nama'          => $this->put('nama'),
                'sandi'         => $this->put('sandi')
            );
            if ($this->bidang_m->save($data, $id)){
                $result['status'] = TRUE;
            }else{
                $result['message'] = $this->bidang_m->get_last_message();
            }
        }else{
            $result['message'] = 'ID is not defined';
        }
        
        $this->response($result);
    }
    
    function bidang_get($id){
        $this->load->model(array('bidang_m'));
        $result = array('status'=>FALSE);
        
        $grup = $this->bidang_m->get($id);
        
        if ($grup){
            $result['status'] = TRUE;
            $result['item'] = $this->remap_fields($this->_remap_fields, $this->_proccess_item($grup));
        }else{
            $result['message'] = 'Data bidang tidak ada';
        }
        
        $this->response($result);
    }
    
    private function _proccess_item($item = NULL){
        if ($item){
            
        }
        
        return $item;
    }
}

/**
 * Filename : Bidang.php
 * Location : application/controllers/services/Bidang.php
 */
