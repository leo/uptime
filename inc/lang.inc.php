<?php

include_once ('inc/httplang.inc.php');

$allowed_langs = array ('de', 'en');

$detectlang = lang_getfrombrowser ($allowed_langs, 'de', null, false);


if($detectlang == 'de' && !isset($_COOKIE["language"])){
	include 'lang/de.lang.php';
	$flagicon = '<a href="/language/en"><img src="/img/flags/en.png"></a>';
}
else if($detectlang == 'en' && !isset($_COOKIE["language"])){
	include 'lang/en.lang.php';
	$flagicon = '<a href="/language/de"><img src="/img/flags/de.png"></a>';
}
else if(isset($_COOKIE["language"]) && $_COOKIE["language"] == 'de'){
	include 'lang/de.lang.php';
	$flagicon = '<a href="/language/en"><img src="/img/flags/en.png"></a>';
	
}
else if(isset($_COOKIE["language"]) && $_COOKIE["language"] == 'en'){
	include 'lang/en.lang.php';
	$flagicon = '<a href="/language/de"><img src="/img/flags/de.png"></a>';
	
}
else{
	include 'lang/de.lang.php';
	$flagicon = '<a href="/language/en"><img src="/img/flags/en.png"></a>';
}
?>