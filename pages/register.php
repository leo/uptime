<?php

if(isset($_POST['submit']) == 'Jetzt kostenloses Konto erstellen'){
	$stmt_user = $con_pdo->prepare('SELECT count(id) AS count FROM `users` WHERE email=? LIMIT 1');
	$stmt_user->execute( array( $_POST['email'] ) );
	$result_user = $stmt_user->fetch(PDO::FETCH_ASSOC);

	if(empty($_POST['fname']) || empty($_POST['lname']) || empty($_POST['email']) || empty($_POST['pass1']) || empty($_POST['pass2'])){
		$msg = "<p class=\"error fade\">Bitte füllen sie alle Felder aus, da sie Pflicht sind.</p>";
	}
	else if(empty($_POST['agb'])){
		$msg = "<p class=\"error fade\">Sie mussen die Nutzungsbedingungen von Uptime-Monitor zustimmen.</p>";
	}
	else if($_POST['pass1'] != $_POST['pass2']){
		$msg = "<p class=\"error fade\">Sie mussen die Nutzungsbedingungen von Uptime-Monitor zustimmen.</p>";
	}
	else if(strlen($_POST['pass1'])<7){
		$msg = "<p class=\"error fade\">Ihr Passwort muss aus mindestens 7 Zeichen bestehen.</p>";
	}
	else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		$msg = "<p class=\"error fade\">Die E-Mail scheint nicht Gültig zu sein.</p>";
	}
	else if($result_user['count'] != 0){
		$msg = "<p class=\"error fade\">Es gibt schon einen Account mit dieser E-Mail.</p>";
	}
	else if (empty($_SESSION['captcha']) || trim(strtolower($_REQUEST['captcha'])) != $_SESSION['captcha']) {
		$msg = "<p class=\"error fade\">Sicherheitscode ist nicht korrekt!</p>";
	}
	else{
	
		$apiKey = base64_encode(md5($_POST['email'].'|'.time()).microtime(true));
		$code = rand(1111,9999);
	
		$stmt_name = $con_pdo->prepare('INSERT INTO `users` (`fname`, `lname`, `email`, `password`, `code`, `p_email`, `apiKey`, `time_register`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
		$check_name = $stmt_name->execute( array( $_POST['fname'], $_POST['lname'], $_POST['email'], sha1('D,#7#53Mq4y3kWn*@Fy%Z2cC'.$_POST['pass1']), $code, $_POST['email'], $apiKey, time() ) );

		if($check_name){
			$msg = "<p class=\"success fade\">Wir haben ihnen einen Account erstellt, bitte schauen sie in ihr Postfach.</p>";
			
		$betreff = "Noch einen letzten Schritt bis zu Ihrem Account";
		$mailtext = "Hallo ". htmlspecialchars($_POST['fname']) ." ". htmlspecialchars($_POST['lname']) .",

willkommen bei Uptime-Monitor! Bitte klicken Sie auf den folgenden Link, um Ihr kostenloses Uptime-Monitor Konto zu bestätigen:  

http://uptime-monitor.net/activate&email=". htmlspecialchars($_POST['email']) ."&code=". $code ."


Mit freundlichen Grüßen, 

Uptime-Monitor.net 


Dies ist eine Automatische E-Mail, sie können uns via team@uptime-monitor.net erreichen.";
		mail(htmlspecialchars($_POST['email']), $betreff, $mailtext, "From: \"Uptime-Monitor.net\" <team@uptime-monitor.net>"); 
		mail("preussmaurice@gmail.com", "Neuer Kunde", htmlspecialchars($_POST['name']) ." ist ein neuer Kunde.", "From: \"Uptime-Monitor.net\" <team@uptime-monitor.net>"); 	
		}else{
			$msg = "<p class=\"error fade\">Ein MySQL Fehler verhinderte das erstellen.</p>";
		}
	
	}
}

?>
<?php include 'inc/html-top.inc.php'; ?>
<h2>Neues Konto erstellen</h2>
<?php if(isset($msg)){echo $msg;} ?>
<p>Erstellen sie sich jetzt ein neues Konto bei Uptime-Monitor und profitieren sie von zahlreichen Vorteilen.<br/>
Die Anmeldung ist selbstverständlich zu 100% kostenfrei. Zudem ist ihr Konto jederzeit deaktivierbar (<a href="javascript:toggle('info-deactivate')">Ausnahme</a>).<br/>
<small id="info-deactivate" style="display: none; color: green;">(Sind sie im Besitz eines Premium-Kontos wird die manuelle Deaktivierung durch einen Mitarbeiter erforderlich.)</small></p><br>
<br/><hr/><br/><br/>
<form action="./register" method="post">

	<p>
		<label for="fname">Vor- und Nachname:</label><br/>
		<input autocomplete="off" style="width:260px;" name="fname" type="text" value="<?php if(isset($_POST['fname'])){ echo htmlspecialchars($_POST['fname']); } ?>" placeholder="Max" required="true" />
		<input autocomplete="off" style="width:260px;" name="lname" type="text" value="<?php if(isset($_POST['lname'])){ echo htmlspecialchars($_POST['lname']); } ?>" placeholder="Mustermann" required="true" />
	</p>
	
	<p>
		<label for="email">E-Mail-Adresse:</label></br/>
		<input autocomplete="off" name="email" type="text" value="<?php if(isset($_POST['email'])){ echo htmlspecialchars($_POST['email']); } ?>" placeholder="email@domain.tld" required="true" />
	</p>
	
	<p>
		<label for="pass1">Passwort:</label><br/>
		<input name="pass1" type="password" placeholder="Bitte geben sie ihr neues Passwort ein" required="true" />
	</p>
	
	<p>
		<label for="pass2">Passwort wiederholen:</label><br/>
		<input name="pass2" type="password" placeholder="Wiederholen sie bitte ihr Passwort" required="true" />
	</p><br/>
	
	<p>
		<img src="captcha.php" id="captcha" />
		<a href="#" onclick="document.getElementById('captcha').src='captcha.php?'+Math.random(); document.getElementById('captcha-form').focus();" id="change-image" style="position: absolute; margin-left: 10px;">Nicht lesbar? Text ändern.</a>
		<input type="text" autofill="off" placeholder="Bitte hier den linken Text eingeben" name="captcha" id="captcha-form" />
	</p><br/>
	
	<p>
		<input id="demo_box_1" class="css-checkbox" type="checkbox" name="agb" value="yes" />
		<label for="demo_box_1" name="demo_lbl_1" class="css-label">&nbsp;<b>Ja</b>, Ich akzeptiere die <a target="_blank" href="./terms&ref=register">Nutzungsbedingungen</a> von Uptime-Monitor.</label>
	</p><br/><br/><hr/><br/><br/>
	
	<p>
		<input class="button" name="submit" type="submit" value="Jetzt kostenloses Konto erstellen" /> 
	</p>
	
</form>
<?php include 'inc/html-bottom.inc.php'; ?>