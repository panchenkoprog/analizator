<?php
class ServiceProject
{
    public $exist_db     = 0;//проверка на наличие БД
    public $analyze      = 0;//проанализирован сайт или нет
    public $report       = 0;//есть ли отчёты в таблице service_report для соответствующего сайта

    public $user_id      = 0;
    public $site_db_name = '';
    public $site_name    = '';
    public $site_email_report = '';// email на который будет отправлятся отчет
    public $site_report  = null;
    public $robots       = 0;
    public $analysis_type= 0;
    public $limit_pages   = 0;
    public $analysis_timeout = 0;
    public $timeout      = null;
}

class ServiceProjectWaiting
{
    public $analyze      = 0;//проанализирован сайт или нет
    public $report       = 0;//есть ли отчёты в таблице service_report для соответствующего сайта

    public $user_id      = 0;
    public $site_db_name = '';
    public $site_name    = '';
    public $site_email_report = '';// email на который будет отправлятся отчет
    public $site_report  = null;
    public $robots       = 0;
    public $analysis_type= 0;
    public $limit_pages   = 0;
    public $analysis_timeout = 0;
    public $timeout      = null;
}
?>