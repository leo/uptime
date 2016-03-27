<?php

$login = doLogin($con_pdo, "preussmaurice@googlemail.com", "waage1995", true);

if($login == 201){
	echo "Kookie";
}else if($login == 200){
	echo "Classic";
}else if($login == 400){
	echo "Pass falsch";
}