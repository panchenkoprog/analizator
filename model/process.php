<?php
class ServiceProcess
{
    public static $service_host             = 'localhost';
    public static $service_root_login       = 'root' ;
    public static $service_root_password    = '' ;//test
    public static $service_db_name          = 'service' ;
    //-------
    public static $service_table_name_users               = 'service_users' ;
    public static $service_field_users_email              = 'email';
    public static $service_field_users_login              = 'login';
    public static $service_field_users_password           = 'password';
    public static $service_field_users_site               = 'site';
    public static $service_field_users_turn_run           = 'turn_run';
    public static $service_field_users_turn_waiting       = 'turn_waiting';
    public static $service_field_users_balance            = 'balance';
    //-------
    public static $service_table_name_site                = 'service_site' ;
    public static $service_field_site_user_id             = 'user_id';
    public static $service_field_site_db_name             = 'site_db_name';
    public static $service_field_site_name                = 'site_name';
    public static $service_field_site_email_report        = 'site_email_report';
    public static $service_field_site_site_report         = 'site_report';
    public static $service_field_site_robots              = 'robots';
    public static $service_field_site_analysis_type       = 'analysis_type';
    public static $service_field_site_limit_pages         = 'limit_pages';
    public static $service_field_site_analysis_timeout    = 'analysis_timeout';
    public static $service_field_site_timeout             = 'timeout';
    //-------
    public static $service_table_name_turn_run            = 'service_turn_run' ;
    public static $service_field_turn_run_user_id         = 'user_id';
    public static $service_field_turn_run_waiting_id      = 'waiting_id';
    public static $service_field_turn_run_login           = 'login';
    public static $service_field_turn_run_password        = 'password';
    //-------
    public static $service_table_name_turn_waiting        = 'service_turn_waiting' ;
    public static $service_field_turn_waiting_user_id     = 'user_id';
    public static $service_field_turn_waiting_run_id      = 'run_id';
    public static $service_field_turn_waiting_login       = 'login';
    public static $service_field_turn_waiting_password    = 'password';


    public $link = null ;
    public $process_id = 0;
    public $run_id = 0;
    public $waiting_id = 0;
    public $response = array(
        'turn_waiting'      =>0,
        'turn_run'          =>0,
        'binding_turn'      =>0,
        'errno_turn'        =>0,
        'error_turn'        =>''
    );


    public $user_id              = 0;
    public $login                = '';
    public $password             = '';
    public $site_name            = '';
    public $site_email_report    = '';
    public $analysis_type        = 0;    //тип анализа: 0-бечплатный, 1-платный
    public $limit_pages          = 0;   //количество сканируемых страниц
    public $analysis_timeout     = 0;    //повторное сканирование 1-нет 2-(через 2-недели после сканирования) 3-($this->timeout_scan - время между сканированием)
    public $timeout_scan         = null; //время между сканированием
    public $robots               = 0;    //проверка robots.txt (1 - включена, 0 - нет)


    public function Process_ConnectMySqlDB()
    {
        $this->link = mysql_connect(self::$service_host, self::$service_root_login, self::$service_root_password) or die ('Ошибка');
    }

    public function Process_DisconnectMySqlDB()
    {
        if(mysql_close($this->link))
        {
            $this->link = null;
        }
    }

    public function Process_GetResponse()
    {
        ob_start();
        $str =  json_encode( $this->response );

        $l = mb_strlen($str);

        echo $str;

        $length = ob_get_length();

        Log::writeProcessTestWrite( "length:".$length, __FILE__, __LINE__ );



        Log::writeProcessTestWrite( "length:".$l, __FILE__, __LINE__ );


        Log::writeProcessTestWrite( "======================================", __FILE__, __LINE__ );

        header('Connection: close');
        header("Content-Length: " . $length);
        header("Content-Encoding: none");
        header("Accept-Ranges: bytes");
        ob_flush();
        ob_end_flush();
        ob_clean();
        ob_end_clean();
        flush();
    }

    public function Process_ResponseInit( $key, $val )
    {
        $this->response[$key] = $val;
    }

    public function Process_ResponseErrorInit( $errno_key, $errno_val, $error_key, $error_val )
    {
        $this->response[$errno_key] = $errno_val;
        $this->response[$error_key] = $error_val;
    }

    function Process_Init()
    {
        $this->user_id           = intval($_REQUEST['id']);
        $this->login             = htmlentities($_REQUEST['login'] , ENT_QUOTES, 'UTF-8' );
        $this->password          = htmlentities($_REQUEST['password'] , ENT_QUOTES, 'UTF-8' );
        $this->site_name         = addslashes(htmlentities($_REQUEST['url'] , ENT_QUOTES, 'UTF-8' ));
        $this->site_email_report = addslashes(htmlentities($_REQUEST['email'] , ENT_QUOTES, 'UTF-8' ));
        $this->robots            = intval($_REQUEST['robots']);
        $this->analysis_type     = intval($_REQUEST['analysis_type']);

        if($this->analysis_type == 1)
        {//платный анализ
            $this->limit_pages = intval($_REQUEST['limit_pages']);
            $this->analysis_timeout = intval($_REQUEST['analysis_timeout']);
            if($this->analysis_timeout == 2)
            {
                $this->timeout_scan = FORTNIGHT; // 14 дней == 2 недели
            }
            elseif($this->analysis_timeout == 3)
            {
                if($_REQUEST['timeout']!='')
                {
                    $this->timeout_scan = intval($_REQUEST['timeout']);
                }
                else
                {
                    $this->timeout_scan = null;
                }
            }
        }
        else
        {//бесплатный анализ
            $this->limit_pages = LIMIT_PAGES;
        }
    }

    function Process_Clear()
    {
        $this->user_id           = 0;
        $this->login             = '';
        $this->password          = '';
        $this->site_name         = '';
        $this->site_email_report = '';
        $this->robots            = 0;
        $this->analysis_type     = 0;
        $this->limit_pages       = 0;
        $this->analysis_timeout  = 0;
        $this->timeout_scan      = null;
    }

    function Process_IsUser()
    {
        $this->Process_ConnectMySqlDB();

        $log = htmlentities($_REQUEST['login'], ENT_QUOTES, 'UTF-8');
        $pas = htmlentities($_REQUEST['password'], ENT_QUOTES, 'UTF-8');
        $pas = md5($pas);

        $sel = "select login, password from ".self::$service_db_name.".".self::$service_table_name_users
            ." where login='".$log."' and password='".$pas."' and id=".$_REQUEST['id'];

        $res = mysql_query($sel,$this->link);

        if(mysql_errno())
        {
            $this->Process_ResponseErrorInit('errno_turn', mysql_errno(), 'error_turn', mysql_error() );
            $this->Process_GetResponse();
            Log::writeProcessTurnError( "errno:".mysql_errno()." error:".mysql_error(), __FILE__, __LINE__ );
            exit();
        }
        elseif(mysql_num_rows($res)>0)
        {
            $this->Process_DisconnectMySqlDB();
            return true;
        }
        else
        {
            $this->Process_DisconnectMySqlDB();
            //неверно ввели логин или пароль :(
            return false;
        }
    }

    function Process_CreateProcessInTurnRun_OR_PutProcessInTurnWaiting()
    {
        $this->Process_ConnectMySqlDB();

        //встивляем данные в очередь ожидания
        $ins = "insert into ".self::$service_db_name.".".self::$service_table_name_turn_waiting."("
            .self::$service_field_turn_waiting_user_id.", "
            .self::$service_field_turn_waiting_login.", "
            .self::$service_field_turn_waiting_password.", "
            .self::$service_field_site_name.", "
            .self::$service_field_site_email_report.", "
            .self::$service_field_site_robots.", "
            .self::$service_field_site_analysis_type.", "
            .self::$service_field_site_limit_pages.", "
            .self::$service_field_site_analysis_timeout.", "
            .self::$service_field_site_timeout
            .") value ("
            .$this->user_id.", "
            ."'".$this->login."', "
            ."'".$this->password."', "
            ."'".$this->site_name."', "
            ."'".$this->site_email_report."', "
            .$this->robots.", "
            .$this->analysis_type.", "
            .$this->limit_pages.", "
            .$this->analysis_timeout.", "
            .( $this->timeout_scan === null ? 'NULL' : $this->timeout_scan ).")";

        if(!mysql_query( $ins, $this->link ))
        {
            $this->Process_ResponseErrorInit('errno_turn', mysql_errno(), 'error_turn', mysql_error() );
            $this->Process_GetResponse();
            Log::writeProcessTurnError( "errno:".mysql_errno()." error:".mysql_error(), __FILE__, __LINE__ );
            exit();
        }
        $this->waiting_id = mysql_insert_id($this->link);
        $this->Process_ResponseInit('turn_waiting', 1);

        //узнаём сколько запущеных процессов на сервере
        $sel = "SELECT COUNT(id) FROM ".self::$service_db_name.".".self::$service_table_name_turn_run;
        $res = mysql_query( $sel, $this->link );
        if(mysql_errno())
        {
            $this->Process_ResponseErrorInit('errno_turn', mysql_errno(), 'error_turn', mysql_error() );
            $this->Process_GetResponse();
            Log::writeProcessTurnError( "errno:".mysql_errno()." error:".mysql_error(), __FILE__, __LINE__ );
            exit();
        }
        elseif(mysql_num_rows($res)>0)
        {
            $row = mysql_fetch_row($res);
            if( intval($row[0]) < TURN_RUN_LIMITER )
            {
                //если 'пул' процессов не полный, добавляем новый процесс
                $ins = "insert into ".self::$service_db_name.".".self::$service_table_name_turn_run."("
                    .self::$service_field_turn_run_user_id.", "
                    .self::$service_field_turn_run_waiting_id.", "
                    .self::$service_field_turn_run_login.", "
                    .self::$service_field_turn_run_password.", "
                    .self::$service_field_site_name.", "
                    .self::$service_field_site_email_report.", "
                    .self::$service_field_site_robots.", "
                    .self::$service_field_site_analysis_type.", "
                    .self::$service_field_site_limit_pages.", "
                    .self::$service_field_site_analysis_timeout.", "
                    .self::$service_field_site_timeout
                    .") value ("
                    .$this->user_id.", "
                    .$this->waiting_id.", "
                    ."'".$this->login."', "
                    ."'".$this->password."', "
                    ."'".$this->site_name."', "
                    ."'".$this->site_email_report."', "
                    .$this->robots.", "
                    .$this->analysis_type.", "
                    .$this->limit_pages.", "
                    .$this->analysis_timeout.", "
                    .( $this->timeout_scan === null ? 'NULL' : $this->timeout_scan ).")";

                if(!mysql_query( $ins, $this->link ))
                {
                    $this->Process_ResponseErrorInit('errno_turn', mysql_errno(), 'error_turn', mysql_error() );
                    $this->Process_GetResponse();
                    Log::writeProcessTurnError( "errno:".mysql_errno()." error:".mysql_error(), __FILE__, __LINE__ );
                    exit();
                }

                $this->process_id = mysql_insert_id($this->link);
                $this->run_id = $this->process_id;
                $this->Process_ResponseInit('turn_run', 1);

                //связываем очередь turn_run & turn_waiting
                $upd = "update ".self::$service_db_name.".".self::$service_table_name_turn_waiting
                    ." set ".self::$service_field_turn_waiting_run_id."=".$this->run_id
                    ." where id=".$this->waiting_id;

                if(!mysql_query( $upd, $this->link ))
                {
                    $this->Process_ResponseErrorInit('errno_turn', mysql_errno(), 'error_turn', mysql_error() );
                    $this->Process_GetResponse();
                    Log::writeProcessTurnError( "errno:".mysql_errno()." error:".mysql_error(), __FILE__, __LINE__ );
                    exit();
                }

                $this->Process_ResponseInit('binding_turn', 1);
                $this->Process_DisconnectMySqlDB();
                $this->Process_GetResponse();
                return true;
            }
            else
            {
                $this->Process_DisconnectMySqlDB();
                $this->Process_GetResponse();
                return false;
            }
        }
        else
        {
            $this->Process_DisconnectMySqlDB();
            $this->Process_GetResponse();
            return false;
        }
    }

    function Process_FinishProcessInTurnRun_OR_StartWaitingProcessInTurnWaiting()
    {
        $this->Process_ConnectMySqlDB();

        //<<START форс-мажор-----------------------------------------------------------

        //если удалили запись из turn_waiting и неуспели почистить за собой в turn_run,
        //при автозагрузке, делаем проверку
        //если есть в turn_run, но нет в turn_waiting, удаляем из turn_run
        //если есть, в turn_run и есть в turn_waiting, чистим БД запускаем заново и пишем в очищенную БД

        //удаляем из turn_waiting строку, которая ждет своего выполненного процесса
        $del = "DELETE FROM ".self::$service_db_name.".".self::$service_table_name_turn_waiting." WHERE id=".$this->waiting_id;
        if(!mysql_query( $del, $this->link ))
        {
            Log::writeProcessTurnError( "errno:".mysql_errno()." error:".mysql_error(), __FILE__, __LINE__ );
            exit();
        }
        $this->waiting_id = 0;

        //разьединяем связь turn_run & turn_waiting и очищаем все данные предыдущего задания
        $upd = "update ".self::$service_db_name.".".self::$service_table_name_turn_run
            ." set ".self::$service_field_turn_run_user_id."=NULL,
            ".self::$service_field_turn_run_waiting_id."=NULL,
            ".self::$service_field_turn_run_login."=NULL,
            ".self::$service_field_turn_run_password."=NULL,
            ".self::$service_field_site_name."=NULL,
            ".self::$service_field_site_email_report."=NULL,
            ".self::$service_field_site_robots."=0,
            ".self::$service_field_site_analysis_type."=0,
            ".self::$service_field_site_limit_pages."=0,
            ".self::$service_field_site_analysis_timeout."=0,
            ".self::$service_field_site_timeout."=NULL"
            ." where id=".$this->run_id;

        if(!mysql_query( $upd, $this->link ))
        {
            Log::writeProcessTurnError( "errno:".mysql_errno()." error:".mysql_error(), __FILE__, __LINE__ );
            exit();
        }
        //>>END----------------------------------------------------------------------
        $this->Process_Clear();

        //назначаем процессу новое задание
        $sel = "SELECT * FROM ".self::$service_db_name.".".self::$service_table_name_turn_waiting
            ." WHERE ".self::$service_field_turn_waiting_run_id." IS NULL ORDER BY id ASC LIMIT 1";
        $res = mysql_query( $sel, $this->link );
        if(mysql_errno())
        {
            Log::writeProcessTurnError( "errno:".mysql_errno()." error:".mysql_error(), __FILE__, __LINE__ );
            //Log::writeProcessTurnError( $sel, __FILE__, __LINE__ );
            //ob_start();
            //var_dump($_REQUEST);
            //$s = ob_get_clean();
            //Log::writeProcessTurnError( $s, __FILE__, __LINE__ );
            exit();
        }
        elseif(mysql_num_rows($res)>0)
        {
            $row = mysql_fetch_array($res, MYSQL_ASSOC);
            $_REQUEST['id']               = $row[self::$service_field_turn_run_user_id];
            $_REQUEST['login']            = html_entity_decode($row[self::$service_field_turn_waiting_login] , ENT_QUOTES, 'UTF-8' );
            $_REQUEST['password']         = html_entity_decode($row[self::$service_field_turn_waiting_password] , ENT_QUOTES, 'UTF-8' );
            $_REQUEST['url']              = html_entity_decode($row[self::$service_field_site_name] , ENT_QUOTES, 'UTF-8' );
            $_REQUEST['email']            = html_entity_decode($row[self::$service_field_site_email_report] , ENT_QUOTES, 'UTF-8' );
            $_REQUEST['robots']           = $row[self::$service_field_site_robots];
            $_REQUEST['analysis_type']    = $row[self::$service_field_site_analysis_type];
            $_REQUEST['limit_pages']      = $row[self::$service_field_site_limit_pages];
            $_REQUEST['analysis_timeout'] = $row[self::$service_field_site_analysis_timeout];
            $_REQUEST['timeout']          = $row[self::$service_field_site_timeout] === null ? "" : $row[self::$service_field_site_timeout];

            $this->Process_Init();
            $this->waiting_id             = intval($row['id']);

            //обновляем данные в turn_run для процесса
            $upd = "update ".self::$service_db_name.".".self::$service_table_name_turn_run
                ." set ".self::$service_field_turn_run_user_id."=".$this->user_id.","
                .self::$service_field_turn_run_waiting_id     ."=".$this->waiting_id.","
                .self::$service_field_turn_run_login          ."='".$this->login."',"
                .self::$service_field_turn_run_password       ."='".$this->password."',"
                .self::$service_field_site_name               ."='".$this->site_name."',"
                .self::$service_field_site_email_report       ."='".$this->site_email_report."',"
                .self::$service_field_site_robots             ."=".$this->robots.","
                .self::$service_field_site_analysis_type      ."=".$this->analysis_type.","
                .self::$service_field_site_limit_pages        ."=".$this->limit_pages.","
                .self::$service_field_site_analysis_timeout   ."=".$this->analysis_timeout.","
                .self::$service_field_site_timeout            ."=".( $this->timeout_scan === null ? 'NULL' : $this->timeout_scan )
                ." where id=".$this->run_id;

            if(!mysql_query( $upd, $this->link ))
            {
                Log::writeProcessTurnError( "errno:".mysql_errno()." error:".mysql_error(), __FILE__, __LINE__ );
                exit();
            }

            //связываем очередь turn_run & turn_waiting
            $upd = "update ".self::$service_db_name.".".self::$service_table_name_turn_waiting
                ." set ".self::$service_field_turn_waiting_run_id."=".$this->run_id
                ." where id=".$this->waiting_id;

            if(!mysql_query( $upd, $this->link ))
            {
                Log::writeProcessTurnError( "errno:".mysql_errno()." error:".mysql_error(), __FILE__, __LINE__ );
                exit();
            }

            $this->Process_DisconnectMySqlDB();
        }
        else
        {
            $del = "DELETE FROM ".self::$service_db_name.".".self::$service_table_name_turn_run." WHERE id=".$this->process_id;
            if(!mysql_query( $del, $this->link ))
            {
                Log::writeProcessTurnError( "errno:".mysql_errno()." error:".mysql_error(), __FILE__, __LINE__ );
                exit();
            }
            $this->process_id = 0;
            $this->Process_DisconnectMySqlDB();
        }
    }
}
?>