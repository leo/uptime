<?php

if(isset($_POST['submit'])){
	if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['subject']) || empty($_POST['message'])){
		$msg = "<p class=\"error fade\">Bitte füllen sie alle Felder aus, da sie Pflicht sind.</p>";
	}
	else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		$msg = "<p class=\"error fade\">Die E-Mail scheint nicht Gültig zu sein.</p>";
	}
	else{
		mail("support@uptime-monitor.net" , htmlspecialchars($_POST['subject']), htmlspecialchars($_POST['message']), "From: \"". htmlspecialchars($_POST['name']) ."\" <". htmlspecialchars($_POST['email']) .">"); 
		$msg = "<p class=\"success fade\">Der support wurde kontaktiert</p>";
	}
}

?><?php include 'inc/html-top.inc.php'; ?>
<h2>Support</h2>
<p>Sie benötigen Hilfe bei der Lösung eines Problems? Dann kontaktieren sie uns doch einfach mittels unserem
Kontaktformulars. Alternativ haben sie natürlich auch die Möglichkeit, uns eine E-Mail zu senden.</p><br/>

<b>E-Mail-Adresse:</b>&nbsp;&nbsp;&nbsp;support@uptime-monitor.net<br/><br/>

<p><b>Achtung:</b> Bitte durchsuchen sie zunächst unsere Dokumentation nach einer Lösung für ihr Problem.<br/>
Durch diese Vorgehensweiße entlasten sie unser Support-Team für dringendere Anfragen.</p>
<br/>
<br/>
<h2>Kontaktformular</h2>
<?php if(isset($msg)){echo $msg;} ?>
<p>Wenn sie Fragen haben, können sie hier direkt eine Frage stellen.</p>
<form action="/contact" method="post">
	<p class="input-block">
		<input type="text" name="name" value="" style="width:260px;" id="contact-subject" placeholder="Ihr Name" required>
		<input type="text" name="email" value="" style="width:260px;" id="contact-email" placeholder="Ihre E-Mail" required>
	</p>

	<p class="input-block">
		<input type="text" name="subject" value="" id="contact-subject" placeholder="Ihr Betreff" required>
	</p>

	<p class="textarea-block">
		<textarea name="message" id="contact-message" rows="6" placeholder="Ihre Nachricht" required></textarea>
	</p>

	<p>
		<input type="submit" name="submit" class="button" value="Absenden">
	</p>
</form>
<br/>
<br/>
<h2>FAQ</h2>

<a href="javascript:toggle('faq-1')">&raquo; Ich brauche mehr als 5 Monitore, was kann ich tun?</a>
<p id="faq-1" style="display: none;">Wir haben das Limit auf 5 Monitore begrenzt, weil wir denken das 5 Monitore reichen. Wenn sie mehr als 5 Monitore brauchen kaufen sie sich die 1 Jahr Premium Option, damit können sie bis zu 50 Monitore erstellen. Wenn sich noch mehr als 50 Monitore brauchen, kontaktiren sie unseren Support.</p>
<br/><br/>

<a href="javascript:toggle('faq-2')">&raquo; Ich erhalte, oder finde keine E-Mail?</a>
<p id="faq-2" style="display: none;">Sollten sie keine E-Mail erhalten haben, überprüfen sie bitte ihr Spam-Postfacch und setzten die E-Mail als Vertrauenswürdig. Überprüfen sich auch nochmal ob sie die E-Mail benachrichtigung im Account aktiviert haben.</p>
<br/><br/>

<a href="javascript:toggle('faq-3')">&raquo; Ich erhalte immer E-Mails vom Uptime-Monitor, wie schalte ich das ab?</a>
<p id="faq-3" style="display: none;">Wenn sie keine Informationsmails über die Monitoren bekommen möchten, können sie dies abschalten. Gehen sie dazu wiefolgt vor:<br><br>1. Melden sie sich an.<br>2. Gehen sie auf Account.<br>3. Dann auf Sofortbenachrichtigungen.<br>4. Ändern oder Entfernen sie die E-Mail<br>5. Speichern sie die Einstellungen.</p>
<br/><br/>

<a href="javascript:toggle('faq-4')">&raquo; Wann geht der Status von ungeprüft auf Aktiv?</a>
<p id="faq-4" style="display: none;">Ein Monitor geht erst dann aktiv wenn er überprüft werden soll, wenn zum Beispiel die Abtastzeit auf 10 Minuten eingestellt ist und sie den Monitor um 12:04 eingetragen haben, wird er um 12:10 Aktiv.</p>
<br/><br/>

<a href="javascript:toggle('faq-5')">&raquo; Was ist das Prüfungsinterval?</a><br>
<p id="faq-5" style="display: none;">Jeder Monitor kann einen eigenes Prüfungsinterval (Abtastzeit) haben. Wird bei einem Monitor zum Beispiel die Abtastzeit auf 10 Minuten gestellt, wird der Monitor alle 10 Minuten die Domain überprüfen.</p>

<?php include 'inc/html-bottom.inc.php'; ?>