<h1>City Picker</h1> 

<h2>Overview</h2>
<p>The City Picker was created for CSCI E-15 Dynamic Web Applications at Harvard Extension, for the Spring '17 Semester. The app was built with the PHP Laravel framework. Users get city recommendations based on cities they already like, temperature preferences, and distance preferences. For users, the scope of the app is a single dynamic page. For 'administrators', a small Admin Panel is also available.</p>

<h2>Install</h2>
<p>Prereqs: composer and npm should already be installed. Instructions are available online if needed.</p>  
<ol>
  <li>Download repo: <code>git clone git@github.com:seanmisra/a3.git</code></li>
  <li>Within the a3 folder, add composer dependencies: <code>composer install</code></li>
  <li>Create your .env doc: <code>cp .env.example .env</code></li>
  <li>Update the Google Maps API key (MAP_KEY) in .env. You can obtain a key free from: https://developers.google.com/maps/documentation/javascript/get-api-key</li> 
  <li>Generate an app key: <code>php artisan key:generate</code></li>
  <li>Install npm dependencies (takes ~ 2 min): <code>npm install</code></li>
  <li>Ensure that your document root is set to the public directory</li>
</ol>

<h2>Update Content</h2> 
<p>If you are updating the code of the website, run <code>gulp --production</code> to combine+minify the javascript and css. If you are updating the city data, follow these steps: </p>
<ol>
  <li>Update the data in data.json either manually or via the Admin Panel (discussed next).</li>
  <li>Visit http://localhost/admin, and select the Compile option. Compilation entails sorting/minifying the JSON and pre-creating additional files which will aid server-side code at runtime. The APP_ENV must be set to "local" for the Admin Panel to be available. It will not be present in a "live" environment.</li>
</ol>

<h2>Admin Panel</h2> 
<p>The Admin Panel is available at http://localhost/admin. Here, an admin can compile data (which is necessary after updating the JSON file - see above). In addition, an admin can add a city to the JSON file, with up to 10 'Vacation' traits and 6 'Permanent' traits. Lastly, an admin can edit the opacity of an image in the public/images directory. Such a feature may be useful if image-editing software (e.g Photoshop) is not available. Since the conversion can take several minutes, it is recommended to change the max_execution_time in php.ini to 500.</p>

<h2>Security</h2> 
<p>I secured the site with https, in order to use the Geolocation API (browsers such as Chrome do not allow use of the API without a secure connection). The SSL certificate was obtained via Let's Encrypt. The app then uses cookies to store the user's location, which at times factors into the app's decision making. However, this data is not used for any other purpose and is erased immediately after the browsing session.</p>

<h2>Credits</h2> 
<ul> 
  <li>Laravel 5</li>
  <li>jQuery</li>
  <li>jQuery UI</li> 
  <li>Bootstrap</li> 
  <li>Font Awesome</li>
  <li>Laravel Debugbar</li> 
  <li>Laravel 5 log viewer</li> 
  <li>Intervention Image</li> 
  <li>Laravel Elixir</li>
  <li>Distance formula from: http://www.geodatasource.com/developers/php</li>
  <li>Slider structure: http://www.cssportal.com/style-input-range/</li>
  <li>simpleWeather.js, which uses the Yahoo Weather API</li> 
  <li>Google Maps JavaScript API</li>
  <li>All images are used responsibly. Most are from Pixabay; some are from Wikipedia</li> 
</ul> 
