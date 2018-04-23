<?php
/* Database connection settings */
$host = '172.20.10.11';
$user = 'usr';
$pass = 'password';
$db = 'accounts';
$mysqli = new mysqli($host,$user,$pass,$db) or die($mysqli->error);
?>
