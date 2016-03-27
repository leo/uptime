<?php

if(!isset($_SESSION['UserID'])){
	$_SESSION['reffer'] = $page;
	header('Location: /login');
}

$stmt_year = $con_pdo->prepare('SELECT * FROM stats ORDER BY id DESC LIMIT 365');
$stmt_year->execute();
while($result_year = $stmt_year->fetch(PDO::FETCH_ASSOC)){ $year = $year + $result_year['connections']; }

$stmt_month = $con_pdo->prepare('SELECT connections FROM `stats` ORDER BY `id` DESC LIMIT 30');
$stmt_month->execute();
while($result_month = $stmt_month->fetch(PDO::FETCH_ASSOC)){ $month = $month + $result_month['connections']; }

$stmt_week = $con_pdo->prepare('SELECT connections FROM `stats` ORDER BY `id` DESC LIMIT 7');
$stmt_week->execute();
while($result_week = $stmt_week->fetch(PDO::FETCH_ASSOC)){ $week = $week + $result_week['connections']; }

$stmt_day = $con_pdo->prepare('SELECT connections FROM `stats` ORDER BY `id` DESC LIMIT 1');
$stmt_day->execute();
while($result_day = $stmt_day->fetch(PDO::FETCH_ASSOC)){ $day = $day + $result_day['connections']; }

?>

<?php include 'inc/html-top.inc.php'; ?>

<h2>Premium-Erweiterung buchen</h2>

<p>Selbstverständlich müssen auch wir unsere Infrastruktur irgendwie finanzieren, bzw. unsere Dienstleistung aufrecht erhalten.
Mit dem Kauf eines Premium-Accounts für 1 Jahr unterstützen sie unser Projekt. Wir können unsere Leistung somit weiterhin aufrecht erhalten,
sowie weitere Funktionen einbauen und leichter auf Kundenwünsche eingehen.</p><br/><br/><br/>

<b style="font-size: 14px;">Allgemeine Vorteile:</b>
<ul class="squared features">
	<li>Benachrichtigung mittels einer SMS-Nachricht</li>
	<li>Bis zu 15 kostenfreie SMS-Meldungen pro Monat.</li>
	<li>Adressen-Prüfung pro Minute oder alle 5 Minuten verfügbar.</li>
	<li>Hinzufügen von bis zu 50 Monitoren zum eigenen Konto</li>
</ul><br/><br/>

<b style="font-size: 14px;">Vorteile für Entwickler:</b>
<ul class="squared features">
	<li>Aufhebung des Abfrage-Limites des eigenen API-Schlüssels</li>
	<li>Weitere Informationen über Monitore mittels API-Schnittstelle</li>
	<li>Persönlicher Daten-Zugang mittels API v2 (Long-Term Support)</li>
</ul><br/><br/>

<img class="pro-superman" src="/img/superman.png" />

<hr/><br/><br/>

<p>Derzeit verwaltet unsere weltweit gefestigte Infrastruktur circa ...</p><br/>

... <b><?php echo number_format($day, 0, ',', '.'); ?></b> Abfragen am Tag, <b><?php echo number_format($week, 0, ',', '.'); ?></b> in der Woche, sowie ganze <b style="font-size: 16px;"><?php echo number_format($year, 0, ',', '.'); ?></b> direkte Abfragen pro Jahr!<br/><br/>
Das ganze Vorhaben erfordert eine sehr starke und natürlich auch kontinuirlich stabile Infrastruktur. Uptime-Monitor hat derzeit keine Sponsoren oder anderweitige Unterstützer die das Projekt fördern.
<br/><br/>In seinem jetzigen Status wird das ganze Projekt voll und ganz durch die Einnahmen finanziert, die durch den Verkauf von Premium-Paketen generiert werden können. In diesem Sinne bedanken wir uns sehr für jegliche Unterstützung!
Auf Wunsch nehmen wir auch gerne außerhalb unseres Premium-Systems Spenden entgegen.<br/><br/>
<br/><hr/>

<br/><br/>

<p><b>Bitte beachten sie:</b> Sobald sie im Besitz eines Premium-Kontos sind, ist es nicht mehr möglich, ihr Konto mittels ihres Kundenbereiches zu deaktivieren, bzw. zu löschen.
Somit muss dieser Wunsch nach Anfrage von einem authorisierten Support-Mitarbeiter getätigt werden. (Dies dient ihrer eigenen Sicherheit.)<br/><br/><br/>

<p><b>365 Tage</b> mit all diesen Features für günstige &nbsp;&nbsp;&nbsp;<b style="font-size: 30px;">30 €</b></p><br/>

<form action="/payments.php?item=1&user=<?php echo $_SESSION['UserID']; ?>" method="post" target="_blank" class="pro-form">
	<?php if ($user['premium']==1) { ?>
	<input type="submit" name="submit" disabled="true" class="button buy-pro" value="<?php echo $lang['alreadyhavepronow']; ?>">
	<?php } else { ?>
	<input type="submit"  class="button buy-pro" value="<?php echo $lang['buypremiumnow']; ?>"/>
	<?php } ?>
</form>
<br/>

<p class="mini">Die Zahlung wird sicher von ihrem persönlichen PayPal-Konto zu unseren Servern übertragen. 
Direkt nach Fertigstellung der Transaktion wird ihr Konto bei Uptime-Monitor zum Premium-Konto hochgestuft und sie
erhalten Zugriff auf alle obenstehenden Premium-Features.</p>

<?php include 'inc/html-bottom.inc.php'; ?>