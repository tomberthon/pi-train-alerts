package service

import (
  "github.com/tomberthon/train-alerts/business"
)

type DeparturesService interface {
  GetDepartures(*business.SearchParams) ([]*business.TrainService, error)
}