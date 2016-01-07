package business

type SearchParams struct {
  StationName  string
  Destination  string
  Size         int
}

func NewSearchParams() *SearchParams {
  return &SearchParams{Size: 10,}
}