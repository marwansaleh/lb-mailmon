<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once  APPPATH.'libraries/PhpWord/Autoloader.php';

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
                PhpOffice\PhpWord\Autoloader::register();
                $phpWord = new PhpOffice\PhpWord\PhpWord();

                // Adding an empty Section to the document...
                $sectionStyle = NULL;
                if ($mail->pagestyle){
                    $style = json_decode($mail->pagestyle);
                    $default_style = wordpagestyling(TRUE);
                    
                    foreach ($default_style as $prop => $def_value){
                        $sectionStyle [$prop] = _wordpagestyling_convert($prop, isset($style->$prop) ? $style->$prop : $def_value);
                    }
                    if (isset($sectionStyle['colsNum'])&&$sectionStyle['colsNum']>1){
                        $sectionStyle['breakType'] = 'continuous';
                    }
                }
                $section = $phpWord->addSection($sectionStyle);
                $phpWord->setDefaultParagraphStyle(
                    array(
                        'align'      => 'both',
                        'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(12),
                        'spacing'    => 120,
                        'lineHeight' => 1.5
                        )
                );
                $phpWord->setDefaultFontName('Arial');
                $phpWord->setDefaultFontSize(11);
                // Adding Text element to the Section having font styled by default...
                PhpOffice\PhpWord\Shared\Html::addHtml($section, $mail->isi_surat);
                
                // Add header if any
                if ($mail->header){
                    $header = $section->addHeader();
                    PhpOffice\PhpWord\Shared\Html::addHtml($header, $mail->header);
                }
                if ($mail->footer){
                    // Add header
                    $footer = $section->addFooter();
                    PhpOffice\PhpWord\Shared\Html::addHtml($footer, $mail->footer);
                }
                

                $filename = rtrim(sys_get_temp_dir(), '/') . '/' . url_title($mail->perihal, '_') . '.docx';
                $objWriter = PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
                $objWriter->save($filename);
                
                $result['status'] = TRUE;
                $result['download_url'] = get_action_url('download/index/'.urlencode(base64_encode($filename)));
            }
        }
        $this->response($result);
    }
}

/**
 * Filename : Export.php
 * Location : application/controllers/service/Export.php
 */
