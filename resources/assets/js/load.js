// establish desktop vs. mobile
if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent))
    var size = 'mobile'; 
else 
    var size = 'desktop'; 


// create headline of site 
function generateHeader() {
    if (cityEntered) {
        $('.jumbotron>h1').hide().html(chosenCity).fadeIn(3500);
        $('#tagline').html('Score: ' + score + '%');
    }
}


// display image only after it loads
function loadImage(path, fadeSpeed, afterFunction) {
    var img = new Image();
    img.src = path; 
    img.onload = function() {
        $('.visiblePage').fadeTo(fadeSpeed, 1);
        $('.loader').fadeOut('slow');

        generateHeader(); 

        $('.jumbotron').css({'background': 'url(' + path + ')' + 'no-repeat 50% 50% fixed', 'background-size': 'cover'}).fadeTo(fadeSpeed, 1, function() {
            afterFunction();
        }); 
        $('.footer').css({'background': 'url(' + path + ')' + 'no-repeat 50% 50% fixed', 'background-size': 'cover'}).fadeTo(fadeSpeed, 1);
    }
    img.onerror = function() {
        loadImage("/images/Default.png", 1000); 
    }
} 

// shake info globe to get attention
function shakeGlobe() {
    $('.glyphicon-globe').effect('shake', { times: 4, distance: 4}, 1000 ); 
}


// attempt to load image if device is a desktop
// load plane image on error or if no city image found
// load Venice cityscape as default homepage
if (size == 'desktop') {
    if (error == true) {
        loadImage("/images/Default.png", 1000);  
    }
    else if (chosenCity == "no city found") {
        loadImage("/images/Home.png", 1000);  
    }
    else {
        loadImage(imagePath, 2000, shakeGlobe); 
    }
}
else {
    $('.loader').fadeOut('slow');
    $('.visiblePage').fadeTo(2000, 1);
    generateHeader(); 
}
