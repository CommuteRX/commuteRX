<?php
include 'init.php';

// Check the action `login`
if(isset($_POST['action']) && $_POST['action'] == 'login')
{

    $username = mysqli_real_escape_string($mysqli, $_POST['username']);
    $password = md5(mysqli_real_escape_string($mysqli, $_POST['password']));

    $query = mysqli_query($mysqli,"SELECT * FROM crx_user WHERE user_name ='$username' and hash='$password'");
    $num_rows = mysqli_num_rows($query); // Get the number of rows


    // If no users exist with posted credentials print 0 like below.
    if($num_rows <= 0)
    {
        echo 0;//deny login
    }
    else
    {
        $fetch = mysqli_fetch_array($query);

        // NOTE : We have already started the session in the init.php
        $_SESSION['userid'] 	= $fetch['user_id'];
        $_SESSION['username'] 	= $fetch['user_name'];

        //$_SESSION['user_role'] 	= $fetch['role'];


        echo 1;//log the user in
    }
}





















