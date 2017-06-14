function CityValidation() {								
    // city elements
    this.cityOne = document.getElementById('cityOne');  
    this.cityTwo = document.getElementById('cityTwo'); 
    this.cityThree = document.getElementById('cityThree'); 

    // city values
    this.cityOneValue = '';
    this.cityTwoValue = '';
    this.cityThreeValue = ''; 

    // city detail elements
    this.detailOne = document.getElementById('cityOneDetail'); 
    this.detailTwo = document.getElementById('cityTwoDetail'); 
    this.detailThree = document.getElementById('cityThreeDetail'); 

    // functions for city validation
    this.updateCities = updateCities; 
    this.validateCities = validateCities; 
    this.validate = validate;
}


// update city element values
function updateCities() {
    this.cityOneValue = (this.cityOne.value).toLowerCase();
    this.cityTwoValue = (this.cityTwo.value).toLowerCase();
    this.cityThreeValue = (this.cityThree.value).toLowerCase(); 
}


// validate all cities
function validateCities() {
    this.updateCities(); 
    this.validate(this.cityOne, this.cityOneValue, this.cityTwoValue, this.cityThreeValue, this.detailOne, 1); 
    this.validate(this.cityTwo, this.cityTwoValue, this.cityOneValue, this.cityThreeValue, this.detailTwo, 2); 
    this.validate(this.cityThree, this.cityThreeValue, this.cityOneValue, this.cityTwoValue, this.detailThree, 3); 
}

//validate an individual city
function validate(city, cityValue, checkOne, checkTwo, update, formNumber) {
    var result = regex.test(cityValue);
    var width = window.screen.width;

    if ((cityValue == checkOne || cityValue == checkTwo || result == false) && cityValue != '') {
        city.style.borderColor = 'lightpink';
        if (width > 800) {
            update.innerHTML='City not found or repeated';
        }
    }
    else if (result == true) {
        city.style.borderColor = 'darkseagreen';
        if (width > 800) {
            update.innerHTML='City found ✔';
        }
    }
    else {
        city.style.borderColor = 'lightgrey'; 
        if (formNumber == 1)	
            update.innerHTML='Enter a city you like (required)';
        else if (formNumber == 2)
            update.innerHTML='Enter a second city you like (optional)';
        else if (formNumber == 3)
            update.innerHTML='Enter a third city you like (optional)';
    }
}