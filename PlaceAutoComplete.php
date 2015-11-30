<?php

use Ivory\GoogleMap;
use Ivory\GoogleMap\MapTypeId;
use Ivory\GoogleMap\Services\BusinessAccount;

$GoogleAuth = json_decode(file_get_contents('http://'. $_SERVER['SERVER_NAME'].'/~jackrobe/gkey.json'));


use Ivory\GoogleMap\Places\Autocomplete;
use Ivory\GoogleMap\Places\AutocompleteComponentRestriction;
use Ivory\GoogleMap\Places\AutocompleteType;
use Ivory\GoogleMap\Helper\Places\AutocompleteHelper;



/**
 * @param $id
 * @param $class
 * @param $value
 * @return Autocomplete
 * @throws GoogleMap\Exception\AssetException
 * @throws GoogleMap\Exception\PlaceException
 */
function makeAuto( $id, $class, $value){

    $field = new Autocomplete();

    $field->setPrefixJavascriptVariable('autocomplete_'. $id);
    $field->setInputId($id);

    $field->setInputAttribute('class', $class);

    $field->setValue($value);

    $field->setTypes(array(AutocompleteType::GEOCODE));
    $field->setComponentRestrictions(array(AutocompleteComponentRestriction::COUNTRY => 'us'));
    $field->setBound(-2.1, -3.9, 2.6, 1.4, true, true);

    $field->setAsync(true);
    $field->setLanguage('en');

    return $field;
}

$autocompleteHelper = new AutocompleteHelper();



/*
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

return $response;*/


/*
$map->setPrefixJavascriptVariable('map_');
$map->setHtmlContainerId('map');

$map->setAsync(true);
$map->setAutoZoom(true);

$map->setCenter(0, 0, true);
$map->setMapOption('zoom', 3);

$map->setBound(-2.1, -3.9, 2.6, 1.4, true, true);

$map->setMapOption('mapTypeId', MapTypeId::ROADMAP);
$map->setMapOption('mapTypeId', 'roadmap');

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

$map->setLanguage('en');*/




?>


