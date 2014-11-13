<?php
//ВАЖНО !!! если закоментить - нужно так же закоментировать такую же строку в файле index.php и captcha/captcha.php & model/validation_data.php & model/analyze.php
//ini_set('session.save_path', $_SERVER['DOCUMENT_ROOT'] .'/sessions/');
include_once('../config/config.php');

if($_REQUEST['key'])
{
    session_start();
    $_SESSION['key'] = md5((string)rand());
    $key = array('key' => $_SESSION['key']);
    echo json_encode( $key );
}
?>