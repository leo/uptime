<?php

// MySQL
$mysql_login_uptime = array(
	"host" => 'localhost',
	"user" => 'lamp',
	"pass" => 'JolfApjigVacFea',
	"data" => 'lamp_uptime'
);

$con_pdo = new PDO('mysql:host='. $mysql_login_uptime["host"] .';dbname='. $mysql_login_uptime["data"] .'', ''. $mysql_login_uptime["user"] .'', ''. $mysql_login_uptime["pass"] .'');
?>