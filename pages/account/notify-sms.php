<?php

if(!isset($_SESSION['UserID'])){
	$_SESSION['reffer'] = $page;
	header('Location: /login');
}

if(isset($_POST['submit']) == "Bestätigen"){
	if(!empty($_POST['code'])){
		$stmt_p = $con_uptime->prepare('UPDATE `users` SET p_sms_active=? WHERE id=? AND p_sms_key=?');
		$check_p = $stmt_p->execute( array( 1, $_SESSION['UserID'], $_POST['code'] ) );
		
		if($check_p){
			$msg = "<p class=\"success fade\">Ihre Telefonnummer wurde bestätigt.</p>";
		}else{
			$msg = "<p class=\"error fade\">Der Code scheint wohl nicht Richtig zu sein.</p>";
		}
	}else{
		$msg = "<p class=\"error fade\">Sie haben kein Code eingegeben.</p>";
	}
}

include 'inc/userprofile.inc.php';
?>
<?php include 'inc/html-top.inc.php'; ?>
<h2>Bitte bestätigen sie Ihre Telefonnummer</h2>
<?php if(isset($msg)){echo $msg;} ?>
<form action="/monitor/notify-sms" method="post">
	<p>Es wird ihnen eine SMS an <b><?php echo $user['p_sms']; ?></b> gesendet, bitte geben sie den Code unten ein:</p><br>
	<p>Bestätigungscode:<br><input name="code" type="text" placeholder="Hier ihren Bestätigungscode eingeben." /></p>
	<p><input class="button" name="submit" type="submit" value="Bestätigen" /></p>
</form>
<?php include 'inc/html-bottom.inc.php'; ?>