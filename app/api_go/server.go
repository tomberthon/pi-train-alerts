package main

import (
  "log"
  "strconv"

  "encoding/json"
  "net/http"

  "github.com/go-martini/martini"
  "github.com/martini-contrib/cors"
  "github.com/tomberthon/train-alerts/business"
  "github.com/tomberthon/train-alerts/service"
)

func main() {

  m := martini.Classic()
  m.Use(martini.Logger())

  // Add a redis repo to use
  m.MapTo(service.NewDeparturesService(), (*service.DeparturesService)(nil))
  m.MapTo(service.NewConfigService(), (*service.ConfigService)(nil))
        
  // Set cors to be enabled for all requests
  m.Use(cors.Allow(&cors.Options{
    AllowOrigins:     []string{"*"},
    AllowCredentials: true,
  }))

  // set routes
  m.Get("/:station/departures", GetStationDepartures)
  m.Get("/config", GetConfig)

  m.Run()
}

func GetStationDepartures(r *http.Request, w http.ResponseWriter, urlParams martini.Params, service service.DeparturesService) (int, []byte) {

  params := BuildParams(r, urlParams);

  log.Printf("Get Departures for Station:  %s", params.StationName)
  if params.Destination != "" {
    log.Printf("Only Going to: %s ", params.Destination)
  }

  departures, err := service.GetDepartures(params)

  if err != nil {
    return http.StatusNotFound, nil
  } 

  jsonString, err := json.MarshalIndent(departures, "", "  ")

  if err != nil {
    panic(err)
  }

  w.Header().Set("Content-Type", "application/json; charset=utf-8")
  return http.StatusOK, jsonString
}

func BuildParams(r *http.Request, urlParams martini.Params) *business.SearchParams {

  qs := r.URL.Query()
  params := business.NewSearchParams();

  params.StationName = urlParams["station"]
	params.Destination = qs.Get("destination")

  size := qs.Get("size")
  if size != ""  {
    params.Size, _ = strconv.Atoi(size)
  }

  return params;

}

func GetConfig(w http.ResponseWriter, service service.ConfigService) (int, []byte) {

  config, err := service.GetConfig()

  if err != nil {
    return http.StatusNotFound, nil
  } 

  jsonString, err := json.MarshalIndent(config, "", "  ")

  if err != nil {
    panic(err)
  }

  w.Header().Set("Content-Type", "application/json; charset=utf-8")
  return http.StatusOK, jsonString
}
