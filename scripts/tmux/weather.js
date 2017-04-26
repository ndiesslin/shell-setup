var http = require('http');

function getWeather() {
    return http.get({
        host: 'api.openweathermap.org',
        path: '/data/2.5/weather?id=5045360&APPID=14e3df96753ec1ac143f5e11dbd7a196'
    }, function(response) {
        // Continuously update stream with data
        var body = '';
        response.on('data', function(d) {
            body += d;
        });
        response.on('end', function(d) {
          // Data reception is done, do whatever with it!
          var parsed = JSON.parse(body),
              temp = convertTemp(parsed.main.temp);
          console.log(temp);
        });
    });
}

console.log(getWeather());

// Convert temp from Kelvin to Fahrenheit, because I'm an American
function convertTemp(t) {
  return Math.round((277*(9/5)-459.67)*100)/100;
}

// Call weather every hour
function callEveryHour() {
  setInterval( getWeather, 1000 * 60 * 60 );
}

callEveryHour();
