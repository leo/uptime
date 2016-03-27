<?php	
if(isset($_POST['submit'])){
	// MySQL
	$mysql_login_uptime = array(
		"host" => 'localhost',
		"user" => 'lamp',
		"pass" => 'JolfApjigVacFea',
		"data" => 'lamp_uptime'
	);

	$con_pdo = new PDO('mysql:host='. $mysql_login_uptime["host"] .';dbname='. $mysql_login_uptime["data"] .'', ''. $mysql_login_uptime["user"] .'', ''. $mysql_login_uptime["pass"] .'');
	
	$stmt_check = $con_pdo->prepare('SELECT count(id) AS count FROM `release` WHERE email=?');
	$stmt_check->execute( array( $_POST['email'] ) );
	$result_check = $stmt_check->fetch(PDO::FETCH_ASSOC);
	
	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		$msg = "Bitte geben Sie eine gültige E-Mail-Adresse ein.";
	}else if($result_check['count'] != 0){
		$msg = "Diese E-Mail-Adresse wurde schon eingetragen.";
	}
	else{	
		$stmt_name = $con_pdo->prepare('INSERT INTO `release` (email) VALUES (?)');
		$stmt_name->execute( array( $_POST['email'] ) );
		$result_name = $stmt_name->fetch(PDO::FETCH_ASSOC);
		
		$msg = "Ihre E-Mail-Adresse wurde erfolgreich eingetragen!";
	}
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
	<title>Uptime-Monitor - Willkommen</title>
	<meta name="robots" content="index">
	<meta name="revisit-after" content="3 days" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link href='https://fonts.googleapis.com/css?family=Noto+Sans' rel='stylesheet' type='text/css'>
	<meta property="og:image" content="/img/facebook.png" />
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<script type="text/javascript">
	function toggle(control){
		var elem = document.getElementById(control);
	
		if(elem.style.display == "none"){
			elem.style.display = "block";
		}else{
			elem.style.display = "none";
		}
	}
	</script>
	<style>
	body {
		background-color: #40434C;
		font-family: 'Noto Sans',sans-serif;
		font-size: .9em;
		font-weight:300;
		padding: 0;
		margin: 0;
	}
	
	div.wrapper {
		margin: auto;
		width: 500px;
		margin-top: 12%;
		color: #FFF;
		margin-left: 300px;
	}
	
	input[type="text"] {
		width: 270px;
		padding: 10px;
		background-color: #FFF;
		border: 0px;
		margin-top: 20px;
		box-shadow: none;
		outline: none;
		margin-right: 0px;
	}
	
	input[type="submit"] {
		background-color: #DC7075;
		color: #FFF;
		background-repeat: no-repeat;
		background-position: center center;
		border: 0px none;
		height: 36px;
		width: 80px;
		cursor: pointer;
		margin-top: 20px;
		position: absolute;
		margin-left: 0px;
	}
	
	img.logo {
		width: 40px;
	}
	
	a { color: #DC7075; }
	
	p#info {
		background-color: #919191;
		padding: 10px;
		font-size: 13px;
	}
	</style>
</head>

<body>

<div class="wrapper">

<a href="/release.php">
	<img class="logo" src="/img/release/logo.png" />
</a>

<h2>Herzlich Willkommen!</h2>

<p>Derzeit befindet sich unser Projekt noch in der Aufbau-Phase. M&ouml;chten sie informiert werden, sobald unsere Seite online geht?
Dann tragen sie doch ihre E-Mail-Adresse im untenstehenden Feld ein, und sie werden automatisch von unserem System 
informiert, wenn die Seite nutzbar ist. <br/><br/><small>(Ihre E-Mail-Adresse wird direkt nach der einmaligen Benachrichtigung entfernt.)</small></p>

<a href="javascript:toggle('info')">Was ist Uptime-Monitor&trade; eigentlich?</a>

<p id="info" style="display: none;">Wir haben dieses Projekt geschaffen, um es Webmastern möglichst leicht zu machen, über den Status ihrer Homepage informiert bleiben zu können. Einfach anmelden, gewünschte URL, Domain, oder IP eintragen, und schon wird die Webseite in einem gewünschten Intervall geprüft. Falls ihre Seite offline geht, werden Sie mittels einer E-Mail, einem RSS-Feed, oder einer Twitter-Nachricht informiert. Wahlweiße bieten wir Ihnen auch die Möglichkeit, ihre Meldungen per SMS direkt auf ihr Handy zu erhalten.</p>

<form action="/release.php" method="post">
	<input type="text" placeholder="Ihre E-Mail-Adresse" name="email"></input>
	<input type="submit" value="Eintragen" name="submit" />
</form><br/>

<span style="color: #A4CFF4"><?php if(isset($msg)){echo $msg;} ?></span>

</div>

</body>
</html>