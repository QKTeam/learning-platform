<?php
require_once('../connection/connection.php');
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
			{
					echo $response['password'];
					var_dump($response);
					return -1;
			}
		
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
		echo $sqlUser->rowCount();
		if($sqlUser->rowCount()!=0)
			return -1;
		$sqlUser=$pdo->prepare('INSERT INTO `user` 
			(`username`,`password`,`email`,`phone`,`gender`,`studentId`,`roleId`)
			VALUES (:username,:password,:email,:phone,:gender,:studentId,:roleId); ');
		$sqlUser->bindValue(':username',urlencode($this->username),PDO::PARAM_STR);
		$sqlUser->bindValue(':password',urlencode($this->password),PDO::PARAM_STR);
		$sqlUser->bindValue(':email',urlencode($this->email),PDO::PARAM_STR);
		$sqlUser->bindValue(':phone',(int)($this->phone),PDO::PARAM_INT);
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
	
	public function show()
	{
		global $pdo;
		$sqlUser=$pdo->prepare('SELECT * FROM `user` WHERE `uid`=:uid ;');
		$sqlUser->bindValue(':uid',(int)$this->uid,PDO::PARAM_INT);
		$sqlUser->execute();
		if( ($response=$sqlUser->fetch(PDO::FETCH_ASSOC)) == false )
		{
			return false;
		}
		$this->username=$response('username');	
		return true;
		
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

?>
