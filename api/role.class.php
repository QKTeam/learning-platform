<?php
require_once('../connection/connection.php');
class Role
{
	public $rid;
	public $name;

	public function find($roleId)
	{
		global $pdo;
		
		$sqlRole=$pdo->prepare('SELECT * FROM `role` WHERE `rid` = :roleId;');
		$sqlRole->bindValue(':roleId',(int)$roleId,PDO::PARAM_INT);
		$sqlRole->execute();	

		if( ($response=$sqlRole->fetch(PDO::FETCH_ASSOC))==false )
		{
			return false;
		}
		else 
		{
			return $response['name'];
		}
	}

	public function list()
	{
		global $pdo;
		$sqlRole=$pdo->prepare('SELECT * FROM `role`;');
		$sqlRole->execute();
		if( ($response=$sqlRole->fetchall(PDO::FETCH_ASSOC))==false)
		{
			return false;
		}
		else 
		{
			foreach($response as &$i)
			{
				$i['name']=urldecode($i['name']);
			}
			return $response;
		}
	}

	public function modify($rid,$rName)
	{
		global $pdo;
		$sqlRole=$pdo->prepare('UPDATE `role` SET `name` = :rName WHERE `rid` = :rid;');
		$sqlRole->bindValue(':rName',urlencode($rName),PDO::PARAM_STR);
		$sqlRole->bindValue(':rid',(int)($rid),PDO::PARAM_INT);
		return $sqlRole->execute();
	}
}

?>
