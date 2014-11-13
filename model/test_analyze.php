<?php
// данный скрипт будет работать если идёт повторный вызов из файла deleteupdate.js 1598 строка --- jQuery.post(file_analyze_php, obj);






/*//$pr = 'R:\usr\local\bin\php.exe';
//$pt = $_SERVER['DOCUMENT_ROOT'].'/model/test.php';

//exec(escapeshellarg($pr)." ".escapeshellarg($pt));


//работает!!!
//exec(escapeshellarg('C:\Program Files (x86)\Notepad++\notepad++').' '. escapeshellarg('R:\home\test59.stll.ru\www\logs\mail.txt') );

// 1) работает!!!
//exec(escapeshellarg('R:\usr\local\bin\php').' '. escapeshellarg('R:\home\test59.stll.ru\www\model\test.php') );

// http://otvety.google.ru/otvety/thread?tid=1533c0bd3de39f2f
// http://php.ru/manual/features.commandline.options.html
// http://php.net/manual/ru/features.commandline.usage.php

// 1) работает!!!
//exec(escapeshellarg('R:\usr\local\bin\php').' -f '. escapeshellarg('R:\home\test59.stll.ru\www\model\test.php') );

// 2) работает!!!
//exec(escapeshellarg('R:\usr\local\bin\php').' '. escapeshellarg('R:\home\test59.stll.ru\www\model\test.php') . " m1=1 m2=2" );

// php.ini
// safe_mode_exec_dir
// ignore_user_abort
// http://php.net/manual/ru/features.commandline.usage.php
// http://www.sql.ru/forum/709312/php-curl-ne-hochet-otkryvat-adres-s-parametrami-hotya-v-brauzere-vse-ok*/





// 1)
//exec(escapeshellarg('R:\usr\local\bin\php').' '. escapeshellarg('R:\home\test59.stll.ru\www\model\test.php') );

/*
    $file_path = 'R:\home\test59.stll.ru\www\logs\test.txt';
    $text = "ffffffffffffffff\r\n";
    $handle = fopen($file_path, "a");
    flock ($handle, LOCK_EX);
    fwrite ($handle, "================test.txt======".date("Y-m-d H:i:s",mktime())."==================\r\n");
    fwrite ($handle, $text);
    fwrite ($handle, "==============================================================\r\n\r\n");
    flock ($handle, LOCK_UN);
    fclose($handle);*/



//------------------------------------------------------------------------------------------------
//************************************************************************************************
//------------------------------------------------------------------------------------------------



// 2)
//exec(escapeshellarg('R:\usr\local\bin\php').' -f '. escapeshellarg('R:\home\test59.stll.ru\www\model\test.php') );

/*$file_path = 'R:\home\test59.stll.ru\www\logs\test.txt';
$text = "ffffffffffffffff\r\n";
$handle = fopen($file_path, "a");
flock ($handle, LOCK_EX);
fwrite ($handle, "================test.txt======".date("Y-m-d H:i:s",mktime())."==================\r\n");
if(isset($_SERVER['argv']))
{
    ob_start();
    var_dump($_SERVER['argv']);
    $str = ob_get_clean();
    fwrite ($handle, $str);
    //Log::write($_REQUEST['analysis_type'], "test.php");
}
//fwrite ($handle, $text);
fwrite ($handle, "==============================================================\r\n\r\n");
flock ($handle, LOCK_UN);
fclose($handle);*/


//------------------------------------------------------------------------------------------------
//************************************************************************************************
//------------------------------------------------------------------------------------------------


// 2)
//exec(escapeshellarg('R:\usr\local\bin\php').' '. escapeshellarg('R:\home\test59.stll.ru\www\model\test.php') . " m1=1 m2=2" );

/*$user_agent_chrome = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.153 Safari/537.36';
$headers_chrome = array(
    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*;q=0.8',
    'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4,uk;q=0.2',
    'Accept-Encoding: deflate',
    'Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.7'
);

if(count($_SERVER['argv']) > 1)
{
    $ch = curl_init( $_SERVER['argv'][1] );
    curl_setopt ($ch, CURLOPT_USERAGENT, $user_agent_chrome);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers_chrome);
    //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                      //TRUE для возврата результата передачи в качестве строки из curl_exec() вместо прямого вывода в браузер.
    //curl_setopt($ch, CURLOPT_FAILONERROR, true);                         //TRUE для тихого окончания работы, если полученный HTTP-код больше или равен 400. Поведение по умолчанию возвращает страницу как обычно, игнорируя код.
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);//Количество секунд ожидания при попытке соединения. Используйте 0 для бесконечного ожидания.
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);              //Максимально позволенное количество секунд для выполнения cURL-функций.

    //......код.....
}
else
{
    $file_path = 'R:\home\test59.stll.ru\www\logs\test.txt';
    $handle = fopen($file_path, "a");
    flock ($handle, LOCK_EX);
    fwrite ($handle, "================test.txt======".date("Y-m-d H:i:s",mktime())."==================\r\n");
    fwrite ($handle, 'error');
    fwrite ($handle, "==============================================================\r\n\r\n");
    flock ($handle, LOCK_UN);
    fclose($handle);
}*/



//------------------------------------------------------------------------------------------------
//************************************************************************************************
//------------------------------------------------------------------------------------------------







//ВАЖНО !!! если закоментить - нужно так же закоментировать такую же строку в файле index.php и captcha/another.php & model/validation_data.php
//ini_set('session.save_path', $_SERVER['DOCUMENT_ROOT'] .'/sessions/');

include_once('../config/config.php');
include_once('../logs/log.php');
include_once('../service/service.php');
include_once('../model/model.php');

session_start();

$model = new Model();
//$model->ModelInit($url); // вызывается в ModelValidationUrlScanPage();
if($model->ModelValidationData())
{
    // 1 - вариант !!!
    /*//<< подготовка параметров для передачи скрипту------------------
    $url = 'http://' . $_SERVER['SERVER_NAME'] . '/model/analyze.php';
    $data = array(
        'id'               => $_SESSION['id'],
        'login'            => $_SESSION['login'],
        'password'         => $_SESSION['password'],
        'email'            => $_SESSION['email'],
        'url'              => $_REQUEST['url'],
        'robots'           => $_REQUEST['robots'],
        'analysis_type'    => $_REQUEST['analysis_type'],
        'limit'            => $_REQUEST['limit'],
        'analysis_timeout' => $_REQUEST['analysis_timeout'],
        'timeout'          => $_REQUEST['timeout']
    );
    //>>end-----------------------

    // http://php.net/manual/ru/ref.exec.php
    //Внимание! Открытые файлы с блокировкой (особенно открытые сессии) должны быть закрыты до выполнения программы в фоне.
    session_write_close();//обязательный вызов, если использовать exec();

    // нужно сделать запуском через командную строку - ф-я exec();
    //<< вызов и передача параметров скрипту, который делает анализ сайта-------------------
    $user_agent_chrome = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.153 Safari/537.36';
    $headers_chrome = array(
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*;q=0.8',
        'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4,uk;q=0.2',
        'Accept-Encoding: deflate',
        'Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.7'
    );

    $ch = curl_init( $url );
    curl_setopt ($ch, CURLOPT_USERAGENT, $user_agent_chrome);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers_chrome);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);//Количество секунд ожидания при попытке соединения. Используйте 0 для бесконечного ожидания.
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);              //Максимально позволенное количество секунд для выполнения cURL-функций.
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_exec($ch);
    curl_close($ch);
    //>>end-----------------------*/

    // 2 - вариант !!!
    //<< подготовка параметров для передачи скрипту------------------
    $url_root = 'http://' . $_SERVER['SERVER_NAME'] . '/model/analyze.php';//URL по которому должен перейти скрипт
    $data = array(
        'url_root'         => $url_root,
        'id'               => $_SESSION['id'],
        'login'            => $_SESSION['login'],
        'password'         => $_SESSION['password'],
        'email'            => $_SESSION['email'],
        'url'              => $model->url_site, // $_REQUEST['url'],
        'robots'           => $_REQUEST['robots'],
        'analysis_type'    => $_REQUEST['analysis_type'],
        'limit'            => $_REQUEST['limit'],
        'analysis_timeout' => $_REQUEST['analysis_timeout'],
        'timeout'          => $_REQUEST['timeout']
    );

    $params = '';
    foreach($data as $k => $v)
    {
        $params .= ' ' . $k . '=' . $v;
    }
    //>>end-----------------------

    // http://php.net/manual/ru/ref.exec.php
    //Внимание! Открытые файлы с блокировкой (особенно открытые сессии) должны быть закрыты до выполнения программы в фоне.
    session_write_close();//обязательный вызов, если использовать exec();

    // нужно сделать запуском через командную строку - ф-я exec();
    $pr = 'R:\usr\local\bin\php.exe';
    $pt = $_SERVER['DOCUMENT_ROOT'] . '/model/send.php';

    exec(escapeshellarg($pr)." ".escapeshellarg($pt) . $params);

}
?>