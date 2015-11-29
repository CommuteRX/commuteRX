<?php

use Ivory\GoogleMap;
use Ivory\GoogleMap\MapTypeId;
use Ivory\GoogleMap\Services\BusinessAccount;

$GoogleAuth = json_decode(file_get_contents('http://'. $_SERVER['SERVER_NAME'].'/~jackrobe/gkey.json'));


$map = new GoogleMap\Map();

$dm  = new BusinessAccount($GoogleAuth->{'client_id'},$GoogleAuth->{'private_key'});

/*$directions->setBusinessAccount($dm);
$ba = $dir*/
/*


use Ivory\GoogleMap\Services\Directions\Directions;




$response = $directions->route('Colorado Springs', 'Denver');*/

use Ivory\GoogleMap\Services\Directions\DirectionsRequest;
use Ivory\GoogleMap\Services\Directions\Directions;
use Ivory\GoogleMap\Services\Directions\DirectionsStatus;
use Ivory\GoogleMap\Services\Base\TravelMode;
use Ivory\GoogleMap\Services\Base\UnitSystem;
use Widop\HttpAdapter\CurlHttpAdapter;
use Ivory\GoogleMap\Helper;

$mh = new Helper\MapHelper();
$directions = new Directions(new CurlHttpAdapter());
$request = new DirectionsRequest();


////// Set your origin
$request->setOrigin('Denver');
//$request->setOrigin(1.1, 2.1, true);
////
////// Set your destination
$request->setDestination('Colorado Springs');
//$request->setDestination(2.1, 1.1, true);

// Set your waypoints
$request->addWaypoint('Castle Rock');
//$request->addWaypoint(1.2, 2.2, true);

// If you use waypoint, optimize it
$request->setOptimizeWaypoints(true);

$request->setAvoidHighways(true);
$request->setAvoidTolls(true);
$request->setProvideRouteAlternatives(true);

$request->setRegion('us');
$request->setLanguage('en');
$request->setTravelMode(TravelMode::DRIVING);
$request->setUnitSystem(UnitSystem::IMPERIAL);
$request->setSensor(false);


// Route your request
$response = $directions->route($request);

$routes = $response->getRoutes();

$map->setPrefixJavascriptVariable('map_');
$map->setHtmlContainerId('map');

$map->setAsync(true);
$map->setAutoZoom(true);

$map->setCenter(0, 0, true);
$map->setMapOption('zoom', 3);

$map->setBound(-2.1, -3.9, 2.6, 1.4, true, true);

//$map->setMapOption('mapTypeId', MapTypeId::ROADMAP);
//$map->setMapOption('mapTypeId', 'roadmap');


//$map->setMapOption('mapTypeId', MapTypeId::HYBRID);
//$map->setMapOption('mapTypeId', 'hybrid');

$map->setMapOption('disableDefaultUI', true);
$map->setMapOption('disableDoubleClickZoom', true);
$map->setMapOptions(array(
    'disableDefaultUI'       => true,
    'disableDoubleClickZoom' => true,
));

$map->setStylesheetOption('width', '300px');
$map->setStylesheetOption('height', '300px');
$map->setStylesheetOptions(array(
    'width'  => '300px',
    'height' => '300px',
));

$map->setLanguage('en');



$test = $mh->render($map);
//$mapHTML = $mh->renderHtmlContainer($map);
//$mapJS = $mh->renderJsContainer($map);

echo $test;
//echo $mapHTML;

//echo $mapJS;
?>




    <div id="map_canvas">

    </div>
<div id="bar"></div>

