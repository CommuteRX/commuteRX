<?php

include 'database.php';


//Register new users unit testing


echo "Unit Testing for the Account Registration Functions<br>";
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

echo "Beginning registration for 3 valid test users...<br>";

$mode = 1;

//user 1
$userName = "CaptainKirk";
$userFirstName = "James";
$userLastName = "Kirk";
$userPassword = "Shatner";

testRegister($userName, $userPassword, $userFirstName, $userLastName, $db, $mode);




//user 2
$userName = "CaptainJaneway";
$userFirstName = "Kathryn";
$userLastName = "Janeway";
$userPassword = "Mulgrew";

testRegister($userName, $userPassword, $userFirstName, $userLastName, $db, $mode);


//user3
$userName = "Mr.Spock";
$userFirstName = "Commander";
$userLastName = "Spock";
$userPassword = "Nimoy";

testRegister($userName, $userPassword, $userFirstName, $userLastName, $db, $mode);


echo "Attempting to register user with a username that is taken....<br>";

$mode = 2;

//user 4
$userName = "CaptainKirk";
$userFirstName = "FakeJames";
$userLastName = "FakeKirk";
$userPassword = "FakeShatner";


testRegister($userName, $userPassword, $userFirstName, $userLastName, $db, $mode);


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

    echo '</table>';


}


function testRegister($userName, $userPassword, $userFirstName, $userLastName, $db, $mode)
{
    echo "Test user: username: {$userName}, Password: {$userPassword}, First name: {$userFirstName}, Last name: {$userLastName}<br> ";


    $db->registration($userName, $userPassword, $userFirstName, $userLastName);

    echo "Result: {$db->feedback}";


    if($mode == 1)
    {

        if($db->feedback == "Successfully Registered")
        {
            echo '<p style="color:green">----PASSED</p><br><br>';
        }
        else
        {
            echo '<p style="color:red">----FAILED</p><br><br>';
        }
    }
    else
    {
        if($db->feedback == "Successfully Registered")
        {
            echo '<p style="color:red">----FAILED</p><br><br>';
        }
        else
        {
            echo '<p style="color:green">----PASSED</p><br><br>';
        }
    }

    echo "====================================================<br><br>";


}
























