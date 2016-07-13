<?php
if (isset($_GET['file']))
{
    $file      = $_GET['file'];
    $info      = pathinfo($file);
    $extension = $info['extension'];
    
    $content_type_list = array();
    $content_type_list['html'] = 'text/html';
    $content_type_list['pdf']  = 'application/pdf';
    $content_type_list['rtf']  = 'application/rtf';
    $content_type_list['csv']  = 'application/csv';
    $content_type_list['txt']  = 'text/plain';
    
    if (in_array($extension, array('html', 'pdf', 'rtf', 'csv', 'txt')))
    {
        $basename  = basename($file);
        
        // get the filesize
        $filesize = filesize($file);
        
        header("Pragma: public");
        header("Expires: 0"); // set expiration time
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-type: " . $content_type_list[$extension] );
        header("Content-Length: {$filesize}");
        header("Content-disposition: inline; filename=\"{$basename}\"");
        header("Content-Transfer-Encoding: binary");
        
        // a readfile da problemas no internet explorer
        // melhor jogar direto o conteudo do arquivo na tela
        echo file_get_contents($file);
    }
}
?>