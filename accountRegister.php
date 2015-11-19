<?php
include 'init.php';

// Check the action `register`
if(isset($_POST['action']) && $_POST['action'] == 'register')
{
    $username = mysqli_real_escape_string($mysqli,$_POST['register_username']);
    $hash = md5(mysqli_real_escape_string($mysqli,$_POST['register_password']));
    $first_name = mysqli_real_escape_string($mysqli,$_POST['register_first_name']);
    $last_name = mysqli_real_escape_string($mysqli,$_POST['register_last_name']);
    $professional_title = mysqli_real_escape_string($mysqli,$_POST['register_title']);


    //check if username already exists
    $colChoice = "SELECT user_name FROM mc_user WHERE user_name = '".$_POST['register_username']."'";
    $sqlQuery = $mysqli->query($colChoice);

    $result=mysqli_query($mysqli, "SELECT user_name FROM mc_user WHERE user_name ='$username'");
    $count=mysqli_num_rows($result);
    $row=mysqli_fetch_array($result,MYSQLI_ASSOC);

    // If result matched $username, table row  must be 1 row
    if($count > 0) //there is already a user by that name
    {
        echo 0;
    }
    else //add user to database
    {

        /* Prepared statement, stage 1: prepare */
        if (!($stmt = $mysqli->prepare("INSERT INTO mc_user(user_name, hash, first_name, last_name, title) VALUES (?, ?, ?, ?, ?)"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        /* Prepared statement, stage 2: bind and execute */
        if (!$stmt->bind_param("sssss", $username, $hash, $first_name, $last_name, $professional_title)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        echo 1;

    }

}














