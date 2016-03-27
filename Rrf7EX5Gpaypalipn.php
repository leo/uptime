<?php
/*
ZL2R7Yvh
transaction_subject=Sie haben eine Zahlung erhalten.
payment_date=02:07:06 Jan 13, 2013 PST
payer_email=******@arcor.de
txn_id=*************73V
payment_status=Completed
mc_gross=11.00
use=0
*/
include 'inc/mysql.inc.php';

$tid = $_POST['txn_id'];
$subject = $_POST['transaction_subject'];
$date = $_POST['payment_date'];
$email = $_POST['payer_email'];
$ppstatus = $_POST['payment_status'];
$balance = $_POST['mc_gross'];
$custom = $_POST['custom'];
$item_name = $_POST['item_name'];
$uid = explode('_', $custom);

if($balance == 30.00){
	$stmt_premium = $con_pdo->prepare('UPDATE users SET premium=1, account_smslimit=100 WHERE id=?');
	$check_premium = $stmt_premium->execute( array( $uid[1] ) );
	
	$stmt_postal = $con_pdo->prepare('UPDATE users SET postal=? WHERE id=?');
	$check_premium = $stmt_postal->execute( array( $_POST, $uid[1] ) );
	
	if($check_premium && $check_premium){
		mail("team@uptime-monitor.net", "Zahlungseingang erfolgreich", "Eingang von ".$uid[1]." Euro by $_POST", "From: Uptime-Monitor.net <team@uptime-monitor.net>"); 
	}else{
		mail("team@uptime-monitor.net", "Zahlungseingang fehlerhaft", "Eingang von ".$uid[1]." Euro by $_POST", "From: Uptime-Monitor.net <team@uptime-monitor.net>"); 
	}
}else{
	mail("team@uptime-monitor.net", "Zahlungseingang fehlerhaft", "Eingang von ".$uid[1]." Euro by $_POST", "From: Uptime-Monitor.net <team@uptime-monitor.net>"); 
}
print_r($_POST);


?>