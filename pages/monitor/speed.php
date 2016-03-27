<?php

if(!isset($_SESSION['UserID'])){
	$_SESSION['reffer'] = $page;
	header('Location: /login');
}
$stmt_mon = $con_pdo->prepare('SELECT count(id) AS count, id FROM `monitors` WHERE owner=? AND id=?');
$stmt_mon->execute( array( $_SESSION['UserID'], $params[2] ) );
$result_mon = $stmt_mon->fetch(PDO::FETCH_ASSOC);

$stmt_min = $con_pdo->prepare('SELECT min FROM `crons` WHERE monitor=?');
$stmt_min->execute( array( $params[2] ) );
$result_min = $stmt_min->fetch(PDO::FETCH_ASSOC);
	
if($result_mon['id'] == $params[2] && $result_mon['count'] == 1){
	if(isset($_POST['submit'])){
		if($_POST['interval'] == '1' && $user['premium'] == 1){
			$stmt_name = $con_pdo->prepare('UPDATE crons SET min=? WHERE monitor=?');
			$check_name = $stmt_name->execute( array( $_POST['interval'], $params[2] ) );

			if($check_name){
				$msg = "<p class=\"success fade\">Das Pr&uuml;fungs-Intervall des ausgew&auml;hlten Monitors wurde erfolgreich auf 1 Minute ge&auml;ndert.</p>";
			}else{
				$msg = "<p class=\"error fade\">Durch einen MYSQL Fehler wird das Interval nicht ge&auml;ndert.</p>";
			}
		}else if($_POST['interval'] == '5' && $user['premium'] == 1){
			$stmt_name = $con_pdo->prepare('UPDATE crons SET min=? WHERE monitor=?');
			$check_name = $stmt_name->execute( array( $_POST['interval'], $params[2] ) );

			if($check_name){
				$msg = "<p class=\"success fade\">Das Pr&uuml;fungs-Intervall des ausgew&auml;hlten Monitors wurde erfolgreich auf 5 Minuten ge&auml;ndert.</p>";
			}else{
				$msg = "<p class=\"error fade\">Durch einen MYSQL Fehler wird das Interval nicht ge&auml;ndert.</p>";
			}
		}else if($_POST['interval'] == '10'){
			$stmt_name = $con_pdo->prepare('UPDATE crons SET min=? WHERE monitor=?');
			$check_name = $stmt_name->execute( array( $_POST['interval'], $params[2] ) );

			if($check_name){
				$msg = "<p class=\"success fade\">Das Pr&uuml;fungs-Intervall des ausgew&auml;hlten Monitors wurde erfolgreich auf 10 Minuten ge&auml;ndert.</p>";
			}else{
				$msg = "<p class=\"error fade\">Durch einen MYSQL Fehler wird das Interval nicht ge&auml;ndert.</p>";
			}
		}else if($_POST['interval'] == '15'){
			$stmt_name = $con_pdo->prepare('UPDATE crons SET min=? WHERE monitor=?');
			$check_name = $stmt_name->execute( array( $_POST['interval'], $params[2] ) );

			if($check_name){
				$msg = "<p class=\"success fade\">Das Pr&uuml;fungs-Intervall des ausgew&auml;hlten Monitors wurde erfolgreich auf 15 Minuten ge&auml;ndert.</p>";
			}else{
				$msg = "<p class=\"error fade\">Durch einen MYSQL Fehler wird das Interval nicht ge&auml;ndert.</p>";
			}
		}else if($_POST['interval'] == '30'){
			$stmt_name = $con_pdo->prepare('UPDATE crons SET min=? WHERE monitor=?');
			$check_name = $stmt_name->execute( array( $_POST['interval'], $params[2] ) );

			if($check_name){
				$msg = "<p class=\"success fade\">Das Pr&uuml;fungs-Intervall des ausgew&auml;hlten Monitors wurde erfolgreich auf 30 Minuten ge&auml;ndert.</p>";
			}else{
				$msg = "<p class=\"error fade\">Durch einen MYSQL Fehler wird das Interval nicht ge&auml;ndert.</p>";
			}
		}else if($_POST['interval'] == '60'){
			$stmt_name = $con_pdo->prepare('UPDATE crons SET min=? WHERE monitor=?');
			$check_name = $stmt_name->execute( array( $_POST['interval'], $params[2] ) );

			if($check_name){
				$msg = "<p class=\"success fade\">Das Pr&uuml;fungs-Intervall des ausgew&auml;hlten Monitors wurde erfolgreich auf 60 Minuten ge&auml;ndert.</p>";
			}else{
				$msg = "<p class=\"error fade\">Durch einen MYSQL Fehler wird das Interval nicht ge&auml;ndert.</p>";
			}
		}else{
			$msg = "<p class=\"error fade\">Bitte geben sie ein g&uuml;ltiges Interval ein.</p>";
		}
	}	
}else{
		$msg = "<p class=\"error fade\">Ung&uuml;ltiger Monitor</p>";
}

?>
<?php include 'inc/html-top.inc.php'; ?>
<h2>Monitor Pingeinstellungen</h2>
<?php if($result_mon['id'] == $params[2] && $result_mon['count'] == 1){ ?>
	<?php if(isset($msg)){echo $msg;} ?>
	<form action="/monitor/speed/<?php echo $params[2]; ?>" method="post">
	<p>Hier können sie Einstellugen des Updateinterval zum Pingen vornehmen.</p><br>
	<p>Updateinterval:<br><select style="width:645px;" name="interval" size="1">
	<?php if($user['premium'] == 1){ ?>
	<option value="1"<?php if($result_min['min'] == "1"){ echo ' selected'; } ?>>1 <?php echo $lang['minute']; ?></option>
	<option value="5"<?php if($result_min['min'] == "5"){ echo ' selected'; } ?>>5 <?php echo $lang['minutes']; ?></option>
	<?php } ?>
	<option value="10"<?php if($result_min['min'] == "10"){ echo ' selected'; } ?>>10 <?php echo $lang['minutes']; ?> (<?php echo $lang['standard']; ?>)</option>
	<option value="15"<?php if($result_min['min'] == "15"){ echo ' selected'; } ?>>15 <?php echo $lang['minutes']; ?></option>
	<option value="30"<?php if($result_min['min'] == "30"){ echo ' selected'; } ?>>30 <?php echo $lang['minutes']; ?></option>
	<option value="60"<?php if($result_min['min'] == "60"){ echo ' selected'; } ?>>60 <?php echo $lang['minutes']; ?></option>
	</select></p>
	<br/>
	<p><input class="button" name="submit" type="submit" value="Pr&uuml;fungs-Intervall speichern" /></p>
<?php }else{ ?>
	<p>Der Monitor wurde nicht gefunden.</p>
<?php } ?>
<?php include 'inc/html-bottom.inc.php'; ?>