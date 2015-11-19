<?php
include 'init.php';

if(!isset($_SESSION['userid']) || $_SESSION['userid'] == ''){
    echo '<script type="text/javascript">window.location = "index.php"; </script>';
}

$current_user = $_SESSION['userid'];
$user_name = $_SESSION['username'];


$user_page_location = "Register New Patient";
$page_title = "MedChart-Register Patient";
include 'header.php';


/******************************************************
 *
 *     REGISTER NEW PATIENT TO MAIN DATABASE
 *
 *****************************************************/
//register a new patient to the main database list
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    if(isset($_POST['registerNewPatient'])) //user tried to register new patient
    {
        $errors = false;

        $ConvertDOB = str_replace('-','/',  $_POST['date_of_birth']);
        $patient_dob = date("Y-m-d", strtotime($ConvertDOB));

        //required field error variables
        $first_name_error = $last_name_error = $middle_name_error = "";
        $dob_error = $sex_error = "";

        //optional field error variables
        $email_error = $phone_error = "";

        //variables for use in keeping entries if error occurs
        $first_name = $_POST['first_name'];
        $middle_name = $_POST['middle_name'];
        $last_name = $_POST['last_name'];
        $dob = $_POST['date_of_birth'];
        $sex = $_POST['sex'];
        $address_l1 = $_POST['address_l1'];
        $address_l2 = $_POST['address_l2'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $zip = $_POST['zip'];
        $country = $_POST['country'];
        $home_phone = $_POST['home_phone'];
        $work_phone = $_POST['work_phone'];
        $mobile_phone = $_POST['mobile_phone'];
        $email = $_POST['email'];
        $emergency_contact = $_POST['emergency_contact'];


        if(empty($_POST['first_name']))
        {
            $first_name_error = "First name is required";
            $errors = true;
        }
        else
        {
            if (!preg_match("/^[a-zA-Z ]*$/",$_POST['first_name']))
            {
                $first_name_error = "Only letters and white space allowed";
                $errors = true;
            }
        }

        if(empty($_POST['last_name']))
        {
            $first_name_error = "Last name is required";
            $errors = true;
        }
        else
        {
            if (!preg_match("/^[a-zA-Z ]*$/",$_POST['last_name']))
            {
                $last_name_error = "Only letters and white space allowed";
                $errors = true;
            }
        }

        if(empty($_POST['date_of_birth']))
        {
            $dob_error = "Date of birth is required";
            $errors = true;
        }
        else
        {
            if(!checkDateFormat($patient_dob))
            {
                $dob_error = "Incorrect format for date of birth";
                $errors = true;
            }

        }

        if(empty($_POST['sex']))
        {
            $sex_error = "Please select gender";
            $errors = true;
        }

        if(!empty($_POST['email']))
        {
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
            {
                $errors = true;
                $email_error = "Invalid email format";
            }

        }

        if(!empty($_POST['home_phone']))
        {
            if(!isPhoneNumber($_POST['home_phone']))
            {
                $phone_error = "Invalid phone number format!";
                $errors = true;
            }

        }

        if(!empty($_POST['work_phone']))
        {
            if(!isPhoneNumber($_POST['work_phone']))
            {
                $phone_error = "Invalid phone number format!";
                $errors = true;
            }

        }

        if(!empty($_POST['mobile_phone']))
        {
            if(!isPhoneNumber($_POST['mobile_phone']))
            {
                $phone_error = "Invalid phone number format!";
                $errors = true;
            }
        }

        if(!empty($_POST['middle_name']))
        {
            if (!preg_match("/^[a-zA-Z ]*$/",$_POST['middle_name']))
            {
                $middle_name_error = "Only letters and white space allowed";
                $errors = true;
            }
        }

    }

    if(!$errors)//safe to add the patient
    {
        //generate a picture for the patient
        $remote_url = 'http://uifaces.com/api/v1/random';
        $json = file_get_contents($remote_url);
        $pic = json_decode($json, true);
        $patient_pic = $pic['image_urls']['epic'];
        $_POST['patient_pic'] = $patient_pic;


        /* Prepared statement, stage 1: prepare */
        if (!($stmt = $mysqli->prepare("INSERT INTO mc_patient(first_name, last_name, date_of_birth, sex, picture, middle_name, email, address_l1, address_l2,
            city, state, zip, country, home_phone, work_phone, mobile_phone, emergency_contact ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        /* Prepared statement, stage 2: bind and execute */
        if (!$stmt->bind_param("sssssssssssssssss", $_POST['first_name'], $_POST['last_name'], $patient_dob,
            $_POST['sex'], $_POST['patient_pic'], $_POST['middle_name'], $_POST['email'], $_POST['address_l1'],
            $_POST['address_l2'], $_POST['city'], $_POST['state'], $_POST['zip'], $_POST['country'], $_POST['home_phone'],
            $_POST['work_phone'], $_POST['mobile_phone'], $_POST['emergency_contact'] )) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        //clear the variables
        $first_name = $middle_name = $last_name = $dob = $sex = $address_l1 = $address_l2 = $city =
        $state = $zip = $country = $home_phone = $work_phone = $mobile_phone = $email = $emergency_contact = "";


        header("Location: patientList.php");

    }
}

?>
    <div class="new_patient_registration">
        <fieldset>
            <!-- <h2>Register New Patient</h2><br> -->
                    <p><span class="error">* Required field.</span></p><br>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                        <div style="white-space:nowrap">
                          <label for="first_name">First Name:</label>
                          <input type="text" name="first_name"  required value="<?php echo $first_name;?>">
                          <span class="error">* <?php echo $first_name_error;?></span>
                        </div>
                        <div style="white-space:nowrap">
                          <label for="middle_name">Middle Name:</label>
                          <input type="text" name="middle_name" value="<?php echo $middle_name;?>">
                          <span class="error"><?php echo $middle_name_error;?></span>
                        </div>
                        <div style="white-space:nowrap">
                          <label for="last_name">Last Name:</label>
                          <input type="text" name="last_name" required value="<?php echo $last_name;?>">
                          <span class="error">* <?php echo $last_name_error;?></span>
                        </div>
                        <div style="white-space:nowrap">
                          <label for="date_of_birth">Date of Birth:</label>
                          <input type="text" name="date_of_birth" placeholder="MM/DD/YYYY" required value="<?php echo $dob;?>">
                          <span class="error">* <?php echo $dob_error;?></span>
                        </div>
                        <div style="white-space:nowrap">
                          <label for="sex">Sex:</label>
                          <select required  name="sex" value="<?php echo $sex;?>">
                                  <option value="">Select</option>
                                  <option value="m">Male</option>
                                  <option value="f">Female</option>
                          </select>
                          <span class="error">* <?php echo $sex_error;?></span>
                        </div>
                        <div style="white-space:nowrap">
                          <label for="address_l1">Address Line 1: </label>
                          <input type="text" size="50" name="address_l1" placeholder="Street address, P.O box" value="<?php echo $address_l1;?>">
                        </div>
                        <div style="white-space:nowrap">
                          <label for="address_l2">Address Line 2: </label>
                          <input type="text" size="50" name="address_l2" placeholder="Apartment, suite, unit, building, floor, etc." value="<?php echo $address_l2;?>">
                        </div>
                        <div style="white-space:nowrap">
                          <label for="city">City: </label>
                          <input type="text" name="city" value="<?php echo $city;?>">
                        </div>
                        <div style="white-space:nowrap">
                          <label for="usstates">State/Province/Region: </label>
                          <select name="state" id="usstates" value="<?php echo $state;?>">';

                                echo'<option value="">Select</option>';

                                <?php
                                //http://www.qwc.me/2013/12/us-states-list-static-php-array-with.html
                                foreach($usstates as $statcode => $statname)
                                {
                                    echo'<option value="'.$statcode.'">'.$statname.'</option>';
                                }

                                ?>
                          </select>
                        </div>
                        <div style="white-space:nowrap">
                          <label for="zip">Zip/Postal Code: </label>
                          <input type="text" name="zip" value="<?php echo $zip;?>">
                        </div>
                        <div style="white-space:nowrap">
                          <label for="country">Country: </label>
                          <input type="text" name="country" value="<?php echo $country;?>">
                        </div>
                        <div style="white-space:nowrap">
                          <label for="home_phone">Home Phone: </label>
                          <input type="text" name="home_phone" value="<?php echo $home_phone;?>">
                        </div>
                        <div style="white-space:nowrap">
                          <label for="home_phone">Home Phone: </label>
                          <input type="text" name="home_phone" value="<?php echo $home_phone;?>">
                          <span class="error"><?php echo $phone_error;?></span>
                        </div>
                        <div style="white-space:nowrap">
                          <label for="work_phone">Work Phone: </label>
                          <input type="text" name="work_phone" value="<?php echo $work_phone;?>">
                          <span class="error"><?php echo $phone_error;?></span>
                        </div>
                        <div style="white-space:nowrap">
                          <label for="mobile_phone">Mobile Phone: </label>
                          <input type="text" name="mobile_phone" value="<?php echo $mobile_phone;?>">
                          <span class="error"><?php echo $phone_error;?></span>
                        </div>
                        <div style="white-space:nowrap">
                          <label for="email">Email: </label>
                          <input type="text" name="email" value="<?php echo $email;?>">
                          <span class="error"><?php echo $email_error;?></span>
                        </div>
                        <div style="white-space:nowrap">
                          <label for="emergency_contact">Emergency Contact: </label>
                          <input type="text" name="emergency_contact">
                        </div>
                        <p><input type= "hidden" name="registerNewPatient" value="1">
                        <p><input type="submit" value="Submit">
                    </form>
        </fieldset>
    </div>
</body>
</html>
