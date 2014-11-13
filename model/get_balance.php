<?php
//ВАЖНО !!! если закоментить - нужно так же закоментировать такую же строку в файле index.php и captcha/another.php & model/analyze.php
//ini_set('session.save_path', $_SERVER['DOCUMENT_ROOT'] .'/sessions/');

include_once('../config/config.php');
include_once('../logs/log.php');
include_once('../service/service.php');
include_once('../model/model.php');

session_start();

if(isset($_REQUEST['change_balance']) && $_REQUEST['change_balance'] != '')
{
    $model = new Model();
    $model->ModelGetBalanceInSession();
    $error = array('error' => 'empty', 'balance' => $_SESSION['balance']);
    echo json_encode( $error );
}
session_write_close();
?>