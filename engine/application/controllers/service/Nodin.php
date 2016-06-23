<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Nodin
 *
 * @author marwansaleh 1:31:58 PM
 */
class Nodin extends REST_Api {
    private $_remap_fields = array(
        'id'                => 'id',
        'tanggal_surat'     => 'tanggal_surat',
        'nomor_surat'       => 'nomor_surat',
        'perihal'           => 'perihal',
        'pengirim'          => 'pengirim',
        'bidang_pengirim'   => 'bidang_pengirim',
        'penerima'          => 'penerima',
        'bidang_penerima'   => 'bidang_penerima',
        'isi_surat'         => 'isi_surat',
        'status'            => 'status',
        'posisi_akhir'      => 'posisi_akhir',
        'sandi'             => 'sandi',
        'persetujuan_direksi'=>'persetujuan_direksi',
        'dokumen'           => 'dokumen',
        'created'           => 'created',
        'editable'          => 'editable',
        'to_send'           => 'to_send'
    );
    
    function __construct($config = 'rest') {
        parent::__construct($config);
    }
    
    function index_get(){
        $this->load->model(array('rel_nodin_m','rel_dokumen_m'));
        $userlib = Userlib::getInstance();
        $me = $userlib->me();
        
        $user_id = $this->get('userId');
        
        $draw = $this->get('draw') ? $this->get('draw') : 1;
        $length = $this->get('length') ? $this->get('length') : 10;
        $search = $this->get('search') ? $this->get('search') : NULL;
        $start = $this->get('start') ? $this->get('start') : 0;
        
        if (!$me->root){
            $where = "pengirim=$user_id OR penerima=$user_id";
        }else{
            $where = NULL;
        }
        
        //$where = array('created_by' => $user_id);
        $totalRecords = $this->rel_nodin_m->get_count($where);
        
        //set filtered if any
        if ($search && $search['value']){
            $this->db->like('nomor_surat', $search['value']);
            $this->db->or_like('perihal', $search['value']);
        }
        //get filtered count
        $totalFiltered = $this->rel_nodin_m->get_count($where);
        
        $result = array('draw' => $draw, 'start'=>$start, 'recordsTotal'=>$totalRecords, 'recordsFiltered'=>$totalFiltered, 'items'=>array());

        if ($totalRecords > 0){
            $userSrv = new Service();
            $users = $userSrv->get_users(TRUE);
            //set filtered if any
            if ($search && $search['value']){
                $this->db->like('nomor_surat', $search['value']);
                $this->db->or_like('perihal', $search['value']);
            }
            //apply offset and limit of data
            $this->db->offset($start)->limit($length);
            
            $query_result = $this->rel_nodin_m->get_by($where);
            if ($query_result){
                $items = array();
                foreach ($query_result as $item){
                    //manipulate result item before return
                    $item->to_send = ($item->pengirim == $user_id) && ($item->status == STATUS_NODIN_NEW || $item->status == STATUS_NODIN_REVISION) ? TRUE : FALSE;
                    $item->editable = $item->pengirim == $user_id  && $item->status != STATUS_NODIN_SIGNED;
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
            if ($users){
                $item->pengirim = isset($users[$item->pengirim]) ? $users[$item->pengirim] : $item->pengirim;
                $item->penerima = isset($users[$item->penerima]) ? $users[$item->penerima] : $item->penerima;
                $item->posisi_akhir = isset($users[$item->posisi_akhir]) ? $users[$item->posisi_akhir] : $item->posisi_akhir;
            }
            $dokumen = $this->rel_dokumen_m->get_by(array('mail'=>$item->id, 'tipe'=>MAIL_NODIN));
            if ($dokumen){
                $item->dokumen = array();
                foreach ($dokumen as $doc){
                    $doc->download_url = get_action_url('download/index/'.urlencode(base64_encode($doc->file_name)));
                    $item->dokumen [] = $doc;
                }
            }else{
                $item->dokumen = NULL;
            }
            
            $item->status = nodin_status($item->status);
        }
        return $item;
    }
    
    public function index_post(){
        $this->load->model(array('rel_nodin_m','rel_rekap_m'));
        $user_id = $this->post('user_id');
        
        $result = array('status'=> FALSE);
        
        $data = array(
            'tanggal_surat'     => $this->post('tanggal_surat'),
            'nomor_surat'       => $this->post('nomor_surat'),
            'perihal'           => $this->post('perihal'),
            'pengirim'          => $user_id,
            'bidang_pengirim'   => $this->post('bidang_pengirim'),
            'penerima'          => $this->post('penerima'),
            'bidang_penerima'   => $this->post('bidang_penerima'),
            'sandi'             => $this->post('sandi'),
            'persetujuan_direksi'=> $this->post('persetujuan_direksi'),
            'isi_surat'         => $this->post('isi_surat', FALSE),
            'header'            => $this->post('header'),
            'footer'            => $this->post('footer'),
            'status'            => STATUS_NODIN_NEW,
            'posisi_akhir'      => $user_id,
            'created'           => time()
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
        
        $inserted_id = $this->rel_nodin_m->save($data);
        if ($inserted_id){
            $result['status'] = TRUE;
            $item = $this->rel_nodin_m->get($inserted_id);
            $result['item'] = $item;
            
            //update rekap surat
            $this->rel_rekap_m->save(array(
                'surat'     => $item->id,
                'nomor'     => $item->nomor_surat,
                'tipe'      => MAIL_NODIN,
                'perihal'   => $item->perihal,
                'tanggal'   => $item->tanggal_surat
            ));
        }else{
            $result['message'] = $this->rel_nodin_m->get_last_message();
        }
        
        $this->response($result);
    }
    
    public function index_put($mail_id){
        $this->load->model(array('rel_nodin_m','rel_rekap_m'));
        $user_id = $this->put('user_id');
        $result = array('status'=> FALSE);
        
        $data = array(
            'tanggal_surat'     => $this->put('tanggal_surat'),
            'nomor_surat'       => $this->put('nomor_surat'),
            'perihal'           => $this->put('perihal'),
            'penerima'          => $this->put('penerima'),
            'bidang_penerima'   => $this->put('bidang_penerima'),
            'sandi'             => $this->put('sandi'),
            'persetujuan_direksi'=> $this->put('persetujuan_direksi'),
            'isi_surat'         => $this->put('isi_surat', FALSE),
            'header'            => $this->put('header', FALSE),
            'footer'            => $this->put('footer', FALSE),
        );
        
        $pagestyle = new stdClass();
        foreach (wordpagestyling(TRUE) as $prop => $defvalue){
            if ($this->put($prop)){
                $pagestyle->$prop = $this->put($prop);
            }else{
                $pagestyle->$prop = $defvalue;
            }
        }
        $data['pagestyle'] = json_encode($pagestyle);
        
        if ($this->rel_nodin_m->save($data, $mail_id)){
            $result['status'] = TRUE;
            $item = $this->rel_nodin_m->get($mail_id);
            $result['item'] =  $item;
            
            //update rekap surat
            $rekap = $this->rel_rekap_m->get_by(array('surat'=>$item->id, 'tipe'=>MAIL_NODIN), TRUE);
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
                    'tipe'      => MAIL_NODIN,
                    'perihal'   => $item->perihal,
                    'tanggal'   => $item->tanggal_surat
                ));
            }
        }else{
            $result['message'] = $this->rel_nodin_m->get_last_message();
        }
        
        $this->response($result);
    }
    
    public function gen_nomor_post(){
        $this->load->model(array('rel_rekap_m','rel_nodin_m'));
        $result = array('status'=>FALSE);
        
        $mailId = $this->post('mail');
        if ($mailId){
            //check di rekap surat, apakah sudah terdaftar dan memiliki nomor
            $rekap_record = $this->rel_rekap_m->get_by(array('surat'=>$mailId, 'tipe'=>MAIL_NODIN), TRUE);
            if ($rekap_record && $rekap_record->nomor){
                $result['status'] = TRUE;
                $result['nomor_surat'] = $rekap_record->nomor;
                
                $this->rel_nodin_m->save(array('nomor_surat'=>$rekap_record->nomor),$mailId);
            }else{
                $mail = $this->rel_nodin_m->get($mailId);
                
                //generate nomor surat dari service nomor surat generator
                $service = new Service();
                $gen_service_result = $service->generate_nomor(array(
                    'bidang_pengirim'       => $mail->bidang_pengirim,
                    'tipe_tujuan'           => DEST_INTERNAL,
                    'nama_penerima'         => $mail->penerima,
                    'perihal'               => $mail->perihal,
                    'persetujuan_direksi'   => $mail->persetujuan_direksi,
                    'sifat_surat'           => $mail->sifat_surat,
                    'bulan'                 => date('m', strtotime($mail->tanggal_surat)),
                    'tahun'                 => date('Y', strtotime($mail->tanggal_surat)),
                    'sandi_perihal'         => $mail->sandi,
                    'type_nomor'            => SURAT_BIASA,
                    'created_by'            => 0
                ));
                if ($gen_service_result){
                    $result['status'] = TRUE;
                    $result['nomor_surat'] = $gen_service_result->nomor_surat;
                    
                    //update surat
                    $this->rel_rekap_m->save_where(array('nomor'=>$gen_service_result->nomor_surat),array('surat'=>$mailId,'tipe'=>MAIL_NODIN));
                    $this->rel_nodin_m->save(array('nomor_surat'=>$gen_service_result->nomor_surat),$mailId);
                }else{
                    $result['message'] = 'Gagal mengenerate nomor surat dari web service';
                }
            }
            
        }else{
            $result['message'] = 'Parameter ID tidak didefinisikan';
        }
        
        $this->response($result);
    }
}

/**
 * Filename : Nodin.php
 * Location : application/controllers/service/Nodin.php
 */
