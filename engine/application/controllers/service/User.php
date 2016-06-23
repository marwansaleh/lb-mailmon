<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of User
 *
 * @author marwansaleh 1:31:58 PM
 */
class User extends REST_Api {
    private $_remap_fields = array(
        'id'                    => 'id',
        'username'              => 'username',
        'nama'                  => 'nama',
        'grup'                  => 'grup',
        'bidang'                => 'bidang',
        'nik'                   => 'nik',
        'email'                 => 'email',
        'mobile'                => 'mobile',
        'telepon'               => 'telepon',
        'avatar'                => 'avatar',
        'active'                => 'active',
        'root'                  => 'root'
    );
    
    function __construct($config = 'rest') {
        parent::__construct($config);
    }
    
    function index_get(){
        $this->load->model(array('user_m','group_m','bidang_m'));
        
        $draw = $this->get('draw') ? $this->get('draw') : 1;
        $length = $this->get('length') ? $this->get('length') : 10;
        $search = $this->get('search') ? $this->get('search') : NULL;
        $start = $this->get('start') ? $this->get('start') : 0;
        
        $totalRecords = $this->user_m->get_count();
        
        //set filtered if any
        if ($search && $search['value']){
            $this->db->like('username', $search['value']);
            $this->db->or_like('nama', $search['value']);
        }
        //get filtered count
        $totalFiltered = $this->user_m->get_count();
        
        $result = array('draw' => $draw, 'start'=>$start, 'recordsTotal'=>$totalRecords, 'recordsFiltered'=>$totalFiltered, 'items'=>array());

        if ($totalRecords > 0){
            //set filtered if any
            if ($search && $search['value']){
                $this->db->like('username', $search['value']);
                $this->db->or_like('nama', $search['value']);
            }
            //apply offset and limit of data
            $this->db->offset($start)->limit($length);
            
            $query_result = $this->user_m->get_by();
            if ($query_result){
                $items = array();
                foreach ($query_result as $item){
                    //manipulate result item before return
                    $items [] = $this->_proccess_item($item);
                }
                //manipulate result item before return
                $result['items'] = $this->remap_fields($this->_remap_fields, $items);
            }
        }
        $this->response($result);
        
    }
    
    function all_get($simple=0){
        $this->load->model(array('user_m','group_m','bidang_m'));
        
        $result = array('item_count'=>0, 'items'=>array());
        
        $users = $this->user_m->get();
        
        if ($users){
            $items = array();
            foreach ($users as $item){
                //manipulate result item before return
                if ($simple){
                    $items [] = $item;
                }else{
                    $items [] = $this->_proccess_item($item);
                }
            }
            
            //manipulate result item before return
            $result['items'] = $this->remap_fields($this->_remap_fields, $items);
            $result['item_count'] = count($result['items']);
        }
        
        $this->response($result);
        
    }
    
    function index_delete($id){
        $this->load->model(array('user_m'));
        $result = array('status'=>FALSE);
        
        if ($this->user_m->delete($id)){
            $result['status'] = TRUE;
        }else{
            $result['message'] = $this->user_m->get_last_message();
        }
        $this->response($result);
    }
    
    function save_post(){
        $this->load->model(array('user_m','group_m','bidang_m'));
        $result = array('status'=>FALSE);
        
        $userlib = Userlib::getInstance();
        
        $data = array(
            'nama'          => $this->post('nama'),
            'username'      => $this->post('username'),
            'password'      => $userlib->hash($this->post('password') ? $this->post('password') : 123),
            'grup'          => $this->post('grup'),
            'bidang'        => $this->post('bidang'),
            'nik'           => $this->post('nik'),
            'email'         => $this->post('email'),
            'mobile'        => $this->post('mobile'),
            'telepon'       => $this->post('telepon'),
            'active'        => $this->post('active')
        );
        $inserted_id = $this->user_m->save($data);
        if ($inserted_id){
            $result['status'] = TRUE;
            $result['item'] = $this->remap_fields($this->_remap_fields, $this->_proccess_item($this->user_m->get($inserted_id)));
        }else{
            $result['message'] = $this->user_m->get_last_message();
        }
        
        $this->response($result);
    }
    
    function save_put($id){
        $this->load->model(array('user_m','group_m','bidang_m'));
        $result = array('status'=>FALSE);
        
        if ($id){
            $data = array(
                'nama'          => $this->put('nama'),
                'username'      => $this->put('username'),
                'grup'          => $this->put('grup'),
                'bidang'        => $this->put('bidang'),
                'nik'           => $this->put('nik'),
                'email'         => $this->put('email'),
                'mobile'        => $this->put('mobile'),
                'telepon'       => $this->put('telepon'),
                'active'        => $this->put('active')
            );
            if ($this->put('change_password') && $this->put('password')){
                $userlib = Userlib::getInstance();
                $data['password'] = $userlib->hash($this->put('password'));
            }
            if ($this->user_m->save($data, $id)){
                $result['status'] = TRUE;
                $result['item'] = $this->remap_fields($this->_remap_fields, $this->_proccess_item($this->user_m->get($id)));
            }else{
                $result['message'] = $this->user_m->get_last_message();
            }
        }else{
            $result['message'] = 'ID is not defined';
        }
        
        $this->response($result);
    }
    
    function user_get($id){
        $this->load->model(array('user_m','group_m','bidang_m'));
        $result = array('status'=>FALSE);
        
        $user = $this->user_m->get($id);
        
        if ($user){
            $result['status'] = TRUE;
            $result['item'] = $this->remap_fields($this->_remap_fields, $this->_proccess_item($user));
        }else{
            $result['message'] = 'Data user tidak ada';
        }
        
        $this->response($result);
    }
    
    function support_get(){
        $this->load->model(array('group_m','bidang_m'));
        $result = array(
            'grup'      => $this->group_m->get(),
            'bidang'    => $this->bidang_m->get()
        );
        
        $this->response($result);
    }
    
    private function _proccess_item($item = NULL){
        if ($item){
            $item->root = $item->grup == CT_USERTYPE_ROOT ? TRUE : FALSE;
            $item->grup = $item->grup ? $this->group_m->get($item->grup) : NULL;
            $item->bidang = $item->bidang ? $this->bidang_m->get($item->bidang) : NULL;
            $item->active = $item->active ? TRUE : FALSE;
            if ($item->avatar){
                $item->avatar = site_url(config_item('avatar').$item->avatar);
            }else{
                $random_avatar = rand(1, 7);
                $item->avatar = site_url(config_item('avatar').'user'.$random_avatar.'.png');
            }
        }
        
        return $item;
    }
    
    public function changepassword_post(){
        $this->load->model(array('user_m'));
        $result = array('status'=>FALSE);
        
        $username = $this->post('username');
        $old_pwd = $this->post('old_password');
        $new_pwd = $this->post('new_password');
        
        $userlib = Userlib::getInstance();
        
        if (!$username || !$old_pwd || !$new_pwd){
            $result['message'] = 'Paramater tidak lengkap. Pastikan anda memasukkan username, password lama dan baru';
        }else{
            $user = $this->user_m->get_by(array('username'=>$username, 'password'=>$userlib->hash($old_pwd)), TRUE);
            if (!$user){
                //try to use email
                $user = $this->user_m->get_by(array('email'=>$username, 'password'=>$userlib->hash($old_pwd)), TRUE);
            }
            
            if ($user){
                if ($this->user_m->save(array('password'=>  $userlib->hash($new_pwd)), $user->id)){
                    $result['status'] = TRUE;
                }
            }else{
                $result['message'] = 'Username dan password lama anda tidak sesuai';
            }
        }
        
        $this->response($result);
    }
    
    public function select2_get($id=NULL){
        $this->load->model(array('user_m','group_m','bidang_m'));
        if ($id){
            
            $item = $this->remap_fields($this->_remap_fields, $this->_proccess_item($this->user_m->get($id)));
            if ($item){
                $item->text = $item->nama;
                if ($item->bidang){
                    $item->text .= ' ('.$item->bidang->nama. ')';
                }
            }
            
            $this->response($item);
        }else{
            $q = $this->get('q') ? $this->get('q') : '';
            $page = $this->get('page') ? $this->get('page') : 1;
            $length = 5;

            $start = ($page-1) * $length;

            //apply offset and limit of data
            $this->db->like('nama',$q);
            $this->db->or_like('username',$q);
            $query_result = $this->user_m->get_offset('*',NULL,$start,$length);
            $items = array();
            if ($query_result){
                $count = count($query_result);

                foreach ($query_result as $item){
                    $item = $this->remap_fields($this->_remap_fields, $this->_proccess_item($item));
                    $item->text = $item->nama;
                    if ($item->bidang){
                        $item->text .= ' ('.$item->bidang->nama. ')';
                    }
                    $items[] = $item;
                }
            }
            
            $endCount = $start + $length;
            $morePages = $endCount > $count;

            $result = array(
                'results' => $items,
                'pagination' => array(
                    'more' => $morePages
                )
            );

            $this->response($result);
        }
    }
}

/**
 * Filename : User.php
 * Location : application/controllers/services/User.php
 */
