<?php
include_once('../config/config.php');
session_start();
$key = array('key' => captcha());
echo json_encode($key);
?>