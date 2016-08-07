<?php
require_once('../connection/connection.php');
require_once('site.class.php');
class User
{
	public $uid;
	public $username;
	public $password;
	private $email;
	private $phone;
	private $gender;
	private $studentId;
	private $roleId;
	public function init($username,$password,$email,$phone,$gender,$studentId,$roleId)
	{
		$this->username=$username;
		$this->password=$password;
		$this->email=$email;
		$this->phone=$phone;
		$this->gender=$gender;//male:0,famale:1;
		$this->studentId=$studentId;
		$this->roleId=$roleId;
	}

	public function login()
	{
		global $pdo;
		$username=$this->username;
		$password=$this->password;
		$sqlUser=$pdo->prepare('SELECT * FROM `user` WHERE `username` = :username ;');
		$sqlUser->bindValue(':username',urlencode($username),PDO::PARAM_STR);
		$sqlUser->execute();

		if( ($response = $sqlUser->fetch(PDO::FETCH_ASSOC)) ==false )
			return -1;	
		if($response['password']==sha1($username.$password))
			return $response['uid'];
		else 
			return 0; 
	}

	public function create()
	{
		global $pdo;
		$username=$this->username;
		$sqlUser=$pdo->prepare('SELECT `password` FROM `user` WHERE `username` = :username;');
		$sqlUser->bindValue(':username',urlencode($username),PDO::PARAM_STR);
		$sqlUser->execute();
		if($sqlUser->rowCount()!=0)
			return -1;
		$sqlUser=$pdo->prepare('INSERT INTO `user` 
			(`username`,`password`,`email`,`phone`,`gender`,`studentId`,`roleId`)
			VALUES (:username,:password,:email,:phone,:gender,:studentId,:roleId); ');
		$sqlUser->bindValue(':username',urlencode($this->username),PDO::PARAM_STR);
		$sqlUser->bindValue(':password',urlencode($this->password),PDO::PARAM_STR);
		$sqlUser->bindValue(':email',urlencode($this->email),PDO::PARAM_STR);
		$sqlUser->bindValue(':phone',urlencode($this->phone),PDO::PARAM_STR);
		$sqlUser->bindValue(':gender',(int)($this->gender),PDO::PARAM_INT);
		$sqlUser->bindValue(':studentId',urlencode($this->studentId),PDO::PARAM_STR);
		$sqlUser->bindValue(':roleId',(int)($this->roleId),PDO::PARAM_INT);
		$sqlUser->execute();
		try
		{
			return (int)$pdo->lastInsertId();
		}
		catch(Exception $e)
		{
			return 0;
		}
	}
	
	public function modify($uid,$old_password,$new_password,$email,$phone,$roleId)
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
		$response=User::show($uid);
		$username=$response[0]['username'];
		$oldpassword=$response[0]['password'];
		if($nowRoleId==1)
		{
			$sqlUser=$pdo->prepare('UPDATE `user` SET `password`=:password,
													  `email`=:email,
													  `phone`=:phone,
													  `roleId`=:roleId
													WHERE `uid`=:uid;');
			$sqlUser->bindValue(':password',urlencode(sha1($username.$new_password)),PDO::PARAM_STR);
			$sqlUser->bindValue(':email',urlencode($email),PDO::PARAM_STR);
			$sqlUser->bindValue(':phone',urlencode($phone),PDO::PARAM_STR);
			$sqlUser->bindValue(':roleId',(int)$roleId,PDO::PARAM_INT);
			$sqlUser->bindValue(':uid',(int)$uid,PDO::PARAM_INT);
			return $sqlUser->execute();
		}
		else 
		{
			if($uid!=$nowId)
				return false;
			if($oldpassword!=sha1($username.$old_password))
				return false;
			$sqlUser=$pdo->prepare('UPDATE `user` SET `password`=:password,
													  `email`=:email,
													  `phone`=:phone
													WHERE `uid`=:uid AND `password`=:old_password;');
			$sqlUser->bindValue(':password',urlencode(sha1($username.$new_password)),PDO::PARAM_STR);
			$sqlUser->bindValue(':email',urlencode($email),PDO::PARAM_STR);
			$sqlUser->bindValue(':phone',urlencode($phone),PDO::PARAM_STR);
			$sqlUser->bindValue(':old_password',urlencode(sha1($username.$old_password)),PDO::PARAM_STR);
			$sqlUser->bindValue(':uid',(int)$uid,PDO::PARAM_INT);
			return $sqlUser->execute();

		}
	}

	public function modifyAdmin($uid,$email,$phone,$roleId)
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
		$response=User::show($uid);
		$username=$response[0]['username'];
		if($nowRoleId==1)
		{
			$sqlUser=$pdo->prepare('UPDATE `user` SET `email`=:email,
													  `phone`=:phone,
													  `roleId`=:roleId
													WHERE `uid`=:uid;');
			$sqlUser->bindValue(':email',urlencode($email),PDO::PARAM_STR);
			$sqlUser->bindValue(':phone',urlencode($phone),PDO::PARAM_STR);
			$sqlUser->bindValue(':roleId',(int)$roleId,PDO::PARAM_INT);
			$sqlUser->bindValue(':uid',(int)$uid,PDO::PARAM_INT);
			return $sqlUser->execute();
		}
		return false;
	}

	public function show($id)
	{
		global $pdo; 
		{
			$sqlUser=$pdo->prepare('SELECT * FROM `user` WHERE `uid` = :id;');
			$sqlUser->bindValue(':id',(int)$id,PDO::PARAM_INT);
			$sqlUser->execute();	
		}
		if( ($response=$sqlUser->fetchall(PDO::FETCH_ASSOC)) == false )
		{
			return false;
		}
		return $response;
	}

	public function listData($username,$studentId,$roleId)
	{
		global $pdo;
		$sqlUser=$pdo->prepare('SELECT * FROM `user` WHERE `username` like :username AND `studentId` LIKE :studentId AND `roleId` LIKE :roleId;');
		$sqlUser->bindValue(':username','%'.urlencode($username).'%',PDO::PARAM_STR);
		$sqlUser->bindValue(':studentId','%'.urlencode($studentId).'%',PDO::PARAM_STR);
		$sqlUser->bindValue(':roleId','%'.urlencode($roleId).'%',PDO::PARAM_STR);
		$sqlUser->execute();
		$response=$sqlUser->fetchall(PDO::FETCH_ASSOC);
		return $response;
	}


	public function delete()
	{
		global $pdo;
		$sqlUser=$pdo->prepare('DELETE FROM `user` WHERE `uid`=:uid;');
		$sqlUser->bindValue(':uid',(int)$this->uid,PDO::PARAM_INT);
		$sqlUser->execute();
		return ($sqlUser->rowCount() == 1);
	}
	
}
