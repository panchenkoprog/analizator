<?php
include_once 'simple_html_dom.php';
include_once 'type.php';
include_once 'collection.php';
include_once 'error.php';


class Model extends Service
{
    //----------------данные для БД--------------
    public $db_name       = '' ;
    public $host          = 'localhost';//'10.100.53.2';//'localhost' ;
    public $root_login    = 'root' ;
    public $root_password = '' ;	//test
    public $link          = '' ;
    public $prefix_db     = 'service_';

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
    public static $service_table_name_report              = 'service_report' ;
    public static $service_field_report_user_id           = 'user_id';
    public static $service_field_report_name              = 'report_name';
    public static $service_field_report_date_start_report = 'date_start_report';
    public static $service_field_report_date_stop_report  = 'date_stop_report';
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

    //-------
    public static $service_email_db_name                  = 'service_email';
    public static $service_table_name_email               = 'service_email';
    public static $service_field_email_id                 = 'id';
    public static $service_field_email_id_page            = 'id_page';
    public static $service_field_email_url                = 'url';
    public static $service_field_email_domain             = 'domain';
    public static $service_field_email_title              = 'title';
    public static $service_field_email_keywords           = 'keywords';
    public static $service_field_email_description        = 'description';

    public static $service_field_email_email              = 'email';
    public static $service_field_email_hash               = 'hash';
    public static $service_field_email_not_send           = 'not_send';
    public static $service_field_email_id_email_address   = 'id_email_address';
    public static $service_field_email_message            = 'message';
    public static $service_field_email_date_send          = 'date_send';

    //public $link_service = '';


    //----------------локальные переменные - характеристики сайта-------
    public $url_site             = '';  // URL сайта
    public $host_site            = '';  // хост stll.com.ua сайта (http://stll.com.ua/)
    public $shema_site           = '';  // протокол http сайта (http://stll.com.ua/)
    public $protocol             = 'http';  // протокол http
    public $process_id           = 0;

    //----------------локальные переменные для ф-ии cURL-------
    public $curl_count_redirects = 3;   // количество редиректов для ф-ии cURL
    public $curl_connect_timeout = 3;  // Количество секунд ожидания при попытке соединения.
    public $curl_timeout         = 3;  // Максимально позволенное количество секунд для выполнения cURL-функций.
    public $curl_redirect_url    = '';  // URL - на который ведёт redirect
    public $curl_error_url       = '';  // ошибка при создании БД к которой привел введённый - URL

    //----------------static переменные-------
    static $error                = 0;
    static $load_timeout         = 0;
    static $load_count           = 0;//количество повторных загрузок
    static $counter_error        = 10;//количество возможных повторных загрузок
    static $add_second           = 10;//количество секунд которые добавляются к времени таймаута (время ожидания ответа сервера)
    static $start_sleep          = 0;
    static $stop_sleep           = 3;//задержка между сканированиями, что бы сервер незаподозрил что работает робот
    static $method               = '';
    static $ct                   = array();
    static $cct                  = array();
    //------
    //http://curl.haxx.se/libcurl/c/libcurl-errors.html
    static $curl_operation_timeout   = 28;//код ошибки который возвращает сервер при окончании таймаута (Operation timeout. The specified time-out period was reached according to the conditions.)
    static $curl_http_returned_error = 22;//код ошибки, который возвращается сервером при ошибке >= 400 (This is returned if CURLOPT_FAILONERROR is set TRUE and the HTTP server returns an error code that is >= 400.)
    static $curl_couldnt_resolve_host= 6; //код ошибки, который возвращается сервером при отсутствии интернета (Couldn't resolve host. The given remote host was not resolved.)
    static $curle_couldnt_connect    = 7;//Failed to connect() to host or proxy.
    static $curl_argument        = null;

    public $user_agent_mozila    = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:29.0) Gecko/20100101 Firefox/29.0';
    public $headers_mozila       = array(
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*;q=0.8',
        'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3',
        'Accept-Encoding: deflate',
        'Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.7'
    );
    public $user_agent_chrome    = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.153 Safari/537.36';
    public $headers_chrome       = array(
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*;q=0.8',
        'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4,uk;q=0.2',
        'Accept-Encoding: deflate',
        'Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.7'
    );

    //----------------название таблиц------------
    public $tb_pages       = 'pages' ;      // основная таблица | id | url | page_code | title | keywords | description | page_size | links | images | content
    public $tb_title       = 'title' ;      // доп. таблица | id | info | count-words | count-symbols
    public $tb_keywords    = 'keywords' ;   // доп. таблица | id | info | count_words | count_symbols
    public $tb_description = 'description' ;// доп. таблица | id | info | count-words | count-symbols
    public $tb_links       = 'links' ;      // доп. таблица | id | page_id | href | anchor
    public $tb_images      = 'images' ;     // доп. таблица | id | page_id | title | count_word_title | alt | count_word_alt | width | height |size
    public $tb_content     = 'content' ;    // доп. таблица | id | original_text | text_words | count_words | count_symbols
    public $tb_counter     = 'counter';     // доп. таблица | id | counter_scan_pages | date_start_scan | date_stop_scan
    public $tb_toggle      = 'toggle';      // таблица-переключатель - когда закончится сканирование и парсинг, единственное значение поля flag, будет == 1, это значит что можно начать сравнение !!!
    public $tb_tools       = 'tools';       // таблица настроек анализа сайта
    public $tb_email       = 'email';       // таблица email адресов найденных на сайте | id | id_page | id_email_address
    public $tb_email_address = 'email_address';// таблица уникальных email адресов найденных на сайте | id | email | hash | not_send | id_page
    public $tb_email_send_save = 'email_send_save';// таблица сохранения истории отправки писем на email пользователя | id | email | message | date_send

    public $tb_pages_archive       = 'pages_archive';       // табл. archive - полная копия табл. pages + поле page_id
    public $tb_title_archive       = 'title_archive' ;      // доп. таблица | id | info | count-words | count-symbols
    public $tb_keywords_archive    = 'keywords_archive' ;   // доп. таблица | id | info | count_words | count_symbols
    public $tb_description_archive = 'description_archive' ;// доп. таблица | id | info | count-words | count-symbols
    public $tb_links_archive       = 'links_archive' ;      // доп. таблица | id | page_id | href | anchor
    public $tb_images_archive      = 'images_archive' ;     // доп. таблица | id | page_id | title | count_word_title | alt | count_word_alt | width | height |size
    public $tb_content_archive     = 'content_archive' ;    // доп. таблица | id | original_text | text_words | count_words | count_symbols
    public $tb_email_archive       = 'email_archive';       // доп. таблица | id | id_page | id_email_address
    public $tb_email_address_archive = 'email_address_archive';// доп.таблица уникальных email адресов найденных на сайте | id | email | hash | not_send | id_page
    public $tb_email_send_save_archive = 'email_send_save_archive';// доп. таблица сохранения истории отправки писем на email пользователя | id | email | message | date_send

    /*public $MODEL_DEFINE_TAGS = array(
        'h1' => true,
        'h2' => true,
        'h3' => true,
        'h4' => true,
        'h5' => true,
        'h6' => true,
        'b' => true,
        'strong' => true
    );//массив тегов, которые нужно проанализировать - true - анализируем | false - не анализируем*/

    public $MODEL_DEFINE_TAGS = array();//массив тегов, которые нужно проанализировать

    /*public $ar_tags        = array(
                                    0 => 'h1',
                                    1 => 'h2',
                                    2 => 'h3',
                                    3 => 'h4',
                                    4 => 'h5',
                                    5 => 'h6',
                                    6 => 'b',
                                    7 => 'strong'
                                );//массив тегов, которые нужно проанализировать
    */
    public $ar_tags           = array();//массив тегов, которые нужно проанализировать

    //----------------локальные переменные для сканирования и парсинга -------
    public $id_keywords           = null;          // id мета-тега который записываем в таблицу pages в поле keywords
    public $id_description        = null;          // id мета-тега который записываем в таблицу pages в поле description
    public $id_title              = null;          // id title который записываем в таблицу pages в поле title
    public $id_content            = null;          // id content который записываем в таблицу pages в поле content
    public $id_image              = null;          // id image который записываем в таблицу pages в поле images
    public $id_last_insert_pages  = null;          // последний id который записали в таблицу pages
    public $id_current_page       = null;          // id текушей страницы из таблицы pages для парсинга
    public $html                  = '';            // html для поиска <title> & <a> & <img>
    public $html_data             = '';            // данные которые получает ф-я cURL

    public $count_level_links     = 0;             // уровень вложенности ссылки
    public $current_url           = '';            //текущая ссылка

    public $cur_page              = 0;             //номер текушей страницы, с которой работаем == id в таблице pages
    public $count_pages           = 0;             //общее количество страниц для обработки == количеству записей в таблице pages
    public $ar_pages              = array();       //записи в таблице pages
    public $limiter_pages         = 0;             //ограничитель сканируемых страниц не должен превышать $this->limit_pages

    //----------------локальные переменные для сравнения -------
    public $counter_scan        = 0;        //счетчик сканирований
    public $date_start_scan     = '';       //начало текущего сканирования
    public $date_stop_scan      = '';       //конец текущего сканирования
    public $tmp_id              = 0;        //временный id для tmp-db
    public $tmp_data_base_report= '';        //название текущего отчёта (название базы данных)


    public $user_login           = '';//логин пользователя, который передаётся через ф-ю exec() - нужен для определения прав админа
    public $user_password        = '';//пароль пользователя, который передаётся через ф-ю exec() - нужен для определения прав админа
    public $site_email_report    = '';   // email на который будет отправлятся отчет
    public $analysis_type        = 0;    //тип анализа: 0-бечплатный, 1-платный
    public $limit_pages          = 0;   //количество сканируемых страниц
    public $analysis_timeout     = 0;    //повторное сканирование 1-нет 2-(через 2-недели после сканирования) 3-($this->timeout_scan - время между сканированием)
    public $timeout_scan         = null; //время между сканированием
    public $robots               = 0;    //проверка robots.txt (1 - включена, 0 - нет)
    public $user_id_session      = 0;    //id под которым зарегестрирован user и который находится в $_SESSION['id']
    public $per_page             = 100;    //количество выводимых на странице записей.
    public $row_page             = 100;   //количество выводимых строк в таблице на одной странице.
    public $count_navigate_menu  = 3;    //количество пунктов меню в navigate_menu (bottom_menu)

    //-------строки шаблоны из которых формируются MIX-строки-----------
    public $eng_small  = 'abcdefghijklmnopqrstuvwxyz';
    public $eng_big    = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    public $number     = '0123456789';
    public $rus_small  = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюя';
    public $rus_big    = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ';
    public $spec_symbols_pass      = '!#*+.-=?@$';
    public $spec_symbols_login     = '';//empty
    public $spec_symbols_email     = '@.';//"^[a-z0-9_]+@[a-z]+\\.[a-z]{2,4}$"
    public $spec_symbols_telephone = ' ';//пробел
    public $spec_symbols_capt      = '';//empty
    //------MIX-строки для проверки данных------------------------------
    public $reg_str_login       = '';
    public $reg_str_password    = '';
    //reg-строка для проверки email
    public $reg_str_email       = "/^[a-z0-9_\.\-]+@[a-z]+\.[a-z]{2,4}$/";
    public $reg_search_str_email= "/[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.+[a-zA-Z]{2,6}/";//шаблон для поиска email на сайте
    //reg-строка для проверки telephone
    //public $reg_str_telephone   = "/^\d{3}\s\d{7}$/";
    public $reg_str_capt        = '';

    //==================================методы взаимодействия с БД======================================

    public static function ModelConnectMySqlDB()
    {
        $link = mysql_connect(self::$service_host, self::$service_root_login, self::$service_root_password) or die ('Ошибка');

        if(isset($_REQUEST['db_name']) && $_REQUEST['db_name'] != '' && isset($_REQUEST['submit']) && $_REQUEST['submit'] != '' )
        {
            //mysql_select_db($_REQUEST['db_name']) or die("Ошибка подключения к БД");
        }
        else
        {
            //mysql_select_db(self::$service_db_name) or die("Ошибка подключения к БД");
            if(!mysql_select_db(self::$service_db_name))
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> Ошибка подключения к БД ".mysql_errno()." файл ".__FILE__."  стр. ".__LINE__."</p>", self::$service_db_name );
                exit();
            }
            mysql_query("SET NAMES utf8"); // теперь всё будет сохранятся в MySql правильной кодировке
        }
        return $link;
    }

    private function ModelDefine()
    {
        //строка для проверки логина (a-z)(0-9)
        $this->reg_str_login = $this->eng_small . $this->number;
        //строка для проверки пароля (a-z)(0-9)(!#*+.-=?@$)
        $this->reg_str_password = $this->eng_small . $this->number . $this->spec_symbols_pass;
        //строка для проверки captcha
        $this->reg_str_capt = $this->eng_small . $this->number;

        $this->limit_pages = LIMIT_PAGES;

        //http://www.php.net/manual/ru/function.get-defined-constants.php
        //$ar = get_defined_constants();//Возвращает ассоциативный массив с именами и значениями всех предопределенных констант
        /*global $MODEL_DEFINE_TAGS;
        foreach($MODEL_DEFINE_TAGS as $k=>$v)
        {
            $this->ar_tags[] = $k;
            $def = 'define_'.$k;
            $ar = get_defined_constants();
            if(!isset($ar[$def]))
            {
                define($def, $v);
            }
        }*/

        global $MODEL_DEFINE_TAGS;
        $this->MODEL_DEFINE_TAGS = $MODEL_DEFINE_TAGS;

        foreach($this->MODEL_DEFINE_TAGS as $k=>$v)
        {
            $this->ar_tags[] = $k;
        }
    }

    function __construct()
    {
        //строка для проверки логина (a-z)(0-9)
        $this->reg_str_login = $this->eng_small . $this->number;
        //строка для проверки пароля (a-z)(0-9)(!#*+.-=?@$)
        $this->reg_str_password = $this->eng_small . $this->number . $this->spec_symbols_pass;
        //строка для проверки captcha
        $this->reg_str_capt = $this->eng_small . $this->number;

        $this->link = Model::ModelConnectMySqlDB();
    }

    function ModelInit($url)
    {
        $this->ModelDefine();

        self::$load_timeout = $this->curl_timeout;

        $ar_components_url = parse_url($url);

        if( !isset($ar_components_url['scheme']))
        {
            $url              = $this->protocol.'://'.$url;//протокол по умолчанию
            $this->host_site  = parse_url( $url, PHP_URL_HOST);  //хост сайта
            $this->url_site   = $url;
            $this->db_name    = str_replace('.', '_', $this->host_site);
            $this->db_name    = str_replace('-', '__', $this->db_name);//!!!!!!!!!!!!!!! (-) == (__) ModelGetReports() и ModelInit()
            if($this->user_id_session)
            {
                $this->db_name    = $this->prefix_db . $this->user_id_session . '_' . $this->db_name;//получаем название БД
            }
            else
            {
                $this->db_name    = $this->prefix_db . $this->db_name;//получаем название БД
            }
        }
        else
        {
            $this->protocol   = parse_url( $url, PHP_URL_SCHEME);//протокол сайта
            $this->host_site  = parse_url( $url, PHP_URL_HOST);  //хост сайта
            $this->url_site   = $this->protocol.'://'.$this->host_site;
            $this->db_name    = str_replace('.', '_', $this->host_site);
            $this->db_name    = str_replace('-', '__', $this->db_name);//!!!!!!!!!!!!!!! (-) == (__) ModelGetReports() и ModelInit()
            if($this->user_id_session)
            {
                $this->db_name    = $this->prefix_db . $this->user_id_session . '_' . $this->db_name;//получаем название БД
            }
            else
            {
                $this->db_name    = $this->prefix_db . $this->db_name;//получаем название БД
            }
        }
    }

    function ModelConnectMySql()
    {
        $this->link = mysql_connect($this->host, $this->root_login, $this->root_password);
        if (!$this->link)
        {
            if(SHOW_ECHO_ERROR_CONNECT_DB)
                Log::write( "<p> Ошибка соединения: ".mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
    }

    function ModelCreateDB()
    {
        $sql = 'CREATE DATABASE '.$this->db_name.' CHARACTER SET utf8 COLLATE utf8_general_ci';
        mysql_query($sql, $this->link);
        if(mysql_errno() == "1007"){
            if(SHOW_ECHO_ERROR_CREATE_DB)
                Log::write( "<p> База ".$this->db_name." уже существует !</p>", $this->host_site );
        } else if (mysql_errno()){
            if(SHOW_ECHO_ERROR_CREATE_DB)
                Log::write( "<p> Ошибка при создании базы данных: " . mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }else{
            if(SHOW_ECHO_CREATE_DB)
                Log::write( "<p> База ".$this->db_name." успешно создана </p>", $this->host_site );
        }
    }

    function ModelConnectDB()
    {
        if(mysql_select_db($this->db_name, $this->link))
        {
            mysql_query("SET NAMES utf8"); // теперь всё будет сохранятся в MySql правильной кодировке
            if(SHOW_ECHO_CONNECT_DB)
                Log::write( "<p> Успешное подключение к БД ".$this->db_name."</p>", $this->host_site );
        }else{
            if(SHOW_ECHO_ERROR_CONNECT_DB)
                Log::write( "<p> Ошибка при подключении к БД " . mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
    }

    function ModelCreateTables()
    {
        //перед обращением в базу данных, ей нужно дать понять, что работать мы собираемся именно в UTF-8,
        //для этого, после соединения с базой пишем:
        mysql_query("SET NAMES utf8"); // теперь всё будет сохранятся в MySql правильной кодировке

        $this->ModelCreateTb();
        $this->ModelCreateTb(false);
    }

    function ModelCreateTb( $dop=true )
    {
        if($dop)
        {
            $tb_title       = $this->tb_title;
            $tb_keywords    = $this->tb_keywords;
            $tb_description = $this->tb_description;
            $tb_links       = $this->tb_links;
            $tb_images      = $this->tb_images;
            $tb_content     = $this->tb_content;
            $tb_pages       = $this->tb_pages;
            $tb_email       = $this->tb_email;
            $tb_email_address = $this->tb_email_address;
            $tb_email_send_save = $this->tb_email_send_save;

            //--------создание таблицы counter-------
            $tb0 = "create table ".$this->tb_counter."( id integer not null auto_increment,
            counter_scan_page integer default null,
            counter_scan_pages integer default null,
            counter_parse_page integer default null,
            counter_parse_pages integer default null,
            counter_compare_page integer default null,
            counter_compare_pages integer default null,
            date_start_scan DATETIME default null,
            date_stop_scan DATETIME default null, primary key (id) ) default charset = utf8";
            $this->ModelCreateTable($tb0, $this->tb_counter);

            //--------создание таблицы toggle-------
            $tb1 = "create table ".$this->tb_toggle."( id integer not null auto_increment,
            flag integer default 0,
            flag_scan integer default 0,
            flag_parse integer default 0,
            flag_compare integer default 0, primary key (id) ) default charset = utf8";
            $this->ModelCreateTable($tb1, $this->tb_toggle);

            //--------создание таблицы tools-------
            $tb_tools = "create table ".$this->tb_tools."( id integer not null auto_increment,
            ".self::$service_field_site_email_report." TEXT default null,
            robots integer default 0,
            analysis_type integer default 0,
            limit_pages integer default 0,
            analysis_timeout integer default 0,
            timeout integer default null, primary key (id) ) default charset = utf8";
            $this->ModelCreateTable($tb_tools, $this->tb_tools);
        }
        else
        {
            $tb_title       = $this->tb_title_archive;
            $tb_keywords    = $this->tb_keywords_archive;
            $tb_description = $this->tb_description_archive;
            $tb_links       = $this->tb_links_archive;
            $tb_images      = $this->tb_images_archive;
            $tb_content     = $this->tb_content_archive;
            $tb_pages       = $this->tb_pages_archive;
            $tb_email       = $this->tb_email_archive;
            $tb_email_address = $this->tb_email_address_archive;
            $tb_email_send_save = $this->tb_email_send_save_archive;
        }

        //------создание таблицы title-------
        // id | info | count-words | count-symbols
        $tb2 = "create table ".$tb_title."( id integer not null auto_increment,
		info MEDIUMTEXT default null,
        count_words integer default null,
        count_symbols integer default null, primary key (id) ) default charset = utf8";

        //------создание таблицы keywords-------
        // id | info | count_words | count_symbols
        $tb3 = "create table ".$tb_keywords."( id integer not null auto_increment,
		info TEXT default null,
        count_words integer default null,
        count_symbols integer default null, primary key (id) ) default charset = utf8";

        //------создание таблицы description-------
        // id | info | count-words | count-symbols
        $tb4 = "create table ".$tb_description."( id integer not null auto_increment,
		info MEDIUMTEXT default null,
        count_words integer default null,
        count_symbols integer default null, primary key (id) ) default charset = utf8";

        //------создание таблицы links-------
        // id | page_id | href | anchor
        $tb5 = "create table ".$tb_links."( id integer not null auto_increment,
        page_id integer default null,
		internal_link integer default null,
		href TEXT default null,
        anchor TEXT default null, primary key (id) ) default charset = utf8";

        //------создание таблицы images-------
        // id | page_id | title | count_word_title | alt | count_word_alt | width | height |size
        $tb6 = "create table ".$tb_images."( id integer not null auto_increment,
        page_id integer default null,
		src TEXT default null,
		title TEXT default null,
        count_word_title integer default null,
		alt TEXT default null,
		count_word_alt integer default null,
		width integer default null,
		height integer default null,
        size double default null, primary key (id) ) default charset = utf8";

        //------создание таблицы content-------
        // id | original_text | text_words | count_words | count_symbols
        $tb7 = "create table ".$tb_content."( id integer not null auto_increment,
		original_text MEDIUMTEXT default null,
		text_words MEDIUMTEXT default null,
        count_words integer default null,
        count_symbols integer default null, primary key (id) ) default charset = utf8";

        $this->ModelCreateTable($tb2, $tb_title);
        $this->ModelCreateTable($tb3, $tb_keywords);
        $this->ModelCreateTable($tb4, $tb_description);
        $this->ModelCreateTable($tb5, $tb_links);
        $this->ModelCreateTable($tb6, $tb_images);
        $this->ModelCreateTable($tb7, $tb_content);

        if($dop)
        {
            //------создание таблицы email_send_save-------
            // id | id_page | email | hash | not_send
            $tb_ess = "create table ".$tb_email_send_save."( ".self::$service_field_email_id." integer not null auto_increment,
            ".self::$service_field_email_email." TEXT default null,
            ".self::$service_field_email_message." TEXT default null,
            ".self::$service_field_email_date_send." DATETIME default null, primary key (".self::$service_field_email_id.") ) default charset = utf8";

            $this->ModelCreateTable($tb_ess, $tb_email_send_save);
        }

        //если админ ! тогда создаём таблицы для сбора email на сайтах
        if($this->user_login == parent::$login && $this->user_password == parent::$password)
        {
            //------создание таблицы email_address-------
            // id | id_page | email | hash | not_send
            $tb_ea = "create table ".$tb_email_address."( ".self::$service_field_email_id." integer not null auto_increment,
            ".self::$service_field_email_email." TEXT default null,
            ".self::$service_field_email_hash." TEXT default null,
            ".self::$service_field_email_not_send." integer default 0,
            ".self::$service_field_email_id_page." integer default null, primary key (".self::$service_field_email_id.") ) default charset = utf8";

            //------создание таблицы email-------
            // id | id_page | id_email_address
            $tb_e = "create table ".$tb_email."( ".self::$service_field_email_id." integer not null auto_increment,
            ".self::$service_field_email_id_page." integer default null,
            ".self::$service_field_email_id_email_address." integer default null,
            foreign key (".self::$service_field_email_id_email_address.") references ".$tb_email_address."(".self::$service_field_email_id."),
            primary key (".self::$service_field_email_id.") ) default charset = utf8";

            $this->ModelCreateTable($tb_ea, $tb_email_address);
            $this->ModelCreateTable($tb_e, $tb_email);
        }

        $s = ' integer default null,';
        $ss = '';
        $c = ' integer default 0,';
        $ch = '';
        foreach($this->ar_tags as $tag)
        {
            $ss .= $tag.$s;
            $ch .= prefix_tag_change . $tag . $c;
        }

        if($dop)
        {
            //------создание таблицы h1...h6, b, strong-------
            // id | info | count-words | count-symbols
            foreach($this->ar_tags as $tag)
            {
                $tmp_tb = "create table ".$tag."( id integer not null auto_increment,
                page_id integer default null,
                info MEDIUMTEXT default null,
                count_words integer default null,
                count_symbols integer default null, primary key (id) ) default charset = utf8";
                $this->ModelCreateTable( $tmp_tb, $tag );
            }

            //------создание таблицы pages-------
            // id | url | page_code | title | keywords | description | page_size | links | images | content
            $tb9 = "create table ".$tb_pages."( id integer not null auto_increment,
            url TEXT default null,
            page_code LONGTEXT default null,
            http_code integer default null,
            content_type TEXT default null,
            redirect_url TEXT default null,
            parent_url TEXT default null,
            load_count integer default null,
            load_timeout integer default null,
            title integer default null,
            keywords integer default null,
            description integer default null,
            level_links integer default null,
            connect_time float default null,
            total_time float default null,
            page_size float default null,
            page_speed float default null,
            ".$ss."
            links integer default null,
            images integer default null,
            content integer default null,
            ".$this->tb_email." integer default null,
            scan integer default null,
            exist integer default null,
            create_page integer default 0,
            update_page integer default 0,
            delete_page integer default 0,
            date_change_page DATETIME default null,
            change_page_code integer default 0,
            change_http_code integer default 0,
            change_content_type integer default 0,
            change_redirect_url integer default 0,
            change_parent_url integer default 0,
            change_load_count integer default 0,
            change_load_timeout integer default 0,
            change_title integer default 0,
            change_keywords integer default 0,
            change_description integer default 0,
            change_level_links integer default 0,
            change_total_time integer default 0,
            change_page_size integer default 0,
            change_page_speed integer default 0,
            ".$ch."
            change_links integer default 0,
            change_images integer default 0,
            change_content integer default 0,
            change_".$this->tb_email." integer default 0,
            change_exist integer default 0,
            reserve_http_code integer default null,
            reserve_content_type TEXT default null,
            foreign key (title) references ".$tb_title."(id),
            foreign key (keywords) references ".$tb_keywords."(id),
            foreign key (description) references ".$tb_description."(id),
            foreign key (content) references ".$tb_content."(id),
            primary key (id) ) default charset = utf8";
        }
        else
        {
            //------создание таблицы h1...h6, b, strong-------
            // id | info | count-words | count-symbols
            foreach($this->ar_tags as $tag)
            {
                $tag_archive = $tag . postfix_tag_archive;
                $tmp_tb = "create table ".$tag_archive."( id integer not null auto_increment,
                page_id integer default null,
                info MEDIUMTEXT default null,
                count_words integer default null,
                count_symbols integer default null, primary key (id) ) default charset = utf8";
                $this->ModelCreateTable( $tmp_tb, $tag_archive );
            }
            //------создание таблицы pages_archive-------
            // id | page_id | url | page_code | title | keywords | description | page_size | links | images | content
            $tb9 = "create table ".$tb_pages."( id integer not null auto_increment,
            page_id integer default null,
            url TEXT default null,
            page_code LONGTEXT default null,
            http_code integer default null,
            content_type TEXT default null,
            redirect_url TEXT default null,
            parent_url TEXT default null,
            load_count integer default null,
            load_timeout integer default null,
            title integer default null,
            keywords integer default null,
            description integer default null,
            level_links integer default null,
            connect_time float default null,
            total_time float default null,
            page_size float default null,
            page_speed float default null,
            ".$ss."
            links integer default null,
            images integer default null,
            content integer default null,
            ".$this->tb_email." integer default null,
            scan integer default null,
            exist integer default null,
            create_page integer default 0,
            update_page integer default 0,
            delete_page integer default 0,
            date_change_page DATETIME default null,
            change_page_code integer default 0,
            change_http_code integer default 0,
            change_content_type integer default 0,
            change_redirect_url integer default 0,
            change_parent_url integer default 0,
            change_load_count integer default 0,
            change_load_timeout integer default 0,
            change_title integer default 0,
            change_keywords integer default 0,
            change_description integer default 0,
            change_level_links integer default 0,
            change_total_time integer default 0,
            change_page_size integer default 0,
            change_page_speed integer default 0,
            ".$ch."
            change_links integer default 0,
            change_images integer default 0,
            change_content integer default 0,
            change_".$this->tb_email." integer default 0,
            change_exist integer default 0,
            foreign key (title) references ".$tb_title."(id),
            foreign key (keywords) references ".$tb_keywords."(id),
            foreign key (description) references ".$tb_description."(id),
            foreign key (content) references ".$tb_content."(id),
            primary key (id) ) default charset = utf8";
        }

        $this->ModelCreateTable($tb9, $tb_pages);
    }

    function ModelCreateTable($tb_query, $tb_name)
    {
        mysql_query($tb_query, $this->link);

        if(mysql_errno() == "1050")
        {
            if(SHOW_ECHO_ERROR_CREATE_DB)
                Log::write( "<p> таблица ".$tb_name." уже существует !</p>", $this->host_site );
            //return;
        }
        else if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR_CREATE_DB)
                Log::write( "<p> таблица ".$tb_name." не создана, ошибка : ".mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        else
        {
            if(SHOW_ECHO_CREATE_DB)
                Log::write( "<p> таблица ".$tb_name." создана !</p>", $this->host_site );
            //return;
        }
    }

    //работает с установками по умолчанию, данные приходящие с файла install.php не учитываются - при необходимости нужно доделать !!!
    function ModelInstallService()
    {
        $this->host_site = self::$service_db_name;
        $this->db_name = self::$service_db_name;
        //$this->ModelConnectMySql();
        $this->ModelCreateDB();
        $this->ModelConnectDB();

        //перед обращением в базу данных, ей нужно дать понять, что работать мы собираемся именно в UTF-8,
        //для этого, после соединения с базой пишем:
        mysql_query("SET NAMES utf8"); // теперь всё будет сохранятся в MySql правильной кодировке

        $tb1 = "create table ".self::$service_table_name_users."( id integer not null auto_increment,
		".self::$service_field_users_email." TEXT default null,
		".self::$service_field_users_login." TEXT default null,
		".self::$service_field_users_password." TEXT default null,
        ".self::$service_field_users_site." integer default null,
        ".self::$service_field_users_turn_run." integer default null,
        ".self::$service_field_users_turn_waiting." integer default null,
        ".self::$service_field_users_balance." DECIMAL(12,2) default 0, primary key (id) ) default charset = utf8";

        $tb2 = "create table ".self::$service_table_name_site."( id integer not null auto_increment,
		".self::$service_field_site_user_id." integer default null,
		".self::$service_field_site_db_name." TEXT default null,
		".self::$service_field_site_name." TEXT default null,
		".self::$service_field_site_email_report." TEXT default null,
		".self::$service_field_site_site_report." integer default 0,
		".self::$service_field_site_robots." integer default 0,
        ".self::$service_field_site_analysis_type." integer default 0,
        ".self::$service_field_site_limit_pages." integer default 0,
        ".self::$service_field_site_analysis_timeout." integer default 0,
        ".self::$service_field_site_timeout." integer default null, primary key (id) ) default charset = utf8";

        $tb3 = "create table ".self::$service_table_name_report."( id integer not null auto_increment,
		".self::$service_field_report_user_id." integer default null,
		".self::$service_field_report_name." TEXT default null,
		".self::$service_field_report_date_start_report." DATETIME default null,
		".self::$service_field_report_date_stop_report." DATETIME default null, primary key (id) ) default charset = utf8";

        $tb4 = "create table ".self::$service_table_name_turn_run."( id integer not null auto_increment,
		".self::$service_field_turn_run_user_id." integer default null,
		".self::$service_field_turn_run_waiting_id." integer default null,
		".self::$service_field_turn_run_login." TEXT default null,
		".self::$service_field_turn_run_password." TEXT default null,
		".self::$service_field_site_name." TEXT default null,
		".self::$service_field_site_email_report." TEXT default null,
		".self::$service_field_site_robots." integer default 0,
        ".self::$service_field_site_analysis_type." integer default 0,
        ".self::$service_field_site_limit_pages." integer default 0,
        ".self::$service_field_site_analysis_timeout." integer default 0,
        ".self::$service_field_site_timeout." integer default null, primary key (id) ) default charset = utf8";

        $tb5 = "create table ".self::$service_table_name_turn_waiting."( id integer not null auto_increment,
		".self::$service_field_turn_waiting_user_id." integer default null,
		".self::$service_field_turn_waiting_run_id." integer default null,
		".self::$service_field_turn_waiting_login." TEXT default null,
		".self::$service_field_turn_waiting_password." TEXT default null,
		".self::$service_field_site_name." TEXT default null,
		".self::$service_field_site_email_report." TEXT default null,
		".self::$service_field_site_robots." integer default 0,
        ".self::$service_field_site_analysis_type." integer default 0,
        ".self::$service_field_site_limit_pages." integer default 0,
        ".self::$service_field_site_analysis_timeout." integer default 0,
        ".self::$service_field_site_timeout." integer default null, primary key (id) ) default charset = utf8";

        $this->ModelCreateTable($tb1, self::$service_table_name_users);
        $this->ModelCreateTable($tb2, self::$service_table_name_site);
        $this->ModelCreateTable($tb3, self::$service_table_name_report);
        $this->ModelCreateTable($tb4, self::$service_table_name_turn_run);
        $this->ModelCreateTable($tb5, self::$service_table_name_turn_waiting);

        //------------------------------------------
        $this->host_site = self::$service_email_db_name;
        $this->db_name = self::$service_email_db_name;
        $this->ModelCreateDB();
        $this->ModelConnectDB();

        mysql_query("SET NAMES utf8"); // теперь всё будет сохранятся в MySql правильной кодировке

        // id | url | domain | title | description | keywords | email
        $tb4 = "create table ".self::$service_table_name_email."( ".self::$service_field_email_id." integer not null auto_increment,
		".self::$service_field_email_url." TEXT default null,
		".self::$service_field_email_domain." TEXT default null,
        ".self::$service_field_email_title." TEXT default null,
        ".self::$service_field_email_keywords." TEXT default null,
        ".self::$service_field_email_description." TEXT default null,
        ".self::$service_field_email_email." TEXT default null, primary key (".self::$service_field_email_id.") ) default charset = utf8";

        $this->ModelCreateTable($tb4, self::$service_table_name_email);
    }

    //прошлое название ModelDB()
    function ModelCreateDataBase()
    {
        /*$res = false;

        $this->ModelCurlInit($ch, $this->url_site);

        if(curl_exec($ch))
        {
            $ar = curl_getinfo($ch);

            if(REDIRECT)
            {
                if( (isset($ar['url']) && parse_url($ar['url'], PHP_URL_HOST) == $this->host_site)
                    && (isset($ar['http_code']) && $ar['http_code'] == 200)
                    && (isset($ar['content_type']) && strstr($ar['content_type'], 'text/html')) )
                {
                    $this->ModelConnectMySql();
                    $this->ModelCreateDB();
                    $this->ModelConnectDB();
                    $this->ModelCreateTables();
                    Log::write( "БД ($this->db_name) и таблицы созданы !!!", $this->host_site );
                    $res = true;
                }
                elseif( (isset($ar['url']) && parse_url($ar['url'], PHP_URL_HOST) != $this->host_site)
                    && (isset($ar['http_code']) && $ar['http_code'] == 200)
                    && (isset($ar['content_type']) && strstr($ar['content_type'], 'text/html')) )
                {
                    $this->curl_redirect_url = "Редирект идет на страницу с другим доменом - ".$ar['redirect_url'];
                    if(SHOW_ECHO_ERROR)
                    {
                        Log::write( $this->curl_redirect_url, $this->host_site );
                        Log::write( "БД ($this->db_name) и таблицы НЕ созданы !!! файл: ".__FILE__."  стр. ".__LINE__, $this->host_site );
                    }
                    //exit();
                    $res = false;
                }
                else
                {
                    ob_start();
                    var_dump($ar);
                    $error = ob_get_clean();
                    if(SHOW_ECHO_ERROR)
                    {
                        Log::write( $error, $this->host_site );
                        Log::write( "БД ($this->db_name) и таблицы НЕ созданы !!! файл: ".__FILE__."  стр. ".__LINE__, $this->host_site );
                    }
                    //exit();
                    $res = false;
                }
            }
            else
            {
                if( (isset($ar['url']) && parse_url($ar['url'], PHP_URL_HOST) == $this->host_site)
                    && (isset($ar['http_code']) && $ar['http_code'] == 200)
                    && (isset($ar['content_type']) && strstr($ar['content_type'], 'text/html')) )
                {
                    $this->ModelConnectMySql();
                    $this->ModelCreateDB();
                    $this->ModelConnectDB();
                    $this->ModelCreateTables();
                    Log::write( "БД ($this->db_name) и таблицы созданы !!!", $this->host_site );
                    $res = true;
                }
                elseif( (isset($ar['url']) && parse_url($ar['url'], PHP_URL_HOST) == $this->host_site)
                    && (isset($ar['http_code']) && $ar['http_code'] != 200)
                    && (isset($ar['content_type']) && strstr($ar['content_type'], 'text/html')) )
                {
                    $this->curl_redirect_url = "Редирект запрещён, редирект на страницу - ".$ar['redirect_url'];
                    if(SHOW_ECHO_ERROR)
                    {
                        Log::write( $this->curl_redirect_url, $this->host_site );
                        Log::write( "БД ($this->db_name) и таблицы НЕ созданы !!! файл: ".__FILE__."  стр. ".__LINE__, $this->host_site );
                    }
                    //exit();
                    $res = false;
                }
                else
                {
                    ob_start();
                    var_dump($ar);
                    $error = ob_get_clean();
                    if(SHOW_ECHO_ERROR)
                    {
                        Log::write( $error, $this->host_site );
                        Log::write( "БД ($this->db_name) и таблицы НЕ созданы !!! файл: ".__FILE__."  стр. ".__LINE__, $this->host_site );
                    }
                    //exit();
                    $res = false;
                }
            }

        }
        else
        {
            //здесь могут быть ошибки :
            //404 и т.п.                 - The requested URL returned error: 404
            //превышение лимита ожидания - Operation timed out after 10000 milliseconds with 0 bytes received

            //если константа REDIRECT == true, может быть ошибка :
            //превышение лимита редиректа $this->curl_count_redirects - Maximum (limit) redirects followed

            $this->curl_error_url = "Ошибка ".curl_errno($ch)." ".curl_error($ch);
            if(SHOW_ECHO_ERROR)
            {
                Log::write( $this->curl_error_url, $this->host_site );
                Log::write( "БД ($this->db_name) и таблицы НЕ созданы !!! файл: ".__FILE__."  стр. ".__LINE__, $this->host_site );
            }
            //exit();
            $this->ModelChargeTimeout( curl_errno($ch), __METHOD__ );
            $res = $this->ModelStartTimeout();
            $this->ModelStopTimeout();
        }
        curl_close($ch);
        unset($ch);
        unset($ar);
        return $res;*/
        $this->ModelConnectMySql();
        $this->ModelCreateDB();
        $this->ModelConnectDB();
        $this->ModelCreateTables();
        //return true;
    }

    //=====================загрузки страницы по ошибке 28-Operation timeout==============================

    function ModelChargeTimeout( $error, $method, $argument=null )
    {
        if(self::$load_count < self::$counter_error)
        {
            self::$error  = $error;
            self::$method = $method;
            if($argument!==null)
            {
                self::$curl_argument = $argument;
            }
            self::$ct[self::$load_count]  = $this->curl_timeout;
            self::$cct[self::$load_count] = $this->curl_connect_timeout;

            if(self::$error == self::$curl_operation_timeout)//Operation timeout
            {
                self::$load_count++;
            }
        }
    }

    function ModelStartTimeout()
    {
        if(self::$error == self::$curl_http_returned_error)//The requested URL returned error >= 400
        {
            return false;
        }
        elseif(self::$error == self::$curl_operation_timeout)//Operation timeout
        {
            if(self::$load_count < self::$counter_error)
            {
                self::$load_timeout = (($this->curl_timeout + $this->curl_connect_timeout) / 2) + (self::$load_count * self::$add_second);
                //$t = rand($t, $t+round($t/3));
                $this->curl_timeout = self::$load_timeout;
                $this->curl_connect_timeout = self::$load_timeout;
                sleep(rand(self::$start_sleep, self::$stop_sleep));
                Log::write("<p>Повторный (".self::$load_count.") вызов метода ".self::$method." с таймаутом ".self::$load_timeout." !!! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                self::$error = 0;
                if(self::$curl_argument===null)
                {
                    return call_user_func(self::$method);
                }
                else
                {
                    return call_user_func_array(self::$method, array(self::$curl_argument));
                }
            }
            else
            {
                Log::write("<p>Лимит (".self::$counter_error.") повторных вызов метода ".self::$method." с таймаутом исчерпан !!! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    function ModelStopTimeout()
    {
        if(self::$load_count)
        {
            self::$load_count--;
            $this->curl_timeout = self::$ct[self::$load_count];
            $this->curl_connect_timeout = self::$cct[self::$load_count];
            self::$method = '';
            if(self::$load_count == 0)
            {
                self::$load_timeout = self::$ct[self::$load_count];
                self::$ct = array();
                self::$cct = array();
                self::$curl_argument = null;
            }
        }
        else
        {
            self::$error = 0;
            self::$method = '';
            self::$ct = array();
            self::$cct = array();
            self::$curl_argument = null;
        }
    }

    //=============методы определения действий скрипта - парсинга или сравнения==========================

    function ModelIsDataBase()
    {
        $this->link = mysql_connect($this->host, $this->root_login, $this->root_password);
        if (!$this->link)
        {
            if(SHOW_ECHO_ERROR_CONNECT_DB)
                Log::write( "<p> Ошибка соединения: ".mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit() ;
        }
        else
        {
            $res = mysql_query("SHOW DATABASES");

            $flag_db = false;
            $flag_tb = false;

            while ($row = mysql_fetch_assoc($res))
            {
                if($row['Database'] == $this->db_name)
                {
                    $flag_db=true;
                    break;
                }
            }

            if($flag_db)
            {
                if(mysql_select_db($this->db_name, $this->link))
                {
                    mysql_query("SET NAMES utf8"); // теперь всё будет сохранятся в MySql правильной кодировке

                    $sql = "SHOW TABLES FROM ".$this->db_name;
                    $result = mysql_query($sql, $this->link);

                    if (mysql_error())
                    {
                        Log::write( 'Ошибка MySQL: ' . mysql_error(), $this->host_site );
                        exit;
                    }
                    else
                    {
                        while ($row = mysql_fetch_row($result))
                        {
                            if($row[0] == 'toggle')
                            {
                                $flag_tb = true;
                                break;
                            }
                        }

                        if($flag_tb)
                        {
                            return true;
                        }
                        else
                        {
                            return false;
                        }
                    }
                }
                else
                {
                    Log::write( "<p> Ошибка при подключении к БД " . mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
            }
            else
            {
                //
                return false;
            }
        }
    }

    function ModelIsAnalizationSite()
    {
        $sel = "select flag from ".$this->tb_toggle." where id=1";
        $res = mysql_query($sel, $this->link);

        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        else
        {
            $row = mysql_fetch_array($res, MYSQL_ASSOC);
            return $row['flag'];
        }

    }

    //==================================методы поиска и парсинга======================================

    function ModelSetCurrentUrl( $url=null, $html_data=null )
    {
        if($url!==null && $html_data===null)
        {
            $this->current_url = $url;
            $this->html = file_get_html($this->current_url);//получаем html для поиска <title> & <img>
        }
        else if($url!==null && $html_data!==null)
        {
            $this->current_url = $url;
            $this->html = str_get_html($html_data);//получаем html для поиска <title> & <img>
        }
    }

    function ModelWriteDataPage($ch, $str){
        $this->html_data .= $str;
        return strlen($str);
    }

    function ModelCurlInit( & $ch, & $url )
    {
        $ch = curl_init( $url );
        curl_setopt ($ch, CURLOPT_USERAGENT, $this->user_agent_chrome);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$this->headers_chrome);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                      //TRUE для возврата результата передачи в качестве строки из curl_exec() вместо прямого вывода в браузер.
        curl_setopt($ch, CURLOPT_FAILONERROR, true);                         //TRUE для тихого окончания работы, если полученный HTTP-код больше или равен 400. Поведение по умолчанию возвращает страницу как обычно, игнорируя код.
        if(REDIRECT)
        {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);                      //разрешаем редиректы
            curl_setopt($ch, CURLOPT_MAXREDIRS, $this->curl_count_redirects);    //количество редиректов
        }
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->curl_connect_timeout);//Количество секунд ожидания при попытке соединения. Используйте 0 для бесконечного ожидания.
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->curl_timeout);              //Максимально позволенное количество секунд для выполнения cURL-функций.
        curl_setopt($ch, CURLOPT_WRITEFUNCTION, array($this,'ModelWriteDataPage'));//Имя callback-функции, принимающей два параметра. Первым параметром является дескриптор cURL, а вторым параметром является строка с записываемыми данными. Данные должны быть сохранены с помощью данной функции. Она должна возвратить точное количество записанных байт, иначе закачка будет прервана с ошибкой.
    }

    function ModelAddPages()
    {
        $url = $this->url_site;

        $this->ModelUserInsertProjectToAnalyze();//здесь есть подключение к БД service

        /*if(!$this->ModelCreateDataBase())
        {
            if(SHOW_ECHO_ERROR)
                Log::write("Заканчиваем работу !!!", $this->host_site);

            if($this->curl_redirect_url)
                echo "<p>Что то не так с введённым URL : ".$this->curl_redirect_url."</p>";
            elseif($this->curl_error_url)
                echo "<p>Что то не так с введённым URL : ".$this->curl_error_url."</p>";
            else
                echo "<p>Ничего не получается - обратитесь к админу !!!</p>";

            Log::write("***************************STOP**********создаём, анализируем, парсим !!!*****************", $this->host_site);
            return;
        }*/

        $this->ModelCreateDataBase();//здесь есть подключение к БД service_...host...

        //<<---заносим в табл. toggle значение, означающее, что сайт начал анализироваться
        $ins = "insert into ".$this->tb_toggle."( flag ) value ( 0 )";
        if(mysql_query( $ins, $this->link ))
        {
            if(SHOW_ECHO)
                Log::write( "<p> табл. toggle занесено значение - 1, сайт начал анализироваться  !</p>", $this->host_site );
        }
        else
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." в табл. toggle НЕ занесено значение - 1, сайт НЕ анализируется ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        //>>end-----------------------------------------------

        //<<---заносим настройки в табл. tools----------------
        $this->ModelInsertProjectTools();
        //>>end-----------------------------------------------

        //<<---заносим в табл. counter - дуту начала анализа сайта (date_start_scan)
        $ins = "insert into ".$this->tb_counter."( date_start_scan ) value ( '".date("Y-m-d H:i:s",mktime())."' )";
        if(mysql_query( $ins, $this->link ))
        {
            $this->counter_scan = mysql_insert_id();//получаем номер текущего сканирования
            $this->ModelStartCreateReport( $this->counter_scan );
        }
        else
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при работе с табл. counter ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        //>>end-----------------------------------------------

        $upd = "update ".$this->tb_toggle." set flag_scan=1";
        if(!mysql_query( $upd, $this->link ))
        {
            if(SHOW_ECHO_ERROR)
            {
                Log::write( "<p> Начало поиска ссылок, таблица ".$this->tb_toggle." НЕ изменена ! ошибка ".mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            }
            exit();
        }

        Log::write( "Начало поиска ссылок.", $this->host_site );
        list($usec, $sec) = explode(" ", microtime());
        $time_start = ((float)$sec + (float)$usec);

        $this->ModelAddPage($url);

        list($usec, $sec) = explode(" ", microtime());
        $time_stop = ((float)$sec + (float)$usec);
        $int = ((int)$time_stop - (int)$time_start);
        $float = ($time_stop - $time_start);
        Log::write( "Конец поиска ссылок.", $this->host_site );
        Log::write( "Время поиска ссылок ".($int/60)."мин. ".$int." сек. ".$float." мсек.", $this->host_site );

        //-----------------------------------------------------
        if(OPERATION_TIMEOUT)
        {
            Log::write( "Начало загрузки страниц (не загруженных по таймауту)", $this->host_site );
            list($usec, $sec) = explode(" ", microtime());
            $time_start = ((float)$sec + (float)$usec);

            $this->ModelPageLoadTimeout();

            list($usec, $sec) = explode(" ", microtime());
            $time_stop = ((float)$sec + (float)$usec);
            $int = ((int)$time_stop - (int)$time_start);
            $float = ($time_stop - $time_start);
            Log::write( "Конец загрузки страниц (не загруженных по таймауту)", $this->host_site );
            Log::write( "Время загрузки страниц (не загруженных по таймауту)".($int/60)."мин. ".$int." сек. ".$float." мсек.", $this->host_site );
        }
        //-----------------------------------------------------

        $upd = "update ".$this->tb_counter." set counter_scan_page=".$this->cur_page.", counter_scan_pages=".$this->count_pages." where id=".$this->counter_scan;
        if(!mysql_query( $upd, $this->link ))
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при работе с табл. counter ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }

        $upd = "update ".$this->tb_toggle." set flag_scan=2, flag_parse=1";
        if(!mysql_query( $upd, $this->link ))
        {
            if(SHOW_ECHO_ERROR)
            {
                Log::write( "<p> Начало работы парсера, таблица ".$this->tb_toggle." НЕ изменена ! ошибка ".mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            }
            exit();
        }

        Log::write( "Начало работы парсера", $this->host_site );
        list($usec, $sec) = explode(" ", microtime());
        $time_start = ((float)$sec + (float)$usec);

        $this->ModelAddPageInfo();

        list($usec, $sec) = explode(" ", microtime());
        $time_stop = ((float)$sec + (float)$usec);
        $int = ((int)$time_stop - (int)$time_start);
        $float = ($time_stop - $time_start);
        Log::write( "Конец работы парсера", $this->host_site );
        Log::write( "Время работы парсера ".($int/60)."мин. ".$int." сек. ".$float." мсек.", $this->host_site );

        //<<----добавляем в табл. counter - дуту окончания анализа сайта (date_stop_scan)
        $upd = "update ".$this->tb_counter." set date_stop_scan='".date("Y-m-d H:i:s",mktime())."' where id=".$this->counter_scan;
        if(!mysql_query( $upd, $this->link ))
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при работе с табл. counter ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        //>>end------------------------------------------------------------

        $upd = "update ".$this->tb_toggle." set flag_parse=2";
        if(!mysql_query( $upd, $this->link ))
        {
            if(SHOW_ECHO_ERROR)
            {
                Log::write( "<p> Конец работы парсера, таблица ".$this->tb_toggle." НЕ изменена ! ошибка ".mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            }
            exit();
        }



        Log::write( "Начало копирования отчета", $this->host_site );
        list($usec, $sec) = explode(" ", microtime());
        $time_start = ((float)$sec + (float)$usec);

        $this->ModelStopCreateReport( $this->counter_scan );

        list($usec, $sec) = explode(" ", microtime());
        $time_stop = ((float)$sec + (float)$usec);
        $int = ((int)$time_stop - (int)$time_start);
        $float = ($time_stop - $time_start);
        Log::write( "Конец копирования отчета", $this->host_site );
        Log::write( "Время копирования отчета".($int/60)."мин. ".$int." сек. ".$float." мсек.", $this->host_site );


        $this->ModelAutomaticGetMoneyInBalance();//возвращает лишние деньги на баланс
        $this->ModelSendReportToEmail();//отправляем отчёт по почте клиенту


        Log::write("***************************STOP**********создаём, анализируем, парсим !!!*****************", $this->host_site);


        //-----------------------------------------------------
        $this->ModelRepeatAnalysis();
        //-----------------------------------------------------


        //<<--------меняем флаг в табл. toggle - сайт проанализирован !!!
        $upd = "update ".$this->tb_toggle." set flag=1";
        if(mysql_query( $upd, $this->link ))
        {
            if(SHOW_ECHO)
            {
                Log::write( "<p>Сайт проанализирован, таблица ".$this->tb_toggle." изменена !</p>", $this->host_site );
            }

            /*Log::write( "Начало копирования отчета", $this->host_site );
            list($usec, $sec) = explode(" ", microtime());
            $time_start = ((float)$sec + (float)$usec);

            $this->ModelStopCreateReport( $this->counter_scan );

            list($usec, $sec) = explode(" ", microtime());
            $time_stop = ((float)$sec + (float)$usec);
            $int = ((int)$time_stop - (int)$time_start);
            $float = ($time_stop - $time_start);
            Log::write( "Конец копирования отчета", $this->host_site );
            Log::write( "Время копирования отчета".($int/60)."мин. ".$int." сек. ".$float." мсек.", $this->host_site );*/

        }
        else
        {
            if(SHOW_ECHO_ERROR)
            {
                Log::write( "<p> Сайт проанализирован, таблица ".$this->tb_toggle." НЕ изменена ! ошибка ".mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            }
            exit();
        }
        //>>end------------------------------------------------------------
    }

    function ModelAddPage( $url )
    {
        //$flag=true;
        $ar_components_link = parse_url($url);

        if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
            $tmp_url = $url;
        }else{
            if(strpos($ar_components_link['path'], '/') === 0){
                $tmp_url = $this->protocol.'://'.$this->host_site.$url;
            }else{
                $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$url;
            }
        }

        $this->html_data = '';

        $this->ModelCurlInit($ch, $tmp_url);

        if(curl_exec($ch))
        {
            $ar = curl_getinfo($ch);
            if(count($ar))
            {
                if( (isset($ar['url']) && parse_url($ar['url'], PHP_URL_HOST) == $this->host_site)
                    && (isset($ar['http_code']) && $ar['http_code'] == 200)
                    && (isset($ar['content_type']) && strstr($ar['content_type'], 'text/html')) )
                {
                    if( $ar['size_download'] == strlen($this->html_data) )
                    {
                        $this->ModelSetCurrentUrl( $tmp_url, $this->html_data );
                        if($this->html->_charset != $this->html->_target_charset)
                            $this->html_data = iconv($this->html->_charset, $this->html->_target_charset, $this->html_data);
                    }

                    //<заносим страницу в БД----------------------------------------------------------------
                    //формируем запрос
                    $ins = "insert into ".$this->tb_pages."(
                    url,
                    page_code,
                    http_code,
                    content_type,
                    connect_time,
                    total_time,
                    page_size,
                    page_speed,
                    level_links,
                    load_count,
                    load_timeout,
                    scan,
                    exist,
                    create_page,
                    date_change_page) values ('"
                        .addslashes(htmlentities($this->current_url , ENT_QUOTES, 'UTF-8' ))
                        ."', '".addslashes(htmlentities($this->html_data , ENT_QUOTES, 'UTF-8' ))
                        ."', ".$ar['http_code']
                        .", '".addslashes(htmlentities($ar['content_type'] , ENT_QUOTES, 'UTF-8' ))
                        ."', ".$ar['connect_time']
                        .", ".$ar['total_time']
                        .", ".$ar['size_download']
                        .", ".$ar['speed_download']
                        .", ".$this->count_level_links
                        .", ".self::$load_count
                        .", ".self::$load_timeout
                        .", ".$this->counter_scan
                        .", 1"
                        .", 1,
                        '".date("Y-m-d H:i:s",mktime())."')";

                    if(mysql_query( $ins, $this->link ))
                    {
                        if(SHOW_ECHO)
                            Log::write( "<p> html-сode Вашей странички занесён в БД !</p>", $this->host_site );
                    }
                    else
                    {
                        if(SHOW_ECHO_ERROR)
                            Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                        exit();
                    }

                    $this->id_last_insert_pages = mysql_insert_id();
                    $this->count_pages = $this->id_last_insert_pages;
                    $this->cur_page = $this->id_last_insert_pages;
                    $this->ar_pages[$this->cur_page] = new Link($this->current_url, $this->count_level_links, '', $this->id_last_insert_pages );
                    //>end------------------------------------------------------------------------------------

                    $this->limiter_pages++;
                    if($this->limiter_pages >= $this->limit_pages)
                    {
                        if(isset($this->html))
                        {
                            if(is_object($this->html))
                            {
                                $this->html->clear();
                            }
                            unset($this->html);
                        }
                        return;
                    }

                    //<работаем над поиском <a>---------------------------------------------------------------
                    $ar_internal_links = array();
                    $ar_lines_links    = array();
                    $ar_external_links = array();

                    if($this->id_last_insert_pages)
                    {
                        if($this->html->innertext != '' && count($this->html->find('a')))
                        {
                            $ar_links_current_page    = $this->html->find('a');
                            $count_links_current_page = count( $ar_links_current_page );

                            if( $count_links_current_page > 0)
                            {
                                $count = 0;

                                foreach($ar_links_current_page as $link)
                                {
                                    if(!$link->href)continue;

                                    $ar_components_link = parse_url( $link->href );
                                    if( isset($ar_components_link['host']) && $ar_components_link['host'] == $this->host_site)
                                    {//если в url есть хост и он == хосту сайта
                                        if( !in_array($link->href, $ar_internal_links) )
                                        {
                                            $ar_internal_links[] = $link->href;
                                        }
                                        //internal_link == 1 - внутренняя ссылка по которой будет производится переход
                                        $ins = "insert into ".$this->tb_links."( page_id, internal_link, href, anchor ) values (".$this->id_last_insert_pages.", 1, '".addslashes(htmlentities($link->href , ENT_QUOTES, 'UTF-8' ))."', '".addslashes(htmlentities($link->plaintext , ENT_QUOTES, 'UTF-8' ))."')";
                                    }
                                    else if( !isset($ar_components_link['host']) && isset($ar_components_link['path']))
                                    {//если в url нет хоста, но есть /path сайта
                                        if( ( (strpos($link->href, 'javascript:')!==0) && (strpos($link->href, 'Javascript:')!==0) && (strpos($link->href, 'mailto:')!==0) ) && !in_array($link->href, $ar_internal_links))
                                        {
                                            $ar_internal_links[] = $link->href;
                                        }
                                        //internal_link == 1 - внутренняя ссылка (/path) по которой будет производится переход
                                        $ins = "insert into ".$this->tb_links."( page_id, internal_link, href, anchor ) values (".$this->id_last_insert_pages.", 1, '".addslashes(htmlentities($link->href , ENT_QUOTES, 'UTF-8' ))."', '".addslashes(htmlentities($link->plaintext , ENT_QUOTES, 'UTF-8' ))."')";
                                    }
                                    else if( !isset($ar_components_link['host']) && isset($ar_components_link['fragment']))
                                    {//если в url нет хоста, но есть #fragment сайта
                                        //internal_link == 1 - внутренняя ссылка (#top) по которой не будет производится переход
                                        $ins = "insert into ".$this->tb_links."( page_id, internal_link, href, anchor ) values (".$this->id_last_insert_pages.", 1, '".addslashes(htmlentities($link->href , ENT_QUOTES, 'UTF-8' ))."', '".addslashes(htmlentities($link->plaintext , ENT_QUOTES, 'UTF-8' ))."')";
                                    }
                                    else if( !isset($ar_components_link['host']) && !isset($ar_components_link['fragment']) && $link->href == '#')
                                    {//если в url нет хоста, но есть #fragment сайта
                                        //internal_link == 1 - внутренняя ссылка (#) по которой не будет производится переход
                                        $ins = "insert into ".$this->tb_links."( page_id, internal_link, href, anchor ) values (".$this->id_last_insert_pages.", 1, '".addslashes(htmlentities($link->href , ENT_QUOTES, 'UTF-8' ))."', '".addslashes(htmlentities($link->plaintext , ENT_QUOTES, 'UTF-8' ))."')";
                                        //$ar_external_links[] =  $link->href;
                                    }
                                    else
                                    {
                                        //internal_link == 0 - внешняя ссылка по которой не будет производится переход
                                        $ins = "insert into ".$this->tb_links."( page_id, internal_link, href, anchor ) values (".$this->id_last_insert_pages.", 0, '".addslashes(htmlentities($link->href , ENT_QUOTES, 'UTF-8' ))."', '".addslashes(htmlentities($link->plaintext , ENT_QUOTES, 'UTF-8' ))."')";
                                        $ar_external_links[] =  $link->href;
                                    }

                                    if(mysql_query( $ins, $this->link ))
                                    {
                                        ++$count;
                                        if(SHOW_ECHO)
                                            Log::write( "<p> links-".$count." Вашей странички занесён в БД !</p>", $this->host_site );
                                    }
                                    else
                                    {
                                        if(SHOW_ECHO_ERROR)
                                            Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                        exit();
                                    }
                                }//end foreach

                                //если производились записи в таблицу tb_links обновляем таблицу tb_pages
                                if($count>0)
                                {
                                    $upd = "update ".$this->tb_pages." set links=".$this->id_last_insert_pages." where id=".$this->id_last_insert_pages;
                                    if(mysql_query( $upd, $this->link ))
                                    {
                                        if(SHOW_ECHO)
                                            Log::write( "<p> Ваша страничка №".$this->id_last_insert_pages." изменена в БД !</p>", $this->host_site );
                                    }
                                    else
                                    {
                                        if(SHOW_ECHO_ERROR)
                                            Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                        exit();
                                    }
                                }
                            }
                        }
                    }
                    //>>end-----------------------------------------------------------------------------------
                    //<получаем ссылки со страницы-----------------------------------------------
                    if( count( $ar_internal_links ) )
                    {
                        $ar_url_page = array();//чистим
                        foreach($this->ar_pages as $p)
                        {
                            $ar_url_page[] = $p->url;
                        }

                        $ar_lines_links = array();
                        foreach($ar_internal_links as $href)
                        {
                            if( !in_array($href, $ar_url_page) )
                            {
                                $ar_lines_links[] = $href;
                            }
                        }
                        $ar_url_page = array();//чистим
                    }
                    //>end--------------------------------------------------------------------

                    //<добавляем новые ссылки в таблицу pages---------------------------------
                    if( count($ar_lines_links) )
                    {
                        $this->count_level_links++;//увеличиваем уровень вложенности ссылок

                        foreach( $ar_lines_links as $link )
                        {
                            /*$ins = "insert into ".$this->tb_pages."(
                            url,
                            parent_url,
                            level_links,
                            scan,
                            exist,
                            create_page,
                            date_change_page ) values ('"
                                .htmlentities($link , ENT_QUOTES, 'UTF-8' )
                                ."', '".htmlentities($url , ENT_QUOTES, 'UTF-8' )
                                ."', ".$this->count_level_links
                                .", ".$this->counter_scan.",
                                1,
                                1,
                                '".date("Y-m-d H:i:s",mktime())."')";

                            if(!mysql_select_db($this->db_name, $this->link))
                            {
                                if(SHOW_ECHO_ERROR)
                                    Log::write( "<p> Ошибка подключения к БД ".mysql_errno()."</p>", $this->host_site );
                                exit();
                            }
                            if(mysql_query( $ins, $this->link ))
                            {
                                if(SHOW_ECHO)
                                    Log::write( "<p> url Вашей странички занесён в БД !</p>", $this->host_site );
                            }
                            else
                            {
                                if(SHOW_ECHO_ERROR)
                                    Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                exit();
                            }*/
                            $this->ar_pages[++$this->count_pages] = new Link($link, $this->count_level_links, $url);
                        }
                    }
                    //>end--------------------------------------------------------------------

                    if($this->count_pages > $this->cur_page)
                    {
                        if(isset($this->html))
                        {
                            if(is_object($this->html))
                            {
                                $this->html->clear();
                            }
                            unset($this->html);
                        }

                        for( true; $this->cur_page < $this->count_pages; true )
                        {
                            if($this->limiter_pages < $this->limit_pages)
                            {
                                $this->ModelAddPageRepeat(++$this->cur_page);
                                //<<-----сохраняем колчество обработанных страниц - для отчёта клиенту-----
                                if( ($this->cur_page % 10) == 0 || ($this->cur_page % 10) == 5 )
                                {
                                    $upd = "update ".$this->tb_counter." set counter_scan_page=".$this->cur_page.", counter_scan_pages=".$this->count_pages." where id=".$this->counter_scan;
                                    if(!mysql_query( $upd, $this->link ))
                                    {
                                        if(SHOW_ECHO_ERROR)
                                            Log::write( "<p> ошибка ".mysql_errno()." при работе с табл. counter ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                        exit();
                                    }
                                }
                                //>>end------------------------------------------------------
                            }
                            else
                            {
                                break;
                            }
                        }
                    }
                }
                else
                {
                    //формируем запрос
                    $ins = "insert into ".$this->tb_pages."(
                    url,
                    http_code,
                    content_type,
                    redirect_url,
                    level_links,
                    connect_time,
                    total_time,
                    page_size,
                    page_speed,
                    load_count,
                    load_timeout,
                    scan,
                    exist,
                    create_page,
                    date_change_page ) values ('"
                        .addslashes(htmlentities( $url, ENT_QUOTES, 'UTF-8' ))
                        ."', ".$ar['http_code']
                        .", '".addslashes(htmlentities($ar['content_type'] , ENT_QUOTES, 'UTF-8' ))
                        ."', ".($ar['redirect_url']?("'".addslashes(htmlentities( $ar['redirect_url'], ENT_QUOTES, 'UTF-8' ))."'"):'NULL')
                        .", ".$this->count_level_links
                        .", ".curl_getinfo($ch, CURLINFO_CONNECT_TIME )
                        .", ".curl_getinfo($ch, CURLINFO_TOTAL_TIME )
                        .", ".($ar['size_download']?$ar['size_download']:'NULL')
                        .", ".($ar['speed_download']?$ar['speed_download']:'NULL')
                        .", ".self::$load_count
                        .", ".self::$load_timeout
                        .", ".$this->counter_scan
                        .", 1,
                        1,
                        '".date("Y-m-d H:i:s",mktime())."')";

                    if(mysql_query( $ins, $this->link ))
                    {
                        if(SHOW_ECHO)
                            Log::write( "<p> html-сode Вашей странички занесён в БД !</p>", $this->host_site );
                    }
                    else
                    {
                        if(SHOW_ECHO_ERROR)
                            Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                        Log::write( $ins, $this->host_site );
                        exit();
                    }

                    $this->id_last_insert_pages = mysql_insert_id();
                    $this->count_pages = $this->id_last_insert_pages;
                    $this->cur_page = $this->id_last_insert_pages;
                    $this->ar_pages[$this->cur_page] = new Link($url, $this->count_level_links, '', $this->id_last_insert_pages);

                    if(isset($this->html))
                    {
                        if(is_object($this->html))
                        {
                            $this->html->clear();
                        }
                        unset($this->html);
                    }
                }
            }
        }
        else
        {
            $ins = "insert into ".$this->tb_pages."(
                url,
                http_code,
                content_type,
                level_links,
                connect_time,
                total_time,
                page_size,
                page_speed,
                load_count,
                load_timeout,
                scan,
                exist,
                create_page,
                date_change_page ) values ('"
                .addslashes(htmlentities($url , ENT_QUOTES, 'UTF-8' ))
                ."', ".curl_errno($ch)
                .", '".addslashes(htmlentities(curl_error($ch) , ENT_QUOTES, 'UTF-8' ))
                ."', ".$this->count_level_links
                .", ".curl_getinfo($ch, CURLINFO_CONNECT_TIME )
                .", ".curl_getinfo($ch, CURLINFO_TOTAL_TIME )
                .", ".(curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD )?curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD ):'NULL')
                .", ".(curl_getinfo($ch, CURLINFO_SPEED_DOWNLOAD )?curl_getinfo($ch, CURLINFO_SPEED_DOWNLOAD ):'NULL')
                .", ".self::$load_count
                .", ".self::$load_timeout
                .", ".$this->counter_scan.",
                1,
                1,
                '".date("Y-m-d H:i:s",mktime())."')";

            /*if(!mysql_select_db($this->db_name, $this->link))
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> Ошибка подключения к БД ".mysql_errno()."</p>", $this->host_site );
                exit();
            }*/
            if(mysql_query( $ins, $this->link ))
            {
                if(SHOW_ECHO)
                    Log::write( "<p>ошибки загрузки Вашей странички занесены в БД !</p>", $this->host_site );

                $this->id_last_insert_pages = mysql_insert_id();
                $this->count_pages = $this->id_last_insert_pages;
                $this->cur_page = $this->id_last_insert_pages;
                $this->ar_pages[$this->cur_page] = new Link($url, $this->count_level_links, '', $this->id_last_insert_pages );
            }
            else
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
        }
        curl_close($ch);
        unset($ch);
        unset($ar);
        //return $flag;
    }

    function ModelAddPageRepeat( $num )
    {
        //<заносим страницу в БД----------------------------------------------------------------
        $ar_components_link = parse_url( $this->ar_pages[$num]->url );

        if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
            $tmp_url = $this->ar_pages[$num]->url;
        }else{
            if(strpos($ar_components_link['path'], '/') === 0){
                $tmp_url = $this->protocol.'://'.$this->host_site.$this->ar_pages[$num]->url;
            }else{
                $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$this->ar_pages[$num]->url;
            }
        }

        $tmp_level_links = $this->ar_pages[$num]->level;
        //$parent_url = $this->ar_pages[$num]->url;
        //$tmp_id = $this->ar_pages[$num]->id;

        $this->html_data = '';

        $this->ModelCurlInit($ch, $tmp_url);

        if(curl_exec($ch))
        {
            $ar = curl_getinfo($ch);
            if(count($ar))
            {
                if( (isset($ar['url']) && parse_url($ar['url'], PHP_URL_HOST) == $this->host_site)
                    && (isset($ar['http_code']) && $ar['http_code'] == 200)
                    && (isset($ar['content_type']) && strstr($ar['content_type'], 'text/html')) )
                {
                    //страницу загружаем делаем все что надо, т.к. это файл HTML
                    if( $ar['size_download'] == strlen($this->html_data) )
                    {
                        $this->ModelSetCurrentUrl( $tmp_url, $this->html_data );
                        if($this->html->_charset != $this->html->_target_charset)
                            $this->html_data = iconv($this->html->_charset, $this->html->_target_charset, $this->html_data);
                    }

                    /*//формируем запрос
                    $upd = "update ".$this->tb_pages
                        ." set page_code='".htmlentities($this->html_data , ENT_QUOTES, 'UTF-8' )
                        ."', http_code=".$ar['http_code']
                        .", content_type='".htmlentities($ar['content_type'] , ENT_QUOTES, 'UTF-8' )
                        ."', total_time=".$ar['total_time']
                        .", page_size=".$ar['size_download']
                        .", page_speed=".$ar['speed_download']
                        .", load_count=".self::$load_count
                        .", load_timeout=".self::$load_timeout
                        ." where id=".$tmp_id;

                    if(!mysql_select_db($this->db_name, $this->link))
                    {
                        if(SHOW_ECHO_ERROR_CONNECT_DB)
                            Log::write( "<p> Ошибка подключения к БД ".mysql_errno()."</p>", $this->host_site );
                        exit();
                    }
                    if(mysql_query( $upd, $this->link ))
                    {
                        if(SHOW_ECHO)
                            Log::write( "<p> Ваша страничка №".$tmp_id." изменена в БД !</p>", $this->host_site );
                    }
                    else
                    {
                        if(SHOW_ECHO_ERROR)
                            Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                        exit();
                    }*/

                    //формируем запрос
                    $ins = "insert into ".$this->tb_pages."(
                    url,
                    page_code,
                    http_code,
                    content_type,
                    parent_url,
                    connect_time,
                    total_time,
                    page_size,
                    page_speed,
                    level_links,
                    load_count,
                    load_timeout,
                    scan,
                    exist,
                    create_page,
                    date_change_page) values ('"
                        .addslashes(htmlentities($this->ar_pages[$num]->url , ENT_QUOTES, 'UTF-8' ))
                        ."', '".addslashes(htmlentities($this->html_data , ENT_QUOTES, 'UTF-8' ))
                        ."', ".$ar['http_code']
                        .", '".addslashes(htmlentities($ar['content_type'] , ENT_QUOTES, 'UTF-8' ))
                        ."', '".addslashes(htmlentities($this->ar_pages[$num]->parent_url , ENT_QUOTES, 'UTF-8' ))
                        ."', ".$ar['connect_time']
                        .", ".$ar['total_time']
                        .", ".$ar['size_download']
                        .", ".$ar['speed_download']
                        .", ".$this->ar_pages[$num]->level
                        .", ".self::$load_count
                        .", ".self::$load_timeout
                        .", ".$this->counter_scan
                        .", 1"
                        .", 1,
                        '".date("Y-m-d H:i:s",mktime())."')";

                    /*if(!mysql_select_db($this->db_name, $this->link))
                    {
                        if(SHOW_ECHO_ERROR)
                            Log::write( "<p> Ошибка подключения к БД ".mysql_errno()."</p>", $this->host_site );
                        exit();
                    }*/
                    if(mysql_query( $ins, $this->link ))
                    {
                        $this->ar_pages[$num]->id = mysql_insert_id();

                        if(SHOW_ECHO)
                            Log::write( "<p> html-сode Вашей странички занесён в БД !</p>", $this->host_site );
                    }
                    else
                    {
                        if(SHOW_ECHO_ERROR)
                            Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                        exit();
                    }
                    //>end------------------------------------------------------------------------------------

                    //<работаем над поиском <a>---------------------------------------------------------------
                    $ar_internal_links = array();
                    $ar_lines_links    = array();

                    if($this->html)
                    {
                        if($this->html->innertext != '' && count($this->html->find('a')))
                        {
                            $ar_links_current_page    = $this->html->find('a');
                            $count_links_current_page = count( $ar_links_current_page );

                            if( $count_links_current_page > 0)
                            {
                                $count = 0;
                                $tmp_id = $this->ar_pages[$num]->id;

                                $ar_internal_links = array();
                                foreach($ar_links_current_page as $link)
                                {
                                    if(!$link->href)continue;

                                    $ar_components_link = parse_url( $link->href );
                                    if( isset($ar_components_link['host']) && $ar_components_link['host'] == $this->host_site )
                                    {//если в url есть хост и он == хосту сайта
                                        if( !in_array($link->href, $ar_internal_links) )
                                        {
                                            $ar_internal_links[] = $link->href;
                                        }
                                        //internal_link == 1 - внутренняя ссылка по которой будет производится переход
                                        $ins = "insert into ".$this->tb_links."( page_id, internal_link, href, anchor ) values (".$tmp_id.", 1, '".addslashes(htmlentities($link->href , ENT_QUOTES, 'UTF-8' ))."', '".addslashes(htmlentities($link->plaintext , ENT_QUOTES, 'UTF-8' ))."')";
                                    }
                                    else if( !isset($ar_components_link['host']) && isset($ar_components_link['path']))
                                    {//если в url нет хоста, но есть /path сайта
                                        if( ( (strpos($link->href, 'javascript:')!==0) && (strpos($link->href, 'Javascript:')!==0) && (strpos($link->href, 'mailto:')!==0) ) && !in_array($link->href, $ar_internal_links))
                                        {
                                            $ar_internal_links[] = $link->href;
                                        }
                                        //internal_link == 1 - внутренняя ссылка (/path) по которой будет производится переход
                                        $ins = "insert into ".$this->tb_links."( page_id, internal_link, href, anchor ) values (".$tmp_id.", 1, '".addslashes(htmlentities($link->href , ENT_QUOTES, 'UTF-8' ))."', '".addslashes(htmlentities($link->plaintext , ENT_QUOTES, 'UTF-8' ))."')";
                                    }
                                    else if( !isset($ar_components_link['host']) && isset($ar_components_link['fragment']))
                                    {//если в url нет хоста, но есть #fragment сайта
                                        //internal_link == 1 - внутренняя ссылка (#top) по которой не будет производится переход
                                        $ins = "insert into ".$this->tb_links."( page_id, internal_link, href, anchor ) values (".$tmp_id.", 1, '".addslashes(htmlentities($link->href , ENT_QUOTES, 'UTF-8' ))."', '".addslashes(htmlentities($link->plaintext , ENT_QUOTES, 'UTF-8' ))."')";
                                    }
                                    else if( !isset($ar_components_link['host']) && !isset($ar_components_link['fragment']) && $link->href == '#')
                                    {//если в url нет хоста, но есть #fragment сайта
                                        //internal_link == 1 - внутренняя ссылка (#) по которой не будет производится переход
                                        $ins = "insert into ".$this->tb_links."( page_id, internal_link, href, anchor ) values (".$tmp_id.", 1, '".addslashes(htmlentities($link->href , ENT_QUOTES, 'UTF-8' ))."', '".addslashes(htmlentities($link->plaintext , ENT_QUOTES, 'UTF-8' ))."')";
                                    }
                                    else
                                    {
                                        //internal_link == 0 - внешняя ссылка по которой не будет производится переход
                                        $ins = "insert into ".$this->tb_links."( page_id, internal_link, href, anchor ) values (".$tmp_id.", 0, '".addslashes(htmlentities($link->href , ENT_QUOTES, 'UTF-8' ))."', '".addslashes(htmlentities($link->plaintext , ENT_QUOTES, 'UTF-8' ))."')";
                                    }

                                    if(mysql_query( $ins, $this->link ))
                                    {
                                        ++$count;
                                        if(SHOW_ECHO)
                                            Log::write( "<p> links-".$count." Вашей странички занесён в БД !</p>", $this->host_site );
                                    }
                                    else
                                    {
                                        if(SHOW_ECHO_ERROR)
                                            Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                        Log::write( $ins, $this->host_site );
                                        exit();
                                    }
                                }//end foreach

                                //если производились записи в таблицу tb_links обновляем таблицу tb_pages
                                if($count>0)
                                {
                                    $upd = "update ".$this->tb_pages." set links=".$tmp_id." where id=".$tmp_id;
                                    if(mysql_query( $upd, $this->link ))
                                    {
                                        if(SHOW_ECHO)
                                            Log::write( "<p> Ваша страничка №".$tmp_id." изменена в БД !</p>", $this->host_site );
                                    }
                                    else
                                    {
                                        if(SHOW_ECHO_ERROR)
                                            Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                        exit();
                                    }
                                }
                            }
                        }
                    }
                    //>>end-----------------------------------------------------------------------------------

                    $this->limiter_pages++;
                    if($this->limiter_pages < $this->limit_pages)
                    {
                        //<получаем ссылки со страницы-----------------------------------------------
                        if( count( $ar_internal_links ) )
                        {
                            $ar_url_page = array();//чистим
                            foreach($this->ar_pages as $p)
                            {
                                $ar_url_page[] = $p->url;
                            }

                            $ar_lines_links = array();
                            foreach($ar_internal_links as $href)
                            {
                                if( !in_array($href, $ar_url_page) )
                                {
                                    $ar_lines_links[] = $href;
                                }
                            }
                            $ar_url_page = array();//чистим
                        }
                        //>end--------------------------------------------------------------------

                        //<добавляем новые ссылки в таблицу pages---------------------------------
                        if( count($ar_lines_links) )
                        {
                            $tmp_level_links++;

                            foreach( $ar_lines_links as $link )
                            {
                                /*//формируем запрос
                                $ins = "insert into ".$this->tb_pages."(
                            url,
                            parent_url,
                            level_links,
                            scan,
                            exist,
                            create_page,
                            date_change_page ) values ('"
                                    .htmlentities($link , ENT_QUOTES, 'UTF-8' )
                                    ."', '".htmlentities($parent_url , ENT_QUOTES, 'UTF-8' )
                                    ."', ".$tmp_level_links
                                    .", ".$this->counter_scan
                                    .", 1,
                                1,
                                '".date("Y-m-d H:i:s",mktime())."')";

                                if(!mysql_select_db($this->db_name, $this->link))
                                {
                                    if(SHOW_ECHO_ERROR_CONNECT_DB)
                                        Log::write( "<p> Ошибка подключения к БД ".mysql_errno()."</p>", $this->host_site );
                                    exit();
                                }
                                if(mysql_query( $ins, $this->link ))
                                {
                                    if(SHOW_ECHO)
                                        Log::write( "<p> html-сode Вашей странички занесён в БД !</p>", $this->host_site );
                                }
                                else
                                {
                                    if(SHOW_ECHO_ERROR)
                                        Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                    exit();
                                }*/
                                $this->ar_pages[++$this->count_pages] = new Link($link, $tmp_level_links, $this->ar_pages[$num]->url);
                            }
                        }
                        //>end--------------------------------------------------------------------
                    }

                    if(isset($this->html))
                    {
                        if(is_object($this->html))
                        {
                            $this->html->clear();
                        }
                        unset($this->html);
                    }
                }
                else
                {
                    //записываем в БД URL | http_code | content_type - больше ничего не делаем, начего незагружаем, т.к. это файл не HTML
                    //.jpg .png .pdf и т.д.

                    //записываем в БД URL | http_code | content_type - больше ничего не делаем, т.к. это может быть всё что угодно !
                    // 'http://iportal.com.ua/javascript:("zzz");'  'http://iportal.com.ua/mailto:info@iportal.com.ua' и т.д.

                    //записываем в БД redirect_url - т.к. это может быть редирект !!!

                    /*//формируем запрос
                    $upd = "update ".$this->tb_pages
                        ." set page_code='',
                        http_code=".$ar['http_code']
                        .", content_type='".htmlentities($ar['content_type'] , ENT_QUOTES, 'UTF-8' )
                        ."', redirect_url=".($ar['redirect_url']?("'".htmlentities( $ar['redirect_url'], ENT_QUOTES, 'UTF-8' )."'"):'NULL')
                        .", load_count=".self::$load_count
                        .", load_timeout=".self::$load_timeout
                        .", total_time=".curl_getinfo($ch, CURLINFO_TOTAL_TIME )
                        ." where id=".$tmp_id;

                    if(!mysql_select_db($this->db_name, $this->link))
                    {
                        if(SHOW_ECHO_ERROR_CONNECT_DB)
                            Log::write( "<p> Ошибка подключения к БД ".mysql_errno()."</p>", $this->host_site );
                        exit();
                    }
                    if(mysql_query( $upd, $this->link ))
                    {
                        if(SHOW_ECHO)
                            Log::write( "<p> Ваша страничка №".$tmp_id." изменена в БД !</p>", $this->host_site );
                    }
                    else
                    {
                        if(SHOW_ECHO_ERROR)
                            Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                        exit();
                    }*/

                    //формируем запрос
                    $ins = "insert into ".$this->tb_pages."(
                    url,
                    page_code,
                    http_code,
                    content_type,
                    redirect_url,
                    parent_url,
                    connect_time,
                    total_time,
                    page_size,
                    page_speed,
                    level_links,
                    load_count,
                    load_timeout,
                    scan,
                    exist,
                    create_page,
                    date_change_page) values ('"
                        .addslashes(htmlentities($this->ar_pages[$num]->url , ENT_QUOTES, 'UTF-8' ))
                        ."', '".""
                        ."', ".$ar['http_code']
                        .", '".addslashes(htmlentities($ar['content_type'] , ENT_QUOTES, 'UTF-8' ))
                        ."', ".($ar['redirect_url']?("'".addslashes(htmlentities( $ar['redirect_url'], ENT_QUOTES, 'UTF-8' ))."'"):'NULL')
                        .", '".addslashes(htmlentities($this->ar_pages[$num]->parent_url , ENT_QUOTES, 'UTF-8' ))
                        ."', ".curl_getinfo($ch, CURLINFO_CONNECT_TIME )
                        .", ".curl_getinfo($ch, CURLINFO_TOTAL_TIME )
                        .", ".($ar['size_download']?$ar['size_download']:'NULL')
                        .", ".($ar['speed_download']?$ar['speed_download']:'NULL')
                        .", ".$this->ar_pages[$num]->level
                        .", ".self::$load_count
                        .", ".self::$load_timeout
                        .", ".$this->counter_scan
                        .", 1"
                        .", 1,
                        '".date("Y-m-d H:i:s",mktime())."')";

                    /*if(!mysql_select_db($this->db_name, $this->link))
                    {
                        if(SHOW_ECHO_ERROR)
                            Log::write( "<p> Ошибка подключения к БД ".mysql_errno()."</p>", $this->host_site );
                        exit();
                    }*/
                    if(mysql_query( $ins, $this->link ))
                    {
                        $this->ar_pages[$num]->id = mysql_insert_id();

                        if(SHOW_ECHO)
                            Log::write( "<p> html-сode Вашей странички занесён в БД !</p>", $this->host_site );
                    }
                    else
                    {
                        if(SHOW_ECHO_ERROR)
                            Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                        exit();
                    }
                }
            }
        }
        else
        {
            /*$upd = "update ".$this->tb_pages
                ." set page_code='', http_code=".curl_errno($ch)
                .", content_type='".htmlentities(curl_error($ch) , ENT_QUOTES, 'UTF-8' )
                ."', load_count=".self::$load_count
                .", load_timeout=".self::$load_timeout
                .", total_time=".curl_getinfo($ch, CURLINFO_TOTAL_TIME )
                ." where id=".$tmp_id;
            if(mysql_query( $upd, $this->link ))
            {
                if(SHOW_ECHO)
                    Log::write( "<p>ошибки загрузки Вашей странички занесены в БД !</p>", $this->host_site );
            }
            else
            {
                if(SHOW_ECHO_ERROR)
                {
                    Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    Log::write( "<p>".$upd."</p>", $this->host_site );
                }
                exit();
            }*/

            //формируем запрос
            $ins = "insert into ".$this->tb_pages."(
                url,
                page_code,
                http_code,
                content_type,
                parent_url,
                connect_time,
                total_time,
                page_size,
                page_speed,
                level_links,
                load_count,
                load_timeout,
                scan,
                exist,
                create_page,
                date_change_page) values ('"
                .addslashes(htmlentities($this->ar_pages[$num]->url , ENT_QUOTES, 'UTF-8' ))
                ."', '".""
                ."', ".curl_errno($ch)
                .", '".addslashes(htmlentities(curl_error($ch) , ENT_QUOTES, 'UTF-8' ))
                ."', '".addslashes(htmlentities($this->ar_pages[$num]->parent_url , ENT_QUOTES, 'UTF-8' ))
                ."', ".curl_getinfo($ch, CURLINFO_CONNECT_TIME )
                .", ".curl_getinfo($ch, CURLINFO_TOTAL_TIME )
                .", ".(curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD )?curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD ):'NULL')
                .", ".(curl_getinfo($ch, CURLINFO_SPEED_DOWNLOAD )?curl_getinfo($ch, CURLINFO_SPEED_DOWNLOAD ):'NULL')
                .", ".$this->ar_pages[$num]->level
                .", ".self::$load_count
                .", ".self::$load_timeout
                .", ".$this->counter_scan
                .", 1"
                .", 1,
                '".date("Y-m-d H:i:s",mktime())."')";

            if(mysql_query( $ins, $this->link ))
            {
                $this->ar_pages[$num]->id = mysql_insert_id();

                if(SHOW_ECHO)
                    Log::write( "<p>ошибки загрузки Вашей странички занесены в БД !</p>", $this->host_site );
            }
            else
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
        }
        curl_close($ch);
        unset($ch);
        unset($ar);
    }

    function ModelAddPageInfo()
    {
        $sel = "SELECT id FROM ".$this->tb_pages." WHERE http_code=200 AND content_type LIKE '%text/html%'";
        $res = mysql_query($sel,$this->link);

        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        else
        {
            $ar_id_page = array();//чистим
            while ($page = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $ar_id_page[] = intval($page['id']);
            }

            $upd = "update ".$this->tb_counter." set counter_parse_page=0, counter_parse_pages=".count($ar_id_page)." where id=".$this->counter_scan;
            if(!mysql_query( $upd, $this->link ))
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при работе с табл. counter ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }

            $tmp_count = 0;//временный счётчик

            foreach( $ar_id_page as $id )
            {
                $sel = "SELECT url, page_code FROM ".$this->tb_pages." WHERE id=".$id;
                $res = mysql_query($sel,$this->link);

                if(mysql_errno())
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
                else
                {
                    $page_url  = '';
                    $page_code = '';
                    while ($line = mysql_fetch_array($res, MYSQL_ASSOC))
                    {
                        $page_url  = html_entity_decode( $line['url'], ENT_QUOTES, 'UTF-8' );
                        $page_code = html_entity_decode( $line['page_code'], ENT_QUOTES, 'UTF-8' );
                    }

                    if($page_code)
                    {
                        $this->html = str_get_html($page_code);

                        $this->id_current_page = $id;

                        $this->ModelAddTitleToPage();  //<<получаем title из html и его в БД
                        $this->ModelAddMetaToPage();   //<<получаем мета-теги из html и заносим их в БД
                        $this->ModelAddTitleKeywordsDescription();//<<заносим в БД Title | Keywords | Description
                        $this->ModelAddImgToPage();    //<<получаем img из html и их в БД
                        $this->ModelAddContentToPage();
                        $this->ModelAddTagsToPage( $this->id_current_page );
                        if($this->user_login == parent::$login && $this->user_password == parent::$password)
                        {
                            $this->ModelAddEmailToPage( $this->id_current_page, $page_url, $page_code );
                        }

                        if(isset($this->html))
                        {
                            if(is_object($this->html))
                            {
                                $this->html->clear();
                            }
                            unset($this->html);
                        }

                        $this->id_current_page = 0;

                        $tmp_count++;
                        if( ($tmp_count % 10) == 0 || ($tmp_count % 10) == 5 )
                        {
                            $upd = "update ".$this->tb_counter." set counter_parse_page=".$tmp_count." where id=".$this->counter_scan;
                            if(!mysql_query( $upd, $this->link ))
                            {
                                if(SHOW_ECHO_ERROR)
                                    Log::write( "<p> ошибка ".mysql_errno()." при работе с табл. counter ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                exit();
                            }
                        }
                    }
                }
            }//end foreach
            $upd = "update ".$this->tb_counter." set counter_parse_page=".$tmp_count." where id=".$this->counter_scan;
            if(!mysql_query( $upd, $this->link ))
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при работе с табл. counter ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
        }
    }

    function ModelAddTitleToPage()
    {
        $this->id_title = 0;
        if(!$this->html) return;

        //<<получаем title из html и его в БД-----------------------

        //работаем над поиском <title>
        if($this->html->innertext != '' && count($this->html->find('title')))
        {
            $title = $this->html->find('title');
            $title_text = $title[0]->plaintext;
            if($title_text)
            {
                $count_title_symbols = mb_strlen($title_text, 'UTF-8');
                $ar_title_words      = explode(' ', $title_text);
                $count_title_words   = count( $ar_title_words );
                //формируем запрос
                $ins = "insert into ".$this->tb_title."( info, count_words, count_symbols ) values ('".addslashes(htmlentities($title_text , ENT_QUOTES, 'UTF-8' ))."', ".$count_title_words.", ".$count_title_symbols.")";

                if(mysql_query( $ins, $this->link ))
                {
                    if(SHOW_ECHO)
                        Log::write( "<p> title занесён в БД !</p>", $this->host_site );
                }
                else
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }

                $this->id_title = mysql_insert_id();
            }
        }
        else if( $this->html->innertext != '' && count($this->html->find('meta')) )
        {
            $title_text = '';
            foreach($this->html->find('meta') as $meta)
            {
                if( isset($meta->name) && $meta->name == 'title' )
                {
                    $title_text = $meta->content;
                }
            }

            if($title_text)
            {
                $count_title_symbols = mb_strlen($title_text, 'UTF-8');
                $ar_title_words      = explode(' ', $title_text);
                $count_title_words   = count( $ar_title_words );
                //формируем запрос
                $ins = "insert into ".$this->tb_title."( info, count_words, count_symbols ) values ('".addslashes(htmlentities($title_text , ENT_QUOTES, 'UTF-8' ))."', ".$count_title_words.", ".$count_title_symbols.")";

                if(mysql_query( $ins, $this->link ))
                {
                    if(SHOW_ECHO)
                        Log::write( "<p> title занесён в БД !</p>", $this->host_site );
                }
                else
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }

                $this->id_title = mysql_insert_id();
            }
        }
        //>>end----------------------------------------------------
    }

    function ModelAddMetaToPage()
    {
        $this->id_keywords    = 0;
        $this->id_description = 0;
        if(!$this->html) return;

        //<<получаем мета-теги из html и заносим их в БД-----------

        if( $this->html->innertext != '' && count($this->html->find('meta')) )
        {
            foreach($this->html->find('meta') as $meta)
            {
                if( (isset($meta->name) && $meta->name == 'keywords') || (isset($meta->name) && $meta->name == 'Keywords') )
                {
                    $tags[strtolower($meta->name)]= $meta->content;
                }
                else if( (isset($meta->name) && $meta->name == 'description') || (isset($meta->name) && $meta->name == 'Description'))
                {
                    $tags[strtolower($meta->name)]= $meta->content;
                }
            }
        }

        //формируем таблицу keywords
        if(isset($tags['keywords']))
        {
            $keywords = $tags['keywords'];
            $count_keywords_symbols = mb_strlen( $keywords, 'UTF-8' );
            if( $count_keywords_symbols )
            {
                //$keywords_not_space     = str_replace( ' ', '', $keywords);
                //$ar_keywords            = explode( ',', $keywords_not_space );
                $ar_keywords            = explode( ',', $keywords );//считаем 1-keywords == слово или словосочитание между запятыми
                $count_keywords_word    = count( $ar_keywords );
                if( $count_keywords_word )
                {
                    //формируем запрос
                    $ins = "insert into ".$this->tb_keywords."( info, count_words, count_symbols ) values ('".addslashes(htmlentities($tags['keywords'] , ENT_QUOTES, 'UTF-8' ))."', ".$count_keywords_word.", ".$count_keywords_symbols.")";

                    if(mysql_query( $ins, $this->link ))
                    {
                        if(SHOW_ECHO)
                            Log::write( "<p> keywords занесён в БД !</p>", $this->host_site );
                    }
                    else
                    {
                        if(SHOW_ECHO_ERROR)
                            Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                        exit();
                    }

                    $this->id_keywords = mysql_insert_id();
                }
            }
        }

        //формируем таблицу description
        if(isset($tags['description']))
        {
            $description = $tags['description'];
            $count_description_symbols = mb_strlen( $description, 'UTF-8' );
            if( $count_description_symbols )
            {
                //$description_not_space     = str_replace( array('.',',','!','?',':'), '', $description);//убираем все символы препинания
                //$ar_description            = explode( ' ', $description_not_space );
                $ar_description            = explode( ' ', $description );//считаем слова разделённые пробелом (' ')
                $count_description_word    = count( $ar_description );
                if( $count_description_word )
                {
                    //формируем запрос
                    $ins = "insert into ".$this->tb_description."( info, count_words, count_symbols ) values ('".addslashes(htmlentities($tags['description'] , ENT_QUOTES, 'UTF-8' ))."', ".$count_description_word.", ".$count_description_symbols.")";

                    if(mysql_query( $ins, $this->link ))
                    {
                        if(SHOW_ECHO)
                            Log::write( "<p> description занесён в БД !</p>", $this->host_site );
                    }
                    else
                    {
                        if(SHOW_ECHO_ERROR)
                            Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                        exit();
                    }

                    $this->id_description = mysql_insert_id();
                }
            }
        }
        //>>end----------------------------------------------------
    }

    function ModelAddTitleKeywordsDescription()
    {
        if(!$this->html) return;

        //<<заносим html-код в БД + размер загужаемой страницы в Kb-------------------
        if( $this->html->innertext != '' )
        {
            //формируем запрос
            $upd = '';

            if($this->id_title && $this->id_keywords && $this->id_description)
            {
                $upd = "update ".$this->tb_pages." set title=".$this->id_title.", keywords=".$this->id_keywords.", description=".$this->id_description." where id=".$this->id_current_page;
            }
            else if($this->id_title)
            {
                if($this->id_title && $this->id_keywords){
                    $upd = "update ".$this->tb_pages." set title=".$this->id_title.", keywords=".$this->id_keywords." where id=".$this->id_current_page;
                }else if($this->id_title && $this->id_description){
                    $upd = "update ".$this->tb_pages." set title=".$this->id_title.", description=".$this->id_description." where id=".$this->id_current_page;
                }else{
                    $upd = "update ".$this->tb_pages." set title=".$this->id_title." where id=".$this->id_current_page;
                }
            }
            else if($this->id_keywords)
            {
                if($this->id_keywords && $this->id_title){
                    $upd = "update ".$this->tb_pages." set title=".$this->id_title.", keywords=".$this->id_keywords." where id=".$this->id_current_page;
                }else if($this->id_keywords && $this->id_description){
                    $upd = "update ".$this->tb_pages." set keywords=".$this->id_keywords.", description=".$this->id_description." where id=".$this->id_current_page;
                }else{
                    $upd = "update ".$this->tb_pages." set keywords=".$this->id_keywords." where id=".$this->id_current_page;
                }
            }
            else if($this->id_description)
            {
                if($this->id_description && $this->id_title){
                    $upd = "update ".$this->tb_pages." set title=".$this->id_title.", description=".$this->id_description." where id=".$this->id_current_page;
                }else if($this->id_description && $this->id_keywords){
                    $upd = "update ".$this->tb_pages." set keywords=".$this->id_keywords.", description=".$this->id_description." where id=".$this->id_current_page;
                }else{
                    $upd = "update ".$this->tb_pages." set description=".$this->id_description." where id=".$this->id_current_page;
                }
            }

            if($upd)
            {
                if(mysql_query( $upd, $this->link ))
                {
                    if(SHOW_ECHO)
                    {
                        Log::write( "<p>title | keywords | description Вашей странички занесён в БД !</p>", $this->host_site );
                    }
                }
                else
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
            }
        }
        //>>end----------------------------------------------------
    }

    function ModelUpdatePageIDTitleIDKeywordsIDDescription( $size_download = null, $speed_download = null, $total_time = null )
    {
        if(!$this->html) return;

        //<<заносим html-код в БД + размер загужаемой страницы в Kb-------------------
        if( $this->html->innertext != '' )
        {
            $text = addslashes(htmlentities($this->html_data , ENT_QUOTES, 'UTF-8' )); // html_entity_decode() - является противоположностью функции htmlentities().

            //формируем запрос
            $upd = "update ".$this->tb_pages." set page_code='".$text."', page_size=".floatval($size_download).", page_speed=".floatval($speed_download).", total_time=".floatval($total_time)." where id=".$this->id_current_page;

            if($this->id_title && $this->id_keywords && $this->id_description)
            {
                $upd = "update ".$this->tb_pages." set page_code='".$text."', title=".$this->id_title.", keywords=".$this->id_keywords.", description=".$this->id_description.", page_size=".floatval($size_download).", page_speed=".floatval($speed_download).", total_time=".floatval($total_time)." where id=".$this->id_current_page;
            }
            else if($this->id_title)
            {
                if($this->id_title && $this->id_keywords){
                    $upd = "update ".$this->tb_pages." set page_code='".$text."', title=".$this->id_title.", keywords=".$this->id_keywords.", page_size=".floatval($size_download).", page_speed=".floatval($speed_download).", total_time=".floatval($total_time)." where id=".$this->id_current_page;
                }else if($this->id_title && $this->id_description){
                    $upd = "update ".$this->tb_pages." set page_code='".$text."', title=".$this->id_title.", description=".$this->id_description.", page_size=".floatval($size_download).", page_speed=".floatval($speed_download).", total_time=".floatval($total_time)." where id=".$this->id_current_page;
                }else{
                    $upd = "update ".$this->tb_pages." set page_code='".$text."', title=".$this->id_title.", page_size=".floatval($size_download).", page_speed=".floatval($speed_download).", total_time=".floatval($total_time)." where id=".$this->id_current_page;
                }
            }
            else if($this->id_keywords)
            {
                if($this->id_keywords && $this->id_title){
                    $upd = "update ".$this->tb_pages." set page_code='".$text."', title=".$this->id_title.", keywords=".$this->id_keywords.", page_size=".floatval($size_download).", page_speed=".floatval($speed_download).", total_time=".floatval($total_time)." where id=".$this->id_current_page;
                }else if($this->id_keywords && $this->id_description){
                    $upd = "update ".$this->tb_pages." set page_code='".$text."', keywords=".$this->id_keywords.", description=".$this->id_description.", page_size=".floatval($size_download).", page_speed=".floatval($speed_download).", total_time=".floatval($total_time)." where id=".$this->id_current_page;
                }else{
                    $upd = "update ".$this->tb_pages." set page_code='".$text."', keywords=".$this->id_keywords.", page_size=".floatval($size_download).", page_speed=".floatval($speed_download).", total_time=".floatval($total_time)." where id=".$this->id_current_page;
                }
            }
            else if($this->id_description)
            {
                if($this->id_description && $this->id_title){
                    $upd = "update ".$this->tb_pages." set page_code='".$text."', title=".$this->id_title.", description=".$this->id_description.", page_size=".floatval($size_download).", page_speed=".floatval($speed_download).", total_time=".floatval($total_time)." where id=".$this->id_current_page;
                }else if($this->id_description && $this->id_keywords){
                    $upd = "update ".$this->tb_pages." set page_code='".$text."', keywords=".$this->id_keywords.", description=".$this->id_description.", page_size=".floatval($size_download).", page_speed=".floatval($speed_download).", total_time=".floatval($total_time)." where id=".$this->id_current_page;
                }else{
                    $upd = "update ".$this->tb_pages." set page_code='".$text."', description=".$this->id_description.", page_size=".floatval($size_download).", page_speed=".floatval($speed_download).", total_time=".floatval($total_time)." where id=".$this->id_current_page;
                }
            }

            /*if(!mysql_select_db($this->db_name, $this->link))
            {
                if(SHOW_ECHO_ERROR_CONNECT_DB)
                    Log::write( "<p> Ошибка подключения к БД ".mysql_errno()."</p>", $this->host_site );
                exit();
            }*/

            if(mysql_query( $upd, $this->link ))
            {
                if(SHOW_ECHO)
                {
                    Log::write( "<p>title | keywords | description Вашей странички занесён в БД !</p>", $this->host_site );
                }
            }
            else
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
        }
        //>>end----------------------------------------------------
    }

    function ModelAddImgToPage()
    {
        if(!$this->html) return;

        //<<получаем img из html и их в БД----------------------
        //работаем над поиском <img>
        if( $this->html->innertext != '' )
        {
            if($this->html->innertext != '' && count($this->html->find('img')))
            {
                $count = 0;
                foreach($this->html->find('img') as $img)
                {	//считаем количество слов в title картинки, разделённые пробелом (' ')
                    if($img->title){
                        $ar_word_title_img       = explode( ' ', $img->title );
                        $count_word_title_img    = count( $ar_word_title_img );
                    }else{
                        $count_word_title_img = 0;
                    }
                    //считаем количество слов в alt картинки, разделённые пробелом (' ')
                    if($img->alt){
                        $ar_word_alt_img         = explode( ' ', $img->alt );
                        $count_word_alt_img      = count( $ar_word_alt_img );
                    }else{
                        $count_word_alt_img = 0;
                    }

                    $ins = "insert into ".$this->tb_images."( page_id, src, title, count_word_title, alt, count_word_alt, width, height )
					values ('".$this->id_current_page."', '".addslashes(htmlentities($img->src , ENT_QUOTES, 'UTF-8' ))."',
					'".addslashes(htmlentities($img->title , ENT_QUOTES, 'UTF-8' ))."', ".$count_word_title_img.",
					'".addslashes(htmlentities($img->alt , ENT_QUOTES, 'UTF-8' ))."', ".$count_word_alt_img.",
					".intval($img->width).", ".intval($img->height).")";

                    if(mysql_query( $ins, $this->link ))
                    {
                        ++$count;
                        if(SHOW_ECHO)
                            Log::write( "<p> img-".$count." Вашей странички занесён в БД !</p>", $this->host_site );
                    }
                    else
                    {
                        if(SHOW_ECHO_ERROR)
                            Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                        exit();
                    }
                }
                //если производились записи в таблицу tb_links обновляем таблицу tb_pages
                if($count>0)
                {
                    $upd = "update ".$this->tb_pages." set images=".$this->id_current_page." where id=".$this->id_current_page;
                    if(mysql_query( $upd, $this->link ))
                    {
                        if(SHOW_ECHO)
                            Log::write( "<p> Ваша страничка №".$this->id_current_page." изменена в БД !</p>", $this->host_site );
                    }
                    else
                    {
                        if(SHOW_ECHO_ERROR)
                            Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                        exit();
                    }
                }
            }
        }
        //>>end----------------------------------------------------
    }

    function ModelAddContentToPage()
    {
        $this->id_content = 0;
        if(!$this->html) return;

        if( $this->html->innertext != '' )
        {
            $ar_text = array();
            $body = $this->html->find('body');

            if($body)
            {
                $original_text_body = $body[0]->plaintext;
                $count_words = 0;
                $count_symbols = 0;

                $this->ModelRecursionGetContent($body[0], $ar_text);

                if( count($ar_text) )
                {
                    foreach($ar_text as $str)
                    {
                        $count_symbols = $count_symbols + mb_strlen($str);
                        $count_words = $count_words + count(explode( ' ', $str ));
                    }

                    //формируем запрос
                    $ins = "insert into ".$this->tb_content."( original_text, text_words, count_words, count_symbols  ) values ('".addslashes(htmlentities($original_text_body , ENT_QUOTES, 'UTF-8' ))."', '".htmlentities(serialize($ar_text), ENT_QUOTES,  'UTF-8')."', ".$count_words.", ".$count_symbols.")";

                    if(mysql_query( $ins, $this->link ))
                    {
                        $this->id_content = mysql_insert_id();

                        if(SHOW_ECHO)
                            Log::write( "<p> content из body занесён в БД !</p>", $this->host_site );

                        $upd = "update ".$this->tb_pages." set content=".$this->id_content." where id=".$this->id_current_page;
                        if(mysql_query( $upd, $this->link ))
                        {
                            if(SHOW_ECHO)
                                Log::write( "<p> content Вашей странички занесён в БД !</p>", $this->host_site );
                        }
                        else
                        {
                            if(SHOW_ECHO_ERROR)
                                Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                            exit();
                        }
                    }
                    else
                    {
                        if(SHOW_ECHO_ERROR)
                            Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                        exit();
                    }
                }
            }
        }
    }

    function ModelRecursionGetContent( $items, & $ar_text )
    {
        if(!is_object($items)) return;

        $ar = $items->children();

        if( !count($ar) )
        {
            $tmp_text = trim($items->plaintext);
            if($tmp_text != '')
                $ar_text[] = $tmp_text;
        }
        else if( count($ar) > 0 )
        {
            foreach( $ar as $t )
            {
                $this->ModelRecursionGetContent( $t, $ar_text );
            }
        }
    }

    function ModelAddTagsToPage( $page_id )
    {
        if(!$this->html) return;

        //<<работаем над поиском тегов из массива $ar_tags
        foreach($this->ar_tags as $tag)
        {
            if($this->MODEL_DEFINE_TAGS[$tag])
            {
                if($this->html->innertext != '' && count($this->html->find($tag)))
                {
                    $count = 0;
                    foreach($this->html->find($tag) as $tag_elem)
                    {
                        $tag_elem_text = $tag_elem->plaintext;//заносим текст
                        //$tag_elem_text = $tag_elem->innertext;//заносим html
                        if($tag_elem_text)
                        {
                            $tag_elem_count_symbols = mb_strlen($tag_elem_text, 'UTF-8');
                            $tag_elem_count_words   = count( array_diff( explode(' ', str_replace(array("\r", "\n", "\t"), "", $tag_elem_text)), array('') ) );

                            //--------------------------------------------------------------------------------------

                            //формируем запрос
                            $ins = "insert into ".$tag."( page_id, info, count_words, count_symbols ) values (".$page_id.", '".addslashes(htmlentities($tag_elem_text , ENT_QUOTES, 'UTF-8' ))."', ".$tag_elem_count_words.", ".$tag_elem_count_symbols.")";

                            if(mysql_query( $ins, $this->link ))
                            {
                                $count++;
                                if(SHOW_ECHO)
                                    Log::write( "<p> ".$tag." занесён в БД !</p>", $this->host_site );
                            }
                            else
                            {
                                if(SHOW_ECHO_ERROR)
                                    Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                exit();
                            }
                        }
                    }//end foreach

                    //если производились записи в таблицы h1... | ...h6 | b | strong обновляем таблицу tb_pages
                    if($count>0)
                    {
                        $upd = "update ".$this->tb_pages." set ".$tag."=".$page_id." where id=".$page_id;
                        if(mysql_query( $upd, $this->link ))
                        {
                            if(SHOW_ECHO)
                                Log::write( "<p> Ваша страничка №".$page_id." изменена в БД !</p>", $this->host_site );
                        }
                        else
                        {
                            if(SHOW_ECHO_ERROR)
                                Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                            exit();
                        }
                    }
                }
            }
        }
        //>>end
    }

    function ModelAddEmailToPage( $page_id, $page_url, $page_code )
    {
        if(!$this->html) return;

        $title       = '';
        $keywords    = '';
        $description = '';

        if($this->id_title)
        {
            $sel = "SELECT info from ".$this->tb_title." WHERE id=".$this->id_title;
            $res = mysql_query($sel,$this->link);

            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            else
            {
                $line = mysql_fetch_array($res, MYSQL_ASSOC);
                $title = html_entity_decode( $line['info'], ENT_QUOTES, 'UTF-8' );
            }
        }
        if($this->id_keywords)
        {
            $sel = "SELECT info from ".$this->tb_keywords." WHERE id=".$this->id_keywords;
            $res = mysql_query($sel,$this->link);

            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            else
            {
                $line = mysql_fetch_array($res, MYSQL_ASSOC);
                $keywords = html_entity_decode( $line['info'], ENT_QUOTES, 'UTF-8' );
            }
        }
        if($this->id_description)
        {
            $sel = "SELECT info from ".$this->tb_description." WHERE id=".$this->id_description;
            $res = mysql_query($sel,$this->link);

            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            else
            {
                $line = mysql_fetch_array($res, MYSQL_ASSOC);
                $description = html_entity_decode( $line['info'], ENT_QUOTES, 'UTF-8' );
            }
        }

        preg_match_all($this->reg_search_str_email, $page_code , $ar, PREG_SET_ORDER);

        //Убрать повторяющиеся значения из многомерного массива
        //http://snipcode.ru/catalog.html?snipid=83
        $ar = array_map( "unserialize", array_unique( array_map("serialize", $ar) ) );

        foreach($ar as $a)
        {
            //формируем запрос для локальной таблицы email в текущей БД
            $sel = "select * from ".$this->tb_email_address." where ".self::$service_field_email_email." like '".$a[0]."'";
            $res = mysql_query( $sel, $this->link );
            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            elseif(mysql_num_rows($res)>0)
            {
                while ($row = mysql_fetch_array($res, MYSQL_ASSOC))
                {
                    $id = $row[self::$service_field_email_id];

                    $ins = "insert into ".$this->tb_email."( "
                        . self::$service_field_email_id_page
                        . ", ".self::$service_field_email_id_email_address
                        . " ) values (".$page_id.", ".$id.")";

                    if(mysql_query( $ins, $this->link ))
                    {
                        if(SHOW_ECHO)
                            Log::write( "<p>email занесён в БД !</p>", $this->host_site );
                    }
                    else
                    {
                        if(SHOW_ECHO_ERROR)
                            Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                        exit();
                    }
                }
            }
            else
            {
                //добавляем email_address
                $ins = "insert into ".$this->tb_email_address."( "
                    .self::$service_field_email_email. ", "
                    . self::$service_field_email_id_page
                    . " ) values ("
                    ."'".addslashes(htmlentities($a[0] , ENT_QUOTES, 'UTF-8' ))."'"
                    .", ". $page_id.")";

                if(mysql_query( $ins, $this->link ))
                {
                    $id = mysql_insert_id();
                    $this->ModelAddHashToEmail($this->db_name, $this->tb_email_address, $id);

                    $ins = "insert into ".$this->tb_email."( "
                        . self::$service_field_email_id_page
                        . ", ".self::$service_field_email_id_email_address
                        . " ) values (".$page_id.", ".$id.")";

                    if(mysql_query( $ins, $this->link ))
                    {
                        if(SHOW_ECHO)
                            Log::write( "<p>email занесён в БД !</p>", $this->host_site );
                    }
                    else
                    {
                        if(SHOW_ECHO_ERROR)
                            Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                        exit();
                    }
                }
                else
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
            }

            //формируем запрос для глобальной таблицы email в service БД
            $ins = "insert into ".self::$service_email_db_name.".".self::$service_table_name_email."( "
                . self::$service_field_email_url.", "
                . self::$service_field_email_domain
                . ", ".self::$service_field_email_title
                . ", ".self::$service_field_email_keywords
                . ", ".self::$service_field_email_description
                . ", ".self::$service_field_email_email
                . " ) values ("
                . "'".addslashes(htmlentities($page_url , ENT_QUOTES, 'UTF-8' ))."', "
                . "'".addslashes(htmlentities($this->host_site , ENT_QUOTES, 'UTF-8' ))."'"
                . ($this->id_title ? ", '".addslashes(htmlentities($title , ENT_QUOTES, 'UTF-8' ))."'" : ", ''")
                . ($this->id_keywords ? ", '".addslashes(htmlentities($keywords , ENT_QUOTES, 'UTF-8' ))."'" : ", ''")
                . ($this->id_description ? ", '".addslashes(htmlentities($description , ENT_QUOTES, 'UTF-8' ))."'" : ", ''")
                . ", '".addslashes(htmlentities($a[0] , ENT_QUOTES, 'UTF-8' ))."')";

            if(mysql_query( $ins, $this->link ))
            {
                if(SHOW_ECHO)
                    Log::write( "<p> ".$this->tb_email." занесён в БД !</p>", $this->host_site );
            }
            else
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." ".mysql_error()."при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
        }
    }

    function ModelAddHashToEmail( $db_name, $tb, $id_email )
    {
        $hash_db = base64_encode( $db_name );
        $hash_id = base64_encode( $id_email );
        $hash = base64_encode( $hash_db . " " . $hash_id );

        $upd = "update ".$tb." set ".self::$service_field_email_hash."='".$hash."' where ".self::$service_field_email_id."=".$id_email;
        if(mysql_query( $upd, $this->link ))
        {
            if(SHOW_ECHO)
                Log::write( "<p>hash занесён в БД !</p>", $this->host_site );
            return $hash;
        }
        else
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при добавлении hash в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
    }

    //===============методы перезагрузки страницы незагруженной из-зи окончания таймаута==================

    function ModelPageLoadTimeout()
    {
        $sel = "select id, url, level_links from ".$this->tb_pages." where http_code=".self::$curl_operation_timeout;
        $res = mysql_query($sel,$this->link);

        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        else
        {
            $ar_page = array();//чистим
            while ($page = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $ar_page[] = array('id' => intval($page['id']), 'url' => html_entity_decode($page['url'], ENT_QUOTES, 'UTF-8' ), 'level_links' => intval($page['level_links']) );
            }

            if( count($ar_page) )
            {
                foreach($ar_page as $page)
                {
                    if($this->limiter_pages < $this->limit_pages)
                    {
                        $this->ModelPageLoadTimeout_AddPages_AddInfo($page);
                    }
                    else
                    {
                        break;
                    }
                }
                if($this->count_pages > $this->cur_page)
                {
                    if(isset($this->html))
                    {
                        if(is_object($this->html))
                        {
                            $this->html->clear();
                        }
                        unset($this->html);
                    }

                    for( true; $this->cur_page < $this->count_pages; true )
                    {
                        if($this->limiter_pages < $this->limit_pages)
                        {
                            $this->ModelPageLoadTimeout_AddPageRepeat(++$this->cur_page);
                            if( ($this->cur_page % 10) == 0 || ($this->cur_page % 10) == 5 )
                            {
                                $upd = "update ".$this->tb_counter." set counter_scan_page=".$this->cur_page.", counter_scan_pages=".$this->count_pages." where id=".$this->counter_scan;
                                if(!mysql_query( $upd, $this->link ))
                                {
                                    if(SHOW_ECHO_ERROR)
                                        Log::write( "<p> ошибка ".mysql_errno()." при работе с табл. counter ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                    exit();
                                }
                            }
                        }
                        else
                        {
                            break;
                        }
                    }
                }
            }
        }
    }

    function ModelPageLoadTimeout_AddPages_AddInfo( $page )//$page - не обьект Page(), а массив array('id'=>[id], 'url'=>[url]);
    {
        $flag = false;
        $ar_components_link = parse_url($page['url']);

        if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
            $tmp_url = $page['url'];
        }else{
            if(strpos($ar_components_link['path'], '/') === 0){
                $tmp_url = $this->protocol.'://'.$this->host_site.$page['url'];
            }else{
                $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$page['url'];
            }
        }

        $this->html_data='';

        $tmp_id = $page['id'];

        $tmp_level_links = $page['level_links'];

        $this->ModelCurlInit($ch, $tmp_url);

        if(curl_exec($ch))
        {
            $ar = curl_getinfo($ch);
            if(count($ar))
            {
                if( (isset($ar['url']) && parse_url($ar['url'], PHP_URL_HOST) == $this->host_site)
                    && (isset($ar['http_code']) && $ar['http_code'] == 200)
                    && (isset($ar['content_type']) && strstr($ar['content_type'], 'text/html')) )
                {
                    if( $ar['size_download'] == strlen($this->html_data) )
                    {
                        $this->ModelSetCurrentUrl( $tmp_url, $this->html_data );
                        if($this->html->_charset != $this->html->_target_charset)
                            $this->html_data = iconv($this->html->_charset, $this->html->_target_charset, $this->html_data);
                    }
                    //формируем запрос
                    $upd = "update ".$this->tb_pages
                        ." set page_code='".addslashes(htmlentities($this->html_data , ENT_QUOTES, 'UTF-8' ))
                        ."', http_code=".$ar['http_code']
                        .", content_type='".addslashes(htmlentities($ar['content_type'] , ENT_QUOTES, 'UTF-8' ))
                        ."', date_change_page='".date("Y-m-d H:i:s",mktime())
                        ."', load_count=".self::$load_count
                        .", load_timeout=".self::$load_timeout
                        .", total_time=".$ar['total_time']
                        .", connect_time=".$ar['connect_time']
                        .", page_size=".$ar['size_download']
                        .", page_speed=".$ar['speed_download']
                        ." where id=".$tmp_id;

                    /*if(!mysql_select_db($this->db_name, $this->link))
                    {
                        if(SHOW_ECHO_ERROR_CONNECT_DB)
                            Log::write( "<p> Ошибка подключения к БД ".mysql_errno()."</p>", $this->host_site );
                        exit();
                    }*/
                    if(mysql_query( $upd, $this->link ))
                    {
                        if(SHOW_ECHO)
                            Log::write( "<p> Ваша страничка №".$tmp_id." изменена в БД !</p>", $this->host_site );
                    }
                    else
                    {
                        if(SHOW_ECHO_ERROR)
                            Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                        exit();
                    }
                    //>end------------------------------------------------------------------------------------

                    //<работаем над поиском <a>---------------------------------------------------------------
                    $ar_internal_links = array();
                    $ar_lines_links    = array();

                    if($this->html)
                    {
                        if($this->html->innertext != '' && count($this->html->find('a')))
                        {
                            $ar_links_current_page    = $this->html->find('a');
                            $count_links_current_page = count( $ar_links_current_page );

                            if( $count_links_current_page > 0)
                            {
                                $count = 0;

                                $ar_internal_links = array();
                                foreach($ar_links_current_page as $link)
                                {
                                    if(!$link->href)continue;

                                    $ar_components_link = parse_url( $link->href );
                                    if( isset($ar_components_link['host']) && $ar_components_link['host'] == $this->host_site )
                                    {//если в url есть хост и он == хосту сайта
                                        if( !in_array($link->href, $ar_internal_links) )
                                        {
                                            $ar_internal_links[] = $link->href;
                                        }
                                        //internal_link == 1 - внутренняя ссылка по которой будет производится переход
                                        $ins = "insert into ".$this->tb_links."( page_id, internal_link, href, anchor ) values (".$tmp_id.", 1, '".addslashes(htmlentities($link->href , ENT_QUOTES, 'UTF-8' ))."', '".addslashes(htmlentities($link->plaintext , ENT_QUOTES, 'UTF-8' ))."')";
                                    }
                                    else if( !isset($ar_components_link['host']) && isset($ar_components_link['path']))
                                    {//если в url нет хоста, но есть /path сайта
                                        if( ( (strpos($link->href, 'javascript:')!==0) && (strpos($link->href, 'Javascript:')!==0) && (strpos($link->href, 'mailto:')!==0) ) && !in_array($link->href, $ar_internal_links))
                                        {
                                            $ar_internal_links[] = $link->href;
                                        }
                                        //internal_link == 1 - внутренняя ссылка (/path) по которой будет производится переход
                                        $ins = "insert into ".$this->tb_links."( page_id, internal_link, href, anchor ) values (".$tmp_id.", 1, '".addslashes(htmlentities($link->href , ENT_QUOTES, 'UTF-8' ))."', '".addslashes(htmlentities($link->plaintext , ENT_QUOTES, 'UTF-8' ))."')";
                                    }
                                    else if( !isset($ar_components_link['host']) && isset($ar_components_link['fragment']))
                                    {//если в url нет хоста, но есть #fragment сайта
                                        //internal_link == 1 - внутренняя ссылка (#top) по которой не будет производится переход
                                        $ins = "insert into ".$this->tb_links."( page_id, internal_link, href, anchor ) values (".$tmp_id.", 1, '".addslashes(htmlentities($link->href , ENT_QUOTES, 'UTF-8' ))."', '".addslashes(htmlentities($link->plaintext , ENT_QUOTES, 'UTF-8' ))."')";
                                    }
                                    else if( !isset($ar_components_link['host']) && !isset($ar_components_link['fragment']) && $link->href == '#')
                                    {//если в url нет хоста, но есть #fragment сайта
                                        //internal_link == 1 - внутренняя ссылка (#) по которой не будет производится переход
                                        $ins = "insert into ".$this->tb_links."( page_id, internal_link, href, anchor ) values (".$tmp_id.", 1, '".addslashes(htmlentities($link->href , ENT_QUOTES, 'UTF-8' ))."', '".addslashes(htmlentities($link->plaintext , ENT_QUOTES, 'UTF-8' ))."')";
                                    }
                                    else
                                    {
                                        //internal_link == 0 - внешняя ссылка по которой не будет производится переход
                                        $ins = "insert into ".$this->tb_links."( page_id, internal_link, href, anchor ) values (".$tmp_id.", 0, '".addslashes(htmlentities($link->href , ENT_QUOTES, 'UTF-8' ))."', '".addslashes(htmlentities($link->plaintext , ENT_QUOTES, 'UTF-8' ))."')";
                                    }

                                    if(mysql_query( $ins, $this->link ))
                                    {
                                        ++$count;
                                        if(SHOW_ECHO)
                                            Log::write( "<p> links-".$count." Вашей странички занесён в БД !</p>", $this->host_site );
                                    }
                                    else
                                    {
                                        if(SHOW_ECHO_ERROR)
                                            Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                        exit();
                                    }
                                }//end foreach

                                //если производились записи в таблицу tb_links обновляем таблицу tb_pages
                                if($count>0)
                                {
                                    $upd = "update ".$this->tb_pages." set links=".$tmp_id." where id=".$tmp_id;
                                    if(mysql_query( $upd, $this->link ))
                                    {
                                        if(SHOW_ECHO)
                                            Log::write( "<p> Ваша страничка №".$tmp_id." изменена в БД !</p>", $this->host_site );
                                    }
                                    else
                                    {
                                        if(SHOW_ECHO_ERROR)
                                            Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                        exit();
                                    }
                                }
                            }
                        }
                    }
                    //>>end-----------------------------------------------------------------------------------

                    $this->limiter_pages++;
                    if($this->limiter_pages < $this->limit_pages)
                    {
                        //<получаем ссылки со страницы-----------------------------------------------
                        if( count( $ar_internal_links ) )
                        {
                            $ar_url_page = array();//чистим
                            foreach($this->ar_pages as $p)
                            {
                                $ar_url_page[] = $p->url;
                            }

                            $ar_lines_links = array();
                            foreach($ar_internal_links as $href)
                            {
                                if( !in_array($href, $ar_url_page) )
                                {
                                    $ar_lines_links[] = $href;
                                }
                            }
                            $ar_url_page = array();//чистим
                        }
                        //>end--------------------------------------------------------------------

                        //<добавляем новые ссылки в таблицу pages---------------------------------
                        if( count($ar_lines_links) )
                        {
                            $tmp_level_links++;

                            foreach( $ar_lines_links as $link )
                            {
                                //формируем запрос
                                /*$ins = "insert into ".$this->tb_pages."(
                                url,
                                parent_url,
                                level_links,
                                scan,
                                exist,
                                create_page,
                                date_change_page ) values ('"
                                    .htmlentities($link , ENT_QUOTES, 'UTF-8' )
                                    ."', '".htmlentities($page['url'] , ENT_QUOTES, 'UTF-8' )
                                    ."', ".$tmp_level_links
                                    .", ".$this->counter_scan
                                    .", 1,
                                1,
                                '".date("Y-m-d H:i:s",mktime())."')";

                                if(!mysql_select_db($this->db_name, $this->link))
                                {
                                    if(SHOW_ECHO_ERROR_CONNECT_DB)
                                        Log::write( "<p> Ошибка подключения к БД ".mysql_errno()."</p>", $this->host_site );
                                    exit();
                                }
                                if(mysql_query( $ins, $this->link ))
                                {
                                    if(SHOW_ECHO)
                                        Log::write( "<p> html-сode Вашей странички занесён в БД !</p>", $this->host_site );
                                }
                                else
                                {
                                    if(SHOW_ECHO_ERROR)
                                        Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                    exit();
                                }
                                $this->ar_pages[++$this->count_pages] = new Link($link, $tmp_level_links, $page['url'], mysql_insert_id());*/
                                $this->ar_pages[++$this->count_pages] = new Link($link, $tmp_level_links, $page['url']);
                            }
                        }
                        //>end--------------------------------------------------------------------
                    }

                    if(isset($this->html))
                    {
                        if(is_object($this->html))
                        {
                            $this->html->clear();
                        }
                        unset($this->html);
                    }
                    $flag = true;
                }
                else
                {
                    //записываем в БД URL | http_code | content_type - больше ничего не делаем, начего незагружаем, т.к. это файл не HTML
                    //.jpg .png .pdf и т.д.

                    //записываем в БД URL | http_code | content_type - больше ничего не делаем, т.к. это может быть всё что угодно !
                    // 'http://iportal.com.ua/javascript:("zzz");'  'http://iportal.com.ua/mailto:info@iportal.com.ua' и т.д.

                    //записываем в БД redirect_url - т.к. это может быть редирект !!!

                    //формируем запрос
                    $upd = "update ".$this->tb_pages
                        ." set page_code='', http_code=".$ar['http_code']
                        .", content_type='".addslashes(htmlentities($ar['content_type'] , ENT_QUOTES, 'UTF-8' ))
                        ."', redirect_url=".($ar['redirect_url']?("'".addslashes(htmlentities( $ar['redirect_url'], ENT_QUOTES, 'UTF-8' ))."'"):'NULL')
                        .", load_count=".self::$load_count
                        .", load_timeout=".self::$load_timeout
                        .", total_time=".curl_getinfo($ch, CURLINFO_TOTAL_TIME )
                        .", connect_time=".curl_getinfo($ch, CURLINFO_CONNECT_TIME )
                        .($ar['size_download']?(", page_size=".$ar['size_download']):"")
                        .($ar['speed_download']?(", page_speed=".$ar['speed_download']):"")
                        ." where id=".$tmp_id;
                    if(mysql_query( $upd, $this->link ))
                    {
                        if(SHOW_ECHO)
                            Log::write( "<p> Ваша страничка №".$tmp_id." изменена в БД !</p>", $this->host_site );
                    }
                    else
                    {
                        if(SHOW_ECHO_ERROR)
                            Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                        exit();
                    }
                    $flag = true;
                }
            }
        }
        else
        {
            $this->ModelChargeTimeout( curl_errno($ch), __METHOD__, $page );
            $flag = $this->ModelStartTimeout();
            if(!$flag)
            {
                $upd = "update ".$this->tb_pages
                    ." set page_code='', http_code=".curl_errno($ch)
                    .", content_type='".addslashes(htmlentities(curl_error($ch) , ENT_QUOTES, 'UTF-8' ))
                    ."', load_count=".self::$load_count
                    .", load_timeout=".self::$load_timeout
                    .", total_time=".curl_getinfo($ch, CURLINFO_TOTAL_TIME )
                    .", connect_time=".curl_getinfo($ch, CURLINFO_CONNECT_TIME )
                    .(curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD )?", page_size=".curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD ):"")
                    .(curl_getinfo($ch, CURLINFO_SPEED_DOWNLOAD )?", page_speed=".curl_getinfo($ch, CURLINFO_SPEED_DOWNLOAD ):"")
                    ." where id=".$tmp_id;
                if(mysql_query( $upd, $this->link ))
                {
                    if(SHOW_ECHO)
                        Log::write( "<p>ошибки загрузки Вашей странички занесены в БД !</p>", $this->host_site );
                }
                else
                {
                    if(SHOW_ECHO_ERROR)
                    {
                        Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                        Log::write( "<p>".$upd."</p>", $this->host_site );
                    }
                    exit();
                }
                $flag = true;
            }
            $this->ModelStopTimeout();
        }
        curl_close($ch);
        unset($ch);
        unset($ar);
        return $flag;
    }

    function ModelPageLoadTimeout_AddPageRepeat( $num )
    {
        $flag = false;
        //<заносим страницу в БД----------------------------------------------------------------
        $ar_components_link = parse_url( $this->ar_pages[$num]->url );

        if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
            $tmp_url = $this->ar_pages[$num]->url;
        }else{
            if(strpos($ar_components_link['path'], '/') === 0){
                $tmp_url = $this->protocol.'://'.$this->host_site.$this->ar_pages[$num]->url;
            }else{
                $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$this->ar_pages[$num]->url;
            }
        }

        $tmp_level_links = $this->ar_pages[$num]->level;
        //$parent_url = $this->ar_pages[$num]->url;
        //$tmp_id = $this->ar_pages[$num]->id;

        $this->html_data = '';

        $this->ModelCurlInit($ch, $tmp_url);

        if(curl_exec($ch))
        {
            $ar = curl_getinfo($ch);
            if(count($ar))
            {
                if( (isset($ar['url']) && parse_url($ar['url'], PHP_URL_HOST) == $this->host_site)
                    && (isset($ar['http_code']) && $ar['http_code'] == 200)
                    && (isset($ar['content_type']) && strstr($ar['content_type'], 'text/html')) )
                {
                    //страницу загружаем делаем все что надо, т.к. это файл HTML
                    if( $ar['size_download'] == strlen($this->html_data) )
                    {
                        $this->ModelSetCurrentUrl( $tmp_url, $this->html_data );
                        if($this->html->_charset != $this->html->_target_charset)
                            $this->html_data = iconv($this->html->_charset, $this->html->_target_charset, $this->html_data);
                    }

                    /*//формируем запрос
                    $upd = "update ".$this->tb_pages
                        ." set page_code='".htmlentities($this->html_data , ENT_QUOTES, 'UTF-8' )
                        ."', http_code=".$ar['http_code']
                        .", content_type='".htmlentities($ar['content_type'] , ENT_QUOTES, 'UTF-8' )
                        ."', date_change_page='".date("Y-m-d H:i:s",mktime())
                        ."', load_count=".self::$load_count
                        .", load_timeout=".self::$load_timeout
                        .", total_time=".$ar['total_time']
                        .", page_size=".$ar['size_download']
                        .", page_speed=".$ar['speed_download']
                        ." where id=".$tmp_id;

                    if(mysql_query( $upd, $this->link ))
                    {
                        if(SHOW_ECHO)
                            Log::write( "<p> Ваша страничка №".$tmp_id." изменена в БД !</p>", $this->host_site );
                    }
                    else
                    {
                        if(SHOW_ECHO_ERROR)
                            Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                        exit();
                    }*/

                    //формируем запрос
                    $ins = "insert into ".$this->tb_pages."(
                    url,
                    page_code,
                    http_code,
                    content_type,
                    parent_url,
                    connect_time,
                    total_time,
                    page_size,
                    page_speed,
                    level_links,
                    load_count,
                    load_timeout,
                    scan,
                    exist,
                    create_page,
                    date_change_page) values ('"
                        .addslashes(htmlentities($this->ar_pages[$num]->url , ENT_QUOTES, 'UTF-8' ))
                        ."', '".addslashes(htmlentities($this->html_data , ENT_QUOTES, 'UTF-8' ))
                        ."', ".$ar['http_code']
                        .", '".addslashes(htmlentities($ar['content_type'] , ENT_QUOTES, 'UTF-8' ))
                        ."', '".addslashes(htmlentities($this->ar_pages[$num]->parent_url , ENT_QUOTES, 'UTF-8' ))
                        ."', ".$ar['connect_time']
                        .", ".$ar['total_time']
                        .", ".$ar['size_download']
                        .", ".$ar['speed_download']
                        .", ".$this->ar_pages[$num]->level
                        .", ".self::$load_count
                        .", ".self::$load_timeout
                        .", ".$this->counter_scan
                        .", 1"
                        .", 1,
                        '".date("Y-m-d H:i:s",mktime())."')";

                    if(mysql_query( $ins, $this->link ))
                    {
                        $this->ar_pages[$num]->id = mysql_insert_id();

                        if(SHOW_ECHO)
                            Log::write( "<p> html-сode Вашей странички занесён в БД !</p>", $this->host_site );
                    }
                    else
                    {
                        if(SHOW_ECHO_ERROR)
                            Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                        exit();
                    }
                    //>end------------------------------------------------------------------------------------

                    //<работаем над поиском <a>---------------------------------------------------------------
                    $ar_internal_links = array();
                    $ar_lines_links    = array();

                    if($this->html)
                    {
                        if($this->html->innertext != '' && count($this->html->find('a')))
                        {
                            $ar_links_current_page    = $this->html->find('a');
                            $count_links_current_page = count( $ar_links_current_page );

                            if( $count_links_current_page > 0)
                            {
                                $count = 0;
                                $tmp_id = $this->ar_pages[$num]->id;

                                $ar_internal_links = array();
                                foreach($ar_links_current_page as $link)
                                {
                                    if(!$link->href)continue;

                                    $ar_components_link = parse_url( $link->href );
                                    if( isset($ar_components_link['host']) && $ar_components_link['host'] == $this->host_site )
                                    {//если в url есть хост и он == хосту сайта
                                        if( !in_array($link->href, $ar_internal_links) )
                                        {
                                            $ar_internal_links[] = $link->href;
                                        }
                                        //internal_link == 1 - внутренняя ссылка по которой будет производится переход
                                        $ins = "insert into ".$this->tb_links."( page_id, internal_link, href, anchor ) values (".$tmp_id.", 1, '".addslashes(htmlentities($link->href , ENT_QUOTES, 'UTF-8' ))."', '".addslashes(htmlentities($link->plaintext , ENT_QUOTES, 'UTF-8' ))."')";
                                    }
                                    else if( !isset($ar_components_link['host']) && isset($ar_components_link['path']))
                                    {//если в url нет хоста, но есть /path сайта
                                        if( ( (strpos($link->href, 'javascript:')!==0) && (strpos($link->href, 'Javascript:')!==0) && (strpos($link->href, 'mailto:')!==0) ) && !in_array($link->href, $ar_internal_links))
                                        {
                                            $ar_internal_links[] = $link->href;
                                        }
                                        //internal_link == 1 - внутренняя ссылка (/path) по которой будет производится переход
                                        $ins = "insert into ".$this->tb_links."( page_id, internal_link, href, anchor ) values (".$tmp_id.", 1, '".addslashes(htmlentities($link->href , ENT_QUOTES, 'UTF-8' ))."', '".addslashes(htmlentities($link->plaintext , ENT_QUOTES, 'UTF-8' ))."')";
                                    }
                                    else if( !isset($ar_components_link['host']) && isset($ar_components_link['fragment']))
                                    {//если в url нет хоста, но есть #fragment сайта
                                        //internal_link == 1 - внутренняя ссылка (#top) по которой не будет производится переход
                                        $ins = "insert into ".$this->tb_links."( page_id, internal_link, href, anchor ) values (".$tmp_id.", 1, '".addslashes(htmlentities($link->href , ENT_QUOTES, 'UTF-8' ))."', '".addslashes(htmlentities($link->plaintext , ENT_QUOTES, 'UTF-8' ))."')";
                                    }
                                    else if( !isset($ar_components_link['host']) && !isset($ar_components_link['fragment']) && $link->href == '#')
                                    {//если в url нет хоста, но есть #fragment сайта
                                        //internal_link == 1 - внутренняя ссылка (#) по которой не будет производится переход
                                        $ins = "insert into ".$this->tb_links."( page_id, internal_link, href, anchor ) values (".$tmp_id.", 1, '".addslashes(htmlentities($link->href , ENT_QUOTES, 'UTF-8' ))."', '".addslashes(htmlentities($link->plaintext , ENT_QUOTES, 'UTF-8' ))."')";
                                    }
                                    else
                                    {
                                        //internal_link == 0 - внешняя ссылка по которой не будет производится переход
                                        $ins = "insert into ".$this->tb_links."( page_id, internal_link, href, anchor ) values (".$tmp_id.", 0, '".addslashes(htmlentities($link->href , ENT_QUOTES, 'UTF-8' ))."', '".addslashes(htmlentities($link->plaintext , ENT_QUOTES, 'UTF-8' ))."')";
                                    }

                                    if(mysql_query( $ins, $this->link ))
                                    {
                                        ++$count;
                                        if(SHOW_ECHO)
                                            Log::write( "<p> links-".$count." Вашей странички занесён в БД !</p>", $this->host_site );
                                    }
                                    else
                                    {
                                        if(SHOW_ECHO_ERROR)
                                            Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                        exit();
                                    }
                                }//end foreach

                                //если производились записи в таблицу tb_links обновляем таблицу tb_pages
                                if($count>0)
                                {
                                    $upd = "update ".$this->tb_pages." set links=".$tmp_id." where id=".$tmp_id;
                                    if(mysql_query( $upd, $this->link ))
                                    {
                                        if(SHOW_ECHO)
                                            Log::write( "<p> Ваша страничка №".$tmp_id." изменена в БД !</p>", $this->host_site );
                                    }
                                    else
                                    {
                                        if(SHOW_ECHO_ERROR)
                                            Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                        exit();
                                    }
                                }
                            }
                        }
                    }
                    //>>end-----------------------------------------------------------------------------------

                    $this->limiter_pages++;
                    if($this->limiter_pages < $this->limit_pages)
                    {
                        //<получаем ссылки со страницы-----------------------------------------------
                        if( count( $ar_internal_links ) )
                        {
                            $ar_url_page = array();//чистим
                            foreach($this->ar_pages as $p)
                            {
                                $ar_url_page[] = $p->url;
                            }

                            $ar_lines_links = array();
                            foreach($ar_internal_links as $href)
                            {
                                if( !in_array($href, $ar_url_page) )
                                {
                                    $ar_lines_links[] = $href;
                                }
                            }
                            $ar_url_page = array();//чистим
                        }
                        //>end--------------------------------------------------------------------

                        //<добавляем новые ссылки в таблицу pages---------------------------------
                        if( count($ar_lines_links) )
                        {
                            $tmp_level_links++;

                            foreach( $ar_lines_links as $link )
                            {
                                /*//формируем запрос
                                $ins = "insert into ".$this->tb_pages."(
                                url,
                                parent_url,
                                level_links,
                                scan,
                                exist,
                                create_page,
                                date_change_page ) values ('"
                                    .htmlentities($link , ENT_QUOTES, 'UTF-8' )
                                    ."', '".htmlentities($parent_url , ENT_QUOTES, 'UTF-8' )
                                    ."', ".$tmp_level_links
                                    .", ".$this->counter_scan
                                    .", 1,
                                    1,
                                    '".date("Y-m-d H:i:s",mktime())."')";

                                if(!mysql_select_db($this->db_name, $this->link))
                                {
                                    if(SHOW_ECHO_ERROR_CONNECT_DB)
                                        Log::write( "<p> Ошибка подключения к БД ".mysql_errno()."</p>", $this->host_site );
                                    exit();
                                }
                                if(mysql_query( $ins, $this->link ))
                                {
                                    if(SHOW_ECHO)
                                        Log::write( "<p> html-сode Вашей странички занесён в БД !</p>", $this->host_site );
                                }
                                else
                                {
                                    if(SHOW_ECHO_ERROR)
                                        Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                    exit();
                                }
                                $this->ar_pages[++$this->count_pages] = new Link($link, $tmp_level_links, $parent_url, mysql_insert_id());*/
                                $this->ar_pages[++$this->count_pages] = new Link($link, $tmp_level_links, $this->ar_pages[$num]->url);
                            }
                        }
                        //>end--------------------------------------------------------------------
                    }

                    if(isset($this->html))
                    {
                        if(is_object($this->html))
                        {
                            $this->html->clear();
                        }
                        unset($this->html);
                    }
                    $flag = true;
                }
                else
                {
                    //записываем в БД URL | http_code | content_type - больше ничего не делаем, начего незагружаем, т.к. это файл не HTML
                    //.jpg .png .pdf и т.д.

                    //записываем в БД URL | http_code | content_type - больше ничего не делаем, т.к. это может быть всё что угодно !
                    // 'http://iportal.com.ua/javascript:("zzz");'  'http://iportal.com.ua/mailto:info@iportal.com.ua' и т.д.

                    //записываем в БД redirect_url - т.к. это может быть редирект !!!

                    /*//формируем запрос
                    $upd = "update ".$this->tb_pages
                        ." set page_code='', http_code=".$ar['http_code']
                        .", content_type='".htmlentities($ar['content_type'] , ENT_QUOTES, 'UTF-8' )
                        ."', redirect_url=".($ar['redirect_url']?("'".htmlentities( $ar['redirect_url'], ENT_QUOTES, 'UTF-8' )."'"):'NULL')
                        .", load_count=".self::$load_count
                        .", load_timeout=".self::$load_timeout
                        .", total_time=".curl_getinfo($ch, CURLINFO_TOTAL_TIME )
                        ." where id=".$tmp_id;

                    if(!mysql_select_db($this->db_name, $this->link))
                    {
                        if(SHOW_ECHO_ERROR_CONNECT_DB)
                            Log::write( "<p> Ошибка подключения к БД ".mysql_errno()."</p>", $this->host_site );
                        exit();
                    }
                    if(mysql_query( $upd, $this->link ))
                    {
                        if(SHOW_ECHO)
                            Log::write( "<p> Ваша страничка №".$tmp_id." изменена в БД !</p>", $this->host_site );
                    }
                    else
                    {
                        if(SHOW_ECHO_ERROR)
                            Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                        exit();
                    }*/

                    //формируем запрос
                    $ins = "insert into ".$this->tb_pages."(
                    url,
                    page_code,
                    http_code,
                    content_type,
                    redirect_url,
                    parent_url,
                    connect_time,
                    total_time,
                    page_size,
                    page_speed,
                    level_links,
                    load_count,
                    load_timeout,
                    scan,
                    exist,
                    create_page,
                    date_change_page) values ('"
                        .addslashes(htmlentities($this->ar_pages[$num]->url , ENT_QUOTES, 'UTF-8' ))
                        ."', '".""
                        ."', ".$ar['http_code']
                        .", '".addslashes(htmlentities($ar['content_type'] , ENT_QUOTES, 'UTF-8' ))
                        ."', ".($ar['redirect_url']?("'".addslashes(htmlentities( $ar['redirect_url'], ENT_QUOTES, 'UTF-8' ))."'"):'NULL')
                        .", '".addslashes(htmlentities($this->ar_pages[$num]->parent_url , ENT_QUOTES, 'UTF-8' ))
                        ."', ".curl_getinfo($ch, CURLINFO_CONNECT_TIME )
                        .", ".curl_getinfo($ch, CURLINFO_TOTAL_TIME )
                        .", ".($ar['size_download']?$ar['size_download']:'NULL')
                        .", ".($ar['speed_download']?$ar['speed_download']:'NULL')
                        .", ".$this->ar_pages[$num]->level
                        .", ".self::$load_count
                        .", ".self::$load_timeout
                        .", ".$this->counter_scan
                        .", 1"
                        .", 1,
                        '".date("Y-m-d H:i:s",mktime())."')";

                    if(mysql_query( $ins, $this->link ))
                    {
                        $this->ar_pages[$num]->id = mysql_insert_id();

                        if(SHOW_ECHO)
                            Log::write( "<p> html-сode Вашей странички занесён в БД !</p>", $this->host_site );
                    }
                    else
                    {
                        if(SHOW_ECHO_ERROR)
                            Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                        exit();
                    }
                    $flag = true;
                }
            }
        }
        else
        {
            $this->ModelChargeTimeout( curl_errno($ch), __METHOD__, $num );
            $flag = $this->ModelStartTimeout();
            if(!$flag)
            {
                /*$upd = "update ".$this->tb_pages
                    ." set page_code='', http_code=".curl_errno($ch)
                    .", content_type='".htmlentities(curl_error($ch) , ENT_QUOTES, 'UTF-8' )
                    ."', load_count=".self::$load_count
                    .", load_timeout=".self::$load_timeout
                    .", total_time=".curl_getinfo($ch, CURLINFO_TOTAL_TIME )
                    ." where id=".$tmp_id;
                if(mysql_query( $upd, $this->link ))
                {
                    if(SHOW_ECHO)
                        Log::write( "<p>ошибки загрузки Вашей странички занесены в БД !</p>", $this->host_site );
                }
                else
                {
                    if(SHOW_ECHO_ERROR)
                    {
                        Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                        Log::write( "<p>".$upd."</p>", $this->host_site );
                    }
                    exit();
                }*/
                //формируем запрос
                $ins = "insert into ".$this->tb_pages."(
                url,
                page_code,
                http_code,
                content_type,
                parent_url,
                connect_time,
                total_time,
                page_size,
                page_speed,
                level_links,
                load_count,
                load_timeout,
                scan,
                exist,
                create_page,
                date_change_page) values ('"
                    .addslashes(htmlentities($this->ar_pages[$num]->url , ENT_QUOTES, 'UTF-8' ))
                    ."', '".""
                    ."', ".curl_errno($ch)
                    .", '".addslashes(htmlentities(curl_error($ch) , ENT_QUOTES, 'UTF-8' ))
                    ."', '".addslashes(htmlentities($this->ar_pages[$num]->parent_url , ENT_QUOTES, 'UTF-8' ))
                    ."', ".curl_getinfo($ch, CURLINFO_CONNECT_TIME )
                    .", ".curl_getinfo($ch, CURLINFO_TOTAL_TIME )
                    .", ".(curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD )?curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD ):'NULL')
                    .", ".(curl_getinfo($ch, CURLINFO_SPEED_DOWNLOAD )?curl_getinfo($ch, CURLINFO_SPEED_DOWNLOAD ):'NULL')
                    .", ".$this->ar_pages[$num]->level
                    .", ".self::$load_count
                    .", ".self::$load_timeout
                    .", ".$this->counter_scan
                    .", 1"
                    .", 1,
                '".date("Y-m-d H:i:s",mktime())."')";

                if(mysql_query( $ins, $this->link ))
                {
                    $this->ar_pages[$num]->id = mysql_insert_id();

                    if(SHOW_ECHO)
                        Log::write( "<p>ошибки загрузки Вашей странички занесены в БД !</p>", $this->host_site );
                }
                else
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
                $flag = true;
            }
            $this->ModelStopTimeout();
        }
        curl_close($ch);
        unset($ch);
        unset($ar);
        return $flag;
    }

    //================================методы сравнения=================================================

    //-----------------получаем из БД данные и формируем обьект Page $page---------------------------
    protected function ModelCompare_Mysql_GetTitlePage( & $page )
    {
        $sel = "select * from ".$this->tb_title." where id=".$page->title;
        $res = mysql_query($sel,$this->link);

        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        else
        {
            $title_page = null;
            while ($line = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $title_page = new TitlePage();
                $title_page->id             = intval( $line['id'] );
                $title_page->info           = html_entity_decode( $line['info'], ENT_QUOTES, 'UTF-8' );
                $title_page->count_words    = intval( $line['count_words'] );
                $title_page->count_symbols  = intval( $line['count_symbols'] );
            }

            $page->obj_title = $title_page;
        }
    }

    protected function ModelCompare_Mysql_GetKeywordsPage( & $page )
    {
        $sel = "select * from ".$this->tb_keywords." where id=".$page->keywords;
        $res = mysql_query($sel,$this->link);

        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        else
        {
            $keywords_page = null;
            while ($line = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $keywords_page = new KeywordsPage();
                $keywords_page->id             = intval( $line['id'] );
                $keywords_page->info           = html_entity_decode( $line['info'], ENT_QUOTES, 'UTF-8' );
                $keywords_page->count_words    = intval( $line['count_words'] );
                $keywords_page->count_symbols  = intval( $line['count_symbols'] );
            }

            $page->obj_keywords = $keywords_page;
        }
    }

    protected function ModelCompare_Mysql_GetDescriptionPage( & $page )
    {
        $sel = "select * from ".$this->tb_description." where id=".$page->description;
        $res = mysql_query($sel,$this->link);

        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        else
        {
            $description_page = null;
            while ($line = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $description_page = new DescriptionPage();
                $description_page->id             = intval( $line['id'] );
                $description_page->info           = html_entity_decode( $line['info'], ENT_QUOTES, 'UTF-8' );
                $description_page->count_words    = intval( $line['count_words'] );
                $description_page->count_symbols  = intval( $line['count_symbols'] );
            }

            $page->obj_description = $description_page;
        }
    }

    protected function ModelCompare_Mysql_GetLinksPage( & $page )
    {
        $sel = "select * from ".$this->tb_links." where page_id=".$page->links;
        $res = mysql_query($sel,$this->link);

        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        else
        {
            $ar_links_page = null;
            while ($line = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $link_page = new LinkPage();
                $link_page->id            = intval( $line['id'] );
                $link_page->anchor        = html_entity_decode( $line['anchor'], ENT_QUOTES, 'UTF-8' );
                $link_page->href          = html_entity_decode( $line['href'], ENT_QUOTES, 'UTF-8' );
                $link_page->internal_link = intval( $line['internal_link'] );
                $link_page->page_id       = intval( $line['page_id'] );
                $ar_links_page[] = $link_page;
            }

            $page->obj_links = $ar_links_page;
        }
    }

    protected function ModelCompare_Mysql_GetImagesPage( & $page )
    {
        $sel = "select * from ".$this->tb_images." where page_id=".$page->images;
        $res = mysql_query($sel,$this->link);

        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        else
        {
            $ar_images_page = null;
            while ($line = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $image_page = new ImagePage();
                $image_page->id               = intval( $line['id'] );
                $image_page->page_id          = intval( $line['page_id'] );
                $image_page->src              = html_entity_decode( $line['src'], ENT_QUOTES, 'UTF-8' );
                $image_page->title            = html_entity_decode( $line['title'], ENT_QUOTES, 'UTF-8' );
                $image_page->count_word_title = intval( $line['count_word_title'] );
                $image_page->alt              = html_entity_decode( $line['alt'], ENT_QUOTES, 'UTF-8' );
                $image_page->count_word_alt   = intval( $line['count_word_alt'] );
                $image_page->width            = intval( $line['width'] );
                $image_page->height           = intval( $line['height'] );
                $image_page->size             = intval( $line['size'] );

                $ar_images_page[] = $image_page;
            }

            $page->obj_images = $ar_images_page;
        }
    }

    protected function ModelCompare_Mysql_GetContentPage( & $page )
    {
        $sel = "select * from ".$this->tb_content." where id=".$page->content;
        $res = mysql_query($sel,$this->link);

        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        else
        {
            $content_page = null;
            while ($line = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $content_page = new ContentPage();
                $content_page->id             = intval( $line['id'] );
                $content_page->original_text  = html_entity_decode( $line['original_text'], ENT_QUOTES, 'UTF-8' );
                $content_page->text_words     = unserialize( html_entity_decode( $line['text_words'], ENT_QUOTES, 'UTF-8' ) );
                $content_page->count_words    = intval( $line['count_words'] );
                $content_page->count_symbols  = intval( $line['count_symbols'] );
            }

            $page->obj_content = $content_page;
        }
    }

    protected function ModelCompare_Mysql_GetTagsPage( Page & $page, $tag )
    {
        $sel = "select * from ".$tag." where page_id=".$page->$tag;
        $res = mysql_query($sel,$this->link);

        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        else
        {
            $ar_tags = null;
            while ($line = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $tag_page = new TagPage();
                $tag_page->id            = intval( $line['id'] );
                $tag_page->page_id       = intval( $line['page_id'] );
                $tag_page->info          = html_entity_decode( $line['info'], ENT_QUOTES, 'UTF-8' );
                $tag_page->count_words   = intval( $line['count_words'] );
                $tag_page->count_symbols = intval( $line['count_symbols'] );
                $ar_tags[] = $tag_page;
            }

            $obj = prefix_tag_obj.$tag;
            $page->$obj = $ar_tags;
        }
    }

    protected function ModelCompare_Mysql_GetObjectsOfMysql( & $page )
    {
        if($page->title)
        {
            $this->ModelCompare_Mysql_GetTitlePage($page);
        }
        if($page->keywords)
        {
            $this->ModelCompare_Mysql_GetKeywordsPage($page);
        }
        if($page->description)
        {
            $this->ModelCompare_Mysql_GetDescriptionPage($page);
        }
        if($page->links)
        {
            $this->ModelCompare_Mysql_GetLinksPage($page);
        }
        if($page->images)
        {
            $this->ModelCompare_Mysql_GetImagesPage($page);
        }
        if($page->content)
        {
            $this->ModelCompare_Mysql_GetContentPage($page);
        }

        if(count($this->ar_tags))
        {
            foreach($this->ar_tags as $tag)
            {
                if($this->MODEL_DEFINE_TAGS[$tag])
                {
                    if(isset($page->$tag) && $page->$tag)
                    {
                        $this->ModelCompare_Mysql_GetTagsPage($page, $tag);
                    }
                }
            }
        }
    }


    //-----------------добавляем свойства в обьект Page-----------------------------------------------
    protected function ModelAddPropertyInObjectPage( Page & $page )
    {
        if(count($this->ar_tags))
        {
            foreach($this->ar_tags as $property_tag)
            {
                $change              = prefix_tag_change . $property_tag;
                $obj                 = prefix_tag_obj . $property_tag;
                $page->$property_tag = 0;
                $page->$change       = 0;
                $page->$obj          = null;
            }
        }
    }

    //-----------------добавляем и инициализируем свойства в обьекте Page------------------------------
    protected function ModelAddInitPropertyInObjectPage( Page & $page , & $line )
    {
        if(count($this->ar_tags))
        {
            foreach($this->ar_tags as $property_tag)
            {
                $change_tag          = prefix_tag_change . $property_tag;
                $obj                 = prefix_tag_obj . $property_tag;
                $page->$property_tag = 0;
                $page->$change_tag   = 0;
                $page->$obj          = null;

                if(isset($line[$property_tag]))
                {
                    $page->$property_tag = intval( $line[$property_tag] );
                }

                if(isset($line[$change_tag]))
                {
                    $page->$change_tag = intval( $line[$change_tag] );
                }
            }
        }
    }


    //-----------------находим нужные елементы из html и формируем обьект Page $page_compare----------
    protected function ModelCompare_FindTitle( Page & $page_compare )
    {
        if(!$this->html) return;

        //<<получаем title из html-----------------------

        //работаем над поиском <title>
        if($this->html->innertext != '' && count($this->html->find('title')))
        {
            $title = $this->html->find('title');
            $title_text = $title[0]->plaintext;
            if($title_text)
            {
                $count_title_symbols = mb_strlen($title_text, 'UTF-8');
                $ar_title_words      = explode(' ', $title_text);
                $count_title_words   = count( $ar_title_words );

                //заполняем обьект
                $obj_title = new TitlePage();
                $obj_title->info          = $title_text;
                $obj_title->count_words   = $count_title_words;
                $obj_title->count_symbols = $count_title_symbols;

                $page_compare->obj_title = $obj_title;
            }
        }
        else if( $this->html->innertext != '' && count($this->html->find('meta')) )
        {
            $title_text = '';
            foreach($this->html->find('meta') as $meta)
            {
                if( isset($meta->name) && $meta->name == 'title' )
                {
                    $title_text = $meta->content;
                }
            }

            if($title_text)
            {
                $count_title_symbols = mb_strlen($title_text, 'UTF-8');
                $ar_title_words      = explode(' ', $title_text);
                $count_title_words   = count( $ar_title_words );

                //заполняем обьект
                $obj_title = new TitlePage();
                $obj_title->info          = $title_text;
                $obj_title->count_words   = $count_title_words;
                $obj_title->count_symbols = $count_title_symbols;

                $page_compare->obj_title = $obj_title;
            }
        }
        //>>end----------------------------------------------------
    }

    protected function ModelCompare_FindKeywordsDescription( Page & $page_compare )
    {
        if(!$this->html) return;

        //<<получаем мета-теги (keywords & description) из html-----------

        if( $this->html->innertext != '' && count($this->html->find('meta')) )
        {
            foreach($this->html->find('meta') as $meta)
            {
                if( (isset($meta->name) && $meta->name == 'keywords') || (isset($meta->name) && $meta->name == 'Keywords') )
                {
                    $tags[strtolower($meta->name)]= $meta->content;
                }
                else if( (isset($meta->name) && $meta->name == 'description') || (isset($meta->name) && $meta->name == 'Description'))
                {
                    $tags[strtolower($meta->name)]= $meta->content;
                }
            }
        }

        //формируем обьект keywords
        if(isset($tags['keywords']))
        {
            $keywords = $tags['keywords'];
            $count_keywords_symbols = mb_strlen( $keywords, 'UTF-8' );
            if( $count_keywords_symbols )
            {
                //$keywords_not_space     = str_replace( ' ', '', $keywords);
                //$ar_keywords            = explode( ',', $keywords_not_space );
                $ar_keywords            = explode( ',', $keywords );//считаем 1-keywords == слово или словосочитание между запятыми
                $count_keywords_word    = count( $ar_keywords );
                if( $count_keywords_word )
                {
                    //формируем обьект
                    $keywords_page = new KeywordsPage();
                    $keywords_page->info = $keywords;
                    $keywords_page->count_words = $count_keywords_word;
                    $keywords_page->count_symbols = $count_keywords_symbols;

                    $page_compare->obj_keywords = $keywords_page;
                }
            }
        }

        //формируем обьект description
        if(isset($tags['description']))
        {
            $description = $tags['description'];
            $count_description_symbols = mb_strlen( $description, 'UTF-8' );
            if( $count_description_symbols )
            {
                //$description_not_space     = str_replace( array('.',',','!','?',':'), '', $description);//убираем все символы препинания
                //$ar_description            = explode( ' ', $description_not_space );
                $ar_description            = explode( ' ', $description );//считаем слова разделённые пробелом (' ')
                $count_description_word    = count( $ar_description );
                if( $count_description_word )
                {
                    //формируем обьект
                    $description_page = new DescriptionPage();
                    $description_page->info = $description;
                    $description_page->count_words = $count_description_word;
                    $description_page->count_symbols = $count_description_symbols;

                    $page_compare->obj_description = $description_page;
                }
            }
        }
        //>>end----------------------------------------------------
    }

    protected function ModelCompare_FindLinks( Page & $page_compare )
    {
        if(!$this->html) return;

        //<<работаем над поиском <a>---------------------------------------------------------------
        //$ar_internal_links = array();//внутренние ссылки
        //$ar_external_links = array();//внешние ссылки

        if($this->html->innertext != '' && count($this->html->find('a')))
        {
            $ar_links_current_page    = $this->html->find('a');
            $count_links_current_page = count( $ar_links_current_page );

            if( $count_links_current_page > 0)
            {
                $ar_links_page = null;//создаём массив ссылок для занесения в $page_compare

                foreach($ar_links_current_page as $link)
                {
                    if(!$link->href)continue;

                    $link_page = new LinkPage();

                    $ar_components_link = parse_url( $link->href );
                    if( isset($ar_components_link['host']) && $ar_components_link['host'] == $this->host_site)
                    {//если в url есть хост и он == хосту сайта
                        /*if( !in_array($link->href, $ar_internal_links) )
                        {
                            $ar_internal_links[] = $link->href;
                        }*/
                    }
                    else if( !isset($ar_components_link['host']) && isset($ar_components_link['path']) )
                    {//если в url нет хоста, но есть /path сайта
                        /*if( !in_array($link->href, $ar_internal_links) )
                        {
                            $ar_internal_links[] = $link->href;
                        }*/
                    }
                    else if( !isset($ar_components_link['host']) && isset($ar_components_link['fragment']) )
                    {//если в url нет хоста, но есть #fragment сайта

                    }
                    else if( !isset($ar_components_link['host']) && !isset($ar_components_link['fragment']) && $link->href == '#')
                    {//если в url нет хоста, но есть #fragment сайта

                    }
                    else
                    {
                        /*if( !in_array($link->href, $ar_external_links) )
                        {
                            $ar_external_links[] = $link->href;
                        }*/
                        $link_page->href = $link->href;
                        $link_page->anchor = $link->plaintext;
                        $link_page->internal_link = 0;
                        //$link_page->page_id = ????

                        $ar_links_page[] = $link_page;
                        continue;
                    }

                    $link_page->href = $link->href;
                    $link_page->anchor = $link->plaintext;
                    $link_page->internal_link = 1;
                    //$link_page->page_id = ????

                    $ar_links_page[] = $link_page;
                }//end foreach

                if($ar_links_page)
                {
                    $page_compare->obj_links = $ar_links_page;
                }
            }
        }
        //>>end работаем над поиском <a>----------------------------------------------------------------------
    }

    protected function ModelCompare_FindImages( Page & $page_compare )
    {
        if(!$this->html) return;

        //<<работаем над поиском <img>---------------------------------------------
        if( $this->html->innertext != '' )
        {
            if($this->html->innertext != '' && count($this->html->find('img')))
            {
                $ar_images_page = null;//создаём массив image для занесения в $page_compare

                foreach($this->html->find('img') as $img)
                {
                    //считаем количество слов в title картинки, разделённые пробелом (' ')
                    if($img->title){
                        $ar_word_title_img       = explode( ' ', $img->title );
                        $count_word_title_img    = count( $ar_word_title_img );
                    }else{
                        $count_word_title_img = 0;
                    }
                    //считаем количество слов в alt картинки, разделённые пробелом (' ')
                    if($img->alt){
                        $ar_word_alt_img         = explode( ' ', $img->alt );
                        $count_word_alt_img      = count( $ar_word_alt_img );
                    }else{
                        $count_word_alt_img = 0;
                    }

                    $image = new ImagePage();
                    $image->src              = $img->src;
                    $image->alt              = $img->alt;
                    $image->title            = $img->title;
                    $image->count_word_alt   = $count_word_alt_img;
                    $image->count_word_title = $count_word_title_img;
                    $image->width            = intval($img->width);
                    $image->height           = intval($img->height);
                    //$image->size = ????? ещё нет такого функционала
                    //$image->page_id = ????

                    $ar_images_page[] = $image;
                }
                if($ar_images_page)
                {
                    $page_compare->obj_images = $ar_images_page;
                }
            }
        }
        //>>end----------------------------------------------------
    }

    protected function ModelCompare_FindContent( Page & $page_compare )
    {
        if(!$this->html) return;

        if( $this->html->innertext != '' )
        {
            $ar_text = array();
            $body = $this->html->find('body');
            if($body)
            {
                $original_text_body = $body[0]->plaintext;
                $count_words = 0;
                $count_symbols = 0;

                $this->ModelRecursionGetContent($body[0], $ar_text);

                if( count($ar_text) )
                {
                    foreach($ar_text as $str)
                    {
                        $count_symbols = $count_symbols + mb_strlen($str);
                        $count_words = $count_words + count(explode( ' ', $str ));
                    }

                    $content = new ContentPage();
                    $content->original_text = $original_text_body;
                    $content->text_words = $ar_text;
                    $content->count_words = $count_words;
                    $content->count_symbols = $count_symbols;

                    $page_compare->obj_content = $content;
                }
            }
        }
    }

    protected function ModelCompare_FindTags( Page & $page_compare )
    {
        if(!$this->html) return;

        if(count($this->ar_tags))
        {
            foreach($this->ar_tags as $tag)
            {
                if($this->MODEL_DEFINE_TAGS[$tag])
                {
                    if($this->html->innertext != '' && count($this->html->find($tag)))
                    {
                        $ar_tags = null;
                        foreach($this->html->find($tag) as $tag_elem)
                        {
                            $tag_page = new TagPage();

                            $tag_elem_text = $tag_elem->plaintext;
                            $tag_page->info = $tag_elem_text;

                            if($tag_elem_text)
                            {
                                $tag_elem_count_symbols = mb_strlen($tag_elem_text, 'UTF-8');
                                $tag_elem_ar_words      = explode(' ', $tag_elem_text);
                                $tag_elem_count_words   = count( $tag_elem_ar_words );

                                $tag_page->count_words  = $tag_elem_count_words;
                                $tag_page->count_symbols= $tag_elem_count_symbols;

                                $ar_tags[] = $tag_page;
                            }
                        }
                        $obj = prefix_tag_obj.$tag;
                        $page_compare->$obj = $ar_tags;
                    }
                }
            }
        }
    }

    //-----------------сравниваем обьекты $page и $page_compare---------------------------------------
    protected function ModelCompare_ComparingTitle( Page & $page, Page & $page_compare )
    {//возвращаем true - если обьекты равны, false - если обьекты НЕ равны
        if( is_object($page->obj_title) && is_object($page_compare->obj_title) )
        {//проверка - если оба обьекта являются обьектами, сравниваем их.
            if($page->obj_title->Compare( $page_compare->obj_title ))
                return true;
            else
                return false;
        }
        else if($page->obj_title == $page_compare->obj_title)
        {//проверка - если оба обьекта равные NULL
            return true;
        }
        else
            return false;
    }

    protected function ModelCompare_ComparingKeywords( Page & $page, Page & $page_compare )
    {//возвращаем true - если обьекты равны, false - если обьекты НЕ равны
        if( is_object($page->obj_keywords) && is_object($page_compare->obj_keywords) )
        {//проверка - если оба обьекта являются обьектами, сравниваем их.
            if($page->obj_keywords->Compare( $page_compare->obj_keywords ))
                return true;
            else
                return false;
        }
        else if($page->obj_keywords == $page_compare->obj_keywords)
        {//проверка - если оба обьекта равные NULL
            return true;
        }
        else
            return false;
    }

    protected function ModelCompare_ComparingDescription( Page & $page, Page & $page_compare )
    {//возвращаем true - если обьекты равны, false - если обьекты НЕ равны
        if( is_object($page->obj_description) && is_object($page_compare->obj_description) )
        {//проверка - если оба обьекта являются обьектами, сравниваем их.
            if($page->obj_description->Compare( $page_compare->obj_description ))
                return true;
            else
                return false;
        }
        else if($page->obj_description == $page_compare->obj_description)
        {//проверка - если оба обьекта равные NULL
            return true;
        }
        else
            return false;
    }

    protected function ModelCompare_ComparingContent( Page & $page, Page & $page_compare )
    {//возвращаем true - если обьекты равны, false - если обьекты НЕ равны
        if( is_object($page->obj_content) && is_object($page_compare->obj_content) )
        {//проверка - если оба обьекта являются обьектами, сравниваем их.
            if($page->obj_content->Compare( $page_compare->obj_content ))
                return true;
            else
                return false;
        }
        else if($page->obj_content == $page_compare->obj_content)
        {//проверка - если оба обьекта равные NULL
            return true;
        }
        else
            return false;
    }

    protected function ModelCompare_ComparingLinks( Page & $page, Page & $page_compare )
    {
        if( count($page->obj_links) != count($page_compare->obj_links) )
        {
            return false;
        }
        else if( count($page->obj_links) == 0 && count($page_compare->obj_links) == 0 )
        {
            return true;
        }

        for($i=0; $i < count($page->obj_links); $i++)
        {
            if( $page->obj_links[$i]->CompareObjectsInArray( $page_compare->obj_links ))
            {
                continue;
            }
            return false;//досрочно прерываем цикл, т.к. елемента $page->obj_links[$i] нет в массиве $page_compare->obj_links
        }
        return true;
    }

    protected function ModelCompare_ComparingImages( Page & $page, Page & $page_compare )
    {
        if( count($page->obj_images) != count($page_compare->obj_images) )
        {
            return false;
        }
        else if( count($page->obj_images) == 0 && count($page_compare->obj_images) == 0 )
        {
            return true;
        }

        for($i=0; $i < count($page->obj_images); $i++)
        {
            if( $page->obj_images[$i]->CompareObjectsInArray( $page_compare->obj_images ))
            {
                continue;
            }
            return false;//досрочно прерываем цикл, т.к. елемента $page->obj_images[$i] нет в массиве $page_compare->obj_images
        }
        return true;
    }

    protected function ModelCompare_ComparingTags( & $array_objects_tags_page, & $array_objects_tags_compare )
    {
        if( count($array_objects_tags_page) != count($array_objects_tags_compare) )
        {
            return false;
        }
        else if( count($array_objects_tags_page) == 0 && count($array_objects_tags_compare) == 0 )
        {
            return true;
        }

        for($i=0; $i < count($array_objects_tags_page); $i++)
        {
            if( $array_objects_tags_page[$i]->CompareObjectsInArray( $array_objects_tags_compare ))
            {
                continue;
            }
            return false;//досрочно прерываем цикл, т.к. елемента $page->obj_tags[$i] нет в массиве $page_compare->obj_tags
        }
        return true;
    }

    protected function ModelCompare_ComparingObjectsPage( Page & $page, Page & $page_compare )
    {
        $ar = array(
            //'id'              => false,
            //'url'             => false,
            'page_code'       => false,
            'http_code'       => false,
            'content_type'    => false,
            'redirect_url'    => false,
            'parent_url'      => false,
            'load_count'      => false,
            'load_timeout'    => false,
            //'title'           => false,
            //'keywords'        => false,
            //'description'     => false,
            'level_links'     => false,
            'total_time'      => false,
            'page_size'       => false,
            'page_speed'      => false,
            //'links'           => false,
            //'images'          => false,
            //'content'         => false,
            //'scan'            => false,
            'exist'           => false,
            //'create_page'     => false,
            //'update_page'     => false,
            //'delete_page'     => false,
            //'date_change_page'  => false,
            'obj_title'       => false,
            'obj_keywords'    => false,
            'obj_description' => false,
            'obj_links'       => false,
            'obj_images'      => false,
            'obj_content'     => false);

        if(count($this->ar_tags))
        {
            foreach($this->ar_tags as $k=>$v)
            {
                if($this->MODEL_DEFINE_TAGS[$v])
                {
                    $ar[$v] = false;
                }
            }
        }

        //$page->page_code != $page_compare->page_code       - общее изменение html-кода
        //$page->exist != $page_compare->exist               - существует запись в БД (ВАЖНО !!! - exist может устанавливаться только при создании == 1, и менятся при удалении == 0, всё остальное не может на него повлиять - даже если изменён тип страницы content_type == 404 | 302 | javascript | image | 28 и т.д. )
        //$page->http_code != $page_compare->http_code       - ответ сервера http (200 | 404 | 302 | 28 и т.д.)
        //$page->content_type != $page_compare->content_type - тип страницы (text/html | image | Operation Timeout-(28) | Redirect-(22) и т.д.)
        //$page->load_count != $page_compare->load_count     - количество попыток загрузки страницы - имеет важное значение! Так как страница может быть не изменена, а загружаться 10-раз с постоянно увеличивающимся таймаутом - Это НЕСТАБИЛЬНОСТЬ работы сервера !!!
        //$page->parent_url != $page_compare->parent_url     - родательская ссылка (страница)
        //$page->redirect_url != $page_compare->redirect_url - страница на которую делается redirect
        if( $page->page_code != $page_compare->page_code
            || $page->exist != $page_compare->exist
            || $page->http_code != $page_compare->http_code
            || $page->content_type != $page_compare->content_type
            || $page->load_count != $page_compare->load_count
            || $page->parent_url != $page_compare->parent_url
            || REDIRECT_COMPARE )
        {
            /*if($page->page_code != $page_compare->page_code)// !!!!!!! стоит ли обращать внимание на этот параметр при сравнении ???????????
            {
                $page_compare->change_page_code= 1;
                $ar['http_code']=true;
            }

            if($page->total_time != $page_compare->total_time)// !!!!!!! стоит ли обращать внимание на этот параметр при сравнении ???????????
            {
                $page_compare->change_total_time = 1;
                $ar['total_time']=true;
            }

            if($page->page_size != $page_compare->page_size)// !!!!!!! стоит ли обращать внимание на этот параметр при сравнении ???????????
            {
                $page_compare->change_page_size = 1;
                $ar['page_size']=true;
            }

            if($page->page_speed != $page_compare->page_speed)// !!!!!!! стоит ли обращать внимание на этот параметр при сравнении ???????????
            {
                $page_compare->change_page_speed = 1;
                $ar['page_speed']=true;
            }
            if($page->load_timeout != $page_compare->load_timeout)// !!!!!!! стоит ли обращать внимание на этот параметр при сравнении ???????????
            {
                $page_compare->change_load_timeout = 1;
                $ar['load_timeout']=true;
            }*/

            if($page->load_count != $page_compare->load_count)// !!!!!!! стоит ли обращать внимание на этот параметр при сравнении ???????????
            {
                $page_compare->change_load_count = 1;
                $ar['load_count']=true;
            }

            if($page->level_links != $page_compare->level_links)// !!!!!!! стоит ли обращать внимание на этот параметр при сравнении ???????????
            {
                $page_compare->change_level_links= 1;
                $ar['level_links']=true;
            }

            if($page->http_code != $page_compare->http_code)
            {
                $page_compare->change_http_code= 1;
                $ar['http_code']=true;
            }

            if($page->content_type != $page_compare->content_type)
            {
                $page_compare->change_content_type = 1;
                $ar['content_type']=true;
            }

            if(REDIRECT_COMPARE)
            {
                if($page->redirect_url != $page_compare->redirect_url)// !!!!!!! стоит ли обращать внимание на этот параметр при сравнении ???????????
                {
                    $page_compare->change_redirect_url = 1;
                    $ar['redirect_url']=true;
                }
            }

            if($page->parent_url != $page_compare->parent_url)// !!!!!!! стоит ли обращать внимание на этот параметр при сравнении ???????????
            {
                $page_compare->change_parent_url = 1;
                $ar['parent_url']=true;
            }

            //сравниваем обьекты
            if($this->ModelCompare_ComparingTitle($page, $page_compare ))
            {
                $ar['obj_title']=false;
            }
            else
            {
                $page_compare->change_title = 1;
                $ar['obj_title']=true;
            }

            if($this->ModelCompare_ComparingKeywords($page, $page_compare ))
            {
                $ar['obj_keywords']=false;
            }
            else
            {
                $page_compare->change_keywords = 1;
                $ar['obj_keywords']=true;
            }

            if($this->ModelCompare_ComparingDescription($page, $page_compare ))
            {
                $ar['obj_description']=false;
            }
            else
            {
                $page_compare->change_description = 1;
                $ar['obj_description']=true;
            }

            if($this->ModelCompare_ComparingContent($page, $page_compare ))
            {
                $ar['obj_content']=false;
            }
            else
            {
                $page_compare->change_content = 1;
                $ar['obj_content']=true;
            }

            //сравниваем массивы обьектов ссылок
            if($this->ModelCompare_ComparingLinks($page, $page_compare ))
            {
                $ar['obj_links']=false;
            }
            else
            {
                $page_compare->change_links = 1;
                $ar['obj_links']=true;
            }

            //сравниваем массивы обьектов images
            if($this->ModelCompare_ComparingImages($page, $page_compare ))
            {
                $ar['obj_images']=false;
            }
            else
            {
                $page_compare->change_images = 1;
                $ar['obj_images']=true;
            }

            if(count($this->ar_tags))
            {
                foreach($this->ar_tags as $k=>$tag)
                {
                    if($this->MODEL_DEFINE_TAGS[$tag])
                    {
                        $obj = prefix_tag_obj . $tag;

                        if($this->ModelCompare_ComparingTags($page->$obj, $page_compare->$obj))
                        {
                            $ar[$tag] = false;
                        }
                        else
                        {
                            $change = prefix_tag_change.$tag;
                            $page_compare->$change = 1;
                            $ar[$tag] = true;
                        }
                    }
                }
            }

            if($page->exist && !$page_compare->exist)
            {
                $page_compare->create_page = 0;
                $page_compare->update_page = 0;
                $page_compare->delete_page = 1;
                $page_compare->change_exist = 1;
                $ar['exist']=true;
            }
            elseif(!$page->exist && $page_compare->exist)
            {
                $page_compare->create_page = 1;
                $page_compare->update_page = 0;
                $page_compare->delete_page = 0;
                $page_compare->change_exist = 1;
                $ar['exist']=true;
            }
            else
            {//этот блок кода обязателен, особенно если раскоментировать сравнение
                //if($page->page_code != $page_compare->page_code) - в начале метода !!!
                //если были изменения на странице
                if( in_array(true, $ar) )
                {
                    $page_compare->create_page = 0;
                    $page_compare->update_page = 1;
                    $page_compare->delete_page = 0;
                }
            }
        }

        //если были изменения на странице
        if( in_array(true, $ar) )
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //-----------------сохранение обьекта $page в табл. tb_pages_archive------------------------------
    protected function ModelCompare_Save_TitlePage_to_TbTitleArchive( Page & $page )
    {
        $title_archive_id = null;

        if($page->obj_title)
        {
            //формируем запрос
            $ins = "insert into ".$this->tb_title_archive."( info, count_words, count_symbols ) values ('".addslashes(htmlentities($page->obj_title->info , ENT_QUOTES, 'UTF-8' ))."', ".$page->obj_title->count_words.", ".$page->obj_title->count_symbols.")";

            if(mysql_query( $ins, $this->link ))
            {
                if(SHOW_ECHO)
                    Log::write( "<p> obj_title занесён в БД !</p>", $this->host_site );
                $title_archive_id = mysql_insert_id();
            }
            else
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
        }
        return $title_archive_id;
    }

    protected function ModelCompare_Save_KeywordsPage_to_TbKeywordsArchive( Page & $page )
    {
        $keywords_archive_id = null;

        if($page->obj_keywords)
        {
            //формируем запрос
            $ins = "insert into ".$this->tb_keywords_archive."( info, count_words, count_symbols ) values ('".addslashes(htmlentities($page->obj_keywords->info , ENT_QUOTES, 'UTF-8' ))."', ".$page->obj_keywords->count_words.", ".$page->obj_keywords->count_symbols.")";

            if(mysql_query( $ins, $this->link ))
            {
                if(SHOW_ECHO)
                    Log::write( "<p> obj_keywords занесён в БД !</p>", $this->host_site );
                $keywords_archive_id = mysql_insert_id();
            }
            else
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
        }
        return $keywords_archive_id;
    }

    protected function ModelCompare_Save_DescriptionPage_to_TbDescriptionArchive( Page & $page )
    {
        $description_archive_id = null;

        if($page->obj_description)
        {
            //формируем запрос
            $ins = "insert into ".$this->tb_description_archive."( info, count_words, count_symbols ) values ('".addslashes(htmlentities($page->obj_description->info , ENT_QUOTES, 'UTF-8' ))."', ".$page->obj_description->count_words.", ".$page->obj_description->count_symbols.")";

            if(mysql_query( $ins, $this->link ))
            {
                if(SHOW_ECHO)
                    Log::write( "<p> obj_description занесён в БД !</p>", $this->host_site );
                $description_archive_id = mysql_insert_id();
            }
            else
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
        }
        return $description_archive_id;
    }

    protected function ModelCompare_Save_LinksPage_to_TbLinksArchive( Page & $page, $page_archive_id )
    {
        if($count_l = count($page->obj_links))
        {
            for($i=0; $i<$count_l; $i++)
            {
                //формируем запрос
                $ins = "insert into ".$this->tb_links_archive."( page_id, internal_link, href, anchor ) values (".$page_archive_id.", "
                    .$page->obj_links[$i]->internal_link.", '".addslashes(htmlentities($page->obj_links[$i]->href , ENT_QUOTES, 'UTF-8' ))."', '"
                    .addslashes(htmlentities($page->obj_links[$i]->anchor , ENT_QUOTES, 'UTF-8' ))."')";

                if(mysql_query( $ins, $this->link ))
                {
                    if(SHOW_ECHO)
                        Log::write( "<p> link занесён в ".$this->tb_links_archive." !</p>", $this->host_site );
                }
                else
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
            }
            return $page_archive_id;
        }
        return null;
    }

    protected function ModelCompare_Save_ImagesPage_to_TbImagesArchive( Page & $page, $page_archive_id )
    {
        if($count_i = count($page->obj_images))
        {
            for($i=0; $i<$count_i; $i++)
            {
                //формируем запрос
                $ins = "insert into ".$this->tb_images_archive."( page_id, src, title, count_word_title, alt, count_word_alt, width, height ) values ("
                    .$page_archive_id.", '".addslashes(htmlentities($page->obj_images[$i]->src , ENT_QUOTES, 'UTF-8' ))."', '"
                    .addslashes(htmlentities($page->obj_images[$i]->title , ENT_QUOTES, 'UTF-8' ))."', "
                    .$page->obj_images[$i]->count_word_title.", '".
                    addslashes(htmlentities($page->obj_images[$i]->alt , ENT_QUOTES, 'UTF-8' ))."', "
                    .$page->obj_images[$i]->count_word_alt.", ".
                    $page->obj_images[$i]->width.", ".$page->obj_images[$i]->height.")";

                if(mysql_query( $ins, $this->link ))
                {
                    if(SHOW_ECHO)
                        Log::write( "<p> image занесён в ".$this->tb_images_archive." !</p>", $this->host_site );
                }
                else
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
            }
            return $page_archive_id;
        }
        return null;
    }

    protected function ModelCompare_Save_ContentPage_to_TbContentArchive( Page & $page )
    {
        $content_archive_id = null;

        if($page->obj_content)
        {
            //формируем запрос
            $ins = "insert into ".$this->tb_content_archive."( original_text, text_words, count_words, count_symbols ) values ('"
                .addslashes(htmlentities($page->obj_content->original_text , ENT_QUOTES, 'UTF-8' ))."', '"
                .htmlentities(serialize($page->obj_content->text_words) , ENT_QUOTES, 'UTF-8' )."', "
                .$page->obj_content->count_words.", ".$page->obj_content->count_symbols.")";

            if(mysql_query( $ins, $this->link ))
            {
                if(SHOW_ECHO)
                    Log::write( "<p> obj_content занесён в БД !</p>", $this->host_site );
                $content_archive_id = mysql_insert_id();
            }
            else
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno().", ".mysql_error()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                Log::write( $ins, $this->host_site );
                exit();
            }
        }
        return $content_archive_id;
    }

    protected function ModelCompare_Save_TagsPage_to_TbTagsArchive( Page & $page, $page_archive_id )
    {
        $ar_tags_archive_id = null;//заносим $page_archive_id для каждой таблицы тегов или NULL !!!
        if(count($this->ar_tags))
        {
            foreach($this->ar_tags as $tag)
            {
                if($this->MODEL_DEFINE_TAGS[$tag])
                {
                    $obj = prefix_tag_obj . $tag;
                    if($count_tags = count($page->$obj))
                    {
                        for($i=0; $i<$count_tags; $i++)
                        {
                            $tag_archive = $tag . postfix_tag_archive;

                            // *********** ВАЖНО !!!! **************
                            //http://www.php.su/learnphp/vars/?varsvars
                            //Переменные переменные (символические ссылки)
                            //$page->$obj[$i]->info - НЕ РАБОТАЕТ !!!
                            //нужно $page->{$obj}[$i]->info       !!!

                            //формируем запрос
                            $ins = "insert into ".$tag_archive."( page_id, info, count_words, count_symbols ) values ("
                                .$page_archive_id
                                .", '".addslashes(htmlentities($page->{$obj}[$i]->info , ENT_QUOTES, 'UTF-8' ))."', "
                                .$page->{$obj}[$i]->count_words.", "
                                .$page->{$obj}[$i]->count_symbols.")";

                            if(mysql_query( $ins, $this->link ))
                            {
                                if(SHOW_ECHO)
                                    Log::write( "<p> link занесён в ".$tag_archive." !</p>", $this->host_site );
                            }
                            else
                            {
                                if(SHOW_ECHO_ERROR)
                                    Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                exit();
                            }
                        }
                        $ar_tags_archive_id[$tag] = $page_archive_id;
                    }
                    else
                    {
                        $ar_tags_archive_id[$tag] = null;
                    }
                }
            }
        }
        return $ar_tags_archive_id;
    }
    //заносим обьект $page в табл. tb_pages_archive
    protected function ModelCompare_Save_Page_to_TbPageArchive( Page & $page )
    {
        //формируем запрос
        $ins = "insert into ".$this->tb_pages_archive."( page_id, url ) values ( ".$page->id.", '".addslashes(htmlentities($page->url , ENT_QUOTES, 'UTF-8' ))."' )";

        if(mysql_query( $ins, $this->link ))
        {
            if(SHOW_ECHO) Log::write( "<p> obj_title занесён в БД !</p>", $this->host_site );

            $page_archive_id = mysql_insert_id();

            $title_archive_id = $this->ModelCompare_Save_TitlePage_to_TbTitleArchive($page);
            $keywords_archive_id = $this->ModelCompare_Save_KeywordsPage_to_TbKeywordsArchive($page);
            $description_archive_id = $this->ModelCompare_Save_DescriptionPage_to_TbDescriptionArchive($page);
            $links_archive_id = $this->ModelCompare_Save_LinksPage_to_TbLinksArchive($page, $page_archive_id);
            $images_archive_id = $this->ModelCompare_Save_ImagesPage_to_TbImagesArchive($page, $page_archive_id);
            $content_archive_id = $this->ModelCompare_Save_ContentPage_to_TbContentArchive($page);

            //TAGS
            $ar_tags_archive_id = $this->ModelCompare_Save_TagsPage_to_TbTagsArchive($page, $page_archive_id);

            $s1 = ", ";
            $s2 = "=";
            $s_res = '';
            $ch_res = '';
            if(count($ar_tags_archive_id))
            {
                foreach($ar_tags_archive_id as $tag => $val)
                {
                    $s_res .= $s1 . $tag . $s2 . ($val?$val:'NULL');

                    $ch     = prefix_tag_change . $tag;
                    $ch_res .= $s1 . $ch . $s2 . $page->$ch;
                }
            }

            $upd = "update ".$this->tb_pages_archive
                ." set page_code='".addslashes(htmlentities($page->page_code , ENT_QUOTES, 'UTF-8' ))
                ."', http_code=".$page->http_code
                .", content_type='".addslashes(htmlentities($page->content_type , ENT_QUOTES, 'UTF-8' ))
                ."', redirect_url=".($page->redirect_url?("'".addslashes(htmlentities($page->redirect_url , ENT_QUOTES, 'UTF-8' ))."'"):'NULL')
                .", parent_url=".($page->parent_url?("'".addslashes(htmlentities($page->parent_url , ENT_QUOTES, 'UTF-8' ))."'"):'NULL')
                .", load_count=".$page->load_count
                .", load_timeout=".$page->load_timeout
                .", title=".($title_archive_id?$title_archive_id:'NULL')
                .", keywords=".($keywords_archive_id?$keywords_archive_id:'NULL')
                .", description=".($description_archive_id?$description_archive_id:'NULL')
                .", level_links=".$page->level_links
                .", connect_time=".$page->connect_time
                .", total_time=".$page->total_time
                .", page_size=".$page->page_size
                .", page_speed=".$page->page_speed
                .$s_res
                .", links=".($links_archive_id?$links_archive_id:'NULL')
                .", images=".($images_archive_id?$images_archive_id:'NULL')
                .", content=".($content_archive_id?$content_archive_id:'NULL')
                .", scan=".$page->scan
                .", exist=".$page->exist
                .", create_page=".$page->create_page
                .", update_page=".$page->update_page
                .", delete_page=".$page->delete_page
                .", date_change_page='".$page->date_change_page
                ."', change_page_code=".$page->change_page_code
                .", change_http_code=".$page->change_http_code
                .", change_content_type=".$page->change_content_type
                .", change_redirect_url=".$page->change_redirect_url
                .", change_parent_url=".$page->change_parent_url
                .", change_load_count=".$page->change_load_count
                .", change_load_timeout=".$page->change_load_timeout
                .", change_title=".$page->change_title
                .", change_keywords=".$page->change_keywords
                .", change_description=".$page->change_description
                .", change_level_links=".$page->change_level_links
                .", change_total_time=".$page->change_total_time
                .", change_page_size=".$page->change_page_size
                .", change_page_speed=".$page->change_page_speed
                .$ch_res
                .", change_links=".$page->change_links
                .", change_images=".$page->change_images
                .", change_content=".$page->change_content
                .", change_exist=".$page->change_exist
                ." where id=".$page_archive_id;

            if(mysql_query( $upd, $this->link ))
            {
                if(SHOW_ECHO)
                {
                    Log::write( "<p>таблица ".$this->tb_pages_archive." обновлена !</p>", $this->host_site );
                }
            }
            else
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
        }
        else
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
    }

    //-----------------обновление строки в табл. tb_pages обьектом $page_compare---------------------------------------
    protected function ModelCompare_Update_TitlePage_itPageCompare( Page & $page, Page & $page_compare )
    {
        if($page->title)
        {
            if($page_compare->obj_title)
            {
                $upd = "update ".$this->tb_title." set info='".addslashes(htmlentities($page_compare->obj_title->info , ENT_QUOTES, 'UTF-8' ))
                    ."', count_words=".$page_compare->obj_title->count_words
                    .",  count_symbols=".$page_compare->obj_title->count_symbols
                    ." where id=".$page->title;

                if(!mysql_query( $upd, $this->link ))
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
            }
            else
            {
                $upd = "update ".$this->tb_pages." set title=NULL where id=".$page->id;
                if(!mysql_query( $upd, $this->link ))
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
                $del = "delete from ".$this->tb_title." where id=".$page->title;
                if(!mysql_query( $del, $this->link ))
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при удалении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
            }
        }
        else
        {
            if($page_compare->obj_title)
            {
                //формируем запрос
                $ins = "insert into ".$this->tb_title
                    ."( info, count_words, count_symbols ) values ('"
                    .addslashes(htmlentities($page_compare->obj_title->info , ENT_QUOTES, 'UTF-8' ))
                    ."', ".$page_compare->obj_title->count_words.", "
                    .$page_compare->obj_title->count_symbols.")";

                if(!mysql_query( $ins, $this->link ))
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }

                $upd = "update ".$this->tb_pages." set title=".mysql_insert_id()." where id=".$page->id;
                if(!mysql_query( $upd, $this->link ))
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
            }
            else
                return;
        }
    }

    protected function ModelCompare_Update_KeywordsPage_itPageCompare( Page & $page, Page & $page_compare )
    {
        if($page->keywords)
        {
            if($page_compare->obj_keywords)
            {
                $upd = "update ".$this->tb_keywords
                    ." set info='".addslashes(htmlentities($page_compare->obj_keywords->info , ENT_QUOTES, 'UTF-8' ))
                    ."', count_words=".$page_compare->obj_keywords->count_words
                    .",  count_symbols=".$page_compare->obj_keywords->count_symbols
                    ." where id=".$page->keywords;

                if(!mysql_query( $upd, $this->link ))
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
            }
            else
            {
                $upd = "update ".$this->tb_pages." set keywords=NULL where id=".$page->id;
                if(!mysql_query( $upd, $this->link ))
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
                $del = "delete from ".$this->tb_keywords." where id=".$page->keywords;
                if(!mysql_query( $del, $this->link ))
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при удалении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
            }
        }else
        {
            if($page_compare->obj_keywords)
            {
                //формируем запрос
                $ins = "insert into ".$this->tb_keywords
                    ."( info, count_words, count_symbols ) values ('"
                    .addslashes(htmlentities($page_compare->obj_keywords->info , ENT_QUOTES, 'UTF-8' ))
                    ."', ".$page_compare->obj_keywords->count_words.", "
                    .$page_compare->obj_keywords->count_symbols.")";

                if(!mysql_query( $ins, $this->link ))
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }

                $upd = "update ".$this->tb_pages." set keywords=".mysql_insert_id()." where id=".$page->id;
                if(!mysql_query( $upd, $this->link ))
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
            }
            else
                return;
        }
    }

    protected function ModelCompare_Update_DescriptionPage_itPageCompare( Page & $page, Page & $page_compare )
    {
        if($page->description)
        {
            if($page_compare->obj_description)
            {
                $upd = "update ".$this->tb_description
                    ." set info='".addslashes(htmlentities($page_compare->obj_description->info , ENT_QUOTES, 'UTF-8' ))
                    ."', count_words=".$page_compare->obj_description->count_words
                    .",  count_symbols=".$page_compare->obj_description->count_symbols
                    ." where id=".$page->description;

                if(!mysql_query( $upd, $this->link ))
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
            }
            else
            {
                $upd = "update ".$this->tb_pages." set description=NULL where id=".$page->id;
                if(!mysql_query( $upd, $this->link ))
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
                $del = "delete from ".$this->tb_description." where id=".$page->description;
                if(!mysql_query( $del, $this->link ))
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при удалении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
            }
        }else
        {
            if($page_compare->obj_description)
            {
                //формируем запрос
                $ins = "insert into ".$this->tb_description
                    ."( info, count_words, count_symbols ) values ('"
                    .addslashes(htmlentities($page_compare->obj_description->info , ENT_QUOTES, 'UTF-8' ))
                    ."', ".$page_compare->obj_description->count_words.", "
                    .$page_compare->obj_description->count_symbols.")";

                if(!mysql_query( $ins, $this->link ))
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }

                $upd = "update ".$this->tb_pages." set description=".mysql_insert_id()." where id=".$page->id;
                if(!mysql_query( $upd, $this->link ))
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
            }
            else
                return;
        }
    }

    protected function ModelCompare_Update_LinksPage_itPageCompare( Page & $page, Page & $page_compare )
    {
        if($page->links && $page_compare->obj_links)
        {
            if((count($page->obj_links) == count($page_compare->obj_links)) && (count($page->obj_links) != 0))
            {
                $count = count($page->obj_links);
                for($i=0; $i < $count; $i++)
                {
                    $upd = "update ".$this->tb_links." set internal_link=".$page_compare->obj_links[$i]->internal_link
                        .", href='".addslashes(htmlentities($page_compare->obj_links[$i]->href , ENT_QUOTES, 'UTF-8'))
                        ."', anchor='".addslashes(htmlentities($page_compare->obj_links[$i]->anchor , ENT_QUOTES, 'UTF-8'))."' where id=".$page->obj_links[$i]->id;
                    if(!mysql_query( $upd, $this->link ))
                    {
                        if(SHOW_ECHO_ERROR)
                            Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                        exit();
                    }
                }
            }
            else if( count($page->obj_links) > count($page_compare->obj_links) )
            {
                $count = count($page->obj_links);
                $count_page_compare = count($page_compare->obj_links);
                for($i=0; $i < $count; $i++)
                {
                    if($i < $count_page_compare)
                    {
                        $upd = "update ".$this->tb_links." set internal_link=".$page_compare->obj_links[$i]->internal_link
                            .", href='".addslashes(htmlentities($page_compare->obj_links[$i]->href , ENT_QUOTES, 'UTF-8'))
                            ."', anchor='".addslashes(htmlentities($page_compare->obj_links[$i]->anchor , ENT_QUOTES, 'UTF-8'))."' where id=".$page->obj_links[$i]->id;
                        if(!mysql_query( $upd, $this->link ))
                        {
                            if(SHOW_ECHO_ERROR)
                                Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                            exit();
                        }
                    }
                    else
                    {
                        $del = "delete from ".$this->tb_links." where id=".$page->obj_links[$i]->id;
                        if(!mysql_query( $del, $this->link ))
                        {
                            if(SHOW_ECHO_ERROR)
                                Log::write( "<p> ошибка ".mysql_errno()." при удалении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                            exit();
                        }
                    }
                }
            }
            else if( count($page->obj_links) < count($page_compare->obj_links) )
            {
                $count = count($page_compare->obj_links);
                $count_page = count($page->obj_links);
                for($i=0; $i < $count; $i++)
                {
                    if($i < $count_page)
                    {
                        $upd = "update ".$this->tb_links." set internal_link=".$page_compare->obj_links[$i]->internal_link
                            .", href='".addslashes(htmlentities($page_compare->obj_links[$i]->href , ENT_QUOTES, 'UTF-8'))
                            ."', anchor='".addslashes(htmlentities($page_compare->obj_links[$i]->anchor , ENT_QUOTES, 'UTF-8'))."' where id=".$page->obj_links[$i]->id;
                        if(!mysql_query( $upd, $this->link ))
                        {
                            if(SHOW_ECHO_ERROR)
                                Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                            exit();
                        }
                    }
                    else
                    {
                        $ins = "insert into ".$this->tb_links
                            ."( page_id, internal_link, href, anchor ) value ( ".$page->id
                            .", ".$page_compare->obj_links[$i]->internal_link
                            .", '".addslashes(htmlentities($page_compare->obj_links[$i]->href , ENT_QUOTES, 'UTF-8'))
                            ."', '".addslashes(htmlentities($page_compare->obj_links[$i]->anchor , ENT_QUOTES, 'UTF-8'))."' )";
                        if(!mysql_query( $ins, $this->link ))
                        {
                            if(SHOW_ECHO_ERROR)
                                Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                            exit();
                        }
                    }
                }
            }
        }
        else if(!$page->links && $page_compare->obj_links)
        {
            $count = count($page_compare->obj_links);
            for($i=0; $i < $count; $i++)
            {
                $ins = "insert into ".$this->tb_links
                    ."( page_id, internal_link, href, anchor ) value ( ".$page->id
                    .", ".$page_compare->obj_links[$i]->internal_link
                    .", '".addslashes(htmlentities($page_compare->obj_links[$i]->href , ENT_QUOTES, 'UTF-8'))
                    ."', '".addslashes(htmlentities($page_compare->obj_links[$i]->anchor , ENT_QUOTES, 'UTF-8'))."' )";
                if(!mysql_query( $ins, $this->link ))
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
            }
            $upd = "update ".$this->tb_pages." set links=".$page->id." where id=".$page->id;
            if(!mysql_query( $upd, $this->link ))
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
        }
        else if($page->links && !$page_compare->obj_links)
        {
            $upd = "update ".$this->tb_pages." set links=NULL where id=".$page->id;
            if(!mysql_query( $upd, $this->link ))
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }

            $count = count($page->obj_links);
            for($i=0; $i < $count; $i++)
            {
                $del = "delete from ".$this->tb_links." where id=".$page->obj_links[$i]->id;
                if(!mysql_query( $del, $this->link ))
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при удалении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
            }
        }
    }

    protected function ModelCompare_Update_ImagesPage_itPageCompare( Page & $page, Page & $page_compare )
    {
        if($page->images && $page_compare->obj_images)
        {
            if((count($page->obj_images) == count($page_compare->obj_images)) && (count($page->obj_images) != 0))
            {
                $count = count($page->obj_images);
                for($i=0; $i < $count; $i++)
                {
                    $upd = "update ".$this->tb_images." set src='"
                        .addslashes(htmlentities( $page_compare->obj_images[$i]->src , ENT_QUOTES, 'UTF-8'))
                        ."', title='".addslashes(htmlentities( $page_compare->obj_images[$i]->title , ENT_QUOTES, 'UTF-8'))
                        ."', count_word_title=".$page_compare->obj_images[$i]->count_word_title
                        .", alt='".addslashes(htmlentities( $page_compare->obj_images[$i]->alt , ENT_QUOTES, 'UTF-8'))
                        ."', count_word_alt=".$page_compare->obj_images[$i]->count_word_alt
                        .", width=".$page_compare->obj_images[$i]->width
                        .", height=".$page_compare->obj_images[$i]->height
                        .", size=".$page_compare->obj_images[$i]->size." where id=".$page->obj_images[$i]->id;
                    if(!mysql_query( $upd, $this->link ))
                    {
                        if(SHOW_ECHO_ERROR)
                            Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                        exit();
                    }
                }
            }
            else if( count($page->obj_images) > count($page_compare->obj_images) )
            {
                $count = count($page->obj_images);
                $count_page_compare = count($page_compare->obj_images);
                for($i=0; $i < $count; $i++)
                {
                    if($i < $count_page_compare)
                    {
                        $upd = "update ".$this->tb_images." set src='"
                            .addslashes(htmlentities( $page_compare->obj_images[$i]->src , ENT_QUOTES, 'UTF-8'))
                            ."', title='".addslashes(htmlentities( $page_compare->obj_images[$i]->title , ENT_QUOTES, 'UTF-8'))
                            ."', count_word_title=".$page_compare->obj_images[$i]->count_word_title
                            .", alt='".addslashes(htmlentities( $page_compare->obj_images[$i]->alt , ENT_QUOTES, 'UTF-8'))
                            ."', count_word_alt=".$page_compare->obj_images[$i]->count_word_alt
                            .", width=".$page_compare->obj_images[$i]->width
                            .", height=".$page_compare->obj_images[$i]->height
                            .", size=".$page_compare->obj_images[$i]->size." where id=".$page->obj_images[$i]->id;
                        if(!mysql_query( $upd, $this->link ))
                        {
                            if(SHOW_ECHO_ERROR)
                                Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                            exit();
                        }
                    }
                    else
                    {
                        $del = "delete from ".$this->tb_images." where id=".$page->obj_images[$i]->id;
                        if(!mysql_query( $del, $this->link ))
                        {
                            if(SHOW_ECHO_ERROR)
                                Log::write( "<p> ошибка ".mysql_errno()." при удалении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                            exit();
                        }
                    }
                }
            }
            else if( count($page->obj_images) < count($page_compare->obj_images) )
            {
                $count = count($page_compare->obj_images);
                $count_page = count($page->obj_images);
                for($i=0; $i < $count; $i++)
                {
                    if($i < $count_page)
                    {
                        $upd = "update ".$this->tb_images." set src='"
                            .addslashes(htmlentities( $page_compare->obj_images[$i]->src , ENT_QUOTES, 'UTF-8'))
                            ."', title='".addslashes(htmlentities( $page_compare->obj_images[$i]->title , ENT_QUOTES, 'UTF-8'))
                            ."', count_word_title=".$page_compare->obj_images[$i]->count_word_title
                            .", alt='".addslashes(htmlentities( $page_compare->obj_images[$i]->alt , ENT_QUOTES, 'UTF-8'))
                            ."', count_word_alt=".$page_compare->obj_images[$i]->count_word_alt
                            .", width=".$page_compare->obj_images[$i]->width
                            .", height=".$page_compare->obj_images[$i]->height
                            .", size=".$page_compare->obj_images[$i]->size." where id=".$page->obj_images[$i]->id;
                        if(!mysql_query( $upd, $this->link ))
                        {
                            if(SHOW_ECHO_ERROR)
                                Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                            exit();
                        }
                    }
                    else
                    {
                        $ins = "insert into ".$this->tb_images
                            ."( page_id, src, title, count_word_title, alt, count_word_alt, width, height, size ) value ( ".$page->id
                            .", '".addslashes(htmlentities($page_compare->obj_images[$i]->src , ENT_QUOTES, 'UTF-8'))
                            ."', '".addslashes(htmlentities($page_compare->obj_images[$i]->title , ENT_QUOTES, 'UTF-8'))
                            ."', ".$page_compare->obj_images[$i]->count_word_title
                            .", '".addslashes(htmlentities($page_compare->obj_images[$i]->alt , ENT_QUOTES, 'UTF-8'))
                            ."', ".$page_compare->obj_images[$i]->count_word_alt
                            .", ".$page_compare->obj_images[$i]->width
                            .", ".$page_compare->obj_images[$i]->height
                            .", ".$page_compare->obj_images[$i]->size
                            ." )";
                        if(!mysql_query( $ins, $this->link ))
                        {
                            if(SHOW_ECHO_ERROR)
                                Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                            exit();
                        }
                    }
                }
            }
        }
        else if(!$page->images && $page_compare->obj_images)
        {
            $count = count($page_compare->obj_images);
            for($i=0; $i < $count; $i++)
            {
                $ins = "insert into ".$this->tb_images
                    ."( page_id, src, title, count_word_title, alt, count_word_alt, width, height, size ) value ( ".$page->id
                    .", '".addslashes(htmlentities($page_compare->obj_images[$i]->src , ENT_QUOTES, 'UTF-8'))
                    ."', '".addslashes(htmlentities($page_compare->obj_images[$i]->title , ENT_QUOTES, 'UTF-8'))
                    ."', ".$page_compare->obj_images[$i]->count_word_title
                    .", '".addslashes(htmlentities($page_compare->obj_images[$i]->alt , ENT_QUOTES, 'UTF-8'))
                    ."', ".$page_compare->obj_images[$i]->count_word_alt
                    .", ".$page_compare->obj_images[$i]->width
                    .", ".$page_compare->obj_images[$i]->height
                    .", ".$page_compare->obj_images[$i]->size
                    ." )";
                if(!mysql_query( $ins, $this->link ))
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
            }
            $upd = "update ".$this->tb_pages." set images=".$page->id." where id=".$page->id;
            if(!mysql_query( $upd, $this->link ))
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
        }
        else if($page->images && !$page_compare->obj_images)
        {
            $upd = "update ".$this->tb_pages." set images=NULL where id=".$page->id;
            if(!mysql_query( $upd, $this->link ))
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }

            $count = count($page->obj_images);
            for($i=0; $i < $count; $i++)
            {
                $del = "delete from ".$this->tb_images." where id=".$page->obj_images[$i]->id;
                if(!mysql_query( $del, $this->link ))
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при удалении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
            }
        }
    }

    protected function ModelCompare_Update_ContentPage_itPageCompare( Page & $page, Page & $page_compare )
    {
        if($page->content)
        {
            if($page_compare->obj_content)
            {
                $upd = "update ".$this->tb_content
                    ." set original_text='".addslashes(htmlentities($page_compare->obj_content->original_text , ENT_QUOTES, 'UTF-8' ))
                    ."', text_words='".htmlentities( serialize($page_compare->obj_content->text_words) , ENT_QUOTES, 'UTF-8' )
                    ."', count_words=".$page_compare->obj_content->count_words
                    .", count_symbols=".$page_compare->obj_content->count_symbols
                    ." where id=".$page->content;

                if(!mysql_query( $upd, $this->link ))
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
            }
            else
            {
                $upd = "update ".$this->tb_pages." set content=NULL where id=".$page->id;
                if(!mysql_query( $upd, $this->link ))
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
                $del = "delete from ".$this->tb_content." where id=".$page->content;
                if(!mysql_query( $del, $this->link ))
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при удалении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
            }
        }else
        {
            if($page_compare->obj_content)
            {
                //формируем запрос
                $ins = "insert into ".$this->tb_content
                    ."( original_text, text_words, count_words, count_symbols ) values ('"
                    .addslashes(htmlentities( $page_compare->obj_content->original_text , ENT_QUOTES, 'UTF-8' ))
                    ."', '".htmlentities( serialize($page_compare->obj_content->original_text) , ENT_QUOTES, 'UTF-8' )
                    ."', ".$page_compare->obj_content->count_words.", "
                    .$page_compare->obj_content->count_symbols.")";

                if(!mysql_query( $ins, $this->link ))
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }

                $upd = "update ".$this->tb_pages." set content=".mysql_insert_id()." where id=".$page->id;
                if(!mysql_query( $upd, $this->link ))
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
            }
            else
                return;
        }
    }

    protected function ModelCompare_Update_TagsPage_itPageCompare( Page & $page, Page & $page_compare )
    {
        if(count($this->ar_tags))
        {
            foreach($this->ar_tags as $tag)
            {
                if($this->MODEL_DEFINE_TAGS[$tag])
                {
                    $obj = prefix_tag_obj . $tag;
                    if($page->$tag && $page_compare->$obj)
                    {
                        if((count($page->$obj) == count($page_compare->$obj)) && (count($page->$obj) != 0))
                        {
                            $count = count($page->$obj);
                            for($i=0; $i < $count; $i++)
                            {
                                $upd = "update ".$tag." set info='".addslashes(htmlentities($page_compare->{$obj}[$i]->info , ENT_QUOTES, 'UTF-8'))
                                    ."', count_words=".$page_compare->{$obj}[$i]->count_words
                                    .", count_symbols=".$page_compare->{$obj}[$i]->count_symbols." where id=".$page->{$obj}[$i]->id;
                                if(!mysql_query( $upd, $this->link ))
                                {
                                    if(SHOW_ECHO_ERROR)
                                        Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                    exit();
                                }
                            }
                        }
                        elseif( count($page->$obj) > count($page_compare->$obj) )
                        {
                            $count = count($page->$obj);
                            $count_page_compare = count($page_compare->$obj);
                            for($i=0; $i < $count; $i++)
                            {
                                if($i < $count_page_compare)
                                {
                                    $upd = "update ".$tag." set info='".addslashes(htmlentities($page_compare->{$obj}[$i]->info , ENT_QUOTES, 'UTF-8'))
                                        ."', count_words=".$page_compare->{$obj}[$i]->count_words
                                        .", count_symbols=".$page_compare->{$obj}[$i]->count_symbols." where id=".$page->{$obj}[$i]->id;
                                    if(!mysql_query( $upd, $this->link ))
                                    {
                                        if(SHOW_ECHO_ERROR)
                                            Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                        exit();
                                    }
                                }
                                else
                                {
                                    $del = "delete from ".$tag." where id=".$page->{$obj}[$i]->id;
                                    if(!mysql_query( $del, $this->link ))
                                    {
                                        if(SHOW_ECHO_ERROR)
                                            Log::write( "<p> ошибка ".mysql_errno()." при удалении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                        exit();
                                    }
                                }
                            }
                        }
                        elseif( count($page->$obj) < count($page_compare->$obj) )
                        {
                            $count = count($page_compare->$obj);
                            $count_page = count($page->$obj);
                            for($i=0; $i < $count; $i++)
                            {
                                if($i < $count_page)
                                {
                                    $upd = "update ".$tag." set info='".addslashes(htmlentities($page_compare->{$obj}[$i]->info , ENT_QUOTES, 'UTF-8'))
                                        ."', count_words=".$page_compare->{$obj}[$i]->count_words
                                        .", count_symbols=".$page_compare->{$obj}[$i]->count_symbols." where id=".$page->{$obj}[$i]->id;
                                    if(!mysql_query( $upd, $this->link ))
                                    {
                                        if(SHOW_ECHO_ERROR)
                                            Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                        exit();
                                    }
                                }
                                else
                                {
                                    $ins = "insert into ".$tag
                                        ."( page_id, info, count_words, count_symbols ) value ( "
                                        .$page->id
                                        .", '".addslashes(htmlentities($page_compare->{$obj}[$i]->info , ENT_QUOTES, 'UTF-8'))
                                        ."', ".$page_compare->{$obj}[$i]->count_words
                                        .", ".$page_compare->{$obj}[$i]->count_symbols." )";
                                    if(!mysql_query( $ins, $this->link ))
                                    {
                                        if(SHOW_ECHO_ERROR)
                                            Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                        exit();
                                    }
                                }
                            }
                        }
                    }
                    elseif(!$page->$tag && $page_compare->$obj)
                    {
                        $count = count($page_compare->$obj);
                        for($i=0; $i < $count; $i++)
                        {
                            $ins = "insert into ".$tag
                                ."( page_id, info, count_words, count_symbols ) value ( "
                                .$page->id
                                .", '".addslashes(htmlentities($page_compare->{$obj}[$i]->info , ENT_QUOTES, 'UTF-8'))
                                ."', ".$page_compare->{$obj}[$i]->count_words
                                .", ".$page_compare->{$obj}[$i]->count_symbols." )";
                            if(!mysql_query( $ins, $this->link ))
                            {
                                if(SHOW_ECHO_ERROR)
                                    Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                exit();
                            }
                        }
                        $upd = "update ".$this->tb_pages." set ".$tag."=".$page->id." where id=".$page->id;
                        if(!mysql_query( $upd, $this->link ))
                        {
                            if(SHOW_ECHO_ERROR)
                                Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                            exit();
                        }
                    }
                    elseif($page->$tag && !$page_compare->$obj)
                    {
                        $upd = "update ".$this->tb_pages." set ".$tag."=NULL where id=".$page->id;
                        if(!mysql_query( $upd, $this->link ))
                        {
                            if(SHOW_ECHO_ERROR)
                                Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                            exit();
                        }

                        $count = count($page->$obj);
                        for($i=0; $i < $count; $i++)
                        {
                            $del = "delete from ".$tag." where id=".$page->{$obj}[$i]->id;
                            if(!mysql_query( $del, $this->link ))
                            {
                                if(SHOW_ECHO_ERROR)
                                    Log::write( "<p> ошибка ".mysql_errno()." при удалении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                exit();
                            }
                        }
                    }
                }
            }
        }
    }
    //обновляем данные из обьекта $page_compare в строке табл. tb_pages по id из обьекта $page
    protected function ModelCompare_Update_Page_itPageCompare( Page & $page, Page & $page_compare )
    {
        $this->ModelCompare_Update_TitlePage_itPageCompare($page, $page_compare);
        $this->ModelCompare_Update_KeywordsPage_itPageCompare($page, $page_compare);
        $this->ModelCompare_Update_DescriptionPage_itPageCompare($page, $page_compare);
        $this->ModelCompare_Update_LinksPage_itPageCompare($page, $page_compare);
        $this->ModelCompare_Update_ImagesPage_itPageCompare($page, $page_compare);
        $this->ModelCompare_Update_ContentPage_itPageCompare($page, $page_compare);
        //TAGS
        $this->ModelCompare_Update_TagsPage_itPageCompare($page, $page_compare);

        $s1 = ", ";
        $s2 = "=";
        $ch_res = '';
        if(count($this->ar_tags))
        {
            foreach($this->ar_tags as $k => $tag)
            {
                if($this->MODEL_DEFINE_TAGS[$tag])
                {
                    $ch      = prefix_tag_change . $tag;
                    $ch_res .= $s1 . $ch . $s2 . $page_compare->$ch;
                }
            }
        }

        $upd = "update ".$this->tb_pages." set page_code='"
            .addslashes(htmlentities( $page_compare->page_code , ENT_QUOTES, 'UTF-8' ))
            ."', http_code=".$page_compare->http_code
            .", level_links=".$page_compare->level_links // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            .", content_type='".addslashes(htmlentities($page_compare->content_type , ENT_QUOTES, 'UTF-8' ))
            ."', redirect_url=".($page_compare->redirect_url?("'".addslashes(htmlentities( $page_compare->redirect_url, ENT_QUOTES, 'UTF-8' ))."'"):'NULL')
            .", parent_url=".($page_compare->parent_url?("'".addslashes(htmlentities( $page_compare->parent_url, ENT_QUOTES, 'UTF-8' ))."'"):'NULL')
            .", load_count=".$page_compare->load_count  // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            .", load_timeout=".$page_compare->load_timeout  // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            .", connect_time=".$page_compare->connect_time
            .", total_time=".$page_compare->total_time  // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            .", page_size=".$page_compare->page_size
            .", page_speed=".$page_compare->page_speed  // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            .", scan=".$page_compare->scan
            .", exist=".$page_compare->exist
            .", create_page=".$page_compare->create_page
            .", update_page=".$page_compare->update_page
            .", delete_page=".$page_compare->delete_page
            .", date_change_page='".$page_compare->date_change_page //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            ."', change_page_code=".$page_compare->change_page_code
            .", change_http_code=".$page_compare->change_http_code
            .", change_content_type=".$page_compare->change_content_type
            .", change_redirect_url=".$page_compare->change_redirect_url
            .", change_parent_url=".$page_compare->change_parent_url
            .", change_load_count=".$page_compare->change_load_count
            .", change_load_timeout=".$page_compare->change_load_timeout
            .", change_title=".$page_compare->change_title
            .", change_keywords=".$page_compare->change_keywords
            .", change_description=".$page_compare->change_description
            .", change_level_links=".$page_compare->change_level_links
            .", change_total_time=".$page_compare->change_total_time  // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            .", change_page_size=".$page_compare->change_page_size
            .", change_page_speed=".$page_compare->change_page_speed  // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            .$ch_res
            .", change_links=".$page_compare->change_links
            .", change_images=".$page_compare->change_images
            .", change_content=".$page_compare->change_content
            .", change_exist=".$page_compare->change_exist
            ." where id=".$page->id;
        if(!mysql_query( $upd, $this->link ))
        {
            if(SHOW_ECHO_ERROR)
            {
                Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                Log::write( "<p> $upd ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                ob_start();
                var_dump($page_compare);
                $str = ob_get_clean();
                Log::write( "<p> page !</p>", $this->host_site );
                Log::write( "<p> $str </p>", $this->host_site );
                ob_start();
                var_dump($page_compare);
                $str = ob_get_clean();
                Log::write( "<p> page_compare !</p>", $this->host_site );
                Log::write( "<p> $str </p>", $this->host_site );
            }
            exit();
        }
    }

    /*protected function ModelCompare_UpdateNotLoadedPage($page_url, $ar, $level_links, $parent_url)
    {
        // url | array(error | errno) | level_links
        $this->ModelCompare( $page_url, $ar, $level_links, $parent_url );
    }*/

    //если страница не изменена, обновляем только поле scan в табл. tb_pages
    protected function ModelCompare_UpdatePage_FieldScan( Page & $page, $scan )
    {
        $upd = "update ".$this->tb_pages." set scan=".$scan." where id=".$page->id;
        if(!mysql_query( $upd, $this->link ))
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
    }

    //если страница недоступна из-зи ошибки operation_timeout-(28), обновляем только поле scan, http_code, content_type, exist в табл. tb_pages
    protected function ModelCompare_UpdatePage_OperationTimeout( Page & $page, $scan, $http_code, $content_type )
    {
        $upd = "update ".$this->tb_pages
            ." set scan=".$scan
            .", http_code=".$http_code
            .", content_type='".addslashes(htmlentities($content_type , ENT_QUOTES, 'UTF-8' ))
            ."', reserve_http_code=".$page->http_code
            .", reserve_content_type='".addslashes(htmlentities($page->content_type , ENT_QUOTES, 'UTF-8' ))
            ."' where id=".$page->id;
        if(!mysql_query( $upd, $this->link ))
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
    }

    protected function ModelCompare_UpdatePage( Page & $page, Page & $page_compare )
    {
        $this->ModelCompare_Save_Page_to_TbPageArchive( $page);
        $this->ModelCompare_Update_Page_itPageCompare( $page, $page_compare );
    }

    //-----------------основной метод для сравнения страниц------------------------------------------------------------
    function ModelCompare( $url, $ar, $level_links, $parent_url, $exist=1 )
    {
        $sel = "select * from ".$this->tb_pages." where url like '".addslashes(htmlentities($url , ENT_QUOTES, 'UTF-8' ))."'";
        $res = mysql_query($sel,$this->link);

        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        else
        {
            $page = null;
            while ($line = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $page = new Page();
                $page->id                  = intval( $line['id'] );
                $page->url                 = html_entity_decode( $line['url'], ENT_QUOTES, 'UTF-8' );
                $page->page_code           = html_entity_decode( $line['page_code'], ENT_QUOTES, 'UTF-8' );
                $page->http_code           = intval( $line['http_code'] );
                $page->content_type        = html_entity_decode( $line['content_type'], ENT_QUOTES, 'UTF-8' );
                $page->redirect_url        = html_entity_decode( $line['redirect_url'], ENT_QUOTES, 'UTF-8' );
                $page->parent_url          = html_entity_decode( $line['parent_url'], ENT_QUOTES, 'UTF-8' );
                $page->title               = intval( $line['title'] );
                $page->load_count          = intval( $line['load_count'] );
                $page->load_timeout        = intval( $line['load_timeout'] );
                $page->keywords            = intval( $line['keywords'] );
                $page->description         = intval( $line['description'] );
                $page->level_links         = intval( $line['level_links']);
                $page->connect_time        = floatval( $line['connect_time'] );
                $page->total_time          = floatval( $line['total_time'] );
                $page->page_size           = floatval( $line['page_size'] );
                $page->page_speed          = floatval( $line['page_speed'] );
                $page->links               = intval( $line['links'] );
                $page->images              = intval( $line['images'] );
                $page->content             = intval( $line['content'] );
                $page->scan                = intval( $line['scan'] );
                $page->exist               = intval( $line['exist'] );
                $page->create_page         = intval( $line['create_page'] );
                $page->update_page         = intval( $line['update_page'] );
                $page->delete_page         = intval( $line['delete_page'] );
                $page->date_change_page    = $line['date_change_page'];

                $page->change_page_code    = intval( $line['change_page_code'] );
                $page->change_http_code    = intval( $line['change_http_code'] );
                $page->change_content_type = intval( $line['change_content_type'] );
                $page->change_redirect_url = intval( $line['change_redirect_url'] );
                $page->change_parent_url   = intval( $line['change_parent_url'] );
                $page->change_load_count   = intval( $line['change_load_count'] );
                $page->change_load_timeout = intval( $line['change_load_timeout'] );
                $page->change_title        = intval( $line['change_title'] );
                $page->change_keywords     = intval( $line['change_keywords'] );
                $page->change_description  = intval( $line['change_description'] );
                $page->change_level_links  = intval( $line['change_level_links'] );
                $page->change_total_time   = intval( $line['change_total_time'] );
                $page->change_page_size    = intval( $line['change_page_size'] );
                $page->change_page_speed   = intval( $line['change_page_speed'] );
                $page->change_links        = intval( $line['change_links'] );
                $page->change_images       = intval( $line['change_images'] );
                $page->change_content      = intval( $line['change_content'] );
                $page->change_exist        = intval( $line['change_exist'] );

                $page->reserve_http_code   = intval( $line['reserve_http_code'] );
                $page->reserve_content_type= html_entity_decode( $line['reserve_content_type'], ENT_QUOTES, 'UTF-8' );

                $this->ModelAddInitPropertyInObjectPage($page, $line);
            }

            //если в таблице pages есть такой URL, тогда
            //сравниваем !

            if($page)
            {
                if($ar['http_code'] == self::$curl_operation_timeout )
                {
                    $this->ModelCompare_UpdatePage_OperationTimeout( $page, $this->counter_scan, $ar['http_code'], $ar['content_type'] );
                    return;
                }

                $this->ModelCompare_Mysql_GetObjectsOfMysql( $page );//собираем обьект из MySql !!!

                $page_compare = new Page();
                //<<заносим html-code----------------------------------------------------------------------
                $page_compare->url              = $url;
                $page_compare->http_code        = $ar['http_code'];
                $page_compare->content_type     = $ar['content_type'];
                $page_compare->redirect_url     = $ar['redirect_url'];
                $page_compare->parent_url       = $parent_url;
                $page_compare->load_count       = self::$load_count;
                $page_compare->load_timeout     = self::$load_timeout;
                $page_compare->connect_time     = $ar['connect_time'];
                $page_compare->total_time       = $ar['total_time'];
                $page_compare->page_size        = $ar['size_download'];
                $page_compare->page_speed       = $ar['speed_download'];
                $page_compare->level_links      = $level_links;
                $page_compare->scan             = $this->counter_scan;
                $page_compare->exist            = $exist;
                $page_compare->date_change_page = date("Y-m-d H:i:s",mktime());

                if($page_compare->http_code == 200 && strstr($ar['content_type'], 'text/html'))
                {
                    $page_compare->page_code  = $this->html_data;
                    //$page_compare->total_time       = $ar['total_time'];
                    /*$page_compare->page_size  = $ar['size_download'];
                    $page_compare->page_speed = $ar['speed_download'];*/
                }
                $this->ModelAddPropertyInObjectPage($page_compare);

                //<<работаем над поиском <title>-----------------------------------------------------------
                $this->ModelCompare_FindTitle( $page_compare );
                //<<работаем над поиском <meta Keywords> & <meta Description>------------------------------
                $this->ModelCompare_FindKeywordsDescription( $page_compare );
                //<<работаем над поиском <a>---------------------------------------------------------------
                $this->ModelCompare_FindLinks( $page_compare );
                //<<работаем над поиском <img>-------------------------------------------------------------
                $this->ModelCompare_FindImages( $page_compare );
                //<<работаем над поиском content-------------------------------------------------------------
                $this->ModelCompare_FindContent( $page_compare );
                //<<работаем над поиском tags h1...h6, b, strong---------------------------------------------
                $this->ModelCompare_FindTags( $page_compare );
                //<<сравниваем обьекты-страницы !
                if($this->ModelCompare_ComparingObjectsPage( $page, $page_compare ))
                {
                    $this->ModelCompare_UpdatePage( $page, $page_compare );
                }
                else
                {
                    $this->ModelCompare_UpdatePage_FieldScan( $page, $this->counter_scan );
                }
            }
            else
            {
                //парсинг едиственной НОВОЙ страницы, найденной при сравнении
                $this->ModelCompare_AddSinglePage( $url, $ar, $level_links, $parent_url );
            }
        }
    }

    //-----------------добавление links НОВОЙ страницы, найденной при сравнении----------------------------------------
    function ModelAddLinksSinglePage( $num )
    {
        //<работаем над поиском <a>---------------------------------------------------------------
        if($this->html)
        {
            if($this->html->innertext != '' && count($this->html->find('a')))
            {
                $ar_links_current_page    = $this->html->find('a');
                $count_links_current_page = count( $ar_links_current_page );

                if( $count_links_current_page > 0)
                {
                    $count = 0;

                    $ar_internal_links = array();
                    foreach($ar_links_current_page as $link)
                    {
                        if(!$link->href)continue;

                        $ar_components_link = parse_url( $link->href );
                        if( isset($ar_components_link['host']) && $ar_components_link['host'] == $this->host_site )
                        {//если в url есть хост и он == хосту сайта
                            //internal_link == 1 - внутренняя ссылка по которой будет производится переход
                            $ins = "insert into ".$this->tb_links."( page_id, internal_link, href, anchor ) values (".$num.", 1, '".addslashes(htmlentities($link->href , ENT_QUOTES, 'UTF-8' ))."', '".addslashes(htmlentities($link->plaintext , ENT_QUOTES, 'UTF-8' ))."')";
                        }
                        else if( !isset($ar_components_link['host']) && isset($ar_components_link['path']))
                        {//если в url нет хоста, но есть /path сайта
                            //internal_link == 1 - внутренняя ссылка (/path) по которой будет производится переход
                            $ins = "insert into ".$this->tb_links."( page_id, internal_link, href, anchor ) values (".$num.", 1, '".addslashes(htmlentities($link->href , ENT_QUOTES, 'UTF-8' ))."', '".addslashes(htmlentities($link->plaintext , ENT_QUOTES, 'UTF-8' ))."')";
                        }
                        else if( !isset($ar_components_link['host']) && isset($ar_components_link['fragment']))
                        {//если в url нет хоста, но есть #fragment сайта
                            //internal_link == 1 - внутренняя ссылка (#top) по которой не будет производится переход
                            $ins = "insert into ".$this->tb_links."( page_id, internal_link, href, anchor ) values (".$num.", 1, '".addslashes(htmlentities($link->href , ENT_QUOTES, 'UTF-8' ))."', '".addslashes(htmlentities($link->plaintext , ENT_QUOTES, 'UTF-8' ))."')";
                        }
                        else if( !isset($ar_components_link['host']) && !isset($ar_components_link['fragment']) && $link->href == '#')
                        {//если в url нет хоста, но есть #fragment сайта
                            //internal_link == 1 - внутренняя ссылка (#) по которой не будет производится переход
                            $ins = "insert into ".$this->tb_links."( page_id, internal_link, href, anchor ) values (".$num.", 1, '".addslashes(htmlentities($link->href , ENT_QUOTES, 'UTF-8' ))."', '".addslashes(htmlentities($link->plaintext , ENT_QUOTES, 'UTF-8' ))."')";
                        }
                        else
                        {
                            //internal_link == 0 - внешняя ссылка по которой не будет производится переход
                            $ins = "insert into ".$this->tb_links."( page_id, internal_link, href, anchor ) values (".$num.", 0, '".addslashes(htmlentities($link->href , ENT_QUOTES, 'UTF-8' ))."', '".addslashes(htmlentities($link->plaintext , ENT_QUOTES, 'UTF-8' ))."')";
                        }

                        if(mysql_query( $ins, $this->link ))
                        {
                            ++$count;
                            if(SHOW_ECHO)
                                Log::write( "<p> links-".$count." Вашей странички занесён в БД !</p>", $this->host_site );
                        }
                        else
                        {
                            if(SHOW_ECHO_ERROR)
                                Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                            exit();
                        }
                    }//end foreach

                    //если производились записи в таблицу tb_links обновляем таблицу tb_pages
                    if($count>0)
                    {
                        $upd = "update ".$this->tb_pages." set links=".$num." where id=".$num;
                        if(mysql_query( $upd, $this->link ))
                        {
                            if(SHOW_ECHO)
                                Log::write( "<p> Ваша страничка №".$num." изменена в БД !</p>", $this->host_site );
                        }
                        else
                        {
                            if(SHOW_ECHO_ERROR)
                                Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                            exit();
                        }
                    }
                }
            }
        }
        //>>end-----------------------------------------------------------------------------------
    }

    //-----------------добавление едиственной НОВОЙ страницы, найденной при сравнении----------------------------------
    function ModelCompare_AddSinglePage( $url, $ar, $level_links, $parent_url )
    {
        if (!$this->link)
        {
            $this->link = mysql_connect($this->host, $this->root_login, $this->root_password);
            if (!$this->link)
            {
                if(SHOW_ECHO_ERROR_CONNECT_DB)
                    Log::write( "<p> Ошибка соединения: ".mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit() ;
            }

            if(!mysql_select_db($this->db_name, $this->link))
            {
                if(SHOW_ECHO_ERROR_CONNECT_DB)
                    Log::write( "<p> Ошибка подключения к БД ".mysql_errno()."</p>", $this->host_site );
                exit();
            }
            mysql_query("SET NAMES utf8"); // теперь всё будет сохранятся в MySql правильной кодировке
        }

        Log::write( __METHOD__, $this->host_site );

        $ins = "insert into ".$this->tb_pages."(
        url,
        parent_url,
        redirect_url,
        http_code,
        content_type,
        level_links,
        scan,
        connect_time,
        total_time,
        page_size,
        page_speed,
        load_count,
        load_timeout,
        exist,
        create_page,
        date_change_page ) value ('"
            .addslashes(htmlentities($url , ENT_QUOTES, 'UTF-8' ))
            ."', '".addslashes(htmlentities($parent_url , ENT_QUOTES, 'UTF-8' ))
            ."', ".($ar['redirect_url']?("'".addslashes(htmlentities( $ar['redirect_url'], ENT_QUOTES, 'UTF-8' ))."'"):'NULL')
            .", ".$ar['http_code']
            .", '".addslashes(htmlentities($ar['content_type'] , ENT_QUOTES, 'UTF-8' ))
            ."', ".$level_links
            .", ".$this->counter_scan
            .", ".$ar['connect_time']
            .", ".$ar['total_time']
            .", ".($ar['size_download']?$ar['size_download']:'NULL')
            .", ".($ar['speed_download']?$ar['speed_download']:'NULL')
            .", ".self::$load_count
            .", ".self::$load_timeout
            .", 1,
            1, '"
            .date("Y-m-d H:i:s",mktime())."' )";

        if(!mysql_query( $ins, $this->link ))
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            Log::write( $ins, $this->host_site );
            exit();
        }
        if( (isset($ar['http_code']) && $ar['http_code'] == 200)
            && (isset($ar['content_type']) && strstr($ar['content_type'], 'text/html')) )
        {
            //<<ПОЛНЫЙ ПАРСЕР СТАРНИЦЫ !!!------------------------------------------------
            $this->id_current_page = mysql_insert_id();
            $this->ar_pages[$this->cur_page]->id = $this->id_current_page;

            $this->ModelAddTitleToPage();  //<<получаем title из html и его в БД
            $this->ModelAddMetaToPage();   //<<получаем мета-теги из html и заносим их в БД
            $this->ModelUpdatePageIDTitleIDKeywordsIDDescription($ar['size_download'],$ar['speed_download'],$ar['total_time']);
            $this->ModelAddLinksSinglePage( $this->id_current_page );
            $this->ModelAddImgToPage();    //<<получаем img из html и их в БД
            $this->ModelAddContentToPage();
            $this->ModelAddTagsToPage($this->id_current_page);

            $this->id_current_page = 0;
            //>>end ПОЛНЫЙ ПАРСЕР СТАРНИЦЫ !!!--------------------------------------------
        }
    }

    //-----------------удаляем ненайденные страницы--------------------------------------------------------------------
    function ModelDeletePageOfTbPages()
    {
        $sel = "select * from ".$this->tb_pages." where scan !=".$this->counter_scan;
        $res = mysql_query($sel,$this->link);

        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        else
        {
            $page = null;
            $ar_page = array();
            while ($line = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $page = new Page();
                $page->id                  = intval( $line['id'] );
                $page->url                 = html_entity_decode( $line['url'], ENT_QUOTES, 'UTF-8' );
                $page->page_code           = html_entity_decode( $line['page_code'], ENT_QUOTES, 'UTF-8' );
                $page->http_code           = intval( $line['http_code'] );
                $page->content_type        = html_entity_decode( $line['content_type'], ENT_QUOTES, 'UTF-8' );
                $page->redirect_url        = html_entity_decode( $line['redirect_url'], ENT_QUOTES, 'UTF-8' );
                $page->parent_url          = html_entity_decode( $line['parent_url'], ENT_QUOTES, 'UTF-8' );
                $page->load_count          = intval( $line['load_count'] );
                $page->load_timeout        = intval( $line['load_timeout'] );
                $page->title               = intval( $line['title'] );
                $page->keywords            = intval( $line['keywords'] );
                $page->description         = intval( $line['description'] );
                $page->level_links         = intval( $line['level_links']);
                $page->connect_time        = floatval( $line['connect_time'] );
                $page->total_time          = floatval( $line['total_time'] );
                $page->page_size           = floatval( $line['page_size'] );
                $page->page_speed          = floatval( $line['page_speed'] );
                $page->links               = intval( $line['links'] );
                $page->images              = intval( $line['images'] );
                $page->content             = intval( $line['content'] );
                $page->scan                = intval( $line['scan'] );
                $page->exist               = intval( $line['exist'] );
                $page->create_page         = intval( $line['create_page'] );
                $page->update_page         = intval( $line['update_page'] );
                $page->delete_page         = intval( $line['delete_page'] );
                $page->date_change_page    = $line['date_change_page'];

                $page->change_page_code    = intval( $line['change_page_code'] );
                $page->change_http_code    = intval( $line['change_http_code'] );
                $page->change_content_type = intval( $line['change_content_type'] );
                $page->change_redirect_url = intval( $line['change_redirect_url'] );
                $page->change_parent_url   = intval( $line['change_parent_url'] );
                $page->change_load_count   = intval( $line['change_load_count'] );
                $page->change_load_timeout = intval( $line['change_load_timeout'] );
                $page->change_title        = intval( $line['change_title'] );
                $page->change_keywords     = intval( $line['change_keywords'] );
                $page->change_description  = intval( $line['change_description'] );
                $page->change_level_links  = intval( $line['change_level_links'] );
                $page->change_total_time   = intval( $line['change_total_time'] );
                $page->change_page_size    = intval( $line['change_page_size'] );
                $page->change_page_speed   = intval( $line['change_page_speed'] );
                $page->change_links        = intval( $line['change_links'] );
                $page->change_images       = intval( $line['change_images'] );
                $page->change_content      = intval( $line['change_content'] );
                $page->change_exist        = intval( $line['change_exist'] );

                $page->reserve_http_code   = intval( $line['reserve_http_code'] );
                $page->reserve_content_type= html_entity_decode( $line['reserve_content_type'], ENT_QUOTES, 'UTF-8' );

                $this->ModelAddInitPropertyInObjectPage($page, $line);

                $this->ModelCompare_Mysql_GetObjectsOfMysql( $page );//собираем обьект из MySql !!!
                $ar_page[] = $page;
            }

            if($ar_page)
            {
                foreach($ar_page as $page)
                {
                    if($page->exist)
                    {
                        $this->ModelCompare_Save_Page_to_TbPageArchive( $page );
                        $upd = "update ".$this->tb_pages
                            ." set scan=".$this->counter_scan
                            .", exist=0, create_page=0, update_page=0, delete_page=1, date_change_page='"
                            .date("Y-m-d H:i:s",mktime())."', change_exist=1 where id=".$page->id;
                        if(!mysql_query( $upd, $this->link ))
                        {
                            if(SHOW_ECHO_ERROR)
                                Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                            exit();
                        }
                    }
                    else
                    {
                        $upd = "update ".$this->tb_pages
                            ." set scan=".$this->counter_scan." where id=".$page->id;
                        if(!mysql_query( $upd, $this->link ))
                        {
                            if(SHOW_ECHO_ERROR)
                                Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                            exit();
                        }
                    }
                }
            }
        }
    }

    //-----------------зацикливаем поиск страниц-----------------------------------------------------------------------
    function ModelScan_SearchPagesInSite( $url )
    {
        $ar_components_link = parse_url($url);

        if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
            $tmp_url = $url;
        }else{
            if(strpos($ar_components_link['path'], '/') === 0){
                $tmp_url = $this->protocol.'://'.$this->host_site.$url;
            }else{
                $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$url;
            }
        }

        $this->html_data = '';

        $this->ModelCurlInit($ch, $tmp_url);

        if(curl_exec($ch))
        {
            $ar = curl_getinfo($ch);
            if(count($ar))
            {
                if( (isset($ar['url']) && parse_url($ar['url'], PHP_URL_HOST) == $this->host_site)
                    && (isset($ar['http_code']) && $ar['http_code'] == 200)
                    && (isset($ar['content_type']) && strstr($ar['content_type'], 'text/html')) )
                {
                    if( $ar['size_download'] == strlen($this->html_data) )
                    {
                        $this->ModelSetCurrentUrl( $tmp_url, $this->html_data );
                        if($this->html->_charset != $this->html->_target_charset)
                            $this->html_data = iconv($this->html->_charset, $this->html->_target_charset, $this->html_data);
                    }
                    //<заносим страницу первую страницу---------------------------------------------------------------
                    $this->count_pages++;
                    $this->cur_page++;
                    $this->ar_pages[$this->cur_page] = new Link($this->current_url, $this->count_level_links, '');
                    //>end------------------------------------------------------------------------------------


                    $this->ModelCompare( $url, $ar, $this->count_level_links, '' );


                    $this->limiter_pages++;
                    if($this->limiter_pages >= $this->limit_pages)
                    {
                        if(isset($this->html))
                        {
                            if(is_object($this->html))
                            {
                                $this->html->clear();
                            }
                            unset($this->html);
                        }
                        return;
                    }

                    //<работаем над поиском <a>---------------------------------------------------------------
                    $ar_internal_links = array();
                    $ar_lines_links    = array();

                    if($this->html)
                    {
                        if($this->html->innertext != '' && count($this->html->find('a')))
                        {
                            $ar_links_current_page    = $this->html->find('a');
                            $count_links_current_page = count( $ar_links_current_page );

                            if( $count_links_current_page > 0)
                            {
                                foreach($ar_links_current_page as $link)
                                {
                                    if(!$link->href)continue;

                                    $ar_components_link = parse_url( $link->href );
                                    if( isset($ar_components_link['host']) && $ar_components_link['host'] == $this->host_site)
                                    {//если в url есть хост и он == хосту сайта
                                        if( !in_array($link->href, $ar_internal_links) )
                                        {
                                            $ar_internal_links[] = $link->href;
                                        }
                                    }
                                    else if( !isset($ar_components_link['host']) && isset($ar_components_link['path']))
                                    {//если в url нет хоста, но есть /path сайта
                                        if( ( (strpos($link->href, 'javascript:')!==0) && (strpos($link->href, 'Javascript:')!==0) && (strpos($link->href, 'mailto:')!==0) ) && !in_array($link->href, $ar_internal_links))
                                        {
                                            $ar_internal_links[] = $link->href;
                                        }
                                    }
                                }//end foreach
                            }
                        }
                    }
                    //>>end-----------------------------------------------------------------------------------

                    //<получаем ссылки со страницы-----------------------------------------------
                    if( count( $ar_internal_links ) )
                    {
                        $ar_url_page = array();//чистим
                        foreach($this->ar_pages as $p)
                        {
                            $ar_url_page[] = $p->url;
                        }

                        $ar_lines_links = array();
                        foreach($ar_internal_links as $href)
                        {
                            if( !in_array($href, $ar_url_page) )
                            {
                                $ar_lines_links[] = $href;
                            }
                        }
                        $ar_url_page = array();//чистим
                    }
                    //>end--------------------------------------------------------------------

                    //<добавляем новые ссылки в таблицу pages---------------------------------
                    if( count($ar_lines_links) )
                    {
                        $this->count_level_links++;//увеличиваем уровень вложенности ссылок

                        foreach( $ar_lines_links as $link )
                        {
                            $this->ar_pages[++$this->count_pages] = new Link($link, $this->count_level_links, $url);
                        }
                    }
                    //>end--------------------------------------------------------------------

                    if($this->count_pages > $this->cur_page)
                    {
                        if(isset($this->html))
                        {
                            if(is_object($this->html))
                            {
                                $this->html->clear();
                            }
                            unset($this->html);
                        }

                        /*Log::write( "--------------------------------------------------------------------------------------", $this->host_site );
                        Log::write( __METHOD__, $this->host_site );
                        Log::write( "url=".$this->ar_pages[$this->cur_page]->url, $this->host_site );
                        Log::write( "--------------------------------------------------------------------------------------", $this->host_site );*/

                        for( true; $this->cur_page < $this->count_pages; true )
                        {
                            if($this->limiter_pages < $this->limit_pages)
                            {
                                $this->ModelScan_SearchPagesInSiteRepeat(++$this->cur_page);
                                if( ($this->cur_page % 10) == 0 || ($this->cur_page % 10) == 5 )
                                {
                                    $upd = "update ".$this->tb_counter." set counter_compare_page=".$this->cur_page.", counter_compare_pages=".$this->count_pages." where id=".$this->counter_scan;
                                    if(!mysql_query( $upd, $this->link ))
                                    {
                                        if(SHOW_ECHO_ERROR)
                                            Log::write( "<p> ошибка ".mysql_errno()." при работе с табл. counter ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                        exit();
                                    }
                                }
                            }
                            else
                            {
                                break;
                            }
                        }
                    }
                }
                else
                {
                    $this->count_pages++;
                    $this->cur_page++;
                    $this->ar_pages[$this->cur_page] = new Link($url, $this->count_level_links, '');

                    $this->ModelCompare( $url, $ar, $this->count_level_links, '');

                    if(isset($this->html))
                    {
                        if(is_object($this->html))
                        {
                            $this->html->clear();
                        }
                        unset($this->html);
                    }
                }
            }
        }
        else
        {
            //здесь могут быть ошибки :
            //404 и т.п.                 - The requested URL returned error: 404
            //превышение лимита ожидания - Operation timed out after 10000 milliseconds with 0 bytes received

            //если константа REDIRECT == true, может быть ошибка :
            //превышение лимита редиректа $this->curl_count_redirects - Maximum (limit) redirects followed

            $ar = curl_getinfo($ch);
            $ar['http_code']    = curl_errno($ch);
            $ar['content_type'] = curl_error($ch);

            $this->count_pages++;
            $this->cur_page++;
            $this->ar_pages[$this->cur_page] = new Link($url, $this->count_level_links, '');
            //$this->ModelCompare_UpdateNotLoadedPage($url, $ar, $this->count_level_links, '');
            $this->ModelCompare( $url, $ar, $this->count_level_links, '' );
        }
        curl_close($ch);
        unset($ch);
        unset($ar);
    }

    function ModelScan_SearchPagesInSiteRepeat( $num )
    {
        //<заносим страницу в БД----------------------------------------------------------------
        $url = $this->ar_pages[$num]->url;
        $ar_components_link = parse_url( $url );

        if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
            $tmp_url = $this->ar_pages[$num]->url;
        }else{
            if(strpos($ar_components_link['path'], '/') === 0){
                $tmp_url = $this->protocol.'://'.$this->host_site.$this->ar_pages[$num]->url;
            }else{
                $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$this->ar_pages[$num]->url;
            }
        }

        $tmp_level_links = $this->ar_pages[$num]->level;
        $parent_url = $this->ar_pages[$num]->url;

        $this->html_data = '';

        $this->ModelCurlInit($ch, $tmp_url);

        if(curl_exec($ch))
        {
            $ar = curl_getinfo($ch);
            if(count($ar))
            {
                if( (isset($ar['url']) && parse_url($ar['url'], PHP_URL_HOST) == $this->host_site)
                    && (isset($ar['http_code']) && $ar['http_code'] == 200)
                    && (isset($ar['content_type']) && strstr($ar['content_type'], 'text/html')) )
                {
                    //страницу загружаем делаем все что надо, т.к. это файл HTML
                    if( $ar['size_download'] == strlen($this->html_data) )
                    {
                        $this->ModelSetCurrentUrl( $tmp_url, $this->html_data );
                        if($this->html->_charset != $this->html->_target_charset)
                            $this->html_data = iconv($this->html->_charset, $this->html->_target_charset, $this->html_data);
                    }
                    //>end------------------------------------------------------------------------------------


                    /*Log::write( "--------------------------------------------------------------------------------------", $this->host_site );
                    Log::write( __METHOD__, $this->host_site );
                    Log::write( "url=".$this->ar_pages[$this->cur_page]->url, $this->host_site );
                    Log::write( "--------------------------------------------------------------------------------------", $this->host_site );*/

                    $this->ModelCompare( $url, $ar, $tmp_level_links, $this->ar_pages[$num]->parent_url );


                    //<работаем над поиском <a>---------------------------------------------------------------
                    $ar_internal_links = array();
                    $ar_lines_links    = array();

                    if($this->html)
                    {
                        if($this->html->innertext != '' && count($this->html->find('a')))
                        {
                            $ar_links_current_page    = $this->html->find('a');
                            $count_links_current_page = count( $ar_links_current_page );

                            if( $count_links_current_page > 0)
                            {
                                $ar_internal_links = array();
                                foreach($ar_links_current_page as $link)
                                {
                                    if(!$link->href)continue;

                                    $ar_components_link = parse_url( $link->href );
                                    if( isset($ar_components_link['host']) && $ar_components_link['host'] == $this->host_site )
                                    {//если в url есть хост и он == хосту сайта
                                        if( !in_array($link->href, $ar_internal_links) )
                                        {
                                            $ar_internal_links[] = $link->href;
                                        }
                                    }
                                    else if( !isset($ar_components_link['host']) && isset($ar_components_link['path']))
                                    {//если в url нет хоста, но есть /path сайта
                                        if( ( (strpos($link->href, 'javascript:')!==0) && (strpos($link->href, 'Javascript:')!==0) && (strpos($link->href, 'mailto:')!==0) ) && !in_array($link->href, $ar_internal_links))
                                        {
                                            $ar_internal_links[] = $link->href;
                                        }
                                    }
                                }//end foreach
                            }
                        }
                    }
                    //>>end-----------------------------------------------------------------------------------

                    $this->limiter_pages++;
                    if($this->limiter_pages < $this->limit_pages)
                    {
                        //<получаем ссылки со страницы-----------------------------------------------
                        if( count( $ar_internal_links ) )
                        {
                            $ar_url_page = array();//чистим
                            foreach($this->ar_pages as $p)
                            {
                                $ar_url_page[] = $p->url;
                            }

                            $ar_lines_links = array();
                            foreach($ar_internal_links as $href)
                            {
                                if( !in_array($href, $ar_url_page) )
                                {
                                    $ar_lines_links[] = $href;
                                }
                            }
                            $ar_url_page = array();//чистим
                        }
                        //>end--------------------------------------------------------------------

                        //<добавляем новые ссылки в таблицу pages---------------------------------
                        if( count($ar_lines_links) )
                        {
                            $tmp_level_links++;

                            foreach( $ar_lines_links as $link )
                            {
                                $this->ar_pages[++$this->count_pages] = new Link($link, $tmp_level_links, $parent_url);
                            }
                        }
                        //>end--------------------------------------------------------------------
                    }

                    if(isset($this->html))
                    {
                        if(is_object($this->html))
                        {
                            $this->html->clear();
                        }
                        unset($this->html);
                    }
                }
                else
                {
                    /*Log::write( "--------------------------------------------------------------------------------------", $this->host_site );
                    Log::write( __METHOD__, $this->host_site );
                    Log::write( "url=".$this->ar_pages[$this->cur_page]->url, $this->host_site );
                    Log::write( "--------------------------------------------------------------------------------------", $this->host_site );*/

                    $this->ModelCompare( $url, $ar, $tmp_level_links, $this->ar_pages[$num]->parent_url );
                }
            }
        }
        else
        {
            //здесь могут быть ошибки :
            //404 и т.п.                 - The requested URL returned error: 404
            //превышение лимита ожидания - Operation timed out after 10000 milliseconds with 0 bytes received

            //если константа REDIRECT == true, может быть ошибка :
            //превышение лимита редиректа $this->curl_count_redirects - Maximum (limit) redirects followed

            $ar = curl_getinfo($ch);
            $ar['http_code']    = curl_errno($ch);
            $ar['content_type'] = curl_error($ch);

            //$this->ModelCompare_UpdateNotLoadedPage($this->ar_pages[$num]->url, $ar, $tmp_level_links, $this->ar_pages[$num]->parent_url);
            $this->ModelCompare($this->ar_pages[$num]->url, $ar, $tmp_level_links, $this->ar_pages[$num]->parent_url);
        }
        curl_close($ch);
        unset($ch);
        unset($ar);
    }

    function ModelScan_Pages()
    {
        $url = $this->url_site;

        if(!mysql_select_db($this->db_name, $this->link))
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> Ошибка подключения к БД ".mysql_errno()."</p>", $this->host_site );
            exit();
        }

        mysql_query("SET NAMES utf8"); // теперь всё будет сохранятся в MySql правильной кодировке

        //*****************************************************************************
        //*****************************************************************************
        //*****************************************************************************
        // ЕСЛИ МЕНЯЕТСЯ КОЛИЧЕСТВО ЕЛЕМЕНТОВ В МАССИВЕ $MODEL_DEFINE_TAGS[] - УВЕЛИЧИВАЕТСЯ !
        // ИЛИ МЕНЯЮТСЯ ТИПЫ ТЕГОВ
        // НУЖНО ДОБАВИТЬ ПРОВЕРКУ И ДОБАВЛЕНИЕ НЕДОСТАЮЩИХ ПОЛЕЙ В ТАБЛИЦЕ PAGES И PAGES_ARCHIVE
        // СООТВЕТСВУЮЩИХ ТЕГАМ В МАССИВЕ $MODEL_DEFINE_TAGS[]
        //*****************************************************************************
        //*****************************************************************************
        //*****************************************************************************


        //<<--------меняем флаг в табл. toggle - сайт анализируется !!!
        /*$upd = "update ".$this->tb_toggle." set flag=0";
        if(mysql_query( $upd, $this->link ))
        {
            if(SHOW_ECHO)
                Log::write( "<p> табл. toggle занесено значение - 0, сайт начал анализироваться  !</p>", $this->host_site );
        }
        else
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." в табл. toggle НЕ занесено значение - 0, сайт НЕ анализируется ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }*/
        //>>end------------------------------------------------------------

        //<<---обнуляем переменные----------------------------
        $this->ar_pages    = array();
        $this->count_pages = 0;
        $this->cur_page    = 0;
        $this->count_level_links = 0;
        $this->tmp_id      = 0;
        $this->limiter_pages = 0;
        $this->tmp_data_base_report = '';
        //>>end-----------------------------------------------

        //<<---обновляем настройки в табл. tools----------------
        $this->ModelUpdateProjectTools();
        //Log::write($this->timeout_scan, $this->host_site);
        //>>end-----------------------------------------------

        //<<---заносим в табл. counter - дуту начала анализа сайта (date_start_scan)
        $ins = "insert into ".$this->tb_counter."( date_start_scan ) value ( '".date("Y-m-d H:i:s",mktime())."' )";
        if(mysql_query( $ins, $this->link ))
        {
            $this->counter_scan = mysql_insert_id();//получаем номер текущего сканирования
            $this->ModelStartCreateReport( $this->counter_scan );
        }
        else
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при работе с табл. counter ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        //>>end-----------------------------------------------

        $upd = "update ".$this->tb_toggle." set flag_compare=1";
        if(!mysql_query( $upd, $this->link ))
        {
            if(SHOW_ECHO_ERROR)
            {
                Log::write( "<p> Начало поиска и сравнения страниц, таблица ".$this->tb_toggle." НЕ изменена ! ошибка ".mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            }
            exit();
        }

        Log::write( "Начало поиска и сравнения страниц.", $this->host_site );
        list($usec, $sec) = explode(" ", microtime());
        $time_start = ((float)$sec + (float)$usec);

        $this->ModelScan_SearchPagesInSite( $url );

        list($usec, $sec) = explode(" ", microtime());
        $time_stop = ((float)$sec + (float)$usec);
        $int = ((int)$time_stop - (int)$time_start);
        $float = ($time_stop - $time_start);
        Log::write( "Конец поиска и сравнения страниц.", $this->host_site );
        Log::write( "Время поиска и сравнения страниц.".($int/60)."мин. ".$int." сек. ".$float." мсек.", $this->host_site );

        //-----------------------------------------------------
        if(OPERATION_TIMEOUT_REPEAT_SCAN)
        {
            Log::write( "Начало загрузки страниц (не загруженных по таймауту)", $this->host_site );
            list($usec, $sec) = explode(" ", microtime());
            $time_start = ((float)$sec + (float)$usec);

            $this->ModelScan_PageLoadTimeout();

            list($usec, $sec) = explode(" ", microtime());
            $time_stop = ((float)$sec + (float)$usec);
            $int = ((int)$time_stop - (int)$time_start);
            $float = ($time_stop - $time_start);
            Log::write( "Конец загрузки страниц (не загруженных по таймауту)", $this->host_site );
            Log::write( "Время загрузки страниц (не загруженных по таймауту)".($int/60)."мин. ".$int." сек. ".$float." мсек.", $this->host_site );
        }
        //-----------------------------------------------------

        Log::write( "Начало удаления страниц", $this->host_site );
        list($usec, $sec) = explode(" ", microtime());
        $time_start = ((float)$sec + (float)$usec);

        $this->ModelDeletePageOfTbPages();

        list($usec, $sec) = explode(" ", microtime());
        $time_stop = ((float)$sec + (float)$usec);
        $int = ((int)$time_stop - (int)$time_start);
        $float = ($time_stop - $time_start);
        Log::write( "Конец удаления страниц", $this->host_site );
        Log::write( "Время удаления страниц ".($int/60)."мин. ".$int." сек. ".$float." мсек.", $this->host_site );

        //<<----добавляем в табл. counter - дуту окончания анализа сайта (date_stop_scan)
        $upd = "update ".$this->tb_counter." set counter_compare_page=".$this->cur_page.", counter_compare_pages=".$this->count_pages.", date_stop_scan='".date("Y-m-d H:i:s",mktime())."' where id=".$this->counter_scan;
        if(!mysql_query( $upd, $this->link ))
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при работе с табл. counter ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        //>>end------------------------------------------------------------

        $upd = "update ".$this->tb_toggle." set flag_compare=2";
        if(!mysql_query( $upd, $this->link ))
        {
            if(SHOW_ECHO_ERROR)
            {
                Log::write( "<p> Конец поиска и сравнения страниц, таблица ".$this->tb_toggle." НЕ изменена ! ошибка ".mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            }
            exit();
        }

        $this->ModelAutomaticGetMoneyInBalance();//возвращает лишние деньги на баланс
        $this->ModelSendReportToEmail();//отправляем отчёт по почте клиенту

        //<<--------меняем флаг в табл. toggle - сайт проанализирован !!!
        /*$upd = "update ".$this->tb_toggle." set flag=1";
        if(mysql_query( $upd, $this->link ))
        {
            if(SHOW_ECHO)
            {
                Log::write( "<p>Сайт проанализирован, таблица ".$this->tb_toggle." изменена !</p>", $this->host_site );
            }*/

        Log::write( "Начало копирования отчета", $this->host_site );
        list($usec, $sec) = explode(" ", microtime());
        $time_start = ((float)$sec + (float)$usec);

        $this->ModelStopCreateReport( $this->counter_scan );

        list($usec, $sec) = explode(" ", microtime());
        $time_stop = ((float)$sec + (float)$usec);
        $int = ((int)$time_stop - (int)$time_start);
        $float = ($time_stop - $time_start);
        Log::write( "Конец копирования отчета", $this->host_site );
        Log::write( "Время копирования отчета".($int/60)."мин. ".$int." сек. ".$float." мсек.", $this->host_site );

        /*}
        else
        {
            if(SHOW_ECHO_ERROR)
            {
                Log::write( "<p> Сайт проанализирован, таблица ".$this->tb_toggle." НЕ изменена ! ошибка ".mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            }
            exit();
        }*/
        //>>end------------------------------------------------------------
    }

    function ModelRestart_Scan_Pages()
    {
        $url = $this->url_site;

        if(!mysql_select_db($this->db_name, $this->link))
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> Ошибка подключения к БД ".mysql_errno()."</p>", $this->host_site );
            exit();
        }

        mysql_query("SET NAMES utf8"); // теперь всё будет сохранятся в MySql правильной кодировке

        //*****************************************************************************
        //*****************************************************************************
        //*****************************************************************************
        // ЕСЛИ МЕНЯЕТСЯ КОЛИЧЕСТВО ЕЛЕМЕНТОВ В МАССИВЕ $MODEL_DEFINE_TAGS[] - УВЕЛИЧИВАЕТСЯ !
        // ИЛИ МЕНЯЮТСЯ ТИПЫ ТЕГОВ
        // НУЖНО ДОБАВИТЬ ПРОВЕРКУ И ДОБАВЛЕНИЕ НЕДОСТАЮЩИХ ПОЛЕЙ В ТАБЛИЦЕ PAGES И PAGES_ARCHIVE
        // СООТВЕТСВУЮЩИХ ТЕГАМ В МАССИВЕ $MODEL_DEFINE_TAGS[]
        //*****************************************************************************
        //*****************************************************************************
        //*****************************************************************************


        //<<--------меняем флаг в табл. toggle - сайт анализируется !!!
        $upd = "update ".$this->tb_toggle." set flag=0";
        if(mysql_query( $upd, $this->link ))
        {
            if(SHOW_ECHO)
                Log::write( "<p> табл. toggle занесено значение - 0, сайт начал анализироваться  !</p>", $this->host_site );
        }
        else
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." в табл. toggle НЕ занесено значение - 0, сайт НЕ анализируется ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        //>>end------------------------------------------------------------

        //<<---обнуляем переменные----------------------------
        /*$this->ar_pages    = array();
        $this->count_pages = 0;
        $this->cur_page    = 0;
        $this->count_level_links = 0;
        $this->tmp_id      = 0;*/
        //>>end-----------------------------------------------

        //<<---обновляем настройки в табл. tools----------------
        $this->ModelUpdateProjectTools();
        //Log::write($this->timeout_scan, $this->host_site);
        //>>end-----------------------------------------------

        //<<---заносим в табл. counter - дуту начала анализа сайта (date_start_scan)
        $ins = "insert into ".$this->tb_counter."( date_start_scan ) value ( '".date("Y-m-d H:i:s",mktime())."' )";
        if(mysql_query( $ins, $this->link ))
        {
            $this->counter_scan = mysql_insert_id();//получаем номер текущего сканирования
            $this->ModelStartCreateReport( $this->counter_scan );
        }
        else
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при работе с табл. counter ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        //>>end-----------------------------------------------

        $upd = "update ".$this->tb_toggle." set flag_compare=1";
        if(!mysql_query( $upd, $this->link ))
        {
            if(SHOW_ECHO_ERROR)
            {
                Log::write( "<p> Начало поиска и сравнения страниц, таблица ".$this->tb_toggle." НЕ изменена ! ошибка ".mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            }
            exit();
        }

        Log::write( "Начало поиска и сравнения страниц.", $this->host_site );
        list($usec, $sec) = explode(" ", microtime());
        $time_start = ((float)$sec + (float)$usec);

        $this->ModelScan_SearchPagesInSite( $url );

        list($usec, $sec) = explode(" ", microtime());
        $time_stop = ((float)$sec + (float)$usec);
        $int = ((int)$time_stop - (int)$time_start);
        $float = ($time_stop - $time_start);
        Log::write( "Конец поиска и сравнения страниц.", $this->host_site );
        Log::write( "Время поиска и сравнения страниц.".($int/60)."мин. ".$int." сек. ".$float." мсек.", $this->host_site );

        //-----------------------------------------------------
        if(OPERATION_TIMEOUT_REPEAT_SCAN)
        {
            Log::write( "Начало загрузки страниц (не загруженных по таймауту)", $this->host_site );
            list($usec, $sec) = explode(" ", microtime());
            $time_start = ((float)$sec + (float)$usec);

            $this->ModelScan_PageLoadTimeout();

            list($usec, $sec) = explode(" ", microtime());
            $time_stop = ((float)$sec + (float)$usec);
            $int = ((int)$time_stop - (int)$time_start);
            $float = ($time_stop - $time_start);
            Log::write( "Конец загрузки страниц (не загруженных по таймауту)", $this->host_site );
            Log::write( "Время загрузки страниц (не загруженных по таймауту)".($int/60)."мин. ".$int." сек. ".$float." мсек.", $this->host_site );
        }
        //-----------------------------------------------------

        Log::write( "Начало удаления страниц", $this->host_site );
        list($usec, $sec) = explode(" ", microtime());
        $time_start = ((float)$sec + (float)$usec);

        $this->ModelDeletePageOfTbPages();

        list($usec, $sec) = explode(" ", microtime());
        $time_stop = ((float)$sec + (float)$usec);
        $int = ((int)$time_stop - (int)$time_start);
        $float = ($time_stop - $time_start);
        Log::write( "Конец удаления страниц", $this->host_site );
        Log::write( "Время удаления страниц ".($int/60)."мин. ".$int." сек. ".$float." мсек.", $this->host_site );

        //<<----добавляем в табл. counter - дуту окончания анализа сайта (date_stop_scan)
        $upd = "update ".$this->tb_counter." set counter_compare_page=".$this->cur_page.", counter_compare_pages=".$this->count_pages.", date_stop_scan='".date("Y-m-d H:i:s",mktime())."' where id=".$this->counter_scan;
        if(!mysql_query( $upd, $this->link ))
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при работе с табл. counter ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        //>>end------------------------------------------------------------

        $upd = "update ".$this->tb_toggle." set flag_compare=2";
        if(!mysql_query( $upd, $this->link ))
        {
            if(SHOW_ECHO_ERROR)
            {
                Log::write( "<p> Конец поиска и сравнения страниц, таблица ".$this->tb_toggle." НЕ изменена ! ошибка ".mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            }
            exit();
        }

        //<<--------меняем флаг в табл. toggle - сайт проанализирован !!!
        /*$upd = "update ".$this->tb_toggle." set flag=1";
        if(mysql_query( $upd, $this->link ))
        {
            if(SHOW_ECHO)
            {
                Log::write( "<p>Сайт проанализирован, таблица ".$this->tb_toggle." изменена !</p>", $this->host_site );
            }

            Log::write( "Начало копирования отчета", $this->host_site );
            list($usec, $sec) = explode(" ", microtime());
            $time_start = ((float)$sec + (float)$usec);

            $this->ModelStopCreateReport( $this->counter_scan );

            list($usec, $sec) = explode(" ", microtime());
            $time_stop = ((float)$sec + (float)$usec);
            $int = ((int)$time_stop - (int)$time_start);
            $float = ($time_stop - $time_start);
            Log::write( "Конец копирования отчета", $this->host_site );
            Log::write( "Время копирования отчета".($int/60)."мин. ".$int." сек. ".$float." мсек.", $this->host_site );

        }
        else
        {
            if(SHOW_ECHO_ERROR)
            {
                Log::write( "<p> Сайт проанализирован, таблица ".$this->tb_toggle." НЕ изменена ! ошибка ".mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            }
            exit();
        }*/
        //>>end------------------------------------------------------------






        Log::write( "Начало копирования отчета", $this->host_site );
        list($usec, $sec) = explode(" ", microtime());
        $time_start = ((float)$sec + (float)$usec);

        $this->ModelStopCreateReport( $this->counter_scan );

        list($usec, $sec) = explode(" ", microtime());
        $time_stop = ((float)$sec + (float)$usec);
        $int = ((int)$time_stop - (int)$time_start);
        $float = ($time_stop - $time_start);
        Log::write( "Конец копирования отчета", $this->host_site );
        Log::write( "Время копирования отчета".($int/60)."мин. ".$int." сек. ".$float." мсек.", $this->host_site );


        $this->ModelAutomaticGetMoneyInBalance();//возвращает лишние деньги на баланс
        $this->ModelSendReportToEmail();//отправляем отчёт по почте клиенту


        Log::write("***************************STOP*********делаем сравнение !!!******************", $this->host_site);


        //-----------------------------------------------------
        $this->ModelRepeatAnalysis();
        //-----------------------------------------------------


        //<<--------меняем флаг в табл. toggle - сайт проанализирован !!!
        $upd = "update ".$this->tb_toggle." set flag=1";
        if(mysql_query( $upd, $this->link ))
        {
            if(SHOW_ECHO)
            {
                Log::write( "<p>Сайт проанализирован, таблица ".$this->tb_toggle." изменена !</p>", $this->host_site );
            }

            /*Log::write( "Начало копирования отчета", $this->host_site );
            list($usec, $sec) = explode(" ", microtime());
            $time_start = ((float)$sec + (float)$usec);

            $this->ModelStopCreateReport( $this->counter_scan );

            list($usec, $sec) = explode(" ", microtime());
            $time_stop = ((float)$sec + (float)$usec);
            $int = ((int)$time_stop - (int)$time_start);
            $float = ($time_stop - $time_start);
            Log::write( "Конец копирования отчета", $this->host_site );
            Log::write( "Время копирования отчета".($int/60)."мин. ".$int." сек. ".$float." мсек.", $this->host_site );*/

        }
        else
        {
            if(SHOW_ECHO_ERROR)
            {
                Log::write( "<p> Сайт проанализирован, таблица ".$this->tb_toggle." НЕ изменена ! ошибка ".mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            }
            exit();
        }
        //>>end------------------------------------------------------------
    }

    //-----------------методы перезагрузки страницы незагруженной из-зи окончания таймаута-----------------------------
    function ModelScan_PageLoadTimeout()
    {
        $sel = "select id, url, parent_url, level_links from ".$this->tb_pages." where http_code=".self::$curl_operation_timeout;
        $res = mysql_query($sel,$this->link);

        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        else
        {
            $ar_url_page = array();//чистим
            while ($page = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $ar_url_page[] = array('id' => intval($page['id']), 'url' => html_entity_decode($page['url'], ENT_QUOTES, 'UTF-8' ), 'level_links' => intval($page['level_links']), 'parent_url' => html_entity_decode($page['parent_url'], ENT_QUOTES, 'UTF-8' ) );
            }

            if( count($ar_url_page) )
            {
                foreach($ar_url_page as $page)
                {
                    if($this->limiter_pages < $this->limit_pages)
                    {
                        $this->ModelScan_PageLoadTimeout_AddPages_AddInfo($page);
                    }
                    else
                    {
                        break;
                    }
                }
                if($this->count_pages > $this->cur_page)
                {
                    if(isset($this->html))
                    {
                        if(is_object($this->html))
                        {
                            $this->html->clear();
                        }
                        unset($this->html);
                    }

                    for( true; $this->cur_page < $this->count_pages; true )
                    {
                        if($this->limiter_pages < $this->limit_pages)
                        {
                            $this->ModelScan_PageLoadTimeout_AddPageRepeat(++$this->cur_page);
                            if( ($this->cur_page % 10) == 0 || ($this->cur_page % 10) == 5 )
                            {
                                $upd = "update ".$this->tb_counter." set counter_compare_page=".$this->cur_page.", counter_compare_pages=".$this->count_pages." where id=".$this->counter_scan;
                                if(!mysql_query( $upd, $this->link ))
                                {
                                    if(SHOW_ECHO_ERROR)
                                        Log::write( "<p> ошибка ".mysql_errno()." при работе с табл. counter ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                    exit();
                                }
                            }
                        }
                        else
                        {
                            break;
                        }
                    }
                }
            }
        }
    }

    function ModelScan_PageLoadTimeout_AddPages_AddInfo( $page )//$page - не обьект Page(), а массив array('id'=>[id], 'url'=>[url], 'level_links'=>[level_links], 'parent_url'=>[parent_url]);
    {
        $flag = false;
        $url = $page['url'];
        $ar_components_link = parse_url($url);

        if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
            $tmp_url = $url;
        }else{
            if(strpos($ar_components_link['path'], '/') === 0){
                $tmp_url = $this->protocol.'://'.$this->host_site.$url;
            }else{
                $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$url;
            }
        }

        $this->html_data = '';
        $tmp_level_links = $page['level_links'];

        $this->ModelCurlInit($ch, $tmp_url);

        if(curl_exec($ch))
        {
            $ar = curl_getinfo($ch);

            if( (isset($ar['url']) && parse_url($ar['url'], PHP_URL_HOST) == $this->host_site)
                && (isset($ar['http_code']) && $ar['http_code'] == 200)
                && (isset($ar['content_type']) && strstr($ar['content_type'], 'text/html')) )
            {
                if( $ar['size_download'] == strlen($this->html_data) )
                {
                    $this->ModelSetCurrentUrl( $tmp_url, $this->html_data );
                    if($this->html->_charset != $this->html->_target_charset)
                        $this->html_data = iconv($this->html->_charset, $this->html->_target_charset, $this->html_data);
                }

                $z = $this->ModelScan_PageLoadTimeout_DelReserve($page);
                $this->ModelScan_PageLoadTimeout_Compare( $url, $ar, $tmp_level_links, $page['parent_url'], $z );
                //<работаем над поиском <a>---------------------------------------------------------------
                $ar_internal_links = array();
                $ar_lines_links    = array();

                if($this->html)
                {
                    if($this->html->innertext != '' && count($this->html->find('a')))
                    {
                        $ar_links_current_page    = $this->html->find('a');
                        $count_links_current_page = count( $ar_links_current_page );

                        if( $count_links_current_page > 0)
                        {
                            $ar_internal_links = array();
                            foreach($ar_links_current_page as $link)
                            {
                                if(!$link->href)continue;

                                $ar_components_link = parse_url( $link->href );
                                if( isset($ar_components_link['host']) && $ar_components_link['host'] == $this->host_site )
                                {//если в url есть хост и он == хосту сайта
                                    if( !in_array($link->href, $ar_internal_links) )
                                    {
                                        $ar_internal_links[] = $link->href;
                                    }
                                }
                                else if( !isset($ar_components_link['host']) && isset($ar_components_link['path']))
                                {//если в url нет хоста, но есть /path сайта
                                    if( ( (strpos($link->href, 'javascript:')!==0) && (strpos($link->href, 'Javascript:')!==0) && (strpos($link->href, 'mailto:')!==0) ) && !in_array($link->href, $ar_internal_links))
                                    {
                                        $ar_internal_links[] = $link->href;
                                    }
                                }
                            }//end foreach
                        }
                    }
                }
                //>>end-----------------------------------------------------------------------------------

                $this->limiter_pages++;
                if($this->limiter_pages < $this->limit_pages)
                {
                    //<получаем ссылки со страницы-----------------------------------------------
                    if( count( $ar_internal_links ) )
                    {
                        $ar_url_page = array();//чистим
                        foreach($this->ar_pages as $page)
                        {
                            $ar_url_page[] = $page->url;
                        }

                        $ar_lines_links = array();
                        foreach($ar_internal_links as $href)
                        {
                            if( !in_array($href, $ar_url_page) )
                            {
                                $ar_lines_links[] = $href;
                            }
                        }
                        $ar_url_page = array();//чистим
                    }
                    //>end--------------------------------------------------------------------

                    //<добавляем новые ссылки в таблицу pages---------------------------------
                    if( count($ar_lines_links) )
                    {
                        $tmp_level_links++;

                        foreach( $ar_lines_links as $link )
                        {
                            $this->ar_pages[++$this->count_pages] = new Link($link, $tmp_level_links, $url);
                        }
                    }
                    //>end--------------------------------------------------------------------
                }

                if(isset($this->html))
                {
                    if(is_object($this->html))
                    {
                        $this->html->clear();
                    }
                    unset($this->html);
                }
                $flag = true;
            }
            else
            {
                $z = $this->ModelScan_PageLoadTimeout_DelReserve($page);
                $this->ModelScan_PageLoadTimeout_Compare( $url, $ar, $tmp_level_links, $page['parent_url'], $z );
                $flag = true;
            }
        }
        else
        {
            //здесь могут быть ошибки :
            //404 и т.п.                 - The requested URL returned error: 404
            //превышение лимита ожидания - Operation timed out after 10000 milliseconds with 0 bytes received

            //если константа REDIRECT == true, может быть ошибка :
            //превышение лимита редиректа $this->curl_count_redirects - Maximum (limit) redirects followed
            $this->ModelChargeTimeout( curl_errno($ch), __METHOD__, $page );
            $flag = $this->ModelStartTimeout();
            if(!$flag)
            {
                $ar = curl_getinfo($ch);
                $ar['http_code']    = curl_errno($ch);
                $ar['content_type'] = curl_error($ch);

                $z = $this->ModelScan_PageLoadTimeout_DelReserve($page);
                //$this->ModelScan_PageLoadTimeout_UpdateNotLoadedPage($url, $ar, $tmp_level_links, $page['parent_url'], $z);
                $this->ModelScan_PageLoadTimeout_Compare($url, $ar, $tmp_level_links, $page['parent_url'], $z);
                $flag = true;
            }
            $this->ModelStopTimeout();
        }
        curl_close($ch);
        unset($ch);
        unset($ar);
        return $flag;
    }

    function ModelScan_PageLoadTimeout_DelReserve($page)
    {
        $flag = false;
        $sel = "select reserve_http_code, reserve_content_type from ".$this->tb_pages." where id=".$page['id'];
        $res = mysql_query($sel,$this->link);

        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        else
        {
            $reserve_http_code = 0;
            $reserve_content_type = '';

            while ($p = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $reserve_http_code = $p['reserve_http_code'];
                $reserve_content_type = html_entity_decode( $p['reserve_content_type'], ENT_QUOTES, 'UTF-8' );
            }

            if($reserve_http_code && $reserve_content_type)
            {
                $upd = "update ".$this->tb_pages
                    ." set http_code=".$reserve_http_code
                    .", content_type='".addslashes(htmlentities($reserve_content_type , ENT_QUOTES, 'UTF-8' ))
                    ."', reserve_http_code=NULL, reserve_content_type=NULL where id=".$page['id'];
                if(!mysql_query( $upd, $this->link ))
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
                $flag = true;
            }
        }
        return $flag;
    }

    function ModelScan_PageLoadTimeout_AddPageRepeat( $num )
    {
        $flag = false;
        $url = $this->ar_pages[$num]->url;
        $ar_components_link = parse_url($url);

        if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
            $tmp_url = $url;
        }else{
            if(strpos($ar_components_link['path'], '/') === 0){
                $tmp_url = $this->protocol.'://'.$this->host_site.$url;
            }else{
                $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$url;
            }
        }

        $this->html_data = '';
        $tmp_level_links = $this->ar_pages[$num]->level;
        $parent_url = $this->ar_pages[$num]->url;

        $this->ModelCurlInit($ch, $tmp_url);

        if(curl_exec($ch))
        {
            $ar = curl_getinfo($ch);

            if( (isset($ar['url']) && parse_url($ar['url'], PHP_URL_HOST) == $this->host_site)
                && (isset($ar['http_code']) && $ar['http_code'] == 200)
                && (isset($ar['content_type']) && strstr($ar['content_type'], 'text/html')) )
            {
                if( $ar['size_download'] == strlen($this->html_data) )
                {
                    $this->ModelSetCurrentUrl( $tmp_url, $this->html_data );
                    if($this->html->_charset != $this->html->_target_charset)
                        $this->html_data = iconv($this->html->_charset, $this->html->_target_charset, $this->html_data);
                }
                $this->ModelScan_PageLoadTimeout_Compare( $url, $ar, $tmp_level_links, $this->ar_pages[$num]->parent_url );
                //<работаем над поиском <a>---------------------------------------------------------------
                $ar_internal_links = array();
                $ar_lines_links    = array();

                if($this->html)
                {
                    if($this->html->innertext != '' && count($this->html->find('a')))
                    {
                        $ar_links_current_page    = $this->html->find('a');
                        $count_links_current_page = count( $ar_links_current_page );

                        if( $count_links_current_page > 0)
                        {
                            $ar_internal_links = array();
                            foreach($ar_links_current_page as $link)
                            {
                                if(!$link->href)continue;

                                $ar_components_link = parse_url( $link->href );
                                if( isset($ar_components_link['host']) && $ar_components_link['host'] == $this->host_site )
                                {//если в url есть хост и он == хосту сайта
                                    if( !in_array($link->href, $ar_internal_links) )
                                    {
                                        $ar_internal_links[] = $link->href;
                                    }
                                }
                                else if( !isset($ar_components_link['host']) && isset($ar_components_link['path']))
                                {//если в url нет хоста, но есть /path сайта
                                    if( ( (strpos($link->href, 'javascript:')!==0) && (strpos($link->href, 'Javascript:')!==0) && (strpos($link->href, 'mailto:')!==0) ) && !in_array($link->href, $ar_internal_links))
                                    {
                                        $ar_internal_links[] = $link->href;
                                    }
                                }
                            }//end foreach
                        }
                    }
                }
                //>>end-----------------------------------------------------------------------------------

                $this->limiter_pages++;
                if($this->limiter_pages < $this->limit_pages)
                {
                    //<получаем ссылки со страницы-----------------------------------------------
                    if( count( $ar_internal_links ) )
                    {
                        $ar_url_page = array();//чистим
                        foreach($this->ar_pages as $page)
                        {
                            $ar_url_page[] = $page->url;
                        }

                        $ar_lines_links = array();
                        foreach($ar_internal_links as $href)
                        {
                            if( !in_array($href, $ar_url_page) )
                            {
                                $ar_lines_links[] = $href;
                            }
                        }
                        $ar_url_page = array();//чистим
                    }
                    //>end--------------------------------------------------------------------

                    //<добавляем новые ссылки в таблицу pages---------------------------------
                    if( count($ar_lines_links) )
                    {
                        $tmp_level_links++;

                        foreach( $ar_lines_links as $link )
                        {
                            $this->ar_pages[++$this->count_pages] = new Link($link, $tmp_level_links, $parent_url);
                        }
                    }
                    //>end--------------------------------------------------------------------
                }

                if(isset($this->html))
                {
                    if(is_object($this->html))
                    {
                        $this->html->clear();
                    }
                    unset($this->html);
                }
                $flag = true;
            }
            else
            {
                $this->ModelScan_PageLoadTimeout_Compare( $url, $ar, $tmp_level_links, $this->ar_pages[$num]->parent_url );
                $flag = true;
            }
        }
        else
        {
            //здесь могут быть ошибки :
            //404 и т.п.                 - The requested URL returned error: 404
            //превышение лимита ожидания - Operation timed out after 10000 milliseconds with 0 bytes received

            //если константа REDIRECT == true, может быть ошибка :
            //превышение лимита редиректа $this->curl_count_redirects - Maximum (limit) redirects followed
            $this->ModelChargeTimeout( curl_errno($ch), __METHOD__, $num );
            $flag = $this->ModelStartTimeout();
            if(!$flag)
            {
                $ar = curl_getinfo($ch);
                $ar['http_code']    = curl_errno($ch);
                $ar['content_type'] = curl_error($ch);

                //$this->ModelScan_PageLoadTimeout_UpdateNotLoadedPage($url, $ar, $tmp_level_links, $this->ar_pages[$num]->parent_url);
                $this->ModelScan_PageLoadTimeout_Compare($url, $ar, $tmp_level_links, $this->ar_pages[$num]->parent_url);
                $flag = true;
            }
            $this->ModelStopTimeout();
        }
        curl_close($ch);
        unset($ch);
        unset($ar);
        return $flag;
    }

    /*function ModelScan_PageLoadTimeout_UpdateNotLoadedPage($page_url, $ar, $level_links, $parent_url, $flag=true)
    {
        // url | array(error | errno) | level_links
        $this->ModelScan_PageLoadTimeout_Compare( $page_url, $ar, $level_links, $parent_url, $flag );
    }*/

    function ModelScan_PageLoadTimeout_Compare( $url, $ar, $level_links, $parent_url, $flag=true, $exist=1 )
    {
        $sel = "select * from ".$this->tb_pages." where url like '".addslashes(htmlentities($url , ENT_QUOTES, 'UTF-8' ))."'";
        $res = mysql_query($sel,$this->link);

        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        else
        {
            $page = null;
            while ($line = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $page = new Page();
                $page->id                  = intval( $line['id'] );
                $page->url                 = html_entity_decode( $line['url'], ENT_QUOTES, 'UTF-8' );
                $page->page_code           = html_entity_decode( $line['page_code'], ENT_QUOTES, 'UTF-8' );
                $page->http_code           = intval( $line['http_code'] );
                $page->content_type        = html_entity_decode( $line['content_type'], ENT_QUOTES, 'UTF-8' );
                $page->redirect_url        = html_entity_decode( $line['redirect_url'], ENT_QUOTES, 'UTF-8' );
                $page->parent_url          = html_entity_decode( $line['parent_url'], ENT_QUOTES, 'UTF-8' );
                $page->title               = intval( $line['title'] );
                $page->load_count          = intval( $line['load_count'] );
                $page->load_timeout        = intval( $line['load_timeout'] );
                $page->keywords            = intval( $line['keywords'] );
                $page->description         = intval( $line['description'] );
                $page->level_links         = intval( $line['level_links']);
                $page->connect_time        = floatval( $line['connect_time'] );
                $page->total_time          = floatval( $line['total_time'] );
                $page->page_size           = floatval( $line['page_size'] );
                $page->page_speed          = floatval( $line['page_speed'] );
                $page->links               = intval( $line['links'] );
                $page->images              = intval( $line['images'] );
                $page->content             = intval( $line['content'] );
                $page->scan                = intval( $line['scan'] );
                $page->exist               = intval( $line['exist'] );
                $page->create_page         = intval( $line['create_page'] );
                $page->update_page         = intval( $line['update_page'] );
                $page->delete_page         = intval( $line['delete_page'] );
                $page->date_change_page    = $line['date_change_page'];

                $page->change_page_code    = intval( $line['change_page_code'] );
                $page->change_http_code    = intval( $line['change_http_code'] );
                $page->change_content_type = intval( $line['change_content_type'] );
                $page->change_redirect_url = intval( $line['change_redirect_url'] );
                $page->change_parent_url   = intval( $line['change_parent_url'] );
                $page->change_load_count   = intval( $line['change_load_count'] );
                $page->change_load_timeout = intval( $line['change_load_timeout'] );
                $page->change_title        = intval( $line['change_title'] );
                $page->change_keywords     = intval( $line['change_keywords'] );
                $page->change_description  = intval( $line['change_description'] );
                $page->change_level_links  = intval( $line['change_level_links'] );
                $page->change_total_time   = intval( $line['change_total_time'] );
                $page->change_page_size    = intval( $line['change_page_size'] );
                $page->change_page_speed   = intval( $line['change_page_speed'] );
                $page->change_links        = intval( $line['change_links'] );
                $page->change_images       = intval( $line['change_images'] );
                $page->change_content      = intval( $line['change_content'] );
                $page->change_exist        = intval( $line['change_exist'] );

                $page->reserve_http_code   = intval( $line['reserve_http_code'] );
                $page->reserve_content_type= html_entity_decode( $line['reserve_content_type'], ENT_QUOTES, 'UTF-8' );

                $this->ModelAddInitPropertyInObjectPage($page, $line);
            }

            //если в таблице pages есть такой URL, тогда
            //сравниваем !

            if($page)
            {
                $this->ModelCompare_Mysql_GetObjectsOfMysql( $page );//собираем обьект из MySql !!!

                $page_compare = new Page();
                //<<заносим html-code----------------------------------------------------------------------
                $page_compare->url              = $url;
                $page_compare->http_code        = $ar['http_code'];
                $page_compare->content_type     = $ar['content_type'];
                $page_compare->redirect_url     = $ar['redirect_url'];
                $page_compare->parent_url       = $parent_url;
                $page_compare->load_count       = self::$load_count;
                $page_compare->load_timeout     = self::$load_timeout;
                $page_compare->connect_time     = $ar['connect_time'];
                $page_compare->total_time       = $ar['total_time'];
                $page_compare->page_size        = $ar['size_download'];
                $page_compare->page_speed       = $ar['speed_download'];
                $page_compare->level_links      = $level_links;
                $page_compare->scan             = $this->counter_scan;
                $page_compare->exist            = $exist;
                $page_compare->date_change_page = date("Y-m-d H:i:s",mktime());

                if($page_compare->http_code == 200 && strstr($ar['content_type'], 'text/html'))
                {
                    $page_compare->page_code  = $this->html_data;
                    //$page_compare->total_time       = $ar['total_time'];
                    //$page_compare->page_size  = $ar['size_download'];
                    //$page_compare->page_speed = $ar['speed_download'];
                }
                $this->ModelAddPropertyInObjectPage($page_compare);

                //<<работаем над поиском <title>-----------------------------------------------------------
                $this->ModelCompare_FindTitle( $page_compare );
                //<<работаем над поиском <meta Keywords> & <meta Description>------------------------------
                $this->ModelCompare_FindKeywordsDescription( $page_compare );
                //<<работаем над поиском <a>---------------------------------------------------------------
                $this->ModelCompare_FindLinks( $page_compare );
                //<<работаем над поиском <img>-------------------------------------------------------------
                $this->ModelCompare_FindImages( $page_compare );
                //<<работаем над поиском content-------------------------------------------------------------
                $this->ModelCompare_FindContent( $page_compare );
                //<<работаем над поиском tags h1...h6, b, strong---------------------------------------------
                $this->ModelCompare_FindTags( $page_compare );

                if($flag)
                {
                    //<<сравниваем обьекты-страницы !
                    if($this->ModelCompare_ComparingObjectsPage( $page, $page_compare ))
                    {
                        $this->ModelCompare_UpdatePage( $page, $page_compare );
                    }
                    else
                    {
                        $this->ModelCompare_UpdatePage_FieldScan( $page, $this->counter_scan );
                    }
                }
                else
                {
                    $this->ModelCompare_Update_Page_itPageCompare( $page, $page_compare );
                }
            }
            else
            {
                //парсинг едиственной НОВОЙ страницы, найденной при сравнении
                $this->ModelScan_PageLoadTimeout_AddSinglePage( $url, $ar, $level_links, $parent_url );
            }
        }
    }

    function ModelScan_PageLoadTimeout_AddSinglePage( $url, $ar, $level_links, $parent_url )
    {
        if (!$this->link)
        {
            $this->link = mysql_connect($this->host, $this->root_login, $this->root_password);
            if (!$this->link)
            {
                if(SHOW_ECHO_ERROR_CONNECT_DB)
                    Log::write( "<p> Ошибка соединения: ".mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit() ;
            }

            if(!mysql_select_db($this->db_name, $this->link))
            {
                if(SHOW_ECHO_ERROR_CONNECT_DB)
                    Log::write( "<p> Ошибка подключения к БД ".mysql_errno()."</p>", $this->host_site );
                exit();
            }
            mysql_query("SET NAMES utf8"); // теперь всё будет сохранятся в MySql правильной кодировке
        }

        $ins = "insert into ".$this->tb_pages."(
        url,
        parent_url,
        redirect_url,
        http_code,
        content_type,
        level_links,
        scan,
        connect_time,
        total_time,
        page_size,
        page_speed,
        load_count,
        load_timeout,
        exist,
        create_page,
        date_change_page ) value ('"
            .addslashes(htmlentities($url , ENT_QUOTES, 'UTF-8' ))
            ."', '".addslashes(htmlentities($parent_url , ENT_QUOTES, 'UTF-8' ))
            ."', ".($ar['redirect_url']?("'".addslashes(htmlentities( $ar['redirect_url'], ENT_QUOTES, 'UTF-8' ))."'"):'NULL')
            .", ".$ar['http_code']
            .", '".addslashes(htmlentities($ar['content_type'] , ENT_QUOTES, 'UTF-8' ))
            ."', ".$level_links
            .", ".$this->counter_scan
            .", ".$ar['connect_time']
            .", ".$ar['total_time']
            .", ".($ar['size_download']?$ar['size_download']:'NULL')
            .", ".($ar['speed_download']?$ar['speed_download']:'NULL')
            .", ".self::$load_count
            .", ".self::$load_timeout
            .", 1,
            1, '"
            .date("Y-m-d H:i:s",mktime())."' )";

        if(!mysql_query( $ins, $this->link ))
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        if( (isset($ar['http_code']) && $ar['http_code'] == 200)
            && (isset($ar['content_type']) && strstr($ar['content_type'], 'text/html')) )
        {
            //<<ПОЛНЫЙ ПАРСЕР СТАРНИЦЫ !!!------------------------------------------------
            $this->id_current_page = mysql_insert_id();
            $this->ar_pages[$this->cur_page]->id = $this->id_current_page;

            $this->ModelAddTitleToPage();  //<<получаем title из html и его в БД
            $this->ModelAddMetaToPage();   //<<получаем мета-теги из html и заносим их в БД
            $this->ModelUpdatePageIDTitleIDKeywordsIDDescription($ar['size_download'],$ar['speed_download'],$ar['total_time']);
            $this->ModelAddLinksSinglePage( $this->id_current_page );
            $this->ModelAddImgToPage();    //<<получаем img из html и их в БД
            $this->ModelAddContentToPage();
            $this->ModelAddTagsToPage($this->id_current_page);

            $this->id_current_page = 0;
            //>>end ПОЛНЫЙ ПАРСЕР СТАРНИЦЫ !!!--------------------------------------------
        }
        /*if($ar['http_code'] == 28)
        {
            //<<ПОЛНЫЙ ПАРСЕР СТАРНИЦЫ !!!------------------------------------------------
            $this->ar_pages[$this->cur_page]->id = mysql_insert_id();
            $this->ar_pages[$this->cur_page]->parent_url = $parent_url;
            $this->ModelScan_PageLoadTimeout_AddSinglePageInfo($this->ar_pages[$this->cur_page]);
            //>>end ПОЛНЫЙ ПАРСЕР СТАРНИЦЫ !!!--------------------------------------------
        }
        else
        {
            //<<ПОЛНЫЙ ПАРСЕР СТАРНИЦЫ !!!------------------------------------------------
            $this->id_current_page = mysql_insert_id();
            $this->ar_pages[$this->cur_page]->id = $this->id_current_page;

            $this->ModelAddTitleToPage();  //<<получаем title из html и его в БД
            $this->ModelAddMetaToPage();   //<<получаем мета-теги из html и заносим их в БД
            $this->ModelUpdatePageIDtitleIDkeywordsIDdescription($ar['size_download'],$ar['speed_download'],$ar['total_time']);//<<заносим html-код в БД + размер загужаемой страницы в Kb
            $this->ModelAddLinksSinglePage( $this->id_current_page );
            $this->ModelAddImgToPage();    //<<получаем img из html и их в БД
            $this->ModelAddContentToPage();
            $this->ModelAddTagsToPage($this->id_current_page);

            $this->id_current_page = 0;
            //>>end ПОЛНЫЙ ПАРСЕР СТАРНИЦЫ !!!--------------------------------------------
        }*/
    }

    //этот метод можно подключить (раскоментировать в ModelScan_PageLoadTimeout_AddSinglePage();)
    //как дополнительную загрузку страницы, если она не была загружена!
    function ModelScan_PageLoadTimeout_AddSinglePageInfo( $page ) // $page == Link
    {
        $flag = false;
        $url = $page->url;
        $ar_components_link = parse_url($url);

        if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
            $tmp_url = $url;
        }else{
            if(strpos($ar_components_link['path'], '/') === 0){
                $tmp_url = $this->protocol.'://'.$this->host_site.$url;
            }else{
                $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$url;
            }
        }

        $this->html_data = '';
        $tmp_level_links = $page->level;

        $this->ModelCurlInit($ch, $tmp_url);

        if(curl_exec($ch))
        {
            $ar = curl_getinfo($ch);

            if( (isset($ar['url']) && parse_url($ar['url'], PHP_URL_HOST) == $this->host_site)
                && (isset($ar['http_code']) && $ar['http_code'] == 200)
                && (isset($ar['content_type']) && strstr($ar['content_type'], 'text/html')) )
            {
                if( $ar['size_download'] == strlen($this->html_data) )
                {
                    $this->ModelSetCurrentUrl( $tmp_url, $this->html_data );
                    if($this->html->_charset != $this->html->_target_charset)
                        $this->html_data = iconv($this->html->_charset, $this->html->_target_charset, $this->html_data);
                }
                //формируем запрос
                $upd = "update ".$this->tb_pages
                    ." set page_code='".addslashes(htmlentities($this->html_data , ENT_QUOTES, 'UTF-8' ))
                    ."', http_code=".$ar['http_code']
                    .", content_type='".addslashes(htmlentities($ar['content_type'] , ENT_QUOTES, 'UTF-8' ))
                    ."', date_change_page='".date("Y-m-d H:i:s",mktime())
                    ."', load_count=".self::$load_count
                    .", load_timeout=".self::$load_timeout
                    .", total_time=".$ar['total_time']
                    .", connect_time=".$ar['connect_time']
                    .", page_size=".$ar['size_download']
                    .", page_speed=".$ar['speed_download']
                    ." where id=".$page->id;
                if(mysql_query( $upd, $this->link ))
                {
                    if(SHOW_ECHO)
                        Log::write( "<p> Ваша страничка №".$page->id." изменена в БД !</p>", $this->host_site );
                }
                else
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }

                $this->id_current_page = $page->id;
                $this->ModelAddTitleToPage();  //<<получаем title из html и его в БД
                $this->ModelAddMetaToPage();   //<<получаем мета-теги из html и заносим их в БД
                $this->ModelUpdatePageIDTitleIDKeywordsIDDescription($ar['size_download'],$ar['speed_download'],$ar['total_time']);//<<заносим html-код в БД + размер загужаемой страницы в Kb
                $this->ModelAddLinksSinglePage( $page->id );
                $this->ModelAddImgToPage();    //<<получаем img из html и их в БД
                $this->ModelAddContentToPage();
                $this->ModelAddTagsToPage($page->id);
                $this->id_current_page = 0;

                //<работаем над поиском <a>---------------------------------------------------------------
                $ar_internal_links = array();
                $ar_lines_links    = array();

                if($this->html)
                {
                    if($this->html->innertext != '' && count($this->html->find('a')))
                    {
                        $ar_links_current_page    = $this->html->find('a');
                        $count_links_current_page = count( $ar_links_current_page );

                        if( $count_links_current_page > 0)
                        {
                            $ar_internal_links = array();
                            foreach($ar_links_current_page as $link)
                            {
                                if(!$link->href)continue;

                                $ar_components_link = parse_url( $link->href );
                                if( isset($ar_components_link['host']) && $ar_components_link['host'] == $this->host_site )
                                {//если в url есть хост и он == хосту сайта
                                    if( !in_array($link->href, $ar_internal_links) )
                                    {
                                        $ar_internal_links[] = $link->href;
                                    }
                                }
                                else if( !isset($ar_components_link['host']) && isset($ar_components_link['path']))
                                {//если в url нет хоста, но есть /path сайта
                                    if( ( (strpos($link->href, 'javascript:')!==0) && (strpos($link->href, 'Javascript:')!==0) && (strpos($link->href, 'mailto:')!==0) ) && !in_array($link->href, $ar_internal_links))
                                    {
                                        $ar_internal_links[] = $link->href;
                                    }
                                }
                            }//end foreach
                        }
                    }
                }
                //>>end-----------------------------------------------------------------------------------

                //<получаем ссылки со страницы-----------------------------------------------
                if( count( $ar_internal_links ) )
                {
                    $ar_url_page = array();//чистим
                    foreach($this->ar_pages as $page)
                    {
                        $ar_url_page[] = $page->url;
                    }

                    $ar_lines_links = array();
                    foreach($ar_internal_links as $href)
                    {
                        if( !in_array($href, $ar_url_page) )
                        {
                            $ar_lines_links[] = $href;
                        }
                    }
                    $ar_url_page = array();//чистим
                }
                //>end--------------------------------------------------------------------

                //<добавляем новые ссылки в таблицу pages---------------------------------
                if( count($ar_lines_links) )
                {
                    $tmp_level_links++;

                    foreach( $ar_lines_links as $link )
                    {
                        $this->ar_pages[++$this->count_pages] = new Link($link, $tmp_level_links, $url);
                    }
                }
                //>end--------------------------------------------------------------------
                if(isset($this->html))
                {
                    if(is_object($this->html))
                    {
                        $this->html->clear();
                    }
                    unset($this->html);
                }
                $flag = true;
            }
            else
            {
                //формируем запрос
                $upd = "update ".$this->tb_pages
                    ." set page_code='', http_code=".$ar['http_code']
                    .", content_type='".addslashes(htmlentities($ar['content_type'] , ENT_QUOTES, 'UTF-8' ))
                    ."', redirect_url=".($ar['redirect_url']?("'".addslashes(htmlentities( $ar['redirect_url'], ENT_QUOTES, 'UTF-8' ))."'"):'NULL')
                    .", load_count=".self::$load_count
                    .", load_timeout=".self::$load_timeout
                    .", total_time=".curl_getinfo($ch, CURLINFO_TOTAL_TIME )
                    .", connect_time=".curl_getinfo($ch, CURLINFO_CONNECT_TIME )
                    .($ar['size_download']?(", page_size=".$ar['size_download']):"")
                    .($ar['speed_download']?(", page_speed=".$ar['speed_download']):"")
                    ." where id=".$page->id;

                if(mysql_query( $upd, $this->link ))
                {
                    if(SHOW_ECHO)
                        Log::write( "<p> Ваша страничка №".$page->id." изменена в БД !</p>", $this->host_site );
                }
                else
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
                $flag = true;
            }
        }
        else
        {
            //здесь могут быть ошибки :
            //404 и т.п.                 - The requested URL returned error: 404
            //превышение лимита ожидания - Operation timed out after 10000 milliseconds with 0 bytes received

            //если константа REDIRECT == true, может быть ошибка :
            //превышение лимита редиректа $this->curl_count_redirects - Maximum (limit) redirects followed
            $this->ModelChargeTimeout( curl_errno($ch), __METHOD__, $page );
            $flag = $this->ModelStartTimeout();
            if(!$flag)
            {
                $ar = curl_getinfo($ch);
                $ar['http_code']    = curl_errno($ch);
                $ar['content_type'] = curl_error($ch);

                //$this->ModelScan_PageLoadTimeout_UpdateNotLoadedPage($url, $ar, $tmp_level_links, $page->parent_url);
                $this->ModelScan_PageLoadTimeout_Compare($url, $ar, $tmp_level_links, $page->parent_url);

                $flag = true;
            }
            $this->ModelStopTimeout();
        }
        curl_close($ch);
        unset($ch);
        unset($ar);
        return $flag;
    }


    //=================================================================================================================
    //-----------------методы регистрации и авторизации пользователя---------------------------------------------------
    //=================================================================================================================

    //возвращает один символ из multibyte-строки по указанной позиции
    function getLetter($data, $position)
    {
        return mb_substr($data, $position, 1);
    }

    //возвращает асоциативный массив,
    //где ключ и значение елемента - это символ строки
    //$str = 'abc';
    //array['a']='a';
    //array['b']='b';
    //array['c']='c';
    function getArrayAssoc($str)
    {
        $ar = array();
        for($i = 0; $i < mb_strlen($str); $i++)
        {
            $key = $this->getLetter($str, $i);
            $ar[$key] = $key;
        }
        return $ar;
    }

    //возвращает номерованный массив,
    //где ключ - это позиция элемента в строке, а значение елемента - это символ строки
    //$str = 'abc';
    //array[0]='a';
    //array[1]='b';
    //array[2]='c';
    function getArrayNumb($str)
    {
        $ar = array();
        for($i = 0; $i < mb_strlen($str); $i++)
        {
            $ar[$i] = $this->getLetter($str, $i);
        }
        return $ar;
    }

    function validUserLoginRegister($user_str)
    {
        if($user_str == '')
        {
            parent::SetErrors('login','указали логин слишком короткий');
            return false;
        }
        elseif(mb_strlen($user_str) > 20)
        {
            parent::SetErrors('login','указали логин слишком длинный');
            return false;
        }
        $user_array_letters = $this->getArrayAssoc($user_str);
        $tmpl_array_letters = $this->getArrayAssoc($this->reg_str_login);
        $count_error = 0;
        foreach($user_array_letters as $key)
        {
            if(!array_key_exists($key, $tmpl_array_letters)) $count_error++;
        }
        if($count_error > 0)
        {
            //запрещённые символы !
            parent::SetErrors('login','указали запрещённые символы !');
            return false;
        }
        $sel = "select id from ".self::$service_table_name_users." where login='".$user_str."'";
        $res = mysql_query($sel, $this->link);
        if(mysql_errno())
        {
            parent::SetErrors('login', "ошибка бд: ".mysql_errno());
            return false;
        }
        elseif( mysql_num_rows( $res ) > 0 )
        {
            parent::SetErrors('login', 'такой логин уже занят !');
            return false;
        }
        else return true;
    }

    function validUserLoginAutoriz($user_str)
    {
        if($user_str == '')
        {
            parent::SetErrors('login','указали логин слишком короткий');
            return false;
        }
        elseif(mb_strlen($user_str) > 20)
        {
            parent::SetErrors('login','указали логин слишком длинный');
            return false;
        }
        $user_array_letters = $this->getArrayAssoc($user_str);
        $tmpl_array_letters = $this->getArrayAssoc($this->reg_str_login);
        $count_error = 0;
        foreach($user_array_letters as $key)
        {
            if(!array_key_exists($key, $tmpl_array_letters)) $count_error++;
        }
        if($count_error > 0)
        {
            //запрещённые символы !
            parent::SetErrors('login','указали запрещённые символы !');
            return false;
        }
        return true;
    }

    function validUserPassword($user_str)
    {
        if($user_str == '')
        {
            parent::SetErrors('password', 'указали пароль слишком короткий');
            return false;
        }
        elseif(mb_strlen($user_str) > 16)
        {
            parent::SetErrors('password', 'указали пароль слишком длинный');
            return false;
        }
        $user_array_letters = $this->getArrayAssoc($user_str);
        $tmpl_array_letters = $this->getArrayAssoc($this->reg_str_password);
        $count_error = 0;
        foreach($user_array_letters as $key)
        {
            if(!array_key_exists($key, $tmpl_array_letters)) $count_error++;
        }
        if($count_error > 0)
        {
            parent::SetErrors('password', 'указали в пароле запрещённые символы !');
            return false;
        }
        return true;
    }

    function validUserEmail($user_str)
    {
        if($user_str == '')
        {
            parent::SetErrors('email', 'Email слишком короткий');
            return false;
        }
        elseif(mb_strlen($user_str) > 50)
        {
            parent::SetErrors('email', 'Email слишком длинный');
            return false;
        }
        if(preg_match($this->reg_str_email, $user_str)) return true;
        else
        {
            parent::SetErrors('email', 'некоректный email !');
            return false;
        }
    }

    function validUserCaptcha($user_str)
    {
        if($user_str == '')
        {
            parent::SetErrors('captcha', 'Captcha слишком короткая');
            return false;
        }
        $user_array_letters = $this->getArrayAssoc($user_str);
        $tmpl_array_letters = $this->getArrayAssoc($this->reg_str_capt);
        $count_error = 0;
        foreach($user_array_letters as $key)
        {
            if(!array_key_exists($key, $tmpl_array_letters)) $count_error++;
        }
        return ($count_error == 0);
    }

    function ModelAddUser(& $session_status, & $result_db)
    {
        $log = htmlentities( $_REQUEST['login'], ENT_QUOTES, 'UTF-8' );
        $pas = htmlentities( $_REQUEST['password'], ENT_QUOTES, 'UTF-8' );
        $em  = htmlentities( $_REQUEST['email'], ENT_QUOTES, 'UTF-8' );
        $c   = htmlentities( $_REQUEST['capt'], ENT_QUOTES, 'UTF-8' );

        $count_errors = 0;
        if(!$this->validUserLoginRegister($log))$count_errors++;
        if(!$this->validUserPassword($pas))$count_errors++;
        if(!$this->validUserEmail($em))$count_errors++;
        if(!$this->validUserCaptcha($c))$count_errors++;

        if($count_errors == 0)
        {
            if($_SESSION['code'] == $_REQUEST['capt'])
            {
                $pas = md5( $pas );
                $ins = "insert into ".self::$service_table_name_users." (login,password,email) values ('".$log."','".$pas."','".$em."')";

                mysql_query($ins,$this->link);

                if(mysql_errno())
                {
                    $session_status = 0;
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при добавлении пользователя ! файл: ".__FILE__."  стр. ".__LINE__."</p>", self::$service_db_name );
                    exit();
                }
                else
                {
                    $_SESSION['user_session'] = 1;
                    $_SESSION['login']        = $log;
                    $_SESSION['password']     = htmlentities( $_REQUEST['password'], ENT_QUOTES, 'UTF-8' );
                    $_SESSION['hash_pass']    = $pas;
                    $_SESSION['email']        = $em;
                    $_SESSION['balance']      = 0;
                    $_SESSION['id']           = mysql_insert_id();

                    $session_status = 1;
                    $result_db = "<p class='text-info'>Регистрация успешна !</p>";
                    Log::write( "<p>Регистрация успешна !</p>", self::$service_db_name );

                    /*--------------- mail -----------------*/
                    $t = array();
                    $t[] = "Регистрация";
                    $t[] = @date("Y-m-j H:i:s")." Вы зарегестрировались на сайте : ".$_SERVER['HTTP_HOST'];
                    $t[] = "Ваш email : ".$_SESSION['email'];
                    $t[] = "Ваш login : ".$_SESSION['login'];
                    $t[] = "Ваш password : ".$_SESSION['password'];
                    $t[] = "//---------------------------------//\r\n";

                    $str = implode("\r\n",$t);

                    //$headers= "MIME-Version: 1.0\r\n";
                    //$headers .= "Content-type: text/html; charset=utf-8\r\n";
                    $headers='';

                    //if(mail($_SESSION['email'], $t[0], $str, $headers))
                    if($this->smtpmail( $_SESSION['email'], $t[0], $str, $headers, 'text/plain'))
                    {
                        file_put_contents('logs/mail.txt', $str, FILE_APPEND);
                        $result_db .= '<p class="text-info">На ваш email '.htmlentities('<'.$_SESSION['email'].'>', ENT_QUOTES, 'UTF-8').' отправлено письмо :)</p>';
                        Log::write( "<p>На ваш email ".htmlentities('<'.$_SESSION['email'].'>', ENT_QUOTES, 'UTF-8')." отправлено письмо :)</p>", self::$service_db_name );
                    }
                    else
                    {
                        file_put_contents('logs/error_mail.txt', $str, FILE_APPEND);
                        $result_db .= '<p class="text-error">Не удалось отправить письмо на Ваш email '.htmlentities('<'.$_SESSION['email'].'>', ENT_QUOTES, 'UTF-8').' :(</p>';
                        Log::write( "<p>Не удалось отправить письмо на Ваш email ".htmlentities('<'.$_SESSION['email'].'>', ENT_QUOTES, 'UTF-8')." :(</p>", self::$service_db_name );
                    }

                    $upd = "update ".self::$service_table_name_users
                        ." set ".self::$service_field_users_site."=".$_SESSION['id']
                        .", ".self::$service_field_users_turn_run."=".$_SESSION['id']
                        .", ".self::$service_field_users_turn_waiting."=".$_SESSION['id']
                        ." where id=".$_SESSION['id'];

                    if(mysql_query($upd, $this->link))
                    {
                        if(SHOW_ECHO)
                            Log::write( "<p>данные пользователя изменены в БД !</p>", self::$service_db_name );

                        //перекидываем пользователя на главную страницу
                        header( 'Location: /index.php?page=0' );
                    }
                    else
                    {
                        $session_status = 0;
                        if(SHOW_ECHO_ERROR)
                            Log::write( "<p> ошибка ".mysql_errno()." при обновлении данных пользователя ! файл: ".__FILE__."  стр. ".__LINE__."</p>", self::$service_db_name );
                        exit();
                    }
                }
            }
            else
            {
                $session_status = 0;
                $result_db = '';

                parent::SetErrors('captcha', 'неправильно ввели символы с картинки');

                parent::SetOutText('login', $_REQUEST['login']);
                parent::SetOutText('password', $_REQUEST['password']);
                parent::SetOutText('email', $_REQUEST['email']);
                //parent::SetOutText('captcha', $_REQUEST['capt']);
            }
        }
        else
        {
            $session_status = 0;
            $result_db = "";
            //$result_db = "<p class='text-error'>Вы допустили ошибки при регистрации!</p>";

            parent::SetOutText('login', $_REQUEST['login']);
            parent::SetOutText('password', $_REQUEST['password']);
            parent::SetOutText('email', $_REQUEST['email']);
            //parent::SetOutText('captcha', $_REQUEST['capt']);
        }
    }

    function ModelAutorizationUser(& $session_status, & $result_db)
    {
        $log = htmlentities($_REQUEST['login'], ENT_QUOTES, 'UTF-8');
        $pas = htmlentities($_REQUEST['password'], ENT_QUOTES, 'UTF-8');
        if(!$this->validUserLoginAutoriz($log) || !$this->validUserPassword($pas))
        {
            $session_status = 0;
            $result_db = '<p class="text-error">неверно ввели логин или пароль :(</p>';
            return;
        }

        $pas = md5($pas);

        $sel = "select login, password, email, id, balance from ".self::$service_table_name_users." where login='".$log."' and password='".$pas."'";

        $res = mysql_query($sel,$this->link);

        if(mysql_errno())
        {
            $result_db = '<p class="text-error">ошибка б.д. '.mysql_errno().' при аутентификации !</p>';
            $session_status = 0;
        }
        elseif(mysql_num_rows($res)>0)
        {
            $_SESSION['user_session'] = 1;

            $row = mysql_fetch_array($res);
            $_SESSION['login']     = $row["login"];
            $_SESSION['password']  = htmlentities($_REQUEST['password'], ENT_QUOTES, 'UTF-8');
            $_SESSION['hash_pass'] = $row["password"];
            $_SESSION['balance']   = floatval( $row["balance"] );
            $_SESSION['email']     = $row["email"];
            $_SESSION['id']        = intval( $row["id"] );

            $session_status = 1;
            //$result_db = "<p class='text-info'>Аутентификация успешна !</p>";
        }
        else
        {
            $session_status = 0;
            $result_db = '<p class="text-error">неверно ввели логин или пароль :(</p>';
        }
    }

    //проверка есть ли такой пользователь для анализа сайта вызванного через ф-ю exec()
    function ModelIsUser()
    {
        $log = htmlentities($_REQUEST['login'], ENT_QUOTES, 'UTF-8');
        $pas = htmlentities($_REQUEST['password'], ENT_QUOTES, 'UTF-8');
        if(!$this->validUserLoginAutoriz($log) || !$this->validUserPassword($pas))
        {
            return false;
        }

        $pas = md5($pas);

        $sel = "select login, password from ".self::$service_table_name_users." where login='".$log."' and password='".$pas."' and id=".$_REQUEST['id'];

        $res = mysql_query($sel,$this->link);

        if(mysql_errno())
        {
            Log::write( "<p> ошибка б.д. ".mysql_errno()." при аутентификации !  файл: ".__FILE__."  стр. ".__LINE__."</p>", self::$service_db_name );
            exit();
        }
        elseif(mysql_num_rows($res)>0)
        {
            $this->user_login = $log;
            $this->user_password = htmlentities($_REQUEST['password'], ENT_QUOTES, 'UTF-8');;
            return true;
        }
        else
        {
            Log::write( "<p> неверно ввели логин или пароль :(  файл: ".__FILE__."  стр. ".__LINE__."</p>", self::$service_db_name );
            exit();
        }
    }


    //=================================================================================================================
    //-----------------методы валидации данных перед парсингом или сравнением------------------------------------------
    //=================================================================================================================

    // в этих методах регистрируются ошибки, но они никуда ещё не выводятся
    // что бы они выводились нужно формировать их отправку в файле validation_data.php || save_tools.php
    // и принимать в файле deleteupdate.js

    function ModelValidationLimitScanPage( $str_limit )
    {
        if($str_limit == '')
        {
            parent::SetErrors('limit',"лимит страниц введён не верно.");
            return false;
        }

        $user_array_letters = $this->getArrayAssoc($str_limit);
        $limit_array_letters = $this->getArrayAssoc($this->number);
        $count_error = 0;
        foreach($user_array_letters as $key)
        {
            if(!array_key_exists($key, $limit_array_letters)) $count_error++;
        }

        if($count_error > 0)
        {
            //запрещённые символы !
            parent::SetErrors('limit',"лимит страниц введён не верно.");
            return false;
        }

        if(((int) round($_SESSION['balance'] / parent::$tariff, 0)) < intval($str_limit))
        {
            parent::SetErrors('limit',"превышение лимита (" . ($_SESSION['balance'] / parent::$tariff) . " страниц)");
            return false;
        }

        return true;
    }

    function ModelValidationTimeoutScanPage( $str_timeout )
    {
        if($str_timeout == '')
        {
            parent::SetErrors('timeout',"некоректный таймаут");
            return false;
        }

        $user_array_letters = $this->getArrayAssoc($str_timeout);
        $timeout_array_letters = $this->getArrayAssoc($this->number);
        $count_error = 0;
        foreach($user_array_letters as $key)
        {
            if(!array_key_exists($key, $timeout_array_letters)) $count_error++;
        }

        if($count_error > 0)
        {
            //запрещённые символы !
            parent::SetErrors('timeout',"некоректный таймаут");
            return false;
        }

        return true;
    }

    function ModelValidationUrlScanPage( $str_url )
    {
        if( $host = parse_url($str_url, PHP_URL_HOST))
        {
            if(isset($_SESSION['id']))
            {
                $this->user_id_session = $_SESSION['id'];
            }
            $this->ModelInit($str_url);
            $this->ModelCurlInit($ch, $this->url_site);
            $flag = false;
            if(curl_exec($ch))
            {
                $ar = curl_getinfo($ch);
                if( (isset($ar['url']) && parse_url($ar['url'], PHP_URL_HOST) == $this->host_site)
                    && (isset($ar['http_code']) && $ar['http_code'] == 200)
                    && (isset($ar['content_type']) && strstr($ar['content_type'], 'text/html')) )
                {
                    $flag = true;
                }
                else
                {
                    parent::SetErrors('url',"ошибка: " . $ar['http_code'] . " - " . $ar['content_type']);
                    $flag = false;
                }
            }
            else
            {
                if(curl_errno($ch)==self::$curl_couldnt_resolve_host)
                {
                    parent::SetErrors('url','Обычно это вызвано отсутствием подключения к Интернету или неправильной настройкой сети. Возможно, недоступен сервер DNS.');
                }
                else
                {
                    parent::SetErrors('url',"ошибка: " . curl_errno($ch) . " - " . curl_error($ch));
                }
                $flag = false;
            }
            curl_close($ch);
            unset($ch);
            unset($ar);
            return $flag;
        }
        else
        {
            parent::SetErrors('url',"некоректный url");
            return false;
        }
    }

    function ModelValidationData()
    {
        $errors = 0;
        //type_analysis
        if(isset($_REQUEST['analysis_type']) && $_REQUEST['analysis_type'] == 1)
        {
            //limit
            if(isset($_REQUEST['limit_pages']) && $_REQUEST['limit_pages'] != '')
            {
                $limit_pages = htmlentities( $_REQUEST['limit_pages'], ENT_QUOTES, 'UTF-8' );
                //метод для валидации для limit
                if($this->ModelValidationLimitScanPage($limit_pages))
                {//если всё норм
                    $this->limit_pages = intval($limit_pages);
                }
                else
                {
                    // если ошибки передадим их в view !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                    $errors++;
                }
            }
            //timeout
            if(isset($_REQUEST['analysis_timeout']) && $_REQUEST['analysis_timeout'] != '')
            {
                if($_REQUEST['analysis_timeout'] == 2)
                {
                    $this->timeout_scan = FORTNIGHT; // 14 дней == 2 недели
                }
                elseif($_REQUEST['analysis_timeout'] == 3 && isset($_REQUEST['timeout']) && $_REQUEST['timeout'] != '')
                {
                    $timeout = htmlentities( $_REQUEST['timeout'], ENT_QUOTES, 'UTF-8' );
                    //нужно создать метод для валидации timeout
                    if($this->ModelValidationTimeoutScanPage($timeout))
                    {
                        $this->timeout_scan = intval($timeout);// (0 == бесконечность), любое другое число == интервалу дней между сканированиями
                    }
                    else
                    {
                        // если ошибки передадим их в view !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                        $errors++;
                    }
                }
                else
                {
                    $this->timeout_scan = null; //дополнительного сканирования нет !!!
                }
            }
        }

        //robots
        /*if(isset($_REQUEST['robots']) && $_REQUEST['robots'] == 'robots')
        {
            $this->robots = true;
        }*/
        if(isset($_REQUEST['robots']) && $_REQUEST['robots'] != '')
        {
            $this->robots = intval($_REQUEST['robots']);
        }

        //url
        if(isset($_REQUEST['url']) && $_REQUEST['url'] != '')
        {
            //метод для валидации url
            if( ! $this->ModelValidationUrlScanPage( $_REQUEST['url'] ) )
            {
                $errors++;
            }
        }
        else
        {
            $errors++;
        }

        //email
        if(isset($_REQUEST['email']) && $_REQUEST['email'] != '')
        {
            //метод для валидации email
            if( ! $this->validUserEmail( $_REQUEST['email'] ) )
            {
                $errors++;
            }
        }
        else
        {
            $errors++;
        }

        //если всё нормально - без ошибок
        return ($errors ? false : true);
    }


    //=================================================================================================================
    //-----------------методы повторного вызова анализа сайта----------------------------------------------------------
    //=================================================================================================================

    //заносим настройки анализа сайта в таблицу service.service_site
    //используется один раз при первом сканировании сайта в ModelAddPages()
    function ModelUserInsertProjectToAnalyze()
    {
        /*$data = array(
            'id'               => $_REQUEST['id'],
            'login'            => $_REQUEST['login'],
            'password'         => $_REQUEST['password'],
            'email'            => $_REQUEST['email'],
            'url'              => $_REQUEST['url'],
            'robots'           => $_REQUEST['robots'],
            'analysis_type'    => $_REQUEST['analysis_type'],
            'limit_pages'       => $_REQUEST['limit_pages'],
            'analysis_timeout' => $_REQUEST['analysis_timeout'],
            'timeout'          => $_REQUEST['timeout']
        );*/

        if($this->user_id_session)
        {
            $this->site_email_report = $_REQUEST['email'];
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

            $ins = "insert into ".self::$service_table_name_site
                ."(user_id, site_db_name, site_name, site_email_report, site_report, robots, analysis_type, limit_pages, analysis_timeout, timeout) value ("
                .$this->user_id_session.", '"
                .$this->db_name."', '"
                .$this->host_site."', '"
                .$this->site_email_report."', "
                .$this->user_id_session.", "
                .$this->robots.", "
                .$this->analysis_type.", "
                .$this->limit_pages.", "
                .$this->analysis_timeout.", "
                .($this->timeout_scan === null ? 'NULL' : $this->timeout_scan).")";

            if(!mysql_select_db(self::$service_db_name, $this->link))
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> Ошибка подключения к БД ".mysql_errno()."</p>", self::$service_db_name );
                exit();
            }
            mysql_query("SET NAMES utf8"); // теперь всё будет сохранятся в MySql правильной кодировке
            if(mysql_query( $ins, $this->link ))
            {
                if(SHOW_ECHO)
                    Log::write( "<p> Ваши данные изменены</p>", self::$service_db_name );
            }
            else
            {
                if(SHOW_ECHO_ERROR)
                {
                    Log::write( "<p> ошибка ".mysql_errno()." ".mysql_error()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", self::$service_db_name );
                    Log::write( $ins, self::$service_db_name );
                }
                exit();
            }
        }
    }

    //проверяем настройки анализа сайта перед обновлением в таблице service.service_site
    function ModelValidationDataForUpdateTools()
    {
        $errors = 0;
        //type_analysis
        if(isset($_REQUEST['analysis_type']) && $_REQUEST['analysis_type'] == 1)
        {
            //limit
            if(isset($_REQUEST['limit_pages']) && $_REQUEST['limit_pages'] != '')
            {
                $limit_pages = htmlentities( $_REQUEST['limit_pages'], ENT_QUOTES, 'UTF-8' );
                //метод для валидации для limit
                if($this->ModelValidationLimitScanPage($limit_pages))
                {//если всё норм
                    $this->limit_pages = intval($limit_pages);
                }
                else
                {
                    // если ошибки передадим их в view !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                    $errors++;
                }
            }
            //timeout
            if(isset($_REQUEST['analysis_timeout']) && $_REQUEST['analysis_timeout'] != '')
            {
                if($_REQUEST['analysis_timeout'] == 2)
                {
                    $this->timeout_scan = FORTNIGHT; // 14 дней == 2 недели
                }
                elseif($_REQUEST['analysis_timeout'] == 3 && isset($_REQUEST['timeout']) && $_REQUEST['timeout'] != '')
                {
                    $timeout = htmlentities( $_REQUEST['timeout'], ENT_QUOTES, 'UTF-8' );
                    //нужно создать метод для валидации timeout
                    if($this->ModelValidationTimeoutScanPage($timeout))
                    {
                        $this->timeout_scan = intval($timeout);// (0 == бесконечность), любое другое число == интервалу дней между сканированиями
                    }
                    else
                    {
                        // если ошибки передадим их в view !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                        $errors++;
                    }
                }
                else
                {
                    $this->timeout_scan = null; //дополнительного сканирования нет !!!
                }
            }
        }

        //robots
        if(isset($_REQUEST['robots']) && $_REQUEST['robots'] != '')
        {
            $this->robots = intval($_REQUEST['robots']);
        }

        //url
        if(!isset($_REQUEST['url']) || $_REQUEST['url'] == '')
        {
            $errors++;
        }

        //email
        if(isset($_REQUEST['email']) && $_REQUEST['email'] != '')
        {
            //метод для валидации email
            if( ! $this->validUserEmail( $_REQUEST['email'] ) )
            {
                $errors++;
            }
        }
        else
        {
            $errors++;
        }

        //если всё нормально - без ошибок
        return ($errors ? false : true);
    }

    //обновляем настройки анализа сайта в таблице service.service_site
    function ModelUserUpdateProjectToolsToAnalyze()
    {
        $this->user_id_session = intval($_SESSION['id']);
        $this->ModelInit($_REQUEST['url']);
        if($this->ModelIsDataBase())
        {
            if($this->user_id_session)
            {
                $this->site_email_report = $_REQUEST['email'];
                $this->robots           = intval($_REQUEST['robots']);
                $this->analysis_type    = intval($_REQUEST['analysis_type']);

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

                $upd = "update ".self::$service_table_name_site
                    ." set ".self::$service_field_site_email_report."='".$this->site_email_report
                    ."', robots=".$this->robots
                    .", analysis_type=".$this->analysis_type
                    .", limit_pages=".$this->limit_pages
                    .", analysis_timeout=".$this->analysis_timeout
                    .", timeout=".($this->timeout_scan === null ? 'NULL' : $this->timeout_scan)
                    ." where ".self::$service_field_site_user_id."=".$this->user_id_session
                    ." and ".self::$service_field_site_db_name." like '".$this->db_name."'";

                if(!mysql_select_db(self::$service_db_name, $this->link))
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> Ошибка подключения к БД ".mysql_errno()."</p>", self::$service_db_name );
                    exit();
                }
                mysql_query("SET NAMES utf8"); // теперь всё будет сохранятся в MySql правильной кодировке
                if(mysql_query( $upd, $this->link ))
                {
                    if(SHOW_ECHO)
                        Log::write( "<p> Ваши данные изменены</p>", self::$service_db_name );
                }
                else
                {
                    if(SHOW_ECHO_ERROR)
                    {
                        Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", self::$service_db_name );
                        Log::write( $upd, self::$service_db_name );
                    }
                    exit();
                }
            }
            return true;
        }
        else
            return false;
    }

    //получаем настройки анализа сайта из таблицы service.service_site и заносим их в текущюю db_name.tools
    //используется один раз при первом сканировании сайта в ModelAddPages()
    function ModelInsertProjectTools()
    {
        $ins = "insert into ".$this->tb_tools
            ."(".self::$service_field_site_email_report.", robots, analysis_type, limit_pages, analysis_timeout, timeout) value ("
            ."'".$this->site_email_report."', "
            .$this->robots.", "
            .$this->analysis_type.", "
            .$this->limit_pages.", "
            .$this->analysis_timeout.", "
            .($this->timeout_scan === null ? 'NULL' : $this->timeout_scan).")";

        if(mysql_query( $ins, $this->link ))
        {
            if(SHOW_ECHO)
                Log::write( "<p> Ваши данные изменены</p>", $this->host_site );
        }
        else
        {
            if(SHOW_ECHO_ERROR)
            {
                Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            }
            exit();
        }
    }

    //обновляем настройки анализа сайта из таблицы service.service_site и заносим их в текущюю db_name.tools
    //используется повторно в ModelScan_Pages() и ModelRestart_Scan_Pages()
    function ModelUpdateProjectTools()
    {
        $sel = "select * from ".self::$service_db_name.".".self::$service_table_name_site
            ." where ".self::$service_field_site_user_id."=".$this->user_id_session
            ." and ".self::$service_field_site_db_name." like '".$this->db_name."'";

        $res = mysql_query($sel, $this->link);

        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        else
        {
            $row = mysql_fetch_array($res, MYSQL_ASSOC);
            $this->site_email_report= $row[self::$service_field_site_email_report];
            $this->robots           = intval($row['robots']);
            $this->analysis_type    = intval($row['analysis_type']);
            $this->limit_pages      = intval($row['limit_pages']);
            $this->analysis_timeout = intval($row['analysis_timeout']);
            if($row['timeout'] !== null)
            {
                $this->timeout_scan = intval($row['timeout']);
            }
            else
            {
                $this->timeout_scan = $row['timeout'];
            }
        }

        $upd = "update ".$this->tb_tools
            ." set ".self::$service_field_site_email_report."='".$this->site_email_report
            ."', robots=".$this->robots
            .", analysis_type=".$this->analysis_type
            .", limit_pages=".$this->limit_pages
            .", analysis_timeout=".$this->analysis_timeout
            .", timeout=".($this->timeout_scan === null ? 'NULL' : $this->timeout_scan);

        if(mysql_query( $upd, $this->link ))
        {
            if(SHOW_ECHO)
                Log::write( "<p> Ваши данные изменены</p>", $this->host_site );
        }
        else
        {
            if(SHOW_ECHO_ERROR)
            {
                Log::write( "<p> ошибка ".mysql_errno()." при добавлении данных в БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            }
            exit();
        }
    }

    //получаем настройки анализа сайта из MySql и заносим их в обьект
    function ModelInitProjectTools()
    {
        $sel = "select * from ".self::$service_db_name.".".self::$service_table_name_site
            ." where ".self::$service_field_site_user_id."=".$this->user_id_session
            ." and ".self::$service_field_site_db_name." like '".$this->db_name."'";

        $res = mysql_query($sel, $this->link);

        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        else
        {
            $row = mysql_fetch_array($res, MYSQL_ASSOC);
            $this->robots           = intval($row['robots']);
            $this->analysis_type    = intval($row['analysis_type']);
            $this->limit_pages      = intval($row['limit_pages']);
            $this->analysis_timeout = intval($row['analysis_timeout']);
            if($row['timeout'] !== null){
                $this->timeout_scan = intval($row['timeout']);
            }else{
                $this->timeout_scan = $row['timeout'];
            }
        }
    }

    //метод определения действия анализатора сайта :
    //1-(создаём, анализируем, парсим !!!) 2-(делаем сравнение !!!) 3-(сайт анализируется !!!)
    function ModelParseOrCompare()
    {
        $this->user_id_session = intval($_REQUEST['id']);
        //$this->process_id      = $process;
        $this->ModelInit($_REQUEST['url']);

        if($this->ModelIsDataBase() && $this->ModelIsAnalizationSite())
        {
            //БД и таблицы существуют, сайт проанализировн
            // делаем сравнение !!!
            Log::write("***************************START********делаем сравнение !!!*******************", $this->host_site);
            $this->ModelRestart_Scan_Pages();
        }
        else if($this->ModelIsDataBase() && !$this->ModelIsAnalizationSite())
        {
            //БД и таблицы существуют, сайт анализируется
            //выдаем сообщение !!!
            Log::write("***************************START*********сайт анализируется !!!******************", $this->host_site);
            Log::write("сайт анализируется !!!", $this->host_site);
            Log::write("***************************STOP**********сайт анализируется !!!*****************", $this->host_site);
            return;
        }
        else
        {
            //БД и таблицы НЕ существуют, сайт ещё не анализировался ни разу
            //создаём, анализируем, парсим !!!
            Log::write("***************************START*********создаём, анализируем, парсим !!!******************", $this->host_site);
            //$this->ModelUserInsertProjectToAnalyze();
            $this->ModelAddPages();
        }
    }

    //метод определяет - нужно ли вызывать повторный анализ сайта или нет
    //смотрит в настройки анализа сайта в таблице service.service_site
    function ModelNeedRepetition()
    {
        $sel = "select * from ".self::$service_db_name.".".self::$service_table_name_site
            ." where ".self::$service_field_site_user_id."=".$this->user_id_session
            ." and ".self::$service_field_site_db_name." like '".$this->db_name."'";

        $res = mysql_query($sel, $this->link);

        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        else
        {
            $row = mysql_fetch_array($res, MYSQL_ASSOC);
            $this->site_email_report= $row[self::$service_field_site_email_report];
            $this->robots           = intval($row['robots']);
            $this->analysis_type    = intval($row['analysis_type']);
            $this->limit_pages      = intval($row['limit_pages']);
            $this->analysis_timeout = intval($row['analysis_timeout']);
            if($row['timeout'] !== null){
                $this->timeout_scan = intval($row['timeout']);
            }else{
                $this->timeout_scan = $row['timeout'];
            }

            if($this->analysis_type == 1)
            {//платный анализ

                if($this->timeout_scan === null)// нет повторного сканирования
                {
                    return false;
                }
                elseif($this->timeout_scan === 0)// есть повторное сканирование но без timeout'a
                {
                    return true;
                }
                else // есть повторное сканирование с timeout'ом
                {
                    $t = time();
                    $count_seconds = 0;

                    while(true)
                    {
                        sleep(1);
                        $count_seconds++;

                        //делаем проверку и обращение к БД через интервал времени REPEAT_ANALYSIS_TIMEOUT_DB
                        if($count_seconds == REPEAT_ANALYSIS_TIMEOUT_DB)
                        {
                            $count_seconds = 0;

                            $sel = "select * from ".self::$service_db_name.".".self::$service_table_name_site
                                ." where ".self::$service_field_site_user_id."=".$this->user_id_session
                                ." and ".self::$service_field_site_db_name." like '".$this->db_name."'";

                            $res = mysql_query($sel, $this->link);

                            if(mysql_errno())
                            {
                                if(SHOW_ECHO_ERROR)
                                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                exit();
                            }
                            else
                            {
                                $row = mysql_fetch_array($res, MYSQL_ASSOC);

                                if(intval($row['analysis_type']) != 1)
                                {
                                    return false;
                                }
                                else
                                {
                                    if($row['timeout'] === null)
                                    {
                                        return false;
                                    }
                                    else
                                    {
                                        $this->timeout_scan = intval($row['timeout']);
                                        //$t2 = $t + $this->timeout_scan;//работаем с сек. для теста !!!
                                        $t2 = $t + ($this->timeout_scan * 24 * 60 * 60);//работаем с днями для программы !!!

                                        if(time() >= $t2)
                                        {
                                            return true;
                                        }
                                        else
                                        {
                                            continue;
                                        }
                                    }
                                }
                            }
                        }

                        //-------------------------------------------

                        //$t3 = $t + $this->timeout_scan;//работаем с сек. для теста !!!
                        $t3 = $t + ($this->timeout_scan * 24 * 60 * 60);//работаем с днями для программы !!!
                        if(time() >= $t3)
                        {
                            return true;
                        }

                    }//end while
                }
            }
            else
            {//бесплатный анализ
                return false;
            }
        }
    }

    //метод поврорного анализа сайта
    function ModelRepeatAnalysis()
    {
        //ModelNeedRepetition() - определяет нужно ли делать повторный анализ
        //ModelAutomaticUpdateBalance() - снимает деньги с баланса
        while($this->ModelNeedRepetition() && $this->ModelAutomaticUpdateBalance())
        {
            Log::write("***************************START********делаем сравнение !!!*******************", $this->host_site);
            $this->ModelScan_Pages();//нужно не превышать лимит страниц !!!
            //$this->ModelAutomaticGetMoneyInBalance();//возвращает лишние деньги на баланс
            //$this->ModelSendReportToEmail();//отправляем отчёт по почте клиенту
            Log::write("***************************STOP*********делаем сравнение !!!******************", $this->host_site);
        }
    }

    //метод который возвращает лишние деньги на баланс
    function ModelAutomaticGetMoneyInBalance()
    {
        if($this->analysis_type == 1)
        {
            if($this->limiter_pages < $this->limit_pages)
            {
                $balance = $this->ModelAutomaticGetBalance( $this->user_id_session );
                $rest = round( parent::$tariff * $this->limit_pages, 2 ) - round( parent::$tariff * $this->limiter_pages, 2 );
                $new_balance = $balance + $rest;
                $upd = "update ".self::$service_db_name.".".self::$service_table_name_users." set balance=".$new_balance." where id=".$this->user_id_session;

                if(!mysql_query($upd, $this->link))
                {
                    if(SHOW_ECHO_ERROR)
                    {
                        Log::write( "<p> ошибка ".mysql_errno()." при обновлении balance в таблице ".self::$service_db_name.".".self::$service_table_name_users." файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    }
                    exit();
                }
            }
        }
    }

    //снятие денег с баланса (в автоматическом режиме!)
    function ModelAutomaticUpdateBalance()
    {
        $this->ModelInitProjectTools();
        $balance = $this->ModelAutomaticGetBalance( $this->user_id_session );
        if($this->ModelAutomaticReCalculationBalance( $balance, $this->user_id_session, $this->limit_pages))
            return true;
        else
            return false;
    }

    //получаем баланс пользователя из MySql (в автоматическом режиме!)
    function ModelAutomaticGetBalance( $user_id )
    {
        $sel = "select balance from ".self::$service_db_name.".".self::$service_table_name_users." where id=".$user_id;

        $res = mysql_query($sel, $this->link);

        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", self::$service_db_name );
            exit();
        }
        else
        {
            $row = mysql_fetch_array($res, MYSQL_ASSOC);
            return floatval($row['balance']);
        }
    }

    //перерасчёт баланса при повторном анализе (в автоматическом режиме!)
    function ModelAutomaticReCalculationBalance( $balance, $user_id, $limit_pages )
    {
        if( round($balance, 2) >= round( parent::$tariff * $limit_pages, 2 ) )
        {
            $balance = round($balance, 2) - round( parent::$tariff * $limit_pages, 2 );
            $upd = "update ".self::$service_db_name.".".self::$service_table_name_users." set balance=".$balance." where id=".$user_id;
            if(!mysql_query( $upd, $this->link ))
            {
                if(SHOW_ECHO_ERROR)
                {
                    Log::write( "<p> ошибка ".mysql_errno()." при обновлении balance в ".self::$service_db_name.".".self::$service_table_name_users." , файл: ".__FILE__."  стр. ".__LINE__."</p>", self::$service_db_name );
                }
                exit();
            }
            else
            {
                return true;
            }
        }
        else
        {
            return false;
        }
    }

    //при каждом обновлении или переходе на страницы
    //извлекает баланс пользователя из БД и заносит в $_SESSION['balance']
    function ModelGetBalanceInSession()
    {
        $sel = "select balance from ".self::$service_db_name.".".self::$service_table_name_users." where id=".$_SESSION['id'];

        $res = mysql_query($sel, $this->link);

        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", self::$service_db_name );
            exit();
        }
        else
        {
            $row = mysql_fetch_array($res, MYSQL_ASSOC);
            $_SESSION['balance'] = floatval($row['balance']);//$row['balance'];//round(floatval($row['balance']),2);//floatval($row['balance']);//+ $row['balance'];//floatval($row['balance']);
        }
    }

    //перерасчёт баланса
    function ModelReCalculationBalance()
    {
        $this->ModelGetBalanceInSession();

        if( isset($_REQUEST['limit_pages']) && round($_SESSION['balance'], 2) >= round( parent::$tariff * intval($_REQUEST['limit_pages']), 2 ) )
        {
            $_SESSION['balance'] = round($_SESSION['balance'], 2) - round( parent::$tariff * intval($_REQUEST['limit_pages']), 2 );
            $upd = "update ".self::$service_db_name.".".self::$service_table_name_users." set balance=".$_SESSION['balance']." where id=".$_SESSION['id'];
            if(!mysql_query( $upd, $this->link ))
            {
                if(SHOW_ECHO_ERROR)
                {
                    Log::write( "<p> ошибка ".mysql_errno()." при обновлении balance в ".self::$service_db_name.".".self::$service_table_name_users." , файл: ".__FILE__."  стр. ".__LINE__."</p>", self::$service_db_name );
                }
                exit();
            }
            else
            {
                return true;
            }
        }
        else
        {
            return false;
        }
    }

    //перерасчёт баланса при повторном анализе
    function ModelRepeatReCalculationBalance()
    {
        $this->ModelGetBalanceInSession();

        if( round($_SESSION['balance'], 2) >= round( parent::$tariff * $this->limit_pages, 2 ) )
        {
            $_SESSION['balance'] = round($_SESSION['balance'], 2) - round( parent::$tariff * $this->limit_pages, 2 );
            $upd = "update ".self::$service_db_name.".".self::$service_table_name_users." set balance=".$_SESSION['balance']." where id=".$_SESSION['id'];
            if(!mysql_query( $upd, $this->link ))
            {
                if(SHOW_ECHO_ERROR)
                {
                    Log::write( "<p> ошибка ".mysql_errno()." при обновлении balance в ".self::$service_db_name.".".self::$service_table_name_users." , файл: ".__FILE__."  стр. ".__LINE__."</p>", self::$service_db_name );
                }
                exit();
            }
            else
            {
                return true;
            }
        }
        else
        {
            return false;
        }
    }

    //=================================================================================================================
    //-----------------методы загрузки файла на сервер для пакетной загрузки проектов----------------------------------
    //=================================================================================================================

    function ModelUploadFile()
    {
        $file_path  = $_SERVER['DOCUMENT_ROOT'] .'/files/';
        $dir = opendir($file_path);
        $new_file = '';
        $ex = 'csv';
        $count = 0;
        while($file = readdir($dir))
        {
            if($file == '.' || $file == '..' || is_dir($file_path . $file))
            {
                continue;
            }
            $count++;
        }
        $count++;
        foreach($_FILES as $file)
        {
            if($file['error'] == 0)
            {
                if(is_uploaded_file($file['tmp_name']))
                {
                    $ar = pathinfo($file['name']);
                    $old_file = $file['tmp_name'];
                    if($ar['extension'] == $ex)
                    {
                        $new_file = $file_path.$count.'.'.$ar['extension'];
                        //move_uploaded_file($old_file, $new_file);
                        rename($old_file, $new_file);
                    }
                    else
                    {
                        return;
                    }
                }
            }
        }

        if(file_exists($new_file))
        {
            $email    = $_SESSION['email'];
            $id       = $_SESSION['id'];
            $login    = $_SESSION['login'];
            $password = $_SESSION['password'];

            //<< подготовка параметров для передачи скрипту------------------
            $url_root = 'http://' . $_SERVER['SERVER_NAME'] . '/model/send_auto.php';//URL по которому должен перейти cUrl
            $file_path_analyze = 'analyze.php';

            $data = array(
                'send'             => 'auto',
                'file_path_analyze'=> $file_path_analyze,
                'id'               => $id,
                'login'            => $login,
                'password'         => $password,
                'file'             => $new_file,//файл с которым нужно работать
                'robots'           => "0",
                'email'            => $email,
                'analysis_type'    => "0",
                'limit_pages'      => "0",
                'analysis_timeout' => "1",//нет
                'timeout'          => ''
            );

            //<< вызов и передача параметров скрипту, который делает анализ сайта-------------------
            $ch = curl_init( $url_root );
            curl_setopt ($ch, CURLOPT_USERAGENT, $this->user_agent_chrome);
            curl_setopt($ch, CURLOPT_HTTPHEADER,$this->headers_chrome);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);//Количество секунд ожидания при попытке соединения. Используйте 0 для бесконечного ожидания.
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);              //Максимально позволенное количество секунд для выполнения cURL-функций.
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_exec($ch);
            //>>end-----------------------
        }
    }

    //=================================================================================================================
    //-----------------методы извлечения данных из БД по проектам пользователя-----------------------------------------
    //=================================================================================================================

    //метод извлекает все проекты пользователя
    function Model_Project_GetUserProjects($num_menu)
    {
        if (isset($_GET['navigate-page']))
        {
            $page=($_GET['navigate-page']-1);
        }
        else
        {
            $page=0;
        }

        $start=abs($page * $this->per_page);

        $sel="select * from ".self::$service_table_name_site." where "
            .self::$service_field_site_user_id."=".$_SESSION['id']
            ." order by id desc limit ".$start.", ".$this->per_page;

        if(!mysql_select_db(self::$service_db_name, $this->link))
        {
            Log::write( "<p class='text-error'>Ошибка подключения к БД ".mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", self::$service_db_name );
            exit();
        }
        mysql_query("SET NAMES utf8"); // теперь всё будет сохранятся в MySql правильной кодировке
        $res = mysql_query( $sel, $this->link );

        if(mysql_errno())
        {
            Log::write( "<p class='text-error'>Ошибка БД ".mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", self::$service_db_name );
            exit();
        }
        elseif(mysql_num_rows($res)>0)
        {
            while ($row = mysql_fetch_array($res, MYSQL_BOTH))
            {
                $sp = new ServiceProject();

                $sp->site_name = $row[self::$service_field_site_name];
                $sp->site_db_name = $row[self::$service_field_site_db_name];

                //даём знать что сайт проанализирован !!!
                //открываем новое соединение - ВНИМАНИЕ 4-параметр для нового соединения == true!!!
                if($tmp_link = mysql_connect($this->host, $this->root_login, $this->root_password, true))
                {
                    if(mysql_select_db($sp->site_db_name, $tmp_link))
                    {
                        $sp->exist_db = 1;

                        mysql_query("SET NAMES utf8", $tmp_link); // теперь всё будет сохранятся в MySql правильной кодировке

                        $tmp_sel = "select flag from ".$this->tb_toggle." where id=1";
                        $tmp_res = mysql_query($tmp_sel, $tmp_link);

                        if(mysql_errno())
                        {
                            if(SHOW_ECHO_ERROR)
                                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                            exit();
                        }
                        else
                        {
                            $tmp_row = mysql_fetch_array($tmp_res, MYSQL_ASSOC);
                            $sp->analyze = intval($tmp_row['flag']);
                        }

                        //даём знать что есть хотя бы один отчёт об анализе сайта !!!
                        //открываем новое соединение - ВНИМАНИЕ 4-параметр для нового соединения == true!!!
                        if($tmp_link2 = mysql_connect($this->host, $this->root_login, $this->root_password, true))
                        {
                            if(!mysql_select_db(self::$service_db_name, $tmp_link2))
                            {
                                if(SHOW_ECHO_ERROR)
                                    Log::write( "<p> Ошибка подключения к БД ".mysql_errno()." файл ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                exit();
                            }
                            mysql_query("SET NAMES utf8", $tmp_link2); // теперь всё будет сохранятся в MySql правильной кодировке

                            $tmp_sel2 = "select count(*) from ".self::$service_table_name_report
                                ." where ".self::$service_field_report_user_id."=".$_SESSION['id']
                                ." and ".self::$service_field_report_name." like '".$sp->site_db_name."%'";

                            $tmp_res2 = mysql_query($tmp_sel2, $tmp_link2);

                            if(mysql_errno())
                            {
                                if(SHOW_ECHO_ERROR)
                                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", self::$service_db_name );
                                exit();
                            }
                            elseif(mysql_num_rows($tmp_res2)>0)
                            {
                                $tmp_row2 = mysql_fetch_row($tmp_res2);
                                $sp->report = intval($tmp_row2[0]);
                            }
                        }
                    }
                }

                self::$ar_projects[] = $sp;
            }
        }
        else
        {
            //$result_db = '<p class="text-error">Нет сообщений :(</p>';
            return;
        }

        $sel = "select count(*) from ".self::$service_table_name_site;
        $res = mysql_query( $sel, $this->link );

        if(mysql_errno())
        {
            Log::write( "<p class='text-error'>Ошибка БД ".mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", self::$service_db_name );
            return;
        }
        elseif(mysql_num_rows($res)>0)
        {
            $row = mysql_fetch_row($res);
            $total_rows = $row[0];
            $num_pages = ceil($total_rows / $this->per_page);

            if($num_pages < $this->count_navigate_menu )
            {
                for($i=1;$i<=$num_pages;$i++)
                {
                    if ($i-1 == $page)
                    {
                        self::$ar_a[] = '<span class="activ"><strong>'.$i.'</strong></span>';
                    }
                    else
                    {
                        self::$ar_a[] = '<a href="'.$_SERVER['PHP_SELF'].'?page='.$num_menu.'&navigate-page='.$i.'">'.$i.'</a>';
                    }
                }
            }
            else
            {
                $count5 = false;
                if($num_pages >= 3 && $num_pages <= 5 ) $this->count_navigate_menu = 3;
                if($num_pages > 5 )
                {
                    $this->count_navigate_menu = 5;
                    $count5 = true;
                }
                //*----------------------------------------------------------------


                //    <<   [1]   2  [[3]]  4  [5]   >>   ....   8

                //$left_page           = 0;//                    <<
                //$start_menu          = 0;//первая ссылка       [1]
                //$cur_menu            = 0;//текущий елемент    [[3]]
                //$end_menu            = 0;//последняя ссылка    [5]
                //$right_page          = 0;//                    >>
                //$num_pages             //всего на страниц     8
                //$total_rows            //всего записей в БД   24
                //$this->per_page        //записей на странице  3
                //$this->count_navigate_menu = 5;//сколько ссылок в меню отображается в один ряд (3,5,7,9,11 - только нечетные числа, для красоты)

                $start_menu = 0;
                $end_menu   = $this->count_navigate_menu;
                $right_page = 0;

                if(($page+1) > $this->count_navigate_menu)
                {
                    $start_menu = abs($this->count_navigate_menu-($page+1));
                    $end_menu = $this->count_navigate_menu + abs($this->count_navigate_menu-($page+1));
                }

                /*if($page >= floor($this->count_navigate_menu / 2))
                {
                    $start_menu = $page-floor($this->count_navigate_menu / 2);
                    //$end_menu = $start_menu
                }*/

                //|<<
                if($count5 && $page > 1)self::$ar_a[] = '<a href="'.$_SERVER['PHP_SELF'].'?page='.$num_menu.'&navigate-page=1">|&laquo;</a>';
                //<<
                if($page > 0) self::$ar_a[] = '<a href="'.$_SERVER['PHP_SELF'].'?page='.$num_menu.'&navigate-page='.$page.'">&laquo;</a>';


                for( $i = $start_menu; $i < $end_menu; $i++ )
                {
                    if ($i == $page)
                    {
                        self::$ar_a[] = '<span class="activ"><strong>'.($i+1).'</strong></span>';
                        $right_page=$i+1;
                        $right_page = ($right_page == $num_pages) ? $num_pages : (++$right_page) ;
                    }
                    else
                    {
                        self::$ar_a[] = '<a href="'.$_SERVER['PHP_SELF'].'?page='.$num_menu.'&navigate-page='.($i+1).'">'.($i+1).'</a>';
                    }
                }


                //>>
                if($page < $num_pages-1)
                    self::$ar_a[] = '<a href="'.$_SERVER['PHP_SELF'].'?page='.$num_menu.'&navigate-page='.$right_page.'">&raquo;</a>';

                if($end_menu < $num_pages)
                {
                    //>>|
                    if($count5 && $page < $num_pages-2) self::$ar_a[] = '<a href="'.$_SERVER['PHP_SELF'].'?page='.$num_menu.'&navigate-page='.$num_pages.'">&raquo;|</a>';
                    //....
                    //self::$ar_a[] = '<span class=""><small>всего</small></span>';
                    //8
                    //self::$ar_a[] = '<a href="'.$_SERVER['PHP_SELF'].'?page='.$num_menu.'&navigate-page='.$num_pages.'">['.$num_pages.']</a>';
                }
            }
        }
    }

    //метод извлекает настройки к проекту указанному в $_GET['site_db_name']
    function Model_Project_GetProjectTools()
    {
        if(isset($_GET['site_db_name']))
        {
            $sel="select * from ".self::$service_table_name_site." where "
                .self::$service_field_site_user_id."=".$_SESSION['id']
                ." and ".self::$service_field_site_db_name." like '".$_GET['site_db_name']."'";

            if(!mysql_select_db(self::$service_db_name, $this->link))
            {
                Log::write( "<p class='text-error'>Ошибка подключения к БД ".mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", self::$service_db_name );
                exit();
            }
            mysql_query("SET NAMES utf8"); // теперь всё будет сохранятся в MySql правильной кодировке
            $res = mysql_query( $sel, $this->link );

            if(mysql_errno())
            {
                Log::write( "<p class='text-error'>Ошибка БД ".mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", self::$service_db_name );
                exit();
            }
            elseif(mysql_num_rows($res)>0)
            {
                while ($row = mysql_fetch_array($res, MYSQL_BOTH))
                {
                    $tools = new ServiceProject();
                    $tools->user_id        = intval($row['user_id']);
                    $tools->site_db_name   = $row['site_db_name'];
                    $tools->site_name      = $row['site_name'];
                    $tools->site_email_report= $row[self::$service_field_site_email_report];
                    $tools->robots         = intval($row['robots']);
                    $tools->analysis_type  = intval($row['analysis_type']);
                    $tools->limit_pages    = intval($row['limit_pages']);
                    $tools->analysis_timeout = intval($row['analysis_timeout']);
                    $tools->timeout        = $row['timeout'];
                    self::$project_tools[] = $tools;
                }
            }
        }
    }

    //метод начинает создавать отчет по текущему анализу сайта,
    //1-(создает пустую БД отчета) 2-(в service.service_report создает время начала создания отчёта)
    function ModelStartCreateReport( $counter_scan )
    {
        $tmp_db_name = $this->db_name. '_tmp_' . $counter_scan;//получаем название БД для отчёта

        $sql = 'CREATE DATABASE '.$tmp_db_name.' CHARACTER SET utf8 COLLATE utf8_general_ci';

        mysql_query($sql, $this->link);
        if(mysql_errno() == "1007")
        {
            if(SHOW_ECHO_ERROR_CREATE_DB)
                Log::write( "<p> База ".$tmp_db_name." уже существует !</p>", $this->host_site );
        }
        else if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR_CREATE_DB)
                Log::write( "<p> Ошибка при создании базы данных: " . mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        else
        {
            if(SHOW_ECHO_CREATE_DB)
                Log::write( "<p> База ".$tmp_db_name." успешно создана </p>", $this->host_site );

            //<<-------------заносим дату начала копирования базы (отчета)---------
            $ins = "insert into ".self::$service_db_name.".".self::$service_table_name_report
                ."(".self::$service_field_report_user_id.", ".self::$service_field_report_name.", ".self::$service_field_report_date_start_report.") value ("
                .$this->user_id_session.", '".$tmp_db_name."', '".date("Y-m-d H:i:s",mktime())."')";

            mysql_query($ins, $this->link);
            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR_CREATE_DB)
                    Log::write( "<p> Ошибка при добавлении данных: " . mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            else
            {
                if(SHOW_ECHO_CREATE_DB)
                    Log::write( "<p>данные успешно сохранены!</p>", $this->host_site );

                $this->tmp_id = mysql_insert_id();
            }
            //>>end------------------------------------------------------------------
        }
    }

    //метод заканчивает создавать отчет по текущему анализу сайта,
    //1-(переписывает все данные из текущей БД в пустую БД отчета) 2-(в service.service_report создает время окончания создания отчёта)
    function ModelStopCreateReport( $counter_scan )
    {
        $tmp_db_name = $this->db_name. '_tmp_' . $counter_scan;//получаем название БД для отчёта
        $this->tmp_data_base_report = $tmp_db_name;

        $tables = array();

        $sql = "SHOW TABLES FROM ".$this->db_name;
        $result = mysql_query($sql, $this->link);

        if (mysql_error())
        {
            Log::write( 'Ошибка MySQL: ' . mysql_error(), $this->host_site );
            exit;
        }
        else
        {
            while($row = mysql_fetch_row($result))
            {
                $tables[] = $row[0];
            }

            foreach($tables as $table)
            {
                $sql = "create table ".$tmp_db_name.".".$table." like ".$this->db_name.".".$table."";

                mysql_query($sql, $this->link);
                if(mysql_errno())
                {
                    if(SHOW_ECHO_ERROR_CREATE_DB)
                        Log::write( "<p> Ошибка при создании таблицы ".$table. " : " . mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
                else
                {
                    $sql = "insert into ".$tmp_db_name.".".$table." select * from ".$this->db_name.".".$table."";

                    mysql_query($sql, $this->link);
                    if(mysql_errno())
                    {
                        if(SHOW_ECHO_ERROR_CREATE_DB)
                            Log::write( "<p> Ошибка при копировании данных: " . mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                        exit();
                    }
                    else
                    {
                        if(SHOW_ECHO_CREATE_DB)
                        {
                            Log::write( "<p> копирование данных успешно завершено! </p>", $this->host_site );
                        }

                        if($table == $this->tb_toggle)
                        {
                            $upd = "update ".$tmp_db_name.".".$table
                                ." set flag=1";

                            mysql_query($upd, $this->link);
                            if(mysql_errno())
                            {
                                if(SHOW_ECHO_ERROR_CREATE_DB)
                                    Log::write( "<p> Ошибка при добавлении данных: " . mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                exit();
                            }
                            else
                            {
                                if(SHOW_ECHO_CREATE_DB)
                                    Log::write( "<p>данные успешно сохранены!</p>", $this->host_site );
                            }
                        }
                    }
                }
            }

            //<<-------------заносим дату окончания копирования базы (отчета)------
            if(count($tables) && $this->tmp_id)
            {
                $upd = "update ".self::$service_db_name.".".self::$service_table_name_report
                    ." set ".self::$service_field_report_date_stop_report."='".date("Y-m-d H:i:s",mktime())
                    ."' where id=".$this->tmp_id;

                mysql_query($upd, $this->link);
                if(mysql_errno())
                {
                    if(SHOW_ECHO_ERROR_CREATE_DB)
                        Log::write( "<p> Ошибка при добавлении данных: " . mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
                else
                {
                    if(SHOW_ECHO_CREATE_DB)
                        Log::write( "<p>данные успешно сохранены!</p>", $this->host_site );
                }
            }
            //>>end----------------------------------------------------------------
        }
    }

    //метод извлекает все отчеты по текущему проекту указанному в $_GET['site_report']
    function ModelGetReports( $num_menu )
    {
        if(isset($_GET['site_report']) && $_GET['site_report'] !='')
        {
            if (isset($_GET['navigate-page']))
            {
                $page=($_GET['navigate-page']-1);
            }
            else
            {
                $page=0;
            }

            $db_name = str_replace('.', '_', $_GET['site_report']);
            $db_name = str_replace('-', '__', $db_name);//!!!!!!!!!!!!!!! (-) == (__) ModelGetReports() и ModelInit()
            $db_name = $this->prefix_db . $_SESSION['id'] . '_' . $db_name;//получаем название БД

            $start=abs($page * $this->per_page);

            $sel="select * from ".self::$service_table_name_report
                ." where ".self::$service_field_report_user_id."=".$_SESSION['id']
                ." and ".self::$service_field_report_name." like '".$db_name."%'"
                ." order by id desc limit ".$start.", ".$this->per_page;

            $res = mysql_query( $sel, $this->link );
            if(mysql_errno())
            {
                Log::write( "<p class='text-error'>Ошибка ".mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", self::$service_db_name );
                exit();
            }
            elseif(mysql_num_rows($res) > 0)
            {
                while ($row = mysql_fetch_array($res, MYSQL_BOTH))
                {
                    $report                    = new ServiceReport();
                    $report->report_id         = $row['id'];
                    $report->user_id           = intval( $row[self::$service_field_report_user_id] );
                    $report->report_name       = $row[self::$service_field_report_name];
                    $report->date_start_report = $row[self::$service_field_report_date_start_report];
                    $report->date_stop_report  = $row[self::$service_field_report_date_stop_report];

                    if(!$report->date_stop_report)
                    {
                        //открываем новое соединение - ВНИМАНИЕ 4-параметр для нового соединения == true!!!
                        $tmp_link = mysql_connect($this->host, $this->root_login, $this->root_password, true) or die ('Ошибка');
                        //mysql_select_db($db_name, $tmp_link) or die("Ошибка подключения к БД");
                        if(!mysql_select_db($db_name, $tmp_link))
                        {
                            if(SHOW_ECHO_ERROR)
                                Log::write( "<p> Ошибка подключения к БД ".mysql_errno()." файл ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                            exit();
                        }
                        mysql_query("SET NAMES utf8", $tmp_link); // теперь всё будет сохранятся в MySql правильной кодировке

                        $tmp_sel = "select * from ".$this->tb_toggle." where id=1";
                        $tmp_res = mysql_query($tmp_sel, $tmp_link);

                        if(mysql_errno())
                        {
                            if(SHOW_ECHO_ERROR)
                                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                            exit();
                        }
                        else
                        {
                            $tmp_row = mysql_fetch_array($tmp_res, MYSQL_ASSOC);
                            $report->flag_compare = intval($tmp_row['flag_compare']);
                            $report->flag_parse   = intval($tmp_row['flag_parse']);
                            $report->flag_scan    = intval($tmp_row['flag_scan']);

                            if($report->flag_compare == 2)
                            {//сравнение страниц сайта - выполнено
                                //return;//смотри в view_reports.php
                            }
                            elseif($report->flag_compare == 1)
                            {//делаем запрос к таблице counter с текущими страницами
                                $tmp_sel = "select count(*) from ".$this->tb_counter;
                                $tmp_res = mysql_query( $tmp_sel, $tmp_link );

                                if(mysql_errno())
                                {
                                    Log::write( "<p class='text-error'>Ошибка БД ".mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                    exit();
                                }
                                elseif(mysql_num_rows($tmp_res)>0)
                                {
                                    $tmp_row = mysql_fetch_row($tmp_res);
                                    $total_rows = $tmp_row[0];
                                    $tmp_sel = "select * from ".$this->tb_counter." where id=".$total_rows;
                                    $tmp_res = mysql_query( $tmp_sel, $tmp_link );
                                    if(mysql_errno())
                                    {
                                        if(SHOW_ECHO_ERROR)
                                            Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                        exit();
                                    }
                                    else
                                    {
                                        $tmp_row = mysql_fetch_array($tmp_res, MYSQL_ASSOC);
                                        $report->counter_compare_pages = intval($tmp_row['counter_compare_pages']);
                                        $report->counter_compare_page = intval($tmp_row['counter_compare_page']);
                                    }
                                }
                            }
                            else
                            {
                                if($report->flag_parse == 2)
                                {//парсинг страниц сайта - выполнено
                                    //return;//смотри в view_reports.php
                                }
                                elseif($report->flag_parse == 1)
                                {//делаем запрос к таблице counter с текущими страницами
                                    $tmp_sel = "select count(*) from ".$this->tb_counter;
                                    $tmp_res = mysql_query( $tmp_sel, $tmp_link );

                                    if(mysql_errno())
                                    {
                                        Log::write( "<p class='text-error'>Ошибка БД ".mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                        exit();
                                    }
                                    elseif(mysql_num_rows($tmp_res)>0)
                                    {
                                        $tmp_row = mysql_fetch_row($tmp_res);
                                        $total_rows = $tmp_row[0];
                                        $tmp_sel = "select * from ".$this->tb_counter." where id=".$total_rows;
                                        $tmp_res = mysql_query( $tmp_sel, $tmp_link );
                                        if(mysql_errno())
                                        {
                                            if(SHOW_ECHO_ERROR)
                                                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                            exit();
                                        }
                                        else
                                        {
                                            $tmp_row = mysql_fetch_array($tmp_res, MYSQL_ASSOC);
                                            $report->counter_parse_pages = intval($tmp_row['counter_parse_pages']);
                                            $report->counter_parse_page  = intval($tmp_row['counter_parse_page']);
                                        }
                                    }
                                }
                                else
                                {
                                    if($report->flag_scan == 2)
                                    {//поиск страниц сайта - выполнено
                                        //return;//смотри в view_reports.php
                                    }
                                    elseif($report->flag_scan == 1)
                                    {//делаем запрос к таблице counter с текущими страницами
                                        $tmp_sel = "select count(*) from ".$this->tb_counter;
                                        $tmp_res = mysql_query( $tmp_sel, $tmp_link );

                                        if(mysql_errno())
                                        {
                                            Log::write( "<p class='text-error'>Ошибка БД ".mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                            exit();
                                        }
                                        elseif(mysql_num_rows($tmp_res)>0)
                                        {
                                            $tmp_row = mysql_fetch_row($tmp_res);
                                            $total_rows = $tmp_row[0];
                                            $tmp_sel = "select * from ".$this->tb_counter." where id=".$total_rows;
                                            $tmp_res = mysql_query( $tmp_sel, $tmp_link );
                                            if(mysql_errno())
                                            {
                                                if(SHOW_ECHO_ERROR)
                                                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                                                exit();
                                            }
                                            else
                                            {
                                                $tmp_row = mysql_fetch_array($tmp_res, MYSQL_ASSOC);
                                                $report->counter_scan_pages = intval($tmp_row['counter_scan_pages']);
                                                $report->counter_scan_page  = intval($tmp_row['counter_scan_page']);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $report->site_name  = $_GET['site_report'];
                    self::$ar_reports[] = $report;
                }
            }
            else
            {
                //$result_db = '<p class="text-error">Нет сообщений :(</p>';
                return;
            }

            $sel="select count(*) from ".self::$service_table_name_report
                ." where ".self::$service_field_report_user_id."=".$_SESSION['id']
                ." and ".self::$service_field_report_name." like '".$db_name."%'";

            $res = mysql_query( $sel, $this->link );

            if(mysql_errno())
            {
                Log::write( "<p class='text-error'>Ошибка БД ".mysql_errno()." файл: ".__FILE__."  стр. ".__LINE__."</p>", self::$service_db_name );
                return;
            }
            elseif(mysql_num_rows($res)>0)
            {
                $row = mysql_fetch_row($res);
                $total_rows = $row[0];
                $num_pages = ceil($total_rows / $this->per_page);

                if($num_pages < $this->count_navigate_menu )
                {
                    for($i=1;$i<=$num_pages;$i++)
                    {
                        if ($i-1 == $page)
                        {
                            self::$ar_a[] = '<span class="activ"><strong>'.$i.'</strong></span>';
                        }
                        else
                        {
                            self::$ar_a[] = '<a href="'.$_SERVER['PHP_SELF'].'?page='.$num_menu.'&site_report='.$_GET['site_report'].'&navigate-page='.$i.'">'.$i.'</a>';
                        }
                    }
                }
                else
                {
                    $count5 = false;
                    if($num_pages >= 3 && $num_pages <= 5 ) $this->count_navigate_menu = 3;
                    if($num_pages > 5 )
                    {
                        $this->count_navigate_menu = 5;
                        $count5 = true;
                    }
                    //*----------------------------------------------------------------


                    //    <<   [1]   2  [[3]]  4  [5]   >>   ....   8

                    //$left_page           = 0;//                    <<
                    //$start_menu          = 0;//первая ссылка       [1]
                    //$cur_menu            = 0;//текущий елемент    [[3]]
                    //$end_menu            = 0;//последняя ссылка    [5]
                    //$right_page          = 0;//                    >>
                    //$num_pages             //всего на страниц     8
                    //$total_rows            //всего записей в БД   24
                    //$this->per_page        //записей на странице  3
                    //$this->count_navigate_menu = 5;//сколько ссылок в меню отображается в один ряд (3,5,7,9,11 - только нечетные числа, для красоты)

                    $start_menu = 0;
                    $end_menu   = $this->count_navigate_menu;
                    $right_page = 0;

                    if(($page+1) > $this->count_navigate_menu)
                    {
                        $start_menu = abs($this->count_navigate_menu-($page+1));
                        $end_menu = $this->count_navigate_menu + abs($this->count_navigate_menu-($page+1));
                    }

                    /*if($page >= floor($this->count_navigate_menu / 2))
                    {
                        $start_menu = $page-floor($this->count_navigate_menu / 2);
                        //$end_menu = $start_menu
                    }*/

                    //|<<
                    if($count5 && $page > 1)self::$ar_a[] = '<a href="'.$_SERVER['PHP_SELF'].'?page='.$num_menu.'&site_report='.$_GET['site_report'].'&navigate-page=1">|&laquo;</a>';
                    //<<
                    if($page > 0) self::$ar_a[] = '<a href="'.$_SERVER['PHP_SELF'].'?page='.$num_menu.'&site_report='.$_GET['site_report'].'&navigate-page='.$page.'">&laquo;</a>';


                    for( $i = $start_menu; $i < $end_menu; $i++ )
                    {
                        if ($i == $page)
                        {
                            self::$ar_a[] = '<span class="activ"><strong>'.($i+1).'</strong></span>';
                            $right_page=$i+1;
                            $right_page = ($right_page == $num_pages) ? $num_pages : (++$right_page) ;
                        }
                        else
                        {
                            self::$ar_a[] = '<a href="'.$_SERVER['PHP_SELF'].'?page='.$num_menu.'&site_report='.$_GET['site_report'].'&navigate-page='.($i+1).'">'.($i+1).'</a>';
                        }
                    }


                    //>>
                    if($page < $num_pages-1)
                        self::$ar_a[] = '<a href="'.$_SERVER['PHP_SELF'].'?page='.$num_menu.'&site_report='.$_GET['site_report'].'&navigate-page='.$right_page.'">&raquo;</a>';

                    if($end_menu < $num_pages)
                    {
                        //>>|
                        if($count5 && $page < $num_pages-2) self::$ar_a[] = '<a href="'.$_SERVER['PHP_SELF'].'?page='.$num_menu.'&site_report='.$_GET['site_report'].'&navigate-page='.$num_pages.'">&raquo;|</a>';
                        //....
                        //self::$ar_a[] = '<span class=""><small>всего</small></span>';
                        //8
                        //self::$ar_a[] = '<a href="'.$_SERVER['PHP_SELF'].'?page='.$num_menu.'&navigate-page='.$num_pages.'">['.$num_pages.']</a>';
                    }
                }
            }
        }
    }


    //=================================================================================================================
    //--------------------методы извлечения данных для отчёта----------------------------------------------------------
    //=================================================================================================================


    //детальный отчет для отправки на email
    function ModelGetDetailReportForEmail( $report_name, $user_id_session=0 )
    {
        $limit_5 = 5;//количество строк, вытаскиваемых из бд.
        $detail_report = new ServiceDetailReport();
        $detail_report->report_name   = $report_name;
        if(!$this->host_site && isset($_REQUEST['host']))$this->host_site = $_REQUEST['host'];//для записи ошибок в файл - формируется в файле view_reports.php & view_report.php
        if(!$user_id_session)$user_id_session = $_SESSION['id'];

        global $MODEL_DEFINE_TAGS;
        $this->MODEL_DEFINE_TAGS = $MODEL_DEFINE_TAGS;
        foreach($this->MODEL_DEFINE_TAGS as $k=>$v)
        {
            $this->ar_tags[] = $k;
        }

        //получаем начало и конец формирования отчёта---------------
        $sel = "select * from ".self::$service_db_name.".".self::$service_table_name_report
            ." where ".self::$service_field_report_user_id."=".$user_id_session
            ." and ".self::$service_field_report_name." like '".$detail_report->report_name."'";
        $res = mysql_query( $sel, $this->link );
        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            //Log::write( $sel, $this->host_site );
            exit();
        }
        elseif(mysql_num_rows($res)>0)
        {
            while($row = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $detail_report->date_start_report = $row[self::$service_field_report_date_start_report];
                $detail_report->date_stop_report = $row[self::$service_field_report_date_stop_report];
            }
        }
        //<<end-----------------------------------------------------

        //получаем начало и конец текущего и всех предыдущих анализов сайта---------------
        $sel = "select * from " . $detail_report->report_name . "." . $this->tb_counter ;
        $res = mysql_query( $sel, $this->link );
        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        elseif(mysql_num_rows($res)>0)
        {
            while($row = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $detail_report->ar_dates[] = array( $row['id'], $row['date_start_scan'], $row['date_stop_scan'] );
            }
            if($i = count($detail_report->ar_dates))
            {
                $last = $detail_report->ar_dates[$i-1];
                $detail_report->date_start_scan = $last[1];
                $detail_report->date_stop_scan = $last[2];
            }
        }
        //<<end-----------------------------------------------------

        //<<получаем количество ссылок-------------------------------------
        $sel = "select count(*) from " . $detail_report->report_name . "." . $this->tb_links;
        $res = mysql_query( $sel, $this->link );
        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        elseif(mysql_num_rows($res)>0)
        {
            $row = mysql_fetch_row($res);
            $detail_report->list_links = $row[0];
        }
        //<<end-----------------------------------------------------

        //получаем количество просканированых страниц-------------------
        $sel = "select count(*) from " . $detail_report->report_name . "." . $this->tb_pages . " where http_code=200 AND content_type LIKE '%text/html%'";
        $res = mysql_query( $sel, $this->link );
        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        elseif(mysql_num_rows($res)>0)
        {
            $row = mysql_fetch_row($res);
            $detail_report->list_pages = $row[0];
        }
        //<<end-----------------------------------------------------

        //получаем страницы с ошибками (>=404) для отчёта-------------------
        $sel = "select * from " . $detail_report->report_name . "." . $this->tb_pages
            ." where http_code=".self::$curl_http_returned_error." limit ".$limit_5;
        $res = mysql_query( $sel, $this->link );
        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        elseif(mysql_num_rows($res)>0)
        {
            while($row = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $detail_report->ar_errors404[html_entity_decode($row['parent_url'], ENT_QUOTES, 'UTF-8')][] = html_entity_decode($row['url'], ENT_QUOTES, 'UTF-8');
            }
        }
        //<<end-----------------------------------------------------

        //получаем страницы с ошибками (>=500) для отчёта-------------------
        $sel = "select * from " . $detail_report->report_name . "." . $this->tb_pages
            ." where http_code=".self::$curl_operation_timeout." limit ".$limit_5;
        $res = mysql_query( $sel, $this->link );
        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        elseif(mysql_num_rows($res)>0)
        {
            while($row = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $detail_report->ar_errors500[html_entity_decode($row['parent_url'], ENT_QUOTES, 'UTF-8')][] = html_entity_decode($row['url'], ENT_QUOTES, 'UTF-8');
            }
        }
        //<<end-----------------------------------------------------

        //<<получаем внешние ссылки---------------------------------
        $sel = "SELECT *, t1.id as id_page FROM "
            . $detail_report->report_name . "." . $this->tb_pages . " AS t1 JOIN "
            . $detail_report->report_name . "." . $this->tb_links . " AS t2 "
            ."WHERE (t1.http_code=200 AND t1.content_type LIKE '%text/html%')"
            ."AND (t1.links=t2.page_id AND t2.internal_link=0) LIMIT ".$limit_5;

        $res = mysql_query( $sel, $this->link );
        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            //Log::write( $sel, $this->host_site );
            exit();
        }
        elseif(mysql_num_rows($res)>0)
        {
            while($row = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $detail_report->ar_pages_internal_links[html_entity_decode($row['url'], ENT_QUOTES, 'UTF-8')][] = array(
                    'id' =>$row['id_page'],
                    'href'=>html_entity_decode($row['href'], ENT_QUOTES, 'UTF-8'),
                    'anchor'=>html_entity_decode($row['anchor'], ENT_QUOTES, 'UTF-8'));
            }
        }
        //>>end-----------------------------------------------------

        //<<получаем страницы у которых нет || пастой title, keywords, description---------------------------------
        $sel = "SELECT * FROM " . $detail_report->report_name . "." . $this->tb_pages
            . " AS t1 WHERE (t1.http_code=200 AND t1.content_type LIKE '%text/html%' AND (t1.title=0 OR t1.title IS NULL)) LIMIT ".$limit_5;
        $res = mysql_query( $sel, $this->link );
        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        elseif(mysql_num_rows($res)>0)
        {
            while($row = mysql_fetch_array($res, MYSQL_ASSOC))
                $detail_report->ar_pages_not_title[] = array('id' => $row['id'], 'url' => html_entity_decode($row['url'], ENT_QUOTES, 'UTF-8'));
        }
        //>>end-----------------------------------------------------

        //<<получаем страницы у которых нет || пастой title, keywords, description---------------------------------
        $sel = "SELECT * FROM " . $detail_report->report_name . "." . $this->tb_pages
            . " AS t1 WHERE (t1.http_code=200 AND t1.content_type LIKE '%text/html%' AND (t1.keywords=0 OR t1.keywords IS NULL)) LIMIT ".$limit_5;
        $res = mysql_query( $sel, $this->link );
        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        elseif(mysql_num_rows($res)>0)
        {
            while($row = mysql_fetch_array($res, MYSQL_ASSOC))
                $detail_report->ar_pages_not_keywords[] = array('id' => $row['id'], 'url' => html_entity_decode($row['url'], ENT_QUOTES, 'UTF-8'));
        }
        //>>end-----------------------------------------------------

        //<<получаем страницы у которых нет || пастой title, keywords, description---------------------------------
        $sel = "SELECT * FROM " . $detail_report->report_name . "." . $this->tb_pages
            . " AS t1 WHERE (t1.http_code=200 AND t1.content_type LIKE '%text/html%' AND (t1.description=0 OR t1.description IS NULL)) LIMIT ".$limit_5;
        $res = mysql_query( $sel, $this->link );
        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        elseif(mysql_num_rows($res)>0)
        {
            while($row = mysql_fetch_array($res, MYSQL_ASSOC))
                $detail_report->ar_pages_not_description[] = array('id' => $row['id'], 'url' => html_entity_decode($row['url'], ENT_QUOTES, 'UTF-8'));
        }
        //>>end-----------------------------------------------------

        self::$detail_report = $detail_report;
    }

    //детальный отчет для вывода на страницу
    function ModelGetDetailReportForPage( $report_name, $user_id_session=0 )
    {
        $detail_report = new ServiceDetailReport();
        $detail_report->report_name   = $report_name;
        if(!$this->host_site && isset($_REQUEST['host']))$this->host_site = $_REQUEST['host'];//для записи ошибок в файл - формируется в файле view_reports.php & view_report.php
        if(!$user_id_session)$user_id_session = $_SESSION['id'];

        global $MODEL_DEFINE_TAGS;
        $this->MODEL_DEFINE_TAGS = $MODEL_DEFINE_TAGS;
        foreach($this->MODEL_DEFINE_TAGS as $k=>$v)
        {
            $this->ar_tags[] = $k;
        }

        //получаем начало и конец формирования отчёта---------------
        $sel = "select * from ".self::$service_db_name.".".self::$service_table_name_report
            ." where ".self::$service_field_report_user_id."=".$user_id_session
            ." and ".self::$service_field_report_name." like '".$detail_report->report_name."'";
        $res = mysql_query( $sel, $this->link );
        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        elseif(mysql_num_rows($res)>0)
        {
            while($row = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $detail_report->date_start_report = $row[self::$service_field_report_date_start_report];
                $detail_report->date_stop_report = $row[self::$service_field_report_date_stop_report];
            }
        }
        //<<end-----------------------------------------------------

        //получаем начало и конец текущего и всех предыдущих анализов сайта---------------
        $sel = "select * from " . $detail_report->report_name . "." . $this->tb_counter ;
        $res = mysql_query( $sel, $this->link );
        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        elseif(mysql_num_rows($res)>0)
        {
            while($row = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $detail_report->ar_dates[] = array( $row['id'], $row['date_start_scan'], $row['date_stop_scan'] );
            }
            if($i = count($detail_report->ar_dates))
            {
                $last = $detail_report->ar_dates[$i-1];
                $detail_report->date_start_scan = $last[1];
                $detail_report->date_stop_scan = $last[2];
            }
        }
        //<<end-----------------------------------------------------

        //<<получаем количество ссылок-------------------------------------
        $sel = "select count(*) from " . $detail_report->report_name . "." . $this->tb_links;
        $res = mysql_query( $sel, $this->link );
        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        elseif(mysql_num_rows($res)>0)
        {
            $row = mysql_fetch_row($res);
            $detail_report->list_links = $row[0];
        }
        //<<end-----------------------------------------------------

        //получаем количество просканированых страниц-------------------
        $sel = "select count(*) from " . $detail_report->report_name . "." . $this->tb_pages . " where http_code=200 AND content_type LIKE '%text/html%'";
        $res = mysql_query( $sel, $this->link );
        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        elseif(mysql_num_rows($res)>0)
        {
            $row = mysql_fetch_row($res);
            $detail_report->list_pages = $row[0];
        }
        //<<end-----------------------------------------------------

        //получаем страницы с ошибками (>=404 || >=500) для отчёта-------------------
        $sel = "select * from " . $detail_report->report_name . "." . $this->tb_pages . " where http_code=".self::$curl_http_returned_error." or http_code=".self::$curl_operation_timeout."";
        $res = mysql_query( $sel, $this->link );
        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        elseif(mysql_num_rows($res)>0)
        {
            while($row = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                if($row['http_code'] == self::$curl_http_returned_error)
                { // 22
                    $detail_report->ar_errors404[html_entity_decode($row['parent_url'], ENT_QUOTES, 'UTF-8')][] = html_entity_decode($row['url'], ENT_QUOTES, 'UTF-8');
                }
                else
                { // 28
                    $detail_report->ar_errors500[html_entity_decode($row['parent_url'], ENT_QUOTES, 'UTF-8')][] = html_entity_decode($row['url'], ENT_QUOTES, 'UTF-8');
                }
            }
        }
        //<<end-----------------------------------------------------

        //<<получаем внешние ссылки---------------------------------
        $sel = "SELECT *, t1.id as id_page FROM "
            . $detail_report->report_name . "." . $this->tb_pages . " AS t1 JOIN "
            . $detail_report->report_name . "." . $this->tb_links . " AS t2 "
            ."WHERE (t1.http_code=200 AND t1.content_type LIKE '%text/html%')"
            ."AND (t1.links=t2.page_id AND t2.internal_link=0)";

        $res = mysql_query( $sel, $this->link );
        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            //Log::write( $sel, $this->host_site );
            exit();
        }
        elseif(mysql_num_rows($res)>0)
        {
            while($row = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $detail_report->ar_pages_internal_links[html_entity_decode($row['url'], ENT_QUOTES, 'UTF-8')][] = array(
                    'id' =>$row['id_page'],
                    'href'=>html_entity_decode($row['href'], ENT_QUOTES, 'UTF-8'),
                    'anchor'=>html_entity_decode($row['anchor'], ENT_QUOTES, 'UTF-8'));
            }
        }
        //>>end-----------------------------------------------------

        //<<получаем страницы у которых нет || пастой title, keywords, description---------------------------------
        $sel = "SELECT * FROM " . $detail_report->report_name . "." . $this->tb_pages
            . " AS t1 WHERE (t1.http_code=200 AND t1.content_type LIKE '%text/html%' AND (t1.title=0 OR t1.title IS NULL)) OR"
            . " (t1.http_code=200 AND t1.content_type LIKE '%text/html%' AND (t1.keywords=0 OR t1.keywords IS NULL)) OR"
            . " (t1.http_code=200 AND t1.content_type LIKE '%text/html%' AND (t1.description=0 OR t1.description IS NULL))";
        $res = mysql_query( $sel, $this->link );
        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        elseif(mysql_num_rows($res)>0)
        {
            while($row = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                if($row['title'] === null)
                {
                    $detail_report->ar_pages_not_title[] = array('id' => $row['id'], 'url' => html_entity_decode($row['url'], ENT_QUOTES, 'UTF-8'));
                }
                if($row['keywords'] === null)
                {
                    $detail_report->ar_pages_not_keywords[] = array('id' => $row['id'], 'url' => html_entity_decode($row['url'], ENT_QUOTES, 'UTF-8'));
                }
                if($row['description'] === null)
                {
                    $detail_report->ar_pages_not_description[] = array('id' => $row['id'], 'url' => html_entity_decode($row['url'], ENT_QUOTES, 'UTF-8'));
                }
            }
        }
        //>>end-----------------------------------------------------

        self::$detail_report = $detail_report;
    }

    //метод определяющий какую инфу нужно предоставить пользователю (pages, links, unique_links)
    function ModelGetDetailReport( $report_name )
    {
        $detail_report = new ServiceDetailReport();
        $detail_report->report_name   = $report_name;
        if(!$this->host_site && isset($_REQUEST['host']))$this->host_site = $_REQUEST['host'];//для записи ошибок в файл - формируется в файле view_reports.php & view_report.php
        //$user_id_session = $_SESSION['id'];

        global $MODEL_DEFINE_TAGS;
        $this->MODEL_DEFINE_TAGS = $MODEL_DEFINE_TAGS;
        foreach($this->MODEL_DEFINE_TAGS as $k=>$v)
        {
            $this->ar_tags[] = $k;
        }

        if(isset($_REQUEST['list_pages']) && $_REQUEST['list_pages'] != '')
        {
            $this->ModelDetailReport_GetListPages( $detail_report );
        }
        elseif(isset($_REQUEST['list_links']) && $_REQUEST['list_links'] != '')
        {
            $this->ModelDetailReport_GetListLinks( $detail_report );
        }
        elseif(isset($_REQUEST['list_internal_links']) && $_REQUEST['list_internal_links'] != '')
        {
            $this->ModelDetailReport_GetListInternalLinks( $detail_report );
        }
        elseif(isset($_REQUEST['list_external_links']) && $_REQUEST['list_external_links'] != '')
        {
            $this->ModelDetailReport_GetListExternalLinks( $detail_report );
        }
        elseif(isset($_REQUEST['list_unique_links']) && $_REQUEST['list_unique_links'] != '')
        {
            $this->ModelDetailReport_GetListUniqueLinks( $detail_report );
        }
        elseif(isset($_REQUEST['list_cycle_links']) && $_REQUEST['list_cycle_links'] != '')
        {
            $this->ModelDetailReport_GetListCycleLinks( $detail_report );
        }
        elseif(isset($_REQUEST['p_show']) && $_REQUEST['p_show'] == 'load_table')
        {
            $this->ModelDetailReport_GetLoadTable( $detail_report );
        }
        elseif(isset($_REQUEST['p_show']) && $_REQUEST['p_show'] == 'resources_table')
        {
            $this->ModelDetailReport_GetResourcesTable( $detail_report );
        }
        elseif(isset($_REQUEST['get_resources_img']) && intval($_REQUEST['get_resources_img']))
        {
            $this->ModelDetailReport_GetResourcesImg( $detail_report, intval($_REQUEST['get_resources_img']));
        }
        elseif(isset($_REQUEST['get_resources_exlink']) && intval($_REQUEST['get_resources_exlink']))
        {
            $this->ModelDetailReport_GetResourcesExLink( $detail_report, intval($_REQUEST['get_resources_exlink']));
        }
        elseif(isset($_REQUEST['p_show']) && $_REQUEST['p_show'] == 'content_table')
        {
            $this->ModelDetailReport_GetContentTable( $detail_report );
        }
        elseif(isset($_REQUEST['p_show']) && $_REQUEST['p_show'] == 'tags_table')
        {
            $this->ModelDetailReport_GetTagsTable( $detail_report );
        }
        elseif((isset($_REQUEST['get_tags']) && intval($_REQUEST['get_tags'])) && (isset($_REQUEST['type']) && $_REQUEST['type'] != ''))
        {
            $this->ModelDetailReport_GetTags( $detail_report, intval($_REQUEST['get_tags']), $_REQUEST['type'] );
        }
        else
        {
            $this->ModelDetailReport_GetGeneralReport( $detail_report );
        }

        self::$detail_report = $detail_report;
    }

    //метод извлекает <общий технический анализ> для отчета - НУЖНО ОПТИМИЗИРОВАТЬ ЗАПРОСЫ К БД., ИСПОЛЬЗОВАТЬ SUM() и COUNT(),
    //вместо того чтобы получать все данные и делить их на $detail_report->list_pages
    function ModelDetailReport_GetGeneralReport( ServiceDetailReport & $detail_report )
    {
        $detail_report->ip_address = gethostbyname($this->host_site);//получаем IP-адрес сайта
        $detail_report->domain     = $this->host_site;//получаем domain сайта

        $sel = "select count(id) from " . $detail_report->report_name . "." . $this->tb_counter ;
        $res = mysql_query( $sel, $this->link );
        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        elseif(mysql_num_rows($res)>0)
        {
            $row = mysql_fetch_row($res);
            $sel = "select date_start_scan, date_stop_scan from " . $detail_report->report_name . "." . $this->tb_counter . " where id=".$row[0] ;
            //<<получаем начало и конец сканирования--------------------------
            $res = mysql_query($sel, $this->link);
            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            else
            {
                $row = mysql_fetch_array($res, MYSQL_ASSOC);
                $detail_report->date_start_scan = $row['date_start_scan'];
                $detail_report->date_stop_scan = $row['date_stop_scan'];
            }
            //>>end------------------------------------------------------------
        }


        $sel = "select count(id) from " . $detail_report->report_name . "." . $this->tb_pages . " where http_code=200 AND content_type LIKE '%text/html%'";
        $res = mysql_query( $sel, $this->link );
        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        elseif(mysql_num_rows($res)>0)
        {
            $row = mysql_fetch_array($res, MYSQL_NUM);
            $detail_report->list_pages = $row[0];//получаем количество просканированых страниц

            //<<получаем лимит на просканирование страниц----------------------
            $sel = "select limit_pages from " . $detail_report->report_name . "." . $this->tb_tools . " where id=1" ;
            $res = mysql_query($sel, $this->link);
            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            else
            {
                $row = mysql_fetch_array($res, MYSQL_ASSOC);
                $detail_report->limit_pages = $row['limit_pages'];//получаем лимит на просканирование страниц
            }
            //>>end------------------------------------------------------------

            //<<получаем количество уникальных ссылок--------------------------
            //$sel = "SELECT COUNT(id) FROM " . $report_name . "." . $this->tb_pages ." WHERE date_change_page BETWEEN STR_TO_DATE('".$detail_report->date_start_scan."', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('".$detail_report->date_stop_scan."', '%Y-%m-%d %H:%i:%s')" ;
            $sel = "SELECT COUNT(id) FROM " . $detail_report->report_name . "." . $this->tb_pages . " where exist=1";
            $res = mysql_query( $sel, $this->link );
            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            elseif(mysql_num_rows($res)>0)
            {
                $row = mysql_fetch_row($res);
                $detail_report->list_unique_links = $row[0];//получаем количество уникальных ссылок
            }
            //>>end------------------------------------------------------------

            //<<получаем количество ссылок-------------------------------------
            $sel = "select count(id) from " . $detail_report->report_name . "." . $this->tb_links ;
            $res = mysql_query( $sel, $this->link );
            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            elseif(mysql_num_rows($res)>0)
            {
                $row = mysql_fetch_row($res);
                $detail_report->list_links = $row[0];//получаем количество ссылок
            }
            //>>end------------------------------------------------------------

            //<<получаем количество внутренних и внешних ссылок----------------
            $sel = "select internal_link, count(id) as cl from " . $detail_report->report_name . "." . $this->tb_links . " group by internal_link";
            $res = mysql_query( $sel, $this->link );
            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            elseif(mysql_num_rows($res)>0)
            {
                while($row = mysql_fetch_array($res, MYSQL_ASSOC))
                {
                    if(intval($row['internal_link']) == 1)
                    {//получаем количество внутренних ссылок
                        $detail_report->list_internal_links = $row['cl'];
                    }
                    else
                    {//получаем количество внешних ссылок
                        $detail_report->list_external_links = $row['cl'];
                    }
                }
            }
            //>>end------------------------------------------------------------

            //<<получаем циклические ссылки------------------------------------
            $sel = "SELECT COUNT(*) FROM "
                . $detail_report->report_name . "." . $this->tb_pages ." AS t1 JOIN "
                . $detail_report->report_name . "." . $this->tb_links ." AS t2 "
                . "WHERE t1.id=t2.page_id AND t1.url=t2.href";
            $res = mysql_query( $sel, $this->link );
            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            elseif(mysql_num_rows($res)>0)
            {
                $row = mysql_fetch_row($res);
                $detail_report->list_cycle_links = $row[0];//получаем количество циклических ссылок
            }
            //>>end------------------------------------------------------------

            //<<получаем максимальный уровень вложенности ссылок---------------
            $sel = "select MAX(level_links) as level from " . $detail_report->report_name . "." . $this->tb_pages ." where http_code=200 AND content_type LIKE '%text/html%'" ;
            $res = mysql_query($sel, $this->link);
            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            else
            {
                $row = mysql_fetch_array($res, MYSQL_NUM);
                $detail_report->level_links = $row[0];//получаем максимальный уровень вложенности ссылок
            }
            //>>end------------------------------------------------------------

            //<<получаем среднее время соединения с сервером---------------
            $sel = "select connect_time from " . $detail_report->report_name . "." . $this->tb_pages ." where http_code=200 AND content_type LIKE '%text/html%'" ;
            $res = mysql_query($sel, $this->link);
            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            else
            {
                $connect_time = 0;
                while($row = mysql_fetch_array($res, MYSQL_ASSOC))
                {
                    $connect_time = $connect_time + floatval($row['connect_time']);//получаем среднее время загрузки страницы
                }
                $connect_time = round($connect_time, 3);
                $detail_report->average_time_connect_server = round( ($connect_time / $detail_report->list_pages) , 3);
            }
            //>>end------------------------------------------------------------

            //<<получаем среднее время загрузки страницы-----------------------
            $sel = "select total_time from " . $detail_report->report_name . "." . $this->tb_pages ." where http_code=200 AND content_type LIKE '%text/html%'" ;
            $res = mysql_query($sel, $this->link);
            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            else
            {
                $total_time = 0;
                while($row = mysql_fetch_array($res, MYSQL_ASSOC))
                {
                    $total_time = $total_time + floatval($row['total_time']);//получаем среднее время загрузки страницы
                }
                $total_time = round($total_time, 3);
                $detail_report->average_time_load_page = round( ($total_time / $detail_report->list_pages) , 3);
            }
            //>>end------------------------------------------------------------

            //<<получаем среднюю скорость--------------------------------------
            $sel = "select page_speed from " . $detail_report->report_name . "." . $this->tb_pages ." where http_code=200 AND content_type LIKE '%text/html%'" ;
            $res = mysql_query($sel, $this->link);
            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            else
            {
                $page_speed = 0;
                while($row = mysql_fetch_array($res, MYSQL_ASSOC))
                {
                    $page_speed = $page_speed + floatval($row['page_speed']);//получаем среднюю скорость
                }
                $page_speed = round($page_speed, 3);
                $detail_report->average_speed = round( (($page_speed / $detail_report->list_pages) / 1000) , 3); //1-кбит == 1000-бит http://www.artlebedev.ru/kovodstvo/sections/84/
            }
            //>>end------------------------------------------------------------

            //<<получаем Объем страниц проекта---------------------------------
            $sel = "select page_size from " . $detail_report->report_name . "." . $this->tb_pages ." where http_code=200 AND content_type LIKE '%text/html%'" ;
            $res = mysql_query($sel, $this->link);
            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            else
            {
                $page_size = 0;
                while($row = mysql_fetch_array($res, MYSQL_ASSOC))
                {
                    $page_size = $page_size + floatval($row['page_size']);//получаем среднюю скорость
                }
                //1-кбит == 1000-бит http://www.artlebedev.ru/kovodstvo/sections/84/
                $page_size = round($page_size, 3);
                $detail_report->kb_volume_pages_project = round($page_size / 1024, 3); //1-кбит == 1000-бит http://www.artlebedev.ru/kovodstvo/sections/84/
                $detail_report->mb_volume_pages_project = round($detail_report->kb_volume_pages_project / 1024, 3); //1-мбит == 1000-кбит http://www.artlebedev.ru/kovodstvo/sections/84/
                $detail_report->kb_average_volume_pages_project = round( (($page_size / $detail_report->list_pages) / 1024) , 3);
            }
            //>>end------------------------------------------------------------
        }
    }

    //метод извлекает страницы для подробного отчета
    function ModelDetailReport_GetListPages( ServiceDetailReport & $detail_report )
    {
        $num_menu = intval($_REQUEST['page']);
        $this->per_page = $this->row_page;
        $params = '&list_pages='.$_GET['list_pages'];

        if (isset($_GET['navigate-page']))
        {
            $page=($_GET['navigate-page']-1);
        }
        else
        {
            $page=0;
        }

        $start=abs($page * $this->per_page);

        $sel = "SELECT url FROM " . $detail_report->report_name . "." . $this->tb_pages
            . " WHERE http_code=200 AND content_type LIKE '%text/html%' AND exist=1"
            . " LIMIT ".$start.", ".$this->per_page;

        $res = mysql_query( $sel, $this->link );
        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        else
        {
            while($row = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $url = html_entity_decode($row['url'], ENT_QUOTES, 'UTF-8');
                $ar_components_link = parse_url($url);

                if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
                    $tmp_url = $url;
                }else{
                    if(strpos($ar_components_link['path'], '/') === 0){
                        $tmp_url = $this->protocol.'://'.$this->host_site.$url;
                    }else{
                        $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$url;
                    }
                }
                $detail_report->ar_pages[] = array(
                    'link' => $tmp_url,
                    'url' => $url
                );
            }

            $sel = "SELECT COUNT(id) FROM ".$detail_report->report_name.".".$this->tb_pages
                . " WHERE http_code=200 AND content_type LIKE '%text/html%' AND exist=1";

            $res = mysql_query( $sel, $this->link );

            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            elseif(mysql_num_rows($res)>0)
            {
                $row = mysql_fetch_row($res);
                $total_rows = $row[0];
                $this->Model_GenerationMenu($page, $num_menu, $total_rows, $params);
            }
        }
    }

    //метод извлекает ссылки для подробного отчета
    function ModelDetailReport_GetListLinks( ServiceDetailReport & $detail_report )
    {
        $num_menu = intval($_REQUEST['page']);
        $this->per_page = $this->row_page;
        $params = '&list_links='.$_GET['list_links'];

        if (isset($_GET['navigate-page']))
        {
            $page=($_GET['navigate-page']-1);
        }
        else
        {
            $page=0;
        }

        $start=abs($page * $this->per_page);

        $sel = "SELECT t1.*, t2.url AS url FROM "
            . $detail_report->report_name . "." . $this->tb_links ." AS t1 JOIN "
            . $detail_report->report_name . "." . $this->tb_pages ." AS t2 "
            . "WHERE t1.page_id=t2.id"." LIMIT ".$start.", ".$this->per_page;

        $res = mysql_query( $sel, $this->link );
        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        else
        {
            while($row = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $url = html_entity_decode($row['href'], ENT_QUOTES, 'UTF-8');
                $ar_components_link = parse_url($url);

                if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
                    $tmp_url = $url;
                }else{
                    if(strpos($ar_components_link['path'], '/') === 0){
                        $tmp_url = $this->protocol.'://'.$this->host_site.$url;
                    }else{
                        $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$url;
                    }
                }
                $detail_report->ar_links[] = array(
                    'link' => $tmp_url,
                    'href' => $url,
                    'anchor' => html_entity_decode($row['anchor'], ENT_QUOTES, 'UTF-8'),
                    'internal' => intval($row['internal_link']),
                    'parent_url' => html_entity_decode($row['url'], ENT_QUOTES, 'UTF-8')
                );
            }

            $sel = "SELECT COUNT(id) FROM ".$detail_report->report_name.".".$this->tb_links;

            $res = mysql_query( $sel, $this->link );

            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            elseif(mysql_num_rows($res)>0)
            {
                $row = mysql_fetch_row($res);
                $total_rows = $row[0];
                $this->Model_GenerationMenu($page, $num_menu, $total_rows, $params);
            }
        }
    }

    //метод извлекает внутренние ссылки для подробного отчета
    function ModelDetailReport_GetListInternalLinks( ServiceDetailReport & $detail_report )
    {
        $num_menu = intval($_REQUEST['page']);
        $this->per_page = $this->row_page;
        $params = '&list_internal_links='.$_GET['list_internal_links'];

        if (isset($_GET['navigate-page']))
        {
            $page=($_GET['navigate-page']-1);
        }
        else
        {
            $page=0;
        }

        $start=abs($page * $this->per_page);

        $sel = "SELECT t1.*, t2.url AS url FROM "
            . $detail_report->report_name . "." . $this->tb_links ." AS t1 JOIN "
            . $detail_report->report_name . "." . $this->tb_pages ." AS t2 "
            . "WHERE t1.page_id=t2.id AND t1.internal_link=1"." LIMIT ".$start.", ".$this->per_page;

        $res = mysql_query( $sel, $this->link );
        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        else
        {
            while($row = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $url = html_entity_decode($row['href'], ENT_QUOTES, 'UTF-8');
                $ar_components_link = parse_url($url);

                if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
                    $tmp_url = $url;
                }else{
                    if(strpos($ar_components_link['path'], '/') === 0){
                        $tmp_url = $this->protocol.'://'.$this->host_site.$url;
                    }else{
                        $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$url;
                    }
                }
                $detail_report->ar_internal_links[] = array(
                    'link' => $tmp_url,
                    'href' => $url,
                    'anchor' => html_entity_decode($row['anchor'], ENT_QUOTES, 'UTF-8'),
                    'internal' => intval($row['internal_link']),
                    'parent_url' => html_entity_decode($row['url'], ENT_QUOTES, 'UTF-8')
                );
            }

            $sel = "SELECT COUNT(id) FROM ".$detail_report->report_name.".".$this->tb_links." WHERE internal_link=1";

            $res = mysql_query( $sel, $this->link );

            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            elseif(mysql_num_rows($res)>0)
            {
                $row = mysql_fetch_row($res);
                $total_rows = $row[0];
                $this->Model_GenerationMenu($page, $num_menu, $total_rows, $params);
            }
        }
    }

    //метод извлекает внешние ссылки для подробного отчета
    function ModelDetailReport_GetListExternalLinks( ServiceDetailReport & $detail_report )
    {
        $num_menu = intval($_REQUEST['page']);
        $this->per_page = $this->row_page;
        $params = '&list_external_links='.$_GET['list_external_links'];

        if (isset($_GET['navigate-page']))
        {
            $page=($_GET['navigate-page']-1);
        }
        else
        {
            $page=0;
        }

        $start=abs($page * $this->per_page);

        $sel = "SELECT t1.*, t2.url AS url FROM "
            . $detail_report->report_name . "." . $this->tb_links ." AS t1 JOIN "
            . $detail_report->report_name . "." . $this->tb_pages ." AS t2 "
            . "WHERE t1.page_id=t2.id AND t1.internal_link=0"." LIMIT ".$start.", ".$this->per_page;

        $res = mysql_query( $sel, $this->link );
        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        else
        {
            while($row = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $url = html_entity_decode($row['href'], ENT_QUOTES, 'UTF-8');
                $ar_components_link = parse_url($url);

                if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
                    $tmp_url = $url;
                }else{
                    if(strpos($ar_components_link['path'], '/') === 0){
                        $tmp_url = $this->protocol.'://'.$this->host_site.$url;
                    }else{
                        $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$url;
                    }
                }
                $detail_report->ar_external_links[] = array(
                    'link' => $tmp_url,
                    'href' => $url,
                    'anchor' => html_entity_decode($row['anchor'], ENT_QUOTES, 'UTF-8'),
                    'internal' => intval($row['internal_link']),
                    'parent_url' => html_entity_decode($row['url'], ENT_QUOTES, 'UTF-8')
                );
            }

            $sel = "SELECT COUNT(id) FROM ".$detail_report->report_name.".".$this->tb_links." WHERE internal_link=0";

            $res = mysql_query( $sel, $this->link );

            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            elseif(mysql_num_rows($res)>0)
            {
                $row = mysql_fetch_row($res);
                $total_rows = $row[0];
                $this->Model_GenerationMenu($page, $num_menu, $total_rows, $params);
            }
        }
    }

    //метод извлекает уникальные ссылки для подробного отчета
    function ModelDetailReport_GetListUniqueLinks( ServiceDetailReport & $detail_report )
    {
        $num_menu = intval($_REQUEST['page']);
        $this->per_page = $this->row_page;
        $params = '&list_unique_links='.$_GET['list_unique_links'];

        if (isset($_GET['navigate-page']))
        {
            $page=($_GET['navigate-page']-1);
        }
        else
        {
            $page=0;
        }

        $start=abs($page * $this->per_page);

        $sel = "select url from " . $detail_report->report_name . "." . $this->tb_pages
            . " where exist=1". " limit ".$start.", ".$this->per_page;;

        $res = mysql_query( $sel, $this->link );
        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        else
        {
            while($row = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $url = html_entity_decode($row['url'], ENT_QUOTES, 'UTF-8');
                $ar_components_link = parse_url($url);

                if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
                    $tmp_url = $url;
                }else{
                    if(strpos($ar_components_link['path'], '/') === 0){
                        $tmp_url = $this->protocol.'://'.$this->host_site.$url;
                    }else{
                        $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$url;
                    }
                }
                $detail_report->ar_unique_links[] = array(
                    'link' => $tmp_url,
                    'url' => $url
                );
            }

            $sel = "SELECT COUNT(id) FROM ".$detail_report->report_name.".".$this->tb_pages
                . " WHERE exist=1";

            $res = mysql_query( $sel, $this->link );

            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            elseif(mysql_num_rows($res)>0)
            {
                $row = mysql_fetch_row($res);
                $total_rows = $row[0];
                $this->Model_GenerationMenu($page, $num_menu, $total_rows, $params);
            }
        }
    }

    //метод извлекает циклические ссылки для подробного отчета
    function ModelDetailReport_GetListCycleLinks( ServiceDetailReport & $detail_report )
    {
        $num_menu = intval($_REQUEST['page']);
        $this->per_page = $this->row_page;
        $params = '&list_cycle_links='.$_GET['list_cycle_links'];

        if (isset($_GET['navigate-page']))
        {
            $page=($_GET['navigate-page']-1);
        }
        else
        {
            $page=0;
        }

        $start=abs($page * $this->per_page);

        $sel = "SELECT t2.* FROM "
            . $detail_report->report_name . "." . $this->tb_pages ." AS t1 JOIN "
            . $detail_report->report_name . "." . $this->tb_links ." AS t2 "
            . "WHERE t1.id=t2.page_id AND t1.url=t2.href"." LIMIT ".$start.", ".$this->per_page;

        $res = mysql_query( $sel, $this->link );
        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        else
        {
            while($row = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $url = html_entity_decode($row['href'], ENT_QUOTES, 'UTF-8');
                $ar_components_link = parse_url($url);

                if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
                    $tmp_url = $url;
                }else{
                    if(strpos($ar_components_link['path'], '/') === 0){
                        $tmp_url = $this->protocol.'://'.$this->host_site.$url;
                    }else{
                        $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$url;
                    }
                }
                $detail_report->ar_cycle_links[] = array(
                    'link' => $tmp_url,
                    'href' => $url,
                    'anchor' => html_entity_decode($row['anchor'], ENT_QUOTES, 'UTF-8'),
                    'internal' => intval($row['internal_link'])
                );
            }

            $sel = "SELECT COUNT(*) FROM "
                . $detail_report->report_name . "." . $this->tb_pages ." AS t1 JOIN "
                . $detail_report->report_name . "." . $this->tb_links ." AS t2 "
                . "WHERE t1.id=t2.page_id AND t1.url=t2.href";

            $res = mysql_query( $sel, $this->link );

            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            elseif(mysql_num_rows($res)>0)
            {
                $row = mysql_fetch_row($res);
                $total_rows = $row[0];
                $this->Model_GenerationMenu($page, $num_menu, $total_rows, $params);
            }
        }
    }

    //метод извлекает таблицу загрузки ( глубина, ответ, скорость(кбит/с), размер(кб), редирект ) страниц для подробного отчета
    function ModelDetailReport_GetLoadTable( ServiceDetailReport & $detail_report )
    {
        $num_menu = intval($_REQUEST['page']);
        $params = '&p_show='.$_GET['p_show'];
        $this->per_page = $this->row_page;

        if (isset($_GET['navigate-page']))
        {
            $page=($_GET['navigate-page']-1);
        }
        else
        {
            $page=0;
        }

        $start=abs($page * $this->per_page);

        $sel = "SELECT * FROM " . $detail_report->report_name . "." . $this->tb_pages
            . " WHERE exist=1 AND content_type NOT LIKE '%image/jpeg%'"
            . " LIMIT ".$start.", ".$this->per_page;

        $res = mysql_query( $sel, $this->link );
        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        else
        {
            while($row = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $url = html_entity_decode($row['url'], ENT_QUOTES, 'UTF-8');
                $ar_components_link = parse_url($url);

                if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
                    $tmp_url = $url;
                }else{
                    if(strpos($ar_components_link['path'], '/') === 0){
                        $tmp_url = $this->protocol.'://'.$this->host_site.$url;
                    }else{
                        $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$url;
                    }
                }

                $detail_report->ar_load_table[] = array(
                    'link'         => $tmp_url,
                    'url'          => $url,
                    'level_links'  => intval($row['level_links']),
                    'http_code'    => intval($row['http_code']),
                    'page_speed'   => floatval($row['page_speed']),
                    'page_size'    => floatval($row['page_size']),
                    'redirect_url' => html_entity_decode($row['redirect_url'], ENT_QUOTES, 'UTF-8')
                );//получаем url просканированых страниц
            }

            $sel = "SELECT COUNT(id) FROM ".$detail_report->report_name.".".$this->tb_pages
                . " WHERE exist=1 AND content_type NOT LIKE '%image/jpeg%'";

            $res = mysql_query( $sel, $this->link );

            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            elseif(mysql_num_rows($res)>0)
            {
                $row = mysql_fetch_row($res);
                $total_rows = $row[0];
                $this->Model_GenerationMenu($page, $num_menu, $total_rows, $params);
            }
        }
    }

    //метод извлекает таблицу ресурсов (img, css, js, внешние ссылки) страниц для подробного отчета
    function ModelDetailReport_GetResourcesTable( ServiceDetailReport & $detail_report )
    {
        $num_menu = intval($_REQUEST['page']);
        $params = '&p_show='.$_GET['p_show'];
        $this->per_page = $this->row_page;

        if (isset($_GET['navigate-page']))
        {
            $page=($_GET['navigate-page']-1);
        }
        else
        {
            $page=0;
        }

        $start=abs($page * $this->per_page);

        $sel = "SELECT id, url FROM ".$detail_report->report_name.".".$this->tb_pages
            . " WHERE exist=1 AND content_type LIKE '%text/html%'"
            . " LIMIT ".$start.", ".$this->per_page;

        $res = mysql_query( $sel, $this->link );

        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        elseif(mysql_num_rows($res)>0)
        {
            while($row = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $url = html_entity_decode($row['url'], ENT_QUOTES, 'UTF-8');
                $ar_components_link = parse_url($url);

                if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
                    $tmp_url = $url;
                }else{
                    if(strpos($ar_components_link['path'], '/') === 0){
                        $tmp_url = $this->protocol.'://'.$this->host_site.$url;
                    }else{
                        $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$url;
                    }
                }

                $detail_report->ar_resources_table[$row['id']] = array(
                    'id'           => $row['id'],
                    'link'         => $tmp_url,
                    'url'          => $url,
                    'c_img'        => 0,
                    'c_i_link'     => 0
                );
            }

            //---------количество внешних ссылок на странице------------
            reset($detail_report->ar_resources_table);
            $ot = $detail_report->ar_resources_table[key($detail_report->ar_resources_table)];
            end($detail_report->ar_resources_table);
            $do = $detail_report->ar_resources_table[key($detail_report->ar_resources_table)];

            $sel = "SELECT t1.page_id, COUNT(t1.page_id) AS c_i_link FROM "
                . $detail_report->report_name . "." . $this->tb_links . " AS t1"
                . " WHERE t1.internal_link=0 AND t1.page_id BETWEEN ".$ot['id']." AND ".$do['id']
                . " GROUP BY t1.page_id";

            $res = mysql_query( $sel, $this->link );
            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            else
            {
                while($row = mysql_fetch_array($res, MYSQL_ASSOC))
                {
                    $detail_report->ar_resources_table[$row['page_id']]['c_i_link'] = $row['c_i_link'];
                }
            }

            //----------количество картинок на странице-----------
            $sel = "SELECT t1.page_id, COUNT(t1.page_id) AS c_img FROM "
                . $detail_report->report_name . "." . $this->tb_images . " AS t1"
                . " WHERE t1.page_id BETWEEN ".$ot['id']." AND ".$do['id']
                . " GROUP BY t1.page_id";

            $res = mysql_query( $sel, $this->link );
            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            else
            {
                while($row = mysql_fetch_array($res, MYSQL_ASSOC))
                {
                    $detail_report->ar_resources_table[$row['page_id']]['c_img'] = $row['c_img'];
                }
            }

            //-------------генерация менюшки---------
            $sel = "SELECT COUNT(id) FROM ".$detail_report->report_name.".".$this->tb_pages
                . " WHERE exist=1 AND content_type LIKE '%text/html%'";

            $res = mysql_query( $sel, $this->link );
            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            elseif(mysql_num_rows($res)>0)
            {
                $row = mysql_fetch_row($res);
                $total_rows = $row[0];
                $this->Model_GenerationMenu($page, $num_menu, $total_rows, $params);
            }
        }
    }

    function ModelDetailReport_GetResourcesImg( ServiceDetailReport & $detail_report, $resource)
    {
        $sel = "SELECT url FROM ".$detail_report->report_name.".".$this->tb_pages . " WHERE id=".$resource;
        $res = mysql_query( $sel, $this->link );
        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        elseif(mysql_num_rows($res)>0)
        {
            $row = mysql_fetch_array($res, MYSQL_ASSOC);
            $url = html_entity_decode($row['url'], ENT_QUOTES, 'UTF-8');
            $ar_components_link = parse_url($url);
            if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
                $tmp_url = $url;
            }else{
                if(strpos($ar_components_link['path'], '/') === 0){
                    $tmp_url = $this->protocol.'://'.$this->host_site.$url;
                }else{
                    $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$url;
                }
            }
            $detail_report->from_page = $tmp_url;

            $sel = "SELECT * FROM ".$detail_report->report_name.".".$this->tb_images . " WHERE page_id=".$resource;
            $res = mysql_query( $sel, $this->link );
            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            elseif(mysql_num_rows($res)>0)
            {
                while($row = mysql_fetch_array($res, MYSQL_ASSOC))
                {
                    $detail_report->ar_resources_img[] = array(
                        'src'              => html_entity_decode($row['src'], ENT_QUOTES, 'UTF-8'),
                        'title'            => html_entity_decode($row['title'], ENT_QUOTES, 'UTF-8'),
                        'count_word_title' => $row['count_word_title'],
                        'alt'              => html_entity_decode($row['alt'], ENT_QUOTES, 'UTF-8'),
                        'count_word_alt'   => $row['count_word_alt'],
                        'width'            => $row['width'],
                        'height'           => $row['height']
                    );
                }
            }
        }
    }

    function ModelDetailReport_GetResourcesExLink( ServiceDetailReport & $detail_report, $resource)
    {
        $sel = "SELECT url FROM ".$detail_report->report_name.".".$this->tb_pages . " WHERE id=".$resource;
        $res = mysql_query( $sel, $this->link );
        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        elseif(mysql_num_rows($res)>0)
        {
            $row = mysql_fetch_array($res, MYSQL_ASSOC);
            $url = html_entity_decode($row['url'], ENT_QUOTES, 'UTF-8');
            $ar_components_link = parse_url($url);
            if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
                $tmp_url = $url;
            }else{
                if(strpos($ar_components_link['path'], '/') === 0){
                    $tmp_url = $this->protocol.'://'.$this->host_site.$url;
                }else{
                    $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$url;
                }
            }
            $detail_report->from_page = $tmp_url;

            $sel = "SELECT * FROM ".$detail_report->report_name.".".$this->tb_links . " WHERE page_id=".$resource . " AND internal_link=0";
            $res = mysql_query( $sel, $this->link );
            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            elseif(mysql_num_rows($res)>0)
            {
                while($row = mysql_fetch_array($res, MYSQL_ASSOC))
                {
                    $detail_report->ar_resources_exlink[] = array(
                        'href'   => html_entity_decode($row['href'], ENT_QUOTES, 'UTF-8'),
                        'anchor' => html_entity_decode($row['anchor'], ENT_QUOTES, 'UTF-8')
                    );
                }
            }
        }
    }

    //метод извлекает данные о контенте (count_word & count_symbols) страниц для подробного отчета
    function ModelDetailReport_GetContentTable( ServiceDetailReport & $detail_report )
    {
        $num_menu = intval($_REQUEST['page']);
        $params = '&p_show='.$_GET['p_show'];
        $this->per_page = $this->row_page;

        if (isset($_GET['navigate-page']))
        {
            $page=($_GET['navigate-page']-1);
        }
        else
        {
            $page=0;
        }

        $start=abs($page * $this->per_page);

        //$sel = "SELECT t1.id, t1.url, t2.id AS c_id, t2.original_text, t2.text_words, t2.count_words, t2.count_symbols FROM "
        $sel = "SELECT t1.id, t1.url, t2.id AS c_id, t2.count_words, t2.count_symbols FROM "
            . $detail_report->report_name . "." . $this->tb_pages . " AS t1 JOIN "
            . $detail_report->report_name . "." . $this->tb_content . " AS t2"
            . " WHERE t1.exist=1 AND t1.http_code=200 AND t1.content_type LIKE '%text/html%' AND (t1.content IS NOT NULL OR t1.content!=0)"
            . " AND t1.content=t2.id"
            . " LIMIT ".$start.", ".$this->per_page;

        $res = mysql_query( $sel, $this->link );
        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        elseif(mysql_num_rows($res)>0)
        {
            while($row = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $url = html_entity_decode($row['url'], ENT_QUOTES, 'UTF-8');
                $ar_components_link = parse_url($url);

                if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
                    $tmp_url = $url;
                }else{
                    if(strpos($ar_components_link['path'], '/') === 0){
                        $tmp_url = $this->protocol.'://'.$this->host_site.$url;
                    }else{
                        $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$url;
                    }
                }

                $detail_report->ar_content_table[] = array(
                    'id'            => $row['id'],
                    'link'          => $tmp_url,
                    'url'           => $url,
                    'c_id'          => $row['c_id'],
                    //'original_text' => html_entity_decode($row['original_text'], ENT_QUOTES, 'UTF-8'),
                    //'text_words'    => unserialize(html_entity_decode($row['text_words'], ENT_QUOTES, 'UTF-8')),
                    'count_words'   => intval($row['count_words']),
                    'count_symbols' => intval($row['count_symbols'])
                );
            }

            $sel = "SELECT COUNT(t1.id) FROM ".$detail_report->report_name.".".$this->tb_pages. " AS t1"
                . " WHERE t1.exist=1 AND t1.http_code=200 AND t1.content_type LIKE '%text/html%' AND (t1.content IS NOT NULL OR t1.content!=0)";

            $res = mysql_query( $sel, $this->link );

            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            elseif(mysql_num_rows($res)>0)
            {
                $row = mysql_fetch_row($res);
                $total_rows = $row[0];
                $this->Model_GenerationMenu($page, $num_menu, $total_rows, $params);
            }

            $increment = 1;
            $multiplier = 1;
            $range = 1000;
            $ot = $increment;
            $do = $range;
            $ar_cs = array();// array(1=>(1...1000), 2=>(1001...2000), 3=>(2001...3000), ...)

            //------ 0-1 тыс. символов -------
            $sel = "SELECT COUNT(t2.id) AS cs".$multiplier." FROM "
                . $detail_report->report_name . "." . $this->tb_content . " AS t2"
                . " WHERE t2.count_symbols BETWEEN ".$ot." AND ".$do."";

            $res = mysql_query( $sel, $this->link );
            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            elseif(mysql_num_rows($res)>0)
            {
                $row = mysql_fetch_array($res, MYSQL_ASSOC);
                $ar_cs[$multiplier] = $row[('cs'.$multiplier)];
            }

            //------ 1-2 тыс. символов -------
            $ot = $do + $increment;
            $do = $range * (++$multiplier);

            $sel = "SELECT COUNT(t2.id) AS cs".$multiplier." FROM "
                . $detail_report->report_name . "." . $this->tb_content . " AS t2"
                . " WHERE t2.count_symbols BETWEEN ".$ot." AND ".$do."";

            $res = mysql_query( $sel, $this->link );
            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            elseif(mysql_num_rows($res)>0)
            {
                $row = mysql_fetch_array($res, MYSQL_ASSOC);
                $ar_cs[$multiplier] = $row[('cs'.$multiplier)];
            }

            //------ 2-3 тыс. символов -------
            $ot = $do + $increment;
            $do = $range * (++$multiplier);

            $sel = "SELECT COUNT(t2.id) AS cs".$multiplier." FROM "
                . $detail_report->report_name . "." . $this->tb_content . " AS t2"
                . " WHERE t2.count_symbols BETWEEN ".$ot." AND ".$do."";

            $res = mysql_query( $sel, $this->link );
            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            elseif(mysql_num_rows($res)>0)
            {
                $row = mysql_fetch_array($res, MYSQL_ASSOC);
                $ar_cs[$multiplier] = $row[('cs'.$multiplier)];
            }

            //------ 3-4 тыс. символов -------
            $ot = $do + $increment;
            $do = $range * (++$multiplier);

            $sel = "SELECT COUNT(t2.id) AS cs".$multiplier." FROM "
                . $detail_report->report_name . "." . $this->tb_content . " AS t2"
                . " WHERE t2.count_symbols BETWEEN ".$ot." AND ".$do."";

            $res = mysql_query( $sel, $this->link );
            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            elseif(mysql_num_rows($res)>0)
            {
                $row = mysql_fetch_array($res, MYSQL_ASSOC);
                $ar_cs[$multiplier] = $row[('cs'.$multiplier)];
            }

            //------ 4-5 тыс. символов -------
            $ot = $do + $increment;
            $do = $range * (++$multiplier);

            $sel = "SELECT COUNT(t2.id) AS cs".$multiplier." FROM "
                . $detail_report->report_name . "." . $this->tb_content . " AS t2"
                . " WHERE t2.count_symbols BETWEEN ".$ot." AND ".$do."";

            $res = mysql_query( $sel, $this->link );
            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            elseif(mysql_num_rows($res)>0)
            {
                $row = mysql_fetch_array($res, MYSQL_ASSOC);
                $ar_cs[$multiplier] = $row[('cs'.$multiplier)];
            }

            //------ 5-6 тыс. символов -------
            $ot = $do + $increment;
            $do = $range * (++$multiplier);

            $sel = "SELECT COUNT(t2.id) AS cs".$multiplier." FROM "
                . $detail_report->report_name . "." . $this->tb_content . " AS t2"
                . " WHERE t2.count_symbols BETWEEN ".$ot." AND ".$do."";

            $res = mysql_query( $sel, $this->link );
            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            elseif(mysql_num_rows($res)>0)
            {
                $row = mysql_fetch_array($res, MYSQL_ASSOC);
                $ar_cs[$multiplier] = $row[('cs'.$multiplier)];
            }

            //------ ...>6 тыс. символов ----
            $ot = $do + $increment;
            ++$multiplier;

            $sel = "SELECT COUNT(t2.id) AS cs".$multiplier." FROM "
                . $detail_report->report_name . "." . $this->tb_content . " AS t2"
                . " WHERE t2.count_symbols>".$ot;

            $res = mysql_query( $sel, $this->link );
            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            elseif(mysql_num_rows($res)>0)
            {
                $row = mysql_fetch_array($res, MYSQL_ASSOC);
                $ar_cs[$multiplier] = $row[('cs'.$multiplier)];
            }

            $detail_report->ar_content_cs = $ar_cs;
        }
    }

    //метод извлекает таблицу тегов (<h1>, <strong>, <b>) страниц для подробного отчета
    function ModelDetailReport_GetTagsTable( ServiceDetailReport & $detail_report )
    {
        $num_menu = intval($_REQUEST['page']);
        $params = '&p_show='.$_GET['p_show'];
        $this->per_page = $this->row_page;

        if (isset($_GET['navigate-page']))
        {
            $page=($_GET['navigate-page']-1);
        }
        else
        {
            $page=0;
        }

        $start=abs($page * $this->per_page);

        //узнаём сколько всего нужных страниц и вытаскиваем их id и url
        $sel = "SELECT id, url FROM ".$detail_report->report_name.".".$this->tb_pages
            . " WHERE exist=1 AND content_type LIKE '%text/html%'"
            . " LIMIT ".$start.", ".$this->per_page;

        $res = mysql_query( $sel, $this->link );

        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
        elseif(mysql_num_rows($res)>0)
        {
            while($row = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $url = html_entity_decode($row['url'], ENT_QUOTES, 'UTF-8');
                $ar_components_link = parse_url($url);

                if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
                    $tmp_url = $url;
                }else{
                    if(strpos($ar_components_link['path'], '/') === 0){
                        $tmp_url = $this->protocol.'://'.$this->host_site.$url;
                    }else{
                        $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$url;
                    }
                }

                $detail_report->ar_tags_table[$row['id']] = array(
                    'id'           => $row['id'],
                    'link'         => $tmp_url,
                    'url'          => $url
                );

                foreach($this->ar_tags as $tag)
                {
                    $detail_report->ar_tags_table[$row['id']][$tag]=0;
                }
            }

            //---------количество тегов на странице------------
            reset($detail_report->ar_tags_table);
            $ot = $detail_report->ar_tags_table[key($detail_report->ar_tags_table)];
            end($detail_report->ar_tags_table);
            $do = $detail_report->ar_tags_table[key($detail_report->ar_tags_table)];

            foreach($this->ar_tags as $tag)
            {
                $sel = "SELECT t1.page_id, COUNT(t1.page_id) AS ".$tag." FROM "
                    . $detail_report->report_name . ".".$tag." AS t1"
                    . " WHERE t1.page_id BETWEEN ".$ot['id']." AND ".$do['id']
                    . " GROUP BY t1.page_id";

                $res = mysql_query( $sel, $this->link );
                if(mysql_errno())
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
                elseif(mysql_num_rows($res)>0)
                {
                    while($row = mysql_fetch_array($res, MYSQL_ASSOC))
                    {
                        $detail_report->ar_tags_table[$row['page_id']][$tag] = $row[$tag];
                    }
                }
            }

            $detail_report->ar_tags = $this->ar_tags;

            //-------------генерация менюшки---------
            $sel = "SELECT COUNT(id) FROM ".$detail_report->report_name.".".$this->tb_pages
                . " WHERE exist=1 AND content_type LIKE '%text/html%'";

            $res = mysql_query( $sel, $this->link );
            if(mysql_errno())
            {
                if(SHOW_ECHO_ERROR)
                    Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                exit();
            }
            elseif(mysql_num_rows($res)>0)
            {
                $row = mysql_fetch_row($res);
                $total_rows = $row[0];
                $this->Model_GenerationMenu($page, $num_menu, $total_rows, $params);
            }
        }
    }

    function ModelDetailReport_GetTags( ServiceDetailReport & $detail_report, $page_id, $type )
    {
        foreach($this->ar_tags as $tag)
        {
            if($tag == $type)
            {
                $sel = "SELECT url FROM ".$detail_report->report_name.".".$this->tb_pages . " WHERE id=".$page_id;
                $res = mysql_query( $sel, $this->link );
                if(mysql_errno())
                {
                    if(SHOW_ECHO_ERROR)
                        Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                    exit();
                }
                elseif(mysql_num_rows($res)>0)
                {
                    $row = mysql_fetch_array($res, MYSQL_ASSOC);
                    $url = html_entity_decode($row['url'], ENT_QUOTES, 'UTF-8');
                    $ar_components_link = parse_url($url);
                    if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
                        $tmp_url = $url;
                    }else{
                        if(strpos($ar_components_link['path'], '/') === 0){
                            $tmp_url = $this->protocol.'://'.$this->host_site.$url;
                        }else{
                            $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$url;
                        }
                    }
                    $detail_report->from_page = $tmp_url;
                    $detail_report->name_current_tag = $type;

                    $fields = mysql_list_fields($detail_report->report_name, $type);
                    $detail_report->count_fields_tag = mysql_num_fields($fields);

                    for($i = 0; $i < $detail_report->count_fields_tag; $i++)
                    {
                        $detail_report->fields_tag[$i] = mysql_fetch_field($fields, $i);
                    }

                    $sel = "SELECT t1.* FROM "
                        . $detail_report->report_name . ".".$type." AS t1"
                        . " WHERE t1.page_id=".$page_id;

                    $res = mysql_query( $sel, $this->link );
                    if(mysql_errno())
                    {
                        if(SHOW_ECHO_ERROR)
                            Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
                        exit();
                    }
                    elseif(mysql_num_rows($res)>0)
                    {
                        while($row = mysql_fetch_array($res, MYSQL_ASSOC))
                        {
                            $obj_tag = new ServiceDetailReportTag();

                            for($i = 0; $i < count($row); $i++)
                            {
                                $field_name = $detail_report->fields_tag[$i]->name;
                                if(($detail_report->fields_tag[$i]->numeric && $detail_report->fields_tag[$i]->name == 'id') ||
                                    ($detail_report->fields_tag[$i]->numeric && $detail_report->fields_tag[$i]->name == 'page_id'))
                                {
                                    continue;
                                }
                                elseif($detail_report->fields_tag[$i]->numeric)
                                {
                                    $obj_tag->$field_name = intval($row[$field_name]);
                                }
                                else
                                {
                                    $obj_tag->$field_name = html_entity_decode($row[$field_name], ENT_QUOTES, 'UTF-8');
                                }
                            }

                            $detail_report->ar_current_tags[] = $obj_tag;
                        }
                    }
                    break;
                }
            }
        }
    }

    //генерирует меню для отчётов - ТРЕБУЕТ ЗАМЕНЫ ВСТРОЕННОГО МЕНЮ В МЕТОДАХ НА ДАННЫЙ МЕТОД !!!
    function Model_GenerationMenu($page, $num_menu, $total_rows, $params)
    {
        $num_pages = ceil($total_rows / $this->per_page);

        if($num_pages < $this->count_navigate_menu )
        {
            for($i=1;$i<=$num_pages;$i++)
            {
                if ($i-1 == $page)
                {
                    self::$ar_a[] = '<span class="activ"><strong>'.$i.'</strong></span>';
                }
                else
                {
                    self::$ar_a[] = '<a href="'.$_SERVER['PHP_SELF']
                        .'?page='.$num_menu
                        .'&report_name='.$_GET['report_name']
                        .$params
                        .'&host='.$_GET['host']
                        .'&navigate-page='.$i.'">'.$i.'</a>';
                }
            }
        }
        else
        {
            $count5 = false;
            if($num_pages >= 3 && $num_pages <= 5 ) $this->count_navigate_menu = 3;
            if($num_pages > 5 )
            {
                $this->count_navigate_menu = 5;
                $count5 = true;
            }
            //*----------------------------------------------------------------


            //    <<   [1]   2  [[3]]  4  [5]   >>   ....   8

            //$left_page           = 0;//                    <<
            //$start_menu          = 0;//первая ссылка       [1]
            //$cur_menu            = 0;//текущий елемент    [[3]]
            //$end_menu            = 0;//последняя ссылка    [5]
            //$right_page          = 0;//                    >>
            //$num_pages             //всего на страниц     8
            //$total_rows            //всего записей в БД   24
            //$this->per_page        //записей на странице  3
            //$this->count_navigate_menu = 5;//сколько ссылок в меню отображается в один ряд (3,5,7,9,11 - только нечетные числа, для красоты)

            $start_menu = 0;
            $end_menu   = $this->count_navigate_menu;
            $right_page = 0;

            if(($page+1) > $this->count_navigate_menu)
            {
                $start_menu = abs($this->count_navigate_menu-($page+1));
                $end_menu = $this->count_navigate_menu + abs($this->count_navigate_menu-($page+1));
            }

            /*if($page >= floor($this->count_navigate_menu / 2))
            {
                $start_menu = $page-floor($this->count_navigate_menu / 2);
                //$end_menu = $start_menu
            }*/

            //|<<
            if($count5 && $page > 1)
                self::$ar_a[] = '<a href="'.$_SERVER['PHP_SELF'].'?page='.$num_menu.'&report_name='.$_GET['report_name']
                    .$params
                    .'&host='.$_GET['host'].'&navigate-page=1">|&laquo;</a>';
            //<<
            if($page > 0)
                self::$ar_a[] = '<a href="'.$_SERVER['PHP_SELF'].'?page='.$num_menu.'&report_name='.$_GET['report_name']
                    .$params
                    .'&host='.$_GET['host'].'&navigate-page='.$page.'">&laquo;</a>';


            for( $i = $start_menu; $i < $end_menu; $i++ )
            {
                if ($i == $page)
                {
                    self::$ar_a[] = '<span class="activ"><strong>'.($i+1).'</strong></span>';
                    $right_page=$i+1;
                    $right_page = ($right_page == $num_pages) ? $num_pages : (++$right_page) ;
                }
                else
                {
                    self::$ar_a[] = '<a href="'.$_SERVER['PHP_SELF'].'?page='.$num_menu.'&report_name='.$_GET['report_name']
                        .$params
                        .'&host='.$_GET['host'].'&navigate-page='.($i+1).'">'.($i+1).'</a>';
                }
            }


            //>>
            if($page < $num_pages-1)
                self::$ar_a[] = '<a href="'.$_SERVER['PHP_SELF'].'?page='.$num_menu.'&report_name='.$_GET['report_name']
                    .$params
                    .'&host='.$_GET['host'].'&navigate-page='.$right_page.'">&raquo;</a>';

            if($end_menu < $num_pages)
            {
                //>>|
                if($count5 && $page < $num_pages-2)
                    self::$ar_a[] = '<a href="'.$_SERVER['PHP_SELF'].'?page='.$num_menu.'&report_name='.$_GET['report_name']
                        .$params
                        .'&host='.$_GET['host'].'&navigate-page='.$num_pages.'">&raquo;|</a>';
                //....
                self::$ar_a[] = '<span class=""><small>всего</small></span>';
                //8
                self::$ar_a[] = '<a href="'.$_SERVER['PHP_SELF'].'?page='.$num_menu.'&report_name='.$_GET['report_name']
                    .$params
                    .'&host='.$_GET['host'].'&navigate-page='.$num_pages.'">['.$num_pages.']</a>';
            }
        }
    }


    //=================================================================================================================
    //--------------------методы отправки отчёта на email--------------------------------------------------------------
    //=================================================================================================================

    //отправляет отчет на email
    function ModelSendReportToEmail()
    {
        Log::write( "Начало отправки отчета", $this->host_site );
        list($usec, $sec) = explode(" ", microtime());
        $time_start = ((float)$sec + (float)$usec);

        $this->ModelGetDetailReportForEmail( $this->tmp_data_base_report, $this->user_id_session );

        if(self::$detail_report !== null)
        {
            if($this->user_login == parent::$login && $this->user_password == parent::$password && SEND_EMAIL)
            {
                $this->ModelGetEmailsForSendLetters(self::$detail_report);

                //<<делаем рассылку-------------------------------
                foreach(self::$detail_report->ar_emails as $email)
                {
                    if($email->not_send)
                    {
                        continue;
                    }
                    $message = '';
                    $this->ModelPreSendEmailForFoundEmail( self::$detail_report, $message, $email->url, $email->email, $email );
                    $this->ModelSendEmail( $message, $email->email );
                    $this->ModelSaveSendEmail( $message, $email->email );
                    //отправляем копию на почту админа для архива (на почта указанную в строке email или
                    //если загрузка была с файла, тогда на почту которая была указана при регестрации)
                    //$this->ModelSendEmail( $message, $this->site_email_report );
                    //$this->ModelSaveSendEmail( $message, $this->site_email_report );
                }
                //>>end-------------------------------------------

                //ДЛЯ АДМИНА НУЖНО ПРИДУМАТЬ ДРУГОЙ ШАБЛОН ПИСЬМА-ОТЧЁТА !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                //отправляем отчёт на почту админа для архива (на почта указанную в строке email или
                //если загрузка была с файла, тогда на почту которая была указана при регестрации)
                //$message = '';
                //$this->ModelPreSendEmailForAdmin( self::$detail_report, $message, $email->url, $email->email, $email );
                //$this->ModelSendEmail( $message, $this->site_email_report );
                //$this->ModelSaveSendEmail( $message, $this->site_email_report );
            }
            else
            {
                //ДЛЯ ОБЫЧНОГО ПОЛЬЗОВАТЕЛЯ НУЖНО ПРИДУМАТЬ ДРУГОЙ ШАБЛОН ПИСЬМА-ОТЧЁТА !!!!!!!!!!!!!!!!!
                $message = '';
                $this->ModelPreSendEmailForUser( self::$detail_report, $message, '', $this->site_email_report );
                $this->ModelSendEmail( $message, $this->site_email_report );
                $this->ModelSaveSendEmail( $message, $this->site_email_report );
            }
        }

        list($usec, $sec) = explode(" ", microtime());
        $time_stop = ((float)$sec + (float)$usec);
        $int = ((int)$time_stop - (int)$time_start);
        $float = ($time_stop - $time_start);
        Log::write( "Конец отправки отчета", $this->host_site );
        Log::write( "Время отправки отчета".($int/60)."мин. ".$int." сек. ".$float." мсек.", $this->host_site );
    }

    //метод находит уникальные email's для рассылки отчета
    function ModelGetEmailsForSendLetters( ServiceDetailReport & $detail_report )
    {
        $sel = "SELECT t1.*, t2.url AS url FROM "
            . $detail_report->report_name . "." . $this->tb_email_address . " AS t1 JOIN "
            . $detail_report->report_name . "." . $this->tb_pages ." AS t2 "
            . "WHERE t1.".self::$service_field_email_not_send."=0 "
            . "AND t1.".self::$service_field_email_id_page."=t2.".self::$service_field_email_id;

        $res = mysql_query( $sel, $this->link );

        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            Log::write( "<p> ошибка ".mysql_error()." при извлечении данных из БД ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            Log::write( $sel, $this->host_site );
            exit();
        }
        else
        {
            while($row = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $email = new EmailPage();
                $email->id          = $row[self::$service_field_email_id];
                $email->email       = $row[self::$service_field_email_email];
                $email->hash        = $row[self::$service_field_email_hash];
                $email->not_send    = intval($row[self::$service_field_email_not_send]);
                $email->id_page     = $row[self::$service_field_email_id_page];
                $email->url         = $row[self::$service_field_email_url];
                $detail_report->ar_emails[] = $email;
            }
        }
    }
    //метод формирует "письмо-отчёт" перед его отправкой для найденного email
    function ModelPreSendEmailForFoundEmail( ServiceDetailReport & $detail_report, & $message, $email_url, $email_email, $email=null )
    {
        $message .= "<p>Доброго времени суток !</p>\r\n";
        $message .= "<p>Мы рады Вам представить новый сервис по анализу сайтов <a href=\"http://".SERVICE_HOST."\">".SERVICE_HOST."</a>.</p>\r\n";
        $message .= "<p>Вы получили данное письмо, так как Ваш email: &lt;".$email_email."&gt; пресутствует на странице данного сайта (".$email_url.").</p>\r\n";
        $message .= "<p>Нашим сервисом был проанализирован данный сайт: &lt;".$this->host_site."&gt;.</p>\r\n";
        $message .= "<p>Цель нашей работы:</p>\r\n";
        $message .= "<p><uo><li>Подсказать Вам на ошибки на сайте &lt;".$this->host_site."&gt;.</li><li>Помочь Вам исправить ошибки.</li></uo></p>\r\n";
        //<<определяем зону домена (ua)||(ru)
        $a = explode('.', $this->host_site);
        $c = count($a);
        $ext = $a[$c-1];
        //>>end
        $message .= "<p>Исправление ошибок на сайте, положительно влияет на сайт в глазах поисковых систем ".($ext=='ua' ? "Google и Yandex" : "Yandex и Google")."</p>\r\n";
        $message .= "<p>Отчёт о сканировании сайта: &lt;".$this->host_site."&gt; ".$detail_report->date_stop_report."</p>\r\n";
        $message .= "<p>Найдено ссылок = ".$detail_report->list_links."</p>\r\n";
        $message .= "<h3>Количество просканированных страниц &lt;".$this->host_site."&gt; :</h3>\r\n";
        $message .= "<p><uo><li>Взято для сканирования ".$this->limit_pages.".</li><li>Фактически просканировано ".$detail_report->list_pages.".</li></uo></p>\r\n";


        //формируем страницы с ошибками
        $message .= "<h3>404 ошибка</h3>\r\n";
        if(count($detail_report->ar_errors404))
        {
            //$message .= "<h3>404 ошибка</h3>\r\n";
            $message .= "<p>На вашем сайте обнаружены ссылки с ошибками:</p>\r\n";
            $count_404 = 0;

            foreach($detail_report->ar_errors404 as $key_error404 => $val_error404)
            {
                if($count_404>=5)break;

                $ar_components_link = parse_url($key_error404);

                if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
                    $tmp_url = $key_error404;
                }else{
                    if(strpos($ar_components_link['path'], '/') === 0){
                        $tmp_url = $this->protocol.'://'.$this->host_site.$key_error404;
                    }else{
                        $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$key_error404;
                    }
                }

                $message .= "\t<p>на странице <a href='".$tmp_url."' target='_blank'>".$tmp_url."</a> :</p>\r\n";
                $link_404 = 0;

                foreach($val_error404 as $ke404 => $ve404)
                {
                    if($link_404>=5)break;
                    $ar_components_link = parse_url($ve404);

                    if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
                        $t_url = $ve404;
                    }else{
                        if(strpos($ar_components_link['path'], '/') === 0){
                            $t_url = $this->protocol.'://'.$this->host_site.$ve404;
                        }else{
                            $t_url = $this->protocol.'://'.$this->host_site.'/'.$ve404;
                        }
                    }

                    $message .= "\t\t<p>- ссылка <a href='".$t_url."' target='_blank'>".$t_url."</a> ;</p>\r\n";
                    $link_404++;
                }
                $count_404++;
            }
        }
        else
        {
            $message .= "<p>На вашем сайте не обнаружены ссылки с 404-ошибкой.</p>\r\n";
        }

        $message .= "<h3>500 ошибка</h3>\r\n";
        if(count($detail_report->ar_errors500))
        {
            //$message .= "<h3>500 ошибка</h3>\r\n";
            $message .= "<p>На вашем сайте обнаружены ссылки с ошибками:</p>\r\n";
            $count_500 = 0;

            foreach($detail_report->ar_errors500 as $key_error500 => $val_error500)
            {
                if($count_500>=5)break;

                $ar_components_link = parse_url($key_error500);

                if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
                    $tmp_url = $key_error500;
                }else{
                    if(strpos($ar_components_link['path'], '/') === 0){
                        $tmp_url = $this->protocol.'://'.$this->host_site.$key_error500;
                    }else{
                        $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$key_error500;
                    }
                }

                $message .= "\t<p>на странице <a href='".$tmp_url."' target='_blank'>".$tmp_url."</a> :</p>\r\n";
                $link_500 = 0;

                foreach($val_error500 as $ke500 => $ve500)
                {
                    if($link_500>=5)break;
                    $ar_components_link = parse_url($ve500);

                    if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
                        $t_url = $ve500;
                    }else{
                        if(strpos($ar_components_link['path'], '/') === 0){
                            $t_url = $this->protocol.'://'.$this->host_site.$ve500;
                        }else{
                            $t_url = $this->protocol.'://'.$this->host_site.'/'.$ve500;
                        }
                    }

                    $message .= "\t\t<p>- ссылка <a href='".$t_url."' target='_blank'>".$t_url."</a> ;</p>\r\n";
                    $link_500++;
                }
                $count_500++;
            }
        }
        else
        {
            $message .= "<p>На вашем сайте не обнаружены ссылки с 505-ошибкой.</p>\r\n";
        }

        //формируем страницы с внешними ссылками
        $message .= "<h3>Страницы с внешними ссылками</h3>\r\n";
        if(count($detail_report->ar_pages_internal_links))
        {
            $message .= "<p>На вашем сайте обнаружены страницы с внешними ссылками:</p>\r\n";
            $count_internal_links=0;

            foreach($detail_report->ar_pages_internal_links as $key_internal_link => $val_internal_link)
            {
                if($count_internal_links>=5)break;

                $ar_components_link = parse_url($key_internal_link);

                if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
                    $tmp_url = $key_internal_link;
                }else{
                    if(strpos($ar_components_link['path'], '/') === 0){
                        $tmp_url = $this->protocol.'://'.$this->host_site.$key_internal_link;
                    }else{
                        $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$key_internal_link;
                    }
                }

                $message .= "\t<p>на странице <a href='".$tmp_url."' target='_blank'>".$tmp_url."</a> :</p>\r\n";
                $count_internal_link=0;

                foreach($val_internal_link as $k => $v)
                {
                    if($count_internal_link>=5)break;
                    $message .= "\t\t<p>- ссылка <a href='".$v['href']."' target='_blank'>".$v['href']."</a> ;</p>\r\n";
                    $count_internal_link++;
                }

                $count_internal_links++;
            }
        }
        else
        {
            $message .= "<p>На вашем сайте не обнаружены страницы с внешними ссылками.</p>\r\n";
        }

        //формируем страницы с отсутствующим title
        $message .= "<h3>Страницы без мета-тега \"title\"</h3>\r\n";
        if(count($detail_report->ar_pages_not_title))
        {
            $message .= "<p>На вашем сайте обнаружены страницы без мета-тега \"title\":</p>\r\n";
            $count_title=0;
            foreach($detail_report->ar_pages_not_title as $key_title => $val_title)
            {
                if($count_title>=5)break;
                $ar_components_link = parse_url($val_title['url']);

                if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
                    $tmp_url = $val_title['url'];
                }else{
                    if(strpos($ar_components_link['path'], '/') === 0){
                        $tmp_url = $this->protocol.'://'.$this->host_site.$val_title['url'];
                    }else{
                        $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$val_title['url'];
                    }
                }
                $message .= "\t<p>без \"title\" <a href='".$tmp_url."' target='_blank'>".$tmp_url."</a> ;</p>\r\n";
                $count_title++;
            }
        }
        else
        {
            $message .= "<p>На вашем сайте не обнаружены страницы без мета-тега \"title\".</p>\r\n";
        }

        //формируем страницы с отсутствующими keywords
        $message .= "<h3>Страницы без мета-тега \"keywords\"</h3>\r\n";
        if(count($detail_report->ar_pages_not_keywords))
        {
            $message .= "<h2>На вашем сайте обнаружены страницы без мета-тега \"keywords\":</h2>\r\n";
            $count_keywords=0;
            foreach($detail_report->ar_pages_not_keywords as $key_keywords => $val_keywords)
            {
                if($count_keywords>=5)break;
                $ar_components_link = parse_url($val_keywords['url']);

                if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
                    $tmp_url = $val_keywords['url'];
                }else{
                    if(strpos($ar_components_link['path'], '/') === 0){
                        $tmp_url = $this->protocol.'://'.$this->host_site.$val_keywords['url'];
                    }else{
                        $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$val_keywords['url'];
                    }
                }
                $message .= "\t<p>без \"keywords\" <a href='".$tmp_url."' target='_blank'>".$tmp_url."</a> ;</p>\r\n";
                $count_keywords++;
            }
        }
        else
        {
            $message .= "<p>На вашем сайте не обнаружены страницы без мета-тега \"keywords\".</p>\r\n";
        }

        //формируем страницы с отсутствующими description
        $message .= "<h3>Страницы без мета-тега \"description\"</h3>\r\n";
        if(count($detail_report->ar_pages_not_description))
        {
            $message .= "<h2>На вашем сайте обнаружены страницы без мета-тега \"description\":</h2>\r\n";
            $count_description=0;
            foreach($detail_report->ar_pages_not_description as $key_description => $val_description)
            {
                if($count_description>=5)break;
                $ar_components_link = parse_url($val_description['url']);

                if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
                    $tmp_url = $val_description['url'];
                }else{
                    if(strpos($ar_components_link['path'], '/') === 0){
                        $tmp_url = $this->protocol.'://'.$this->host_site.$val_description['url'];
                    }else{
                        $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$val_description['url'];
                    }
                }
                $message .= "\t<p>без \"description\" <a href='".$tmp_url."' target='_blank'>".$tmp_url."</a> ;</p>\r\n";
                $count_description++;
            }
        }
        else
        {
            $message .= "<p>На вашем сайте не обнаружены страницы без мета-тега \"description\".</p>\r\n";
        }

        $message .= "<p>Ещё 258 параметров про сайт &lt;".$this->host_site."&gt; найдёте <a href=\"http://".SERVICE_HOST."\">здесь</a>.</p>\r\n";
        if($email!==null)
        {
            $message .= "<hr />\r\n";
            $message .= "<p>Я не хочу получать подобные - <a href=\"http://".$_SERVER['SERVER_NAME']."/model/not_send.php?not_send=".$email->hash."\">письма</a></p>";
        }
    }
    //метод формирует "письмо-отчёт" перед его отправкой на почту админа для архива
    function ModelPreSendEmailForAdmin( ServiceDetailReport & $detail_report, & $message, $email_url, $email_email, $email=null )
    {
        $message .= "<p>Доброго времени суток !</p>\r\n";
        $message .= "<p>Мы рады Вам представить новый сервис по анализу сайтов <a href=\"http://".SERVICE_HOST."\">".SERVICE_HOST."</a>.</p>\r\n";
        $message .= "<p>Вы получили данное письмо, так как Ваш email: &lt;".$email_email."&gt; пресутствует на странице данного сайта (".$email_url.").</p>\r\n";
        $message .= "<p>Нашим сервисом был проанализирован данный сайт: &lt;".$this->host_site."&gt;.</p>\r\n";
        $message .= "<p>Цель нашей работы:</p>\r\n";
        $message .= "<p><uo><li>Подсказать Вам на ошибки на сайте &lt;".$this->host_site."&gt;.</li><li>Помочь Вам исправить ошибки.</li></uo></p>\r\n";
        //<<определяем зону домена (ua)||(ru)
        $a = explode('.', $this->host_site);
        $c = count($a);
        $ext = $a[$c-1];
        //>>end
        $message .= "<p>Исправление ошибок на сайте, положительно влияет на сайт в глазах поисковых систем ".($ext=='ua' ? "Google и Yandex" : "Yandex и Google")."</p>\r\n";
        $message .= "<p>Отчёт о сканировании сайта: &lt;".$this->host_site."&gt; ".$detail_report->date_stop_report."</p>\r\n";
        $message .= "<p>Найдено ссылок = ".$detail_report->list_links."</p>\r\n";
        $message .= "<h3>Количество просканированных страниц &lt;".$this->host_site."&gt; :</h3>\r\n";
        $message .= "<p><uo><li>Взято для сканирования ".$this->limit_pages.".</li><li>Фактически просканировано ".$detail_report->list_pages.".</li></uo></p>\r\n";


        //формируем страницы с ошибками
        $message .= "<h3>404 ошибка</h3>\r\n";
        if(count($detail_report->ar_errors404))
        {
            //$message .= "<h3>404 ошибка</h3>\r\n";
            $message .= "<p>На вашем сайте обнаружены ссылки с ошибками:</p>\r\n";
            $count_404 = 0;

            foreach($detail_report->ar_errors404 as $key_error404 => $val_error404)
            {
                if($count_404>=5)break;

                $ar_components_link = parse_url($key_error404);

                if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
                    $tmp_url = $key_error404;
                }else{
                    if(strpos($ar_components_link['path'], '/') === 0){
                        $tmp_url = $this->protocol.'://'.$this->host_site.$key_error404;
                    }else{
                        $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$key_error404;
                    }
                }

                $message .= "\t<p>на странице <a href='".$tmp_url."' target='_blank'>".$tmp_url."</a> :</p>\r\n";
                $link_404 = 0;

                foreach($val_error404 as $ke404 => $ve404)
                {
                    if($link_404>=5)break;
                    $ar_components_link = parse_url($ve404);

                    if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
                        $t_url = $ve404;
                    }else{
                        if(strpos($ar_components_link['path'], '/') === 0){
                            $t_url = $this->protocol.'://'.$this->host_site.$ve404;
                        }else{
                            $t_url = $this->protocol.'://'.$this->host_site.'/'.$ve404;
                        }
                    }

                    $message .= "\t\t<p>- ссылка <a href='".$t_url."' target='_blank'>".$t_url."</a> ;</p>\r\n";
                    $link_404++;
                }
                $count_404++;
            }
        }
        else
        {
            $message .= "<p>На вашем сайте не обнаружены ссылки с 404-ошибкой.</p>\r\n";
        }

        $message .= "<h3>500 ошибка</h3>\r\n";
        if(count($detail_report->ar_errors500))
        {
            //$message .= "<h3>500 ошибка</h3>\r\n";
            $message .= "<p>На вашем сайте обнаружены ссылки с ошибками:</p>\r\n";
            $count_500 = 0;

            foreach($detail_report->ar_errors500 as $key_error500 => $val_error500)
            {
                if($count_500>=5)break;

                $ar_components_link = parse_url($key_error500);

                if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
                    $tmp_url = $key_error500;
                }else{
                    if(strpos($ar_components_link['path'], '/') === 0){
                        $tmp_url = $this->protocol.'://'.$this->host_site.$key_error500;
                    }else{
                        $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$key_error500;
                    }
                }

                $message .= "\t<p>на странице <a href='".$tmp_url."' target='_blank'>".$tmp_url."</a> :</p>\r\n";
                $link_500 = 0;

                foreach($val_error500 as $ke500 => $ve500)
                {
                    if($link_500>=5)break;
                    $ar_components_link = parse_url($ve500);

                    if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
                        $t_url = $ve500;
                    }else{
                        if(strpos($ar_components_link['path'], '/') === 0){
                            $t_url = $this->protocol.'://'.$this->host_site.$ve500;
                        }else{
                            $t_url = $this->protocol.'://'.$this->host_site.'/'.$ve500;
                        }
                    }

                    $message .= "\t\t<p>- ссылка <a href='".$t_url."' target='_blank'>".$t_url."</a> ;</p>\r\n";
                    $link_500++;
                }
                $count_500++;
            }
        }
        else
        {
            $message .= "<p>На вашем сайте не обнаружены ссылки с 505-ошибкой.</p>\r\n";
        }

        //формируем страницы с внешними ссылками
        $message .= "<h3>Страницы с внешними ссылками</h3>\r\n";
        if(count($detail_report->ar_pages_internal_links))
        {
            $message .= "<p>На вашем сайте обнаружены страницы с внешними ссылками:</p>\r\n";
            $count_internal_links=0;

            foreach($detail_report->ar_pages_internal_links as $key_internal_link => $val_internal_link)
            {
                if($count_internal_links>=5)break;

                $ar_components_link = parse_url($key_internal_link);

                if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
                    $tmp_url = $key_internal_link;
                }else{
                    if(strpos($ar_components_link['path'], '/') === 0){
                        $tmp_url = $this->protocol.'://'.$this->host_site.$key_internal_link;
                    }else{
                        $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$key_internal_link;
                    }
                }

                $message .= "\t<p>на странице <a href='".$tmp_url."' target='_blank'>".$tmp_url."</a> :</p>\r\n";
                $count_internal_link=0;

                foreach($val_internal_link as $k => $v)
                {
                    if($count_internal_link>=5)break;
                    $message .= "\t\t<p>- ссылка <a href='".$v['href']."' target='_blank'>".$v['href']."</a> ;</p>\r\n";
                    $count_internal_link++;
                }

                $count_internal_links++;
            }
        }
        else
        {
            $message .= "<p>На вашем сайте не обнаружены страницы с внешними ссылками.</p>\r\n";
        }

        //формируем страницы с отсутствующим title
        $message .= "<h3>Страницы без мета-тега \"title\"</h3>\r\n";
        if(count($detail_report->ar_pages_not_title))
        {
            $message .= "<p>На вашем сайте обнаружены страницы без мета-тега \"title\":</p>\r\n";
            $count_title=0;
            foreach($detail_report->ar_pages_not_title as $key_title => $val_title)
            {
                if($count_title>=5)break;
                $ar_components_link = parse_url($val_title['url']);

                if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
                    $tmp_url = $val_title['url'];
                }else{
                    if(strpos($ar_components_link['path'], '/') === 0){
                        $tmp_url = $this->protocol.'://'.$this->host_site.$val_title['url'];
                    }else{
                        $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$val_title['url'];
                    }
                }
                $message .= "\t<p>без \"title\" <a href='".$tmp_url."' target='_blank'>".$tmp_url."</a> ;</p>\r\n";
                $count_title++;
            }
        }
        else
        {
            $message .= "<p>На вашем сайте не обнаружены страницы без мета-тега \"title\".</p>\r\n";
        }

        //формируем страницы с отсутствующими keywords
        $message .= "<h3>Страницы без мета-тега \"keywords\"</h3>\r\n";
        if(count($detail_report->ar_pages_not_keywords))
        {
            $message .= "<h2>На вашем сайте обнаружены страницы без мета-тега \"keywords\":</h2>\r\n";
            $count_keywords=0;
            foreach($detail_report->ar_pages_not_keywords as $key_keywords => $val_keywords)
            {
                if($count_keywords>=5)break;
                $ar_components_link = parse_url($val_keywords['url']);

                if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
                    $tmp_url = $val_keywords['url'];
                }else{
                    if(strpos($ar_components_link['path'], '/') === 0){
                        $tmp_url = $this->protocol.'://'.$this->host_site.$val_keywords['url'];
                    }else{
                        $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$val_keywords['url'];
                    }
                }
                $message .= "\t<p>без \"keywords\" <a href='".$tmp_url."' target='_blank'>".$tmp_url."</a> ;</p>\r\n";
                $count_keywords++;
            }
        }
        else
        {
            $message .= "<p>На вашем сайте не обнаружены страницы без мета-тега \"keywords\".</p>\r\n";
        }

        //формируем страницы с отсутствующими description
        $message .= "<h3>Страницы без мета-тега \"description\"</h3>\r\n";
        if(count($detail_report->ar_pages_not_description))
        {
            $message .= "<h2>На вашем сайте обнаружены страницы без мета-тега \"description\":</h2>\r\n";
            $count_description=0;
            foreach($detail_report->ar_pages_not_description as $key_description => $val_description)
            {
                if($count_description>=5)break;
                $ar_components_link = parse_url($val_description['url']);

                if( isset($ar_components_link['host']) && $ar_components_link['host'] != ''){
                    $tmp_url = $val_description['url'];
                }else{
                    if(strpos($ar_components_link['path'], '/') === 0){
                        $tmp_url = $this->protocol.'://'.$this->host_site.$val_description['url'];
                    }else{
                        $tmp_url = $this->protocol.'://'.$this->host_site.'/'.$val_description['url'];
                    }
                }
                $message .= "\t<p>без \"description\" <a href='".$tmp_url."' target='_blank'>".$tmp_url."</a> ;</p>\r\n";
                $count_description++;
            }
        }
        else
        {
            $message .= "<p>На вашем сайте не обнаружены страницы без мета-тега \"description\".</p>\r\n";
        }

        $message .= "<p>Ещё 258 параметров про сайт &lt;".$this->host_site."&gt; найдёте <a href=\"http://".SERVICE_HOST."\">здесь</a>.</p>\r\n";
        if($email!==null)
        {
            $message .= "<hr />\r\n";
            $message .= "<p>Я не хочу получать подобные - <a href=\"http://".$_SERVER['SERVER_NAME']."/model/not_send.php?not_send=".$email->hash."\">письма</a></p>";
        }
    }
    //метод формирует "письмо-отчёт" перед его отправкой на почту пользователя
    function ModelPreSendEmailForUser( ServiceDetailReport & $detail_report, & $message, $email_url, $email_email, $email=null )
    {
        $message .= "<p>Доброго времени суток !</p>\r\n";
        $message .= "<p>Ваш сайт: &lt;".$this->host_site."&gt; был просканирован.</p>\r\n";
        $message .= "<p>Подробности отчёта смотрите на <a href=\"http://".$_SERVER['SERVER_NAME']."\">сайте</a></p>";
    }
    //отписка от рассылки
    function ModelNotSendEmail( $hash )
    {
        $ar_hash = explode(" ",base64_decode( $hash ));
        $db      = base64_decode($ar_hash[0]);
        $id      = base64_decode($ar_hash[1]);
        $this->db_name = $db;
        if($this->ModelIsDataBase())
        {
            $upd = "update ".$this->db_name.".".$this->tb_email_address
                ." set ".self::$service_field_email_not_send."=1 where ".self::$service_field_email_id."=".$id;
            if(mysql_query( $upd, $this->link ))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    //отправляем письмо
    function ModelSendEmail( $message, $email_to )
    {
        $headers='';
        $theme = 'Отчёт об анализе сайта '.$this->host_site;

        //if(mail( $email_to, $theme, $message, $headers))
        if($this->smtpmail( $email_to, $theme, $message, $headers))
        {
            $file_path = $_SERVER['DOCUMENT_ROOT'].'/logs/report.txt';
            $text = "Ваш email: " . $email_to . "\r\n";
            $text .= $theme . "\r\n" . $message . "\r\n";
            $handle = fopen($file_path, "a");
            flock ($handle, LOCK_EX);
            fwrite ($handle, "======================".date("Y-m-d H:i:s",mktime())."==================\r\n");
            fwrite ($handle, $text);
            fwrite ($handle, "==============================================================\r\n\r\n");
            flock ($handle, LOCK_UN);
            fclose($handle);
        }
        else
        {
            $file_path = $_SERVER['DOCUMENT_ROOT'].'/logs/report.txt';
            $text = "Ваш email: " . $email_to . "\r\n";
            $text .= $theme . "\r\n" . $message . "\r\n";
            $handle = fopen($file_path, "a");
            flock ($handle, LOCK_EX);
            fwrite ($handle, "==========ПИСЬМО НЕ ОТПРАВЛЕНО !!!============".date("Y-m-d H:i:s",mktime())."==================\r\n");
            fwrite ($handle, $text);
            fwrite ($handle, "==============================================================\r\n\r\n");
            flock ($handle, LOCK_UN);
            fclose($handle);
        }
    }
    //сохраняем письмо для истории (кому + что + когда отправили)
    function ModelSaveSendEmail( $message, $email_to )
    {
        $ins = "insert into ".$this->tb_email_send_save." ("
            .self::$service_field_email_email.","
            .self::$service_field_email_message.","
            .self::$service_field_email_date_send
            .") value ('".addslashes(htmlentities($email_to , ENT_QUOTES, 'UTF-8' ))."',"
            ."'".addslashes(htmlentities($message , ENT_QUOTES, 'UTF-8' ))."',"
            ."'".date("Y-m-d H:i:s",mktime())."')";
        mysql_query( $ins, $this->link );
        if(mysql_errno())
        {
            if(SHOW_ECHO_ERROR)
                Log::write( "<p> ошибка ".mysql_errno()." ".mysql_error()." при работе с табл. ".$this->tb_email_send_save." ! файл: ".__FILE__."  стр. ".__LINE__."</p>", $this->host_site );
            exit();
        }
    }
    //отправка почты через smtp
    function smtpmail($mail_to, $subject, $message, $headers='', $message_type='text/html') {
        global $config;
        $SEND =   "Date: ".date("D, d M Y H:i:s") . "\r\n";
        $SEND .=   'Subject: =?'.$config['smtp_charset'].'?B?'.base64_encode($subject)."=?=\r\n";

        if ($headers) $SEND .= $headers."\r\n\r\n";
        else
        {
            $SEND .= "Reply-To: ".$config['smtp_username']."\r\n";
            $SEND .= "MIME-Version: 1.0\r\n";
            $SEND .= "Content-Type: ".$message_type."; charset=\"".$config['smtp_charset']."\"\r\n";
            $SEND .= "Content-Transfer-Encoding: 8bit\r\n";
            $SEND .= "From: \"".$config['smtp_from']."\" <".$config['smtp_username'].">\r\n";
            $SEND .= "To: $mail_to <$mail_to>\r\n";
            $SEND .= "X-Priority: 3\r\n\r\n";

            /*$SEND.="Date: ".date("D, j M Y G:i:s")." +0700\r\n";
            $SEND.="From: =?UTF-8?Q?".str_replace("+","_",str_replace("%","=",urlencode(''.$config['smtp_from'].'')))."?= <".$config['smtp_username'].">\r\n";
            $SEND.="X-Mailer: The Bat! (v3.99.3) Professional\r\n";
            $SEND.="Reply-To: =?UTF-8?Q?".str_replace("+","_",str_replace("%","=",urlencode(''.$config['smtp_from'].'')))."?= <".$config['smtp_username'].">\r\n";
            $SEND.="X-Priority: 3 (Normal)\r\n";
            $SEND.="Message-ID: <172562218.".date("YmjHis")."@".$config['smtp_searcher'].">\r\n";
            $SEND.="To: =?UTF-8?Q?".str_replace("+","_",str_replace("%","=",urlencode('')))."?= <$mail_to>\r\n";
            $SEND.="Subject: =?UTF-8?Q?".str_replace("+","_",str_replace("%","=",urlencode(''.$subject.'')))."?=\r\n";
            $SEND.="MIME-Version: 1.0\r\n";
            $SEND.="Content-Type: text/html; charset=UTF-8\r\n";
            $SEND.="Content-Transfer-Encoding: 8bit\r\n";*/
        }
        $SEND .=  $message."\r\n";
        if( !$socket = fsockopen($config['smtp_host'], $config['smtp_port'], $errno, $errstr, 30) ) {
            if ($config['smtp_debug']) Log::write( "$errno\n\r$errstr", self::$service_db_name );
            return false;
        }

        if (!$this->server_parse($socket, "220", __LINE__)) return false;

        fputs($socket, "HELO " . $config['smtp_host'] . "\r\n");
        if (!$this->server_parse($socket, "250", __LINE__)) {
            if ($config['smtp_debug']) Log::write( 'Не могу отправить HELO!', self::$service_db_name );
            fclose($socket);
            return false;
        }
        fputs($socket, "AUTH LOGIN\r\n");
        if (!$this->server_parse($socket, "334", __LINE__)) {
            if ($config['smtp_debug']) Log::write( 'Не могу найти ответ на запрос авторизаци.', self::$service_db_name );
            fclose($socket);
            return false;
        }
        fputs($socket, base64_encode($config['smtp_username']) . "\r\n");
        if (!$this->server_parse($socket, "334", __LINE__)) {
            if ($config['smtp_debug']) Log::write( 'Логин авторизации не был принят сервером!', self::$service_db_name );
            fclose($socket);
            return false;
        }
        fputs($socket, base64_encode($config['smtp_password']) . "\r\n");
        if (!$this->server_parse($socket, "235", __LINE__)) {
            if ($config['smtp_debug']) Log::write( 'Пароль не был принят сервером как верный! Ошибка авторизации!', self::$service_db_name );
            fclose($socket);
            return false;
        }
        fputs($socket, "MAIL FROM: <".$config['smtp_username'].">\r\n");
        if (!$this->server_parse($socket, "250", __LINE__)) {
            if ($config['smtp_debug']) Log::write( 'Не могу отправить комманду MAIL FROM: ', self::$service_db_name );
            fclose($socket);
            return false;
        }
        fputs($socket, "RCPT TO: <" . $mail_to . ">\r\n");

        if (!$this->server_parse($socket, "250", __LINE__)) {
            if ($config['smtp_debug']) Log::write( 'Не могу отправить комманду RCPT TO: ', self::$service_db_name );
            fclose($socket);
            return false;
        }
        fputs($socket, "DATA\r\n");

        if (!$this->server_parse($socket, "354", __LINE__)) {
            if ($config['smtp_debug']) Log::write( 'Не могу отправить комманду DATA', self::$service_db_name );
            fclose($socket);
            return false;
        }
        fputs($socket, $SEND."\r\n.\r\n");

        if (!$this->server_parse($socket, "250", __LINE__)) {
            if ($config['smtp_debug']) Log::write( 'Не смог отправить тело письма. Письмо не было отправленно!', self::$service_db_name );
            fclose($socket);
            return false;
        }
        fputs($socket, "QUIT\r\n");
        fclose($socket);
        return TRUE;
    }
    //работа с socket 'ами
    function server_parse($socket, $response, $line = __LINE__) {
        global $config;
        $server_response='';
        while (substr($server_response, 3, 1) != ' ') {
            if (!($server_response = fgets($socket, 256))) {
                if ($config['smtp_debug']) Log::write( "Проблемы с отправкой почты!\n\r$response\n\r$line", self::$service_db_name );
                return false;
            }
        }
        if (!(substr($server_response, 0, 3) == $response)) {
            if ($config['smtp_debug']) Log::write( "Проблемы с отправкой почты!\n\r$response\n\r$line", self::$service_db_name );
            return false;
        }
        return true;
    }
}
?>