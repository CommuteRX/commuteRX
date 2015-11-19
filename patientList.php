<?php
include 'init.php';

if(!isset($_SESSION['userid']) || $_SESSION['userid'] == ''){
    echo '<script type="text/javascript">window.location = "index.php"; </script>';
}


$current_user = $_SESSION['userid'];
$user_name = $_SESSION['username'];

$user_page_location = "Patient Database";
$page_title = "MedChart-Patient List";
include 'header.php';
?>
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


<?php
if(!empty($_GET['scheduleAppointment']))
{
    $_SESSION['slot_info'] = 1; //set to true as the time slot info is saved as session variables
}

?>
      <div class="patient_list_table">
 <?php


 /******************************************************
  *
  *               SCHEDULE PATIENT
  *
  *****************************************************/
 if(isset($_POST['schedule_appointment']))
 {
     if($_SESSION['slot_info'] == 1) //we have all the other info we need
     {
         $_SESSION['slot_info'] = 0; //reset

         //save patientToSchedule to session
         $_SESSION['slot_patient'] = $_POST['patient'];

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
     else
     {
         //save Patient to schedule to session
         $_SESSION['slot_patient'] = $_POST['patient'];

         header("Location: dashboard.php?scheduleAppointment=1");
     }

 }





 /******************************************************
  *
  *    DISPLAY PATIENT DATABASE
  *
  *****************************************************/
// $colChoice = "SELECT * FROM patient
// LEFT JOIN subscription ON ( patient.patient_id = subscription.patient_id )";

$colChoice = "SELECT * FROM mc_patient ";


 $sqlQuery = $mysqli->query($colChoice);

 $num_rows = mysqli_num_rows($sqlQuery); // Get the number of rows

 if($num_rows <= 0)// If no medications
 {
     echo "<p>There are currently no patients on record.</p>";
 }
 else
 {
    echo '
    <div >
        <table id="table4">
            <tr>
                <th colspan="4">The Patient Database</th>
            </tr>';

            while($row = $sqlQuery->fetch_row())
            {
                $patient_dob  = date("n/j/Y", strtotime($row[1]));
                $patient_dob_calc = date($row[1]);
                //calculate age
                //credit http://www.devmanuals.com/tutorials/php/date/php-date-difference-in-years.html
                $current_date = date("Y-m-d");
                $date_diff=strtotime($current_date)-strtotime($patient_dob_calc);
                $num_years = floor(($date_diff)/(60*60*24*365));
                if ($row[2] == 'm')
                {
                    $patient_sex = "Male";
                }
                else
                {
                    $patient_sex = "Female";
                }

                echo "<tr>";
                echo '<td><img src="' . $row[4] . '" alt="Patient Picture"></td>';
                echo "<td>
        <h4>Patient ID: $row[0]</h4>
        <br><h4>$row[6], $row[3] $row[5]</h4>
        <br>DOB: $patient_dob ($num_years yrs )
        <br>$patient_sex
        <br>
        </td>

            <td><form action='chart.php' method='post'>
            <input type= 'hidden' name='openChart' value='1'>
            <input type= 'hidden' name='patient_id' value='$row[0]'>
            <input type='submit' value='Open Chart'>
            </form></td>

            <td><form action='patientList.php' method='post'>
            <input type= 'hidden' name='schedule_appointment' value='1'>
            <input type='hidden' name='patient' value='$row[0]'>
            <input type='submit' value='Schedule'></form>
            </td>




            ";

            }

            echo '    </table>
        </div><script language="javascript" type="text/javascript">
    var table4Filters = {

            col_0: "none",
            col_2: "none",
            col_3: "none",
            col_4: "none",
            stylesheet: "css/stylesheet.css",
            on_keyup: true,
            on_keyup_delay: 500,
            input_watermark: "Search..."
    };
    var tf03 = setFilterGrid("table4",table4Filters);
</script>


            ';
 }

echo '</div>
</body>
 </html>';

?>
