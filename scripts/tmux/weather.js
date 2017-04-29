// Get initial weather
getWeather();

function getWeather() {
  var http = require('http');
  var str = '';

  var options = {
    host: 'api.openweathermap.org',
    path: '/data/2.5/weather?units=imperial&id=5045360&APPID=14e3df96753ec1ac143f5e11dbd7a196'
  };

  var callback = function(response) {
    // Continuously update stream with data
    response.on('data', function(d) {
      // Data reception is done, do whatever with it!
      var parsed = JSON.parse(d),
          clouds = parsed.clouds.all,
          description = parsed.weather[0].main,
          icon = getWeatherIcon(parsed.weather[0].icon),
          wind = parsed.wind.speed,
          temp = parsed.main.temp,
          visibility = clearityIcon(parsed.clouds.all);
      str += icon+' '+temp+'°, '+description+', ☴ '+wind+', '+visibility+' '+clouds;
    });

    response.on('end', function() {
      console.log(str);
    }); 
  };
  var weather = http.request(options, callback).end();
  weather;
}

// Get main weather icon
function getWeatherIcon(id) {
  switch(id) {
    case '01d': case '01n':
      return '☀️';
    case '02d': case '02n':
      return '🌤';
    case '03d': case '03n':
      return '🌥️';
    case '04d': case '04n':
      return '☁';
    case '09d': case '09n':
      return '🌧';
    case '10d': case '10n':
      return '🌦';
    case '11d': case '11n':
      return '🌩';
    case '13d': case '13n':
      return '❄';
    case '50d': case '50n':
      return '🌁';
    default:
      return '';
  }
}

// Get icon for visibility/ clearity
function clearityIcon(num) {
  switch (num) {
    case num > 75:
      return '☁';
    case num <= 7: case num >= 50:
      return '🌥';
    case num <= 49: case num >= 25:
      return '🌤';
    case num < 25:
      return '☀️';
    default:
      return '☀️';
  }
}
