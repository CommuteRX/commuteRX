<?php
include 'init.php';

if(!isset($_SESSION['userid']) || $_SESSION['userid'] == ''){
    echo '<script type="text/javascript">window.location = "index.php"; </script>';
}

$current_user = $_SESSION['userid'];
$user_name = $_SESSION['username'];



//save the patient_id
$current_patient;

if(!empty($_GET['patient_id']))
{
    $current_patient = $_GET['patient_id'];
}
else
{
    $current_patient = $_POST['patient_id'];
}

if(isset($_POST['newVisit']))
{
    $current_patient = $_POST['newVisit'];
}

if(isset($_POST['newMedication']))
{
    $current_patient = $_POST['newMedication'];

}


$user_page_location = "The Patient Chart";
$page_title = "MedChart-Patient Chart ";
include 'header.php';

/****************************************
 *
 *    ADD NEW MEDICATION TO PATIENT
 *
 ***************************************/

if(isset($_POST['addMedication']))
{
    $errors= false;
    $current_patient = $_POST['patient_id'];

    if(!$errors)//safe to add the appointment
    {
        /* Prepared statement, stage 1: prepare */
        if (!($stmt = $mysqli->prepare("INSERT INTO mc_medication(name, dose, frequency, patient_id ) VALUES (?, ?, ?, ? )"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        /* Prepared statement, stage 2: bind and execute */
        if (!$stmt->bind_param("sssi", $_POST['medication_name'], $_POST['medication_dose'], $_POST['medication_frequency'], $_POST['patient_id'] )) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

    }

    header("Location: chart.php?patient_id=$current_patient");

}


/*******************************************
 *
 *    REMOVE MEDICATION FROM PATIENT CHART
 *
 ******************************************/

if(isset($_POST['deleteMedication']))
{
    $medication_id = $_POST['medication_id'];
    $update = "DELETE FROM mc_medication  WHERE medication_id='$medication_id'";
    if($mysqli->query($update) === TRUE){

        echo '<div class="modal-alert">
        <div class="alert alert-success">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <strong>Success!</strong> The medications was removed.
        </div>';


    }

    header("Location: chart.php?patient_id=$current_patient");
}


/******************************************************
 *
 *    Remove a diagnosis(problem) from patient's chart
 *
 *****************************************************/

if(isset($_POST['deleteProblem']))
{
    $problemToDelete = $_POST['problem_list_id'];
    $deleteRow = "DELETE FROM mc_problem_list WHERE problem_id ='$problemToDelete'";
    if($mysqli->query($deleteRow) === TRUE){

        echo '<div class="modal-alert">
        <div class="alert alert-success">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <strong>Success!</strong>The problem was removed from the chart.
        </div>';

    }

    header("Location: chart.php?patient_id=$current_patient");
}





?>


        <!-- Modal -->
        <div class="modal fade" id="medicationEntry" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel" style="color: #666;">Enter Medication Details</h4>
                    </div>
                    <div class="modal-body">
                      <div>
                        <fieldset>
                          <form id="add_medication" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                            <div style="white-space:nowrap">
                              <label for="medication_name">Medication Name: </label>
                              <input type="text" name="medication_name" required>
                            </div>
                            <div style="white-space:nowrap">
                              <label for="medication_dose">Dose: </label>
                              <input type="text" name="medication_dose" required>
                            </div>
                            <div style="white-space:nowrap">
                              <label for="medication_frequency">Frequency: </label>
                              <input type="text" name="medication_frequency" required>
                              <input type= "hidden" name="addMedication" value="1">
                              <input type= "hidden" name="patient_id" value="<?php echo $current_patient;?>"><br>
                              <input type="submit" value="Submit">
                            </div>
                          </form>
                        </fieldset>
                      </div>
                    </div>
                </div>
            </div>
        </div>
<?php




/******************************************************
 *
 *    Display the patient's chart
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

echo '  <div class="primary_content">
          <div class="patient_info_title">
            <h2>' . $patient_last_name . ', ' . $patient_first_name . ' ' . $patient_middle_name . '</h2>
            <h4>Patient ID: ' . $patient_id . '</h4>
          </div>
          <div class="patient_picture">
            <img src="' . $patient_picture . '" alt="Patient\'s Picture">
          </div>
          <div class="patient_info">

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
            </p>';

  echo "    <p>
              <form action='diagnose.php' method='post'>
                <input type= 'hidden' name='addObservation' value='1'>
                <input type= 'hidden' name='patient_id' value='$patient_id'>
                <input type='submit' value='Dx me!'>
              </form>
            </p>
          </div>
        </div>";



/******************************************************
 *
 *      DISPLAY APPOINTMENTS
 *
 *****************************************************/
?>
    <div class="secondary_content">
    <h2>Appointment History</h2>
<?php

$colChoice = "SELECT * FROM mc_encounter WHERE patient_id = $current_patient";
$sqlQuery = $mysqli->query($colChoice);

$num_rows = mysqli_num_rows($sqlQuery); // Get the number of rows

if($num_rows <= 0)// If no past appointments
{

    echo '<div class="modal-alert">
    <div class="alert alert-info">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <strong>Note!</strong><p>This patient has no record of past appointments.</p>
    </div>';
}
else
{
    echo '
    <div id="dynTable">
         <table>';

    echo '

            <tr><th>Date</th>
            <th>Chief Complaint</th>
            <th>Vitals</th>
            <th>Notes</th>
            <th>Healthcare Provider ID</th>
            </tr>';
    while($row = $sqlQuery->fetch_assoc())
    {
        $encounter_id = $row['encounter_id'];

        if($row['smoking_status'] = 'y')
        {
            $smoke_status = "Current smoker";
        }
        else
        {
            $smoke_status = "Non-smoker";
        }

        echo "<tr><td>{$row['date']}</td>
        <td>{$row['chief_complaint']}</td>
        <td>
        Height: {$row['height']} Weight: {$row['weight']}<br>
        Pulse: {$row['pulse']} Resp: {$row['respirations']}<br>
        Temp: {$row['temperature']} O2: {$row['o2']}<br>
        BP: {$row['systolic']}/{$row['diastolic']}<br>
        $smoke_status


        </td>
        <td>{$row['notes']}</td><td>{$row['provider_id']}</td>";
    }

    echo " </table>
    </div>";

}




/******************************************************
 *
 *      DISPLAY MEDICATIONS
 *
 *****************************************************/
?>
<!-- Button trigger modal -->
<button type="button" class="button button-primary" data-toggle="modal" data-target="#medicationEntry">
    Add Medication
</button>
<h2>Current Medications</h2>
<?php

$colChoice = "SELECT * FROM mc_medication WHERE patient_id = $current_patient";
$sqlQuery = $mysqli->query($colChoice);

$num_rows = mysqli_num_rows($sqlQuery); // Get the number of rows

if($num_rows <= 0)// If no medications
{

    echo '<div class="modal-alert">
    <div class="alert alert-info">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <strong>Note!</strong><p>This patient has no medications on record.</p>
    </div>';

}
else
{
    echo '
    <div id="dynTable">
         <table>
            <tr>
            <th>Medication Name</th>
            <th>Dose</th>
            <th>Frequency</th>
            <th>Discontinue</th>
            </tr>';
    while($row = $sqlQuery->fetch_assoc())
    {


        echo "<tr>
            <td>{$row['name']}</td>
            <td>{$row['dose']}</td>
            <td>{$row['frequency']}</td>";
        echo "
        <td><form action='chart.php' method='post'>
        <input type= 'hidden' name='deleteMedication' value='1'>
        <input type= 'hidden' name='medication_id' value='{$row['medication_id']}'>
        <input type= 'hidden' name='patient_id' value='{$row['patient_id']}'>
        <input type='submit' value='Delete'>
        </form></td>";

    }

    echo " </table>
    </div>";

}

/******************************************************
 *
 *      DISPLAY PROBLEM LIST (DIAGNOSED CONDITIONS)
 *
 *****************************************************/
echo "<h2>Problem List</h2>";

$colChoice = "SELECT problem, problem_id FROM mc_problem_list WHERE patient_id = $current_patient";
$sqlQuery = $mysqli->query($colChoice);

$num_rows = mysqli_num_rows($sqlQuery); // Get the number of rows

if($num_rows <= 0)// If no problems
{

    echo '<div class="modal-alert">
    <div class="alert alert-info">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <strong>Note!</strong><p>This patient has no problems on record.</p>
        <p>Click "Dx Me!" at the top to begin searching for a diagnosis.</p>
    </div>';
}
else
{
    echo '
    <div id="problem_list_form">
         <table>
            <tr><th>Condition</th>
            <th>Remove</th>
            </tr>';
    while($row = $sqlQuery->fetch_assoc())
    {
        $problem_id = $row['problem_id'];

        echo "<tr><td>{$row['problem']}</td>";
        echo "
        <td><form action='chart.php' method='post'>
        <input type= 'hidden' name='deleteProblem' value='1'>
        <input type= 'hidden' name='problem_list_id' value='$problem_id'>
        <input type= 'hidden' name='patient_id' value='$current_patient'>
        <input type='submit' value='Delete'>
        </form></td>";
    }

    echo " </table>
    </div>";
}

echo '
    </div>
  </body>
</html>';
