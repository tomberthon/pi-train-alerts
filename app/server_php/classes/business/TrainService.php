<?php

/**
 * Description of trainService
 */
class TrainService {

  public $serviceId;
  public $origin;
  public $destination;
  public $std;
  public $etd;
  public $platform;
  public $operator;
  public $operatorCode;
  public $disruptionMessage;
  public $disruptionFormation;

  public function isDelayed() {
    return $this->etd != "On time";
  }

  public function setDisruptionMessage($message) {
    $this->disruptionMessage = $message;

    $regexp = '/Will be formed of ([0-9]+) coaches instead of ([0-9]+)./';

    $matches = array();
    if (preg_match($regexp, $message, $matches)) {
      $this->disruptionFormation = $matches[1] . " not " . $matches[2];
    }
  }

  public function __toString() {

    if (!$this->isDelayed()) {
      $time = $this->std . " " . $this->disruptionFormation;
    } else {
      $time = $this->std . " due " . $this->etd . " " . $this->disruptionFormation;
    }

    return str_pad($time, 24) . str_pad($this->origin . " -> " . $this->destination, 60) . "[" . $this->disruptionMessage . "]";
  }

}
