<?php
session_start();
include 'inc/mysql.inc.php';
include 'inc/lang.inc.php';
include 'inc/functions.inc.php';

doAutoLogin($con_pdo);

if(isset($_SESSION['UserID'])){
	$stmt_user = $con_pdo->prepare('SELECT * FROM `users` WHERE id=? LIMIT 1');
	$stmt_user->execute( array( $_SESSION['UserID'] ) );
	$user = $stmt_user->fetch(PDO::FETCH_ASSOC);
}

function sendLogger($status, $user, $args="none"){
	mysql_query("INSERT INTO log (status, user, args, timestamp) VALUES ('$status', '$user', '$args', '". time() ."')");
}


$params = explode("/", $_GET['q']);
$page = $_GET['a'];
$pagesec = $_GET['q'];

function headertop($url, $time=0){
	echo '<meta http-equiv="refresh" content="'.$time.'; URL='.$url.'">';
}

$page = str_replace('/', '-', $page);

if($page .'/'. $params[1] == 'account/delete'){
	$inc_page = "Account l&auml;schen";
	
}else if($page .'/'. $params[1] == 'account/features'){
	$inc_page = "Account Zusatzfunktionen";
	
}else if($page .'/'. $params[1] == 'account/overview'){
	$inc_page = "Account &Uuml;bersicht";
	
}else if($page .'/'. $params[1] == 'account/password'){
	$inc_page = "Passwort &auml;ndern";

}else if($page .'/'. $params[1] == 'account/edit'){
	$inc_page = "Kontakt-Daten verwalten";
	
}else if($page .'/'. $params[1] == 'account/notify'){
	$inc_page = "Meldungen";
	
}else if($page .'/'. $params[1] == 'account/api'){
	$inc_page = "Entwickler-API";
	
}else if($page .'/'. $params[1] .'/'. $params[2] == 'account/api/v1'){
	$inc_page = "API-Doku v1";
	
}else if($page .'/'. $params[1] == 'account/settings'){
	$inc_page = "Account Einstellungen";
	
}else if($page .'/'. $params[1] == 'monitor/all'){
	$inc_page = "Monitor Optionen";
	
}else if($page .'/'. $params[1] == 'monitor/api'){
	$inc_page = "Monitor API";
	
}else if($page .'/'. $params[1] == 'monitor/create'){
	$inc_page = "Monitor Erstellen";
	
}else if($page .'/'. $params[1] == 'monitor/edit'){
	$inc_page = "Monitor Bearbeiten";
	
}else if($page .'/'. $params[1] == 'monitor/notify'){
	$inc_page = "Monitor Benachrichtigungen";
	
}else if($page .'/'. $params[1] .'/'. $params[2] == 'monitor/notify/sms'){
	$inc_page = "Monitor SMS";
	
}else if($page .'/'. $params[1] == 'monitor/ping'){
	$inc_page = "Monitor Pingeinstellungen";
	
}else if($page .'/'. $params[1] == 'monitor/remove'){
	$inc_page = "Monitor l&ouml;schen";
	
}else if($page .'/'. $params[1] == 'monitor/settings'){
	$inc_page = "Monitor &Uuml;bersicht";
	
}else if($page .'/'. $params[1] == 'account/log'){
	$inc_page = "Account Protokoll";
	
}else if($page .'/'. $params[1] == 'sms/providers'){
	$inc_page = "Unterst&uuml;tze SMS-Anbieter";
	
}else if($page .'/'. $params[1] == 'account/premium'){
	$inc_page = "Premium-Erweiterung buchen";
	
}else if($page == 'register'){
	$inc_page = $lang['pagename-registernow'];
	
}else if($page == 'support'){
	$inc_page = "Support";
	
}else if($page == 'terms'){
	$inc_page = "Nutzungsbedingungen";
	
}else if($page == 'monitor'){
	$inc_page = $lang['pagename-monitorall'];
	
}else if($page == '404'){
	$inc_page = "Seite nicht gefunden";
	
}else if($page == 'about'){
	$inc_page = "&Uuml;ber Uptime-Monitor.net";
	
}else if($page == 'account'){
	$inc_page = $lang['pagename-ownaccount'];
	
}else if($page == 'features'){
	$inc_page = $lang['pagename-features'];
	
}else if($page == 'contact'){
	$inc_page = "Kontakt";
	
}else if($page == 'home'){
	$inc_page = $lang['pagename-startpage'];
	
}else if($page == 'imprint'){
	$inc_page = "Impressum";
	
}else if($page == 'login'){
	$inc_page = $lang['pagename-loginnow'];
	
}else if($page == 'logout'){
	$inc_page = "Abmelden";
	
}else{
	$inc_page = "Title not found";
}

if(empty($page)){
	header('Location: /home');
}else if(!empty($params[1]) && !empty($params[2]) && file_exists('pages/'. $page .'/'. $params[1] .'-'. $params[2] .'.php')){
	include 'pages/'. $page .'/'. $params[1] .'-'. $params[2] .'.php';
}else if($page == 'language'){
	$expire=time()+60*60*24*30;
	setcookie("language", $params[1], $expire);
	header('Location: '. $_SERVER['HTTP_REFERER']);
}else if(!empty($params[1]) && file_exists('pages/'. $page .'/'. $params[1] .'.php')){
	include 'pages/'. $page .'/'. $params[1] .'.php';
}else if(empty($params[1]) && file_exists('pages/'. $page. '.php')){
	include 'pages/'. $page. '.php';
}else{
	header('Location: /404');
}

/*
if(empty($page)){
	header('Location: /home');
}else if(!file_exists('pages/'.$page.'.php')){
	header('Location: /404');
}else if(file_exists('pages/'.$page.'.php') && isset($page)){
	include 'pages/'.$page.'.php';
}else{
	header('Location: /'.$page);
}*/

?>