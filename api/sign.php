<?php

	echo "ss";
	$request = json_decode(file_get_contents('php://input', 'r'), true);
	var_dump($request);
	$username=$request['username'];


	echo $username;
	SUCCESS(json_encode($username));	

	function SUCCESS($data) { // $data is a json string
	die(json_encode(array(
		'code' => '0000',
		'response' => json_decode($data, true)
	)));
}

?>


