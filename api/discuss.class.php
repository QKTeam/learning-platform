
<?php
require_once('../connection/connection.php');
require_once('site.class.php');
require_once('user.class.php');

class Discuss
{
	public $did;
	private $userId;
	private $createTime;
	private $updateTime;
	private $title;
	private $content;
	private $courseId;
	private $fatherId;
	
	public function create($title,$content,$courseId,$fatherId)
	{
		global $pdo;
		$tm=time();
		$sqlDiscuss=$pdo->prepare('INSERT INTO `discuss` (`userId`,`createTime`,`updateTime`,`title`,`content`,`courseId`,`fatherId`) 
												VALUES (:userId,:createTime,:updateTime,:title,:content,:courseId,:fatherId);');
		$sqlDiscuss->bindValue(':userId',(int)Site::getSessionUid(),PDO::PARAM_INT);
		$sqlDiscuss->bindValue(':createTime',(int)$tm,PDO::PARAM_INT);
		$sqlDiscuss->bindValue(':updateTime',(int)$tm,PDO::PARAM_INT);
		$sqlDiscuss->bindValue(':title',urlencode($title),PDO::PARAM_STR);
		$sqlDiscuss->bindValue(':content',urlencode($content),PDO::PARAM_STR);
		$sqlDiscuss->bindValue(':courseId',(int)$courseId,PDO::PARAM_INT);
		$sqlDiscuss->bindValue(':fatherId',(int)$fatherId,PDO::PARAM_INT);
		
		$response=$sqlDiscuss->execute();
		try
		{
			return (int)$pdo->lastInsertId();
		}
		catch(Exception $e)
		{
			return 0;
		}
	}

	public function show($did)
	{
		global $pdo;

		$sqlDiscuss=$pdo->prepare('SELECT * FROM `discuss` WHERE `did` = :did;');
		$sqlDiscuss->bindValue(':did',(int)$did,PDO::PARAM_INT);
		$sqlDiscuss->execute();
		$response=$sqlDiscuss->fetch(PDO::FETCH_ASSOC);
		if($response==false)
		{
			return false;
		}
		else
		{
			return $response;
		}
	}

	public function list($courseId)
	{
		global $pdo;
		$sqlDiscuss=$pdo->prepare('SELECT * FROM `discuss` WHERE `courseId` = :courseId;');
		$sqlDiscuss->bindValue(':courseId',(int)($courseId),PDO::PARAM_INT);
		$sqlDiscuss->execute();
		$response=$sqlDiscuss->fetchall(PDO::FETCH_ASSOC);
		return $response;	
		
	}

	public function modify($did,$title,$content)
	{
			
		global $pdo;
		if(checkAuthority())
		{
			$nowUser=User::show(Site::getSessionUid());
			$nowRoleId=$nowUser[0]['roleId'];
			$nowId=$nowUser[0]['uid'];
		}
		else 
			return false;

		if($nowRoleId==1)
		{
			$sqlDiscuss=$pdo->prepare('UPDATE `discuss` SET `updateTime`=:updateTime, `title` = :title , `content` = :content WHERE `did` = :did ;');
			$sqlDiscuss->bindValue(':updateTime',(int)time(),PDO::PARAM_INT);
			$sqlDiscuss->bindValue(':title',urlencode($title),PDO::PARAM_STR);
			$sqlDiscuss->bindValue(':content',urlencode($content),PDO::PARAM_STR);
			$sqlDiscuss->bindValue(':did',(int)($did),PDO::PARAM_INT);
			$response=$sqlDiscuss->execute();
		}
		else 
		{
			$sqlDiscuss=$pdo->prepare('UPDATE `discuss` SET `updateTime`=:updateTime, `title` = :title , `content` = :content WHERE `did` = :did AND `userId`= :userId;');
			$sqlDiscuss->bindValue(':updateTime',(int)time(),PDO::PARAM_INT);
			$sqlDiscuss->bindValue(':title',urlencode($title),PDO::PARAM_STR);
			$sqlDiscuss->bindValue(':content',urlencode($content),PDO::PARAM_STR);
			$sqlDiscuss->bindValue(':did',(int)($did),PDO::PARAM_INT);
			$sqlDiscuss->bindValue(':userid',(int)($nowUser),PDO::PARAM_INT);
			$response=$sqlDiscuss->execute();
		}
	
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
		}
		else 
			return false;
		global $pdo;

		if($nowRoleId==1)
		{
			$sqlDiscuss=$pdo->prepare('UPDATE FROM `discuss` WHERE `pid` = :pid ; ');
			$sqlDiscuss->bindValue(':pid',(int)($pid),PDO::PARAM_INT);
			$response=$sqlDiscuss->execute();
		}
		else
		{
			$flag=0;			
			$sqlDiscuss=$pdo->prepare('SELECT * FROM `discuss` WHERE `did`=:did;');
			$sqlDiscuss->bindValue(':did',(int)($did),PDO::PARAM_INT);
			$sqlDiscuss->execute();
			$response=$sqlDiscuss->fetch(PDO::FETCH_ASSOC);
			if ($response['userid']==$nowId) 
				$flag=1;
			
			if(Course::findOwner($response['courseId'])==$nowid)
				$flag=1;

			if($flag==1)
			{
				$sqlDiscuss=$pdo->prepare('UPDATE FROM `discuss` WHERE `pid` = :pid ; ');
				$sqlDiscuss->bindValue(':pid',(int)($pid),PDO::PARAM_INT);
				$response=$sqlDiscuss->execute();	
			}
			else 
				return false;
		}

		if($response==false)
			return false;
		else 
			return true;
	}
}
/*
`did` INT NOT NULL AUTO_INCREMENT,
	`userId` INT NOT NULL, -- 创建用户id
	`createTime` INT NOT NULL, -- 创建时间，秒级
	`updateTime` INT NOT NULL, -- 修改时间，秒级
	`title` VARCHAR(50) NOT NULL, -- 讨论标题
	`content` TEXT NOT NULL, -- 讨论内容
	`courseId` INT NOT NULL, -- discuss的课程id
	`fatherId` INT NOT NULL, -- 回复discuss的id，默认0表示新开
	PRIMARY KEY (`did`),

*/


?>


