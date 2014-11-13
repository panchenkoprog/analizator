<?php
if(isset($_REQUEST['send']) && $_REQUEST['send'] == 'auto')
{
    set_time_limit(2147483647);
    include_once('../logs/log.php');

    $user_agent_chrome = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.153 Safari/537.36';
    $headers_chrome = array(
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*;q=0.8',
        'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4,uk;q=0.2',
        'Accept-Encoding: deflate',
        'Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.7'
    );
    $timeout     = 60;//сек. - ожидание ответа от сервера
    $count_error = 0;
    $error_limit = 10;
    $url_root    = 'http://' . $_SERVER['SERVER_NAME'] . '/model/' . $_REQUEST['file_path_analyze'];//URL по которому должен перейти cUrl

    if(file_exists($_REQUEST['file']))
    {
        $ar_new_file = file($_REQUEST['file']);

        while($line = current($ar_new_file))
        {
            $ar_tmp = str_getcsv($line, ';', '"');
            if(count($ar_tmp) == 2)//есть host & email
            {
                //<< подготовка параметров для передачи скрипту------------------
                $data = array(
                    'id'               => $_REQUEST['id'],
                    'login'            => $_REQUEST['login'],
                    'password'         => $_REQUEST['password'],
                    'url'              => $ar_tmp[0],//host
                    'robots'           => $_REQUEST['robots'],
                    'email'            => $ar_tmp[1],//email
                    'analysis_type'    => $_REQUEST['analysis_type'],
                    'limit_pages'      => $_REQUEST['limit_pages'],
                    'analysis_timeout' => $_REQUEST['analysis_timeout'],
                    'timeout'          => $_REQUEST['timeout']
                );

                //<< вызов и передача параметров скрипту, который делает анализ сайта-------------------
                $ch = curl_init( $url_root );
                curl_setopt ($ch, CURLOPT_USERAGENT, $user_agent_chrome);
                curl_setopt($ch, CURLOPT_HTTPHEADER,$headers_chrome);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);//Количество секунд ожидания при попытке соединения. Используйте 0 для бесконечного ожидания.
                curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);              //Максимально позволенное количество секунд для выполнения cURL-функций.
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $str = curl_exec($ch);
                if($str!==false && $str!=='')
                {
                    $obj = json_decode($str);
                    if(is_object($obj))
                    {
                        if($obj->errno_turn)
                        {
                            Log::writeSendAutoError( $ar_tmp[0]."   error:".$obj->errno_turn."   errno:".$obj->error_turn, __FILE__, __LINE__);
                        }
                    }
                    else
                    {
                        Log::writeSendAutoError( $ar_tmp[0]."   str:".$str, __FILE__, __LINE__);
                    }
                    next($ar_new_file);
                    curl_close($ch);
                }
                else
                {
                    Log::writeSendAutoError( $ar_tmp[0]."  errno:".curl_errno($ch)."  error:".curl_error($ch), __FILE__, __LINE__);
                    curl_close($ch);
                    next($ar_new_file);
                    //if(++$count_error >= $error_limit) break;
                }
                //>>end-----------------------
            }
            elseif(count($ar_tmp) == 1)//есть host & (email - по умолчанию)
            {
                //<< подготовка параметров для передачи скрипту------------------
                $data = array(
                    'id'               => $_REQUEST['id'],
                    'login'            => $_REQUEST['login'],
                    'password'         => $_REQUEST['password'],
                    'url'              => $ar_tmp[0],//host
                    'robots'           => $_REQUEST['robots'],
                    'email'            => $_REQUEST['email'],//email
                    'analysis_type'    => $_REQUEST['analysis_type'],
                    'limit_pages'      => $_REQUEST['limit_pages'],
                    'analysis_timeout' => $_REQUEST['analysis_timeout'],
                    'timeout'          => $_REQUEST['timeout']
                );

                //<< вызов и передача параметров скрипту, который делает анализ сайта-------------------
                $ch = curl_init( $url_root );
                curl_setopt ($ch, CURLOPT_USERAGENT, $user_agent_chrome);
                curl_setopt($ch, CURLOPT_HTTPHEADER,$headers_chrome);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);//Количество секунд ожидания при попытке соединения. Используйте 0 для бесконечного ожидания.
                curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);              //Максимально позволенное количество секунд для выполнения cURL-функций.
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $str = curl_exec($ch);
                if($str!==false && $str!=='')
                {
                    $obj = json_decode($str);
                    if(is_object($obj))
                    {
                        if($obj->errno_turn)
                        {
                            Log::writeSendAutoError( $ar_tmp[0]."   error:".$obj->errno_turn."   errno:".$obj->error_turn, __FILE__, __LINE__);
                        }
                    }
                    else
                    {
                        Log::writeSendAutoError( $ar_tmp[0]."   str:".$str, __FILE__, __LINE__);
                    }
                    next($ar_new_file);
                    curl_close($ch);
                }
                else
                {
                    Log::writeSendAutoError( $ar_tmp[0]."  errno:".curl_errno($ch)."  error:".curl_error($ch), __FILE__, __LINE__);
                    curl_close($ch);
                    next($ar_new_file);
                    //if(++$count_error >= $error_limit) break;
                }
                //>>end-----------------------
            }
        }//end foreach
        chmod($_REQUEST['file'], 0777);
        unlink($_REQUEST['file']);
    }
}
?>