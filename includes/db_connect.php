<?php
	$dbHost='localhost';// чаще всего это так, но иногда требуется прописать ip адрес базы данных
	$dbName='demo';// название вашей базы
	$dbUser='demo';// пользователь базы данных
	$dbPass='demo';// пароль пользователя
	$mySqli =  new mysqli($dbHost,$dbUser,$dbPass, $dbName);
    if($mySqli->connect_errno) {
       die('Could not connect: ' . $mySqli->connect_errno);
    }
?>