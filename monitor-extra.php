<?php



function show_progress($con_pdo, $id, $limit){

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
	
	
	@$li = 720/$li;
	$massenstring = '<div class="progress">';
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
	
	//echo $val['start'] .' bis '.$val['end'].' | '. $text ."\n\n";
	if(round($dif, 0)*0.50 > 0){
		$massenstring .= '<div class="bar hastip bar-'. $status .'" style="width: '. round($dif, 0)*0.50 .'px" title="'. date("H:i", strtotime($val['end'])) .' - '. date("H:i", strtotime($val['start'])) ." Uhr (".round($dif,0).' Min.)"></div>';
		$massenstring .= "\n\n";
	}
	$all = $all + $met;
	}
	if(abs(round($all-1440,0)*0.50) > 0){
		$massenstring .= '<div class="bar hastip bar-unchecked" style="width: '. abs(round($all-1440,0)*0.50) .'px" title="Ungepr&uuml;ft | '. round(100/1440*round(abs(round($all-1440,0)),0), 2) .'% | '. abs(round($all-1440,0)) .' Minuten"></div>';
	}
	$massenstring .= '</div>';
	
	return $massenstring;
}

function uptime_gesammt($con_pdo, $id){
	$stmt_all = $con_pdo->prepare('SELECT id FROM protokoll WHERE monitorid=?');
	$stmt_all->execute( array( $id ) );
	
	while($row = $stmt_all->fetch(PDO::FETCH_ASSOC)){
		$gesammt = $gesammt+$row['status'];
	}
	
	return $gesammt;
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



?>