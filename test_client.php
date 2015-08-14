<?php
	include('pids_manager.php');

	$pids = new pids_manager();
	$client = new GearmanClient();
	$results = new StdClass();

	$client->addServer();
	$client->setCompleteCallback("task_complete");
	
	$results->value = array();
	$results->pids = array();

	echo "Sending job\n";

	$client->addTask("reverse", "Hello!", $results, "t1");
	$client->addTask("reverse", "Hello!", $results, "t2");
	$client->runTasks();

	$pids->add( $results->pids );
	$pids->kill_all();


	function task_complete( $task, $results ){

		$data = unserialize( $task->data() );
		if( !in_array( $data['pid'], $results->pids ) ){
			array_push( $results->pids, $data['pid'] );
		}
		print_r( $data['data'] );
	}

?>