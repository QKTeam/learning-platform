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
}

?>
