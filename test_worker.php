<?php

echo "Starting\n";

$gmworker= new GearmanWorker();
$gmworker->addServer();

$gmworker->addFunction("reverse", "reverse_fn");
$gmworker->addFunction("same", "same_fn");

print "Waiting for job...\n";
while($gmworker->work())
{
  if ($gmworker->returnCode() != GEARMAN_SUCCESS)
  {
    echo "return_code: " . $gmworker->returnCode() . "\n";
    break;
  }
}


function same_fn($job){

  $workload = $job->workload();
  $result= unserialize($workload);
  $result['un_campo_mas'] = 'yeah';
  return result( $result );
}

function reverse_fn($job){

	$workload = $job->workload();
  $return = strrev($workload);
  return result( $return );
}

function result( $data ){

  $return = array(
    'data' => $data,
    'pid' => getmypid()
  );
  return serialize( $return );
}

?>