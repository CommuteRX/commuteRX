<?php
/**
 * Created by PhpStorm.
 * User: Robert
 * Date: 11/25/2015
 * Time: 8:33 PM
 */

//Get autocomplete HTML/JS from Google
include_once 'PlaceAutoComplete.php';
$start = makeAuto('start', 'form-control ', 'Starting Point');
$destination = makeAuto('end', 'form-control ', 'Destination');


?>
<div class="container-fluid">

    <!-- Route Adding -->
    <div class="row">
      <div class="col-sm-6, col-lg-6">
        <h1>Add a route:</h1>
      </div>
    </div>
    <div class="row">
        <div class="col-lg-5">
            <div class="row">
                <form action="" autocomplete="on" method="POST" class="route_entry form-inline">
                    <div class="row">
                        <div class="col-sm-9 col-lg-5">
                            <?php echo $autocompleteHelper->renderHtmlContainer($start);?>
                        </div>

                        <div class="col-sm-9 col-lg-5">
                            <?php echo $autocompleteHelper->renderHtmlContainer($destination);?>
                        </div>
                        <div class="col-sm-3 col-lg-2">
                            <input type="submit" class="btn btn-primary" value="Add Route" id="get_route"/>
                        </div>
                    </div>
                    <span class='msg'></span>
                    <div id="error"></div>
                    <br>

                </form>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <h1>My routes:</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    Routes go here
                </div>
            </div>
        </div>

    <!-- display map Results -->

        <div class="col-lg-4">
            <div id="map" style="height:400px; width:100%;"></div>
        </div>
        <!-- display Directionss -->
        <div class="col-lg-3">
            <div id="directions" ></div>
        </div>
   </div>

</div>




<script type="text/javascript">
    var test;
    //Adapted from the Google js API tutorials
    function initGMAP () {

        //directions

        var geo = new google.maps.Geocoder;

        var map;
        var origin_place_id = null;
        var destination_place_id = null;
        var travel_mode = google.maps.TravelMode.DRIVING;

        //MAP DISPLAY
        var map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: -34.397, lng: 150.644},
            zoom: 6
        });
        var infoWindow = new google.maps.InfoWindow({map: map});

        // Try HTML5 geolocation.
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {

                var pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                map.setCenter(pos);


            }, function() {
                handleLocationError(true, infoWindow, map.getCenter());
            });
        } else {
            // Browser doesn't support Geolocation
            handleLocationError(false, infoWindow, map.getCenter());
        }



        //DESTINATION FUNCTIONS
        var directionsService = new google.maps.DirectionsService;
        var directionsDisplay = new google.maps.DirectionsRenderer({
            draggable: true,
            map: map,
            panel: document.getElementById('directions')
        });
        var origin_input = document.getElementById('start');
        var destination_input = document.getElementById('end');
        var origin_autocomplete = new google.maps.places.Autocomplete(origin_input);
        origin_autocomplete.bindTo('bounds', map);
        var destination_autocomplete =
            new google.maps.places.Autocomplete(destination_input);
        destination_autocomplete.bindTo('bounds', map);

        function expandViewportToFitPlace(map, place) {
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }
        }

        //UPDATE MAP WHEN PLACE CHANGES
        origin_autocomplete.addListener('place_changed', function() {
            var place = origin_autocomplete.getPlace();
            if (!place.geometry) {
                window.alert("Autocomplete's returned place contains no geometry");
                return;
            }
            expandViewportToFitPlace(map, place);

            // If the place has a geometry, store its place ID and route if we have
            // the other place ID
            origin_place_id = place.place_id;
            route(origin_place_id, destination_place_id, travel_mode,
                directionsService, directionsDisplay);
        });

        destination_autocomplete.addListener('place_changed', function() {
            var place = destination_autocomplete.getPlace();
            if (!place.geometry) {
                window.alert("Autocomplete's returned place contains no geometry");
                return;
            }
            expandViewportToFitPlace(map, place);

            // If the place has a geometry, store its place ID and route if we have
            // the other place ID
            destination_place_id = place.place_id;
            route(origin_place_id, destination_place_id, travel_mode,
                directionsService, directionsDisplay);
        });

        function route(origin_place_id, destination_place_id, travel_mode,
                       directionsService, directionsDisplay) {
            if (!origin_place_id || !destination_place_id) {
                return;
            }
            directionsService.route({
                origin: {'placeId': origin_place_id},
                destination: {'placeId': destination_place_id},
                travelMode: travel_mode
            }, function(response, status) {
                if (status === google.maps.DirectionsStatus.OK) {
                    directionsDisplay.setDirections(response);
                    console.log(response);
                    //TODO GET THE LEGS of the ROUTE  into vars for STORAGE in our DB
                    test = response;

                } else {
                    window.alert('Directions request failed due to ' + status);
                }
            });
        }
    }

    //reverse geo the return position and add it to the field
    //@param at lat longitiude
    //return a formatted address string
    function getPlace(position){
        geo.geocode({'location': position}, function(results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                if (results[1]) {
                    return  results[1].formatted_address;

                } else {
                    return 'No results found'
                }
            } else {
                window.alert('Geocoder failed due to: ' + status);
            }

        });
    }


    //AUTOCOMPLETE CALLBACK
   // function load_ivory_google_map_api () { google.load("maps", "3", {"other_params":"libraries=places&language=en&signed_in=true","callback":load_ivory_google_place}); };
</script>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyALjLA0zJhijfA12n9JfPPftDPD2xXEZa8&libraries=places&callback=initGMAP"
        async defer></script>
<script type="text/javascript">

</script>


<script>

    $(document).ready(function()
    {

        $('#get_route').click(function(){ // Create `click` event function for login
            var start = $('#go'); // Get the username field
            var end = $('#end'); // Get the password field

            if(start.val() == ''){ // Check the username values is empty or not
                start.focus(); // focus to the filed
                login_result.html('<span class="error">Enter the starting point</span>');
                return false;
            }
            if(end.val() == ''){ // Check the password value is empty
                end.focus();
                login_result.html('<span class="error">Enter the destination</span>');
                return false;
            }
            if(start.val() != '' && end.val() != ''){ // Check the username and password values is not empty and make the ajax request
                var UrlToPass = 'action=gen_route&start='+start.val()+'&end='+end.val();
                $.ajax({ // Send the credential values to  ajaxLogin.php using Ajax in POST menthod
                    type : 'POST',
                    data : UrlToPass,
                    //TODO MAKE A FUNCTION HERE THAT ADDS THE ROUTES to the DB
                    url  : '',
                    success: function(responseText){ // Get the result and assign to each cases
                        //console.log(responseText);

                        if(responseText == ''){
                            // handle no reply
                            console.log(responseText);
                        }
                        else{

                         //responseText;
                            console.log(responseText);
                        }

                    }
                });
            }
            return false;
        });

        function handleResponse(response){

            response = ''
        }

    });


</script>





