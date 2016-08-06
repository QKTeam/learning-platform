<?php
require_once('user.class.php');
require_once('site.class.php');
require_once('discuss.class.php');
require_once('course.class.php');

if($action[1]=='show')
{
	$response=Discuss::show(getRequest('did'));

	if($response==false)
	{
		handle(ERROR_SYSTEM.'04');
	}
	else 
	{

		$response['did']=(int)$response['did'];
		$response['userId']=(int)$response['userId'];
		$response['createTime']=(int)$response['createTime'];
		$response['updateTime']=(int)$response['updateTime'];
		$response['title']=urldecode($response['title']);
		$response['content']=urldecode($response['content']);
		$response['courseId']=(int)$response['courseId'];
		$response['fatherId']=(int)$response['fatherId'];
		handle('0000'.json_encode($response));
	}
}

if($action[1]=='list')
{
	$response=Discuss::list(getRequest('courseId'));
	foreach ($response as &$i ) {
		$i['did']=(int)$i['did'];
		$i['userId']=(int)$i['userId'];
		$i['createTime']=(int)$i['createTime'];
		$i['updateTime']=(int)$i['updateTime'];
		$i['title']=urldecode($i['title']);
		$i['content']=urldecode($i['content']);
		$i['courseId']=(int)$i['courseId'];
		$i['fatherId']=(int)$i['fatherId'];
	}
	handle('0000'.json_encode($response));
}


if(!checkAuthority())
	handle(ERROR_PERMISSION.'01');
$nowUser=User::show(Site::getSessionUid());
$nowRoleId=$nowUser[0]['roleId'];

if ($action[1]=='new') 
{
	$newDiscuss=new Discuss;
	if( ($response=$newDiscuss->create(getRequest('title'),getRequest('content'),getRequest('courseId'),getRequest('fatherId'))) !=0)
	{
		handle('0000{"did":'.$response.'}');
	}
	else
	{
		handle(ERROR_SYSTEM.'04');
	}
}

if($action[1]=='renew')
{
	$response=Discuss::modify(getRequest('did'),getRequest('title'),getRequest('content'));
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

	$response=Discuss::delete(getRequest('pid'));
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