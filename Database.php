<?php

class Database
{
//TODO separate Extend the class when we add more functionality, so that login can be separate from routes, etc.
//TODO put this base and constructor somewhere outside the serving dir, because passwords :D
   public function __construct()
    {

        date_default_timezone_set('UTC');
        $this->usr = 'jackrobe-db';
        $this->db = 'jackrobe-db';
        $this->host = 'oniddb.cws.oregonstate.edu';
        $this->password = 'Lcn03teET3EbfHb0';
        $this->success = '';
        $this->feedback = '';
        $this->id = '';
        $this->userName = '';

        // NEW MYSQL CONNECTION reference by $this->ms in subsequent useage
        $this->ms = new mysqli($this->host, $this->usr, $this->password, $this->db);

        if ($this->ms->connect_error) {
            $this->feedback = $this->ms->error;
            return false;
        }
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
            $this->feedback = "Registeration Failed";
            return false;
        }

    }
/*
    //````````````````````````````````````````````````````````````````
    //SAVE USERS LIST
    //LEAVING THIS HERE AS AN EXAMPLE UPDATING THE DB
    //@ param list - strings
    //
    function saveList($list)
    {

        // Now add both pass and user
        //bind
        if ($st = $this->ms->prepare("UPDATE user SET list =? WHERE id=?")){
            $st->bind_param("si", $list, $_SESSION['userid'] );

        }else {
            $this->feedback = "Failed at binding params";
            return false;
        }

        //execute
        if ($st->execute()) {
            if($st->affected_rows == 1){

                $this->userMusic = $list;
                $this->feedback = "Successfully Updated List";
                $st->close();
                return true;
            }else{

                $this->feedback = "Something went wrong no rows updated for user- " . $_SESSION['userid'];
                $st->close();
                return false;
            }

        } else {
            $st->close();
            $this->feedback = "Something went wrong in execution when updating your list";
            return false;
        }

    }

//########################################################### EXAMPLE
TO RETURN an ARRAY OF ELEMENTS
    //Get Art
    //@ param list
    //

    function get_art(){

        $st = $this->ms->stmt_init();

        $st->prepare( "SELECT  a.id, a.name, a.desc ,s.name as status, s.id as status_id, c.name as category, c.id as category_id, a.price, a.createdOn  FROM art as a
        INNER JOIN `user_art` as ua ON ua.aid = a.id
        LEFT JOIN `status` as s ON s.id = ua.sid
        LEFT JOIN `category` as c ON c.id = ua.cid
        WHERE ua.uid =?");

        $art = array();


        if (!$st->bind_param('i', $_SESSION['userid'] )) {
            return $this->feedback = "Failed at binding params";
        }
        $st->execute();
        $result = $st->get_result();  // get the result;
        if ($result->num_rows >= 1){

            for($i = 0; $art[$i] =  $result->fetch_assoc(); $i++);
            array_pop($art);  //because fetch assoc  always returns an extra 0

            $st->close();
            return $art;

        } else {

            $this->feedback = "empty";
            $st->close();
            return $art[] = '';
        }

    }

*/
}

?>
