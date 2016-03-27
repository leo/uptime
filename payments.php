<?php
// Database variables
$host = "localhost"; //database location
$user = "uptime-monitor"; //database username
$pass = "JfJ43DRoAE43J2oZsBVdCC7u"; //database password
$db_name = "uptime-monitor"; //database name

// PayPal settings
$paypal_email = 'seller@codingdev.de';
$return_url = 'http://uptime-monitor.net/paymentcancelled';
$cancel_url = 'http://uptime-monitor.net/paymentsuccessful';
$notify_url = 'http://uptime-monitor.net/payments.php';

//Form
$cmd = "_xclick";
$no_note = "1";
$lc = "DE";
$currency_code = $row['item_currency'];
$bn = "PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest";


// Include Functions
include("functions.php");

//Database Connection
$link = mysql_connect($host, $user, $pass);
mysql_select_db($db_name);

// Check if paypal request or response
if (!isset($_POST["txn_id"]) && !isset($_POST["txn_type"])){

	//GET ITEM
	$con=mysqli_connect("uptime-monitor.net","uptime-monitor","JfJ43DRoAE43J2oZsBVdCC7u","uptime-monitor");
	$result = mysqli_query($con,"SELECT *,count(id) AS count FROM itemshop WHERE id='{$_GET['item']}'");
	$row = mysqli_fetch_array($result);

	if($row['count'] != 1){
		header('Location: http://uptime-monitor.net/paymentcancelled');
		exit();
	}

	$item_number = $_GET['item']. "/" . $_GET['user'];
	$item_name = $row['item_name'];
	$item_amount = $row['item_price'];

	// Firstly Append paypal account to querystring
	$querystring .= "?business=".urlencode($paypal_email)."&";
	
	//The item name and amount can be brought in dynamically by querying the $_POST['item_number'] variable.
	$querystring .= "cmd=".urlencode($cmd)."&";
	$querystring .= "no_note=".urlencode($no_note)."&";
	$querystring .= "lc=".urlencode($lc)."&";
	$querystring .= "currency_code=".urlencode($currency_code)."&";
	$querystring .= "bn=".urlencode($bn)."&";
	$querystring .= "item_number=".urlencode($item_number)."&";
	$querystring .= "item_name=".urlencode($item_name)."&";
	$querystring .= "amount=".urlencode($item_amount)."&";
	
	//loop for posted values and append to querystring
	foreach($_POST as $key => $value){
		$value = urlencode(stripslashes($value));
		$querystring .= "$key=$value&";
	}
	
	// Append paypal return addresses
	$querystring .= "return=".urlencode(stripslashes($return_url))."&";
	$querystring .= "cancel_return=".urlencode(stripslashes($cancel_url))."&";
	$querystring .= "notify_url=".urlencode($notify_url);
	
	header('location:https://www.sandbox.paypal.com/cgi-bin/webscr'.$querystring);
	exit();

}else{
	
	// Response from Paypal
	$data['item_name']			= $_POST['item_name'];
	$data['item_number'] 		= $_POST['item_number'];
	$data['payment_status'] 	= $_POST['payment_status'];
	$data['payment_amount'] 	= $_POST['mc_gross'];
	$data['payment_currency']	= $_POST['mc_currency'];
	$data['txn_id']				= $_POST['txn_id'];
	$data['receiver_email'] 	= $_POST['receiver_email'];
	$data['payer_email'] 		= $_POST['payer_email'];
	$data['custom'] 			= $_POST['custom'];
	$number = explode("/", $data['item_number']);
	
	$con=mysqli_connect("localhost","uptime-monitor","JfJ43DRoAE43J2oZsBVdCC7u","uptime-monitor");
	$result = mysqli_query($con,"SELECT *,count(id) AS count FROM itemshop WHERE id='{$number['0']}' AND item_price='{$data['payment_amount']}'");
	$row = mysqli_fetch_array($result);

	
	if($row['count']){
		// Payment Ok
		mysqli_query($con,"INSERT INTO payment (payment_id, payment_amount, payment_status, payment_ceated, payment_owner) VALUES ('{$row['id']}', '{$row['item_price']}', 'success', '".time()."', '{$number['1']}')");
	}else{					
		// Payment made but data has been changed
		// E-mail admin or alert user
	}
}
?>