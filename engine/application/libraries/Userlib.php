<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Class of Userlib
 * Handle management of user, group and privileges
 *
 * @author marwansaleh 11:13:40 PM
 */
class Userlib extends Library {
    private static $objInstance;
    
    private $_prefix_session_access = '_ACC_';
    private $_role_session = '_ROLE_SESSION_';
    private $_page_session = '_PAGE_SESSION_';
    
    function __construct() {
        parent::__construct();
    }
    
    public static function getInstance(  ) { 
            
        if(!self::$objInstance){ 
            self::$objInstance = new Userlib();
        } 
        
        return self::$objInstance; 
    }
    
    public function get_name(){
        return $this->ci->session->userdata($this->_prefix_session_access.'nama');
    }
    
    public function get_userid(){
        return $this->ci->session->userdata($this->_prefix_session_access.'id');
    }
    /**
     * Check if user is loggedin
     * @return boolean
     */
    public function isLoggedin(){
        if ($this->ci->session->userdata($this->_prefix_session_access . 'isloggedin')){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    /**
     * Try to guess wheather user is online
     * @param string $session_id
     * @return boolean
     */
    public function is_online($session_id){
        $session_table = $this->ci->config->item('sess_table_name') ? $this->ci->config->item('sess_table_name'):'sessions';
        $this->ci->db->select('COUNT(*) AS found')->from($session_table)->where('id',$session_id);
        $row = $this->ci->db->get()->row();
        
        if ($row){
            return $row->found > 0;
        }
        
        return FALSE;
    }
    /**
     * 
     * @param string $username
     * @param string $password
     * @return boolean FALSE if failed, return user object if succeed
     */
    public function login($username, $password){
        $user_loggedin = $this->_login_service($username, $password);
        if ($user_loggedin){
            //login success
            //create session for detail user
            $user_session = array();
            foreach ($user_loggedin as $prop => $prop_value){
                $user_session[$this->_prefix_session_access . $prop] = $prop_value;
            }
            $user_session[$this->_prefix_session_access.'isloggedin'] = TRUE;
            $user_session[$this->_prefix_session_access.'is_administrator'] = $user_loggedin->root;
            $this->ci->session->set_userdata($user_session);
            
            //prepare user menu
            $user_menu = $this->_prepare_user_menu();
            $this->ci->session->set_userdata($this->_page_session, $user_menu);
            
            //prepare user access
            $user_access = $this->_prepare_user_access();
            $this->ci->session->set_userdata($this->_role_session, $user_access);
            
            return $user_loggedin;
        }
        
        return FALSE;
    }
    /**
     * Get menu for currelntly loggedin user
     * @return stdclass menu
     */
    private function _prepare_user_menu(){
        if (!isset($this->ci->mtr_menu_m)){
            $this->ci->load->model(array('mtr_menu_m','mtr_module_m'));
        }
        $me = $this->me();
        $menu_user = array();

        $user_pages = array();
        
        if (!$me->is_administrator){
            if (!isset($this->ci->auth_group_page_m)){
                $this->ci->load->model('auth_group_page_m');
            }
            $user_pages_query = $this->ci->auth_group_page_m->get_by(array('group_id'=>$me->grup->id,'granted'=>1));
            
            foreach ($user_pages_query as $upq){
                $user_pages[] = $upq->page_id;
            }
        }

        foreach ($this->ci->mtr_menu_m->get_by(array('hidden'=>0)) as $menuitem){
            $menuitem->module_name = $this->ci->mtr_module_m->get_value('name',array('id'=>$menuitem->module));
            
            if ($me->is_administrator || in_array($menuitem->id,$user_pages)){
                $menuitem->granted = TRUE;
            }else{
                $menuitem->granted = FALSE;
            }
            
            $menu_user[$menuitem->id] = $menuitem;
        }
        
        return $menu_user;
    }
    /**
     * Get user access by group and user access specifi
     * @return array of user access fitur
     */
    private function _prepare_user_access(){
        //load user model
        $this->ci->load->model(array('auth_role_m','auth_group_action_m','auth_user_action_m'));
        
        $me = $this->me();
        
        //get all role
        $user_access = array();
        foreach ($this->ci->auth_role_m->get() as $role){
            if ($me->is_administrator){
                $role->granted = TRUE;
            }else{
                $role->granted = $role->granted==1 ? TRUE : FALSE;
            }
            $user_access[$role->id] = $role;
        }
        
        if (!$me->is_administrator){
            //get group access
            $group_access = $this->ci->auth_group_action_m->get_by(array('group_id'=>$me->grup->id));
            if ($group_access){
                foreach ($group_access as $ga){
                    if (isset($user_access[$ga->role_id])){
                        $access = $user_access[$ga->role_id];
                        $access->granted = $ga->granted==1 ? TRUE : FALSE;
                        $user_access[$ga->role_id] = $access;
                    }
                }
            }
            
            //get user access
            $user_access_specific = $this->ci->auth_user_action_m->get_by(array('user_id'=>$me->id));
            if ($user_access_specific){
                foreach ($user_access_specific as $ua){
                    if (isset($user_access[$ua->role_id])){
                        $access = $user_access[$ua->role_id];
                        $access->granted = $ua->granted==1 ? TRUE : FALSE;
                        $user_access[$ga->role_id] = $access;
                    }
                }
            }
        }
        
        //change user_access array into associative array by role name instead of role id
        $user_associative_array = array();
        foreach ($user_access as $access){
            $user_associative_array[$access->name] = $access->granted;
        }
        
        return $user_associative_array;
    }
    /**
     * Login using web service
     * @param string $username
     * @param string $password
     * @return boolean FALSE if FAILED or object login if success
     */
    private function _login_service($username,$password){
        if (!isset($this->ci->user_m)){
            $this->ci->load->model('user_m');
        }
        
        $user = $this->ci->user_m->get_by(array('username'=>$username, 'password'=>  $this->hash($password)), TRUE);
        if (!$user){
            //try to use email
            $user = $this->ci->user_m->get_by(array('email'=>$username, 'password'=>  $this->hash($password)), TRUE);
        }
        
        if ($user){
            //load helper
            $this->ci->load->helper('general');
            $this->ci->load->model(array('group_m','bidang_m'));
            if ($user->bidang){
                $user->bidang = $this->ci->bidang_m->get($user->bidang);
            }
            if ($user->grup){
                $user->grup = $this->ci->group_m->get($user->grup);
            }
            $user->root = $user->grup->id == CT_USERTYPE_ROOT ? TRUE : FALSE;
            $user->active = $user->active ? TRUE : FALSE;
            
            if ($user->avatar){
                $user->avatar = avatar_url($user->avatar);
            }else{
                $random_avatar = rand(1, 7);
                $user->avatar = avatar_url('user'.$random_avatar.'.png');
            }
            
        }
        
        return $user;
    }
    /**
     * User logout / end session
     * @param type $user_id USER ID(optional) if omitted, userID will be taken from session
     */
    public function logout(){
        $this->ci->session->sess_destroy();
    }
    /**
     * Check for access privileges for a specific page
     * @param int $page_id
     * @return boolean
     */
    public function has_page_access($page_id){
        $page_access = $this->ci->session->userdata($this->_page_session);
        if (isset($page_access[$page_id])){
            $page = $page_access[$page_id];
            
            return $page->granted;
        }
        
        return FALSE;
        
        //return isset($page_access[$page_id]) ? $page_access[$page_id] : FALSE;
    }
    /**
     * Check if user has access to certain fitur / role name
     * @param string $role_name
     * @return Boolean TRUE if has access or FALSE if not
     */
    public function has_access($role_name){
        $role_session = $this->ci->session->userdata($this->_role_session);
        return isset($role_session[$role_name]) ? $role_session[$role_name] : FALSE;
    }
    /**
     * Check group is admin group
     * @param int $group_id
     * @return boolean
     */
    public function is_admin(){
        return $this->ci->session->userdata($this->_prefix_session_access.'root');
    }
    /**
     * Get admin group ID
     * @return int
     */
    public function get_admin_groupID(){
        return CT_USERTYPE_ROOT;
    }
    /**
     * Generate password
     * @return string
     */
    public function generate_password($length=6){
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
    /**
     * Get current loggedin user as object
     * @return \stdClass of user objek
     */
    public function me(){
        $user = new stdClass();
        $user->id = $this->ci->session->userdata($this->_prefix_session_access.'id');
        $user->username = $this->ci->session->userdata($this->_prefix_session_access.'username');
        $user->nama = $this->ci->session->userdata($this->_prefix_session_access.'nama');
        $user->grup = $this->ci->session->userdata($this->_prefix_session_access.'grup');
        $user->bidang = $this->ci->session->userdata($this->_prefix_session_access.'bidang');
        $user->wilayah = $this->ci->session->userdata($this->_prefix_session_access.'wilayah');
        $user->is_administrator = $this->ci->session->userdata($this->_prefix_session_access.'is_administrator');
        $user->root = $this->ci->session->userdata($this->_prefix_session_access.'root');
        $user->avatar = $this->ci->session->userdata($this->_prefix_session_access.'avatar');
        
        return $user;
    }
    /**
     * Get user menu
     * @return array of user menu
     */
    public function get_user_menu(){
        return $this->ci->session->userdata($this->_page_session);
    }
    
    public function hash($subject){
        return hash('md5', config_item('encryption_key') . $subject);
    }
}

/**
 * Filename : Userlib.php
 * Location : application/libraries/Userlib.php
 */
