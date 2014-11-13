<?php
    function regex( $str, & $sub1, & $sub2 )
    {
        $res = '';//здесь будет записываться обработанная строка
        $ar_str = str_split($str);//расскладываем строку на елементы

        $pos1 = strpos($str, $sub1);//находим вхождение первой подстроки

        if($pos1 !== false)
        {
            $pos2 = strpos($str, $sub2, $pos1+strlen($sub1));//находим вхождение второй подстроки

            if($pos2 !== false)
            {
                $count = count($ar_str);
                //посимвольно проходим по всей строке
                for($i=0; $i < $count; $i++)
                {
                    if($i >= $pos1 && $i <= $pos2)//если символ находится в нужном нам диапазоне - пропускаем
                    {
                        continue;
                    }
                    else//если символ НЕнаходится в нужном нам диапазоне - записываем в новую строку
                    {
                        $res.=$ar_str[$i];
                    }
                }
            }
        }

        if($res)
        {
            $ar_str = null;
            $str = null;
            return regex($res, $sub1, $sub2);//рекурсия !!!!
        }
        else
            return $str;
    }

    $file_path = '1.html';//путь к файлу
    $str = file_get_contents($file_path);//получаем строку где производится поиск

    $sub1 = 'tppabs="';//начало искомой подстроки
    $sub2 = '"';    //конец искомой подстроки

    $res = regex($str, $sub1, $sub2);

    if($res)
    {
        $handle = fopen($file_path, "w+");
        flock ($handle, LOCK_EX);
        fwrite ($handle, $res);
        flock ($handle, LOCK_UN);
        fclose($handle);
        echo "<p>файл ".$file_path." изменен !</p>";
    }
    else
        echo "<p>файл ".$file_path." неизменен !</p>";
?>