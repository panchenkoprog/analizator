<?php
//ВАЖНО !!! если закоментить - нужно так же закоментировать такую же строку в файле index.php и captcha/another.php & model/analyze.php
//ini_set('session.save_path', $_SERVER['DOCUMENT_ROOT'] .'/sessions/');

include_once('../config/config.php');
include_once('../logs/log.php');
include_once('../service/service.php');
include_once('../model/model.php');

session_start();

$model = new Model();
//$this->ModelInit($str_url); // вызывается в ModelValidationUrlScanPage();
if($model->ModelValidationData())
{//нет ошибок

    if($model->ModelIsDataBase() && $model->ModelIsAnalizationSite())
    {
        $error = array('error' => 'empty', 'response' => '<p class="text-warning">сайт уже есть в базе !!! управление анализом <a href="/index.php?page=1">здесь</a>.</p>');
        echo json_encode( $error );
        //session_write_close();
    }
    elseif($model->ModelIsDataBase() && !$model->ModelIsAnalizationSite())
    {
        $error = array('error' => 'empty', 'response' => '<p class="text-warning">сайт уже анализируется !!! управление анализом <a href="/index.php?page=2&site_db_name='.$model->db_name.'">здесь</a>.</p>');
        echo json_encode( $error );
        //session_write_close();
    }
    else
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

        /*$_SESSION['robots']           = intval($_REQUEST['robots']);
        $_SESSION['analysis_type']    = intval($_REQUEST['analysis_type']);
        $_SESSION['limit_pages']      = intval($_REQUEST['limit_pages']);
        $_SESSION['analysis_timeout'] = intval($_REQUEST['analysis_timeout']);
        if($_REQUEST['timeout']!=''){
            $_SESSION['timeout'] = intval($_REQUEST['timeout']);
        }else{
            $_SESSION['timeout'] = null;
        }*/

        if(intval($_REQUEST['analysis_type']) == 1)
        {
            //делаем расчёт стоимости анализа страниц и перерасчёт баланса
            if($model->ModelReCalculationBalance())
            {
                //<< подготовка параметров для передачи скрипту------------------
                $url_root = 'http://' . $_SERVER['SERVER_NAME'] . '/model/analyze.php';//URL по которому должен перейти скрипт
                $data = array(
                    'url_root'         => $url_root,
                    'id'               => $_SESSION['id'],
                    'login'            => $_SESSION['login'],
                    'password'         => $_SESSION['password'],
                    //'email'            => $_SESSION['email'],
                    'url'              => $model->url_site, // $_REQUEST['url'],
                    'robots'           => $_REQUEST['robots'],
                    'email'            => $_REQUEST['email'],
                    'analysis_type'    => $_REQUEST['analysis_type'],
                    'limit_pages'      => $_REQUEST['limit_pages'],
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

                if(strtolower(substr(PHP_OS, 0, 3)) === 'win')
                {// для Windows OS
                    $pr = WIN_PHP_INTERPRETATOR;
                }
                else
                {// для Linux OS
                    $pr = LIN_PHP_INTERPRETATOR;
                }
                $pt = $_SERVER['DOCUMENT_ROOT'] . '/model/send.php';
                // нужно сделать запуском через командную строку - ф-я exec();
                exec(escapeshellarg($pr)." ".escapeshellarg($pt) . $params);

                $error = array('error' => 'empty', 'balance' => $_SESSION['balance'], 'response' => '<p class="text-warning">Результат о сканировании сайта смотрите <a href="/index.php?page=1">здесь</a>.</p>');
                echo json_encode( $error );
            }
            else
            {
                $error = array('error' => 'empty', 'balance' => $_SESSION['balance'], 'response' => '<p class="text-error">Недостаточно денег на балансе.</p>');
                echo json_encode( $error );
            }
        }
        else
        {
            //<< подготовка параметров для передачи скрипту------------------
            $url_root = 'http://' . $_SERVER['SERVER_NAME'] . '/model/analyze.php';//URL по которому должен перейти скрипт
            $data = array(
                'url_root'         => $url_root,
                'id'               => $_SESSION['id'],
                'login'            => $_SESSION['login'],
                'password'         => $_SESSION['password'],
                //'email'            => $_SESSION['email'],
                'url'              => $model->url_site, // $_REQUEST['url'],
                'robots'           => $_REQUEST['robots'],
                'email'            => $_REQUEST['email'],
                'analysis_type'    => $_REQUEST['analysis_type'],
                'limit_pages'      => $_REQUEST['limit_pages'],
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

            if(strtolower(substr(PHP_OS, 0, 3)) === 'win')
            {// для Windows OS
                $pr = WIN_PHP_INTERPRETATOR;
            }
            else
            {// для Linux OS
                $pr = LIN_PHP_INTERPRETATOR;
            }
            $pt = $_SERVER['DOCUMENT_ROOT'] . '/model/send.php';
            // нужно сделать запуском через командную строку - ф-я exec();
            exec(escapeshellarg($pr)." ".escapeshellarg($pt) . $params);

            $error = array('error' => 'empty', 'response' => '<p class="text-warning">Результат о сканировании сайта смотрите <a href="/index.php?page=1">здесь</a>.</p>');
            echo json_encode( $error );
        }
    }
}
else
{
    //есть ошибки
    if($model::GetErrors('url'))
    {
        $error = array('error' => '<p class="text-error">Ошибка связана с введённым URL - '.$model::GetErrors('url').'</p>');
    }
    else
    {
        $error = array('error' => '<p class="text-error">некоректные данные</p>');
    }
    echo json_encode( $error );
    session_write_close();
}
?>