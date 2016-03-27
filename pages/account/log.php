<?php

if(!isset($_SESSION['UserID'])){
	$_SESSION['reffer'] = $page;
	header('Location: /login');
}

$stmt_log = $con_pdo->prepare('SELECT *, count(id) AS count FROM log WHERE user=? ORDER BY id DESC');
$stmt_log->execute( array( $_SESSION['UserID'] ) );
$return_log = $stmt_log->fetch(PDO::FETCH_ASSOC);
?>
<?php include 'inc/html-top.inc.php'; ?>
<h2>Account Protokoll</h2>
<?php if($return_log['count'] != 0){ ?>
<p>Hier sehen sie alle Aktionen, die auf Ihrem Account get&auml;tigt werden.</p><br>
<table id="tbl" class="account-log table-striped" style="width:100%">
<thead>
	<tr>
		<th>Datum</th>
		<th>Aktion</th>
	</tr>
</thead>
<tbody>
<?php

while($row = $stmt_log->fetch(PDO::FETCH_ASSOC))
{
	$status = $row['status'];
	$status = eregi_replace("2", "Ihnen wurde eine E-Mail gesendet weil ihr Monitor wieder Online ging.", $status);
	$status = eregi_replace("1", "Ihnen wurde eine E-Mail gesendet weil ihr Monitor wieder Offline ging.", $status);
	echo "<tr><td>". date("d.m.Y H:i:s", $row['timestamp']) . "</td><td>" . $status ."</td></tr>";
}
?>
</tbody>
</table>
<p class="muted">Das Account Protokoll wird nach maximal 7 Tage gel&ouml;scht.</p>
<?php } else { ?>
<p>Der Log ist Leer.</p>
<?php } ?>
<?php include 'inc/html-bottom.inc.php'; ?>