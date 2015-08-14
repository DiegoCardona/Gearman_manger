<?php

$gmclient= new GearmanClient();
$gmclient->addServer();
//$gmclient->setCompleteCallback("reverse_complete");
$gmclient->setCompleteCallback("same_complete");

$results = new StdClass();
$results->value = array();
$results->pids = array();
$to_send = array(
	'key1' => 'value',
	'key2' => 'value',
	'key3' => 'value',
	'key4' => 'value'
);

$to_send = serialize( $to_send );

echo "Sending job\n";


$gmclient->addTask("reverse", "Hello!", $results, "t1");
$gmclient->addTask("reverse", "Hello!", $results, "t2");
$gmclient->addTask("same", $to_send, $results, "t3");

$gmclient->runTasks();

kill_pids( $results->pids );


// function reverse_complete( $task, $results){

// 		$data = unserialize( $task->data() );
// 		$results->value[$task->unique()] = array(
// 				"handle" => $task->jobHandle(),
// 				"data" => $data['data']
// 		);
// 		$results->pid[] = $data['pid'];
// }

function same_complete( $task, $results ){

	$data = unserialize( $task->data() );
	if( !in_array( $data['pid'], $results->pids ) ){
		array_push( $results->pids, $data['pid'] );
	}
	print_r( $data['data'] );
	echo $data['pid'];
}


function kill_pids( $pids ){
	print_r( $pids );
	foreach ($pids as $pid) {
		if( !is_null( $pid ) && $pid != '' && !is_nan( $pid ) ){
			echo $pid . "\n";
			system( 'kill -9 '. $pid );
		}	
	}
}



?>