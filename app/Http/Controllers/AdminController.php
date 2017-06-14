<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Image; 
use Input;

class AdminController extends Controller
{   
    public function __invoke(Request $request) {
        
        //-------DATA RETRIEVAL/VALIDATION-------//
        
        # selected from drop-down by user
        $option = $request->input('option', '');

        if($_POST) {    
            # parse data already created
            $pattern = file_get_contents(database_path().'/regex.txt');
            $cityJson = file_get_contents(database_path().'/data.json');
            $cities = json_decode($cityJson, true); 
            ksort($cities); 
            $numberOfCities = count($cities);
            
            # data validation for entering a city
            if ($option == 'City') { 
                $vacationTraits = []; 
                $permanentTraits = []; 
                
                $inputCityName = $request->input('inputCityName','');
                $trait1V = $request->input('trait1V','');
                $trait2V = $request->input('trait2V','');
                $trait3V = $request->input('trait3V','');
                $trait4V = $request->input('trait4V','');
                $trait5V = $request->input('trait5V','');
                $trait6V = $request->input('trait6V','');
                $trait7V = $request->input('trait7V','');
                $trait8V = $request->input('trait8V','');
                $trait9V = $request->input('trait9V','');
                $trait10V = $request->input('trait10V','');

                $trait1P = $request->input('trait1P','');
                $trait2P = $request->input('trait2P','');
                $trait3P = $request->input('trait3P','');
                $trait4P = $request->input('trait4P','');
                $trait5P = $request->input('trait5P','');
                $trait6P = $request->input('trait6P','');

                $inputTemperature = $request->input('inputTemperature','');
                $inputLatitude = $request->input('inputLatitude','');
                $inputLongitude = $request->input('inputLongitude','');
                
                
                # regex to ensure a city name is not repeated
                $cityRegEx = 'regex:/^(?!'.$pattern.').+$/i';                 
                
                $rules = ['inputCityName' => array('required', $cityRegEx), 
                'trait1V' => 'required|regex:/^[-a-zA-Z ]+$/u', 
                'inputTemperature' => 'required|digits:2', 'inputLatitude' => 
                'required|numeric', 'inputLongitude' => 'required|numeric']; 
                
                
                # add rules for optional fields if entered
                function addRule ($input, $ruleIndex, &$vacationTraits, &$permanentTraits) {
                    if (trim($input) != '') {
                        $rules[$ruleIndex] = 'regex:/^[-a-zA-Z ]+$/u';  
                
                        # add to arrays (will be used later)                        
                        if (substr($ruleIndex, -1) == 'V') 
                            $vacationTraits[] = $input;
                        else
                            $permanentTraits[] = $input;
                    }
                }
                
                $keywords = [$trait2V, $trait3V, $trait4V, $trait5V, $trait6V, 
                $trait7V, $trait8V, $trait9V, $trait10V, $trait1P, $trait2P, 
                $trait3P, $trait4P, $trait5P, $trait6P]; 
                $ruleIndexes = ['trait2V', 'trait3V', 'trait4V', 'trait5V', 
                'trait6V', 'trait7V', 'trait8V', 'trait9V', 'trait10V', 
                'trait1P', 'trait2P', 'trait3P', 'trait4P', 'trait5P', 'trait6P'];  
                
                for($x = 0; $x<sizeof($keywords); $x++) {
                    addRule($keywords[$x], $ruleIndexes[$x], $vacationTraits, $permanentTraits); 
                }
                
                      
                # go back if validation fails
                $validator = Validator::make($request->all(), $rules); 
                if ($validator->fails()) 
                    return redirect('/admin')->withErrors($validator)->withInput(Input::all()); 
                
                else {
                    $numberOfCities++; 
                    array_unshift($vacationTraits, $trait1V);  
                }
            }
            
            
            # data validation for entering an image
            if ($option == 'Image') {
                $regPattern = 'regex:/^('.$pattern.')$/i'; 
                $imageRegEx= str_replace(' ', '_', $regPattern); 
                $imageNameValidation = array('required', $imageRegEx); 
                
                $rules = ['imageFileName' => $imageNameValidation, 
                'imageFileOpacity' => 'required|digits:2'];  
                $validator = Validator::make($request->all(), $rules); 
                if ($validator->fails()) 
                    return redirect('/admin')->withErrors($validator)->withInput(Input::all()); 
            }
            
        }
        
        
        
        //-------MAIN ACTIONS-------//

        if ($option == 'Compile') {             
            # create full, minified JSON
            $compiledCities = json_encode($cities);
            file_put_contents(database_path().'/cleanData.json', $compiledCities); 
            
            # create JSON w/ just city names
            foreach ($cities as $city){
                $map[] = $city['name']; 
            }
            $jsMap = json_encode($map);
            file_put_contents(database_path().'/map.json', $jsMap);  

            # create regex pattern w/ cities
            $pattern = ""; 
            
            for($x = 0; $x<$numberOfCities; $x++) {
                if($x != $numberOfCities - 1)
                    $pattern=$pattern.$map[$x].'|'; 
                else
                    $pattern=$pattern.$map[$x];
            }
            file_put_contents(database_path().'/regex.txt', $pattern); 
        }
        
        
        if ($option == 'City') {        
            # create new city array
            $newCity['name'] = $inputCityName; 
            $newCity['Vacation'] = $vacationTraits; 
            $newCity['Permanent'] = $permanentTraits; 
            $newCity['Temp'] = intval($inputTemperature); 
            $newCity['Latitude'] = floatval($inputLatitude); 
            $newCity['Longitude'] = floatval($inputLongitude); 


            # add new city
            $cities[$inputCityName] = $newCity;
            $cities = json_encode($cities, JSON_PRETTY_PRINT);

            file_put_contents(database_path().'/data.json', $cities);
        }
        
        
        if ($option == 'Image') {        
            $imageFileName = $request->input('imageFileName','');
            $imageFileOpacity = $request->input('imageFileOpacity', 30);

            # create new .png image in /public/images folder 
            $imagePath = 'images/'.$imageFileName.'.png'; 
            if ($imageFileName != '') {
                $img = Image::make($imagePath)->opacity(floatval($imageFileOpacity))->save($imagePath); 
            }
        }
        
        
        
        //-------SUCCESS MESSAGE-------//
        
        $successMessage = ''; 
        
        switch ($option) {
            case 'Compile':
                $successMessage = '<h2>City data compiled successfully!
                </h2>The cleanData. json, map.json, and regex.txt files
                have been regenerated. &#9786<br><br>'; 
                break; 
            case 'City': 
                $successMessage = '<h2>You just created a city called
                <strong>'.$inputCityName.'</strong></h2><p>The total
                city count is '.$numberOfCities.'</p>'; 
                break; 
            case 'Image': 
                $successMessage = '<h2>Success</h2><p>The pic,
                <strong>'.$imagePath.'</strong>, has been adjusted</p>'; 
                break; 
            default: 
                $successMessage = 'Error generating success message';       
        }
        
        
        
        //-------RETURN VIEW-------//

        return view('admin')->with([
            'option' => $option,
            'successMessage' => $successMessage
        ]); 
    }
}
