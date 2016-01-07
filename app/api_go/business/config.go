package business

import (
  "fmt"
)

type Config struct {
  ServiceId      string   `json:"serviceId"`
}

func (config Config) String() string {
  return fmt.Sprintf("%s", config.ServiceId)
}
