<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Download
 *
 * @author marwansaleh 1:47:44 PM
 */
class Download extends Admin_Controller {
    function index($filename){
        if (!$filename){
            exit('No file has defined');
        }else{
            $filename = base64_decode(urldecode($filename));
        }
        if (!file_exists($filename)){
            exit('File '.$filename.' is not found in server');
        }
        $mimetype = mime_content_type($filename);
        $basename = basename($filename);
        header('Content-Description: File Transfer');
        header("Content-Type: $mimetype");
        header("Content-Transfer-Encoding: Binary");
        header("Content-Disposition: attachment; filename=\"$basename\""); 
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filename));
        readfile($filename);
        exit;
    }
}

/**
 * Filename : Download.php
 * Location : /Download.php
 */
