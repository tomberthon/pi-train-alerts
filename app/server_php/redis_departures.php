<?php
require_once 'vendor/autoload.php';
require_once 'classes/service/StationBoardService.php';
require_once 'classes/converter/TrainServiceConverter.php';

$trainListService = new StationBoardService();
$converter = new TrainServiceConverter();
$redisClient = new Predis\Client(getenv('DB_PORT'));

$targetStationCode = getenv('APP_STATION_CODE');
$departureBoard = $trainListService->getDepartingServices($targetStationCode, 20);

if (is_null($departureBoard)) {
  echo "No Train departures from this station: " . $targetStationCode . "\n";
  exit;
}

$listId = "dep-" . $departureBoard->station;
$redisClient->del($listId);

foreach ($departureBoard->nextDepartures as $trainService) {
  
  $redisClient->rpush($listId, $converter->toJson($trainService));
  //echo $trainService . "\n";
}



