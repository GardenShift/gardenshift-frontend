//
//  Author : Hilay Khatri
// 

function update_maps()
    {
        
        // This is the main funtion that initializes the google map with the API key, makes an AJAX call to the REST APi to retrieve the data and show it on th google map
           
        $("#MainBodyId").append("<div id='google_canvas'><div id='map_canvas' style='position: absolute; width:600px; height:600px; z-index: 1; '></div></div>");  
        var data = $("#mapdataForm").serialize();
        
        var map;
        var geocoder = new google.maps.Geocoder();

        var raleigh = new google.maps.LatLng(35.7699298, -78.4469157);

        var mapOptions = {
            zoom: 8,
            center: raleigh,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map =  new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
 
        var markersArray = [];
        
        

        $.ajax({
        type:"POST",
        url:"http://test-gardenshift.rhcloud.com/index.php/pages/get_mapdata",
        data: data,
        success: function(response)
            {
                       
             
                 
                 var obj = jQuery.parseJSON(response);
                 
                  
                for(i=0; i< obj.length; i++)
                    {

                        if(!isNaN(obj[i].zipcode))
                            {

                                codeAddress(obj[i]);  

                            }


                    }                
                 
                 showOverlays();
               
            
                 

            }
        });

       
    
        

        function showOverlays() {
        if (markersArray) {
            for (i in markersArray) {
               
            markersArray[i].setMap(map);
                        
                        GEvent.addListener(markersArray[i], "click", function() {
                        
                        markersArray[i].openInfoWindow('My InfoWindow');
            });
         
   
}
            }
        }
        
        
        
        function codeAddress(obj) {
            
            // This function takes in the zip code and translates it into respective latitude and longitude. This function also populates the markersArray which stores the location of the markers
            
            var address = obj.zipcode;
            
            geocoder.geocode( {'address': address}, function(results, status) {
                
            
            if (status == google.maps.GeocoderStatus.OK) {  
                var marker = new google.maps.Marker({
                    map: map, 
                    position: results[0].geometry.location,
                    title: obj.username + " growing " + $('#crop_name').val()
  
                });
                markersArray.push(marker);
              
            } else {
                alert("Geocode was not successful for the following reason: " + status);
            }
            });
        }
           
            $( "#google_canvas" ).dialog({               
         
         
            modal: false,
            resizable: true,
            autoResize: false,       
            title: "Available Crops",
            overlay: {backgroundColor: "#0FF", opacity: 0.5},
            autoOpen: true,
            width: '620',
            height: '730',
          
            buttons: {
                    'Close': function() {
                        $("#mapdataForm")[0].reset();
                        $(this).dialog('close');
                        $(this).remove();
                        
                       
                    }
                               
                  }
            }
);
            
    }
    
    