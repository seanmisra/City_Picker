// -----This JS file loads the two bottom information squares (w/ the weather and city traits)-----//


// display background image only after it loads
// options: string of optional background CSS properties
// execute: a function to execute after the image has loaded
function syncBackgroundLoad(imgPath, element, options, execute) {
    if (options == null)
        options = ''; 
    var img = new Image(); 
    img.src = imgPath; 
    img.onload = function() {
        $(element).css('background', 'url('+imgPath+')' + options); 
    }
    if (execute != null)
        execute(); 
}     


// initiate process of displaying city info
function cityInfo(callback) {
    html = '<h3>Why ' + chosenCity + "?</h3><br><br><h1><div id='cityReasons'></div></h1>"; 
    $('#infoBlock').html(html);
    syncBackgroundLoad(imagePath, '#infoBlock', '50% 50%', callback); 
}


// cycles the cityTrait array in the info box
function cycleCity() {
    var index = -1;
    var reasonInterval = setInterval(function() {
        index++;
        if (index >=cityTraits.length) 
            index = 0;
        $('#cityReasons').html(cityTraits[index]); 
    }, 500);
}


// weather Reg Exp's 
exprPartlyCloudy = /partly cloudy|mostly clear/i; 
exprCloudy = /cloudy|breezy/i; 
exprRain = /rain|showers/i;
exprSun = /sun|clear/i;
exprThunder = /thunder/i;
exprSnow = /snow/i;   


function loadWeather(location, woeid) {
    $.simpleWeather({
        location: location,
        woeid: woeid,
        unit: 'f',
        success: function(weather) {
            // create weather content
            html = '<h3>Weather Now</h3>'; 
            html += '<br><br><h1 id="cityTemp">'+weather.temp+'&deg;'+weather.units.temp+'</h1>';
            html += "<span id='weatherDescrip'>"+weather.currently+"</span";
            $('#weatherBlock').html(html);
            var unitSwitch = 0; 
            var reasonInterval = setInterval(function() {
                if (unitSwitch == 0) {
                    $('#cityTemp').html(weather.alt.temp+'&deg;C');  
                     unitSwitch = 1; 
                }
                else {
                    $('#cityTemp').html(weather.temp+'&deg;'+weather.units.temp);   
                    unitSwitch = 0;
                }   
            }, 6000)


            // display appropriate weather image based on weather description
            if (exprPartlyCloudy.test(weather.currently)) 
                syncBackgroundLoad('images/some_clouds.png', '#weatherBlock'); 
            else if (exprCloudy.test(weather.currently)) 
                syncBackgroundLoad('images/more_clouds.png', '#weatherBlock'); 
            else if (exprRain.test(weather.currently))
                syncBackgroundLoad('images/rain.png', '#weatherBlock');  
            else if (exprSun.test(weather.currently))
                syncBackgroundLoad('images/sun.png', '#weatherBlock'); 
            else if (exprThunder.test(weather.currently))
                syncBackgroundLoad('images/thunder.png', '#weatherBlock'); 
            else if (exprSnow.test(weather.currently))
                syncBackgroundLoad('images/snow.png', '#weatherBlock');   
            else 
                syncBackgroundLoad('images/some_clouds.png', '#weatherBlock');   

            // display city info
            cityInfo(cycleCity); 
        },

        error: function(error) {
            // create weather content
            html = '<h3>Avg Weather</h3>'; 
            html += '<br><br><h1 id="cityTemp">'+cityTemperature+'&deg;'+'F'+'</h1>';
            html += "<span id='weatherDescrip'>"+'Annual Average'+"</span";
            $('#weatherBlock').html(html);

            syncBackgroundLoad('images/some_clouds.png', '#weatherBlock'); 

            // display city info 
            cityInfo(cycleCity);    
        }
    });
}


// DOMINOE POKE: sets all functions above into motion
loadWeather(cityLatitude+','+cityLongitude); 