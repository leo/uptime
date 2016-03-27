<?php

if(!isset($_SESSION['UserID'])){
	$_SESSION['reffer'] = $page;
	header('Location: /login');
}

$email = $user['email'];
$size = 150;

$grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=mm&s=" . $size;


if(isset($_GET['resend'])){
	$code = rand(1111, 9999);

	$stmt_code = $con_pdo->prepare('UPDATE `users` SET code=? WHERE id=? AND active=0 LIMIT 1');
	$check_code = $stmt_code->execute( array( $code, $_SESSION['UserID'] ) );
	
	$stmt_newcode = $con_pdo->prepare('SELECT *, count(id) AS count FROM `users` WHERE id=? AND active=0 LIMIT 1');
	$check_newcode = $stmt_newcode->execute( array( $_SESSION['UserID'] ) );
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
<h2><?php echo $lang['pagename-ownaccount']; ?></h2>
<?php if(isset($msg)){echo $msg;} ?>
<div class="link-list">
<a href="/account/password"><?php echo $lang['changepassword']; ?></a>
<a href="/account/edit"><?php echo $lang['editdetails']; ?></a>
<a href="/account/api"><?php echo $lang['apidokuwiki']; ?></a>
<a href="/account/notify"><?php echo $lang['monitornotifys']; ?></a>
<a href="/account/premium"><?php echo $lang['premiumextend']; ?></a>
<a href="/monitor/create"><?php echo $lang['createmonitor']; ?></a>
</div><br/>
<br/><hr/><br/>
<br><div class="left-account">
<h3><?php echo $lang['relevantaccinfo']; ?></h3>

<b><?php echo $user['fname']; ?> <?php echo $user['lname']; ?></b>
<ul class="squared features">
	<?php if ($user['premium']==1) { ?>
	<li><?php echo $lang['prountilthe']; ?> <?php echo date("d.m.Y", $user['time_buypremium']+60*60*24*365); ?> (<?php echo $lang['sincethe']; ?> <?php echo date("d.m.Y", $user['time_buypremium']); ?>)</li>
	<?php } else { ?>
	<li><?php echo $lang['freeaccstatus']; ?> (<a href="/account/premium"><?php echo $lang['buypremiumnow']; ?></a>)</li>
	<?php } ?>
	<?php if ($user['active']==0) { ?>
	<li><?php echo $lang['accisntveryfied']; ?> (<a href="/account&resend=true"><?php echo $lang['veryfieaccnow']; ?></a>)</li>
	<?php } ?>
	<li><?php echo $lang['dateofacccreate']; ?>: <?php echo date("d.m.Y", $user['time_register']); ?></li>
	<li><?php echo $lang['userslastlogin']; ?>: <?php echo date("d.m.Y H:i:s", $user['time_lastlogin']); ?></li>
	<li><?php echo $lang['numbavailablesms']; ?>: <?php echo $user['account_smslimit']; ?></li>
</ul>
<br/>
<br/><a href="/account/log"><?php echo $lang['showactivitylog']; ?></a>
</div>

<div class="avatar-big">
	<span><?php if ($user['premium']==1) { ?>Premium<?php } else { ?><?php echo $lang['free']; ?><?php } ?></span>
	<img src="<?php echo $grav_url; ?>" alt="<?php echo $user['name']; ?>" />
</div>
<br/><br/>
<?php include 'inc/html-bottom.inc.php'; ?>