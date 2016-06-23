<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Class of Service
 * Handle management of user, group and privileges
 *
 * @author marwansaleh 11:13:40 PM
 */
class Service extends Library {
    private $ci;
    function __construct() {
        parent::__construct();
        $this->ci =& get_instance(); 
    }
    
    public function get_users($array=FALSE){
        $users = NULL;
        
        if (!isset($this->ci->user_m)){
            $this->ci->load->model('user_m');
        }
        
        $users = $this->ci->user_m->get();
        
        if ($users){
            if ($array){
                $result = array();
                foreach ($users as $item){
                    $result[$item->id] = $item;
                }

                return $result;
            }
        }
        
        return $users;
    }
    
    public function get_bidang($array=FALSE){
        if (!isset($this->ci->bidang_m)){
            $this->ci->load->model('bidang_m');
        }
        
        $items = $this->ci->bidang_m->get();
        
        if ($items){
            if ($array){
                $result = array();
                foreach ($items as $item){
                    $result[$item->id] = $item;
                }
            }
            
            return $result;
        }
        
        return $items;
    }
    
    public function get_groups($array=FALSE){
       if (!isset($this->ci->group_m)){
            $this->ci->load->model('group_m');
        }
        
        $items = $this->ci->group_m->get();
        
        if ($items){
            if ($array){
                $result = array();
                foreach ($items as $item){
                    $result[$item->id] = $item;
                }
            }
            
            return $result;
        }
        
        return $items;
    }
}

/**
 * Filename : Service.php
 * Location : application/libraries/Service.php
 */
