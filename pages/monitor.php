<?php
if(!isset($_SESSION['UserID'])){
$_SESSION['reffer'] = $page;
	$_SESSION['reffer'] = $page;
	header('Location: /login');
}else{

include 'monitor-extra.php';

function shortText($string,$lenght) {
    if(strlen($string) > $lenght) {
        $string = substr($string,0,$lenght)."...";
        $string_ende = strrchr($string, " ");
        $string = str_replace($string_ende," ...", $string);
    }
    return $string;
}

?>
<?php include 'inc/html-top.inc.php'; ?>
<h2><?php echo $lang['pagename-monitorall']; ?></h2>
<?php
$stmt_stc = $con_pdo->prepare('SELECT count(id) AS count FROM monitors WHERE owner=?');
$stmt_stc->execute( array( $_SESSION['UserID'] ) );
$result_stc = $stmt_stc->fetch(PDO::FETCH_ASSOC);

if($result_stc['count']>0){
?>
<? if(isset($_SESSION['apimsg'])){echo $_SESSION['apimsg']; unset($_SESSION['apimsg']);} ?>
<?php if(isset($msg)){ echo $msg; } ?>
<p><span>Hier finden sie eine kurze Übersicht über alle Adressen, die Sie bei uns angemeldet haben.<br/>
Unser System prüft den Status der angegebenen Domains und IPs im jeweilig bestimmten Intervall.</span></p><br/>
	<p class="legend">Legende zum 24h Verlauf:&nbsp;&nbsp;&nbsp;
	<span class="online">Online</span> &nbsp;-&nbsp;
	<span class="offline">Offline</span> &nbsp;-&nbsp;
	<span class="paused">Pausiert</span> &nbsp;-&nbsp;
	<span class="unchecked">Ungeprüft</span></p><br>
	<br>

<?php

$stmt_monitors = $con_pdo->prepare('SELECT * FROM monitors WHERE owner=? ORDER BY name ASC');
$stmt_monitors->execute( array( $_SESSION['UserID'] ) );
while($row = $stmt_monitors->fetch(PDO::FETCH_ASSOC))
  {
  $vanish = false;
  
	$stmt_monitor = $con_pdo->prepare('SELECT * FROM protokoll WHERE monitorid=? ORDER BY id DESC LIMIT 1');
	$stmt_monitor->execute( array( $row['id'] ) );
	$result_monitor = $stmt_monitor->fetch(PDO::FETCH_ASSOC);
  
  if($result_monitor['status']==1){
	$status = "<img src=\"/img/bullet_green.png\" title=\"Aktiv\"> ";
	$txtstatus = '<small> &#8211 '. @round(100-(100/(uptime_up($con_pdo, $row['id'])+uptime_down($con_pdo, $row['id']))*uptime_down($con_pdo, $row['id'])), 2) .'% online</small>';
  }else if($result_monitor['status']==2){
	$status = "<img src=\"/img/bullet_red.png\" title=\"Inaktiv\"> ";
	$txtstatus = '<small> &#8211 '. @round(100-(100/(uptime_up($con_pdo, $row['id'])+uptime_down($con_pdo, $row['id']))*uptime_down($con_pdo, $row['id'])), 2) .'% online</small>';
  }else if($result_monitor['status']==3){
	$status = "<img src=\"/img/bullet_blue.png\" title=\"Pausiert\"> ";
	$txtstatus = '<small> &#8211 '. @round(100-(100/(uptime_up($con_pdo, $row['id'])+uptime_down($con_pdo, $row['id']))*uptime_down($con_pdo, $row['id'])), 2) .'% online</small>';
  }else{
	$status = "<img src=\"/img/bullet_black.png\"> ";
	$txtstatus = '<small> &#8211 ungepr&uuml;ft</small>';
	$vanish = true;
  }
  
  echo '<div id="monitor">
	<div class="top">
	<div class="lefted">
		<span>'. $status ." ". $row['name'] .'</span>'. $txtstatus  .'
	</div>
	<div class="righted">';
	
	echo '<a class="moni-sett editnow" href="/monitor/edit/'. $row['id'] .'" title="Daten verwalten">E</a>';
	echo '<a class="moni-sett setspeed" href="/monitor/speed/'. $row['id'] .'" title="Prüfungs-Intervall editieren">T</a>';

	if($row['status'] == 1){ 
		echo '<a class="moni-sett pausenow" href="/monitor/query/'. $row['id'] .'/stop" title="&Uuml;berwachung anhalten">S</a></div>'; } else {
		echo '<a class="moni-sett playnow" href="/monitor/query/'. $row['id'] .'/start" title="&Uuml;berwachung fortsetzen">P</a></div>'; 
	}
	
	
	
	echo '</div>'. show_progress($con_pdo, $row['id'], '288');
			
	echo '<div class="bottom">';
	echo '<div style="float:right;">';	
		$vor = time();
		echo "<span style=\"font-size:10px; color:#888;\">". date("d.m H:i", $vor) ."</span>";			
	echo '</div>';
		$vor = time()-24*60*60;
		echo "<span style=\"font-size:10px; color:#888;\">". date("d.m H:i", $vor) ."</span>";					
	echo '</div>';
		
	echo '</div>';
				
					
  }

?>
<p class="mini" style="margin-top: 35px !important;">Der obenstehende 24h Uptime-Verlauf befindet sich derzeit noch im BETA-Stadium. Sollten Sie Fehler entdecken, melden Sie diese bitte direkt an team@uptime-monitor.net. Wir werden ihre Nachricht dann so schnell wie möglich bearbeiten, und den entsprechenden Fehler beheben.</p>

<?php } else { ?>
<p>Sie haben noch keinen Monitor. <a href="/monitor/create">Klicken sie hier, um einen Monitor zu erstellen.</a></p>
<?php }} ?>
<?php include 'inc/html-bottom.inc.php'; ?>