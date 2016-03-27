<?php

if(!isset($_SESSION['UserID'])){
	$_SESSION['reffer'] = $page;
	header('Location: /login');
}

if(isset($_POST['submit']) == "Speichern"){

	$salt = 'D,#7#53Mq4y3kWn*@Fy%Z2cC';
		
	$password = sha1($salt.$_POST['pass1']);
	$password2 = sha1($salt.$_POST['pass2']);
	$password3 = sha1($salt.$_POST['pass3']);

	// CHECK OLD PASSWORD
	$stmt_pc = $con_uptime->prepare('SELECT count(id) AS count FROM `users` WHERE id=? AND password=? LIMIT 1');
	$stmt_pc->execute( array( $_SESSION['UserID'], $password ) );
	$result_pc = $stmt_pc->fetch(PDO::FETCH_ASSOC);
	
	if(!$result_pc){
		$msg = "<p class=\"error fade\">Einstellungen wurden nicht übernommen. (MySQL Error #2)</p>";
	}
	else if(strlen($_POST['pass2'])<7){
		$msg = "<p class=\"error fade\">Das neue Passwort ist zu Kurz, mindestens 8 Zeichen.</p>";
	}
	else if($password2 != $password3){
		$msg = "<p class=\"error fade\">Die neuen Passwörter stimmen nicht überein.</p>";
	}
	else if($result_pc['count'] != 1){
		$msg = "<p class=\"error fade\">Das alte Passwort stimmt nicht überein.</p>";
	}
	else{
		$stmt_up = $con_uptime->prepare('UPDATE `users` SET password=? WHERE password=? AND id=? LIMIT 1');
		$check_up = $stmt_up->execute( array( $password2, $password, $_SESSION['UserID'] ) );
		
		if($check_up){
			$msg = "<p class=\"success fade\">Das Paswort wurde erfolgreich geändert.</p>";
		}else{
			$msg = "<p class=\"error fade\">Einstellungen wurden nicht übernommen. (MySQL Error #1)</p>";
		}
	
	}

}

?>
<?php include 'inc/html-top.inc.php'; ?>
<h2>Eigenes Kennwort ändern</h2>
<?php if(isset($msg)){echo $msg;} ?>
<p>Hier können sie das Zugangspasswort für ihr Konto bearbeiten.</p><br>
<form action="/account/password" method="post">
	<p>Altes Passwort:<br><input style="width:623px;" name="pass1" type="password" placeholder="Ihr Altes Passwort" /></p>
	<p>Neues Passwort:<br><input style="width:623px;" name="pass2" type="password" placeholder="Ihr neues Passwort" /></p>
	<p>Passwort wiederholen:<br><input style="width:623px;" name="pass3" type="password" placeholder="Wiederholen sie ihr neues Passwort" /></p>
	<br/><p><input class="button" name="submit" type="submit" value="Speichern" /></p>
</form>
<?php include 'inc/html-bottom.inc.php'; ?>