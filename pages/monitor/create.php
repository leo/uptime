<?php

if(!isset($_SESSION['UserID'])){
	$_SESSION['reffer'] = $page;
	header('Location: /login');
}

function validate_domain_name($domain) {
  // Strip out any http or www
  $search = array('http://', 'www.');
  $replace = array('','');
  $domain = str_replace($search, $replace, $domain);

  $regex = "/^([a-z0-9][a-z0-9\-]{1,63})\.[a-z\.]{2,6}$/i";

  return preg_match($regex, $domain, $matches);
}

// MONITOR LIMIT
$stmt_lm = $con_pdo->prepare('SELECT count(id) AS count FROM `monitors` WHERE owner=?');
$stmt_lm->execute( array( $_SESSION['UserID'] ) );
$result_ml = $stmt_lm->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['submit'])){	
	// MONITOR EXIST
	$stmt_me = $con_pdo->prepare('SELECT count(id) AS count FROM `monitors` WHERE owner=? AND url=? AND port=?');
	$stmt_me->execute( array( $_SESSION['UserID'], $_POST['domain'], $_POST['port'] ) );
	$result_me = $stmt_me->fetch(PDO::FETCH_ASSOC);

	if(empty($_POST['name']) || empty($_POST['domain']) || empty($_POST['port'])){
		$msg = "<p class=\"error fade\">Damit wir ihre Anfrage bearbeiten können, müssen alle Felder ausgefüllt werden.</p>";
	}
	//else if(validate_domain_name($_POST['domain']) == 0 || !filter_var($_POST['domain'], FILTER_VALIDATE_IP) || !filter_var($_POST['domain'], FILTER_VALIDATE_URL)){
	//	$msg = "<p class=\"error fade\">Diese Webseite wird nicht unterstützt.</p>";
	//}
	else if($_POST['port'] > 99999){
		$msg = "<p class=\"error fade\">Der angegebene Port wird nicht unterstützt.</p>";
	}
	else if($result_me['count'] != 0){
		$msg = "<p class=\"error fade\">Es gibt schon einen Monitor mit diesen Parametern.</p>";
	}
	else if($result_ml['count'] >= $user['account_monitorlimit']){
		$msg = "<p class=\"error fade\">Sie besitzen schon mehr als ". $user['account_monitorlimit'] ." Monitore.</p>";
	}
	else{
		if(!empty($_POST['domain']) && preg_match('/(?=^.{1,254}$)(^(?:(?!\d|-)[a-z0-9\-]{1,63}(?<!-)\.)+(?:[a-z]{2,})$)/i', $_POST['domain']) > 0 || !empty($_POST['domain']) && filter_var($_POST['domain'], FILTER_VALIDATE_IP) || !empty($_POST['domain']) && filter_var($_POST['domain'], FILTER_VALIDATE_URL)){
		
			$apiKey = base64_encode(md5($user['email'].'|'.time()).microtime(true));
			$code = rand(1111,9999);
			
			// INSERT MONITOR
			$stmt_monitor = $con_pdo->prepare('INSERT INTO `monitors` (`name`, `url`, `port`, `owner`, `time`) VALUES (?, ?, ?, ?, ?)');
			$check_monitor = $stmt_monitor->execute( array( $_POST['name'], strtolower($_POST['domain']), $_POST['port'], $_SESSION['UserID'], time() ) );
			
			// GET MONITOR ID
			$stmt_monitorid = $con_pdo->prepare('SELECT count(id) AS count, id FROM `monitors` WHERE owner=? AND url=? AND port=?');
			$stmt_monitorid->execute( array( $_SESSION['UserID'], $_POST['domain'], $_POST['port'] ) );
			$result_monitorid = $stmt_monitorid->fetch(PDO::FETCH_ASSOC);
			
			// INSERT CRON			
			$stmt_cron = $con_pdo->prepare('INSERT INTO `crons` (min, monitor, active) VALUES (5, ?, 1)');
			$check_cron = $stmt_cron->execute( array( $result_monitorid['id'] ) );

			if($check_cron && $check_monitor){
				$msg = "<p class=\"success fade\">Ihr Monitor wurde erstellt und ist innerhalb von 5 Minuten aktiv.</p>";	
			}else{
				$msg = "<p class=\"error fade\">Durch einen MySQL Error konnte der Monitor nicht erstellt werden.</p>";	
			}
		
		}
		else{
			$msg = "<p class=\"error fade\">Diese Webseite wird nicht unterstützt.</p>";
		}
	}
}

?>
<?php include 'inc/html-top.inc.php'; ?>
<h2>Monitor erstellen</h2>
<?php if(isset($msg)){echo $msg;} ?>
<?php
if($result_ml['count'] <= $user['account_monitorlimit']){
?>
<p>Um einen neuen Monitor zu ihrer Überwachungs-Liste hinzuzufügen, füllen Sie bitte einfach die nachstehenden Felder aus. Als Webseiten-Verbindung wird sowohl eine Domain, als auch eine IPv4-Adresse (mit Port) akzeptiert.</p><br>
<form action="/monitor/create" method="post">
	<p>Name:<br><input name="name" maxlength="52" type="text" placeholder="z.B.: 'Mein eigene Homepage'" /></p>
	<p>Webseite / Port:<br><input style="width:477px;" name="domain" type="text" placeholder="Domain (example.com), IP (123.123.123.123) oder URL (http://..., https://...)" />: <input style="width:40px;" name="port" type="text" maxlength="4" value="80" /></p>
	<br/><p><input class="button" name="submit" type="submit" value="Neuen Monitor hinzufügen" /></p>
</form>
<?php } else { ?>
<p>Leider können wir ihnen zurzeit nur maximal <?php echo $user['account_monitorlimit']; ?> Monitore bereitstellen, <a href="./faq.php?open=4">Sie brauchen Mehr?</a>.</p>
<?php } ?>
<?php include 'inc/html-bottom.inc.php'; ?>