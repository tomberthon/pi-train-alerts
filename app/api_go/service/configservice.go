package service

import (
  "github.com/tomberthon/train-alerts/business"
)

type ConfigService interface {
  GetConfig() (*business.Config, error)
}