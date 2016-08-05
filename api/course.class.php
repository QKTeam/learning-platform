<?php
require_once('../connection/connection.php');
require_once('site.class.php');
require_once('user.class.php');
class Course
{
	public $cid;
	public $ownerId;
	private $createTime;
	private $updateTime;
	private $name;
	private $content;
	private $visibility;
	
	public function create($name,$content)
	{
		global $pdo;
		$tm=time();
		
		$sqlCourse=$pdo->prepare('INSERT INTO `course` (`ownerId`,`createTime`,`updateTime`,`name`,`content`,`visibility`) 
												VALUES (:ownerId,:createTime,:updateTime,:name,:content,0);');
		$sqlCourse->bindValue(':ownerId',(int)$this->ownerId,PDO::PARAM_INT);
		$sqlCourse->bindValue(':createTime',(int)$tm,PDO::PARAM_INT);
		$sqlCourse->bindValue(':updateTime',(int)$tm,PDO::PARAM_INT);
		$sqlCourse->bindValue(':name',urlencode($name),PDO::PARAM_STR);
		$sqlCourse->bindValue(':content',urlencode($content),PDO::PARAM_STR);
		$response=$sqlCourse->execute();
		
		try
		{
			return (int)$pdo->lastInsertId();
		}
		catch(Exception $e)
		{
			return 0;
		}
	}
	public function findOwner($courseId)
	{
		global $pdo;
		echo $courseId;
		$sqlCourse=$pdo->prepare('SELECT `ownerId` FROM `course` WHERE `cid` = :courseId;');
		$sqlCourse->bindValue(':courseId',(int)($courseId),PDO::PARAM_INT);
		$sqlCourse->execute();
		$response=$sqlCourse->fetch(PDO::FETCH_ASSOC);
		if($response==false)
			return false;
		else 
			return (int)$response['ownerId'];
	}
	public function show($cid)
	{
		global $pdo;
		$sqlCourse=$pdo->prepare('SELECT * FROM `course` WHERE `cid` = :cid;');
		$sqlCourse->bindValue(':cid',(int)$cid,PDO::PARAM_INT);
		$sqlCourse->execute();
		$response=$sqlCourse->fetch(PDO::FETCH_ASSOC);
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
				$sqlCourse=$pdo->prepare('SELECT * FROM `course` WHERE `name` LIKE :name AND `visibility` <> -1;');
				$sqlCourse->bindValue(':name','%'.urlencode($name).'%',PDO::PARAM_STR);
				$sqlCourse->execute();
				$response=$sqlCourse->fetchall(PDO::FETCH_ASSOC);

				if($response==false)
					return false;
				else
					return $response;
			}
			else if($nowRoleId==2)
			{
				$sqlCourse=$pdo->prepare('SELECT * FROM `course` WHERE `name` LIKE :name AND `ownerId` = :nowUser AND `visibility` = 0;');
				$sqlCourse->bindValue(':name','%'.urlencode($name).'%',PDO::PARAM_STR);
				$sqlCourse->bindValue(':nowUser',(int)($nowId),PDO::PARAM_INT);
				$sqlCourse->execute();
				$response1=$sqlCourse->fetchall(PDO::FETCH_ASSOC);

				$sqlCourse=$pdo->prepare('SELECT * FROM `course` WHERE `name` LIKE :name AND `visibility`= 1;');
				$sqlCourse->bindValue(':name','%'.urlencode($name).'%',PDO::PARAM_STR);
				$sqlCourse->execute();
				$response2=$sqlCourse->fetchall(PDO::FETCH_ASSOC);

				//var_dump($response1);
				//var_dump($response2);
				

				$response=array_merge($response1,$response2);

				if($response=="")
					return false;
				else
					return $response;
			}
			else 
			{
				$sqlCourse=$pdo->prepare('SELECT * FROM `course` WHERE `name` LIKE :name AND `visibility`= 1;');
				$sqlCourse->bindValue(':name','%'.urlencode($name).'%',PDO::PARAM_STR);
				$sqlCourse->execute();
				$response=$sqlCourse->fetchall(PDO::FETCH_ASSOC);
				if($response==false)
					return false;
				else
					return $response;	
			}
		}
	}

	public function modify($cid,$name,$content,$visibility)
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
			
		}
		else 
			return false;

		global $pdo;

		if($nowRoleId==1)
		{
			$sqlCourse=$pdo->prepare('UPDATE `course` SET `updateTime` = :updateTime , `name` = :name , `content` = :content , `visibility` = :visibility 
													WHERE `cid` = :cid ; ');
			$sqlCourse->bindValue(':updateTime',(int)time(),PDO::PARAM_INT);
			$sqlCourse->bindValue(':name',urlencode($name),PDO::PARAM_STR);
			$sqlCourse->bindValue(':content',urlencode($content),PDO::PARAM_STR);
			$sqlCourse->bindValue(':visibility',(int)($visibility),PDO::PARAM_INT);
			$sqlCourse->bindValue(':cid',(int)($cid),PDO::PARAM_INT);
			$response=$sqlCourse->execute();
		}
		else if($nowRoleId==2)
		{
			$sqlCourse=$pdo->prepare('UPDATE `course` SET `updateTime` = :updateTime ,`name` = :name , `content` = :content , `visibility` = :visibility 
													WHERE `cid` = :cid AND `ownerId` = :nowId; ');
			$sqlCourse->bindValue(':updateTime',(int)time(),PDO::PARAM_INT);
			$sqlCourse->bindValue(':name',urlencode($name),PDO::PARAM_STR);
			$sqlCourse->bindValue(':content',urlencode($content),PDO::PARAM_STR);
			$sqlCourse->bindValue(':visibility',(int)($visibility),PDO::PARAM_INT);
			$sqlCourse->bindValue(':cid',(int)($cid),PDO::PARAM_INT);
			$sqlCourse->bindValue(':nowId',(int)($nowId),PDO::PARAM_INT);
			$response=$sqlCourse->execute();
			
		}
		else
			$response=false;

		if($response==false)
			return false;
		else 
			return true;
	}

	public function delete($cid)
	{

		if(checkAuthority())
		{
			$nowUser=User::show(Site::getSessionUid());
			$nowRoleId=$nowUser[0]['roleId'];
			$nowId=$nowUser[0]['uid'];
			
		}
		else 
			return false;

		global $pdo;

		if($nowRoleId==1)
		{
			$sqlCourse=$pdo->prepare('UPDATE `course` SET `visibility` = -1 WHERE `cid` = :cid ; ');
			$sqlCourse->bindValue(':cid',(int)($cid),PDO::PARAM_INT);
			$response=$sqlCourse->execute();
		}
		else if($nowRoleId==2)
		{
			$sqlCourse=$pdo->prepare('UPDATE `course` SET  `visibility` = -1
													WHERE `cid` = :cid AND `ownerId` = :nowId; ');
			$sqlCourse->bindValue(':cid',(int)($cid),PDO::PARAM_INT);
			$sqlCourse->bindValue(':nowId',(int)($nowId),PDO::PARAM_INT);
			$response=$sqlCourse->execute();
			
		}
		else
			$response=false;

		if($response==false)
			return false;
		else 
			return true;
	}
}
/*
CREATE  TABLE IF NOT EXISTS `learningPlatform`.`course` (
	`cid` INT NOT NULL AUTO_INCREMENT,
	`ownerId` INT NOT NULL, -- 创建人uid
	`createTime` INT NOT NULL, -- 创建时间，秒级
	`updateTime` INT NOT NULL, -- 修改时间，秒级
	`name` VARCHAR(50) NOT NULL, -- 课程名称
	`content` TEXT NOT NULL, -- 课程内容
	`visibility` INT NOT NULL DEFAULT 0, -- 可见性，0隐藏，1可见，-1删除
	PRIMARY KEY (`cid`),
	UNIQUE INDEX `cid_UNIQUE` (`cid` ASC)
)ENGINE = InnoDB;

*/
?>


