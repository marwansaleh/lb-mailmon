<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class MY_Controller inherit from CI_Controller 
 * which will be the base controller for all controllers used
 * in this application
 *
 * @author marwansaleh 5:42:25 PM
 */
class MY_Controller extends CI_Controller {
    public $data = array();
    protected $userlib = NULL;
    
    function __construct() {
        parent::__construct();
        
        if (!isset($this->session)){
            $this->load->library('session') or die('Can not load library Session');
        }
        
        $this->userlib = Userlib::getInstance();
    }
}

class Admin_Controller extends MY_Controller {
    private static $_menu_level_deep = 0;
    private static $_menu_level_base_parent = 0;
    
    function __construct() {
        parent::__construct();
        
        $class_name = $this->router->fetch_class();
        $role_id_by_url = NULL;
        if ($class_name != 'error'){
            $role_id_by_url = $this->get_roleid_by_url();
        }
        
        if (!$this->userlib->isLoggedin()){
            $referrer = set_url_back(uri_string());
            redirect(get_action_url('auth/index/' . $referrer));
        }else if ($class_name != 'error' && $role_id_by_url){
            if (!$this->userlib->has_page_access($role_id_by_url->id)){
                redirect(site_url('error/unauthorize'));
            }
        }
        
        //load neccessary models
        $this->load->model(array('mtr_menu_m'));
        //store user loggedin detail
        $this->data['me'] = $this->userlib->me();
        //any notification fo me ?
        $this->data['notifications'] = $this->get_unread_disposition();
        //load helper
        $this->load->library('form_validation');
        //init breadcumb
        $this->data['breadcumb'] = array();
        //set mainmenu
        $this->data['mainmenus'] = $this->get_user_menu();
        //var_dump($this->data['mainmenus']);
        $this->data['active_menu'] = $role_id_by_url;
        //set default active module
        $this->data['module_active'] = $this->get_active_module_by_url();
    }
    protected function get_active_module_by_url(){
        $url_array = explode('/', uri_string());
        
        //find modul id from url array
        if (isset($url_array[0])){
            return $url_array[0];
        }
        return module_name(CT_MODULE_OTHER);
    }
    protected function get_user_menu(){
        
        $result = $this->userlib->get_user_menu();
        //var_dump($result);exit;
        if ($result){
            $menu_array = array('parents' => array(),'items' => array());
            foreach ($result as $menu_item){
                if (!$menu_item->granted){
                    continue;
                }
                $menu_array['parents'][$menu_item->parent][] = $menu_item->id;
                $menu_array['items'][$menu_item->id] = $menu_item;
            }
            return $this->_hierarchy_menus($menu_array); //start level deep from 0
        }else{
            return NULL;
        }
    }
    private function _hierarchy_menus($menus, $parent=0, $level_deep=0){
        $menulist = array();
        if (isset($menus['parents'][$parent])){
            
            //get menu item for each id where parent = $parent
            foreach ($menus['parents'][$parent] as $menu_id){
                $menuitem = $menus['items'][$menu_id];
                //jika parent sama dengan base, kembalikan level ke 0
                if (self::$_menu_level_deep > 0 && $menuitem->parent == self::$_menu_level_base_parent){
                    $level_deep = 1;
                }
                //apakah sudah sampai pada level yang diinginkan
                if (self::$_menu_level_deep > 0 && $level_deep >= self::$_menu_level_deep){
                    //echo 'level:'.$level_deep.' counter:'.self::$menu_level_deep;exit;
                    $menuitem->children = NULL;
                }else{
                    //does menu has submenu ?
                    if (isset($menus['parents'][$menuitem->id])){
                        $menuitem->children = $this->_hierarchy_menus($menus, $menuitem->id, ($level_deep+1));
                    }else{
                        $menuitem->children = NULL;
                    }
                }
                
                
                $menulist[] = $menuitem;
            }
        }
        return $menulist;
    }
    protected function get_roleid_by_url(){
        if (!isset($this->mtr_menu_m)){
            $this->load->model('mtr_menu_m');
        }
        $menu_link = rtrim(uri_string(),'/');
        $this->db->like('link',$menu_link);
        $menuitem = $this->mtr_menu_m->get_select_where('id,parent',NULL,TRUE);
        if ($menuitem){
            $result = new stdClass();
            $result->id = $menuitem->id;
            $result->parent = $menuitem->parent;
            return $result;
        }else{
            return NULL;
        }
    }
    protected function get_unread_disposition(){
        if (!isset($this->rel_disposisi_m)){
            $this->load->model(array('rel_disposisi_m'));
        }
        $me = $this->userlib->me();
        $unread = $this->rel_disposisi_m->get_by(array('penerima'=>$me->id, 'diterima'=>STATUS_DIS_NOTACCEPT));
        if ($unread){
            $disposisi = array();
            $srv = new Service();
            $users = $srv->get_users(TRUE);
            foreach ($unread as $item){
                $item->pengirim = isset($users[$item->pengirim]) ? $users[$item->pengirim] : $item->pengirim;
                $item->penerima = isset($users[$item->penerima]) ? $users[$item->penerima] : $item->penerima;
                
                $disposisi [] = $item;
            }
        }else{
            $disposisi = NULL;
        }
        
        return $disposisi;
    }
}

/**
 * Filename : MY_Controller.php
 * Location : applications/core/MY_Controller.php
 */
