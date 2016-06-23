<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of User
 *
 * @author marwansaleh 9:53:09 AM
 */
class User extends Admin_Controller {
    
    function __construct() {
        parent::__construct();
        $this->data['page_title'] = 'User Accounts';
    }
    
    public function index(){
        $this->data['page_subtitle'] = 'Daftar User';
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], '<i class="fa fa-home"></i> Home', get_action_url('dashboard'));
        breadcumb_add($this->data['breadcumb'], 'Daftar User', get_action_url('system/user'),TRUE);
        
        $this->data['subview'] = 'system/user/index';
        $this->load->view('_layout_main', $this->data);
    }
    
    public function edit($id=NULL){
        $this->data['id'] = $id;
        
        if ($id){
            $this->data['page_subtitle'] = 'Edit User';
        }else{
            $this->data['page_subtitle'] = 'Tambah User';
        }
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], '<i class="fa fa-home"></i> Home', get_action_url('dashboard'));
        breadcumb_add($this->data['breadcumb'], 'Daftar User', get_action_url('system/user'));
        breadcumb_add($this->data['breadcumb'], 'Update User', get_action_url('system/user/edit/'.$id), TRUE);
        
        $this->data['back_url'] = get_action_url('system/user');
        
        $this->data['subview'] = 'system/user/edit';
        $this->load->view('_layout_main', $this->data);
    }
}

/**
 * Filename : User.php
 * Location : application/controllers/system/User.php
 */
