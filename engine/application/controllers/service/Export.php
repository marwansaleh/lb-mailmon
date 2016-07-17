<?php defined('BASEPATH') OR exit('No direct script access allowed');

//require_once  APPPATH.'libraries/PhpWord/Autoloader.php';
require_once  APPPATH.'libraries/phpdocxprof/classes/CreateDocx.inc';
//require_once  APPPATH.'libraries/phpdocxprof/classes/WordFragment.inc';

/**
 * Description of Export
 *
 * @author marwansaleh 1:31:58 PM
 */
class Export extends REST_Api {
    
    function __construct($config = 'rest') {
        parent::__construct($config);
    }
    
    function word_post(){
        $this->load->model(array('rel_incoming_m','rel_outgoing_m','rel_nodin_m'));
        $result = array('status'=>FALSE);
        
        $mail_id = $this->post('mail_id');
        $mail_type = $this->post('mail_type');
        
        if (!$mail_id || !$mail_type){
            $result['message'] = 'ID atau tipe tidak didefinisikan';
        }else{
            if ($mail_type==MAIL_INCOMING){
                $mail = $this->rel_incoming_m->get($mail_id);
            }elseif ($mail_type == MAIL_OUTGOING){
                $mail = $this->rel_outgoing_m->get($mail_id);
            }elseif ($mail_type == MAIL_NODIN){
                $mail = $this->rel_nodin_m->get($mail_id);
            }
            
            if (!$mail){
                $result['message'] = 'Data surat tidak ditemukan';
            }else{
                
                //generate filename without dot extension
                $filename = rtrim(sys_get_temp_dir(), '/') . '/' . time();
                
                $phpdocx = new CreateDocx();
                //$phpdocx->setLanguage('id-ID');
                //create document properties
                $properties = array(
                    'title' => strtoupper($mail_type),
                    'subject' => $mail->perihal,
                    'creator' => 'PT. BRIngin Sejahtera Makmur',
                    'keywords' => $mail_type,
                    'description' => $mail->perihal,
                    'category' => strtoupper($mail_type),
                    'contentStatus' => $mail_type==MAIL_OUTGOING ? outgoing_status($mail->status) : nodin_status($mail->status),
                    'company' => 'PT. BRIngin Sejahtera Makmur'
                );
                $phpdocx->addProperties($properties);
                //insert isi surat in html format
                $phpdocx->embedHTML($mail->isi_surat);
                
                //check if any header / footer
                if ($mail->header){
                    $wordFragment = new WordFragment($phpdocx, 'defaultHeader');
                    $wordFragment->addText($mail->header);
                    $phpdocx->addHeader(array('default' => $wordFragment));
                    //$rawHeaderML = $phpdocx->embedHTML($mail->header, array('rawWordML' => TRUE, 'target' => 'defaultHeader'));
                    //$phpdocx->addHeader(array('default' => $phpdocx->createWordMLFragment(array($rawHeaderML))));
                }
                //generate the docx file
                $phpdocx->createDocx($filename);
                
                //return result json value
                if (file_exists($filename .'.docx')){
                    $result['status'] = TRUE;
                    $result['download_url'] = get_action_url('download/index/'.urlencode(base64_encode($filename .'.docx')));
                }else{
                    $result['message'] = 'Sistem gagal membuat file ms word';
                }
            }
        }
        $this->response($result);
    }
}

/**
 * Filename : Export.php
 * Location : application/controllers/service/Export.php
 */
