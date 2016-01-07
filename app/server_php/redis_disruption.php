<?php
require_once 'vendor/autoload.php';
require_once 'classes/service/DisruptionService.php';

$disruptionService = new DisruptionService();
$redisClient = new Predis\Client(getenv('DB_PORT'));

$targetStation = getenv('APP_STATION');
$disruption = $disruptionService->getDisruption();

$listId = "dis-". $targetStation;

$redisClient->del($listId);

foreach ($disruption as $disruptionDetail) {

  $stoppingPoints = $disruptionDetail->stoppingPoints;
  if (in_array($targetStation, $stoppingPoints)) {

    $redisClient->rpush($listId, json_encode($disruptionDetail));
  }

  //echo $disruptionDetail . "\n";
}
