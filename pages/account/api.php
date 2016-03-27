<?php
session_start();
if(!isset($_SESSION['UserID'])){
	$_SESSION['reffer'] = $page;
	header('Location: /login');
}

if($params[2] == 'keyrefresh'){
	
	$apiKey = base64_encode(md5($user['email'].'|'.time()).microtime(true));
	
	$stmt_api = $con_pdo->prepare('UPDATE `users` SET apiKey=? WHERE id=?');
	$check_api = $stmt_api->execute( array( $apiKey, $_SESSION['UserID'] ) );

	if($check_api){
		$_SESSION['apimsg'] = "<div class=\"success fade\">Dein API-Key wurde erfolgreich erneuert.</div>";
		header("Location: /account/api");
	}else{
		$_SESSION['apimsg'] = "<div class=\"error fade\">Dein API-Key wurde nicht erneuert.</div>";
		header("Location: /account/api");
	}
	
}

$stmt_user = $con_pdo->prepare('SELECT * FROM `users` WHERE id=? LIMIT 1');
$stmt_user->execute( array( $_SESSION['UserID'] ) );
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);
?>
<?php include 'inc/html-top.inc.php'; ?>
<h2>API Dokumentation</h2>
<? if(isset($msg)){echo $msg;} ?>
<? if(isset($_SESSION['apimsg'])){echo "test"; unset($_SESSION['apimsg']);} ?>
<p>Willkommen in der API Dokumentation f&uuml;r Entwickler.</p>
<br>
<ul class="squared features">
	<li><a href="/account/api/v1">Dokumentation f&uuml;r die API v1 (Short-Term Support)</a></li>
	<li><a href="/account/api/v2">Dokumentation f&uuml;r die API v2 (Long-Term Support)</a></li>
</ul>
<br>
<br>
<script type="text/javascript">function select_all(text){text.focus();text.select();} </script>
<h3>Ihr persönlicher API-Key</h3>
<p>Dies ist ihr persönlicher Zugangs-Schlüssel für unsere offizelle API-Schnittstelle. Bewahren Sie ihn nur an einem sicheren Ort auf, bzw. verwenden sie ihn nicht in Scripten oder Programmen, deren Sicherheitsfaktor niedrig liegt.</p><br/>
<p><b>API-Key:</b><br/><input style="width:100%; padding: 3px !important;" onclick="select_all(this);" type="text" value="<?php echo $user['apiKey']; ?>"></p>
<p>Sie vermuten jemand besitzt ihren API-Key? <a href="/account/api/keyrefresh">Klicken sie hier um ihn zu erneuern.</a></p>
<br>
<p class="mini" style="font">API's mit einem Short-Term Support sind nur so lange unterst&uuml;tzt wie das System die Daten verarbeiten kann. Kann die API nicht mehr Sytemdaten verarbeiten, wird sie nicht mehr Aktualisiert.</p>
<p class="mini">API's mit einem Long-Term Support werden - so gut wir k&ouml;nnen - immer Aktualisiert. Die API wird so lange aktualisiert, bis dass wir gezwungen sind eine Version h&ouml;her zu gehen.</p>
<?php include 'inc/html-bottom.inc.php'; ?>