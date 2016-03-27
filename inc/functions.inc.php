<?php


function doLogin($con_pdo, $username, $password, $rememberMe=false, $twitter=false){

	$utime = time();

	if($twitter){
		$stmt_user = $con_pdo->prepare('SELECT *, count(id) AS count FROM `users` WHERE oauth_provider=? AND oauth_uid=? LIMIT 1');
		$stmt_user->execute( array( $username, $password ) );
		$result_user = $stmt_user->fetch(PDO::FETCH_ASSOC);
	}else{
		$stmt_user = $con_pdo->prepare('SELECT *, count(id) AS count FROM `users` WHERE email=? AND password=? LIMIT 1');
		$stmt_user->execute( array( $username, sha1('D,#7#53Mq4y3kWn*@Fy%Z2cC'.$password) ) );
		$result_user = $stmt_user->fetch(PDO::FETCH_ASSOC);
	}
	
	if($result_user['count'] == 1){
		$_SESSION["UserID"] = $result_user['id'];
		$_SESSION['UserSess'] = md5(uniqid('LOGOUT', true));

		//Remember Me?
		if($rememberMe){
		$expire = time() + 3600 * 24 * 30; //Verfalldatum in 60 Tagen

		setcookie("rememberMe", base64_encode($result_user['id']), $expire);

		$salt = "gjhg/%4565GUUZTu&772";
		$hash = md5($salt."|".$result_user["rememberMe"]."|".$result_user["time_register"]."|".substr($result_user["apiKey"], 0, 5)."|".$result_user["id"]);

		/* Und jetzt schreiben wir auch dieses Cookie */
		setcookie("rememberMeToken", $hash, $expire);

			return "201";
		}
		else{
			return "200";
		}
	}else{
		return "400";
	}

}//function

function doAutoLogin($con_pdo){

//Das Cookie lesen, decodieren und so die User-ID erhalten
$userId = base64_decode($_COOKIE["rememberMe"]);

/* Den Hash wiederherstellen
Da wir diese Codezeilen hier bereits zum zweiten Mal nutzen, würde man
diese in der Praxis natürlich in eine eigene Funktion auslagern */

$stmt_user = $con_pdo->prepare('SELECT *, count(id) AS count FROM `users` WHERE id=? LIMIT 1');
$stmt_user->execute( array( $userId ) );
$result_user = $stmt_user->fetch(PDO::FETCH_ASSOC);

$salt = "gjhg/%4565GUUZTu&772"; // siehe http://bit.ly/hXtmVJ
$hash = md5($salt."|".$result_user["rememberMe"]."|".$result_user["time_register"]."|".substr($result_user["apiKey"], 0, 5)."|".$result_user["id"]);

//Die Daten vergleichen
if($hash == $_COOKIE["rememberMeToken"]){
//wenn ok einloggen
$_SESSION["UserID"] = $result_user["id"];
if(!isset($_SESSION['UserSess'])){
	$_SESSION['UserSess'] = md5(uniqid('LOGOUT', true));
}
return true;
}//if

return false;

}//function