<?php
include 'init.php';




//generate a picture for the patient
$remote_url = 'http://uifaces.com/api/v1/random';







//$num_rows = mysqli_num_rows($sqlQuery); // Get the number of rows


$colChoice = "SELECT * FROM patient";
$sqlQuery = $mysqli->query($colChoice);

while($row = $sqlQuery->fetch_row())
{
    $json = file_get_contents($remote_url);
    $pic = json_decode($json, true);
    $patient_pic = $pic['image_urls']['epic'];

    $update = "UPDATE patient SET picture='$patient_pic' WHERE patient_id='$row[0]'";
    if($mysqli->query($update) === TRUE){
        echo "The picture was updated. <br>";
    }


}