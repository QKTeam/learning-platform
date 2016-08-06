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
      		$response['name']=urldecode($response['name']);
      		$response['content']=urldecode($response['content']);
      		$response['visibility']=(int)$response['visibility'];
			handle('0000'.json_encode($response));
		}
	}
	else 
		handle(ERROR_PERMISSION.'04');
}
if($action[1]=='list')
{
	$response=Course::list(getRequest('ownerId'),getRequest('name'));
	if($response==false)
	{
		handle(ERROR_SYSTEM.'04');
	}
	else 
	{

		/*
		    "cid": "3",
      "ownerId": "1",
      "createTime": "1469954109",
      "updateTime": "1469954109",
      "name": "first+class",
      "content": "example",
      "visibility": "0"
		*/
      foreach ($response as &$i) 
      {
      	$i['cid']=(int)$i['cid'];
      	$i['ownerId']=(int)$i['ownerId'];
      	$i['createTime']=(int)$i['createTime'];
      	$i['updateTime']=(int)$i['updateTime'];
      	$i['name']=urldecode($i['name']);
      	$i['content']=urldecode($i['content']);
      	$i['visibility']=(int)$i['visibility'];
      }
		handle('0000'.json_encode($response));
	}
}


if(!checkAuthority())
	handle(ERROR_PERMISSION.'01');
$nowUser=User::show(Site::getSessionUid());
$nowRoleId=$nowUser[0]['roleId'];

if ($action[1]=='new') 
{
	if($nowRoleId>2)
		handle(ERROR_PERMISSION.'01');

	$newCourse=new Course;
	$newCourse->ownerId=Site::getSessionUid();
	if( ($response=$newCourse->create(getRequest('name'),getRequest('content'))) !=0)
	{
		handle('0000{"cid":'.$response.'}');
	}
	else
	{
		handle(ERROR_SYSTEM.'04');
	}
}

if($action[1]=='renew')
{
	if($nowRoleId>2)
		handle(ERROR_PERMISSION.'01');
	$response=Course::modify(getRequest('cid'),getRequest('name'),getRequest('content'),getRequest('visibility'));
	if($response==false)
	{
		handle(ERROR_SYSTEM.'04');
	}
	else 
	{
		handle('0000');
	}
}
if($action[1]=='delete')
{
	if($nowRoleId>2)
		handle(ERROR_PERMISSION.'01');
	$response=Course::delete(getRequest('cid'));
	if($response==false)
	{
		handle(ERROR_SYSTEM.'04');
	}
	else 
	{
		handle('0000');
	}
}

handle(ERROR_INPUT.'04');

?>