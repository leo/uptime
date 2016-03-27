<?php
function generatePW($length=8)
{

	$dummy	= array_merge(range('0', '9'), range('a', 'z'), range('A', 'Z'), array('#','&','@','$','_','%','?','+'));

	// shuffle array

	mt_srand((double)microtime()*1000000);
	
	for ($i = 1; $i <= (count($dummy)*2); $i++)
	{
		$swap		= mt_rand(0,count($dummy)-1);
		$tmp		= $dummy[$swap];
		$dummy[$swap]	= $dummy[0];
		$dummy[0]	= $tmp;
	}

	// get password

	return substr(implode('',$dummy),0,$length);

}
 
if(isset($_POST['submit'])){

	$stmt_user = $con_pdo->prepare('SELECT count(id) AS count FROM `users` WHERE email=? LIMIT 1');
	$stmt_user->execute( array( $_POST['email'] ) );
	$result_user = $stmt_user->fetch(PDO::FETCH_ASSOC);
	
	if(empty($_POST['email'])){
		$msg = "<p class=\"error fade\">Bitte füllen sie alle Felder aus, da sie Pflicht sind.</p>";
	}
	else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		$msg = "<p class=\"error fade\">Die E-Mail scheint nicht Gültig zu sein.</p>";
	}
	else if($result_user['count'] != 1){
		$msg = "<p class=\"error fade\">Es gibt keinen Account mit dieser E-Mail.</p>";
	}
	else{
		$pass = generatePW(10);
		$passsec = sha1('D,#7#53Mq4y3kWn*@Fy%Z2cC'.$pass);
	
		$stmt_name = $con_pdo->prepare('UPDATE `users` SET password=? WHERE email=?');
		$check_name = $stmt_name->execute( array( $passsec, $_POST['email'] ) );
	
		if($check_name){
			$msg = "<p class=\"success fade\">Wir haben ihnen eine neues Passwort erstellt, bitte schauen sie in ihr Postfach.</p>";
			
			$betreff = "Ihr neues Account Passwort";
			$mailtext = "Hallo,
ihr Account hat jetzt ein neues Passwort.

Das Passwort lautet: $pass

Sie können im Benutzerkonto bei Passort ändern, ein neues Passwort setzten.


Mit freundlichen Grüßen

Uptime-Monitor.net";
			mail(htmlspecialchars($_POST['email']), $betreff, $mailtext, "From: \"Uptime-Monitor.net\" <team@uptime-monitor.net>"); 
		}
	}	
}

?>
<?php include 'inc/html-top.inc.php'; ?>
<h2>Passwort wiederherstellen</h2>
<?php if(isset($msg)){echo $msg;} ?>
<?php if(isset($_SESSION['sessionmsg'])){echo $_SESSION['sessionmsg']; unset($_SESSION['sessionmsg']);} ?>
<p>Bitte geben sie ihre E-Mail ein, um ein neues Passwort zu generieren.</p><br>
<form action="./resetpass" method="post">
	<p>E-Mail:<br><input name="email" type="text" placeholder="Bitte geben sie hier ihre E-Mail ein" required /></p>
	<br/><p><input class="button" name="submit" type="submit" value="Wiederherstellen" /></p>
</form>
<?php include 'inc/html-bottom.inc.php'; ?>