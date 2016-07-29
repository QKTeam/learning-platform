<?php
date_default_timezone_set('PRC');
require_once('config.php');
try {
	$pdo = new PDO('mysql:host='.$conn_hostname.';dbname='.$conn_database, $conn_username, $conn_password);
	$pdo->exec('SET NAMES UTF8');
}
catch(Exception $e) {
	$res = array('code' => "0100", "errorMsg" => $e->getMessage());
	die(json_encode($res));
}
