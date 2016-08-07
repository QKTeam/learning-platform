<?php

require_once('user.class.php');
require_once('site.class.php');
require_once('role.class.php');

if($action[1]=='signup')
{
	$currentuser=new User;
	$currentuser->init(getRequest('username'),sha1(getRequest('username').getRequest('password')),getRequest('email'),getRequest('phone'),getRequest('gender'),getRequest('studentId'),4);
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
if($action[1]=='list')
{
	$nowUser=User::show(Site::getSessionUid());
	$nowRoleId=$nowUser[0]['roleId'];

	$response=User::list(getRequest('username'),getRequest('studentId'),getRequest('roleId'));

	if($nowRoleId==1||$nowRoleId==2)
	{
		foreach ($response as &$i) {
			$i['uid']=(int)$i['uid'];
			$i['username']=urldecode($i['username']);
			unset($i['password']);
			$i['email']=urldecode($i['email']);
			$i['phone']=urldecode($i['phone']);
			$i['gender']=(int)$i['gender'];
			$i['studentId']=urldecode($i['studentId']);
			$i['roleId']=(int)$i['roleId'];
			$i['roleName']=Role::find($i['roleId']);	
		}
		handle('0000'.json_encode($response));
	}
	else 
	{
		
		foreach ($response as &$i) {		
			$i['uid']=(int)$i['uid'];
			$i['username']=urldecode($i['username']);
			unset($i['password']);
			$i['email']=urldecode($i['email']);
			unset($i['phone']);
			$i['gender']=(int)$i['gender'];
			unset($i['studentId']);
			$i['roleId']=(int)$i['roleId'];
			$i['roleName']=Role::find($i['roleId']);
		}
		handle('0000'.json_encode($response));
	}
}

// if(!checkAuthority())
// 	handle(ERROR_PERMISSION.'01');

$nowUser=User::show(Site::getSessionUid());
$nowRoleId=$nowUser[0]['roleId'];


if($action[1]=='show')
{
	$response=User::show(getRequest('uid'));
	$result=$response[0];
	// var_dump($nowUser);
	// var_dump($result['uid']);
	if($nowRoleId==1||$nowRoleId==2||$nowUser[0]['uid']==$result['uid'])
	{
		$result['uid']=(int)$result['uid'];
		$result['username']=urldecode($result['username']);
		unset($result['password']);
		$result['email']=urldecode($result['email']);
		$result['phone']=urldecode($result['phone']);
		$result['gender']=(int)$result['gender'];
		$result['studentId']=urldecode($result['studentId']);
		$result['roleId']=(int)$result['roleId'];
		$result['roleName']=Role::find($result['roleId']);
		//var_dump($result);
		handle('0000'.json_encode($result));
	}
	else 
	{
		if($nowUser==$result['uid'])
		{
			$result['uid']=(int)$result['uid'];
			$result['username']=urldecode($result['username']);
			unset($result['password']);
			$result['email']=urldecode($result['email']);
			$result['phone']=urldecode($result['phone']);
			$result['gender']=(int)$result['gender'];
			$result['studentId']=urldecode($result['studentId']);
			$result['roleId']=(int)$result['roleId'];
			$result['roleName']=Role::find($result['roleId']);
			handle('0000'.json_encode($result));
		}
		else 
		{
			$result['uid']=(int)$result['uid'];
			$result['username']=urldecode($result['username']);
			unset($result['password']);
			$result['email']=urldecode($result['email']);
			unset($result['phone']);
			unset($result['gender']);
			unset($result['studentId']);
			$result['roleId']=(int)$result['roleId'];
			$result['roleName']=Role::find($result['roleId']);
			handle('0000'.json_encode($result));	
		}
	}
}
if($action[1]=='signout')
{		
	Site::clearSession();
	handle('0000');
}
else
{
	handle(ERROR_INPUT,'02');
}
?>
