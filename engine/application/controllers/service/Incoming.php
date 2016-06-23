<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Incoming
 *
 * @author marwansaleh 1:31:58 PM
 */
class Incoming extends REST_Api {
    private $_remap_fields = array(
        'id'                => 'id',
        'tanggal_surat'     => 'tanggal_surat',
        'tanggal_terima'    => 'tanggal_terima',
        'nomor_surat'       => 'nomor_surat',
        'perihal'           => 'perihal',
        'pengirim'          => 'pengirim',
        'penerima'          => 'penerima',
        'keterangan'        => 'keterangan',
        'dokumen'           => 'dokumen',
        'status'            => 'status',
        'created'           => 'created',
        'editable'          => 'editable',
        'disposisable'      => 'disposisable'
    );
    
    function __construct($config = 'rest') {
        parent::__construct($config);
    }
    
    function index_get(){
        $this->load->model(array('rel_incoming_m','rel_dokumen_m'));
        $userlib = Userlib::getInstance();
        $me = $userlib->me();
        
        $user_id = $this->get('userId');
        
        $draw = $this->get('draw') ? $this->get('draw') : 1;
        $length = $this->get('length') ? $this->get('length') : 10;
        $search = $this->get('search') ? $this->get('search') : NULL;
        $start = $this->get('start') ? $this->get('start') : 0;
        
        if (!$me->root){
            $where = "created_by=$user_id OR penerima=$user_id";
        }else{
            $where = NULL;
        }
        
        $totalRecords = $this->rel_incoming_m->get_count($where);
        
        //set filtered if any
        if ($search && $search['value']){
            $this->db->like('nomor_surat', $search['value']);
            $this->db->or_like('pengirim', $search['value']);
            $this->db->or_like('perihal', $search['value']);
        }
        //get filtered count
        $totalFiltered = $this->rel_incoming_m->get_count($where);
        
        $result = array('draw' => $draw, 'start'=>$start, 'recordsTotal'=>$totalRecords, 'recordsFiltered'=>$totalFiltered, 'items'=>array());

        if ($totalRecords > 0){
            $userSrv = new Service();
            $users = $userSrv->get_users(TRUE);
            //set filtered if any
            if ($search && $search['value']){
                $this->db->like('nomor_surat', $search['value']);
                $this->db->or_like('pengirim', $search['value']);
                $this->db->or_like('perihal', $search['value']);
            }
            //apply offset and limit of data
            $this->db->offset($start)->limit($length);
            
            $query_result = $this->rel_incoming_m->get_by($where);
            if ($query_result){
                $items = array();
                foreach ($query_result as $item){
                    //manipulate result item before return
                    $item->user = $user_id;
                    $item->editable = $item->created_by == $user_id;
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
            $item->disposisable = $item->penerima==$item->user;
            
            if ($users){
                $item->penerima = isset($users[$item->penerima]) ? $users[$item->penerima] : $item->penerima;
            }
            $dokumen = $this->rel_dokumen_m->get_by(array('mail'=>$item->id, 'tipe'=>MAIL_INCOMING));
            if ($dokumen){
                $item->dokumen = array();
                foreach ($dokumen as $doc){
                    $doc->download_url = get_action_url('download/index/'.urlencode(base64_encode($doc->file_name)));
                    $item->dokumen [] = $doc;
                }
            }else{
                $item->dokumen = NULL;
            }
            
            $item->status = incoming_status($item->status);
        }
        return $item;
    }
    
    public function index_post(){
        $this->load->model(array('rel_incoming_m','rel_rekap_m'));
        $result = array('status'=> FALSE);
        
        $data = array(
            'tanggal_surat'     => $this->post('tanggal_surat'),
            'tanggal_terima'    => $this->post('tanggal_terima'),
            'nomor_surat'       => $this->post('nomor_surat'),
            'perihal'           => $this->post('perihal'),
            'pengirim'          => $this->post('pengirim'),
            'penerima'          => $this->post('penerima'),
            'bidang'            => $this->post('bidang'),
            'keterangan'        => $this->post('keterangan'),
            'created'           => time(),
            'created_by'        => $this->post('user_id')
        );
        
        $inserted_id = $this->rel_incoming_m->save($data);
        if ($inserted_id){
            $result['status'] = TRUE;
            $item = $this->rel_incoming_m->get($inserted_id);
            $result['item'] = $item;
            
            //update rekap surat
            $this->rel_rekap_m->save(array(
                'surat'     => $item->id,
                'nomor'     => $item->nomor_surat,
                'tipe'      => MAIL_INCOMING,
                'perihal'   => $item->perihal,
                'tanggal'   => $item->tanggal_surat
            ));
        }else{
            $result['message'] = $this->rel_incoming_m->get_last_message();
        }
        
        $this->response($result);
    }
    
    public function index_put($mail_id){
        $this->load->model(array('rel_incoming_m','rel_rekap_m'));
        $result = array('status'=> FALSE);
        
        $data = array(
            'tanggal_surat'     => $this->put('tanggal_surat'),
            'tanggal_terima'    => $this->put('tanggal_terima'),
            'nomor_surat'       => $this->put('nomor_surat'),
            'perihal'           => $this->put('perihal'),
            'pengirim'          => $this->put('pengirim'),
            'penerima'          => $this->put('penerima'),
            'bidang'            => $this->put('bidang'),
            'keterangan'        => $this->put('keterangan')
        );
        
        if ($this->rel_incoming_m->save($data, $mail_id)){
            $result['status'] = TRUE;
            $item = $this->rel_incoming_m->get($mail_id);
            $result['item'] = $item;
            
            //update rekap surat
            $rekap = $this->rel_rekap_m->get_by(array('surat'=>$item->id, 'tipe'=>MAIL_INCOMING), TRUE);
            if ($rekap){
                $this->rel_rekap_m->save(array(
                    'nomor'     => $item->nomor_surat,
                    'perihal'   => $item->perihal,
                    'tanggal'   => $item->tanggal_surat
                ), $rekap->id);
            }else{
                $this->rel_rekap_m->save(array(
                    'surat'     => $item->id,
                    'nomor'     => $item->nomor_surat,
                    'tipe'      => MAIL_INCOMING,
                    'perihal'   => $item->perihal,
                    'tanggal'   => $item->tanggal_surat
                ));
            }
        }else{
            $result['message'] = $this->rel_incoming_m->get_last_message();
        }
        
        $this->response($result);
    }
}

/**
 * Filename : Incoming.php
 * Location : application/controllers/service/Incoming.php
 */
