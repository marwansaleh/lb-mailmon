<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of User
 *
 * @author marwansaleh 1:31:58 PM
 */
class User extends REST_Api {
    private $_remap_fields = array(
        'id'            => 'id',
        'userid'        => 'userid',
        'last_login'    => 'last_login',
        'last_ip'       => 'last_ip',
        'last_url'      => 'last_url',
        'session_id'    => 'session_id'
    );
    
    function __construct($config = 'rest') {
        parent::__construct($config);
    }
    
    private function _proccess_item($item=NULL){
        if ($item){
            $item->module = $this->mtr_module_m->get($item->module);
            $item->icon = $item->icon ? '<i class="fa '.$item->icon.'"></i>':NULL;
            $item->hidden = $item->hidden==1 ? TRUE : FALSE;
        }
        return $item;
    }
    
    public function index_post(){
        $this->load->model(array('auth_user_m'));
        $result = array('status'=> FALSE);
        
        $data = array(
            'userid'    => $this->post('userid')
        );
        
        $inserted_id = $this->auth_user_m->save($data);
        if ($inserted_id){
            $result['status'] = TRUE;
            $result['item'] = $this->auth_user_m->get($inserted_id);
        }else{
            $result['message'] = $this->auth_user_m->get_last_message();
        }
        
        $this->response($result);
    }
}

/**
 * Filename : User.php
 * Location : application/controllers/service/User.php
 */
