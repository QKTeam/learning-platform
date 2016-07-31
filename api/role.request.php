<?php
	require_once('role.class.php');
	require_once('user.class.php');
	require_once('site.class.php');
	
	if($action[1]=='list')
	{
		$response=Role::list(getRequest('ownerId'),getRequest('name'));
		if($response==false)
		{
			handle(ERROR_SYSTEM.'00');
		}
		else 
		{
			handle('0000'.json_encode($response));
		}
	}


	if($action[1]=='renew')
	{
		$nowUser=User::show(Site::getSessionUid());
		$nowRoleId=$nowUser[0]['roleId'];
		if(getRequest('rid')<=4&&getRequest('rid')>=1)
		{
				if($nowRoleId==1)
			{
				$response = Role::modify(getRequest('rid'),getRequest('name'));
				if($response==false)
				{
					handle(ERROR_SYSTEM.'03');
				}
				else 
				{
					handle('0000');
				}
			}
			else 
			{
				handle(ERROR_PERMISSION.'03');
			}
		}
		else 
		{
			handle(ERROR_INPUT.'03');
		}
}
?>
