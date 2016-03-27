<?php

if(!isset($_SESSION['UserID'])){
	$_SESSION['reffer'] = $page;
	header('Location: /login');
}

// COUNT
$stmt_co = $con_pdo->prepare('SELECT count(id) AS count, id FROM `monitors` WHERE owner=? AND id=?');
$stmt_co->execute( array( $_SESSION['UserID'], $params[2] ) );
$result_co = $stmt_co->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['submit'])){	
	// MONITOR EXIST
	$stmt_me = $con_pdo->prepare('SELECT count(id) AS count, id FROM `monitors` WHERE owner=? AND url=? AND port=?');
	$stmt_me->execute( array( $_SESSION['UserID'], $_POST['domain'], $_POST['port'] ) );
	$result_me = $stmt_me->fetch(PDO::FETCH_ASSOC);

	if(empty($_POST['name']) || empty($_POST['domain']) || empty($_POST['port'])){
		$msg = "<p class=\"error fade\">Bitte füllen sie alle Felder aus, da sie Pflicht sind.</p>";
	}
	else if($_POST['port'] > 99999){
		$msg = "<p class=\"error fade\">Der angegebene Port wird nicht unterstützt.</p>";
	}
	else if($result_me['count'] != 0 && $params[2] != $result_me['id']){
		$msg = "<p class=\"error fade\">Es gibt schon einen Monitor mit diesen Parametern.</p>";
	}
	else{
		if(!empty($_POST['domain']) && preg_match('/(?=^.{1,254}$)(^(?:(?!\d|-)[a-z0-9\-]{1,63}(?<!-)\.)+(?:[a-z]{2,})$)/i', $_POST['domain']) > 0 || !empty($_POST['domain']) && filter_var($_POST['domain'], FILTER_VALIDATE_IP) || !empty($_POST['domain']) && filter_var($_POST['domain'], FILTER_VALIDATE_URL)){
		
			$stmt_p = $con_pdo->prepare('UPDATE `monitors` SET name=?, url=?, port=? WHERE owner=? AND id=?');
			$check_p = $stmt_p->execute( array( $_POST['name'], strtolower($_POST['domain']), $_POST['port'], $_SESSION['UserID'], $params[2] ) );
			
			if($check_p){
				$msg = "<p class=\"success fade\">Ihre Monitor Einstellungen wurden gespeichert.</p>";
				header('Location: /monitor#jump-'. $result_co['id']);
			}else{
				$msg = "<p class=\"error fade\">Ihre Monitor Einstellungen wurden nicht gespeichert. (MySQL Error #1)</p>";
			}
		}
		else{
			$msg = "<p class=\"error fade\">Diese Webseite wird nicht unterstützt.</p>";
		}
	}
	
}

if(isset($_POST['submit2'])){	
	// MONITOR EXIST
	$stmt_me = $con_pdo->prepare('SELECT count(id) AS count, id FROM `monitors` WHERE id=? AND owner=?');
	$stmt_me->execute( array( $params[2], $_SESSION['UserID'] ) );
	$result_me = $stmt_me->fetch(PDO::FETCH_ASSOC);

	if($result_me['count'] != 0 && $params[2] != $result_me['id']){
		$msg = "<p class=\"error fade\">Es gibt schon einen Monitor mit diesen Parametern.</p>";
	}
	else{
		$stmt_monitor = $con_pdo->prepare('DELETE FROM monitors WHERE id=?');
		$check_monitor = $stmt_monitor->execute( array( $params[2] ) );
		
		$stmt_protokoll = $con_pdo->prepare('DELETE FROM protokoll WHERE monitorid=?');
		$check_protokoll = $stmt_protokoll->execute( array( $params[2] ) );
		
		$stmt_cron = $con_pdo->prepare('DELETE FROM crons WHERE monitor=?');
		$check_cron = $stmt_cron->execute( array( $params[2] ) );

		if($check_monitor && $check_protokoll && $check_cron){
			$_SESSION['apimsg'] = "<p class=\"success fade\">Der ausgew&auml;hlte Monitor wurde erfolgreich von ihrem Konto entfernt.</p>";
			header('Location: /monitor');
		}else{
			$msg = "<p class=\"error fade\">Ein MySQL fehler blokierte die Aktion.</p>";
		}
	}
	
}

// MONITOR ALL
$stmt_id = $con_pdo->prepare('SELECT * FROM `monitors` WHERE owner=? AND id=?');
$stmt_id->execute( array( $_SESSION['UserID'], $params[2] ) );
$result_id = $stmt_id->fetch(PDO::FETCH_ASSOC);
?>
<?php include 'inc/html-top.inc.php'; ?>
<h2>Monitor bearbeiten</h2>
<?php if(isset($msg)){echo $msg;} ?>
<?php
if($result_co['count'] == 1){
?>
<p>Hier können sie den Namen des Monitors ändern.</p><br>
<form action="/monitor/edit/<?php echo $result_id['id']; ?>" method="post">
	
	<p>Name:<br><input name="name" type="text" value="<?php echo $result_id['name']; ?>" /></p>
	<p>Webseite / Port:<br><input style="width:477px;" name="domain" type="text" value="<?php echo $result_id['url']; ?>" />: <input style="width:40px;" name="port" type="text" value="<?php echo $result_id['port']; ?>" /></p>
	<br/><p><input class="button" name="submit" type="submit" value="Einstellungen speichern" /></p>
</form>
<br>
<br>
<hr>
<br>
<br>
<h3>Monitor Löschen</h3>
<p>Hier können sie ihren Monitor löschen</p><br>
<form action="/monitor/edit/<?php echo $result_id['id']; ?>" method="post">
	<p><input class="button" name="submit2" type="submit" value="Jetzt l&ouml;schen" /></p>
</form>
<?php } else { ?>
<p>Leider können wir ihnen zurzeit nur maximal <?php echo $user['limit']; ?> Monitore bereitstellen, <a href="./faq.php?open=4">Sie brauchen Mehr?</a>.</p>
<?php } ?>
<?php include 'inc/html-bottom.inc.php'; ?>