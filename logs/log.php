<?php
class Log
{
    //Log::write('message', 'file');
    static function write($mess="", $name="main")
    {
        $file_path = $_SERVER['DOCUMENT_ROOT'].'/logs/site/'.$name.'.txt';
        $text = $mess."\r\n";
        $handle = fopen($file_path, "a");
        flock ($handle, LOCK_EX);
        fwrite ($handle, "================".$name."======".date("Y-m-d H:i:s",mktime())."==================\r\n");
        fwrite ($handle, $text);
        fwrite ($handle, "==============================================================\r\n\r\n");
        flock ($handle, LOCK_UN);
        fclose($handle);
    }

    static function writeSendAutoError($mess="", $file, $line, $name="send_auto")
    {
        $file_path = $_SERVER['DOCUMENT_ROOT'].'/logs/send_auto/'.$name.'.txt';
        $text = "[error ".date("Y-m-d H:i:s",mktime())."]  file:".$file."  line:".$line."  ".$mess."\r\n";
        $handle = fopen($file_path, "a");
        flock($handle, LOCK_EX);
        fwrite($handle, $text);
        flock($handle, LOCK_UN);
        fclose($handle);
    }

    static function writeProcessTurnError($mess="", $file, $line,  $name="turn_error")
    {
        $file_path = $_SERVER['DOCUMENT_ROOT'].'/logs/process/'.$name.'.txt';
        $text = "[error ".date("Y-m-d H:i:s",mktime())."]  file:".$file."  line:".$line."  ".$mess."\r\n";
        $handle = fopen($file_path, "a");
        flock ($handle, LOCK_EX);
        fwrite ($handle, $text);
        fwrite ($handle, $text);
        flock ($handle, LOCK_UN);
        fclose($handle);
    }

    static function writeProcessTestWrite($mess="", $file, $line,  $name="test_write")
    {
        $file_path = $_SERVER['DOCUMENT_ROOT'].'/logs/process/'.$name.'.txt';
        $text = "[test ".date("Y-m-d H:i:s",mktime())."]  file:".$file."  line:".$line."  ".$mess."\r\n";
        $handle = fopen($file_path, "a");
        flock ($handle, LOCK_EX);
        fwrite ($handle, $text);
        fwrite ($handle, $text);
        flock ($handle, LOCK_UN);
        fclose($handle);
    }
}
?>