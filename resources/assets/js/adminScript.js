// update drop-down menu on page load 
var menu = $('select[name=option]');
selected = menu.val(); 
changeDisplay(selected); 

// update drop-down menu when changed 
menu.change(function() {
    var selected = menu.val(); 
    changeDisplay(selected); 
});      

function changeDisplay(value) {
    switch(value) {
        case 'City': {
            $('.compileData').hide(300); 
            $('.newCity').show(300); 
            $('.imageOpacity').hide(300); 
            break; 
        }
        case 'Image': {
            $('.compileData').hide(300); 
            $('.newCity').hide(300); 
            $('.imageOpacity').show(300); 
            break; 
        }
        default: {
            $('.compileData').show(300); 
            $('.newCity').hide(300); 
            $('.imageOpacity').hide(300);  
        }    
    }
}
