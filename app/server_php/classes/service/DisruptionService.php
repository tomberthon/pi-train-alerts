<?php
require_once __DIR__ . '/../../vendor/simple-html-dom/simple-html-dom/simple_html_dom.php';
require_once __DIR__ . '/../../classes/business/DisruptedService.php';

/**
 * Description of DisruptionService
 */
class DisruptionService {

  private $baseUrl = "http://m.journeycheck.com";
  private $startPath = "/southwesttrains";

  public function getDisruption() {


    $incidentLinks = $this->getIncidentLinks();

    $disruptedServices = array();
    foreach ($incidentLinks as $link) {
      $services = $this->getServices($link);
      $disruptedServices = array_merge($disruptedServices, $services);
    }

    foreach ($disruptedServices as $service) {
      $this->loadServiceDetail($service);
    }
    
    return $disruptedServices;
  }

  private function getIncidentLinks() {
    $incidentLinks = array();

    $html = file_get_html($this->baseUrl . $this->startPath);

    // get Incient links to investigate
    $aTags = $html->find("div.pageNavigation ul li a");
    foreach ($aTags as $tag) {

      $count = 0;
      $heading = "";

      $headingSpans = $tag->find("span.incidentHeading");
      if (sizeof($headingSpans) > 0) {
        $heading = trim($headingSpans[0]->innertext);
      }

      $countSpans = $tag->find("span.incidentCount");
      if (sizeof($countSpans) > 0) {
        $count = trim($countSpans[0]->innertext);
      }

      //echo $heading . " -> " . $count . "\n";
      if ($count > 0 &&
              ($heading == "Other Train Service Updates" || $heading == "Train Cancellation" || $heading == "Train Formation Updates")) {
        $incidentLinks[] = html_entity_decode($tag->attr['href']);
      }
    }
    return $incidentLinks;
  }

  private function getServices($path) {

    $services = array();
    $url = $this->baseUrl . $path;

    $html = file_get_html($url);

    $aTags = $html->find("div.pageNavigation ul li a");
    foreach ($aTags as $tag) {

      $summarySpans = $tag->find("span.summaryList");
      if (sizeof($summarySpans) > 0) {
        $summary = trim($summarySpans[0]->innertext);

        if (strpos($summary, " due ")) {

          list($fromString, $toString) = explode(" to ", $summary);
          list($to, $due) = explode(" due ", $toString);

          $left = substr($fromString, 0, 5);
          $from = substr($fromString, 6);

          //echo $from . " at " . $left . " -> " . $to . " at " . $due . "\n";

          $disruptedService = new DisruptedService();
          $disruptedService->from = $from;
          $disruptedService->to = $to;
          $disruptedService->detailPath = html_entity_decode($tag->attr['href']);

          $services[] = $disruptedService;
        }
      }
    }

    return $services;
  }

  private function loadServiceDetail(DisruptedService $distruptedService) {

    //echo "Loading Detail for " . $distruptedService . "\n";

    $url = $this->baseUrl . $distruptedService->detailPath;

    $html = file_get_html($url);

    $descriptionText = $html->find("div.pageNavigation")[0]->innertext;
    list($junk, $junk, $description, $junk) = explode("span", $descriptionText, 4);
    $description = trim(str_replace(">", "", strip_tags($description)));

    while (strpos($description, "  ") !== false) {
      $description = str_replace("  ", " ", $description);
    }

    $distruptedService->description = $description;

    // Load the stopping stations and scheduled times
    $stoppingPoints = $html->find("div.pageNavigation ul.boards li");

    foreach ($stoppingPoints as $point) {
      $station = trim(html_entity_decode($point->find("span.station")[0]->innertext));
      $time = str_replace("&nbsp;", "", $point->find("span.time")[0]->innertext);

      //echo $time . " -> " . $station . "\n";
      $distruptedService->stoppingPoints[$time] = $station;
    }

    return $distruptedService;
  }

}
