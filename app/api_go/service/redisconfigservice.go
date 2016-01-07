package service

import (
  "github.com/tomberthon/train-alerts/business"
  "github.com/tomberthon/train-alerts/integration"
)

type RedisConfigService struct {
  Repo  integration.Repo
}

func NewConfigService() *RedisConfigService {

  return &RedisConfigService { 
    Repo: integration.NewRedisRepo(),
 } 
}

func (service RedisConfigService) GetConfig() (*business.Config, error) {

  config := &business.Config {
    ServiceId: "config",
  }

  return config, nil
}