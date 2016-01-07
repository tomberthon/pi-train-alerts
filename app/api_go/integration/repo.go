package integration

import (
  "github.com/tomberthon/train-alerts/business"
)

type Repo interface {
  GetDeparturesToDestination(*business.SearchParams) ([]*business.TrainService, error)

  GetDisruption(*business.SearchParams) (map[string]string)

}