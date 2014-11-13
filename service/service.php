<?php
include_once('service_project.php');
include_once('service_report.php');

class Service
{
    public static $tariff      = 0.05;//при изменении этого параметра, нужно изменить var tariff в файле /js/scripts.js
    public static $ar_projects = array();// для вывода сообщений
    public static $ar_a        = array();// для вывода ссылок
    public static $login    = 'w';
    public static $password = 'wwwwww';

    public static $project_tools = array();//настройки проекта
    public static $ar_reports    = array();//для вывода отчетов

    public static $detail_report = null;//обьект new ServiceDetailReport() - детальный отчет
    public static $lines_detail_report = array();//строки из таблицы pages для подробного отчета

    //---------ошибки формы---------//
    public static $ar_errors = array();
    //---возращаемый текст в input при ошибке регистрации---//
    public static $ar_reg_out_text = array();

    //--------------заносим ошибки формы----------------//
    public static function SetErrors($name, $value)
    {
        self::$ar_errors[$name] = $value;
    }
    //--------------возвращаем ошибки формы----------------//
    public static function GetErrors($name)
    {
        if(array_key_exists($name,self::$ar_errors))
            return self::$ar_errors[$name];
        else
            return '';
    }
    //--- заносим  текст в input при ошибке регистрации ---//
    public static function SetOutText($name, $value)
    {
        self::$ar_reg_out_text[$name] = $value;
    }
    //--- возвращаем текст в input при ошибке регистрации ---//
    public static function GetOutText($name)
    {
        if(array_key_exists($name,self::$ar_reg_out_text))
            return self::$ar_reg_out_text[$name];
        else
            return '';
    }
}
?>