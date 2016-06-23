<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {

    public function index($referrer=NULL)
    {
        if ($this->userlib->isLoggedin()){
            redirect('incoming/register');
        }
        if ($referrer){
            $this->data['referrer'] = $referrer;
        }
        $this->data['remember'] = $this->_remember_me();
        $this->data['subview'] = 'login/index';
        $this->data['submit_url'] = get_action_url('auth/login');
        $this->load->view('_layout_login', $this->data);
    }
    
    function login(){
        $this->load->library('form_validation');
        
        $referrer = $this->input->post('referrer');
        
        $this->form_validation->set_rules(array(
            array(
                'field' => 'username', 
                'label' => 'Username', 
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'password', 
                'label' => 'Password', 
                'rules' => 'trim|required'
            )
        ));
        if ($this->form_validation->run() !== FALSE){
            $username = $this->input->post('username', TRUE);
            $password = $this->input->post('password', TRUE);
            $remember = $this->input->post('remember', TRUE);
            
            if ($remember){
                $this->_remember_me($username, $password);
            }else{
                $this->_remember_clear();
            }
            
            $login_service = $this->userlib->login($username, $password);
            //var_dump($login_service);exit;
            if ($login_service){
                if ($referrer){
                    redirect(get_url_back($referrer));
                }else{
                    redirect(get_action_url('incoming/register'));
                }
                exit;
            }else{
                $this->session->set_flashdata('message',  'Login gagal. Pastikan username dan password benar.');
                $this->session->set_flashdata('message_type', 'error');
                redirect($referrer ? get_action_url('auth/index/'.$referrer) : get_action_url('auth'));
            }
        }else{
            $this->session->set_flashdata('message', validation_errors());
            $this->session->set_flashdata('message_type', 'error');
            redirect($referrer ? get_action_url('auth/index/'.$referrer) : get_action_url('auth'));
        }
    }
    
    public function logout(){
        $this->userlib->logout();
        
        redirect(get_action_url('auth'));
    }
    
    private function _remember_me($username=NULL,$password=NULL){
        if (!$username && !$password){
            $cookie = $this->input->cookie('login_remember_me');
            if ($cookie){
                return json_decode($cookie);
            }else{
                return NULL;
            }
        } else {
            $cookie = new stdClass();
            $cookie->username = $username;
            $cookie->password = $password;
            
            $this->input->set_cookie('login_remember_me', json_encode($cookie), 365*24*3600);
        }
    }
    
    private function _remember_clear(){
        $this->input->set_cookie('login_remember_me', '', 0);
    }
}

/*
 * Filename: Auth.php
 * Location: application/controllers/Auth.php
 */