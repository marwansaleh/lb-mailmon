<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Outgoing
 *
 * @author marwansaleh 1:31:58 PM
 */
class Outgoing extends REST_Api {
    private $_remap_fields = array(
        'id'                => 'id',
        'tanggal_surat'     => 'tanggal_surat',
        'nomor_surat'       => 'nomor_surat',
        'perihal'           => 'perihal',
        'pengirim'          => 'pengirim',
        'bidang_pengirim'   => 'bidang_pengirim',
        'penerima'          => 'penerima',
        'persetujuan_direksi'=> 'persetujuan_direksi',
        'tipe_surat'        => 'tipe_surat',
        'tipe_tujuan'       => 'tipe_tujuan',
        'sifat_surat'       => 'sifat_surat',
        'isi_surat'         => 'isi_surat',
        'status'            => 'status',
        'keterangan_status' => 'keterangan_status',
        'posisi_akhir'      => 'posisi_akhir',
        'dokumen'           => 'dokumen',
        'signer'            => 'signer',
        'created'           => 'created',
        'created_by'        => 'created_by',
        'editable'          => 'editable',
        'to_send'           => 'to_send',
        'to_sign'           => 'to_sign'
    );
    
    function __construct($config = 'rest') {
        parent::__construct($config);
    }
    
    function index_get(){
        $this->load->model(array('rel_outgoing_m','rel_dokumen_m'));
        $userlib = Userlib::getInstance();
        $me = $userlib->me();
        
        $user_id = $this->get('userId');
        
        $draw = $this->get('draw') ? $this->get('draw') : 1;
        $length = $this->get('length') ? $this->get('length') : 10;
        $search = $this->get('search') ? $this->get('search') : NULL;
        $start = $this->get('start') ? $this->get('start') : 0;
        
        if (!$me->root){
            $where = "created_by=$user_id OR signer=$user_id";
        }else{
            $where = NULL;
        }
        
        //$where = array('created_by' => $user_id);
        $totalRecords = $this->rel_outgoing_m->get_count($where);
        
        //set filtered if any
        if ($search && $search['value']){
            $this->db->like('nomor_surat', $search['value']);
            $this->db->or_like('perihal', $search['value']);
            $this->db->or_like('penerima', $search['value']);
        }
        //get filtered count
        $totalFiltered = $this->rel_outgoing_m->get_count($where);
        
        $result = array('draw' => $draw, 'start'=>$start, 'recordsTotal'=>$totalRecords, 'recordsFiltered'=>$totalFiltered, 'items'=>array());

        if ($totalRecords > 0){
            $userSrv = new Service();
            $users = $userSrv->get_users(TRUE);
            //set filtered if any
            if ($search && $search['value']){
                $this->db->like('nomor_surat', $search['value']);
                $this->db->or_like('penerima', $search['value']);
                $this->db->or_like('perihal', $search['value']);
            }
            //apply offset and limit of data
            $this->db->offset($start)->limit($length);
            
            $query_result = $this->rel_outgoing_m->get_by($where);
            if ($query_result){
                $items = array();
                foreach ($query_result as $item){
                    //manipulate result item before return
                    $item->to_send = ($item->created_by == $user_id || $item->pengirim == $user_id) && ($item->status == STATUS_OUT_NEW || $item->status == STATUS_OUT_REVISION) ? TRUE : FALSE;
                    $item->to_sign = $item->status == STATUS_OUT_SIGNING && $item->signer == $user_id;
                    $item->editable = $item->status != STATUS_OUT_SIGNED && ($item->created_by == $user_id || $item->pengirim == $user_id);
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
                $item->signer = isset($users[$item->signer]) ? $users[$item->signer] : $item->signer;
                $item->pengirim = isset($users[$item->pengirim]) ? $users[$item->pengirim] : $item->pengirim;
                $item->posisi_akhir = isset($users[$item->posisi_akhir]) ? $users[$item->posisi_akhir] : $item->posisi_akhir;
            }
            $dokumen = $this->rel_dokumen_m->get_by(array('mail'=>$item->id, 'tipe'=>MAIL_OUTGOING));
            if ($dokumen){
                $item->dokumen = array();
                foreach ($dokumen as $doc){
                    $doc->download_url = get_action_url('download/index/'.urlencode(base64_encode($doc->file_name)));
                    $item->dokumen [] = $doc;
                }
            }else{
                $item->dokumen = NULL;
            }
            
            $item->status = outgoing_status($item->status);
        }
        return $item;
    }
    
    public function index_post(){
        $this->load->model(array('rel_outgoing_m','rel_rekap_m'));
        $user_id = $this->post('user_id');
        
        $result = array('status'=> FALSE);
        
        $data = array(
            'tanggal_surat'     => $this->post('tanggal_surat'),
            'nomor_surat'       => $this->post('nomor_surat'),
            'perihal'           => $this->post('perihal'),
            'pengirim'          => $user_id,
            'bidang_pengirim'   => $this->post('bidang_pengirim'),
            'penerima'          => $this->post('penerima'),
            'persetujuan_direksi'=> $this->post('persetujuan_direksi'),
            'tipe_surat'        => $this->post('tipe_surat'),
            'tipe_tujuan'       => $this->post('tipe_tujuan'),
            'sifat_surat'       => $this->post('sifat_surat'),
            'signer'            => $this->post('signer'),
            'isi_surat'         => $this->post('isi_surat', FALSE),
            'header'            => $this->post('header'),
            'footer'            => $this->post('footer'),
            'status'            => STATUS_OUT_NEW,
            'posisi_akhir'      => $user_id,
            'created'           => time(),
            'created_by'        => $user_id
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
        
        $inserted_id = $this->rel_outgoing_m->save($data);
        if ($inserted_id){
            $result['status'] = TRUE;
            $item = $this->rel_outgoing_m->get($inserted_id);
            $result['item'] = $item;
            
            //update rekap surat
            $this->rel_rekap_m->save(array(
                'surat'     => $item->id,
                'nomor'     => $item->nomor_surat,
                'tipe'      => MAIL_OUTGOING,
                'perihal'   => $item->perihal,
                'tanggal'   => $item->tanggal_surat
            ));
        }else{
            $result['message'] = $this->rel_outgoing_m->get_last_message();
        }
        
        $this->response($result);
    }
    
    public function index_put($mail_id){
        $this->load->model(array('rel_outgoing_m','rel_rekap_m'));
        $user_id = $this->put('user_id');
        $result = array('status'=> FALSE);
        
        $data = array(
            'tanggal_surat'     => $this->put('tanggal_surat'),
            'nomor_surat'       => $this->put('nomor_surat'),
            'perihal'           => $this->put('perihal'),
            'penerima'          => $this->put('penerima'),
            'persetujuan_direksi'=> $this->put('persetujuan_direksi'),
            'tipe_surat'        => $this->put('tipe_surat'),
            'tipe_tujuan'       => $this->put('tipe_tujuan'),
            'sifat_surat'       => $this->put('sifat_surat'),
            'signer'            => $this->put('signer'),
            'isi_surat'         => $this->put('isi_surat', FALSE),
            'header'            => $this->put('header', FALSE),
            'footer'            => $this->put('footer', FALSE),
            'modified'          => time(),
            'modified_by'       => $user_id,
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
        
        if ($this->rel_outgoing_m->save($data, $mail_id)){
            $result['status'] = TRUE;
            $item = $this->rel_outgoing_m->get($mail_id);
            $result['item'] =  $item;
            
            //update rekap surat
            $rekap = $this->rel_rekap_m->get_by(array('surat'=>$item->id, 'tipe'=>MAIL_OUTGOING), TRUE);
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
                    'tipe'      => MAIL_OUTGOING,
                    'perihal'   => $item->perihal,
                    'tanggal'   => $item->tanggal_surat
                ));
            }
        }else{
            $result['message'] = $this->rel_outgoing_m->get_last_message();
        }
        
        $this->response($result);
    }
    
    public function gen_nomor_post(){
        $this->load->model(array('rel_rekap_m','rel_outgoing_m'));
        $result = array('status'=>FALSE);
        
        $mailId = $this->post('mail');
        if ($mailId){
            //check di rekap surat, apakah sudah terdaftar dan memiliki nomor
            $rekap_record = $this->rel_rekap_m->get_by(array('surat'=>$mailId, 'tipe'=>MAIL_OUTGOING), TRUE);
            if ($rekap_record && $rekap_record->nomor){
                $result['status'] = TRUE;
                $result['nomor_surat'] = $rekap_record->nomor;
                
                $this->rel_outgoing_m->save(array('nomor_surat'=>$rekap_record->nomor),$mailId);
            }else{
                $mail = $this->rel_outgoing_m->get($mailId);
                
                //generate nomor surat dari service nomor surat generator
                $service = new Service();
                $users = $service->get_users(TRUE);
                $gen_service_result = $service->generate_nomor(array(
                    'bidang_pengirim'       => $mail->bidang_pengirim,
                    'tipe_tujuan'           => $mail->tipe_tujuan,
                    'nama_penerima'         => isset($users[$mail->penerima]) ? $users[$mail->penerima]->nama : $mail->penerima,
                    'perihal'               => $mail->perihal,
                    'persetujuan_direksi'   => $mail->persetujuan_direksi,
                    'sifat_surat'           => $mail->sifat_surat,
                    'bulan'                 => date('m', strtotime($mail->tanggal_surat)),
                    'tahun'                 => date('Y', strtotime($mail->tanggal_surat)),
                    'sandi_perihal'         => $mail->sandi,
                    'type_nomor'            => $mail->tipe_surat,
                    'created_by'            => 0
                ));
                if ($gen_service_result){
                    $result['status'] = TRUE;
                    $result['nomor_surat'] = $gen_service_result->nomor_surat;
                    
                    //update surat
                    $this->rel_rekap_m->save_where(array('nomor'=>$gen_service_result->nomor_surat),array('surat'=>$mailId,'tipe'=>MAIL_OUTGOING));
                    $this->rel_outgoing_m->save(array('nomor_surat'=>$gen_service_result->nomor_surat),$mailId);
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
 * Filename : Outgoing.php
 * Location : application/controllers/service/Outgoing.php
 */
