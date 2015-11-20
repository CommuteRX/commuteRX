<?php
include 'init.php';

if(!isset($_SESSION['userid']) || $_SESSION['userid'] == ''){
    echo '<script type="text/javascript">window.location = "index.php"; </script>';
}

$current_user = $_SESSION['userid'];
$user_name = $_SESSION['username'];

$user_page_location = "My Dashboard";
$page_title = "CommuteRX-Dashboard";




include 'header.php';


include 'footer.php';
