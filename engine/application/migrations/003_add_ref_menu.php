<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Migration_add_ref_menu
 *
 * @author marwansaleh
 */
class Migration_add_ref_menu extends MY_Migration {
    protected $_table_name = 'ref_mainmenu';
    protected $_primary_key = 'id';
    protected $_index_keys = array('caption','link');
    protected $_fields = array(
        'id'    => array (
            'type'  => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'auto_increment' => TRUE
        ),
        'parent'    => array (
            'type'  => 'INT',
            'constraint' => 11,
            'default' => 0
        ),
        'module'   => array(
            'type'  => 'INT',
            'constraint' => 11,
            'null' => FALSE
        ),
        'caption'   => array(
            'type'  => 'VARCHAR',
            'constraint' => 50,
            'null' => TRUE
        ),
        'title' => array(
            'type'  => 'VARCHAR',
            'constraint' => 254,
            'null' => TRUE
        ),
        'icon'   => array(
            'type'  => 'VARCHAR',
            'constraint' => 50,
            'null' => TRUE
        ),
        'link'   => array(
            'type'  => 'VARCHAR',
            'constraint' => 254,
            'null' => TRUE
        ),
        'sort'   => array(
            'type'  => 'INT',
            'constraint' => 3,
            'default' => 0
        ),
        'hidden'   => array(
            'type'  => 'TINYINT',
            'constraint' => 1,
            'default' => 0,
            'null' =>TRUE
        )
    );
    
    function up() {
        parent::up();
        
        $main_menu = array(
            array(
                'module'        => CT_MODULE_DASHBOARD,
                'caption'       => 'Dashboard',
                'title'         => 'Display important parameters',
                'icon'          => 'fa-dashboard',
                'link'          => 'dashboard',
                'sort'          => 0,
                'hidden'        => 1
            ),
            array(
                'module'    => CT_MODULE_INCOMING,
                'caption'   => 'Surat Masuk',
                'title'     => 'Incoming',
                'icon'      => 'fa-envelope',
                'link'      => NULL,
                'sort'      => 1,
                'hidden'    => 0,
                'children'  => array(
                    array(
                        'module'    => CT_MODULE_INCOMING,
                        'caption'   => 'Register',
                        'title'     => 'Daftar',
                        'icon'      => NULL,
                        'link'      => 'incoming/register',
                        'sort'      => 0,
                        'hidden'    => 0
                    )
                )
            ),
            array(
                'module'    => CT_MODULE_OUTGOING,
                'caption'   => 'Surat Keluar',
                'title'     => 'Outgoing',
                'icon'      => 'fa-envelope-o',
                'link'      => NULL,
                'sort'      => 3,
                'hidden'    => 0,
                'children'  => array(
                    array(
                        'module'    => CT_MODULE_OUTGOING,
                        'caption'   => 'Register',
                        'title'     => 'Daftar',
                        'icon'      => NULL,
                        'link'      => 'outgoing/register',
                        'sort'      => 0,
                        'hidden'    => 0
                    )
                )
            ),
            array(
                'module'    => CT_MODULE_NODIN,
                'caption'   => 'Nota Dinas',
                'title'     => 'Nota dinas',
                'icon'      => 'fa-share',
                'link'      => NULL,
                'sort'      => 4,
                'hidden'    => 0,
                'children'  => array(
                    array(
                        'module'    => CT_MODULE_NODIN,
                        'caption'   => 'Register',
                        'title'     => 'Daftar',
                        'icon'      => NULL,
                        'link'      => 'nodin/register',
                        'sort'      => 0,
                        'hidden'    => 0
                    )
                )
            ),
            array(
                'module'        => CT_MODULE_DISPOSISI,
                'caption'       => 'Disposisi',
                'title'         => 'Disposisi',
                'icon'          => 'fa-forward',
                'link'          => 'disposisi',
                'sort'          => 2,
                'hidden'        => 0
            ),
            array(
                'module'    => CT_MODULE_SYSTEM,
                'caption'   => 'System Settings',
                'title'     => 'System settings',
                'icon'      => 'fa-cogs',
                'link'      => NULL,
                'sort'      => 5,
                'hidden'    => 0,
                'children'  => array(
                    array(
                        'module'    => CT_MODULE_SYSTEM,
                        'caption'   => 'Accounts',
                        'title'     => 'User account',
                        'icon'      => NULL,
                        'link'      => 'system/user',
                        'sort'      => 0,
                        'hidden'    => 0
                    ),
                    array(
                        'module'    => CT_MODULE_SYSTEM,
                        'caption'   => 'User Privileges',
                        'title'     => 'Action privileges',
                        'icon'      => NULL,
                        'link'      => 'system/privilege',
                        'sort'      => 2,
                        'hidden'    => 1
                    ),
                    array(
                        'module'    => CT_MODULE_SYSTEM,
                        'caption'   => 'Menu Management',
                        'title'     => 'Menu management',
                        'icon'      => NULL,
                        'link'      => 'system/menu',
                        'sort'      => 3,
                        'hidden'    => 0
                    ),
                    array(
                        'module'    => CT_MODULE_SYSTEM,
                        'caption'   => 'Mail Template',
                        'title'     => 'Template',
                        'icon'      => NULL,
                        'link'      => 'system/template',
                        'sort'      => 4,
                        'hidden'    => 0
                    )
                )
            )
        );
        
        if (!function_exists('menu_filter_url')){
            function menu_filter_url($data){
                if (isset($data['link']) && $data['link']){
                    $data['link'] = rtrim($data['link'], '/');
                }
                
                if (isset($data['parent']) && isset($data['sort'])){
                    $data['sort'] = $data['sort'] + $data['parent'];
                }
                
                return $data;
            }
        }
        
        $this->_seed_extend($main_menu,0,'parent','menu_filter_url');
    }
}

/*
 * filename : 004_add_ref_menu.php
 * location : /application/migrations/004_add_ref_menu.php
 */
