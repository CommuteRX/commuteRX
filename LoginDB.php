<?php

class LoginDB extends Database
{


    //NOTE we can make a new constructor here that calls the parents, but it seems messy to me
    //I.e if you find yourself having to use an own constructor here, either it doesnt belong to the parent class
    // or you need to update the parent class's consctor

    /** check_userName
     * Checks if user name is in the database

     * @param {string} $user
     * @this { check_userName  }
     * @return { boolean} True if present
     */
    public function check_userTEST(&$usr)
    {
        $this->feedback = "Hi " . $usr;

        echo ' Welcome and ';

    }

}

?>
