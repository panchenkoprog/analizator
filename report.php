<?php
//phpinfo();
//exit;

// http://php.net//manual/ru/session.configuration.php
// определяет вероятность запуска функции сборщика мусора (gc, garbage collection)
// Вероятность рассчитывается как gc_probability/gc_divisor,
// т.е. 1/100 означает, что функция сборщика мусора (gc, garbage collection) запускается в одном случае из ста
// ini_set('session.gc_divisor', 100);  // по умолчанию в denwer'e == 1000, а в php.net пишут == 100, для тестирования можно сделать == 10 и посмотреть как удаляются сессии
// ini_set('session.gc_probability', 1);// по умолчанию == 1, определяет вероятность запуска функции сборщика мусора

// http://habrahabr.ru/post/28418/
//ВАЖНО !!! если закоментить - нужно так же закоментировать такую же строку в файле captcha/captcha.php & captcha/another.php & model/validation_data.php & model/analyze.php
//ini_set('session.save_path', $_SERVER['DOCUMENT_ROOT'] .'/sessions/');

// задает отсрочку времени в секундах, после которой данные сессии будут рассматриваться как "мусор" и потенциально будут удалены.
// Сбор мусора может произойти в течение старта сессии (в зависимости от значений session.gc_probability и session.gc_divisor).
// ini_set('session.gc_maxlifetime', 10);

//set_time_limit(2147483647);
//ini_set('memory_limit', '256M');//256M == (count Link() == 450000) || 128M == (count Link() == 200000)
//mb_internal_encoding("utf-8"); // меняем внутреннюю кодировку на UTF-8
header("Content-Type: text/html; charset=utf-8");

include_once('config/config.php');
include_once('logs/log.php');
include_once('controller/controller.php');
include_once('controller/controller_report.php');
include_once('service/service.php');
include_once('model/model.php');
include_once('view/view.php');
include_once('view/view_detail_report.php');


session_start();

if(isset($_SESSION['user_session']) && $_SESSION['user_session'] == 1)
{
    $controller = new ControllerReport();
    $controller->ControllerSession(1);
}
else
{
    $controller = new ControllerReport();
    $controller->ControllerNotSession(0);
}

session_write_close();

?>