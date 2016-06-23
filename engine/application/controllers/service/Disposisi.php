<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Disposisi
 *
 * @author marwansaleh 1:31:58 PM
 */
class Disposisi extends REST_Api {
    private $_remap_fields = array(
        'id'                => 'id',
        'tipe'              => 'tipe',
        'mail'              => 'mail',
        'pengirim'          => 'pengirim',
        'penerima'          => 'penerima',
        'keterangan'        => 'keterangan',
        'waktu_kirim'       => 'waktu_kirim',
        'diterima'          => 'diterima',
        'waktu_terima'      => 'waktu_terima',
        'acceptable'        => 'acceptable',
        'disposisable'      => 'disposisable',
        'viewable'          => 'viewable',
        'status'            => 'status',
        'to_sign'           => 'to_sign'
    );
    
    function __construct($config = 'rest') {
        parent::__construct($config);
    }
    
    function index_get(){
        $this->load->model(array('rel_disposisi_m','rel_incoming_m','rel_outgoing_m','rel_nodin_m'));
        $userlib = Userlib::getInstance();
        $me = $userlib->me();
        
        $user_id = $this->get('userId');
        
        $draw = $this->get('draw') ? $this->get('draw') : 1;
        $length = $this->get('length') ? $this->get('length') : 10;
        $search = $this->get('search') ? $this->get('search') : NULL;
        $start = $this->get('start') ? $this->get('start') : 0;
        
        if (!$me->root){
            $where = array('penerima'=>$user_id);
        }else{
            $where = NULL;
        }
        $totalRecords = $this->rel_disposisi_m->get_count($where);
        
        //set filtered if any
        if ($search && $search['value']){
            $this->db->like('keterangan', $search['value']);
        }
        //get filtered count
        $totalFiltered = $this->rel_disposisi_m->get_count($where);
        
        $result = array('draw' => $draw, 'start'=>$start, 'recordsTotal'=>$totalRecords, 'recordsFiltered'=>$totalFiltered, 'items'=>array());

        if ($totalRecords > 0){
            $userService = new Service();
            $users = $userService->get_users(TRUE);
            
            //set filtered if any
            if ($search && $search['value']){
                $this->db->like('keterangan', $search['value']);
            }
            //apply offset and limit of data
            $this->db->offset($start)->limit($length);
            
            $query_result = $this->rel_disposisi_m->get_by($where);
            if ($query_result){
                $items = array();
                foreach ($query_result as $item){
                    //manipulate result item before return
                    $item->user = $user_id;
                    if ($item->tipe==MAIL_OUTGOING){
                        $mail = $this->rel_outgoing_m->get($item->mail);
                        $item->to_sign = $mail->status==STATUS_OUT_SIGNING && ($item->status == STATUS_OUT_SIGNING && $item->penerima == $user_id);
                    }else if ($item->tipe==MAIL_NODIN){
                        $mail = $this->rel_nodin_m->get($item->mail);
                        $item->to_sign = $mail->status==STATUS_NODIN_SIGNING &&  ($item->status == STATUS_NODIN_SIGNING && $item->penerima == $user_id);
                    }else{
                        $item->to_sign = FALSE;
                    }
                    $items [] = $this->_proccess_item($item, $users);
                }
                //manipulate result item before return
                $result['items'] = $this->remap_fields($this->_remap_fields, $items);
            }
        }
        $this->response($result);
        
    }
    
    public function get_get($item_id){
        $this->load->model(array('rel_disposisi_m','rel_incoming_m','rel_outgoing_m','rel_nodin_m'));
        $result = array('status'=>FALSE);
        
        $disposisi = $this->rel_disposisi_m->get($item_id);
        if ($disposisi){
            $result['status'] = TRUE;
            $result['item'] = $this->remap_fields($this->_remap_fields, $this->_proccess_item($item_id));
        }else{
            $result['message'] = 'Data disposisi tidak ditemukan';
        }
        
        $this->response($result);
    }
    
    private function _proccess_item($item=NULL, $users=NULL){
        if ($item){
            $item->diterima = $item->diterima == 1;
            if ($item->penerima==$item->user){
                $item->acceptable = !$item->diterima && $item->user==$item->penerima ? TRUE : FALSE;
                $item->disposisable = !$item->acceptable;
            }else{
                $item->acceptable = FALSE;
                $item->disposisable = FALSE;
            }
            
            $item->viewable = !$item->acceptable;
            
            if ($users){
                $item->pengirim = isset($users[$item->pengirim]) ? $users[$item->pengirim]->nama : $item->pengirim;
                $item->penerima = isset($users[$item->penerima]) ? $users[$item->penerima]->nama : $item->penerima;
            }
            if ($item->tipe == MAIL_INCOMING){
                $item->status = incoming_status($item->status);
                $item->mail = $this->rel_incoming_m->get($item->mail);
            }else if ($item->tipe == MAIL_OUTGOING){
                $item->status = outgoing_status($item->status);
                $item->mail = $this->rel_outgoing_m->get($item->mail);
            }else if ($item->tipe == MAIL_NODIN){
                $item->status = nodin_status($item->status);
                $item->mail = $this->rel_nodin_m->get($item->mail);
            }
            
        }
        return $item;
    }
    
    public function accept_post($item_id){
        $this->load->model(array('rel_disposisi_m'));
        $result = array('status'=>FALSE);
        
        if ($this->rel_disposisi_m->save(array('diterima'=>1,'waktu_terima'=>date('Y-m-d H:i:s')), $item_id)){
            $result['status'] = TRUE;
        }else{
            $result['message'] = $this->rel_disposisi_m->get_last_message();
        }
        
        $this->response($result);
    }
    
    public function history_get($mail_id){
        $this->load->model(array('rel_disposisi_m','rel_incoming_m','rel_outgoing_m','rel_nodin_m'));
        $result = array('item_count'=>0, 'items' => array());
        
        $userlib = Userlib::getInstance();
        $me = $userlib->me();
        
        $mail_type = $this->get('type');
        //get mail
        $this->db->order_by('mail');
        $history = $this->rel_disposisi_m->get_by(array('mail'=>$mail_id, 'tipe'=>$mail_type));
        if ($history){
            //get user from service
            $userService = new Service();
            $users = $userService->get_users(TRUE);
            foreach($history as $item){
                $item->user = $me->id;
                $result['items'][] = $this->_proccess_item($item, $users);
            }
        }
        
        $result['item_count'] = count($result['items']);
        
        $this->response($result);
    }
    
    public function index_post(){
        $this->load->model(array('rel_incoming_m','rel_disposisi_m','rel_outgoing_m','rel_nodin_m','rel_rekap_m'));
        $result = array('status'=> FALSE);
        
        $mail_id = $this->post('mail_id');
        $mail_type = $this->post('mail_type');
        if ($mail_id && $mail_type){
            if ($this->post('penerima')){
                $data = array(
                    'tipe'              => $mail_type,
                    'mail'              => $mail_id,
                    'pengirim'          => $this->post('user_id'),
                    'penerima'          => $this->post('penerima'),
                    'keterangan'        => $this->post('keterangan'),
                    'waktu_kirim'       => date('Y-m-d H:i:s'),
                    'status'            => $mail_type == MAIL_INCOMING ? STATUS_IN_DISPOSED : $this->post('status')
                );

                $inserted_id = $this->rel_disposisi_m->save($data);
                if ($inserted_id){
                    //udpate status surat masuk menjadui disposisi
                    if ($mail_type == MAIL_INCOMING){
                        $this->rel_incoming_m->save(array('status'=>$data['status']), $mail_id);
                    }else if ($mail_type == MAIL_OUTGOING){
                        //pastikan nomor surat ada jika status sign
                        $update_outgoing = array('status' => $data['status'], 'posisi_akhir'=> $data['penerima']);
                        if ($data['status'] == STATUS_OUT_SIGNED){
                            $outgoing = $this->rel_outgoing_m->get($mail_id);
                            if (!$outgoing->nomor_surat){
                                //generate nomor surat dari service nomor surat generator
                                $service = new Service();
                                $gen_service_result = $service->generate_nomor(array(
                                    'bidang_pengirim'       => $outgoing->bidang_pengirim,
                                    'tipe_tujuan'           => $outgoing->tipe_tujuan,
                                    'nama_penerima'         => $outgoing->penerima,
                                    'perihal'               => $outgoing->perihal,
                                    'persetujuan_direksi'   => $outgoing->persetujuan_direksi,
                                    'sifat_surat'           => $outgoing->sifat_surat,
                                    'bulan'                 => date('m', strtotime($outgoing->tanggal_surat)),
                                    'tahun'                 => date('Y', strtotime($outgoing->tanggal_surat)),
                                    'sandi_perihal'         => $outgoing->sandi,
                                    'type_nomor'            => $outgoing->tipe_surat,
                                    'created_by'            => 0
                                ));
                                if ($gen_service_result){
                                    $update_outgoing['nomor_surat'] = $gen_service_result->nomor_surat;

                                    //update surat
                                    $this->rel_rekap_m->save_where(array('nomor'=>$gen_service_result->nomor_surat),array('surat'=>$outgoing->id,'tipe'=>MAIL_OUTGOING));
                                    $this->rel_outgoing_m->save(array('nomor_surat'=>$gen_service_result->nomor_surat),$outgoing->id);
                                }
                            }
                        }
                        $this->rel_outgoing_m->save($update_outgoing, $mail_id);
                    }else if ($mail_type == MAIL_NODIN){
                        //pastikan nomor surat ada jika status sign
                        $update_nodin = array('status' => $data['status'], 'posisi_akhir'=> $data['penerima']);
                        if ($data['status'] == STATUS_NODIN_SIGNED){
                            $nodin = $this->rel_nodin_m->get($mail_id);
                            if (!$nodin->nomor_surat){
                                //generate nomor surat dari service nomor surat generator
                                $service = new Service();
                                $gen_service_result = $service->generate_nomor(array(
                                    'bidang_pengirim'       => $nodin->bidang_pengirim,
                                    'tipe_tujuan'           => DEST_INTERNAL,
                                    'nama_penerima'         => $nodin->penerima,
                                    'perihal'               => $nodin->perihal,
                                    'persetujuan_direksi'   => $nodin->persetujuan_direksi,
                                    'sifat_surat'           => $nodin->sifat_surat,
                                    'bulan'                 => date('m', strtotime($nodin->tanggal_surat)),
                                    'tahun'                 => date('Y', strtotime($nodin->tanggal_surat)),
                                    'sandi_perihal'         => $nodin->sandi,
                                    'type_nomor'            => SURAT_BIASA,
                                    'created_by'            => 0
                                ));
                                if ($gen_service_result){
                                    $update_nodin['nomor_surat'] = $gen_service_result->nomor_surat;

                                    //update surat
                                    $this->rel_rekap_m->save_where(array('nomor'=>$gen_service_result->nomor_surat),array('surat'=>$nodin->id,'tipe'=>MAIL_NODIN));
                                    $this->rel_nodin_m->save(array('nomor_surat'=>$gen_service_result->nomor_surat),$nodin->id);
                                }
                            }
                        }
                        $this->rel_nodin_m->save($update_nodin, $mail_id);
                    }
                    $result['status'] = TRUE;
                    $result['item'] = $this->rel_disposisi_m->get($inserted_id);
                }else{
                    $result['message'] = $this->rel_disposisi_m->get_last_message();
                }
            }else{
                $result['message'] = 'Penerima belum dipilih';
            }
        }else{
            $result['message'] = 'ID atau tipe surat tidak terdefinisi';
        }
        
        $this->response($result);
    }
}

/**
 * Filename : Disposisi.php
 * Location : application/controllers/service/Disposisi.php
 */
