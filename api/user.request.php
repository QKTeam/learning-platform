<?php

require_once('user.class.php');
if($action[1]=='signup')
{
	$currentuser=new User;
	$currentuser->init(getRequest('username'),sha1(getRequest('username').getRequest('password')),getRequest('email'),getRequest('phone'),getRequest('gender'),getRequest('studentId'),getRequest('roleId'));
	$reponse=$currentuser->create();
	if($reponse > 0)
		handle('0000{"cid":'.$response.'}');
	else if($reponse== 0)
		handle(ERROR_SYSTEM.'00');
	else 
		handle(ERROR_INPUT.'02{"errorMsg":"username used"}');
}
if($action[1]=='signin')
{
	$currentuser=new User;
	$currentuser->username=getRequest('username');
	$currentuser->password=getRequest('password');
	$response=$currentuser->login();
	if($reponse==-1)
	{
		handle(ERROR_INPUT.'{"errorMsg":"No this user."}');
	}
	else if($response==0)
	{
		handle(ERROR_INPUT.'{"errorMsg":"wrong password"}');
	}
	else 
	{
		SUCCESS(json_encode('"uid"='.$response.'',true));
	}
}
if(!checkAuthority()())
	handle(ERROR_PERMISSION.'01');
switch($action[1])
{
	case 'signout':
	break;
	
}

?>
