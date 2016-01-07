<?php
require_once(__DIR__ . '/../../lib/OpenLDBWS/OpenLDBWS.php');
require_once(__DIR__ . '/../../classes/business/StationBoard.php');
require_once(__DIR__ . '/../../classes/converter/TrainServiceConverter.php');

/**
 * Description of StationBoardService
 */
class StationBoardService {

  private $OpenLDBWS;
  private $trainServiceConverter;

  function __construct() {
    
    $accessToken = getenv('APP_OPENLDBWS_KEY');
    
    $this->OpenLDBWS = new OpenLDBWS($accessToken, true);
    $this->trainServiceConverter = new TrainServiceConverter();
  }

  public function getDepartingServices($stationCode, $numServices = 10) {

    $departureBoardData = $this->OpenLDBWS->getDepartingServices($numServices, $stationCode);

    if (!property_exists($departureBoardData->GetStationBoardResult, "trainServices")) {
      var_dump($departureBoardData);
      return null;
    }
    
    $serviceData = $departureBoardData->GetStationBoardResult->trainServices->service;

    $departureBoard = new StationBoard();
    $departureBoard->stationCode = $stationCode;
    $departureBoard->station = $departureBoardData->GetStationBoardResult->locationName;
    
    $services = array();
    foreach ($serviceData as $data) {
      $trainService = $this->trainServiceConverter->fromData($data);
      
      //$this->getServiceDetails($trainService->serviceId);
      
      
      $services[] = $trainService;
    }
    
    $departureBoard->nextDepartures = $services;
    return $departureBoard;
  }
  
  public function getServiceDetails($id) {
    $serviceDetails = $this->OpenLDBWS->GetServiceDetails($id);
    
    var_dump($serviceDetails);
    
    return $serviceDetails;
  }

}
