<?php
require_once('../connection/connection.php');
require_once('site.class.php');
require_once('user.class.php');

class Point
{
	public $pid;
	private $createTime;
	private $updateTime;
	private $importance;
	private $name;
	private $content;
	private $pointId;
	private $order;
	private $visibility;

	/*

CREATE  TABLE IF NOT EXISTS `learningPlatform`.`point` (
	`pid` INT NOT NULL AUTO_INCREMENT,
	`createTime` INT NOT NULL, -- 创建时间，秒级
	`updateTime` INT NOT NULL, -- 修改时间，秒级
	`importance` INT(1) NOT NULL DEFAULT 0, -- 重要性，[0-5]表示[0-5]个感叹号
	`name` VARCHAR(50) NOT NULL, -- 知识点名称
	`content` TEXT NOT NULL, -- 知识点内容
	`pointId` INT NOT NULL, -- 属于的课程id
	`order` INT NOT NULL DEFAULT 0, -- 排序顺序，`order`相同则按照`pid`排序
	`visibility` INT NOT NULL DEFAULT 0, -- 可见性，0隐藏，1可见，-1删除
	PRIMARY KEY (`pid`),
	UNIQUE INDEX `pid_UNIQUE` (`pid` ASC)
)ENGINE = InnoDB;
*/
	
	public function create($importance,$name,$content,$courseId)
	{
		global $pdo;
		$tm=time();
		
		$sqlPoint=$pdo->prepare('INSERT INTO `point` (`createTime`,`updateTime`,`importance`,`name`,`content`,`courseId`,`order`,`visibility`) 
												VALUES (:createTime,:updateTime,:importance,:name,:content,:courseId,0,0);');
		$sqlPoint->bindValue(':createTime',(int)$tm,PDO::PARAM_INT);
		$sqlPoint->bindValue(':updateTime',(int)$tm,PDO::PARAM_INT);
		$sqlPoint->bindValue(':importance',(int)$importance,PDO::PARAM_INT);
		$sqlPoint->bindValue(':name',urlencode($name),PDO::PARAM_STR);
		$sqlPoint->bindValue(':content',urlencode($content),PDO::PARAM_STR);
		$sqlPoint->bindValue(':courseId',(int)$courseId,PDO::PARAM_INT);
		$response=$sqlPoint->execute();
		try
		{
			return (int)$pdo->lastInsertId();
		}
		catch(Exception $e)
		{
			return 0;
		}
	}

	public function show($pid)
	{
		global $pdo;


		$sqlPoint=$pdo->prepare('SELECT * FROM `point` WHERE `pid` = :pid;');
		$sqlPoint->bindValue(':pid',(int)$pid,PDO::PARAM_INT);
		$sqlPoint->execute();
		$response=$sqlPoint->fetch(PDO::FETCH_ASSOC);
		if($response==false)
		{
			return false;
		}
		else
		{
			return $response;
		}
	}

	public function list($ownerId,$name)
	{
		global $pdo;

		if(checkAuthority())
		{
			$nowUser=User::show(Site::getSessionUid());
			$nowRoleId=$nowUser[0]['roleId'];
			$nowId=$nowUser[0]['uid'];
		}

		if($ownerId==0)
		{
			if($nowRoleId==1)
			{
				$sqlPoint=$pdo->prepare('SELECT * FROM `point` WHERE `name` LIKE :name AND `visibility` <> -1 ;');
				$sqlPoint->bindValue(':name','%'.urlencode($name).'%',PDO::PARAM_STR);
				$sqlPoint->execute();
				$response=$sqlPoint->fetchall(PDO::FETCH_ASSOC);
				return $response;
			}
			else if($nowRoleId==2)
			{
				$sqlPoint=$pdo->prepare('SELECT * FROM `point` WHERE `name` LIKE :name AND `ownerId` = :nowUser AND visibility = 0;');
				$sqlPoint->bindValue(':name','%'.urlencode($name).'%',PDO::PARAM_STR);
				$sqlPoint->bindValue(':nowUser',(int)($nowId),PDO::PARAM_INT);
				$sqlPoint->execute();
				$response1=$sqlPoint->fetchall(PDO::FETCH_ASSOC);

				$sqlPoint=$pdo->prepare('SELECT * FROM `point` WHERE `name` LIKE :name AND `visibility`= 1;');
				$sqlPoint->bindValue(':name','%'.urlencode($name).'%',PDO::PARAM_STR);
				$sqlPoint->execute();
				$response2=$sqlPoint->fetchall(PDO::FETCH_ASSOC);

				//var_dump($response1);
				//var_dump($response2);
				

				$response=array_merge($response1,$response2);

				return $response;
			}
			else 
			{
				$sqlPoint=$pdo->prepare('SELECT * FROM `point` WHERE `name` LIKE :name AND `visibility`= 1;');
				$sqlPoint->bindValue(':name','%'.urlencode($name).'%',PDO::PARAM_STR);
				$sqlPoint->execute();
				$response=$sqlPoint->fetchall(PDO::FETCH_ASSOC);
				return $response;	
			}
		}
	}

	public function modify($pid,$importance,$name,$content,$courseId,$order,$visibility)
	{
		

		if($visibility==true)
			$visibility=1;
		else
			$visibility=0;

		if(checkAuthority())
		{
			$nowUser=User::show(Site::getSessionUid());
			$nowRoleId=$nowUser[0]['roleId'];
			$nowId=$nowUser[0]['uid'];		
			if($nowRoleId==2&&($nowUser!=Course::findOwner($pid)))
				return false;
		}
		else 
			return false;

		global $pdo;
		

		if($nowRoleId==1)
		{
			$sqlPoint=$pdo->prepare('UPDATE `point` SET `updateTime`=:updateTime,`importance`=:importance, `name` = :name , `content` = :content,`courseId`=:courseId,`order`=:order, `visibility` = :visibility WHERE `pid` = :pid ;');
			$sqlPoint->bindValue(':updateTime',(int)time(),PDO::PARAM_INT);
			$sqlPoint->bindValue(':importance',(int)$importance,PDO::PARAM_INT);
			$sqlPoint->bindValue(':name',urlencode($name),PDO::PARAM_STR);
			$sqlPoint->bindValue(':content',urlencode($content),PDO::PARAM_STR);
			$sqlPoint->bindValue(':courseId',(int)$courseId,PDO::PARAM_INT);
			$sqlPoint->bindValue(':order',(int)$order,PDO::PARAM_INT);
			$sqlPoint->bindValue(':visibility',(int)($visibility),PDO::PARAM_INT);
			$sqlPoint->bindValue(':pid',(int)($pid),PDO::PARAM_INT);
			$response=$sqlPoint->execute();
		}
		else
			$response=false;

		if($response==false)
			return false;
		else 
			return true;
	}

	public function delete($pid)
	{

		if(checkAuthority())
		{
			$nowUser=User::show(Site::getSessionUid());
			$nowRoleId=$nowUser[0]['roleId'];
			$nowId=$nowUser[0]['uid'];
			if($nowRoleId==2&&($nowUser!=Course::findOwner($pid)))
				return false;
		}
		else 
			return false;
		global $pdo;

		if($nowRoleId==1)
		{
			$sqlPoint=$pdo->prepare('UPDATE `point` SET `visibility` = -1 WHERE `pid` = :pid ; ');
			$sqlPoint->bindValue(':pid',(int)($pid),PDO::PARAM_INT);
			$response=$sqlPoint->execute();
		}
		else
			$response=false;

		if($response==false)
			return false;
		else 
			return true;
	}
}


?>


