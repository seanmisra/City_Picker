<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CityData;
use Validator;
use Input;

class HomeController extends Controller
{   
    
    public function __invoke(Request $request) {     
        
        //-------DATA RETRIEVAL/VALIDATION-------//
        
        # regex for validation
        $pattern = file_get_contents(database_path().'/regex.txt');
        $regPattern = 'regex:/^('.$pattern.')$/i'; 
        
        $cityOne = $request->input('cityOne','');
        $cityTwo = $request->input('cityTwo','');
        $cityThree = $request->input('cityThree','');
        $temperature = round($request->input('temperature', 60));
        $purpose = $request->input('purpose', ''); 
        $tripDistance = $request->input('tripDistance', '');        
        
        $submitted = false;
        
        # validate if data submitted
        if($_GET) {
            $submitted = true; 
            
            # build validation rules
            $cityOneValidation = array('required', $regPattern); 
            $cityTwoValiadation = array($regPattern); 
            $cityThreeValidation = array($regPattern); 
            $rules = ['cityOne' => $cityOneValidation, 'temperature' => 
            'required|numeric|between:20,80', 'purpose' => 'required|in:Permanent,Vacation,Close Trip', 
            'tripDistance' => 'numeric|min:50'];
                    
            # add rules for city two/three, if filled out 
            if (trim($cityTwo) != '')
                $rules['cityTwo'] = array($regPattern); 
            if (trim($cityThree) != '')
                $rules['cityThree'] = array($regPattern); 
            
            $validator = Validator::make($request->all(), $rules); 
            if ($validator->fails()) 
                return redirect('/')->withErrors($validator)->withInput(Input::all());       
        }
        
        
        
        //-------MISC. DATA HANDLING-------//

        # cookie variables -- for geolocation
        $latitude = 0; 
        $longitude = 0; 
        $geolocation = false; 
        if (isset($_COOKIE['latitude'])) {
            $latitude = $_COOKIE['latitude'];
            $longitude = $_COOKIE['longitude'];
            $geolocation = true; 
        }
        
        # Google Maps variables
        $mapKey = \Config::get('app.mapKey');   
        $googleLink = 'https://maps.googleapis.com/maps/api/js?key='.$mapKey.'&callback=initMaps'; 
        
    

        //-------CITY SELECTION-------//
        
        # parse data from JSON file
        $cityData = new CityData(database_path().'/cleanData.json'); 
        $jsMap = $cityData->jsMap; 
        
        # set defaults
        $chosenCity = 'no city found'; 
        $score = 0; 
        $cityLongitude = 0; 
        $cityLatitude = 0; 
        $cityTemperature = 0; 
        $cityTraits = json_encode(['nothing']);
        $numberOfCities= 0;
        $perfectCitySize= 0;

        if ($submitted) { 
            $cityData->findCity($cityOne, $cityTwo, $cityThree, $purpose, $temperature, 
            $longitude, $latitude, $tripDistance); 
            $chosenCity = $cityData->chosenCity;
            
            $score = $cityData->maxScore; 
            $cityLongitude = $cityData->cityLongitude;
            $cityLatitude = $cityData->cityLatitude;
            $cityTemperature = $cityData->cityTemperature;
            $cityTraits = $cityData->cityTraits; 
            $numberOfCities = $cityData->numberOfCities; 
            $perfectCitySize = $cityData->perfectCitySize; 
            
            if ($chosenCity == 'no city found') {
                return redirect('/')->withInput(Input::all()); 
            }
        }
           
        
    
        //-------FRONT-END MESSAGES-------//
        
        if ($submitted) {
            # random messages displayed as page loads
            $loadStatements = ['Our algorithms are currently getting a workout', 
            'We only have to travel the world a few more times', 
            'Our friendly city matchbot will be right with you', 
            'Androids dream of electric skies, oceans, and trees', 
            "We're deciding between two or three top choices"];  
            shuffle($loadStatements); 
            $loadStatement = '...'.array_pop($loadStatements).'...'; 
            
            # pop-up message
            $popTitle = '<h3>Results</h3>'; 
            $popContent = "We picked <strong>".$chosenCity."</strong> from a 
            dataset of <strong>".$numberOfCities."</strong> cities. We considered 
            <strong>".$perfectCitySize." </strong>traits that you might like in 
            a city. New data is added daily, so our results are continuously 
            improving.<br><br><div class='iconSet' style='text-align: center;'>
            <a target='_blank' href='https://github.com/seanmisra/a3'class='fa fa-github-square' 
            style='font-size: 50px;' aria-hidden='true'></a><a target='_blank'
            href='https://twitter.com/seanmisra'class='fa fa-twitter-square' 
            style='font-size: 50px;' aria-hidden='true'></a></div><br>"; 
            
        }
        else {
            # message displayed as page loads
            $loadStatement = '...The City Picker is flying to a computer near you...'; 
           
            # pop-up message
            $popTitle = '<h3>Welcome</h3>'; 
            $popContent = "Enter 1 - 3 cities you aleady like and an ideal 
            temperature, and we'll sift through our data to calculate the best 
            fit. More cities and data are added daily. Thanks for stopping by!
            <br><br><div class='iconSet' style='text-align: center;'><a target='_blank' 
            href='https://github.com/seanmisra/a3'class='fa fa-github-square' 
            style='font-size: 50px;' aria-hidden='true'></a> 
            <a target='_blank' href='https://twitter.com/seanmisra'class='fa fa-twitter-square' 
            style='font-size: 50px;' aria-hidden='true'></a></div><br>";
        }
        
        
        
        //-------RETURN VIEW-------//
        
        return view('home')->with([ 
            'submitted' => $submitted,
            
            # city data and meta-data
            'cityOne' => $cityOne,
            'cityTwo' => $cityTwo,
            'cityThree' => $cityThree,
            'temperature' => $temperature,
            'purpose' => $purpose,
            'tripDistance' => $tripDistance,
            'jsMap' => $jsMap,
            'cityData' => $cityData,
            'chosenCity' => $chosenCity,
            'score' => $score,
            'pattern' => $pattern,
            'geolocation' => $geolocation,
            'cityLongitude' => $cityLongitude,
            'cityLatitude' => $cityLatitude,
            'cityTemperature' => $cityTemperature,
            'cityTraits' => $cityTraits,
            'numberOfCities' => $numberOfCities,
            'perfectCitySize' => $perfectCitySize,
            
            # Google Map variables
            'googleLink' => $googleLink, 
            'mapKey' => $mapKey,
            
            # display variables
            'loadStatement' => $loadStatement,
            'popTitle' => $popTitle,
            'popContent' => $popContent,
        ]);
    }
}
