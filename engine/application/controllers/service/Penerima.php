<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Penerima
 *
 * @author marwansaleh 1:31:58 PM
 */
class Penerima extends REST_Api {
    private $_remap_fields = array(
        'id'                => 'id',
        'text'              => 'text',
        'name'              => 'name',
        'username'          => 'username',
        'bidang'            => 'bidang',
        'bidang_nama'       => 'bidang_nama'
    );
    
    function __construct($config = 'rest') {
        parent::__construct($config);
    }
    
    function index_get($status=NULL){
        $this->load->model(array('auth_user_m','mtr_bidang_m'));
        
        $draw = $this->get('draw') ? $this->get('draw') : 1;
        $length = $this->get('length') ? $this->get('length') : 10;
        $search = $this->get('search') ? $this->get('search') : NULL;
        $start = $this->get('start') ? $this->get('start') : 0;
        
        $totalRecords = $this->auth_user_m->get_count();
        
        //set filtered if any
        if ($search && $search['value']){
            $this->db->like('name', $search['value']);
            $this->db->or_like('username', $search['value']);
        }
        //get filtered count
        $totalFiltered = $this->auth_user_m->get_count();
        
        $result = array('draw' => $draw, 'start'=>$start, 'recordsTotal'=>$totalRecords, 'recordsFiltered'=>$totalFiltered, 'items'=>array());

        if ($totalRecords > 0){
            //set filtered if any
            if ($search && $search['value']){
                $this->db->like('name', $search['value']);
                $this->db->or_like('username', $search['value']);
            }
            //apply offset and limit of data
            $this->db->offset($start)->limit($length);
            
            $query_result = $this->auth_user_m->get_by();
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
    
    private function _proccess_item($item=NULL){
        if ($item){
            $item->bidang_nama = $this->mtr_bidang_m->get_value('nama',array('id'=>$item->bidang));
        }
        return $item;
    }
    
    public function index_post(){
        $this->load->model(array('rel_incoming_m'));
        $result = array('status'=> FALSE);
        
        $data = array(
            'tanggal_surat'     => $this->post('tanggal_surat'),
            'tanggal_terima'    => $this->post('tanggal_terima'),
            'nomor_surat'       => $this->post('nomor_surat'),
            'perihal'           => $this->post('perihal'),
            'pengirim'          => $this->post('pengirim'),
            'penerima'          => $this->post('penerima'),
            'keterangan'        => $this->post('keterangan'),
            'created'           => time()
        );
        
        $inserted_id = $this->rel_incoming_m->save($data);
        if ($inserted_id){
            $result['status'] = TRUE;
            $result['item'] = $this->rel_incoming_m->get($inserted_id);
        }else{
            $result['message'] = $this->rel_incoming_m->get_last_message();
        }
        
        $this->response($result);
    }
    
    public function select2_get($id=NULL){
        $this->load->model(array('auth_user_m','mtr_bidang_m'));
        if ($id){
            
            $item = $this->_proccess_item($this->auth_user_m->get($id));
            if ($item){
                $item->text = $item->name . ' (' . $item->bidang_nama .')';
            }
            
            $this->response($item);
        }else{
            $q = $this->get('q') ? $this->get('q') : '';
            $page = $this->get('page') ? $this->get('page') : 1;
            $length = 5;

            $start = ($page-1) * $length;

            //apply offset and limit of data
            $this->db->like('name',$q);
            $this->db->or_like('username',$q);
            $query_result = $this->auth_user_m->get_offset('*',NULL,$start,$length);
            $items = array();
            if ($query_result){
                $count = count($query_result);

                foreach ($query_result as $item){
                    $item = $this->_proccess_item($item);
                    $item->text = $item->name . ' (' . $item->bidang_nama .')';
                    $items[] = $item;
                }
            }
            
            $endCount = $start + $length;
            $morePages = $endCount > $count;

            $result = array(
                'results' => $items,
                'pagination' => array(
                    'more' => $morePages
                )
            );

            $this->response($result);
        }
    }
}

/**
 * Filename : Penerima.php
 * Location : application/controllers/service/Penerima.php
 */
