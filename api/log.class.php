<?php

require_once('../connection/connection.php');
require_once('site.class.php');
require_once('user.class.php');
class Log
{
	public $lid;
	private $createTime;
	public $userId;
	private $pointId;
	private $courseId;
	private $status;
	
	public function create($pointId,$courseId,$status)
	{
		global $pdo;
		$tm=time();
		echo $this->userId;
		$sqlLog=$pdo->prepare('INSERT INTO `log` (`createTime`,`userId`,`pointId`,`courseId`,`status`) 
												VALUES (:createTime,:userId,:pointId,:courseId,:status);');
		$sqlLog->bindValue(':createTime',(int)$tm,PDO::PARAM_INT);
		$sqlLog->bindValue(':userId',(int)($this->userId),PDO::PARAM_INT);
		$sqlLog->bindValue(':pointId',(int)$pointId,PDO::PARAM_INT);
		$sqlLog->bindValue(':courseId',(int)$courseId,PDO::PARAM_INT);
		$sqlLog->bindValue(':status',(int)$status,PDO::PARAM_INT);

		$response=$sqlLog->execute();
		try
		{
			return (int)$pdo->lastInsertId();
		}
		catch(Exception $e)
		{
			return 0;
		}
	}

	public function show($lid)
	{
		if(checkAuthority())
		{
			$nowUser=User::show(Site::getSessionUid());
			$nowRoleId=$nowUser[0]['roleId'];
			$nowId=$nowUser[0]['uid'];
		}

		$flag=0;
		global $pdo;
		$sqlLog=$pdo->prepare('SELECT * FROM `log` WHERE `lid` = :lid;');
		$sqlLog->bindValue(':lid',(int)$lid,PDO::PARAM_INT);
		$sqlLog->execute();
		$response=$sqlLog->fetch(PDO::FETCH_ASSOC);

		if($nowRoleId==1)
			$flag=1;
		else if ($nowId==Course::findowner($response['courseId'])) 
			$flag=1;
		else 
			if($nowId=$response['userId'])
				$flag=1;
		
	
		if($flag!=1)
		{
			return false;
		}
		else
		{
			return $response;
		}
	}
	public function listData($userId,$pointId)
	{
		global $pdo;

		if(checkAuthority())
		{
			$nowUser=User::show(Site::getSessionUid());
			$nowRoleId=$nowUser[0]['roleId'];
			$nowId=$nowUser[0]['uid'];
		}
		if($userId==0)
			$userId="";
		if($pointId==0)
			$pointId="";

		
		if($nowRoleId==1)
		{
			
			$sqlLog=$pdo->prepare('SELECT * FROM `log` WHERE `userId` LIKE :userId AND `pointId` LIKE :pointId;');
			$sqlLog->bindValue(':userId','%'.urlencode($userId).'%',PDO::PARAM_STR);
			$sqlLog->bindValue(':pointId','%'.urlencode($pointId).'%',PDO::PARAM_STR);
			$sqlLog->execute();
			$response=$sqlLog->fetchall(PDO::FETCH_ASSOC);
			return $response;
		}
		else if($nowRoleId==2)
		{
			

			$sqlLog=$pdo->prepare('SELECT * FROM `log` WHERE `userId` LIKE :userId AND `pointId` LIKE :pointId;');
			$sqlLog->bindValue(':userId','%'.urlencode($userId).'%',PDO::PARAM_STR);
			$sqlLog->bindValue(':pointId','%'.urlencode($pointId).'%',PDO::PARAM_STR);
			$sqlLog->execute();
			$response=$sqlLog->fetchall(PDO::FETCH_ASSOC);
			$cnt=0;
			foreach ($response as &$i) 
			{
				$flag=0;
				if($i['userId']==$nowId)
					$flag=1;
				if(Course::findowner($i['courseId'])==$nowId)
					$flag=1;
				if($flag===0)
					array_splice($response, $cnt,1);
				$cnt++;
			}
			
			return $response;
		}
		else 
		{
			$sqlLog=$pdo->prepare('SELECT * FROM `log` WHERE `userId` LIKE :userId AND `pointId` LIKE :pointId AND `usedId`=:usedId;');
			$sqlLog->bindValue(':userId','%'.urlencode($userId).'%',PDO::PARAM_STR);
			$sqlLog->bindValue(':pointId','%'.urlencode($pointId).'%',PDO::PARAM_STR);
			$sqlLog->bindValue(':pointId',(int)($nowId).'%',PDO::PARAM_INT);
			$sqlLog->execute();
			$response=$sqlLog->fetchall(PDO::FETCH_ASSOC);
			return $response;
		}
		
	}

	
}
