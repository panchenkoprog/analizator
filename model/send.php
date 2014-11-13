<?php
if(isset($_SERVER['argv']))
{
    $data = array();

    //<< получаем данные из командной строки и готовим их для отправки в сервис POST'ом
    foreach($_SERVER['argv'] as $k => $v)
    {
        if($k==0){continue;}

        $ar = explode('=', $v);
        if(count($ar) > 1)
        {
            $data[$ar[0]] = $ar[1];
        }
        elseif(count($ar) == 1)
        {
            $data[$ar[0]] = "";
        }
    }
    //>>end

    //<< вызов и передача параметров скрипту, который делает анализ сайта-------------------
    $user_agent_chrome = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.153 Safari/537.36';
    $headers_chrome = array(
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*;q=0.8',
        'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4,uk;q=0.2',
        'Accept-Encoding: deflate',
        'Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.7'
    );

    $ch = curl_init( $data['url_root'] );
    curl_setopt ($ch, CURLOPT_USERAGENT, $user_agent_chrome);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers_chrome);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);//Количество секунд ожидания при попытке соединения. Используйте 0 для бесконечного ожидания.
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);              //Максимально позволенное количество секунд для выполнения cURL-функций.
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_exec($ch);
    curl_close($ch);
    //>>end-----------------------
}
?>