<?php

/**
 * Description of DisruptedService
 */
class DisruptedService {

  public $from;
  public $to;
  public $detailPath;
  public $description;
  
  public $stoppingPoints = array();

  public function __toString() {
    return $this->from . " -> " . $this->to . " : " . $this->detailPath;
  }

}
