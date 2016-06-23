<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Template
 *
 * @author marwansaleh 9:53:09 AM
 */
class Template extends Admin_Controller {
    
    function __construct() {
        parent::__construct();
        $this->data['page_title'] = 'Mail Template';
    }
    
    public function index(){
        $this->data['page_subtitle'] = 'Daftar template';
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], '<i class="fa fa-home"></i> Home', get_action_url('dashboard'));
        breadcumb_add($this->data['breadcumb'], 'Daftar template', get_action_url('system/template'),TRUE);
        
        $this->data['subview'] = 'system/template/index';
        $this->load->view('_layout_main', $this->data);
    }
    
    public function edit($id=NULL){
        $this->load->model(array('rel_template_m'));
        
        $this->data['id'] = $id;
        
        if ($id){
            $this->data['page_subtitle'] = 'Edit Template';
            $item = $this->rel_template_m->get($id);
            if ($item->pagestyle){
                $item->pagestyle = json_decode($item->pagestyle);
            }else{
                $item->pagestyle = NULL;
            }
        }else{
            $this->data['page_subtitle'] = 'Tambah Template';
            $item = $this->rel_template_m->get_new();
            $item->pagestyle = NULL;
        }
        $this->data['item'] = $item;
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], '<i class="fa fa-home"></i> Home', get_action_url('dashboard'));
        breadcumb_add($this->data['breadcumb'], 'Daftar Template', get_action_url('system/template'));
        breadcumb_add($this->data['breadcumb'], 'Update Template', get_action_url('system/template/edit/'.$id), TRUE);
        
        //suporting data
        $this->data['pagestyles'] = wordpagestyling(TRUE);
        
        $this->data['back_url'] = get_action_url('system/template');
        
        $this->data['subview'] = 'system/template/edit';
        $this->load->view('_layout_main', $this->data);
    }
}

/**
 * Filename : Template.php
 * Location : application/controllers/system/Template.php
 */
