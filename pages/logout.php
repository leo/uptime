<?php
	session_start();
	
	if($_GET['confirm'] == $_SESSION['UserSess']){
		unset($_SESSION['UserID']);
		unset($_SESSION['UserSess']);
		
		$expire = time() - 3600 * 24 * 30;
		
		setcookie("rememberMe", "", $expire);
		setcookie("rememberMeToken", "", $expire);
		
		header('Location: /login');
	}else{
		header('Location: /monitor');
	}
?>