/**
 * Hotspots - Hotspots
 */

'use strict';

// Get Hotspot Data with address
document.addEventListener('DOMContentLoaded', function () {
    (function () {
        const fill_auto_address = document.getElementById('fill_auto_address');
        
        if(fill_auto_address){
            fill_auto_address.addEventListener('click', event => {

                console.log("Auto fill button pressed");
    
                const hotspot_address = document.getElementById('hotspot-address').value;
    
                if(!hotspot_address)
                    return;
    
                // Creating Our XMLHttpRequest object 
                var xhr = new XMLHttpRequest();
        
                // Making our connection  
                var url = 'https://etl.api.hotspotrf.com/v1/hotspots/' + hotspot_address;
                xhr.open("GET", url, true);
        
                // function execute after request is successful 
    
                fetch(url, { method: 'GET' })
                    .then(Result => Result.json())
                    .then(response => {
                        if(response.hasOwnProperty('error')){
                            alert(response['error']);
                            return;
                        }
                        console.log(response);
                        document.getElementById('hotspot-name').value = response.data.name;
                        document.getElementById('hotspot-city').value = response.data.geocode.long_city;
                        document.getElementById('hotspot-state').value = response.data.geocode.long_state;
                        document.getElementById('hotspot-country').value = response.data.geocode.long_country;
                    })
                    .catch(errorMsg => { 
                        console.log(errorMsg); 
                    });
            });
        }
        
    })();
});
