<?php
require_once('../connection/connection.php');
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
		
		if($response==false)
		{
			return false;
		}
		else 
		{
			return true;
		}
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
}/*
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


