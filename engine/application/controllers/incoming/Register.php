<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Register
 *
 * @author marwansaleh 9:53:09 AM
 */
class Register extends Admin_Controller {
    
    function __construct() {
        parent::__construct();
        $this->data['page_title'] = 'Incoming';
    }
    
    public function index(){
        $this->data['page_subtitle'] = 'Daftar surat masuk';
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], '<i class="fa fa-home"></i> Home', get_action_url('dashboard'));
        breadcumb_add($this->data['breadcumb'], 'Daftar Surat', get_action_url('incoming/register'),TRUE);
        
        $this->data['subview'] = 'incoming/register/index';
        $this->load->view('_layout_main', $this->data);
    }
    
    public function edit($id=NULL){
        $this->load->model(array('rel_incoming_m'));
        
        $this->data['id'] = $id;
        
        if ($id){
            $this->data['page_subtitle'] = 'Edit Surat Masuk';
            $item = $this->rel_incoming_m->get($id);
            
            if ($item->created_by != $this->userlib->get_userid()){
                $this->session->set_flashdata('message', 'Maaf. Anda tidak bisa merubah surat masuk yang dibuat user lain');
                $this->session->set_flashdata('message_type', 'error');

                redirect('incoming/register');
                exit;
            }
            $this->data['item'] = $item;
        }else{
            $this->data['page_subtitle'] = 'Register Surat Masuk';
            $item = $this->rel_incoming_m->get_new();
            $item->tanggal_surat = date('Y-m-d');
            $item->tanggal_terima = date('Y-m-d');
            $this->data['item'] = $item;
        }
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], '<i class="fa fa-home"></i> Home', get_action_url('dashboard'));
        breadcumb_add($this->data['breadcumb'], 'Daftar Surat', get_action_url('incoming/register'));
        breadcumb_add($this->data['breadcumb'], 'Update Surat', get_action_url('incoming/register/edit/'.$id));
        
        //suporting data
        
        $this->data['back_url'] = get_action_url('incoming/register');
        
        $this->data['subview'] = 'incoming/register/edit';
        $this->load->view('_layout_main', $this->data);
    }
    
    public function upload($id=NULL){
        if (!$id){
            $this->session->set_flashdata('message', 'Parameter ID tidak didefinisikan');
            $this->session->set_flashdata('message_type', 'error');
            
            redirect('incoming/register');
            exit;
        }
        $this->data['page_subtitle'] = 'Upload Dokumen';
        
        $this->load->model(array('rel_incoming_m','rel_dokumen_m'));
        $incoming =  $this->rel_incoming_m->get($id);
        if (!$incoming){
            $this->session->set_flashdata('message', 'Data surat masuk dengan ID:'.$id.' tidak ditemukan');
            $this->session->set_flashdata('message_type', 'error');
            
            redirect('incoming/register');
            exit;
        }
        $this->data['incoming'] = $incoming;
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], '<i class="fa fa-home"></i> Home', get_action_url('dashboard'));
        breadcumb_add($this->data['breadcumb'], 'Daftar Surat', get_action_url('incoming/register'));
        breadcumb_add($this->data['breadcumb'], 'Upload dokumen', get_action_url('incoming/register/upload/'.$id));
        
        $this->data['back_url'] = get_action_url('incoming/register/edit/'.$id);
        $this->data['finish_url'] = get_action_url('incoming/register');
        
        $this->data['subview'] = 'incoming/register/upload';
        $this->load->view('_layout_main', $this->data);
    }
    
    public function disposisi($id){
        
        $this->data['page_subtitle'] = 'Disposisi Surat Masuk';
        
        $this->load->model(array('rel_incoming_m','rel_dokumen_m'));
        $incoming =  $this->rel_incoming_m->get($id);
        if (!$incoming){
            $this->session->set_flashdata('message', 'Data surat masuk dengan ID:'.$id.' tidak ditemukan');
            $this->session->set_flashdata('message_type', 'error');
            
            redirect('incoming/register');
            exit;
        }
        $dokumens = $this->rel_dokumen_m->get_by(array('tipe'=>MAIL_INCOMING, 'mail'=>$id));
        if ($dokumens){
            $incoming->dokumen = array();
            foreach ($dokumens as $dokumen){
                $dokumen->download_url = get_action_url('download/index/'.urlencode(base64_encode($dokumen->file_name)));
                $incoming->dokumen [] = $dokumen;
            }
        }else{
            $incoming->dokumen = NULL;
        }
        
        $this->data['mail'] = $incoming;
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], '<i class="fa fa-home"></i> Home', get_action_url('dashboard'));
        breadcumb_add($this->data['breadcumb'], 'Daftar Surat', get_action_url('incoming/register'));
        breadcumb_add($this->data['breadcumb'], 'Disposisi', get_action_url('incoming/register/disposisi/'.$id));
        
        $this->data['back_url'] = get_action_url('incoming/register');
        
        $this->data['subview'] = 'incoming/register/disposisi';
        $this->load->view('_layout_main', $this->data);
    }
    
    public function view($item_id){
        $this->load->model(array('rel_incoming_m','rel_dokumen_m'));
        $data = array('me'=>  $this->userlib->me());
        
        $mail =  $this->rel_incoming_m->get($item_id);
        
        if ($mail){
            $dokumens = $this->rel_dokumen_m->get_by(array('tipe'=>MAIL_INCOMING, 'mail'=>$mail->id));
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
            $data['error'] = 'Data surat masuk tidak ditemukan';
        }
        
        $this->load->view('incoming/register/lihat', $data);
    }
}

/**
 * Filename : Register.php
 * Location : application/controllers/incoming/Register.php
 */
