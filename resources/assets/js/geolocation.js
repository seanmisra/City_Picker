function geoLocateUser() {
    if(navigator.geolocation) {
        // will call success() if location found; or fail() if error
        navigator.geolocation.getCurrentPosition(success, fail);
    }

    
    function success(position) {
        var latitude = position.coords.latitude; 
        var longitude = position.coords.longitude;
        document.cookie = 'latitude='+latitude;
        document.cookie = 'longitude='+longitude;
    }

    
    function fail(err) {
        switch(err.code) {
            case err.PERMISSION_DENIED:
                $('select[name=purpose] > option:first-child').text('Close Trip: N/A - Tracking disabled').attr('disabled','disabled'); 
                break;
            case err.POSITION_UNAVAILABLE:
                $('select[name=purpose] > option:first-child').text('Close Trip: N/A - Position Unavailable').attr('disabled','disabled');
                break;
            case err.TIMEOUT:
                $('select[name=purpose] > option:first-child').text('Close Trip: N/A - Timeout').attr('disabled','disabled'); 
                break;
            case err.UNKNOWN_ERROR:
                $('select[name=purpose] > option:first-child').text('Close Trip: N/A - Error with Tracking').attr('disabled','disabled'); 
                break;
        }
        $('select[name=purpose]').val('Vacation'); 
        $('#closeTripOption').hide(700);
    }
}

// attempt to set location
geoLocateUser(); 
