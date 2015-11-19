<?php
include 'init.php';

if(!isset($_SESSION['userid']) || $_SESSION['userid'] == ''){
    echo '<script type="text/javascript">window.location = "index.php"; </script>';
}

$current_user = $_SESSION['userid'];
$user_name = $_SESSION['username'];



//set the current patient
$current_patient;

if(!empty($_GET['patient_id']))
{
    $current_patient = $_GET['patient_id'];
}
else
{
    $current_patient = $_POST['patient_id'];
}

$user_page_location = "Diagnose Patient";
$page_title = "MedChart-Diagnosis";
include 'header.php';

?>

    <!-- Modal -->
<div class="modal fade" id="observationEntry" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Observation</h4>
    </div>
    <div class="modal-body">
<?php


/******************************************************
 *
 *      INFERMEDICA API CALL FOR OBSERVATIION LIST
 *      (Trying out curl to see if any faster)
 *
 *****************************************************/
////display medical observations
//$remote_url = 'https://api.infermedica.com/v1/observations';
//
////Create a stream
//$opts = array(
//    'http'=>array(
//        'method'=>"GET",
//        'header' => "app_id: $app_id\r\n" .
//            "app_key: $app_key\r\n"
//    )
//);
//
//$context = stream_context_create($opts);
//// Open the file using the HTTP headers set above
//$file = file_get_contents($remote_url, false, $context);
//
//$decoded_json = json_decode($file, true);


// Initialize curl
$curl = curl_init();


// Set the options
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_URL, 'https://api.infermedica.com/v1/observations');
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    "app_id: $app_id",
    "app_key: $app_key"
));

// Execute the request
$file = curl_exec($curl);

// Free up the resources
curl_close($curl);

$decoded_json = json_decode($file, true);


echo '

    <div>
         <table id="table6">';
echo "<h2>Medical Observations</h2>";

echo '
            <tr><th>Observation Name</th>
            <th>Add</th>
            </tr>';

foreach($decoded_json as $item)
{


    echo "<tr><td>{$item['name']}</td>";
    echo "
                    <td><form action='diagnose.php' method='post'>
                    <input type= 'hidden' name='addObservation' value='1'>
                    <input type= 'hidden' name='observation' value='{$item['name']}'>
                    <input type= 'hidden' name='observation_id' value='{$item['id']}'>
                    <input type= 'hidden' name='patient_id' value='{$current_patient}'>
                    <input type='submit' value='Add Observation'>
                    </form></td>";
}

echo '</table>
<script language="javascript" type="text/javascript">
    var table6Filters = {


            col_1: "none",
            stylesheet: "css/stylesheet.css",
            on_keyup: true,
            on_keyup_delay: 500,
            input_watermark: "Search..."
    };
    var tf03 = setFilterGrid("table6",table6Filters);
</script>

</div>
            </div>
        </div>
    </div>
</div>';



/**********************************************************
 *
 *      REMOVE DIAGNOSIS(PROBLEM) FROM PATIENT'S CHART
 *
 **********************************************************/

if(isset($_POST['deleteProblem']))
{

    $problemToDelete = $_POST['problem_list_id'];
    $deleteRow = "DELETE FROM mc_problem_list WHERE problem_id ='$problemToDelete'";
    if($mysqli->query($deleteRow) === TRUE){
        //echo "The problem was removed from the chart. <br>";
    }

    header("Location: diagnose.php?patient_id=$current_patient");
}



/******************************************************
 *
 *      REMOVE OBSERVATION FROM PATIENT'S CHART
 *
 *****************************************************/

if(isset($_POST['deleteObservation']))
{
    $current_patient = $_POST['patient_id'];

    $observationToDelete = $_POST['observation_list_id'];
    $deleteRow = "DELETE FROM mc_observation WHERE observation_id ='$observationToDelete'";
    if($mysqli->query($deleteRow) === TRUE){
        //echo "The observation was removed from the chart. <br>";
    }

    header("Location: diagnose.php?patient_id=$current_patient");
}



/******************************************************
 *
 *      ADD OBSERVATION TO PATIENT'S CHART
 *
 *****************************************************/
if(isset($_POST['addObservation']))
{


    /* Prepared statement, stage 1: prepare */
    if (!($stmt = $mysqli->prepare("INSERT INTO mc_observation (observation, api_id, patient_id) VALUES (?, ?, ?)"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    /* Prepared statement, stage 2: bind and execute */
    if (!$stmt->bind_param("ssi",  $_POST['observation'], $_POST['observation_id'], $current_patient )) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }


    header("Location: diagnose.php?patient_id=$current_patient");

}

/******************************************************
 *
 *      ADD DIAGNOSIS(PROBLEM) TO PATIENT'S CHART
 *
 *****************************************************/
if(isset($_POST['addProblem']))
{


    /* Prepared statement, stage 1: prepare */
    if (!($stmt = $mysqli->prepare("INSERT INTO mc_problem_list(problem, patient_id) VALUES (?, ?)"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    /* Prepared statement, stage 2: bind and execute */
    if (!$stmt->bind_param("si",  $_POST['problem'],  $current_patient )) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }


    header("Location: diagnose.php?patient_id=$current_patient");

}




/******************************************************
 *
 *      DISPLAY PATIENT'S CHART
 *
 *****************************************************/

$result=$mysqli->query("SELECT * FROM mc_patient WHERE patient_id = '$current_patient'");

while($row=$result->fetch_array())
{
    $patient_id = $row['patient_id'];
    $patient_dob  = date("n/j/Y", strtotime($row['date_of_birth']));
    $patient_dob_calc = date($row['date_of_birth']);
    $patient_picture = $row['picture'];
    $patient_first_name = $row['first_name'];
    $patient_middle_name = $row['middle_name'];
    $patient_last_name = $row['last_name'];
    $patient_email = $row['email'];
    $patient_address_l1 = $row['address_l1'];
    $patient_address_l2 = $row['address_l2'];
    $patient_city = $row['city'];
    $patient_state = $row['state'];
    $patient_zip = $row['zip'];
    $patient_country = $row['country'];
    $patient_home_phone = $row['home_phone'];
    $patient_work_phone = $row['work_phone'];
    $patient_mobile_phone = $row['mobile_phone'];
    $patient_emergency_contact = $row['emergency_contact'];
    $patient_sex = $row['sex'];
}

if ($patient_sex == 'm')
{
    $patient_sex = "Male";
}
else
{
    $patient_sex = "Female";
}

//calculate age
//credit http://www.devmanuals.com/tutorials/php/date/php-date-difference-in-years.html
$current_date = date("Y-m-d");
$date_diff=strtotime($current_date)-strtotime($patient_dob_calc);
$num_years = floor(($date_diff)/(60*60*24*365));


echo '
    <div class="primary_content" id="diagnosis_patient_info">
        <div class="patient_info_title">
          <h2>Patient ID: ' . $patient_id . '</h2>
        </div>
        <div class="patient_picture"><img src="' . $patient_picture . '" alt="Patient\'s Picture"></div>
        <div class="patient_info">
          <h2>' . $patient_last_name . ', ' . $patient_first_name . ' ' . $patient_middle_name . '</h2>
          <br><p>' . $patient_sex . '
          <br>Date of Birth: ' . $patient_dob . ' (' . $num_years . ' yrs )
          <br><br>' . $patient_address_l1 . ' ' . $patient_address_l2 . '
          <br>' . $patient_city . ' ' . $patient_state . ' ' . $patient_zip . '
          </p>
        </div>
        <div class="patient_contact_info">
          <p><br>Home: ' . $patient_home_phone . '
          <br>Work: ' . $patient_work_phone . '
          <br>Mobile: ' . $patient_mobile_phone . '
          <br>Email: ' . $patient_email . '
          <br>Emergency contact: ' . $patient_emergency_contact . '
          </p>
        </div>
    </div>';




/******************************************************
 *
 *      DISPLAY PATIENT'S PROBLEM LIST
 *
 *****************************************************/
?>
<div class="secondary_content">

<h2>Current Problem List</h2>
<?php

$colChoice = "SELECT problem, problem_id FROM mc_problem_list WHERE patient_id = $current_patient";
$sqlQuery = $mysqli->query($colChoice);

$num_rows = mysqli_num_rows($sqlQuery); // Get the number of rows

if($num_rows <= 0)// If no problems
{

    echo '<div class="modal-alert">
    <div class="alert alert-info">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <strong>Note!</strong><br> This patient has no problems on record.
    </div>';


}
else
{
    echo '
    <div>
         <table>
            <tr><th>Condition</th>
            <th>Remove</th>
            </tr>';
    while($row = $sqlQuery->fetch_assoc())
    {
        $problem_list_id = $row['problem_id'];

        echo "<tr><td>{$row['problem']}</td>";
        echo "
        <td><form action='diagnose.php' method='post'>
        <input type= 'hidden' name='deleteProblem' value='1'>
        <input type= 'hidden' name='problem_list_id' value='$problem_list_id'>
        <input type= 'hidden' name='patient_id' value='$current_patient'>
        <input type='submit' value='Delete'>
        </form></td>";

    }

    echo " </table>
    </div>";
}


/******************************************************
 *
 *     DISPLAY CURRENT OBSERVATION LIST
 *
 *****************************************************/

$colChoice = "SELECT * FROM mc_observation WHERE patient_id = $current_patient";
$sqlQuery = $mysqli->query($colChoice);

?>
<!-- Button trigger modal -->
<button type="button" class="button" data-toggle="modal" data-target="#observationEntry">
    Add Observation
</button>

<!-- Large modal -->
<button type="button" class="button" data-toggle="modal" data-target=".requestDx">Request Diagnosis</button>


<h2>Selected Medical Observations</h2>
<?php

$num_rows = mysqli_num_rows($sqlQuery); // Get the number of rows

if($num_rows <= 0)// If no problems
{

    echo '<div class="modal-alert">
    <div class="alert alert-info">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <strong>Note!</strong><br>Add some observations then click "Request Diagnosis".
    </div>';


}
else
{
    echo '
    <div >
         <table>
            <tr>
            <th>Observation</th>
            <th>Remove</th>
            </tr>';
    while($row = $sqlQuery->fetch_assoc())
    {
        $observation_list_id = $row['observation_id'];

        echo "<tr><td>{$row['observation']}</td>";
        echo "
        <td><form action='diagnose.php' method='post'>
        <input type= 'hidden' name='deleteObservation' value='1'>
        <input type= 'hidden' name='observation_list_id' value='$observation_list_id'>
        <input type= 'hidden' name='patient_id' value='{$row['patient_id']}'>
        <input type='submit' value='Remove'>
        </form></td>";
    }

    echo " </table>
    </div>";
}



/******************************************************
 *
 *      PERFORM A DX REQUEST
 *
 *      INFERMEDICA API POST CALL
 *
 *****************************************************/
?>
    <!-- Modal -->
    <div class="modal fade requestDx" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <?php


                $current_patient = $patient_id;
                $current_patient_sex = strtolower($patient_sex);
                $current_patient_age = $num_years;
                $choice_id = "present";


                $sqlQuery=$mysqli->query("SELECT * FROM mc_observation WHERE patient_id = '$current_patient'");


                $evidence_array = array();

                while($row = $sqlQuery->fetch_array())
                {

                    $evidence_array[] = ["id" => $row['api_id'],"choice_id" => $choice_id];
                }


                $request_array = [
                    "sex" => $current_patient_sex,
                    "age" => $current_patient_age,
                    "evidence" => $evidence_array,
                ];

                $content = json_encode($request_array);

                $remote_url = 'https://api.infermedica.com/v1/diagnosis';

                //Create a stream
                $opts = array(
                    'http'=>array(
                        'method'=>"POST",
                        'header' => "app_id: $app_id\r\n" .
                            "app_key: $app_key\r\n" . "Content-type: application/json\r\n"
                            . "Content-Length: " . strlen($content) . "\r\n",
                        'content' => $content
                    )
                );

                $context = stream_context_create($opts);
                // Open the file using the HTTP headers set above
                $file = file_get_contents($remote_url, false, $context);

                $decoded_json = json_decode($file, true);
                //echo '<pre>'.json_encode(json_decode($file), JSON_PRETTY_PRINT).'</pre>';

                echo "<h2>Possible Medical Conditions</h2>";

                echo '
    <div>
         <table id="table3">
            <tr>
                <th>Condition Name</th>
                <th>Probability</th>
                <th>Add</th>
            </tr>';

                foreach($decoded_json['conditions'] as $item)
                {
                    echo "<tr><td>{$item['name']}</td>
            <td>{$item['probability']}</td>";
                    echo "
        <td><form action='diagnose.php' method='post'>
        <input type= 'hidden' name='addProblem' value='1'>
        <input type= 'hidden' name='problem' value='{$item['name']}'>
        <input type= 'hidden' name='patient_id' value='$current_patient'>
        <input type='submit' value='Add Diagnosis'>
        </form></td>";
                }


                echo '</table></div>
<script language="javascript" type="text/javascript">
    var table3Filters = {


            col_1: "none",
            col_2: "none",
            stylesheet: "css/stylesheet.css",
            on_keyup: true,
            on_keyup_delay: 500,
            input_watermark: "Search..."
    };
    var tf03 = setFilterGrid("table3",table3Filters);
</script>';

                ?>
            </div>
        </div>
    </div>
  </div>

<?php


echo '
        </body>
    </html>';
