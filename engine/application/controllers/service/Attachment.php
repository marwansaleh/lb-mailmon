<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Attachment
 *
 * @author marwansaleh 1:31:58 PM
 */
class Attachment extends REST_Api {
    
    
    function __construct($config = 'rest') {
        parent::__construct($config);
    }
    
    public function bymail_get($mail_id, $type='incoming'){
        $this->load->model(array('rel_dokumen_m'));
        
        $result = array('item_count'=>0,'items'=>array());
        
        $items = $this->rel_dokumen_m->get_by(array('mail'=>$mail_id, 'tipe'=>$type));
        $result['items'] = array();
        $nomor = 1;
        foreach($items as $item){
            $item->nomor = $nomor++;
            $item->download_url = urlencode(base64_encode($item->file_name));
            $result['items'][] = $item;
        }
        
        $result['item_count'] = count($result['items']);
        
        $this->response($result);
    }
    
    function upload_post(){
        $this->load->model(array('rel_dokumen_m'));
        $mail_id = $this->post('id');
        $mail_type = $this->post('type');
        
        $result = array('status' => FALSE);
        
        $config['upload_path'] = upload_path();
        $config['allowed_types'] = 'jpg|png|doc|docx|xls|xlsx|pdf';
        $config['max_size'] = 2000;
        
        if (!file_exists(upload_path())){
            mkdir(upload_path(), 0777, TRUE);
        }

        $this->load->library('upload', $config);
        
        if (!$this->upload->do_upload('userfile')) {
            $result['message'] = $this->upload->display_errors();
        } else {
            $upload = $this->upload->data();
            $inserted = $this->rel_dokumen_m->save(array(
                'tipe'          => $mail_type,
                'mail'          => $mail_id,
                'file_name'     => $upload['full_path'],
                'file_type'     => $upload['file_type'],
                'orig_name'     => $upload['orig_name'],
                'file_size'     => $upload['file_size'],
                
            ));
            $result['status'] = TRUE;
            $result['data'] = $this->rel_dokumen_m->get($inserted);
        }
        
        $this->response($result);
    }
    
    function uploaddelete_delete($upload_id){
        $this->load->model(array('rel_dokumen_m'));
        
        $result = array('status' => FALSE);
        
        $upload_file = $this->rel_dokumen_m->get($upload_id);
        
        if (file_exists($upload_file->file_name)){
            if (unlink($upload_file->file_name)){
                $this->rel_dokumen_m->delete($upload_id);
                $result['status'] = TRUE;
            }else{
                $result['message'] = 'Gagal menghapus file dari server';
            }
        }else{
            $result['message'] = 'File tidak ditemukan';
        }
        
        $this->response($result);
    }
}

/**
 * Filename : Attachment.php
 * Location : application/controllers/service/marketing/Attachment.php
 */
