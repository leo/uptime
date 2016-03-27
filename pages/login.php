<?php
if(isset($_POST['submit'])){

	if($_POST['signin'] == "yes"){
		$login = doLogin($con_pdo, $_POST['email'], $_POST['pass'], true);
	}else{
		$login = doLogin($con_pdo, $_POST['email'], $_POST['pass'], false);
	}

	if($login == 201 || $login == 200){
		if($_SESSION['reffer']){
			$ref = $_SESSION['reffer'];
			unset($_SESSION['reffer']);
			header('Location: ./'.$ref);
		}else{
			header('Location: /monitor');
		}
	}else if($login == 400){
		$msg = "<p class=\"error fade\">Die Emailadresse oder das Passwort sind nicht korrekt. Bitte versuchen Sie es erneut.</p>";
	}
} else if(isset($_GET['resend'])){
	$code = rand(1111, 9999);

	$stmt_code = $con_pdo->prepare('UPDATE `users` SET code=? WHERE email=? AND active=0 LIMIT 1');
	$check_code = $stmt_code->execute( array( $code, $_GET['resend'] ) );
	
	$stmt_newcode = $con_pdo->prepare('SELECT *, count(id) AS count FROM `users` WHERE email=? AND active=0 LIMIT 1');
	$check_newcode = $stmt_newcode->execute( array( $_GET['resend'] ) );
	$result_newcode = $stmt_newcode->fetch(PDO::FETCH_ASSOC);
	
	if($check_code && $check_newcode && $result_newcode['count'] == 1){
		$betreff = "Ihr neuer Bestätigungscode";
		$mailtext = "Hallo ". htmlspecialchars($result_newcode['fname']) ." ". htmlspecialchars($result_newcode['lname']) .",

bitte klicken Sie auf den folgenden Link, um Ihr kostenloses Uptime-Monitor Konto zu bestätigen:  

http://uptime-monitor.net/activate&email=". htmlspecialchars($result_newcode['email']) ."&code=". $code ."


Mit freundlichen Grüßen, 

Uptime-Monitor.net 


Dies ist eine Automatische E-Mail, sie können uns via team@uptime-monitor.net erreichen.";
		mail(htmlspecialchars($result_newcode['email']), $betreff, $mailtext, "From: \"Uptime-Monitor.net\" <team@uptime-monitor.net>");
		
		$msg = "<p class=\"success fade\">Wir haben eine bet&auml;tigungsmal gesendet, bitte schauen sie in ihr Postfach.</p>";
	}else{
		$msg = "<p class=\"error fade\">Absenden einer bet&auml;tigungsmal nicht erforderlich!</p>";
	}

}

?>
<?php include 'inc/html-top.inc.php'; ?>
<h2>Anmelden</h2>
<?php if(isset($msg)){echo $msg;} ?>
<?php if(isset($_SESSION['sessionmsg'])){echo $_SESSION['sessionmsg']; unset($_SESSION['sessionmsg']);} ?>
<p>Melden Sie sich an oder <a href="./register">erstellen Sie ein kostenloses Konto</a>.</p><br>
	<form action="./login" method="post">
		<p>E-Mail:<br><input style="width:350px;" name="email" type="text" placeholder="Bitte geben sie hier ihre E-Mail ein" required /></p>
		<p>Passwort:<br><input style="width:350px;" name="pass" type="password" placeholder="Bitte geben sie hier ihr Passwort ein" required /></p>
		<p><input id="demo_box_1" class="css-checkbox" type="checkbox" name="signin" value="yes" /> <label for="demo_box_1" name="demo_lbl_1" class="css-label">Angemeldet bleiben?</label></p>
		<br/><p><input class="button" name="submit" type="submit" value="Anmelden" /> oder <a href="/resetpass">Passwort vergessen?</a></p>
	</form>
	
<?php include 'inc/html-bottom.inc.php'; ?>