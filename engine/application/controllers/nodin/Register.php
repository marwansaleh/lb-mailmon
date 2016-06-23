<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Register
 *
 * @author marwansaleh 9:53:09 AM
 */
class Register extends Admin_Controller {
    
    function __construct() {
        parent::__construct();
        $this->data['page_title'] = 'Nota Dinas';
    }
    
    public function index(){
        $this->data['page_subtitle'] = 'Daftar nota dinas';
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], '<i class="fa fa-home"></i> Home', get_action_url('dashboard'));
        breadcumb_add($this->data['breadcumb'], 'Daftar Surat', get_action_url('nodin/register'),TRUE);
        
        $this->data['subview'] = 'nodin/index';
        $this->load->view('_layout_main', $this->data);
    }
    
    public function pilihtemplate(){
        $this->load->model(array('rel_template_m'));
        
        //get mail item
        $templates =  $this->rel_template_m->get_by(array('tipe'=>MAIL_NODIN));
        if ($templates){
            $data['templates'] = $templates;
        }else{
            $data['error'] = 'Data surat keluar tidak ditemukan';
        }
        
        $this->load->view('nodin/pilihtemplate', $data);
    }
    
    public function edit($id=NULL, $template_id=NULL){
        $this->load->model(array('rel_nodin_m','mtr_sifat_m'));
        
        $this->data['id'] = $id;
        
        if ($id){
            $this->data['page_subtitle'] = 'Edit Nota Dinas';
            $item = $this->rel_nodin_m->get($id);
            
            if ($item->pengirim != $this->userlib->get_userid()){
                $this->session->set_flashdata('message', 'Maaf. Anda tidak bisa merubah surat yang dibuat user lain');
                $this->session->set_flashdata('message_type', 'error');

                redirect('nodin/register');
                exit;
            }
            if ($item->pagestyle){
                $item->pagestyle = json_decode($item->pagestyle);
            }else{
                $item->pagestyle = NULL;
            }
            
            $this->data['item'] = $item;
        }else{
            $this->data['page_subtitle'] = 'Tulis Nota Dinas';
            $item = $this->rel_nodin_m->get_new();
            $item->tanggal_surat = date('Y-m-d');
            
            $item->pagestyle = NULL;
            
            if ($template_id){
                $this->load->model('rel_template_m');
                $template = $this->rel_template_m->get($template_id);
                if ($template){
                    $item->header = $template->header;
                    $item->footer = $template->footer;
                    $item->isi_surat = $template->content;
                    $item->pagestyle = $template->pagestyle ? json_decode($template->pagestyle): NULL;
                }
            }
            $this->data['item'] = $item;
        }
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], '<i class="fa fa-home"></i> Home', get_action_url('dashboard'));
        breadcumb_add($this->data['breadcumb'], 'Daftar Surat', get_action_url('nodin/register'));
        breadcumb_add($this->data['breadcumb'], 'Update Surat', get_action_url('nodin/register/edit/'.$id));
        
        //suporting data
        $this->data['sifat_surat'] = $this->mtr_sifat_m->get();
        $this->data['pagestyles'] = wordpagestyling(TRUE);
        
        $this->data['back_url'] = get_action_url('nodin/register');
        
        $this->data['subview'] = 'nodin/edit';
        $this->load->view('_layout_main', $this->data);
    }
    
    private function _prepare_bidang_options($bidang){
        $result = array();
        
        $service = new Service();
        $bidang_service = $service->get_bidang(TRUE);
        if ($bidang_service){
            if (isset($bidang_service[$bidang])){
                $selected = $bidang_service[$bidang];
                if ($selected->parent == 0){
                    $result [] = $selected;
                    
                    foreach ($bidang_service as $item){
                        if ($item->parent == $selected->id){
                            $result[] = $item;
                        }
                    }
                }else{
                    //ambil parentnya
                    $parent = $bidang_service[$selected->parent];
                    $result [] = $parent;
                    foreach ($bidang_service as $item){
                        if ($item->parent == $parent->id){
                            $result[] = $item;
                        }
                    }
                }
            }
        }
        
        return $result;
    }
    
    public function upload($id=NULL){
        if (!$id){
            $this->session->set_flashdata('message', 'Parameter ID tidak didefinisikan');
            $this->session->set_flashdata('message_type', 'error');
            
            redirect('nodin/register');
            exit;
        }
        $this->data['page_subtitle'] = 'Upload Dokumen';
        
        $this->load->model(array('rel_nodin_m','rel_dokumen_m'));
        $mail =  $this->rel_nodin_m->get($id);
        if (!$mail){
            $this->session->set_flashdata('message', 'Data nota dinas dengan ID:'.$id.' tidak ditemukan');
            $this->session->set_flashdata('message_type', 'error');
            
            redirect('nodin/register');
            exit;
        }
        $this->data['mail'] = $mail;
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], '<i class="fa fa-home"></i> Home', get_action_url('dashboard'));
        breadcumb_add($this->data['breadcumb'], 'Daftar Surat', get_action_url('nodin/register'));
        breadcumb_add($this->data['breadcumb'], 'Upload dokumen', get_action_url('nodin/register/upload/'.$id));
        
        $this->data['back_url'] = get_action_url('nodin/register/edit/'.$id);
        $this->data['finish_url'] = get_action_url('nodin/register');
        
        $this->data['subview'] = 'nodin/upload';
        $this->load->view('_layout_main', $this->data);
    }
    
    public function approval($item_id){
        $this->load->model(array('rel_nodin_m','rel_dokumen_m'));
        $data = array('me'=>  $this->userlib->me());
        
        //get mail item
        $mail =  $this->rel_nodin_m->get($item_id);
        if ($mail){
            $mail->type = MAIL_NODIN;
            
            $dokumens = $this->rel_dokumen_m->get_by(array('tipe'=>MAIL_NODIN, 'mail'=>$mail->id));
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
            $data['error'] = 'Data nota dinas tidak ditemukan';
        }
        
        $this->load->view('nodin/approval', $data);
    }
    public function sign($item_id){
        $this->load->model(array('rel_nodin_m','rel_dokumen_m'));
        $data = array('me'=>  $this->userlib->me());
        
        //get mail item
        $mail =  $this->rel_nodin_m->get($item_id);
        if ($mail){
            $mail->type = MAIL_NODIN;
            $srv = new Service();
            $users = $srv->get_users(TRUE);
            $mail->pengirim = isset($users[$mail->pengirim]) ? $users[$mail->pengirim]->nama : $mail->pengirim;
            $mail->penerima = isset($users[$mail->penerima]) ? $users[$mail->penerima]->nama : $mail->penerima;
            
            $dokumens = $this->rel_dokumen_m->get_by(array('tipe'=>MAIL_NODIN, 'mail'=>$mail->id));
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
            $data['error'] = 'Data nota dinas tidak ditemukan';
        }
        
        $this->load->view('outgoing/sign', $data);
    }
    public function view($item_id){
        $this->load->model(array('rel_nodin_m','rel_dokumen_m'));
        $data = array('me'=>  $this->userlib->me());
        
        $mail =  $this->rel_nodin_m->get($item_id);
        
        if ($mail){
            $srv = new Service();
            $users = $srv->get_users(TRUE);
            $mail->pengirim = isset($users[$mail->pengirim]) ? $users[$mail->pengirim]->nama : $mail->pengirim;
            $mail->penerima = isset($users[$mail->penerima]) ? $users[$mail->penerima]->nama : $mail->penerima;
            
            $dokumens = $this->rel_dokumen_m->get_by(array('tipe'=>MAIL_NODIN, 'mail'=>$mail->id));
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
            $data['error'] = 'Data nota dinas tidak ditemukan';
        }
        
        $this->load->view('nodin/lihat', $data);
    }
}

/**
 * Filename : Register.php
 * Location : application/controllers/incoming/Register.php
 */
