<?php

if(isset($_GET['email']) && isset($_GET['code'])){
	
	$stmt_register = $con_pdo->prepare('SELECT count(id) AS count, id FROM users WHERE email=? AND code=?');
	$stmt_register->execute( array( $_GET['email'], $_GET['code'] ) );
	$result_register = $stmt_register->fetch(PDO::FETCH_ASSOC);
	
	if($result_register['count'] == 1){
		$stmt_name = $con_pdo->prepare('UPDATE users SET active=1 WHERE email=? AND code=?');
		$check_name = $stmt_name->execute( array( $_GET['email'], $_GET['code'] ) );

		if($check_name){
			$msg = "<p>Ihr Uptime-Monitor.net Account wurde erfolgreich aktiviert. Die können jetzt alle Features im vollem Umfang nutzen.</p>";
		}else{
			$msg = "<p>Ein MySQL Fehler verhinderte das Freischalten.</p>";
		}
	}else{
		$msg = "<p>Wir konnten Ihren Account nicht aktivieren!</p>";
	}

}

?>
<?php include 'inc/html-top.inc.php'; ?>
<h2>Account aktivierung</h2>
<?php if(isset($msg)){echo $msg;} ?>
<?php include 'inc/html-bottom.inc.php'; ?>