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

?>
<?php include 'inc/html-top.inc.php'; ?>
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
<?php include 'inc/html-bottom.inc.php'; ?>