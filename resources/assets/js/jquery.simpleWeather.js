// simpleWeather v3.1.0 - http://simpleweatherjs.com //
// student has taken code from site above and removed parts not needed

(function($) {
    'use strict';
    
    function getAltTemp(unit, temp) {
        if(unit === 'f')
            return Math.round((5.0/9.0)*(temp-32.0));
        else 
            return Math.round((9.0/5.0)*temp+32.0);
    }

    $.extend({
        simpleWeather: function(options){
            options = $.extend({
                location: '',
                woeid: '',
                unit: 'f',
                success: function(weather){},
                error: function(message){}
            }, options);

            var now = new Date();
            var weatherUrl = 'https://query.yahooapis.com/v1/public/yql?format=json&rnd=' + now.getFullYear() + now.getMonth() + now.getDay() + now.getHours() + '&diagnostics=true&callback=?&q=';

            if(options.location !== '') {
                /* If latitude/longitude coordinates, need to format a little different. */
                var location = '';
                if(/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/.test(options.location)) {
                    location = '(' + options.location + ')';
                } 
                else {
                  location = options.location;
                }

                weatherUrl += 'select * from weather.forecast where woeid in (select woeid from geo.places(1) where text="' + location + '") and u="' + options.unit + '"';
            } 
            else if(options.woeid !== '') {
                weatherUrl += 'select * from weather.forecast where woeid=' + options.woeid + ' and u="' + options.unit + '"';
            } 
            else {
                options.error('Could not retrieve weather due to an invalid location.');
                return false;
            }

            $.getJSON(
                encodeURI(weatherUrl),
                function(data) {
                    if(data !== null && data.query !== null && data.query.results !== null && data.query.results.channel.description !== 'Yahoo! Weather Error') {
                        var result = data.query.results.channel,
                        weather = {},
                        forecast;

                        weather.temp = result.item.condition.temp;
                        weather.currently = result.item.condition.text;
                        weather.units = {temp: result.units.temperature, distance: result.units.distance, pressure: result.units.pressure, speed: result.units.speed};

                        weather.alt = {temp: getAltTemp(options.unit, result.item.condition.temp), high: getAltTemp(options.unit, result.item.forecast[0].high), low: getAltTemp(options.unit, result.item.forecast[0].low)};

                        if(options.unit === 'f')
                            weather.alt.unit = 'c';
                        else 
                            weather.alt.unit = 'f';

                        options.success(weather);
                    } 
                    else 
                        options.error('Hmmm... looks like the weather information was lost on the way here');      
                }
            );    
            return this;
        }
    });
})(jQuery);
