<?php
header("Content-Type: text/html; charset=utf-8");
//mb_internal_encoding("utf-8"); // меняем внутреннюю кодировку на UTF-8

if(isset($_GET['not_send']) && $_GET['not_send'] != '')
{
    include_once('../config/config.php');
    include_once('../logs/log.php');
    include_once('../service/service.php');
    include_once('../model/model.php');

    $model = new Model();
    if($model->ModelNotSendEmail($_GET['not_send']))
    {
        echo "Ваш email удалён из Базы данных рассылки!";
    }
    else
    {
        echo "Ваш email в Базе данных рассылки - не найден! Обратитесь к администратору.";
    }
}
else
{
    echo "Привет незванный гость!!!";
}
?>