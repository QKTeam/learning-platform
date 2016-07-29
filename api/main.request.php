<?php
require_once('globalSettings.conf.php');
require_once('../connection/connection.php');
require_once('publicFunctions.func.php');
@session_start();

$request = [];
$action = [];

function analyseAddress() {
	global $action, $request;
	$action = explode('/', explode('?', substr($_SERVER['REQUEST_URI'], 5))[0]);
	 
	$request = json_decode(file_get_contents('php://input', 'r'), true);
}

function sendRequest() {
	global $action;
	if(count($action) !== 2 || $action[0] === '' || $action[1] === '') {
		handle(ERROR_INPUT.'00'.'Request Error.');
	}
	$requestFile = $action[0].'.request.php';
	if(file_exists($requestFile)) include($requestFile);
	else handle(ERROR_INPUT.'01'.'Request Error.');
}
analyseAddress();
sendRequest();