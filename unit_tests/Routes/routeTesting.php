<?php
$rt = new RoutesDB();

echo "Unit Testing for the Account Login Functions<br>";
echo "====================================================<br>";

echo "Initializing database...<br>";

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


echo "Printing current route table from database...for user \"test\"<br><br>";

$sql = "SELECT rout.route_name,
        rout.route_start,
        rout.route_end
        FROM
        crx_user AS `user`
        INNER JOIN crx_user_route AS urout ON urout.uid = `user`.user_id
        INNER JOIN crx_routes AS rout ON urout.rid = rout.id
        WHERE user.user_name ='test' ";

$sqlQuery = $db->ms->query($sql);
$num_rows = mysqli_num_rows($sqlQuery); // Get the number of rows

if($num_rows <= 0)// If no user
{
    echo "<p>There are currently no Routes on record.</p>";
    echo '<p style="color:YELLOW">----CAUTION</p><br><br>';
}
else
{
    echo '
        <table  border="1">
            <tr><th>Current Routes in Database</th></tr>
            <tr><td>Route Name</td><td>Start</td><td>End</td></tr>';

    while($row = $sqlQuery->fetch_row())
    {
        echo "<tr>";
        echo "<td>$row[0]</td><td>$row[1]</td><td>$row[2]</td>";
        echo "</tr>";
    }

    echo '</table><br>';

}


echo "TESTING Database ROUTE ADD with presets ( variation in content)<br><br>";

//Route1
$name = "Route1";
$start = "Here";
$end = "There";

#call Test Function;
testROUTEADD($name, $start, $end);


//2
$name = "2 Route";
$start = "#$%#$%#%&Y&&**^&*^&!!!~!@#!$%#";
$end = "23423423423424234";
#call Test Function;
testROUTEADD($name, $start, $end);


//3
$name = "";
$start = "Here";
$end = "There";
#call Test Function;
testROUTEADD($name, $start, $end);


//4
$name = "Route4";
$start = "";
$end = "There";
#call Test Function;
testROUTEADD($name, $start, $end);


//5
$name = "Route5";
$start = "There";
$end = "";
#call Test Function;
testROUTEADD($name, $start, $end);



echo "Attempting to login in valid users with incorrect passwords....<br><br>";




function testROUTEADD($name, $start, $end)
{
    $rt = new RoutesDB();
    echo "Test Route: {$name}<br> ";
    $rt->addRoute($start, $end, $name , '4') ;


    echo "TESTING RAW ADD: {$rt->feedback} ";

    if( $rt->feedback == "Added User Route | Successfully Added"){
        #test passed
        echo '<p style="color:green">----PASSED</p><br><br>';

    }else{
        #test fail
        echo '<p style="color:red">----FAILED</p><br><br>';
    }

    echo "TESTING ADD via URL POST Result:";

    $url = 'http://web.engr.oregonstate.edu/~jackrobe/CommuteRX/TESTaddRoute';
    $myvars = 'route_name=' . $name . '&start=' . $start . '&end=' . $end . '&TESTMODE=ROBTESTIN' . '&action=add_route';
    $ch = curl_init( $url );
    curl_setopt( $ch, CURLOPT_POST, 1);
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt( $ch, CURLOPT_HEADER, 0);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);


    $response = json_decode(curl_exec( $ch ));
    print_r($response);


    if($response->message == 'Added User Route | Successfully Added'){
        #test passed
        echo '<p style="color:green">----PASSED</p><br><br>';

    }else{
        #test fail
        echo '<p style="color:red">----FAILED</p><br><br>';
    }


    $sql = "SELECT *
        FROM
        crx_user AS `user`
        INNER JOIN crx_user_route AS urout ON urout.uid = `user`.user_id
        INNER JOIN crx_routes AS rout ON urout.rid = rout.id
        WHERE user.user_name ='test' ";

    $sqlQuery = $rt->ms->query($sql);
    $num_rows = mysqli_num_rows($sqlQuery); // Get the number of rows

    testROUTADDRESULT($name, $start, $end, $num_rows);


    echo "====================================================<br><br>";



}

function testROUTADDRESULT($name, $start, $end, $id)
{
    $testResult = false;
    $id = $id -1;
    $rt = new RoutesDB();
    echo "**********************************************<br><br>";
    echo "TESTING THE RESULTS OF THE ADD: {$name}<br> ";
    $result = $rt->get_userRoutes('4');
    $result = $result[$id];
    echo "Page returned: <br>";
    print_r($result);
    echo "<br>";

    echo "Database Result: {$rt->feedback}";

    if( $rt->feedback == "Success"){

        resultText(true, '');
    }else{

        resultText(false, '');
    }


    echo "Results:";
    echo  $result['route_name']. ';' . $result['route_start'] . ';' .$result['route_end'] . '<br/>';
    if($result['route_name'] == $name){
        resultText(true, 'Route Name:');

    }else{

        resultText(false, 'Route Name:');
        echo "Expected: {$name}";
        echo 'Received: ' . $result['route_name']. '<br/>';
    }

    if($result['route_start'] == $start){

        resultText(true, 'Start:');
    }else{

        resultText(false, 'Start:');
        echo "Expected: {$start}";
        echo 'Received: ' . $result['route_start'] . '<br/>';
    }

    if($result['route_end'] == $end){

        resultText(true, 'End:');
    }else{

        echo "Expected: {$end}";
        echo 'Received: ' . $result['route_end']. '<br/>';
        resultText(false, 'End:');
    }


    echo "====================================================<br><br>";
}


echo "Printing current route table from database...for user \"test\"<br><br>";

$sql = "SELECT rout.route_name,
        rout.route_start,
        rout.route_end
        FROM
        crx_user AS `user`
        INNER JOIN crx_user_route AS urout ON urout.uid = `user`.user_id
        INNER JOIN crx_routes AS rout ON urout.rid = rout.id
        WHERE user.user_name ='test' ";

$sqlQuery = $db->ms->query($sql);
$num_rows = mysqli_num_rows($sqlQuery); // Get the number of rows

if($num_rows <= 0)// If no user
{
    echo "<p>There are currently no Routes on record.</p>";
    echo '<p style="color:YELLOW">----CAUTION</p><br><br>';
}
else
{
    echo '
        <table  border="1">
            <tr><th>Current Routes in Database</th></tr>
            <tr><td>Route Name</td><td>Start</td><td>End</td></tr>';

    while($row = $sqlQuery->fetch_row())
    {
        echo "<tr>";
        echo "<td>$row[0]</td><td>$row[1]</td><td>$row[2]</td>";
        echo "</tr>";
    }

    echo '</table><br>';

}



function resultText($r, $msg){

    echo "$msg";
    if($r == true)
    {
        echo '<p style="color:green">----PASSED</p><br><br>';
    }
    else
    {
        echo '<p style="color:red">----FAILED</p><br><br>';
    }

}



?>