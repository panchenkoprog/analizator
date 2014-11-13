<?php
//ВАЖНО !!! если закоментить - нужно так же закоментировать такую же строку в файле index.php и captcha/another.php & model/analyze.php
//ini_set('session.save_path', $_SERVER['DOCUMENT_ROOT'] .'/sessions/');

include_once('../config/config.php');
include_once('../logs/log.php');
include_once('../service/service.php');
include_once('../model/model.php');

session_start();

$model = new Model();
if($model->ModelValidationDataForUpdateTools())
{
    if($model->ModelUserUpdateProjectToolsToAnalyze())
    {
        $response = array('error' => 'empty', 'response' => '<p class="text-info">настройки изменены !</p>');
        echo json_encode( $response );
    }
}
else
{
    //есть ошибки
    $error = array('error' => '<p class="text-error">некоректные данные !</p>');
    echo json_encode( $error );
}
?>