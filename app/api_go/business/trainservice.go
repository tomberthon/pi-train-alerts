package business

import (
  "fmt"
  "regexp"
  "strings"
  "strconv"
)

type TrainService struct {
  ServiceId      string   `json:"serviceId"`
  Origin         string   `json:"origin"`
  Destination    string   `json:"destination"`
  Std            string   `json:"std"`
  Etd            string   `json:"etd"`
  Disruption     string   `json:"disruption,omitempty"`
  PlannedCoaches int      `json:"plannedCoaches,omitempty"`
  ActualCoaches  int      `json:"actualCoaches,omitempty"`
  IsOldTrain     bool     `json:"isOldTrain,omitempty"`
}

func (service *TrainService) SetDisruption(disruption string) {

  service.Disruption = disruption
  if strings.Contains(disruption, "First class not available") {
    service.IsOldTrain = true
  }
  if strings.Contains(disruption, "Will be formed of") {
    r, _ := regexp.Compile("Will be formed of ([0-9]+) coaches instead of ([0-9]+).")
   
    result := r.FindStringSubmatch(disruption)

    service.PlannedCoaches, _ = strconv.Atoi(result[2])
    service.ActualCoaches, _ = strconv.Atoi(result[1])
  }
}

func (service TrainService) String() string {
  return fmt.Sprintf("%-8s %-8s   %24s -> %-24s", service.Std, service.Etd, service.Origin, service.Destination)
}

