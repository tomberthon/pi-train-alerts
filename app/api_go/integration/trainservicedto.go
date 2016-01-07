package integration

import "fmt"

type TrainServiceDto struct {
  ServiceId      string `json:"serviceId"`
  Origin         string `json:"origin"`
  Destination    string `json:"destination"`
  Std            string `json:"std"`
  Etd            string `json:"etd"`
}

func (service TrainServiceDto) String() string {
  return fmt.Sprintf("%-8s %-8s   %24s -> %-24s", service.Std, service.Etd, service.Origin, service.Destination)
}
