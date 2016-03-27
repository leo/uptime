<!DOCTYPE html>
<html lang="de">
<head>
	<title>Uptime-Monitor - <?php echo $inc_page; ?></title>
	<meta name="robots" content="index">
	<meta name="revisit-after" content="3 days" />
	<meta name="author" content="Maurice Preuß, Leonard Lamprecht, Rene Preuß">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="/css/style.css" media="screen" />
	<meta name="description" content="Wir bieten ihnen die Möglichkeit, alle ihre Webseiten zu überwachen. Zusätzlich sehen sie einen Tagesverlauf ihrer Webseiten und die gesamte Uptime in Prozent und sollte ihre Webseite mal Offline gehen, werden sie direkt per E-Mail informiert.">
	<meta name="keywords" content="Domain, IP, URL, HTTP, HTTPS, Uptime, Monitor, Monitoring, Status, API, Überwachen, Tracking, Health, PHP, JSON, Kostenlos, Email, E-Mail, Notify, Informiren, Information">
	<link href='https://fonts.googleapis.com/css?family=Noto+Sans' rel='stylesheet' type='text/css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script type="text/javascript" src="/js/tooltipsy.min.js"></script>
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-34936474-4']);
	  _gaq.push(['_setDomainName', 'uptime-monitor.net']);
	  _gaq.push(['_trackPageview']);

	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	function toggle(control){
		var elem = document.getElementById(control);
	
		if(elem.style.display == "none"){
			elem.style.display = "block";
		}else{
			elem.style.display = "none";
		}
	}

	</script>
</head>

<body onload="$('.fade').fadeIn('slow');">

<div id="admin-bar">
	<div id="admin-content">
	<?php if(isset($_SESSION['UserID'])){ ?><a href="/account/edit"><?php echo $user['fname']. " " .$user['lname']; ?></a><?php if ($user['premium']==1) { echo '<span class="pro-badge">Pro</span>'; } ?><?php } else { ?>Nicht angemeldet<?php } ?>
		<div style="float:right;" id="admin-bar-menu">
			<?php if(isset($_SESSION['UserID'])){ ?><a class="logout-btn" href="/logout&confirm=<?php echo $_SESSION['UserSess']; ?>"><?php echo $lang['topbar-logout']; ?></a><?php } else { ?><a href="/login"><?php echo $lang['topbar-login']; ?></a><?php } ?><!-- - <?php echo $flagicon; ?> -->
		</div>
	</div>
</div>

<div class="header">
</div>
 

<div id="wrapper">

<ul id="navi">	
	<?php if(isset($_SESSION['UserID'])){ ?>
	<li <?php if($page == 'monitor'){echo 'class="active"';} ?>><a href="/monitor"><?php echo $lang['navi-survey']; ?></a></li>
	<li <?php if($page == 'account' || $page == 'account-password' || $page == 'account-delete' || $page == 'account-features' || $page == 'account-charge'){echo 'class="active"';} ?>><a href="/account"><?php echo $lang['navi-account']; ?></a></li>
	<li <?php if($page == 'support'){echo 'class="active"';} ?>><a href="/support"><?php echo $lang['navi-support']; ?></a></li>
	<?php }else{ ?>
	<li <?php if($page == 'home'){echo 'class="active"';} ?>><a href="/home"><?php echo $lang['navi-home']; ?></a></li>
	<li <?php if($page == 'features'){echo 'class="active"';} ?>><a href="/features"><?php echo $lang['navi-about']; ?></a></li>
	<li <?php if($page == 'login'){echo 'class="active"';} ?>><a href="/login"><?php echo $lang['navi-login']; ?></a></li>
	<li <?php if($page == 'contact'){echo 'class="active"';} ?>><a href="/contact"><?php echo $lang['navi-contact']; ?></a></li>
	<?php } ?>
</ul>

<div id="inwrap">

<div id="content"><div class="version">v2.1 Beta</div>
