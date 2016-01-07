package integration

import (
  "fmt"
  "time"
//  "os"

  "encoding/json"

  "github.com/tomberthon/train-alerts/business"
  "github.com/garyburd/redigo/redis"
)

type RedisRepo struct {
  pool      *redis.Pool
}

func NewRedisRepo() *RedisRepo {

  //redisUrl := os.Getenv("DB_PORT_6379_TCP_ADDR") + ":" + os.Getenv("DB_PORT_6379_TCP_PORT")
  redisUrl := "localhost:6379"

  pool := &redis.Pool{
        MaxIdle: 3,
        IdleTimeout: 240 * time.Second,
        Dial: func () (redis.Conn, error) {
            c, err := redis.Dial("tcp", redisUrl)
            if err != nil {
                return nil, err
            }
            return c, err
        },
        TestOnBorrow: func(c redis.Conn, t time.Time) error {
            _, err := c.Do("PING")
            return err
        },
    }

  return &RedisRepo{pool: pool,}
}

func (repo RedisRepo) GetDeparturesToDestination(params *business.SearchParams) ([]*business.TrainService, error) {

  // If no Desination set - return a list of the next 5 departures
  if (params.Destination == "") {
    return repo.getAllDepartures(params.StationName, params.Size)
  }

  // If a desination was set - get a list of the next 20 and filter to only return departures 
  // going to the specifed destination
  departures := make([]*business.TrainService, 0, params.Size);

  allDepartures, err := repo.getAllDepartures(params.StationName, 20)

  if err != nil {
    return departures, err;
  }

  i := 0;
  for _,element := range allDepartures {

    if (element.Destination == params.Destination) {
      departures = append(departures, element);
      i++
    }

    if (i >= 5) {
      break;
    }

  }

  return departures, err;
}

func (repo RedisRepo) GetDisruption(params *business.SearchParams) (map[string]string) {

  conn := repo.pool.Get()
  defer conn.Close()

  listId := "dis-" + params.StationName
  result, err := redis.Strings(conn.Do("LRANGE", listId, 0, 10));

  if err != nil {
    fmt.Println(err);
    return nil
  }

  disruptionMap := make(map[string]string)
  for _,element := range result {

    res := &business.DisruptedService{}
    json.Unmarshal([]byte(element), &res)

    for time, desination := range res.StoppingPoints {
      if desination == params.StationName {
        key := fmt.Sprintf("%s:%s", time, res.To);
        disruptionMap[key] = res.Description
      }
    }
  }
  return disruptionMap
}


func (repo RedisRepo) getAllDepartures(stationName string, total int) ([]*business.TrainService, error) {

  conn := repo.pool.Get()
  defer conn.Close()

  listId := "dep-" + stationName
  result, err := redis.Strings(conn.Do("LRANGE", listId, 0, total - 1));

  if err != nil {
    fmt.Println(err);
  }

  departures := make([]*business.TrainService, 0, len(result));

  // display the data
  for _,element := range result {

    res := &TrainServiceDto{}
    json.Unmarshal([]byte(element), &res)
    
    departures = append(departures, repo.convertDto(res))
  }


  return departures, err;
}

func (repo RedisRepo) convertDto(dto *TrainServiceDto) (*business.TrainService) {
  
  return &business.TrainService{
      ServiceId:     dto.Std + ":" + dto.Destination,
      Origin:        dto.Origin,
      Destination:   dto.Destination,
      Std:           dto.Std,
      Etd:           dto.Etd,
  }
}

