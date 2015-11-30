<?php
session_cache_limiter(false);
session_start();
/**
 * Created by PhpStorm.
 * User: Robert
 * Date: 11/20/2015
 * Time: 2:19 PM
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__.'/vendor/autoload.php';
require_once 'init.php';  //currently just instanciating new classes


$app = new \Slim\Slim(array(
    'mode' => 'development'));

$app->add(new \Slim\Middleware\SessionCookie(array('secret' => 'myappsecret')));



//could be used to check if there is an sctive session
// currently not in use by the remainder of the router
$app->hook('slim.before.dispatch', function() use ($app) {
    $user = null;
    if (isset($_SESSION['userid'])) {
        $user = $_SESSION['userid'];
    }
    $app->view()->setData('userid', $user);
});

//***MAIN PAGE***//

$app->get('/', function () use ($app, $db){
    //If they are  logged in show them the dashboard
    if(isset($_SESSION['userid']) && $_SESSION['userid'] != ''){ // Redirect to secured user page if user logged in


        //todo figure out how to use $app->render('') it some how is using a templatgin system that might be too much for this project, but could be useful it worked on
        $page_title = "Dashboard";
        require_once 'header.php';
        require_once 'dashboard.php';
       //require_once 'footer.php';

    }else{

        require_once 'login.html';

    }

});


$app->get('/Dashboard', function () use ($app, $db){
    //If they are  logged in show them the dashboard
    if(isset($_SESSION['userid']) && $_SESSION['userid'] != ''){ // Redirect to secured user page if user logged in


        //todo figure out how to use $app->render('') it some how is using a templatgin system that might be too much for this project, but could be useful it worked on
        $page_title = "Dashboard";
        require_once 'header.php';
        require_once 'dashboard.php';
        //require_once 'footer.php';

    }else{

        require_once 'login.html';

    }

});

//***REGISTRATION***//

$app->get('/Register', function () {

    //If they are logged in send them here
    if(isset($_SESSION['userid']) && $_SESSION['userid'] != ''){ // Redirect to secured user page if user logged in

        $page_title = "Dashboard";
        require_once  'header.php';
        require_once 'dashboard.php';
        require_once  'footer.php';

    //Make them register
    }else{

        require_once 'accountRegister.html';

    }


});

//**-----------------------------VALIDATION FUNCTIONS

//**VALIDATE LOGIN***//
//NOte use of $db his calls the database php

$app->post('/validateLogin', function() use($app, $db){

    //Post vars go here
    $user = $app->request()->post('username');
    $pass = $app->request()->post('password');
    $action = $app->request()->post('action');

    $app->response()->header('Content-Type', 'application/json'); //VIP for JSON RESPONSES!!!
    // Check the action `login`
    if(isset($action) && $action == 'login'){

        $result = $db->login($user, $pass); // uses a database.php method

        // If no users exist with posted credentials
        if(!$result) {

            //deny login
            echo 0;
            //debug
            //echo $db->feedback;
        }
        else {

            //set them in the session
            $_SESSION['userid']  = $db->id;
            $_SESSION['username'] = $db->userName;

            //clear for takeoff!
            echo 1;//log the user in
            //todo not sure if better to let JS hnadle that, or php, probably php...
            //$app->redirect('');
        }
    }

});

//** REGISTRATION VALIDATION **//

$app->post('/validateRegistration', function() use($app, $db){

    //Post Vars
    $action = $app->request()->post('action');
    $username = $app->request()->post('register_username');
    $hash = $app->request()->post('register_password');
    $first_name = $app->request()->post('register_first_name');
    $last_name = $app->request()->post('register_last_name');

    //VIP for JSON RESPONSES!!!
    $app->response()->header('Content-Type', 'application/json');

    //if action
    if(isset($action) && $action == 'register'){

       $result = $db->check_userName($username);

        // If no users exist with posted credentials
        if($result ) {

            echo 0;//deny login
        }
        else {

            //register the user in the DB
            //TODO this could go wrong, but there is not a step yet to check it

            $db->registration($username, $hash, $first_name, $last_name);

            //set them in the session
            $_SESSION['userid']  = $db->id;
            $_SESSION['username'] = $db->userName;

            //clear for takeoff!
            //Todo we can change this at a future iteration
            //allow for messages, and other info to be sent back...or not
            echo 1;//log the user in
            //$app->redirect('Dashboard', 301);
        }
    }


});

//** LOGGED IN USERS- DASHBOARD */

$app->get('/Dashboard', function () {

    //If they are logged in send them here
    if(isset($_SESSION['userid']) && $_SESSION['userid'] != ''){ // Redirect to secured user page if user logged in

        $page_title = "Dashboard";  //used in header.php
        require_once  'header.php';
        require_once 'dashboard.php';
        require_once  'footer.php';

    //Make them register
    }else{

        require_once 'accountRegister.html';

    }

});


//***LOGOUT***//

$app->get('/Logout', function () use($app) {

        $page_title = "Logging you out";
        //require_once  'header.php';
        require_once 'logout.php';
        require_once  'footer.php';
        $app->redirect('Dashboard', 301);


});

///ADD NEW URL ROUTES HERE AS NECESSARY

$app->get('/Routes', function () use ($app, $db){
    //If they are  logged in show them the dashboard
    if(isset($_SESSION['userid']) && $_SESSION['userid'] != ''){ // Redirect to secured user page if user logged in

        $page_title = "Routes";
        require_once 'header.php';
        require_once 'viewRouteAdd.php';
        require_once 'footer.php';

    }else{

        require_once 'login.html';

    }

});


$app->post('/ListRoutes', function () use ($app, $routes){
    $response = [];
    //todo start checking not the user id but for an actual session id
    if(isset($_SESSION['userid']) && $_SESSION['userid'] != '') { // Redirect to secured user page if user logged in

        //Post Vars
        $action = $app->request()->post('action');

        //others
        $response['user'] = $_SESSION['userid'] . $_SESSION['username'];

        //VIP for JSON RESPONSES!!!
        $app->response()->header('Content-Type', 'application/json');

        //If there is a valid action
        if ($action == 'list_routes') { // Redirect to secured user page if user logged in

            //add the route to the DB
            if($usersRoutes = $routes->get_userRoutes($_SESSION['userid'])){

                $response['message'] = $routes->feedback;
                $response['data'] = $usersRoutes;

            //bad database
            }else {

                $response['message'] = $routes->feedback;
            }

            //no action set
        } else {

            $response['message'] = "Not Allowed";

        }
    }else{

        $response['message']= 'No Valid User Session for add routes' ;

    }
    $app->response->body( json_encode($response));

});


$app->post('/addRoute', function () use ($app, $routes, $db){

    $response = [];
    //todo start checking not the user id but for an actual session id
    if(isset($_SESSION['userid']) && $_SESSION['userid'] != '') { // Redirect to secured user page if user logged in

        //Post Vars
        $action = $app->request()->post('action');
        $start = $app->request()->post('start');
        $end = $app->request()->post('end');
        $name = $app->request()->post('name');

        //others
        $response['user'] = $_SESSION['userid'] . $_SESSION['username'];

        //VIP for JSON RESPONSES!!!
        $app->response()->header('Content-Type', 'application/json');

        //If there is a valid action
        if ($action == 'add_route') { // Redirect to secured user page if user logged in

            //add the route to the DB
            if($routes->addRoute($start, $end, $name, $_SESSION['userid'])){

                $response['message'] = $routes->feedback;

            //bad database
            }else {

                $response['message'] = $routes->feedback;
            }

         //no action set
        } else {

            $response['message'] = "Not Allowed";

        }
    }else{

        $response['message']= 'No Valid User Session for add routes' ;

    }
    $app->response->body( json_encode($response));

});


$app->get('/TESTDirection', function () use ($app){
    echo "Testing Routes";
    require_once 'testing/routeGeneratorTest.php';

});

$app->get('/TEST-DB', function () use($app, $lg, $db) {
    //explanation of some inheritance stuff in php

    $name = 'bevis';
    $name2 = 'butt-head';

    echo $lg->check_userName($name);  // NOTE WE HAVE ACCESS TO db functions
    echo $lg->feedback;  // and get the parents constructor vars
    //but not
    //echo $db->feedback; // because there is nothing from this declaration
    echo '<br />';
    //AND
    //
    $lg->check_userTEST($name2); // A function only in LoginDB
    echo $lg->feedback; //



});




$app->run(); //vip
?>
