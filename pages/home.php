<?php

if(isset($_SESSION['UserID'])){
	header('Location: /monitor');
}

?>
<?php include 'inc/html-top.inc.php'; ?>
<h2>Willkommen beim Uptime-Monitor</h2>			
<p><b>Uptime-Monitor überwacht alle Ihre Webseiten alle 5 Minuten, völlig <b>kostenlos</b>.</b></p>
<p>Wir bieten ihnen die Möglichkeit, alle ihre Webseiten zu überwachen. Zusätzlich sehen sie einen Tagesverlauf ihrer Webseiten und die gesamte Uptime in Prozent und sollte ihre Webseite mal Offline gehen, werden sie direkt per E-Mail informiert.</p>
<br/><p>Für Programmierer gibt es noch eine API-Schnittstelle womit es möglich ist viele verschiedene Parameter abzufragen.<p>
<p><a href="./register">&raquo; Klicken sie hier um einen kostenlosen Account zu erstellen.</a></p>
<br><hr/><br/><br/>
<b>Folgende Features werden noch kommen:</b>
<p>Wir haben uns vorgenommen, unseren Service noch zu verbessern. Folgende Features werden noch kommen:</p><br/>

<p>&bull; RSS, Facebook, Twitter Benachrichtigung.<br>
&bull; Verlauf 7 Tage, 14 Tge, 30 Tage.<br>
&bull; Monitor Starten/Stoppen.</p>
<?php include 'inc/html-bottom.inc.php'; ?>