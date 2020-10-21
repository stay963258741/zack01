<?php

session_start();

unset($_SESSION['token']);
unset($_SESSION['info']);

$http = '//'.$_SERVER['HTTP_HOST'];

header('Location:'.$http.'/index.php');

?>