<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Template
 *
 * @author marwansaleh 1:31:58 PM
 */
class Template extends REST_Api {
    private $_remap_fields = array(
        'id'        => 'id',
        'nama'      => 'nama',
        'tipe'      => 'tipe',
        'header'    => 'header',
        'footer'    => 'footer',
        'pagestyle' => 'pagestyle'
    );
    
    function __construct($config = 'rest') {
        parent::__construct($config);
    }
    
    function index_get(){
        $this->load->model(array('rel_template_m'));
        
        $draw = $this->get('draw') ? $this->get('draw') : 1;
        $length = $this->get('length') ? $this->get('length') : 10;
        $search = $this->get('search') ? $this->get('search') : NULL;
        $start = $this->get('start') ? $this->get('start') : 0;
        
        $totalRecords = $this->rel_template_m->get_count();
        
        //set filtered if any
        if ($search && $search['value']){
            $this->db->like('nama', $search['value']);
            $this->db->or_like('tipe', $search['value']);
        }
        //get filtered count
        $totalFiltered = $this->rel_template_m->get_count();
        
        $result = array('draw' => $draw, 'start'=>$start, 'recordsTotal'=>$totalRecords, 'recordsFiltered'=>$totalFiltered, 'items'=>array());

        if ($totalRecords > 0){
            $userSrv = new Service();
            $users = $userSrv->get_users(TRUE);
            //set filtered if any
            if ($search && $search['value']){
                $this->db->like('nama', $search['value']);
                $this->db->or_like('tipe', $search['value']);
            }
            //apply offset and limit of data
            $this->db->offset($start)->limit($length);
            
            $query_result = $this->rel_template_m->get_by();
            if ($query_result){
                $items = array();
                foreach ($query_result as $item){
                    //manipulate result item before return
                    $items [] = $this->_proccess_item($item, $users);
                }
                //manipulate result item before return
                $result['items'] = $this->remap_fields($this->_remap_fields, $items);
            }
        }
        $this->response($result);
        
    }
    
    private function _proccess_item($item=NULL, $users=NULL){
        if ($item){
            if ($item->pagestyle){
                $item->pagestyle = json_decode($item->pagestyle);
            }
        }
        return $item;
    }
    
    public function index_post(){
        $this->load->model(array('rel_template_m'));
        $result = array('status'=> FALSE);
        
        $data = array(
            'nama'          => $this->post('nama'),
            'tipe'          => $this->post('tipe'),
            'header'        => $this->post('header'),
            'footer'        => $this->post('footer')
        );
        $pagestyle = new stdClass();
        foreach (wordpagestyling(TRUE) as $prop => $defvalue){
            if ($this->post($prop)){
                $pagestyle->$prop = $this->post($prop);
            }else{
                $pagestyle->$prop = $defvalue;
            }
        }
        $data['pagestyle'] = json_encode($pagestyle);
        
        $inserted_id = $this->rel_template_m->save($data);
        if ($inserted_id){
            $result['status'] = TRUE;
            $item = $this->rel_template_m->get($inserted_id);
            $result['item'] = $item;
        }else{
            $result['message'] = $this->rel_template_m->get_last_message();
        }
        
        $this->response($result);
    }
    
    public function index_put($item_id){
        $this->load->model(array('rel_template_m'));
        $result = array('status'=> FALSE);
        
        $data = array(
            'nama'          => $this->put('nama'),
            'tipe'          => $this->put('tipe'),
            'header'        => $this->put('header'),
            'footer'        => $this->put('footer')
        );
        $pagestyle = new stdClass();
        foreach (wordpagestyling(TRUE) as $prop=>$defvalue){
            if ($this->put($prop)){
                $pagestyle->$prop = $this->put($prop);
            }else{
                $pagestyle->$prop = $defvalue;
            }
        }
        $data['pagestyle'] = json_encode($pagestyle);
        if ($this->rel_template_m->save($data, $item_id)){
            $result['status'] = TRUE;
            $item = $this->rel_template_m->get($item_id);
            $result['item'] = $item;
        }else{
            $result['message'] = $this->rel_template_m->get_last_message();
        }
        
        $this->response($result);
    }
}

/**
 * Filename : Template.php
 * Location : application/controllers/service/Template.php
 */
