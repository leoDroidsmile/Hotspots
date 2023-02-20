/**
 * Hotspots - Hotspots
 */

'use strict';

document.addEventListener('DOMContentLoaded', function () {
    (function () {
        const fill_auto_address = document.getElementById('fill_auto_address');
        
        fill_auto_address.addEventListener('click', event => {

            console.log("Auto fill button pressed");

            const hotspot_address = document.getElementById('hotspot-address').value;

            if(!hotspot_address)
                return;

            // Creating Our XMLHttpRequest object 
            var xhr = new XMLHttpRequest();
    
            // Making our connection  
            var url = 'https://api.helium.io/v1/hotspots/' + hotspot_address;
            xhr.open("GET", url, true);
    
            // function execute after request is successful 

            fetch(url, { method: 'GET' })
                .then(Result => Result.json())
                .then(response => {
                    console.log(response);
                    document.getElementById('hotspot-city').value = response.data.geocode.long_city;
                    document.getElementById('hotspot-state').value = response.data.geocode.long_state;
                    document.getElementById('hotspot-country').value = response.data.geocode.long_country;
                })
                .catch(errorMsg => { console.log(errorMsg); });
        });
    })();
});
