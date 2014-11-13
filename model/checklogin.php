<?php
include_once('../config/config.php');
include_once('../service/service.php');
include_once('../model/model.php');
$link = Model::ModelConnectMySqlDB();
if($_POST['chlogin'])
{
    $t = htmlentities( $_POST['chlogin'], ENT_QUOTES, 'UTF-8' );
    $sel = "select id from ".Model::$service_table_name_users." where login='".$t."'";
    $res = mysql_query($sel, $link);

    if(mysql_errno())
    {
        $tmp = "ошибка бд: ".mysql_errno();
        $ch = array('chlogin' => $tmp);
        echo json_encode( $ch );
    }
    elseif( mysql_num_rows( $res ) > 0 )
    {
        $ch = array('chlogin' => 'такой логин уже занят !');
        echo json_encode( $ch );
    }
    else
    {
        $ch = array('chlogin' => '');
        echo json_encode( $ch );
    }
}
else
{
    $ch = array('chlogin' => '');
    echo json_encode( $ch );
}
?>