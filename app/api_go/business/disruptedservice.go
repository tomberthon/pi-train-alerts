package business

import (
  "fmt"
)

type DisruptedService struct {
  From            string              `json="from"`
  To              string              `json="to"`
  Description     string              `json="description"`
  StoppingPoints  map[string]string   `json="stoppingPoints"`
}

func (service DisruptedService) String() string {
  return fmt.Sprintf("%24s -> %-24s : %s : %v", service.From, service.To, service.Description, service.StoppingPoints)
}
