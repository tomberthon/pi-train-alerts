<?php
require_once 'vendor/autoload.php';
require_once 'classes/service/DisruptionService.php';
require_once 'classes/converter/TrainServiceConverter.php';
$redisClient = new Predis\Client(getenv('DB_PORT'));

$converter = new TrainServiceConverter();

$targetStation = getenv('APP_STATION');
while (true) {

  $listId = 'dep-' . $targetStation;
  echo "ListId: " . $listId . "\n";
  $values = $redisClient->lrange($listId, 0, 5);

  //var_dump($values);
  
  for ($i = 0; $i < count($values); $i++) {

    $service = $converter->fromJson($values[$i]);

    echo $service . "\n";
  }

  echo "\n\n";
  
  sleep(10);
}
//
//
//foreach ($disruption as $disruptionDetail) {
//
//  $stoppingPoints = $disruptionDetail->stoppingPoints;
//  if (in_array($targetStation, $stoppingPoints)) {
//
//    $std = array_search($targetStation, $stoppingPoints);
//
//    $idArray = array(
//        "dis",
//        $targetStation,
//        $std,
//        $disruptionDetail->from,
//        $disruptionDetail->to,
//    );
//
//    $id = implode('-', $idArray);
//
//    $redisClient->set($id, json_encode($disruptionDetail));
//  }
//
//  echo $disruptionDetail . "\n";
//}
