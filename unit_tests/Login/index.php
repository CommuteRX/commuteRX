<?php

include 'database.php';


//Login users unit testing


echo "Unit Testing for the Account Login Functions<br>";
echo "====================================================<br>";

echo "Initializing database...<br>";

//initialize the db
$db = new Database();

//check connection to database
if ($db->success = true)
{
    //echo "Result: {$db->feedback}";
    echo "Result: Connection to database was a success!<br>";
    echo '<p style="color:green">----PASSED</p><br><br>';
}
else
{
    echo "Result: {$db->feedback}<br>";
    echo "Result: Connection to database failed!<br>";
    echo '<p style="color:red">----FAILED</p><br><br>';
}


echo "Printing current user table from database...<br><br>";



$colChoice = "SELECT * FROM crx_user ";
$sqlQuery = $db->ms->query($colChoice);
$num_rows = mysqli_num_rows($sqlQuery); // Get the number of rows

if($num_rows <= 0)// If no user
{
    echo "<p>There are currently no users on record.</p>";
    echo '<p style="color:red">----FAILED</p><br><br>';
}
else
{
    echo '
        <table  border="1">
            <tr><th>Current Users in Database</th></tr>
            <tr><td>User id</td><td>UserName</td><td>First Name</td><td>Last Name</td><td>Password</td></tr>';

    while($row = $sqlQuery->fetch_row())
    {
        echo "<tr>";
        echo "<td>$row[0]</td><td>$row[4]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td>";
        echo "</tr>";
    }

    echo '</table><br>';

}


echo "Beginning login process for 3 valid test users...<br><br>";

$mode = 1;

//user 1
$userName = "CaptainKirk";
$userPassword = "Shatner";

testLogin($userName, $userPassword, $db, $mode);



//user 2
$userName = "CaptainJaneway";
$userPassword = "Mulgrew";

testLogin($userName, $userPassword, $db, $mode);


//user3
$userName = "Mr.Spock";
$userPassword = "Nimoy";

testLogin($userName, $userPassword, $db, $mode);


echo "Attempting to login in valid users with incorrect passwords....<br><br>";

$mode = 2;

//user 1
$userName = "CaptainKirk";
$userPassword = "FAKEShatner";

testLogin($userName, $userPassword, $db, $mode);



//user 2
$userName = "CaptainJaneway";
$userPassword = "FAKEMulgrew";

testLogin($userName, $userPassword, $db, $mode);


//user3
$userName = "Mr.Spock";
$userPassword = "FAKENimoy";

testLogin($userName, $userPassword, $db, $mode);


echo "Attempting to login in users with non-registered usernames...<br><br>";



//user 1
$userName = "LukeSkywalker";
$userPassword = "Jedi";

testLogin($userName, $userPassword, $db, $mode);



//user 2
$userName = "HanSolo";
$userPassword = "Millennium Falcon";

testLogin($userName, $userPassword, $db, $mode);



function testLogin($userName, $userPassword, $db, $mode)
{
    echo "Test user: username: {$userName}, Password: {$userPassword}<br> ";


    $db->login($userName, $userPassword);

    echo "Result: {$db->feedback}";


    if($mode == 1)
    {

        if($db->feedback == "Password is incorrect - try again")
        {
            echo '<p style="color:red">----FAILED</p><br><br>';
        }
        else
        {
            echo '<p style="color:green">----PASSED</p><br><br>';
        }
    }
    else
    {
        if($db->feedback == "Password is incorrect - try again")
        {
            echo '<p style="color:green">----PASSED</p><br><br>';
        }
        else
        {
            echo '<p style="color:red">----FAILED</p><br><br>';
        }
    }

    echo "====================================================<br><br>";


}

