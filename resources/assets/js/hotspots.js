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
                        document.getElementById('hotspot-name').value = response.data.name;
                        document.getElementById('hotspot-city').value = response.data.geocode.long_city;
                        document.getElementById('hotspot-state').value = response.data.geocode.long_state;
                        document.getElementById('hotspot-country').value = response.data.geocode.long_country;
                    })
                    .catch(errorMsg => { 
                        console.log(errorMsg); 
                    });
                
                // Get Witness, Beacon, Invalid

                url = 'https://etl.hotspotty.org/api/v1/hotspots/witnesses-lean';
                xhr.open("POST", url, true);
        
                // function execute after request is successful 
    
                fetch(url, { 
                    method: 'POST',
                    headers : {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        "hotspotIds":[hotspot_address]
                    })
                }).then(Result => Result.json())
                    .then(response => {
                        var witness_data = response['data'][0];

                        document.getElementById('Beacon').value = witness_data['wB']['a'];
                        document.getElementById('Beacon_Invalid').value = witness_data['wB']['i'];
                        
                        document.getElementById('Witness').value = witness_data['wO']['a'];
                        document.getElementById('Witness_Invalid').value = witness_data['wO']['i'];
                        
                        document.getElementById('Bdirect_Witness').value = witness_data['b']['a'];
                        document.getElementById('Bdirect_Witness_Invalid').value = witness_data['b']['i'];
                    })
                    .catch(errorMsg => { 
                        console.log(errorMsg); 
                    });
            });
        }
        
    })();
});
