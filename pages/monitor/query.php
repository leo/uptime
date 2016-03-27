<?php
if($params[3] == 'stop' && isset($params[2])){
	$stmt_stop = $con_pdo->prepare('UPDATE monitors SET status=0 WHERE id=? AND owner=?');
	$check_stop = $stmt_stop->execute( array( $params[2], $_SESSION['UserID'] ) );

	if($check_stop){
		$_SESSION['apimsg'] = "<div class=\"success fade\">Die Pr&uuml;fung des ausgew&auml;hlten Monitors wurde erfolgreich angehalten.</div>";
	}else{
		$_SESSION['apimsg'] = "<div class=\"success fade\">Ein MySQL Fehler verhinderte die Aktion, bitte benachrichtige den Support.</div>";
	}
	header("Location: /monitor");
	
	}else if($params[3] == 'start' && isset($params[2])){
	$stmt_start = $con_pdo->prepare('UPDATE monitors SET status=1 WHERE id=? AND owner=?');
	$check_start = $stmt_start->execute( array( $params[2], $_SESSION['UserID'] ) );

	if($check_start){
		$_SESSION['apimsg'] = "<div class=\"success fade\">Die Pr&uuml;fung des ausgew&auml;hlten Monitors wurde erfolgreich wieder getartet.</div>";
	}else{
		$_SESSION['apimsg'] = "<div class=\"success fade\">Ein MySQL Fehler verhinderte die Aktion, bitte benachrichtige den Support.</div>";
	}
	header("Location: /monitor");
}
?>