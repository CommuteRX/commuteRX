<?php

//Removed datbase user and password for security

class Database
{
    public function __construct()
    {

        date_default_timezone_set('UTC');
        $this->usr = '';
        $this->db = '';
        $this->host = 'oniddb.cws.oregonstate.edu';
        $this->password = '';
        $this->success;
        $this->feedback = '';
        $this->id = '';
        $this->userName = '';

        // NEW MYSQL CONNECTION reference by $this->ms in subsequent usage
        $this->ms = new mysqli($this->host, $this->usr, $this->password, $this->db);

        if ($this->ms->connect_error) {
            $this->feedback = $this->ms->connect_error;

            $this->success = false;
            return false;
        }
        $this->success = true;
        return true;
    }


    public function __destruct()
    {
        //print "Destroying " . $this->usr . "\n";

    }


    /** check_userName
     * Checks if user name is in the database
     * @param {string} $user
     * @this { check_userName  }
     * @return { boolean} True if present
     */
    function check_userName(&$usr)
    {
        $this->feedback = "";
        //bind
        if ($st = $this->ms->prepare("SELECT user_name FROM crx_user WHERE user_name=? ")) {
            $st->bind_param("s", $usr);
        }else {
            $this->feedback .= "No Binding";
        }
        //execute
        if (!$st->execute()) {
            $st->close();
            $this->feedback .= "No execution";
            //fetch
        } else {
            // check if it's there
            if (!$st->fetch()) {
                $this->feedback = "User Name does not exist - try again";
                $st->close();

            } else {
                return true;
            }
        }
        return false;
    }


    /**
     * checks the user and password against the DB using sha1
     * note pass by reference
     * @param {string} user name
     * @param {string} password unencrpyted
     *
     * @return { Boolean } true if login successful i.e. there is a match
     *  Additionaly sets constructor variables, feedback, id, and username
     **/
    function login(&$usr, &$pass)
    {

        //sha1 the password
        $hashedPass = sha1($pass);

        //check for both pass and user
        if ($st = $this->ms->prepare("SELECT `user_name`, `hash`, `user_id` FROM crx_user WHERE user_name=? AND hash=? ")) {
            $st->bind_param("ss", $usr, $hashedPass);

        } else {

            $this->feedback = "Failed Binding";
        }
        //execute   (flows differently when its a select see registration function for insert flow )
        $st->execute();
        $result = $st->get_result();

        //it worked
        if ($result->num_rows == 1) {

            //get the info
            while ($row = $result->fetch_assoc()) {
                $this->id = $row['user_id'];
                $this->userName = $row['user_name'];
            }

            $this->feedback = "Login Successful! for user " . $this->id;
            $st->close();
            return true;

            // nothing was updated
        } else {

            $this->feedback = "Password is incorrect - try again";
            $st->close();
            return false;

        }

    }




    /**
     * Entrers user, fname and lname and password
     *
     * @param {string} user name
     * @param {string} password unencrpyted
     * @param {string} first name
     * @param {string} lastname
     * @return { Boolean } true if login successful i.e. there is a match
     *  Additionaly sets constructor variables, feedback, id, and username
     **/
    function registration(&$usr, &$pass, &$fname, &$lname)
    {

        //sha1 the password
        $hashedPass = sha1($pass);

        if ($this->check_userName($usr)) {
            $this->feedback = "User Already exists - try another name";

            return false;
        }
        // Now add both pass and user
        //bind

        if ($st = $this->ms->prepare("INSERT INTO crx_user(user_name, hash, first_name, last_name) VALUES (?, ?, ?, ?)")){
            $st->bind_param("ssss", $usr, $hashedPass, $fname, $lname);
        }else{
            $this->feedback = 'Failed binding';


        }
        //execute
        if ($st->execute()) {

            $this->userid = $st->insert_id; // grabs the newly created id
            $this->feedback = "Successfully Registered";
            $st->close();

            return true;

        } else {
            $st->close();
            $this->feedback = $this->ms->error;
            $this->feedback = "Registration Failed";
            return false;
        }

    }


}
























