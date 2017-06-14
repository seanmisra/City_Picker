<?php

namespace App;

class CityData {
    const TRAIT_SCORE = 5; # increase to assign more value to city traits
    const TEMP_BASE = 40; # increase to assign more value to temperature
    const SCORE_WEIGHT = 1.2; # weights total score 
    
    //"world" variables (all cities)
    public $cities; 
    public $map; 
    public $jsMap; 
    public $numberOfCities; 
    public $pattern; 
	
    //user preferences
    private  $cityOne;
    private  $cityTwo; 
    private  $cityThree;
    private  $purposeForTravel;  
    private  $preferredTemp;
    public $longitude;
    public $latitude;
    public $tripDistance;
    
    //user meta-data
    private $perfectCity = []; 
    private $citiesInputed = 1;
    public $perfectCitySize;
	
    //score variables
    private $distScores;  # for geolocation
    private $tempScores;  # for temperature 
    private $traitScores; # for city traits 
    private $totalScores; 
    
    //chosen city (output)
    public $chosenCity = 'no city found';  
    public $cityLongitude;
    public $cityLatitude;
    public $cityTemperature;
    public $cityTraits;
    public $maxScore; # score of chosen city
	
	
    // reads city data from JSON files
    public function __construct($jsonPath) {
        # get all city Data
        $cityJson = file_get_contents($jsonPath); 
        $this->cities = json_decode($cityJson, true); 
        $this->numberOfCities = count($this->cities);
		
        # get list of cities
        $this->jsMap = file_get_contents(database_path().'/map.json'); 
        $this->map = json_decode($this->jsMap, true);  
    }
	
	
    // **find the chosenCity** 
    // runs helper functions to find best city based on inputs 
    public function findCity($cityOne, $cityTwo='', $cityThree='', 
        $purpose, $temperature, $longitude, $latitude, $tripDistance) {
        # set object variables
        $this->purposeForTravel = $purpose;
        $this->preferredTemp = $temperature;
        $this->cityOne = $cityOne; 
        $this->cityTwo = $cityTwo; 
        $this->cityThree = $cityThree; 
        $this->longitude = $longitude;
        $this->latitude = $latitude; 
        $this->tripDistance = $tripDistance; 
        if(isset($cityTwo))
            $this->citiesInputed++; 
        if(isset($cityThree))
            $this->citiesInputed++; 
		
        #calculate all city scores
        $this->perfectCity(); 
        if ($this->purposeForTravel == 'Close Trip')
            $this->calcDistScores();
        $this->calcTempScores(); 
        $this->calcTraitScores(); 
        $this->calcTotalScores(); 
        $this->weightScore(); 
        
        # set chosen city variables
        if ($this->chosenCity != 'no city found') {
            $this->setCoordinates($this->chosenCity);
            $this->setTraits($this->chosenCity);
            $this->setTemperature($this->chosenCity); 
        }
    }
	
	
    // return "case-correct" version of city Name
    private function cityName($input) {
        $regExString = '/'.$input.'/i'; 
        $regExArray = preg_grep($regExString, $this->map); 
        return $userCity = array_pop($regExArray);
    }
    
    
    // takes the user's three entered cities 
    // creates an array (perfectCity) of all qualities the user is looking for 
    private function perfectCity() {
        $this->addCity($this->cityOne);
        $this->addCity($this->cityTwo);
        $this->addCity($this->cityThree);
        if ($this->perfectCity != '')
            $this->perfectCitySize = sizeof(array_unique($this->perfectCity)); 
    } 
    
    
    // adds one city to the perfectCity array 
    private function addCity($inputCity) {
        if($inputCity == '')
            return; 
        $userCity = $this->cityName($inputCity); 
                  
        # always add Vacation qualities
        $this->perfectCity = array_merge($this->perfectCity, 
            $this->cities[$userCity]['Vacation']); 
            
        # add Permanent qualities if purpose is Permanent 
        if($this->purposeForTravel == 'Permanent')
            $this->perfectCity = array_merge($this->perfectCity, 
                $this->cities[$userCity]['Permanent']); 
    }
    
    
    // SOURCE: geodatasource.com
    // URL: http://www.geodatasource.com/developers/php
    private function distance($lat1, $lon1, $lat2, $lon2) {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  
        cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return $miles;
    }

    
    // calculate distance from user to every city 
    // only applicable for 'Day Trip' option - location tracking must be enabled 
    private function calcDistScores() {
        foreach ($this->cities as $city) {            
            $cityLat = $city['Latitude'];
            $cityLong = $city['Longitude'];            
            $distance = $this->distance($cityLat, $cityLong, 
                $this->latitude, $this->longitude);
            $this->distScores[] = $distance;    
        }
    }
    
    
    // sets the coordinates of the chosen city
    private function setCoordinates($chosenCity) {
        $this->cityLongitude = $this->cities[$chosenCity]['Longitude']; 
        $this->cityLatitude = $this->cities[$chosenCity]['Latitude'];
    }
	
    
    // sets the temperature of the chosen city
    private function setTemperature($chosenCity) {
        $this->cityTemperature = $this->cities[$chosenCity]['Temp']; 
    }
    
    
    // set the cityTraits array
    private function setTraits($chosenCity) {
        foreach($this->cities[$chosenCity]['Vacation'] as $cityTrait) {
            if (substr($cityTrait, 1) != 'x') {
                # if x is first letter exclude trait
                # gives some flexibility when entering data
                $this->cityTraits [] = $cityTrait;  
            }
        }
        $this->cityTraits = json_encode($this->cityTraits);
    }
        
    
    // calculates temperature scores for each city
    // the higher the score, the more favorable a city's temp for user 
    private function calcTempScores() {
        foreach ($this->cities as $city){
            $cityTemp = $city['Temp']; 
            $difference  = abs($this->preferredTemp - $cityTemp); 
            $score = ((self::TEMP_BASE - $difference)/4)*$this->citiesInputed; 
            $this->tempScores[] = $score; 
        }
    }
	
	
    // calculates trait scores for each city
    // does this by comparing each city to the perfectCity array
    private function calcTraitScores() {
        # scale trait scores based on trip purpose
        $traitScore = ($this->purposeForTravel == 'Vacation') ? self::TRAIT_SCORE 
            : self::TRAIT_SCORE*.75; 
        
        foreach ($this->cities as $city){
            $score = 0; 
            foreach($this->perfectCity as $desiredCityTrait) {
                if (in_array($desiredCityTrait, $city['Vacation'])) {
                    $score += $traitScore; 
                }
            }
            if($this->purposeForTravel == 'Permanent') {
                foreach($this->perfectCity as $desiredCityTrait) {
                    if(in_array($desiredCityTrait, $city['Permanent']))
                        $score += $traitScore; 
                }
            }
            $this->traitScores[] = $score; 	
        }
    }
	
	
    // calculates total scores for each city and a maxScore
    // selects a chosenCity w/ the maxScore 
    private function calcTotalScores() {
        $enteredCities = [trim(strtolower($this->cityOne)), 
        trim(strtolower($this->cityTwo)), trim(strtolower($this->cityThree))];   
        
        for($x=0; $x<$this->numberOfCities; $x++) {
            $this->totalScores[$x] = $this->tempScores[$x] + $this->traitScores[$x];
            if ($this->totalScores[$x] > $this->maxScore) {
                $testCity = $this->map[$x];                      
                if (!in_array(strtolower($testCity), $enteredCities)) {
                    # if Day Trip selected, will bypass certain cities too far away
                    if($this->purposeForTravel == 'Close Trip') {
                        if($this->distScores[$x] > $this->tripDistance)
                            continue; 
                    }
                    $this->maxScore = $this->totalScores[$x];
                    $this->chosenCity = $testCity;                         
                }
            }
        }
    }
	
    
    // weights maxScore based on constant
    private function weightScore() {
        $this->maxScore = round($this->maxScore*self::SCORE_WEIGHT); 
    }
	
} # end of class  