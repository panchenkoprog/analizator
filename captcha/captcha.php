<?php
//ВАЖНО !!! если закоментить - нужно так же закоментировать такую же строку в файле index.php и captcha/another.php & model/validation_data.php & model/analyze.php
//ini_set('session.save_path', $_SERVER['DOCUMENT_ROOT'] .'/sessions/');

include_once('../config/config.php');

session_start();

$im = imagecreate(150,50);//создали холст

$yellow = imagecolorallocate($im, 51,204,153);   //залили холст желтым цветом
$color = imagecolorallocate($im, 0,0,0);        //создали перо которым рисуем зашумление
$grey = imagecolorallocate($im, 128,128,128);   //создали перо для рисования линий
$str_code = "";                                 //строка-код
$black = imagecolorallocate($im, rand(0, 200), 0, rand(0, 200)); //случайный цвет для текста

function writeline($im, $grey)
{
    for($i=0; $i<20; $i++)
    {
        $x1 = rand(0,149);
        $y1 = rand(0,49);
        $x2 = rand(0,149);
        $y2 = rand(0,49);
        imageline($im, $x1, $y1, $x2, $y2, $grey);
    }
}

function noise($im, $black)//ф-я создаёт зашумление
{
    $w = imagesx($im);
    $h = imagesy($im);
    $per = 5;
    $nd = round($w*$h*$per/50);
    
    for($i = 0; $i<$nd; $i++)
    {
        $x = rand(0,$w);
        $y = rand(0,$h);
        imagesetpixel($im, $x,$y,$black);
    }
}

function generate_code() 
{    
    $chars = 'abcdefhiknprstvxyz23456789'; // Задаем символы, используемые в капче. Разделитель использовать не надо.
    $length = rand(4, 7); // Задаем длину капчи, в нашем случае - от 4 до 7
    $numChars = strlen($chars); // Узнаем, сколько у нас задано символов
    $str = '';
    for ($i = 0; $i < $length; $i++) {
    $str .= substr($chars, rand(0, $numChars)-1, 1);
    }
    // Перемешиваю
    $array_mix = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);
    shuffle ($array_mix);
    return $array_mix;
}
        
function writestring($im, $code, $color)
{
    $x = rand(0, 25);
    for($i = 0; $i < count($code); $i++)
    {
        $x+=15;
        imagettftext ($im, 20, 0, $x, rand(20, 30), $color, "comicbd.ttf", $code[$i]);
    }
}

//получаю масив букв
$str_code = generate_code();
//записываю строку в $_SESSION['code'] + $_SESSION['key'] - случайное название картинки для формирования url 
$_SESSION['code'] = implode("", $str_code);

//накладываю на задний фон полосы
writeline($im,$grey);
//накладываю строку на полосы
writestring($im,$str_code,$color);
//накладываю на буквы зашумлённость
noise($im,$black);

header("Content-type: image/jpeg");
//на ubuntu была проблема - неотображались картинки, а на denwer'e - всё нормально
//решением проблемы стало - обновление GD-библиотеки из GD Version 2.0 на GD Version bundled (2.0.34 compatible)
//http://php.net/manual/ru/function.imagejpeg.php - примечание
//http://php.net//manual/ru/function.imagegif.php - примечание
imagejpeg($im);
imagedestroy($im);
?>