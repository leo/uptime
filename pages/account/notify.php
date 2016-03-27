<?php

if(!isset($_SESSION['UserID'])){
	$_SESSION['reffer'] = $page;
	header('Location: /login');
}
		

$istvorhanden = false;

$nr = array('0155', '0157', '0161', '0163', '0164', '0177', '0178');

function StartsWithOld($Haystack, $Needle){ 
    return substr($Haystack, 0, strlen($Needle)) == $Needle;
}

$ExampleText = $_POST['sms'];

foreach($nr as $val){
	if (StartsWithOld($ExampleText, $val)){
    $istvorhanden = true;
	}
}

if(isset($_POST['submit']) == "Speichern"){
	// E-MAIL	
	if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		$stmt_p = $con_pdo->prepare('UPDATE `users` SET p_email=? WHERE id=?');
		$check_p = $stmt_p->execute( array( $_POST['email'], $_SESSION['UserID'] ) );
		
		if($check_p){
			$msg = "<p class=\"success fade\">Ihre E-Mail Einstellungen wurden gespeichert.</p>";
		}else{
			$msg = "<p class=\"error fade\">Ihre E-Mail Einstellungen wurden nicht gespeichert. (MySQL Error #1)</p>";
		}
	}else if($_POST['email']==""){
		$stmt_p = $con_pdo->prepare('UPDATE `users` SET p_email=? WHERE id=?');
		$check_p = $stmt_p->execute( array( '', $_SESSION['UserID'] ) );
		
		if($check_p){
			$msg = "<p class=\"info fade\">Sie werden nun nicht mehr per E-Mail benachrichtigt.</p>";
		}else{
			$msg = "<p class=\"error fade\">Ihre E-Mail Einstellungen wurden nicht gespeichert. (MySQL Error #2)</p>";
		}
	}
	
	// SMS
	if($_POST['sms'] != ""){
		if($user['p_sms_active'] != 1 && $_POST['sms'] != $user['p_sms']){	
			
			
			if($istvorhanden == true){
				$code = rand(1111,9999);
			
				$stmt_p = $con_pdo->prepare('UPDATE `users` SET p_sms=?, p_sms_key=? WHERE id=?');
				$check_p = $stmt_p->execute( array( $_POST['sms'], $code, $_SESSION['UserID'] ) );
				
				function sendSMS($text, $nr){
					$connection = ssh2_connect('codingdev.dlinkddns.com', 22);
					ssh2_auth_password($connection, 'root', 'Ak7GK2dZdoY9AVJ9');

					$stream = ssh2_exec($connection, 'sudo -s && echo "'. $text .'" | gammu-smsd-inject TEXT '.$nr);
					
					header('Location: /monitor/notify-sms');
				}
				
				echo sendSMS("Bitte bestätigen sie Ihre Telefonnummer mit dem Code $code.\n\nMfG.\nUptime-Monitor", $_POST['sms']);
				
				if($check_p){
					$msg2 = "<p class=\"success fade\">Ihre SMS Einstellungen wurden gespeichert.</p>";
				}else{
					$msg2 = "<p class=\"error fade\">Ihre SMS Einstellungen wurden nicht gespeichert. (MySQL Error #1)</p>";
				}
			}else{
				$msg2 = "<p class=\"error fade\">Ihre Telefonnummer ist nicht gültig.</p>";
			}
		}else{
			$msg2 = "<p class=\"info fade\">Die Nummer ist bei uns registriert.</p>";
		}
	}else if($_POST['sms'] == ""){
		$stmt_p = $con_pdo->prepare('UPDATE `users` SET p_sms=?, p_sms_key=?, p_sms_active=? WHERE id=?');
		$check_p = $stmt_p->execute( array( '', '0', '0', $_SESSION['UserID'] ) );
		
		if($check_p){
			$msg2 = "<p class=\"info fade\">Sie werden aktuell nicht per SMS benachrichtigt.</p>";
		}else{
			$msg2 = "<p class=\"error fade\">Ihre SMS Einstellungen wurden nicht gespeichert. (MySQL Error #2)</p>";
		}
	}
}

$stmt_user = $con_pdo->prepare('SELECT * FROM `users` WHERE id=? LIMIT 1');
$stmt_user->execute( array( $_SESSION['UserID'] ) );
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);
?>
<?php include 'inc/html-top.inc.php'; ?>
<h2>Benachrichtigungen verwalten</h2>
<?php if(isset($msg)){echo $msg;} ?>
<?php if(isset($msg2)){echo $msg2;} ?>
<form action="/account/notify" method="post">
	<p>Hier können sie Einstellungen vornehmen, um sie im Notfall zu benachrichtigen wenn ihre Webseite Offline geht. Sie werden auch benachrichtigt, wenn ihre Webseite wieder Online geht.</p><br>
	
	<br/><hr/><br/><br/>
	
	<table class="notify-settings">
	<tbody>
		<tr>
			<td>E-Mail-Adresse:<br><input name="email" type="text" value="<?php echo $user['p_email']; ?>" placeholder="name@domain.tld" /></td>
			<td><input id="email" class="css-checkbox" type="checkbox" name="agb" value="yes"/>
			<label for="email" name="demo_lbl_1" class="css-label">&nbsp;Aktivieren ?</label></td>
		</tr>
		
		<tr>
			<td>SMS-Nachricht (<a href="/sms-providers&ref=notify">Unterstützte Anbieter</a>)<?php if($user['p_sms_active']==1){echo " | (Telefonnummer bestätigt)";}else if($user['p_sms_active'] == 0 && $user['p_sms'] != ""){echo " | (<a href=\"/monitor/notify-sms\">Telefonnummer nicht bestätigt</a>)";} ?>:<br><input <?php if ($user['premium']==1) { } else { echo 'disabled="true"'; } ?>name="sms" type="text" value="<?php echo $user['p_sms']; ?>" placeholder="z.B.: +491234567890" /></td>
			<td><input id="sms-msg" class="css-checkbox" type="checkbox" name="agb" value="yes"/>
			<label for="sms-msg" name="demo_lbl_1" class="css-label">&nbsp;Aktivieren ?</label></td>
		</tr>
		
		<tr>
			<td>Twitter-Nachricht:<br><input name="twitter" type="text" value="<?php echo $user['p_twitter']; ?>" placeholder="z.B.: @uptime-monitor" /></td>
			<td><input id="twitter" class="css-checkbox" type="checkbox" name="agb" value="yes"/>
			<label for="twitter" name="demo_lbl_1" class="css-label">&nbsp;Aktivieren ?</label></td>
		</tr>
	</tbody>
</table>
<br/><hr/><br/>

<br/><p><input class="button" name="submit" type="submit" value="Einstellungen speichern" /></p>
</form>
<?php include 'inc/html-bottom.inc.php'; ?>