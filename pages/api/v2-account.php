<?php

function checkAPIKey($apikey, $con_pdo){

	$stmt_apikey = $con_pdo->prepare('SELECT count(id) AS count, user_id FROM `sessions` WHERE `session_id`=?');
	$stmt_apikey->execute( array( $apikey ) );
	$result_apikey = $stmt_apikey->fetch(PDO::FETCH_ASSOC);

	if($result_apikey['count']){
		return $result_apikey['user_id'];
	}else{
		return false;
	}
	
}

if(empty($params[3])){
	// account
	
	if(checkAPIKey($_GET['apikey'], $con_pdo)){
		$stmt_user = $con_pdo->prepare('SELECT * FROM `users` WHERE `id`=?');
		$stmt_user->execute( array( checkAPIKey($_GET['apikey'], $con_pdo) ) );
		$result_user = $stmt_user->fetch(PDO::FETCH_ASSOC);	
	
		$arr = array ('error'=>false, 'status_code'=>'0007',
		
		'user_fname'=>$result_user['fname'],
		'user_lname'=>$result_user['lname'],
		'user_email'=>$result_user['email'],
		'user_active'=>$result_user['active'],
		'user_premium'=>$result_user['premium'],
		
		);
	}else{
		$arr = array ('error'=>true, 'status_code'=>'0008');
	}
	
	echo json_encode($arr);
}
else{
	$arr = array ('error'=>true, 'status_code'=>'0006');

	echo json_encode($arr);
}