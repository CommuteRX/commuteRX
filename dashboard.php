<?php





?>


<div class="container-fluid">

    <!-- Route Adding -->
    <div class="row">
        <div class="col-sm-6, col-lg-6">
            <h1>My routes:</h1>

                <div class="col-lg-12" id="listRoutes">
                    <ul class="list-group" id="route_list">

                    </ul>

                </div>

        </div>
        <div class="col-lg-6">

        </div>

    </div>

</div>


<script>

    $(document).ready(function()
    {
        var UrlToPass = 'action=list_routes';
        $.ajax({ // Send the credential values to  ajaxLogin.php using Ajax in POST menthod
            type : 'POST',
            data : UrlToPass,
            url  : 'ListRoutes',
            success: function(responseText){ // Get the result and assign to each cases
                //console.log(responseText);

                if(responseText == ''){
                    // handle no reply
                    $('#route_message').innerHTML = responseText.message
                }
                else{

                    listRoutes(responseText)
                }

            }
        });

    });
    function listRoutes(dbobj){


        var list = dbobj['data'];
        //todo Make this prettier!
        $.each( list, function( key, val ) {
            console.log(val);

            var $li = $("<li class=\"list-group-item\" ><strong>Name:</strong>"+val.route_name+"<br/><strong>Start:</strong>"+ val.route_start+ " <strong>End:</strong>"+ val.route_end+"</li>");
            $("#route_list").append($li);
        });


    }


</script>