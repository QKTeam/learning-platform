<?php
require_once('user.class.php');
require_once('site.class.php');
require_once('course.class.php');

if($action[1]=='show')
{
	$nowUser=User::show(Site::getSessionUid());
	$nowRoleId=$nowUser[0]['roleId'];
	$cid=getRequest('cid');
	$response=Course::show($cid);
	if($nowRoleId==1||($nowRoleId==2 && $response['ownerId']==$nowUser))
		$flag=1;
	else 
		$flag=0;
	if($flag==1||$response['visibility']==true)
	{
		if($response==false)
		{
			handle(ERROR_SYSTEM.'04');
		}
		else 
		{
			if($response['visibility']==1)
				$response['visibility']=true;
			else 
				$response['visibility']=false;
			$response['cid']=(int)$response['cid'];
			$response['ownerId']=(int)$response['ownerId'];
			$response['createTime']=(int)$response['createTime'];
			$response['updateTime']=(int)$response['updateTime'];
			
			handle('0000'.json_encode($response));
		}
	}
	else 
		handle(ERROR_PERMISSION.'04');

}

if(!checkAuthority())
	handle(ERROR_PERMISSION.'01');


if ($action[1]=='new') 
{
	$newCourse=new Course;
	$newCourse->ownerId=Site::getSessionUid();
	if( $newCourse->create(getRequest('name'),getRequest('content')) ==true)
	{
		handle('0000');
	}
	else
	{
		handle(ERROR_SYSTEM.'04');
	}

}
?>