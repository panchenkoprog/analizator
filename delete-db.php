<?php
set_time_limit(2147483647);
$db_name       = '' ;
$host          = 'localhost';//'10.100.53.2';//'localhost' ;
$root_login    = 'root' ;
$root_password = '' ;	//test
$link          = '' ;
$service       = 'service';
$prefix_db     = 'service_';
$service_email = 'service_email';
$service_table_name_site = 'service_site';
$service_table_name_report = 'service_report';
$service_table_name_turn_run = 'service_turn_run';
$service_table_name_turn_waiting = 'service_turn_waiting';


$link = mysql_connect($host, $root_login, $root_password) or die ('Ошибка');
mysql_query("SET NAMES utf8"); // теперь всё будет сохранятся в MySql правильной кодировке
if ($link)
{
    $res = mysql_query("SHOW DATABASES", $link);

    while ($row = mysql_fetch_assoc($res))
    {
        if(strpos($row['Database'], $prefix_db) === 0 && $row['Database'] != $service_email)
        {
            $drop = "DROP DATABASE ".$row['Database'];
            mysql_query($drop, $link);
            if(mysql_error())
            {
                echo 'errno: '.mysql_errno().'  error: '.mysql_error();
            }
        }
    }

    $del_from_table = "DELETE FROM ".$service_email.".".$service_email;
    mysql_query($del_from_table, $link);
    if(mysql_error())
    {
        echo 'errno: '.mysql_errno().'  error: '.mysql_error();
    }

    $del_from_table = "DELETE FROM ".$service.".".$service_table_name_site;
    mysql_query($del_from_table, $link);
    if(mysql_error())
    {
        echo 'errno: '.mysql_errno().'  error: '.mysql_error();
    }

    $del_from_table = "DELETE FROM ".$service.".".$service_table_name_report;
    mysql_query($del_from_table, $link);
    if(mysql_error())
    {
        echo 'errno: '.mysql_errno().'  error: '.mysql_error();
    }

    $del_from_table = "DELETE FROM ".$service.".".$service_table_name_turn_run;
    mysql_query($del_from_table, $link);
    if(mysql_error())
    {
        echo 'errno: '.mysql_errno().'  error: '.mysql_error();
    }

    $del_from_table = "DELETE FROM ".$service.".".$service_table_name_turn_waiting;
    mysql_query($del_from_table, $link);
    if(mysql_error())
    {
        echo 'errno: '.mysql_errno().'  error: '.mysql_error();
    }
}