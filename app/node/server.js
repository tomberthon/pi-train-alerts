var express = require('express');
var path = require('path');
var app = express();

app.use(express.static(path.join(__dirname, 'public')));

app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'jade');

app.get('/', function(req, res) {
  //res.send('Hello World!');
  res.render('index', { title: 'Express' });
});

var server = app.listen(80, function() {

  var host = server.address().address;
  var port = server.address().port;

  console.log('Example app listening on port ', port);

});
