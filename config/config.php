<?php
mb_internal_encoding("utf-8");

//ВАЖНО !!! если закоментить - нужно так же закоментировать такую же строку в файле index.php и captcha/captcha.php & model/validation_data.php & model/analyze.php
ini_set('session.save_path', $_SERVER['DOCUMENT_ROOT'] .'/sessions/');

define('prefix_tag_change', 'change_');
define('prefix_tag_obj', 'obj_');
define('postfix_tag_archive', '_archive');

define('SHOW_ECHO', false);
define('SHOW_ECHO_ERROR', true);
define('SHOW_ECHO_CREATE_DB', false);
define('SHOW_ECHO_ERROR_CREATE_DB', true);
define('SHOW_ECHO_CONNECT_DB', false);
define('SHOW_ECHO_ERROR_CONNECT_DB', true);

define('REDIRECT', false);//false - запрещаем редирект ! true - разрешаем редирект, но только внутренний (с хостом сайта) !
define('REDIRECT_COMPARE', true);//сравнение страниц на которые делается redirect
define('OPERATION_TIMEOUT', true);
define('OPERATION_TIMEOUT_REPEAT_SCAN', true);

define('TURN_RUN_LIMITER', 4);//количество запущеных процессов (постоянно присутствующих строк в таблице service_turn_run)
define('FORTNIGHT', 14); // сканирование раз в две недели
define('LIMIT_PAGES', 5);//ограничение на количество сканирования страниц сайта в бесплатном режиме
define('REPEAT_ANALYSIS_TIMEOUT_DB', 60);//таймаут обращения к БД (каждые 1 мин. == 60 сек.)

define('WIN_PHP_INTERPRETATOR', '/usr/local/php5/php.exe');//путь к интерпритатору PHP для Windows OS (на локальном компе)
define('LIN_PHP_INTERPRETATOR', '/usr/bin/php');//путь к интерпритатору PHP для Linux OS

define('SEND_EMAIL', false);//отправлять отчёт на почту

//<<для отправки почты--------
//для Denwer'a , что бы отсылались письма (с защитой ssl или tls) нужно установить расширения http://www.denwer.ru/packages/php5.html
//и в php.ini изменить настройки - раскоментировать строку (extension=php_openssl.dll)
//на Linux'e таких проблем незамечено !!!
$config['smtp_username'] = "analizator@stll.ru";//'spam@stll.ru';  //Смените на имя своего почтового ящика.
$config['smtp_port']     = '465';//'110';//'25'; // Порт работы. Не меняйте, если не уверены.
$config['smtp_host']     = 'ssl://smtp.yandex.ru';//ssl или tls - для яндекса !!!  'tls://smtp.yandex.ru';//'smtp.yandex.ru';//'mx1.thebest-on.com';  //сервер для отправки почты(для наших клиентов менять не требуется)
$config['smtp_password'] = 'DJtLtbTPy30MM';//'gt73dds3d';  //'123321';//Измените пароль
$config['smtp_debug']    = true;  //Если Вы хотите видеть сообщения ошибок, укажите true вместо false
$config['smtp_charset']  = 'UTF-8';   //кодировка сообщений. (или UTF-8, итд)
$config['smtp_from']     = 'stll.ru'; //Ваше имя - или имя Вашего сайта. Будет показывать при прочтении в поле "От кого"
//>>end-------------------------

//количество тегов в массиве - влияет на количество полей в таблице pages и pages_archive
//массив тегов, которые нужно проанализировать - true - анализируем | false - не анализируем
$MODEL_DEFINE_TAGS = array(
    'h1' => true,
    'h2' => true,
    'h3' => true,
    'h4' => true,
    'h5' => true,
    'h6' => true,
    'b' => true,
    'strong' => true
);

function captcha()
{
    $d1 = rand(1,20);
    $d2 = rand(1,20);

    if($d1 > $d2)
    {
        $_SESSION['key'] = $d1 . ' - ' . $d2 . ' = ';
        $_SESSION['code'] = (string)($d1 - $d2);
    }
    else
    {
        $_SESSION['key'] = $d1 . ' + ' . $d2 . ' = ';
        $_SESSION['code'] = (string)($d1 + $d2);
    }
    return $_SESSION['key'];
}