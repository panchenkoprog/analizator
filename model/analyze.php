<?php
//set_time_limit(2147483647);
//ini_set('memory_limit', '256M');//256M == (count Link() == 450000) || 128M == (count Link() == 200000)
//mb_internal_encoding("utf-8"); // меняем внутреннюю кодировку на UTF-8
ignore_user_abort(true);

include_once('../config/config.php');
include_once('../logs/log.php');
include_once('../model/process.php');

$sp = new ServiceProcess();

if($sp->Process_IsUser())
{
    $sp->Process_Init();//инициализируем из приходящего $_REQUEST[]

    if($sp->Process_CreateProcessInTurnRun_OR_PutProcessInTurnWaiting())
    {
        include_once('../service/service.php');
        include_once('../model/model.php');

        while( $sp->process_id )
        {
            set_time_limit(2147483647);

            $model = new Model();
            if($model->ModelIsUser())
            {
                $model->ModelParseOrCompare();// внутри вызывается $model->ModelInit($_REQUEST['url']);
            }
            $sp->Process_FinishProcessInTurnRun_OR_StartWaitingProcessInTurnWaiting();
        }
    }
}
?>