<?php

class RoutesDB extends Database
{

    //NOTE we can make a new constructor here that calls the parents, but it seems messy to me
    //I.e if you find yourself having to use an own constructor here, either it doesnt belong to the parent class
    // or you need to update the parent class's constuctor


    /** GET USER ROUTES FROM DB
     * @return array|string
     * @param uid the user's ID
     */
    public function get_userRoutes(&$uid)
    {
        $st = $this->ms->stmt_init(); // VIP

        $st->prepare( "SELECT
                        rout.route_name,
                        rout.route_start,
                        rout.route_end
                        FROM
                        crx_user AS `user`
                        INNER JOIN crx_user_route AS urout ON urout.uid = `user`.user_id
                        INNER JOIN crx_routes AS rout ON urout.rid = rout.id
                                              WHERE user.user_id =?");

        $routes = array();

        if (!$st->bind_param('i', $uid )) {
            return $this->feedback = "Failed at binding params";
        }
        $st->execute();
        $result = $st->get_result();  // get the result;
        if ($result->num_rows >= 1){

            for($i = 0; $routes[$i] =  $result->fetch_assoc(); $i++);
            array_pop($routes);  //because fetch assoc  always returns an extra 0

            $st->close();
            return $routes;

        } else {

            $this->feedback = "empty";
            $st->close();
            return $routes[] = '';
        }

    }

    /** ADDS A ROUTES TO DB
     * @return t/f on success
     * @ sets feedback
     * @param string|start, string|end, string|name, userID
     */
    public function addRoute(&$start, &$end, &$name, &$uid ){

        // Now add the route
        //bind

        if ($st = $this->ms->prepare("INSERT INTO crx_routes(route_name, route_start, route_end) VALUES ( ?, ?, ?)")) {
            $st->bind_param("sss", $name, $start, $end);
        } else {
            $this->feedback = 'Failed binding';
            return false;
        }
        //execute
        if ($st->execute()) {
            $rid = $st->insert_id;

            if($this->addUserRoute( $uid, $rid)){

                $this->feedback .= "Successfully Added";
                $st->close();
                return true;

            }else{
                $this->feedback .= "Unsuccessful adding to user routes :(" .$rid .';'. $uid . ')';
                $st->close();
                return false;

            }


        } else {
            $st->close();
            $this->feedback = $this->ms->error;
            $this->feedback .= "Couldn't Add Route";
            return false;
        }

    }

    /** Links a route to a user
     * @return t/f on success
     * @ sets feedback
     * @param the route ID, userID
     */
    private function addUserRoute(&$uid, &$rid)
    {

            // Now add the route
            //bind
            if ($st = $this->ms->prepare("INSERT INTO crx_user_route(uid, rid ) VALUES (?,?)")) {
                $st->bind_param("ii", $uid, $rid);
            } else {
                $this->feedback = 'Failed binding';
                return false;
            }
            //execute
            if ($st->execute()) {

                //$this->userid = $st->insert_id; // grabs the newly created id

                $this->feedback = "Added User Route | ";
                $st->close();
                return true;

            } else {
                $st->close();
                $this->feedback .= $this->ms->error;
                $this->feedback .= "Cannot Add Route to User Inventory or User Session Failed $uid , $rid| ";
                return false;
            }


    }


}

?>
