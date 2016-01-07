<?php

require_once 'vendor/autoload.php';
require_once('classes/service/StationBoardService.php');
require_once('classes/service/DisruptionService.php');

$trainListService = new StationBoardService();
$disruptionService = new DisruptionService();

$departureBoard = $trainListService->getDepartingServices("SNS", 20);
$disruption = $disruptionService->getDisruption();

foreach ($departureBoard->nextDepartures as $trainService) {

  foreach ($disruption as $disruptionDetail) {
    
    $stoppingPoints = $disruptionDetail->stoppingPoints;
    if (array_key_exists($trainService->std, $stoppingPoints) && $stoppingPoints[$trainService->std] == $departureBoard->station) {
      
      //echo $disruptionDetail->description;  
      $trainService->setDisruptionMessage($disruptionDetail->description);
      break;
    }
    
  }
  //echo $trainService . "\n";
}



