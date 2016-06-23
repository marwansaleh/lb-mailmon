<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Disposisi
 *
 * @author marwansaleh 9:53:09 AM
 */
class Disposisi extends Admin_Controller {
    
    function __construct() {
        parent::__construct();
        $this->data['page_title'] = 'Disposisi';
    }
    
    public function index(){
        $this->data['page_subtitle'] = 'Daftar Disposisi';
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], '<i class="fa fa-home"></i> Home', get_action_url('dashboard'));
        breadcumb_add($this->data['breadcumb'], 'Daftar Disposisi', get_action_url('disposisi'),TRUE);
        
        $this->data['subview'] = 'disposisi/index';
        $this->load->view('_layout_main', $this->data);
    }
    
    public function disposisi($item_id){
        $this->load->model(array('rel_incoming_m','rel_dokumen_m','rel_disposisi_m','rel_outgoing_m','rel_nodin_m'));
        $data = array('me'=>  $this->userlib->me());
        
        //get disposisi item
        $disposisi = $this->rel_disposisi_m->get($item_id);
        if ($disposisi){
            $data['disposisi'] = $disposisi;
            
            if ($disposisi->tipe == MAIL_INCOMING){
                $mail =  $this->rel_incoming_m->get($disposisi->mail);
            }elseif ($disposisi->tipe == MAIL_OUTGOING){
                $mail =  $this->rel_outgoing_m->get($disposisi->mail);
                $srv = new Service();
                $users = $srv->get_users(TRUE);
                $mail->pengirim = isset($users[$mail->pengirim]) ? $users[$mail->pengirim]->nama : $mail->pengirim;
            }elseif ($disposisi->tipe == MAIL_NODIN){
                $mail =  $this->rel_nodin_m->get($disposisi->mail);
                $srv = new Service();
                $users = $srv->get_users(TRUE);
                $mail->pengirim = isset($users[$mail->pengirim]) ? $users[$mail->pengirim]->nama : $mail->pengirim;
                $mail->penerima = isset($users[$mail->penerima]) ? $users[$mail->penerima]->nama : $mail->penerima;
            }
            $dokumens = $this->rel_dokumen_m->get_by(array('tipe'=>$disposisi->tipe, 'mail'=>$mail->id));
            if ($dokumens){
                $mail->dokumen = array();
                foreach ($dokumens as $dokumen){
                    $dokumen->download_url = get_action_url('download/index/'.urlencode(base64_encode($dokumen->file_name)));
                    $mail->dokumen [] = $dokumen;
                }
            }else{
                $mail->dokumen = NULL;
            }
            
            $data['mail'] = $mail;
        }else{
            $data['error'] = 'Data disposisi tidak ditemukan';
        }
        
        $this->load->view('disposisi/kirim_baru', $data);
    }
    
    public function view($item_id){
        $this->load->model(array('rel_incoming_m','rel_dokumen_m','rel_disposisi_m','rel_outgoing_m','rel_nodin_m'));
        $data = array('me'=>  $this->userlib->me());
        
        //get disposisi item
        $disposisi = $this->rel_disposisi_m->get($item_id);
        if ($disposisi){
            $data['disposisi'] = $disposisi;
            
            if ($disposisi->tipe == MAIL_INCOMING){
                $mail =  $this->rel_incoming_m->get($disposisi->mail);
            }elseif ($disposisi->tipe == MAIL_OUTGOING){
                $mail =  $this->rel_outgoing_m->get($disposisi->mail);
                $srv = new Service();
                $users = $srv->get_users(TRUE);
                $mail->pengirim = isset($users[$mail->pengirim]) ? $users[$mail->pengirim]->nama : $mail->pengirim;
            }elseif ($disposisi->tipe == MAIL_NODIN){
                $mail =  $this->rel_nodin_m->get($disposisi->mail);
                $srv = new Service();
                $users = $srv->get_users(TRUE);
                $mail->pengirim = isset($users[$mail->pengirim]) ? $users[$mail->pengirim]->nama : $mail->pengirim;
                $mail->penerima = isset($users[$mail->penerima]) ? $users[$mail->penerima]->nama : $mail->penerima;
            }
            $dokumens = $this->rel_dokumen_m->get_by(array('tipe'=>$disposisi->tipe, 'mail'=>$mail->id));
            if ($dokumens){
                $mail->dokumen = array();
                foreach ($dokumens as $dokumen){
                    $dokumen->download_url = get_action_url('download/index/'.urlencode(base64_encode($dokumen->file_name)));
                    $mail->dokumen [] = $dokumen;
                }
            }else{
                $mail->dokumen = NULL;
            }
            
            $data['mail'] = $mail;
        }else{
            $data['error'] = 'Data disposisi tidak ditemukan';
        }
        
        $this->load->view('disposisi/lihat', $data);
    }
    
    
}

/**
 * Filename : Register.php
 * Location : application/controllers/incoming/Register.php
 */
