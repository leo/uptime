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
	else if(empty($_POST['agb'])){
		$msg = "<p class=\"error fade\">Sie mussen die Nutzungsbedingungen von Uptime-Monitor zustimmen.</p>";
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
			header('Location: /monitor');
		}else{
			$msg = "<p class=\"error fade\">MySQL Error</p>";
		}
	}

}

?>
<?php include 'inc/html-top.inc.php'; ?>
<h2>Bitte vervollständigen sie ihren Account</h2>
<?php if(isset($msg)){echo $msg;} ?>
<form action="/account/complete" method="post">
	<p>Vor- und Nachname:<br><input autocomplete="off" style="width:260px;" name="fname" type="text" value="<?php echo htmlspecialchars($user['fname']); ?>" placeholder="Max" />
	<input autocomplete="off" style="width:260px;" name="lname" type="text" value="<?php echo htmlspecialchars($user['lname']); ?>" placeholder="Mustermann" /></p>
	<p>E-Mail-Adresse:<br><input autocomplete="off" name="email" type="text" value="<?php echo htmlspecialchars($user['email']); ?>" placeholder="email@domain.tld" /></p>
	<p><input id="demo_box_1" class="css-checkbox" type="checkbox" name="agb" value="yes" /> <label for="demo_box_1" name="demo_lbl_1" class="css-label"><b>Ja</b>, Ich akzeptiere die <a target="_blank" href="./terms&ref=register">Nutzungsbedingungen</a> von Uptime-Monitor.</label></label></p>
	<br>
	<p><input class="button" name="submit" type="submit" value="Abschließen" /></p>
</form>
<?php include 'inc/html-bottom.inc.php'; ?>