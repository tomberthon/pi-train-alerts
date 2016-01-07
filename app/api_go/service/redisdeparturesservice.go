package service

import (
  "github.com/tomberthon/train-alerts/business"
  "github.com/tomberthon/train-alerts/integration"
)

type RedisDeparturesService struct {
  Repo  integration.Repo
}

func NewDeparturesService() *RedisDeparturesService {

  return &RedisDeparturesService {
    Repo: integration.NewRedisRepo(),
  } 
}

func (service RedisDeparturesService) GetDepartures(params *business.SearchParams) ([]*business.TrainService, error) {

  departures, err := service.Repo.GetDeparturesToDestination(params)

  if err != nil {
    return nil, err
  }

  disruption := service.Repo.GetDisruption(params)

  for i,departure := range departures {
    matchedDisruption := disruption[departure.ServiceId]
    
    if matchedDisruption != "" {
      departures[i].SetDisruption(matchedDisruption)
    }
  }

  return departures, err
}