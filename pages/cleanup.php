<?php
$stmt_name = $con_pdo->prepare('SELECT * FROM `monitors`');
$stmt_name->execute( array( ) );
while($result_name = $stmt_name->fetch(PDO::FETCH_ASSOC)){
	$nlike .= " AND monitorid != ". $result_name['id'];
}
//echo "DELETE FROM protokoll WHERE ". substr($nlike, 5);
