<?php
include 'init.php';

if(!isset($_SESSION['userid']) || $_SESSION['userid'] == ''){
    echo '<script type="text/javascript">window.location = "index.php"; </script>';
}

$current_user = $_SESSION['userid'];
$user_name = $_SESSION['username'];

$user_page_location = "My Dashboard";
$page_title = "MedChart-Dashboard";


/******************************************************
 *
 *   CHANGE SCHEDULE FOR SELECTED PROVIDER AND DATE
 *
 *****************************************************/
if(isset($_POST['view_schedule']))
{
    $provider_id = $_POST['selected_provider'];
    $view_date = date("Y-m-d", strtotime($_POST['selected_date']));

    $_SESSION['default_provider'] 	= $provider_id;
    $_SESSION['default_date'] 	= date("Y-m-d", strtotime($_POST['selected_date']));

}


//Default provider and date for schedule
if(isset($_SESSION['default_provider']))
{
    $provider_id = $_SESSION['default_provider'];
    $view_date = $_SESSION['default_date'];
    $datePickerDate = date("m/d/Y", strtotime($view_date));

}
else
{
    $provider_id = "Select Provider";
    $view_date = date("Y-m-d");
    $datePickerDate = date("m/d/Y", strtotime($view_date));

}

include 'header.php';
?>

    <!-- Modal -->
    <div class="modal fade" id="block_reason" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 style="font-family: 'Roboto Condensed', sans-serif; color: #666;" class="modal-title" id="myModalLabel" style="color: #666;">Blocked time slot</h4>
                </div>
                <div class="modal-body">
                    <div>
                        <fieldset>
                            <form id="add_medication" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                                <div style="white-space:nowrap">
                                    <label style="width: 250px;" for="block_reason">Enter comment for blocked time slot: </label>
                                    <input type="text" name="slot_reason" required>
                                </div>

                                    <input type= "hidden" name="add_block_slot" value="1">
                                    <br>
                                    <input type="submit" value="Submit">
                                </div>
                            </form>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal -->
<div class="modal fade" id="appointment_schedule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel" style="font-family: 'Roboto Condensed', sans-serif; color: #666;">Schedule Appointment</h4>
            </div>
            <div class="modal-body">
                <div>
                    <fieldset>
                        <form id="add_medication" action="dashboard.php" method="post">
                            <div style="white-space:nowrap">
                                <label style="width: 250px;" for="block_reason">Enter reason for appointment:  </label>
                                <input type="text" name="appointment_reason" required>
                            </div>

                            <input type= "hidden" name="add_appointment" value="1">
                            <br>
                            <input type="submit" value="Submit">
                </div>
                </form>
                </fieldset>
            </div>
        </div>
    </div>
</div>
</div>


<!-- Modal -->
        <div class="modal fade" id="appointmentEntry" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel" style="color: #666;">Enter Appointment Details</h4>
                    </div>
                    <div class="modal-body">
                        <div>
                            <fieldset>
                                <form id="appointment_details" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                                  <div style="white-space:nowrap">
                                    <label for="chief_complaint">Chief Complaint: </label>
                                    <TEXTAREA required NAME="chief_complaint" ROWS=3 COLS=30 ></TEXTAREA>
                                  </div>
                                  <div style="white-space:nowrap">
                                    <label for="smoking_status">Smoking status: </label>
                                    <select  name="smoking_status">
                                      <option value="">Select</option>
                                      <option value="y">Current Smoker</option>
                                      <option value="n">Not smoking</option>
                                    </select>
                                  </div>
                                  <div style="white-space:nowrap">
                                    <label for="height">Height: </label>
                                    <input type="text" name="height">
                                  </div>
                                  <div style="white-space:nowrap">
                                    <label for="weight">Weight: </label>
                                    <input type="text" name="weight">
                                  </div>
                                  <div style="white-space:nowrap">
                                    <label for="temperature">Temperature: </label>
                                    <input type="text" name="temperature">
                                  </div>
                                  <div style="white-space:nowrap">
                                    <label for="respirations">Respirations: </label>
                                    <input type="text" name="respirations">
                                  </div>
                                  <div style="white-space:nowrap">
                                    <label for="pulse">Pulse: </label>
                                    <input type="text" name="pulse">
                                  </div>
                                  <div style="white-space:nowrap">
                                    <label for="o2">O2: </label>
                                    <input type="text" name="o2">
                                  </div>
                                  <div style="white-space:nowrap">
                                    <label for="systolic">Systolic: </label>
                                    <input type="text" name="systolic">
                                  </div>
                                  <div style="white-space:nowrap">
                                    <label for="diastolic">Diastolic: </label>
                                    <input type="text" name="diastolic">
                                  </div>
                                  <div style="white-space:nowrap">
                                    <label for="notes">Notes: </label>
                                    <TEXTAREA NAME="notes" ROWS=3 COLS=30 ></TEXTAREA>
                                  </div>
                                  <div style="white-space:nowrap">
                                        <input type= "hidden" name="addAppointment" value="1">
                                        <input type= "hidden" name="patient_id" value="<?php echo $_SESSION['patient_id'];?>"><br>
                                        <input type="submit" value="Submit">
                                  </div>
                                </form>
                            </fieldset>
                        </div><br>
                    </div>
                </div>
            </div>
        </div>

<?php

if(!empty($_GET['scheduleAppointment']))
{
    $_SESSION['slot_info'] = 1; //set to true as the time slot info is saved as session variables

    echo '<div class="modal-alert">
    <div class="alert alert-info">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <strong>Note!</strong><p>Please choose a time slot to schedule the patient.</p>
    </div>';
}


/******************************************************
 *
 *    ADD an appointment to patient's chart
 *
 *****************************************************/
if(isset($_POST['addAppointment']))
{
    $errors= false;
    $encounter_patient = $_SESSION['patient_id'];
    $encounter_provider = $_SESSION['slot_provider'];
    $encounter_time = $_SESSION['slot_time'];
    $encounter_date =  $_SESSION['slot_date'];
    $encounter_appointment = $_SESSION['appointment_id'];
    $encounter_status = "open";

    if(!$errors)//safe to add the encounter
    {
        /* Prepared statement, stage 1: prepare */
        if (!($stmt = $mysqli->prepare("INSERT INTO mc_encounter (chief_complaint, smoking_status, height, weight, pulse,
          systolic, diastolic, respirations, temperature, notes, patient_id, provider_id, o2, appointment_id, student_id, status, mc_encounter.date, mc_encounter.time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        /* Prepared statement, stage 2: bind and execute */
        if (!$stmt->bind_param("ssssssssssiisiisss", $_POST['chief_complaint'], $_POST['smoking_status'], $_POST['height'],
            $_POST['weight'], $_POST['pulse'], $_POST['systolic'], $_POST['diastolic'], $_POST['respirations'],
            $_POST['temperature'], $_POST['notes'], $encounter_patient, $encounter_provider, $_POST['o2'], $encounter_appointment, $current_user,$encounter_status, $encounter_date, $encounter_time  )) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

    }


    $slot_status = $_SESSION['slot_status'];

    $updateRow = "UPDATE mc_appointment SET status='$slot_status' WHERE appointment_id=$encounter_appointment";

    $mysqli->query($updateRow);

    header("Location: chart.php?patient_id=$encounter_patient&encounter_status=1");

}

/******************************************************
 *
 *            BLOCK TIME SLOT
 *
 *****************************************************/
if(isset($_POST['update_slot']))
{
    if($_POST['update_slot'] == "block_slot")
    {

       $_SESSION['slot_time'] 	= $_POST['slot_time'];
       $_SESSION['slot_date'] 	= $_POST['slot_date'];
       $_SESSION['slot_provider'] = $_POST['slot_provider'];

        ?>
            <!-- Button trigger modal -->
            <button type="button" class="button button-primary" data-toggle="modal" data-target="#block_reason">
                Add Reason
            </button>
            <script>
            $( ".button" ).hide();
            document.getElementsByClassName("button")[0].click();
            </script>
        <?php

    }
}

if(isset($_POST['add_block_slot']))
{
    $slot_time 	=  $_SESSION['slot_time'];
    $slot_date 	= $_SESSION['slot_date'];
    $slot_provider = $_SESSION['slot_provider'];
    $slot_reason = $_POST['slot_reason'];

    /* Prepared statement, stage 1: prepare */
    if (!($stmt = $mysqli->prepare("INSERT INTO mc_blocked_slots(provider_id, slot_date, slot_time, reason) VALUES (?, ?, ?, ?)"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    /* Prepared statement, stage 2: bind and execute */
    if (!$stmt->bind_param("isss", $slot_provider, $slot_date, $slot_time, $slot_reason)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    else
    {
        echo '<div class="modal-alert">
        <div class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <strong>Success!</strong> The time slot has been blocked out.
        </div>';
    }
}

/******************************************************
 *
 *            SCHEDULE APPOINTMENT FOR TIME SLOT
 *
 *****************************************************/
if((isset($_POST['update_slot'])) && ($_SESSION['slot_info'] == 0))
{
    if($_POST['update_slot'] == "schedule_appointment")
    {
        $_SESSION['slot_time'] 	= $_POST['slot_time'];
        $_SESSION['slot_date'] 	= $_POST['slot_date'];
        $_SESSION['slot_provider'] = $_POST['slot_provider'];

        header("Location: patientList.php?scheduleAppointment=1");
    }
}

if((isset($_POST['update_slot'])) && ($_SESSION['slot_info'] == 1))
{
        $_SESSION['slot_info'] = 0; //reset

        $_SESSION['slot_time'] 	= $_POST['slot_time'];
        $_SESSION['slot_date'] 	= $_POST['slot_date'];
        $_SESSION['slot_provider'] = $_POST['slot_provider'];

        //just need the appointment reason
         ?>
         <!-- Button trigger modal -->
         <button type="button" class="button button-primary appointment-button" data-toggle="modal" data-target="#appointment_schedule">
             schedule appointment
         </button>
         <script>
             $( ".appointment-button" ).hide();
             document.getElementsByClassName("appointment-button")[0].click();
         </script>
         <?php

}

if(isset($_POST['add_appointment']))
 {
     $patientToSchedule = $_SESSION['slot_patient'];
     $slot_time 	=  $_SESSION['slot_time'];
     $slot_date 	= $_SESSION['slot_date'];
     $slot_provider = $_SESSION['slot_provider'];
     $slot_reason = $_POST['appointment_reason'];

     /* Prepared statement, stage 1: prepare */
        if (!($stmt = $mysqli->prepare("INSERT INTO mc_appointment( mc_appointment.patient_id, mc_appointment.date, mc_appointment.time, mc_appointment.provider_id, mc_appointment.scheduler, mc_appointment.reason) VALUES (?, ?, ?, ?, ?, ? )"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        /* Prepared statement, stage 2: bind and execute */
        if (!$stmt->bind_param("issiis",  $patientToSchedule, $slot_date, $slot_time,
             $slot_provider, $current_user, $slot_reason)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        else
        {
            echo '<div class="modal-alert">
            <div class="alert alert-success">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <strong>Success!</strong> The appointment was added to the schedule.
             </div>';

        }

 }

/******************************************************
 *
 *      REMOVE BLOCK FROM SCHEDULE
 *
 *****************************************************/
if(isset($_POST['remove_block']))
{
    $rowToRemove = $_POST['remove_block'];
    $deleteRow = "DELETE FROM mc_blocked_slots WHERE slot_id ='$rowToRemove'";

    if($mysqli->query($deleteRow) === TRUE)
    {
        echo '<div class="modal-alert">
        <div class="alert alert-success">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <strong>Success!</strong> The block was removed from the schedule.
        </div>';
    }
}


/******************************************************
 *
 *            UPDATE APPOINTMENT STATUS
 *
 *****************************************************/
if(isset($_POST['update_status']))
{
    $appointmentToUpdate = $_POST['appointment_id'];
    $update = 0;

    if($_POST['update_status'] == "cancel")
    {
        $new_status ="Canceled";
        $update = 1;

    }
    else if($_POST['update_status'] == "no_show")
    {
        $new_status ="No Show";
        $update = 1;

    }
    else if($_POST['update_status'] == "late")
    {
        $new_status ="Late";
        $update = 1;

    }
    else if($_POST['update_status'] == "scheduled")
    {
        $new_status ="Scheduled";
        $update = 1;

    }
    else if($_POST['update_status'] == "check_in")
    {
        //save appointment_id, patient_id, date, time, provider_id
        $_SESSION['appointment_id'] = $_POST['appointment_id'];
        $_SESSION['patient_id'] = $_POST['patient_id'];
        $_SESSION['slot_time'] 	= $_POST['slot_time'];
        $_SESSION['slot_date'] 	= $_POST['slot_date'];
        $_SESSION['slot_provider'] = $_POST['slot_provider'];
        $_SESSION['slot_status'] ="Checked In";


        ?>
        <!-- Button trigger modal -->
        <div class="secondary_content">
          <button type="button" class="button button-primary checkin-button" data-toggle="modal" data-target="#appointmentEntry">
              Chart New Visit
          </button>
         <script>
         $( ".checkin-button" ).hide();
         document.getElementsByClassName("checkin-button")[0].click();
        </script>
     <?php

    }

    if($update == 1)
    {
        $updateRow = "UPDATE mc_appointment SET status='$new_status' WHERE appointment_id=$appointmentToUpdate";


        if($mysqli->query($updateRow) === TRUE)
        {
            echo '<div class="modal-alert">
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <strong>Success!</strong> The appointment status was updated.
            </div>';
        }
    }

}


/******************************************************
 *
 *   FORM FOR CHANGING SCHEDULE OPTIONS
 *
 *****************************************************/
$colChoice = "SELECT mc_provider.provider_id, mc_provider.last_name, mc_provider.first_name, mc_provider.title FROM mc_provider ORDER BY mc_provider.last_name";
$sqlQuery = $mysqli->query($colChoice);

echo "<div id='scheduling_dropdowns'>
        <div>
          <form action='dashboard.php' method='post'>
              <div class='ui-widget'>
                  <label>Provider: </label>
                  <select id='combobox' name='selected_provider'>";

  while($row = $sqlQuery->fetch_row())
  {
        if($row[0] == $provider_id)
        {
            echo "<option selected='selected' value='$row[0]'>$row[1], $row[2], $row[3]</option>";
        }
        else
        {
            echo "<option  value='$row[0]'>$row[1], $row[2], $row[3]</option>";
        }

  }

  echo '          </select>
              </div>
          <p>Date: <input type="text" id="datepicker" name="selected_date" ></p>
              <input type="submit"  name="view_schedule" value="View Schedule">
          </form>
        </div>
      </div>
      ';




/******************************************************
 *
 *      DISPLAY SCHEDULE
 *
 *****************************************************/

$colChoice = "SELECT mc_provider.provider_id, mc_provider.last_name, mc_provider.first_name, mc_provider.title FROM mc_provider WHERE mc_provider.provider_id = $provider_id";
$sqlQuery = $mysqli->query($colChoice);
$row = $sqlQuery->fetch_row();

$display_date = new DateTime($view_date);
$show_date = $display_date->format('l jS \of F Y');


echo "  <div id='registration_page'>
                <h2>Schedule for $row[1], $row[2], $row[3] on $show_date</h2>
                <table>
                    <tr>
                    <th>Time</th><th>Patient ID</th><th>Patient</th><th>Reason for Visit</th><th>Status</th><th>Update Status</th>
                    </tr>
            ";


$begin = new DateTime("08:30");
$end   = new DateTime("16:15");
$interval = DateInterval::createFromDateString('15 min');
$times    = new DatePeriod($begin, $interval, $end);


foreach ($times as $time)
{
    $match = 0;

    echo "<tr>";

    $colChoice = "SELECT mc_appointment.appointment_id , mc_appointment.time , mc_appointment.reason , mc_appointment.status , mc_patient.patient_id , mc_patient.date_of_birth , mc_patient.sex , mc_patient.first_name, mc_patient.middle_name, mc_patient.last_name
    FROM (mc_appointment INNER JOIN mc_patient ON (mc_appointment.patient_id  = mc_patient.patient_id ))
    WHERE (mc_appointment.provider_id = $provider_id AND mc_appointment.date = '{$view_date}')";
    $sqlQuery = $mysqli->query($colChoice);

    while($row = $sqlQuery->fetch_row())
    {
        if($row[1] == $time->format('H:i:s'))
        {
            $match = 1; //set to true

            $patient_dob  = date("n/j/Y", strtotime($row[5]));
            $patient_dob_calc = date($row[5]);
            //calculate age
            //credit http://www.devmanuals.com/tutorials/php/date/php-date-difference-in-years.html
            $current_date = date("Y-m-d");
            $date_diff=strtotime($current_date)-strtotime($patient_dob_calc);
            $num_years = floor(($date_diff)/(60*60*24*365));

            if ($row[6] == 'm')
            {
                $patient_sex = "Male";
            }
            else
            {
                $patient_sex = "Female";
            }


            $s_time = $time->format('H:i:s');
            $s_date = $view_date;
            $s_provider = $provider_id;

            echo "<td>";
            echo $time->format('g:i a');


            echo "</td>
                  <td>ID: $row[4] </td>
                  <td><a href='chart.php?patient_id=$row[4]'> $row[9], $row[7] $row[8] ($num_years yrs) $patient_sex</a></td>
                  <td>$row[2]</td>";


            if($row[3] == "Checked In")
            {
                echo "<td style='color: #3184d5;' >$row[3]</td>";
            }
            else if($row[3] == "Scheduled")
            {

                echo "<td style='color: #49BF71;' >$row[3]</td>";

            }
            else if($row[3] == "No Show")
            {
                echo "<td style='color: red;' >$row[3]</td>";
            }
            else if($row[3] == "Late")
            {
                echo "<td style='color: #fd7427;' >$row[3]</td>";
            }
            else if($row[3] == "Canceled")
            {
                echo "<td style='color: red;' >$row[3]</td>";
            }

            if($row[3] == "Checked In")
            {
                echo "<td></td>";

            }
            else if($row[3] == "Canceled")
            {
                $match = 0; //set to false again so that new open appointment slot is printed
                echo "<td></td><tr>";

            }
            else
            {
                echo "     <td>
                <form action='dashboard.php' method='post'>
                    <input type= 'hidden' name='slot_time' value='$s_time'>
                    <input type= 'hidden' name='slot_date' value='$s_date'>
                    <input type= 'hidden' name='slot_provider' value='$s_provider'>
                    <input type= 'hidden' name='appointment_id' value='$row[0]'>
                    <input type= 'hidden' name='patient_id' value='$row[4]'>
                    <select  name='update_status' onchange='this.form.submit()' >
                        <option value=''>Update Status</option>
                        <option value='check_in'>Check in</option>
                        <option value='cancel'>Cancel</option>
                        <option value='no_show'>No Show</option>
                        <option value='late'>Late</option>
                        <option value='scheduled'>Scheduled</option>
                   </select>
                </form>
              </td>";
           }
        }
    }

    if($match == 0)//if no match was found
    {
        $block_match = 0; //set to false
        //check for match in blocked time slots
        $colChoice = "SELECT mc_blocked_slots.reason, mc_blocked_slots.slot_time, mc_blocked_slots.slot_id  FROM mc_blocked_slots
        WHERE (mc_blocked_slots.provider_id = $provider_id AND mc_blocked_slots.slot_date = '{$view_date}')";

        $sqlQuery = $mysqli->query($colChoice);

        while($row = $sqlQuery->fetch_row())
        {
            if($row[1] == $time->format('H:i:s'))
            {
                $block_match = 1; //set to true
                echo "<td style='background-color: #da635d;'>";
                echo $time->format('g:i a');
                echo "</td>
                          <td style='background-color: #da635d;text-align: center;'  colspan='4'>Blocked: $row[0]</td>
                          <td style='background-color: #da635d;'>
                            <form action='dashboard.php' method='post'>
                                <input type= 'hidden' name='remove_block' value='$row[2]'>
                                <input type='submit' value='Remove Block'>
                            </form>
                          </td>";
            }
        }
    }

    if(($block_match == 0) && ($match == 0))
    {

        $s_time = $time->format('H:i:s');
        $s_date = $view_date;
        $s_provider = $provider_id;

        echo "<td>";
        echo $time->format('g:i a');
        echo "</td>
                  <td colspan='4'></td>
                  <td>
                    <form action='dashboard.php' method='post'>
                        <input type= 'hidden' name='slot_time' value='$s_time'>
                        <input type= 'hidden' name='slot_date' value='$s_date'>
                        <input type= 'hidden' name='slot_provider' value='$s_provider'>
                        <select  name='update_slot' onchange='this.form.submit()' >
                            <option value=''>Update Status</option>
                            <option value='schedule_appointment'>Schedule Appointment</option>
                            <option value='block_slot'>Block Time Slot</option>
                       </select>
                    </form>
                  </td>";
    }

    $time->add($interval)->format('H:i:s');
}

echo "</tr></table></div>";

include 'footer.php';
