<?php

if(empty($params[3])){
	// authenticate
	
	if(empty($_GET['user']) && empty($_GET['password'])){
		$arr = array ('error'=>true, 'status_code'=>'0004');
	}else{
		$login = doLogin($con_pdo, $_GET['user'], $_GET['password'], false);
		
		if($login == 201 || $login == 200){
		
			$session_id = base64_encode(md5($_SESSION['UserID'].'|'.time()).microtime(true));
			
			$stmt_session = $con_pdo->prepare('INSERT INTO sessions (session_id, user_id, time) VALUES (?, ?, ?)');
			$check_session = $stmt_session->execute( array( $session_id, $_SESSION['UserID'], time() ) );
		
			if($check_session){
				$arr = array ('error'=>false, 'status_code'=>'0002','session_id'=>$session_id);
			}else{
				$arr = array ('error'=>true, 'status_code'=>'0005');
			}
		}else if($login == 400){
			$arr = array ('error'=>true, 'status_code'=>'0003');
		}
	}

	echo json_encode($arr);
}
else{
	$arr = array ('error'=>true, 'status_code'=>'0001');

	echo json_encode($arr);
}