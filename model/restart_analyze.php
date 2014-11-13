<?php
//ВАЖНО !!! если закоментить - нужно так же закоментировать такую же строку в файле index.php и captcha/another.php & model/analyze.php
//ini_set('session.save_path', $_SERVER['DOCUMENT_ROOT'] .'/sessions/');

include_once('../config/config.php');
include_once('../logs/log.php');
include_once('../service/service.php');
include_once('../model/model.php');

session_start();

if(isset($_REQUEST['site_name']) && $_REQUEST['site_name'] != '')
{
    $model = new Model();
    if(isset($_SESSION['id']))
    {
        $model->user_id_session = $_SESSION['id'];
        $model->ModelInit($_REQUEST['site_name']);
        $model->ModelInitProjectTools();

        if($model->analysis_type == 1)
        {//платный анализ

            //делаем расчёт стоимости анализа страниц и перерасчёт баланса
            if($model->ModelRepeatReCalculationBalance())
            {
                //<< подготовка параметров для передачи скрипту------------------
                $url_root = 'http://' . $_SERVER['SERVER_NAME'] . '/model/analyze.php';//URL по которому должен перейти скрипт
                $data = array(
                    'url_root'         => $url_root,
                    'id'               => $_SESSION['id'],
                    'login'            => $_SESSION['login'],
                    'password'         => $_SESSION['password'],
                    'email'            => $_SESSION['email'],
                    'url'              => $model->url_site
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

                $error = array('error' => 'empty');
                echo json_encode( $error );
            }
            else
            {
                $error = array('error' => 'error_' . $_REQUEST['error'], 'response' => 'Недостаточно денег на балансе.');
                echo json_encode( $error );
            }

        }
        else
        {//безплатный анализ
            //<< подготовка параметров для передачи скрипту------------------
            $url_root = 'http://' . $_SERVER['SERVER_NAME'] . '/model/analyze.php';//URL по которому должен перейти скрипт
            $data = array(
                'url_root'         => $url_root,
                'id'               => $_SESSION['id'],
                'login'            => $_SESSION['login'],
                'password'         => $_SESSION['password'],
                'email'            => $_SESSION['email'],
                'url'              => $model->url_site
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

            $error = array('error' => 'empty');
            echo json_encode( $error );
        }
    }
    else
    {
        //есть ошибки
        $error = array('error' => '<p class="text-error">некоректные данные</p>');
        echo json_encode( $error );
        session_write_close();
    }
}
else
{
    //есть ошибки
    $error = array('error' => '<p class="text-error">некоректные данные</p>');
    echo json_encode( $error );
    session_write_close();
}
?>