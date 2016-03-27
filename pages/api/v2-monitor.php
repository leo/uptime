<?php

function checkAPIKey($apikey, $con_pdo){

	$stmt_apikey = $con_pdo->prepare('SELECT count(id) AS count, user_id FROM `sessions` WHERE `session_id`=?');
	$stmt_apikey->execute( array( $apikey ) );
	$result_apikey = $stmt_apikey->fetch(PDO::FETCH_ASSOC);

	if($result_apikey['count']){
		return $result_apikey['user_id'];
	}else{
		return false;
	}
	
}

function checkPremium($userid, $con_pdo){

	$stmt_premium = $con_pdo->prepare('SELECT count(id) AS count FROM `users` WHERE `id`=?');
	$stmt_premium->execute( array( $userid ) );
	$result_premium = $stmt_premium->fetch(PDO::FETCH_ASSOC);

	if($result_premium['count']){
		return true;
	}else{
		return false;
	}
	
}

function uptime_gesammt($con_pdo, $id){
	$stmt_ges = $con_pdo->prepare('SELECT count(id) AS count FROM protokoll WHERE monitorid=?');
	$stmt_ges->execute( array( $id ) );
	$result_ges = $stmt_ges->fetch(PDO::FETCH_ASSOC);
	
	return $result_ges['count'];
}

function uptime_up($con_pdo, $id){
	$stmt_up = $con_pdo->prepare('SELECT count(id) AS count FROM protokoll WHERE monitorid=? AND status=1');
	$stmt_up->execute( array( $id ) );
	$result_up = $stmt_up->fetch(PDO::FETCH_ASSOC);
	
	return $result_up['count'];
}
function uptime_down($con_pdo, $id){
	$stmt_up = $con_pdo->prepare('SELECT count(id) AS count FROM protokoll WHERE monitorid=? AND status=2');
	$stmt_up->execute( array( $id ) );
	$result_up = $stmt_up->fetch(PDO::FETCH_ASSOC);
	
	return $result_up['count'];
}
function monitor_status($con_pdo, $id){
	$stmt_status = $con_pdo->prepare('SELECT * FROM protokoll WHERE monitorid=? ORDER BY id DESC LIMIT 1');
	$stmt_status->execute( array( $id ) );
	$result_status = $stmt_status->fetch(PDO::FETCH_ASSOC);
	
	return $result_status['status'];
}

if(empty($params[3])){
	// monitor
	$arr = array();
	
	if(checkAPIKey($_GET['apikey'], $con_pdo)){
				
		$stmt_monitor = $con_pdo->prepare('SELECT * FROM `monitors` WHERE `owner`=?');
		$stmt_monitor->execute( array( checkAPIKey($_GET['apikey'], $con_pdo) ) );
		while($result_monitor = $stmt_monitor->fetch(PDO::FETCH_ASSOC)){
	
			array_push($arr, array(
				'error'=>false, 'status_code'=>'0010',
				'monitor_id'=>$result_monitor['id'],
				'monitor_name'=>$result_monitor['name'],
				'monitor_url'=>$result_monitor['url'],
				'monitor_port'=>$result_monitor['port'],
				'monitor_status'=>monitor_status($con_pdo, $result_monitor['id']),
				'monitor_total_uptime'=>@round(100-(100/(uptime_up($con_pdo, $result_monitor['id'])+uptime_down($con_pdo, $result_monitor['id']))*uptime_down($con_pdo, $result_monitor['id'])), 2),
			));
		
		}
	}else{
		$arr = array ('error'=>true, 'status_code'=>'0011');
	}
	
	echo json_encode($arr);
}
else if($params[3] == 'show'){
	// monitor/show
	$arr = array();
	
	if(!checkAPIKey($_GET['apikey'], $con_pdo)){
		$arr = array ('error'=>true, 'status_code'=>'0012');
	}else if(!checkPremium(checkAPIKey($_GET['apikey'], $con_pdo), $con_pdo)){
		$arr = array ('error'=>true, 'status_code'=>'0013');		
	}else{
		$stmt_monitor = $con_pdo->prepare('SELECT *, count(id) AS count FROM `monitors` WHERE `owner`=? AND `id`=?');
		$stmt_monitor->execute( array( checkAPIKey($_GET['apikey'], $con_pdo), $_GET['monitor_id'] ) );
		$result_monitor = $stmt_monitor->fetch(PDO::FETCH_ASSOC);
		if($result_monitor['count'] != 1){
			$arr = array ('error'=>true, 'status_code'=>'0014');
		}else{
			$arr = array(
				'error'=>false, 'status_code'=>'0015',
				'monitor_name'=>$result_monitor['name'],
				'monitor_url'=>$result_monitor['url'],
				'monitor_port'=>$result_monitor['port'],
				'monitor_status'=>monitor_status($con_pdo, $result_monitor['id']),
				'monitor_total_uptime'=>@round(100-(100/(uptime_up($con_pdo, $result_monitor['id'])+uptime_down($con_pdo, $result_monitor['id']))*uptime_down($con_pdo, $result_monitor['id'])), 2),
			);
		}
	}
	
	echo json_encode($arr);
}
else if($params[3] == 'create'){
	// monitor/create
	echo 'monitor/create';
}
else if($params[3] == 'destroy'){
	// monitor/destroy
	echo 'monitor/destroy';
}
else if($params[3] == 'update'){
	// monitor/update
	echo 'monitor/update';
}
else if($params[3] == 'progressbar'){
	$stmt_min = $con_pdo->prepare('SELECT min FROM crons WHERE monitor=?');
	$stmt_min->execute( array( $id ) );
	$result_min = $stmt_min->fetch(PDO::FETCH_ASSOC);

	$lastday = time()-60*60*24-$result_min['min']*60*2;
 
	$stmt_stats = $con_pdo->prepare('SELECT status, timestamp FROM protokoll WHERE monitorid=? AND timestamp>=? ORDER BY timestamp DESC');
	$stmt_stats->execute( array( $id, $lastday ) );
	$result_stats = $stmt_stats->fetch(PDO::FETCH_ASSOC);


	$lastState = '';
	$cnt = 0;
	$states = array();
	//Alle Einträge durchgehen
	$end = "";
	while($result_stats_2 = $stmt_stats->fetch(PDO::FETCH_ASSOC)){
		
		if($lastState != $result_stats_2['status']){
			// Statuswechsel
			if($end == ""){
				$states[++$cnt]['start'] = date( 'Y-m-d H:i:s', $result_stats_2['timestamp']);
			}else{
				$states[++$cnt]['start'] = $end;
			} 
			
			//beim neuen Eintrag noch den Status hinzufügen
			$states[$cnt]['state'] =  $result_stats_2['status'];
			//Den letzten Status anpssen
			$lastState = $result_stats_2['status'];
		}
		// Das Schlussdatum wird überschreiben bis zum nächsten $cnt
		$states[$cnt]['end'] =  date( 'Y-m-d H:i:s', $result_stats_2['timestamp']);
		$end = date( 'Y-m-d H:i:s', $result_stats_2['timestamp']);
	}


	$dif1 = "";
  $dif2 = "";
  $lo = "";
  $li = "";
  $test = "";
  $all = "";
  
  $li = 0;
	foreach($states AS $val){
		$li++;
	}
	
	
	@$li = 1440/$li;
	foreach($states AS $val){
	
	if($val['state'] == 2){
	$status = 'danger';
	$text = "Offline";
	}else if($val['state'] == 1){
	$status = 'success';
	$text = "Online";
	}else if($val['state'] == 3){
	$status = 'paused';
	$text = "Pausiert";
	}
	
	$dif1 = strtotime($val['start']);
	$dif2 = strtotime($val['end']);
	
	//$min  = $differenz / 60 * 60; 
	
	$dif = $dif2-$dif1;
	$dif  = abs(round($dif / 60, 2)); 
	//echo $li."\n";
	
	//echo round($dif, 0)."+";
	
	
	//echo round($dif,0)*0.43263888888;
	
	if($dif <= 1440){
		$met = $dif;
	}else{
		$met = 1440;
	}
	
	$arr = array();
	
	//echo $val['start'] .' bis '.$val['end'].' | '. $text ."\n\n";
	if(round($dif, 0)*1 > 0){
		$arr = array();
		array_push($arr, array('status'=>$status, 'width'=>(round($dif, 0)*1)) );
	}
	$all = $all + $met;
	}
	if(abs(round($all-1440,0)*0.50) > 0){
		$arr = array();
		array_push($arr, array('status'=>'unchecked', 'width'=>(abs(round($all-1440,0)*1))) );
	}
	
	echo json_encode($arr);
}
else{
	$arr = array ('error'=>true, 'status_code'=>'0009');
	echo json_encode($arr);
}