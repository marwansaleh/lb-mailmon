<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Menu
 *
 * @author marwansaleh 9:53:09 AM
 */
class Menu extends Admin_Controller {
    
    function __construct() {
        parent::__construct();
        $this->data['page_title'] = 'Menu Management';
    }
    
    public function index(){
        $this->data['page_subtitle'] = 'Daftar menu';
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], '<i class="fa fa-home"></i> Home', get_action_url('dashboard'));
        breadcumb_add($this->data['breadcumb'], 'Daftar Menu', get_action_url('system/menu'),TRUE);
        
        $this->data['subview'] = 'system/menu/index';
        $this->load->view('_layout_main', $this->data);
    }
    
    public function edit($id=NULL){
        $this->load->model(array('mtr_menu_m','mtr_module_m','auth_group_page_m'));
        
        $this->data['id'] = $id;
        
        if ($id){
            $this->data['page_subtitle'] = 'Edit Menu';
            $item = $this->mtr_menu_m->get($id);
            $access = $this->auth_group_page_m->get_by(array('page_id'=>$item->id));
            if ($access){
                $item->access = array();
                foreach ($access as $acc){
                    $item->access[$acc->group_id] = $acc->granted==1 ? TRUE : FALSE;
                }
            }
        }else{
            $this->data['page_subtitle'] = 'Tambah Menu';
            $item = $this->mtr_menu_m->get_new();
        }
        $this->data['item'] = $item;
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], '<i class="fa fa-home"></i> Home', get_action_url('dashboard'));
        breadcumb_add($this->data['breadcumb'], 'Daftar Menu', get_action_url('system/menu'));
        breadcumb_add($this->data['breadcumb'], 'Update Menu', get_action_url('system/menu/edit/'.$id), TRUE);
        
        //suporting data
        $this->data['modules'] = $this->mtr_module_m->get();
        $this->data['groups'] = $this->_get_user_grup_service();
        
        $this->data['back_url'] = get_action_url('system/menu');
        
        $this->data['subview'] = 'system/menu/edit';
        $this->load->view('_layout_main', $this->data);
    }
    
    private function _get_user_grup_service(){
        $service_url = config_item('service_url') . 'grup/all';
        
        $ch = curl_init(); 
        // set url 
        curl_setopt($ch, CURLOPT_URL, $service_url); 

        curl_setopt($ch,CURLOPT_POST,FALSE); 
        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

        // $output contains the output string 
        $output = curl_exec($ch); 
        if (curl_errno($ch)){
            return FALSE;
        }else{
            //echo $output; exit;
            $output = json_decode($output);
        }
        // close curl resource to free up system resources 
        curl_close($ch);  
        
        return isset($output->items) ? $output->items : NULL;
    }
}

/**
 * Filename : Menu.php
 * Location : application/controllers/system/Menu.php
 */
