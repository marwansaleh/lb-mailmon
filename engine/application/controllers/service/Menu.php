<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Menu
 *
 * @author marwansaleh 1:31:58 PM
 */
class Menu extends REST_Api {
    private $_remap_fields = array(
        'id'        => 'id',
        'parent'    => 'parent',
        'module'    => 'module',
        'caption'   => 'caption',
        'title'     => 'title',
        'icon'      => 'icon',
        'link'      => 'link',
        'sort'      => 'sort',
        'hidden'    => 'hidden',
        'groups'    => 'groups',
        'is_child'  => 'is_child'
    );
    
    function __construct($config = 'rest') {
        parent::__construct($config);
    }
    
    function index_get(){
        $this->load->model(array('mtr_menu_m','mtr_module_m','auth_group_page_m'));
        
        $draw = $this->get('draw') ? $this->get('draw') : 1;
        $length = $this->get('length') ? $this->get('length') : 10;
        $search = $this->get('search') ? $this->get('search') : NULL;
        $start = $this->get('start') ? $this->get('start') : 0;
        
        $totalRecords = $this->mtr_menu_m->get_count();
        
        //set filtered if any
        if ($search && $search['value']){
            $this->db->like('caption', $search['value']);
        }
        //get filtered count
        $totalFiltered = $this->mtr_menu_m->get_count();
        
        $result = array('draw' => $draw, 'start'=>$start, 'recordsTotal'=>$totalRecords, 'recordsFiltered'=>$totalFiltered, 'items'=>array());

        if ($totalRecords > 0){
            //get groups from webservcie
            $srv = new Service();
            $group_service = $srv->get_groups(TRUE);
            //set filtered if any
            if ($search && $search['value']){
                $this->db->like('caption', $search['value']);
            }
            //apply offset and limit of data
            $this->db->offset($start)->limit($length);
            
            $query_result = $this->mtr_menu_m->get_by();
            if ($query_result){
                $items = array();
                foreach ($query_result as $item){
                    $items [] = $this->_proccess_item($item, $group_service);
                }
                //manipulate result item before return
                $result['items'] = $this->remap_fields($this->_remap_fields, $items);
            }
        }
        $this->response($result);
        
    }
    
    private function _proccess_item($item=NULL, $groups=NULL){
        if ($item){
            $item->module = $this->mtr_module_m->get($item->module);
            $item->icon = $item->icon ? '<i class="fa '.$item->icon.'"></i>':NULL;
            $item->hidden = $item->hidden==1 ? TRUE : FALSE;
            $item->is_child = $item->parent > 0;
            
            //group access
            $item->groups = NULL;
            if ($groups){
                $group_access = $this->auth_group_page_m->get_by(array('page_id'=>$item->id, 'granted'=>1));
                if ($group_access){
                    $item->groups = array();
                    foreach ($group_access as $ga){
                        $group = isset($groups[$ga->group_id]) ? $groups[$ga->group_id]->nama : $ga->group_id;
                        $item->groups [] = $group;
                    }
                }
            }
        }
        return $item;
    }
    
    public function index_post(){
        $this->load->model(array('mtr_menu_m'));
        $result = array('status'=> FALSE);
        
        $data = array(
            'parent'    => $this->post('parent'),
            'module'    => $this->post('module'),
            'caption'   => $this->post('caption'),
            'title'     => $this->post('title'),
            'icon'      => $this->post('icon'),
            'link'      => $this->post('link'),
            'sort'      => $this->post('sort'),
            'hidden'    => $this->post('hidden')
        );
        //prepare for group access
        $access = $this->post('access');
        
        $inserted_id = $this->mtr_menu_m->save($data);
        if ($inserted_id){
            $result['status'] = TRUE;
            $result['item'] = $this->mtr_menu_m->get($inserted_id);
            
            //update access group
            if ($access){
                $this->load->model('auth_group_page_m');
                foreach ($access as $acc){
                    //add access page group
                    $this->auth_group_page_m->save(array(
                        'page_id'   => $inserted_id,
                        'group_id'  => $acc,
                        'granted'   => TRUE
                    ));
                }
            }
        }else{
            $result['message'] = $this->mtr_menu_m->get_last_message();
        }
        
        $this->response($result);
    }
    
    public function index_put($item_id){
        $this->load->model(array('mtr_menu_m'));
        $result = array('status'=> FALSE);
        
        $data = array(
            'parent'    => $this->put('parent'),
            'module'    => $this->put('module'),
            'caption'   => $this->put('caption'),
            'title'     => $this->put('title'),
            'icon'      => $this->put('icon'),
            'link'      => $this->put('link'),
            'sort'      => $this->put('sort'),
            'hidden'    => $this->put('hidden')
        );
        //prepare for group access
        $access = $this->put('access');
        
        if ($this->mtr_menu_m->save($data, $item_id)){
            $result['status'] = TRUE;
            $result['item'] = $this->mtr_menu_m->get($item_id);
            //update access group
            if ($access){
                $this->load->model('auth_group_page_m');
                $this->auth_group_page_m->delete_where(array('page_id'=>$item_id));
                foreach ($access as $acc){
                    //add access page group
                    $this->auth_group_page_m->save(array(
                        'page_id'   => $item_id,
                        'group_id'  => $acc,
                        'granted'   => TRUE
                    ));
                }
            }
        }else{
            $result['message'] = $this->mtr_menu_m->get_last_message();
        }
        
        $this->response($result);
    }
    
    public function index_delete($item_id){
        $this->load->model(array('mtr_menu_m'));
        $result = array('status'=> FALSE);
        
        $item = $this->mtr_menu_m->get($item_id);
        if (!$item){
            $result['message'] = 'Data menu tidak ditemukan';
        }else{
            $this->mtr_menu_m->delete($item_id);
            $result['status'] = TRUE;
        }
        
        $this->response($result);
    }
    
    function bymodule_get($module_id){
        $this->load->model(array('mtr_menu_m'));
        
        $result = array('status'=>FALSE);
        if (!$module_id){
            $result['message'] = 'Module ID is not valid';
        }else{
            $result['status'] = TRUE;
            
            $items = $this->mtr_menu_m->get_by(array('module'=>$module_id));
            $result['items'] = array();
            if ($items){
                $result['item_count'] = count($items);
                $result['items'] = $items;
            }else{
                $result['item_count'] = 0;
            }
        }
        $this->response($result);
    }
}

/**
 * Filename : Menu.php
 * Location : application/controllers/service/Menu.php
 */
