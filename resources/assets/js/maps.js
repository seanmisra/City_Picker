function initMaps() {
    if(cityEntered) {
        var position = {lat: cityLatitude, lng: cityLongitude};

        // map one 
        var map = new google.maps.Map(document.getElementById('mapBlock'), {
            zoom: 5,
            center: position
        });
        var marker = new google.maps.Marker({
            position: position,
            map: map
        });

        // map two
        var mapTwo = new google.maps.Map(document.getElementById('mapZoom'), {
            center: position,
            zoom: 10
        });
    }
}