<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Privilege
 *
 * @author marwansaleh 9:53:09 AM
 */
class Privilege extends Admin_Controller {
    
    function __construct() {
        parent::__construct();
        $this->data['page_title'] = 'Action Privileges';
    }
    
    public function index(){
        $this->data['page_subtitle'] = 'Daftar Akses Grup';
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], '<i class="fa fa-home"></i> Home', get_action_url('dashboard'));
        breadcumb_add($this->data['breadcumb'], 'Daftar Menu', get_action_url('system/privilege'),TRUE);
        
        $this->data['subview'] = 'system/privilege/index';
        $this->load->view('_layout_main', $this->data);
    }
    
    public function edit($id=NULL){
        $this->load->model(array('mtr_menu_m','mtr_module_m'));
        
        $this->data['id'] = $id;
        
        if ($id){
            $this->data['page_subtitle'] = 'Edit Menu';
            $item = $this->mtr_menu_m->get($id);
            $this->data['item'] = $item;
        }else{
            $this->data['page_subtitle'] = 'Tambah Menu';
            $item = $this->mtr_menu_m->get_new();
            $this->data['item'] = $item;
        }
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], '<i class="fa fa-home"></i> Home', get_action_url('dashboard'));
        breadcumb_add($this->data['breadcumb'], 'Daftar Menu', get_action_url('system/menu'));
        breadcumb_add($this->data['breadcumb'], 'Update Menu', get_action_url('system/menu/edit/'.$id), TRUE);
        
        //suporting data
        $this->data['modules'] = $this->mtr_module_m->get();
        
        $this->data['back_url'] = get_action_url('system/menu');
        
        $this->data['subview'] = 'system/menu/edit';
        $this->load->view('_layout_main', $this->data);
    }
}

/**
 * Filename : Privilege.php
 * Location : application/controllers/system/Privilege.php
 */
