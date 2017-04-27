var http = require('http');

function getWeather() {
  return http.get({
    host: 'api.openweathermap.org',
    path: '/data/2.5/weather?units=imperial&id=5045360&APPID=14e3df96753ec1ac143f5e11dbd7a196'
  }, function(response) {
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
      return console.log(icon+' '+temp+'°, '+description+', ☴ '+wind+', '+visibility+' '+clouds);
    });
  });
}

// Get main weather icon
function getWeatherIcon(id) {
  switch(id) {
    case '01d':
    case '01n':
      return '🌣';
      break;
    case '02d':
    case '02n':
      return '⛅';
      break;
    case '03d':
    case '03n':
      return '⛅';
      break;
    case '04d':
    case '04n':
      return '☁';
      break;
    case '09d':
    case '09n':
      return '🌧';
      break;
    case '10d':
    case '10n':
      return '🌦';
      break;
    case '11d':
    case '11n':
      return '🌩';
      break;
    case '13d':
    case '13n':
      return '❄';
      break;
    case '50d':
    case '50n':
      return '🌁';
      break;
    default:
      return '';
  }
}

// Get icon for visibility/ clearity
function clearityIcon(num) {
  switch (num) {
    case num > 75:
      return '☁';
    case num <= 7:
    case num >= 50:
      return '🌥';
    case num <= 49:
    case num >= 25:
      return '🌤';
    case num < 25:
      return '🌣';
    default:
      return '🌣';
  }
}

// Get initial weather
getWeather();

// Call weather every hour
function callWeatherTimer() {
  setInterval( getWeather, 1000 * 60 * 10 );
}

callWeatherTimer();
