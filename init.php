<?php
//todo needs some clean up.
//current all it does is initialize the db, Other functions may or may not need to be moved depending

include 'Database.php';
include 'LoginDB.php';
include 'RoutesDB.php';// extension example
/*
$mysqlInst = function($app){
    return function () use ($app) {
        $db_user = 'jackrobe-db';
        $db_pass = 'Ex2Sqdt4jhzPkO7M';

        //connect to database
        $app->mysqli = new mysqli("oniddb.cws.oregonstate.edu", $db_user, $db_pass, $db_user);
        if ($app->mysqli->connect_errno) {
            echo "Failed to connect to MySQL: (" . $app->mysqli->connect_errno . ") " . $app->mysqli->connect_error;
        } else {
            //echo "Connection to database was a success!<br>";//for debugging
        }
        return true;
    };
};

*/

$db = new Database();
$routes = new RoutesDB();
$lg = new LoginDB();


//todo this may or may not be optimal here
/******************************************************
 *
 *      FORM VALIDATIONS AND VARIABLES
 *
 *****************************************************/
//code from https://gist.github.com/voku/dd277e9c660f38b8c3a3 for dateCheck validation
/**
 * check for date-format
 *
 * @param string $date valid is only "YYYY-MM-DD"
 *
 * @return bool
 */
function checkDateFormat($date)
{
    // match the format of the date
    if (preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts))
    {

        // check whether the date is valid or not
        if (checkdate($parts[2],$parts[3],$parts[1])) {
            return true;
        } else {
            return false;
        }

    } else {
        return false;
    }
}

//credit http://www.benhallbenhall.com/2015/02/php-script-validate-phone-number
/**
 * Removes everything but numbers, then checks its size to ensure 10 or 7 numbers.
 *   NOTE: Does not support extensions
 * @param $phone mixed is the phone number but is treated as a string
 * @return
 **/
function isPhoneNumber($phone) {

    // Strips non numeric values out
    $numbers = preg_replace("%[^0-9]%", "", $phone );

    // Get the length of numbers supplied
    $length = strlen($numbers);

    // Validate size - must be 10 or 7
    if ( $length == 10 || $length == 7 ) {
        return $numbers;
    }

    // Was not a good number
    return false;

}


//credit http://www.qwc.me/2013/12/us-states-list-static-php-array-with.html
$usstates = array(
    'AK' => 'Alaska',
    'AZ' => 'Arizona',
    'AR' => 'Arkansas',
    'CA' => 'California',
    'CO' => 'Colorado',
    'CT' => 'Connecticut',
    'DE' => 'Delaware',
    'DC' => 'District of Columbia',
    'FL' => 'Florida',
    'GA' => 'Georgia',
    'HI' => 'Hawaii',
    'ID' => 'Idaho',
    'IL' => 'Illinois',
    'IN' => 'Indiana',
    'IA' => 'Iowa',
    'KS' => 'Kansas',
    'KY' => 'Kentucky',
    'LA' => 'Louisiana',
    'ME' => 'Maine',
    'MD' => 'Maryland',
    'MA' => 'Massachusetts',
    'MI' => 'Michigan',
    'MN' => 'Minnesota',
    'MS' => 'Mississippi',
    'MO' => 'Missouri',
    'MT' => 'Montana',
    'NE' => 'Nebraska',
    'NV' => 'Nevada',
    'NH' => 'New Hampshire',
    'NJ' => 'New Jersey',
    'NM' => 'New Mexico',
    'NY' => 'New York',
    'NC' => 'North Carolina',
    'ND' => 'North Dakota',
    'OH' => 'Ohio',
    'OK' => 'Oklahoma',
    'OR' => 'Oregon',
    'PA' => 'Pennsylvania',
    'RI' => 'Rhode Island',
    'SC' => 'South Carolina',
    'SD' => 'South Dakota',
    'TN' => 'Tennessee',
    'TX' => 'Texas',
    'UT' => 'Utah',
    'VT' => 'Vermont',
    'VA' => 'Virginia',
    'WA' => 'Washington',
    'WV' => 'West Virginia',
    'WI' => 'Wisconsin',
    'WY' => 'Wyoming');


