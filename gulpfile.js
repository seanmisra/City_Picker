var elixir = require('laravel-elixir');

elixir(function(mix) {
    mix.scripts([
        'jquery-3.2.0.min.js',
        'bootstrap.min.js',
        'jquery-ui.min.js',
        'load.js',
        'validation.js',
        'script.js',
        'geolocation.js',
        'maps.js',
        'jquery.simpleWeather.js',
        'info.js'
    ], 'public/js/allScripts.js')
    .scripts([
        'jquery-3.2.0.min.js',
        'bootstrap.min.js',
        'adminScript.js'
    ], 'public/js/allScriptsAdmin.js'); 
    
    mix.styles([
        'jquery-ui.min.css',
        'style.css'
    ]);
});
