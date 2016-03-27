<?php
if(!isset($_SESSION['UserID'])){
	$_SESSION['reffer'] = $page;
	header('Location: /login');
}

if(isset($_POST['submit'])){
	
	$stmt_user = $con_pdo->prepare('SELECT count(id) AS count FROM `users` WHERE email=? AND id!=? LIMIT 1');
	$stmt_user->execute( array( $_POST['email'], $_SESSION['UserID'] ) );
	$result_user = $stmt_user->fetch(PDO::FETCH_ASSOC);

	if(empty($_POST['fname']) || empty($_POST['lname']) || empty($_POST['email'])){
		$msg = "<p class=\"error fade\">Bitte füllen sie alle Felder aus, da sie Pflicht sind.</p>";
	}
	else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		$msg = "<p class=\"error fade\">Die E-Mail scheint nicht Gültig zu sein.</p>";
	}
	else if($result_user['count'] != 0){
		$msg = "<p class=\"error fade\">Es gibt schon einen Account mit dieser E-Mail.</p>";
	}
	else{
		$stmt_name = $con_pdo->prepare('UPDATE `users` SET fname=?, lname=?, email=?, account_complete=2 WHERE id=?');
		$check_name = $stmt_name->execute( array( $_POST['fname'], $_POST['lname'], $_POST['email'], $_SESSION['UserID'] ) );

		if($check_name){
			$stmt_user = $con_pdo->prepare('SELECT * FROM `users` WHERE id=? LIMIT 1');
			$stmt_user->execute( array( $_SESSION['UserID'] ) );
			$user = $stmt_user->fetch(PDO::FETCH_ASSOC);
			$msg = "<p class=\"success fade\">Ihre Kontakt-Daten wurden erfolgreich gespeichert.</p>";
		}else{
			$msg = "<p class=\"error fade\">Ein MySQL-Error ist aufgetreten. Bitte benachrichtigen Sie den Support.</p>";
		}
	}

}

if(isset($_POST['submit2'])){
	
	$salt = 'D,#7#53Mq4y3kWn*@Fy%Z2cC';
		
	$password1 = sha1($salt.$_POST['pass']);
	$password2 = sha1($salt.$_POST['pass2']);

	// CHECK OLD PASSWORD
	$stmt_pc = $con_pdo->prepare('SELECT count(id) AS count, id FROM `users` WHERE id=? AND password=? LIMIT 1');
	$stmt_pc->execute( array( $_SESSION['UserID'], $password1 ) );
	$result_pc = $stmt_pc->fetch(PDO::FETCH_ASSOC);
	
	
	if($password1 != $password2){
		$msg = "<p class=\"error fade\">Die Passwörter stimmen nicht überein.</p>";
	}
	else if($result_pc['count'] != 1){
		$msg = "<p class=\"error fade\">Das Passwort ist nicht korrekt.</p>";
	}
	else if($user['premium'] != 0){
		$msg = "<p class=\"error fade\">Ihr Konto konnte nicht gelöscht werden. Grund: Sie sind ein Premium-Nutzer.</p>";
	}
	else{
		$stmt_mid = $con_pdo->prepare('SELECT id FROM `monitors` WHERE owner=?');
		$stmt_mid->execute( array( $_SESSION['UserID'] ) );
		while($result_mid = $stmt_mid->fetch(PDO::FETCH_ASSOC)){
			//Remove
			//$time = '*/5 * * * *';
			//$srcipt = 'php -e /home/ping/check.php -f='. $row['id'];
			//mysqli_query($cronmaster,"DELETE FROM crons WHERE time='$time' AND script='$srcipt' AND active='1'");
			//mysqli_query($uptime,"DELETE FROM monitors WHERE id='{$row['id']}'");
			//mysqli_query($uptime,"DELETE FROM protokoll WHERE monitorid='{$row['id']}'");
			
			$stmt_d1 = $con_pdo->prepare('DELETE FROM monitors WHERE id=?');
			$stmt_d2 = $con_pdo->prepare('DELETE FROM protokoll WHERE id=?');
			$stmt_d3 = $con_pdo->prepare('DELETE FROM crons WHERE monitor=?');
		
			$stmt_d1->execute( array( $result_mid['id'] ) );
			$stmt_d2->execute( array( $result_mid['id'] ) );
			$stmt_d3->execute( array( $result_mid['id'] ) );
		}
		$stmt_d4 = $con_pdo->prepare('DELETE FROM users WHERE id=?');
		$check_d4 = $stmt_d4->execute( array( $_SESSION['UserID'] ) );
		
		$stmt_d5 = $con_pdo->prepare('DELETE FROM log WHERE user=?');
		$check_d5 = $stmt_d4->execute( array( $_SESSION['UserID'] ) );
		
		$betreff = "Schade das sie gegangen sind";
		$mailtext = 'Hallo '. $user['fname'] .' '. $user['lname'] .',

es hat uns gefreut, das sie Uptime-Monitor genutzt haben. Sollte sie was gestört haben sagen sie uns das bitte via team@uptime-monitor.net, damit wir unseren Dienst fürs näschste mal verbessern können. Danke.


Mit freundlichen Grüßen,

Uptime-Monitor.net';
		mail($user['email'], $betreff, $mailtext, "From: \"Uptime-Monitor.net\" <team@uptime-monitor.net>"); 
			
		$_SESSION['sessionmsg'] = "<p class=\"info fade\">Ihr Account wird nun geschlossen. Schade das sie gegangen sind.</p>";
		header('Location: /logout&confirm='.$_SESSION['UserSess']);
	}

}
?>
<?php include 'inc/html-top.inc.php'; ?>
<h2>Kontakt-Daten verwalten</h2>
<?php if(isset($msg)){echo $msg;} ?>
<p>Auf dieser Seite haben Sie die Möglichkeit, die Kontakt-Daten zu verwalten, die in Verbindung mit ihrem Benutzer-Konto bei Uptime-Minitor
stehen, sowie dieses auch zu löschen. <br/><br/>Bitte geben sie in den untentehenden Formularen warheitsgemäße Daten ein, da diese andernfalls nicht 
in Verbindung mit einer eingegangenen Zahlung gebracht werden können.</p><br/>
<br/><hr/>
<br><br/>
<h3>Pers&ouml;nliche Angaben</h3>
<form action="/account/edit" method="post">
	<p>Vor- und Nachname:<br><input autocomplete="off" style="width:260px;" name="fname" type="text" value="<?php echo htmlspecialchars($user['fname']); ?>" placeholder="Max" />
	<input autocomplete="off" style="width:260px;" name="lname" type="text" value="<?php echo htmlspecialchars($user['lname']); ?>" placeholder="Mustermann" /></p>
	<p>E-Mail-Adresse:<br><input autocomplete="off" name="email" type="text" value="<?php echo htmlspecialchars($user['email']); ?>" placeholder="email@domain.tld" /></p>
	<br/>
	<p><input class="button" name="submit" type="submit" value="Kontakt-Daten speichern" />
	<small class="next-to-btn">Selbtverständlich geben wir ihre Kontakt-Daten nicht an Dritte weiter und verwenden sie auch nicht zum Versenden von Werbung, Newslettern oder ähnlichem.</small></p>
</form>
<br><br/>
<hr>
<br><br/>
<h3>Account löschen</h3>

<form action="/account/edit" method="post">
	<p>Passwort:<br><input <?php if ($user['premium']==1) { echo 'disabled="true" '; } ?>name="pass" type="password" placeholder="Bitte geben sie hier ihr Passowort ein" required /></p>
	<p>Passwort wiederholen:<br><input <?php if ($user['premium']==1) { echo 'disabled="true" '; } ?>name="pass2" type="password" placeholder="Bitte geben sie hier ihr Passwort ein" required /></p>
	<br/>
	<p><input <?php if ($user['premium']==1) { echo 'disabled="true" '; } ?>class="button" name="submit2" type="submit" value="Konto zur Löschung freigeben" /></p>
</form><?php if ($user['premium']==1) { ?><br/><br/>
<p class="error fade" style="font-size: 11px;">Bitte beachten Sie, dass es nicht möglich ist, ihr Konto über ihre Verwaltungs-Oberfläche zu deaktivieren, wenn sie im Besitz eines Premium-Kontos sind. Dies dient ihrer eigenen Sicherheit. Wenn Sie ihr Konto löschen möchten, kontaktieren sie bitte den <a href="/support">Support</a>.</p><?php } ?>
<?php include 'inc/html-bottom.inc.php'; ?>