<?php
require_once(__DIR__ . '/../../classes/business/TrainService.php');

/**
 * Description of TrainServiceConverter
 */
class TrainServiceConverter {

  public function fromData($data) {

    // Handle multiple locations

    if (is_array($data->destination->location)) {
      $destination = $this->combineLocations($data->destination->location);
    } else {
      $destination = $data->destination->location->locationName;
    }

    if (is_array($data->origin->location)) {
      $origin = $this->combineLocations($data->origin->location);
    } else {
      $origin = $data->origin->location->locationName;
    }

    $service = new TrainService();
    $service->destination = $destination;
    $service->origin = $origin;
    $service->std = $data->std;
    $service->etd = $data->etd;
    $service->operator = $data->operator;
    $service->operatorCode = $data->operatorCode;
    $service->serviceId = $data->serviceID;
    $service->platform = @$data->platform;

    return $service;
  }

  private function combineLocations($locations) {
    foreach ($locations as $location) {
      $names[] = $location->locationName;
    }
    $name = implode(" / ", $names);
    return $name;
  }
  
  public function fromJson($json) {
    
    $obj = json_decode($json);
    
    $service = new TrainService();
    $service->destination = $obj->destination;
    $service->origin = $obj->origin;
    $service->std = $obj->std;
    $service->etd = $obj->etd;
    $service->operator = $obj->operator;
    $service->operatorCode = $obj->operatorCode;
    $service->serviceId = $obj->serviceId;
    $service->platform = $obj->platform;
    
    return $service;
  }
  
  public function toJson(TrainService $service) {
    return json_encode($service);
  }

}
