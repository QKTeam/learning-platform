<?php

require_once('user.class.php');
require_once('site.class.php');
if($action[1]=='signup')
{
	$currentuser=new User;
	$currentuser->init(getRequest('username'),sha1(getRequest('username').getRequest('password')),getRequest('email'),getRequest('phone'),getRequest('gender'),getRequest('studentId'),getRequest('roleId'));
	$response=$currentuser->create();
	if($response > 0)
		handle('0000{"uid":'.$response.'}');
	else if($response== 0)
		handle(ERROR_SYSTEM.'00');
	else 
		handle(ERROR_INPUT.'02username used');
}

if($action[1]=='signin')
{
	$currentuser=new User;
	$currentuser->username=getRequest('username');
	$currentuser->password=getRequest('password');
	$response=$currentuser->login();
	if($response==-1)
	{
		handle(ERROR_INPUT.'00No this user.');
	}
	else if($response==0)
	{
		handle(ERROR_INPUT.'00wrong password');
	}
	else 
	{
		require_once('site.class.php');
		Site::writeInSession($response);
		handle('0000{"uid":'.$response.'}');
	}
}

if(!checkAuthority())
	handle(ERROR_PERMISSION.'01');

switch($action[1])
{
	case 'signout':
		Site::clearSession();
		handle('0000');
	break;

	case 'list':
		$uid=getRequest('uid');
		
		$nowUser=User::show(Site::getSessionUid());
		$nowRoleId=$nowUser[0]['roleId'];
		$response=User::list(getRequest('username'),getRequest('studentId'),getRequest('roleId'));

		if($nowRoleId==1||$nowRoleId==2)
		{
			if($response==false)
				handle('0000');
			handle('0000'.json_encode($response));
		}
		else 
		{
			foreach ($response as &$i) {
				unset($i['password']);
				unset($i['phone']);
				unset($i['studentId']);
			}
			handle('0000'.json_encode($response));
		}
		
	break;


}

?>
