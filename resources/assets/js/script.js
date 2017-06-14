// create bootstrap popover
$('#info').popover();   


// validate text inputs on page load 
var formValidation = new CityValidation(); 
formValidation.validateCities(); 


// validate text inputs on keyup
$('#cityOne, #cityTwo, #cityThree').on('keyup', function() {
    formValidation.validateCities(); 	
}); 


// create jQuery autocomplete validation 
function setAutoComplete (element) {
    $(element).autocomplete({
        source: function(request, response) {
            // remove whitespaces and escape regex chars
            var re = ($.ui.autocomplete.escapeRegex((request.term).replace(" ", "")));

            // if the trimmed result is nothing ignore it
            if (re == "") {
                return false; 
            }

            // case insensitive match, ignoring whitespaces in list
            // only matches beginning of word
            var matcher = new RegExp("^" + re, "i" );
            var result = $.grep( dropDown, function(item,index){
                item = item.replace(" ", ""); 
                return matcher.test(item);
            });
            response(result);
        }, 
        
        select: function(event, ui) {
            $(element).css('border-color', 'darkseagreen'); 

            if (size == 'desktop')
                var message = 'City found ✔'; 
            else 
                var message = 'City found'; 
            switch(element) {
                case '#cityOne':
                    $('#cityOneDetail').html(message);
                    break; 
                case '#cityTwo':
                    $('#cityTwoDetail').html(message);
                    break; 
                case '#cityThree': 
                    $('#cityThreeDetail').html(message);
                    break; 
            }
        }
    }); 
}

setAutoComplete('#cityOne'); 
setAutoComplete('#cityTwo'); 
setAutoComplete('#cityThree'); 


// generate placeholders for text inputs (if needed)
if(!$('#cityOne').val()){
    $('#magicOne').html('City One');
}
if(!$('#cityTwo').val()){
    $('#magicTwo').html('City Two');
}
if(!$('#cityThree').val()){
    $('#magicThree').html('City Three');
}


//adjust inputs/labels on focus 
function adjustInput(input, label, labelName) {
    $(input).on('focus', function() {
        $(input).val(''); 
        $(label).css({'font-size':'10px','padding-top':'0em', 'margin-top':'-1.8em'}).html(labelName);
        formValidation.validateCities();
    }); 
    $(label).on('click', function() {
        $(input).val('').focus(); 
        $(label).css({'font-size':'10px','padding-top':'0em', 'margin-top':'-1.8em'}).html(labelName);
        formValidation.validateCities();
    }); 
}
adjustInput('#cityOne', '#magicOne', 'City One'); 
adjustInput('#cityTwo', '#magicTwo', 'City Two'); 
adjustInput('#cityThree', '#magicThree', 'City Three'); 


// guide user down upon clicking first input 
if (size == 'desktop') {
    $('#cityOne, #magicOne').on('click', function() {
        $('html, body').animate({
            scrollTop: $('#cityOne').offset().top - 100
        }, 1200);
    });
}


// start mini intro after a second
// plays on $_GET-less load, not after submitting form
if (error == true || !cityEntered) {
    setTimeout(function() {  
        var tutorialMessages = ["...Find a city you'll love...", "...Enter a few places you already like...", "...And we'll crunch the numbers to find a match...", "Find this project on <a target='_blank' href='https://github.com/seanmisra/a3'>GitHub</a>"]; 

        $('#tutorialContent').hide().html(tutorialMessages[0]).fadeIn(3000).fadeOut(3000, function() {
            $('#tutorialContent').html(tutorialMessages[1]).fadeIn(3000).fadeOut(3000, function() {
                $('#tutorialContent').html(tutorialMessages[2]).fadeIn(3000).fadeOut(3000, function(){
                    $('#tutorialContent').html(tutorialMessages[3]).fadeIn(3000); 
                });
            });
        });

    }, 1000);
}


// update temperature value on input (permanent)
$('#temperature').on('input', function() {
    val = $(this).val(); 
    $('#currentTemp').html(Math.round(val)+'° F'); 
});


// update temperature on hover (not permanent)
$('#temperature').on('mouseover mousemove', function(e) {
    value = (e.offsetX/e.target.clientWidth)*40 + 40; 
    $('#magicTemp').fadeIn('slow');
    $('#magicTemp').html(Math.round(value)+'° F'); 
});
$('#temperature').on('mouseleave', function() {
    $('#magicTemp').fadeOut('slow');
});


// toggle max miles option based on "purpose"
var reasonForTrip = $('#purpose').val(); 
if (reasonForTrip == 'Close Trip')
    $('#closeTripOption').show(700);
else 
    $('#closeTripOption').hide();

$('#purpose').on('change', function() {
    var reasonForTrip = $('#purpose').val(); 
    if (reasonForTrip == 'Close Trip')
        $('#closeTripOption').show(700);
    else
        $('#closeTripOption').hide(700);
});


// change submit button style on hover
$( '#submitButton' ).hover(function() {
    $('#buttonText').css('margin-right', '25px');
    $('#arrowIcon').css('display', 'inline');
}, function() {
    $('#buttonText').css('margin-right', '0px');
    $('#arrowIcon').css('display', 'none');
});    
