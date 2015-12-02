<!DOCTYPE html>
<html lang="en">
<head>
    <?php echo("<title>$page_title</title>"); ?>

    <!--Todo add metadata-->
    <meta charset="utf-8" />
    <link href='https://fonts.googleapis.com/css?family=Lora' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet' type='text/css'>
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script type="text/javascript" language="javascript" src="js/tablefilter.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>



    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.rawgit.com/twbs/bootstrap/v4-dev/dist/css/bootstrap.css">
    <link rel="stylesheet" href="css/stylesheet.css" type="text/css">
    

    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    <style type="text/css">
        .modal-alert{
            margin: 20px;
        }
        #map {
            height: 100%;
        }
        #start, #end{width:100% !important;}
        .pac-item,.pac-container {width:inherit !important;}
    </style>
    <script>
       /* var phpDate = new Date("<?php //echo $datePickerDate;?>");
        $(function() {
            $( "#datepicker" ).datepicker();
            $( "#datepicker" ).datepicker( "setDate", phpDate);
        });*/
    </script>

    <style>
        .custom-combobox {
            position: relative;
            display: inline-block;
        }
        .custom-combobox-toggle {
            position: absolute;
            top: 0;
            bottom: 0;
            margin-left: -1px;
            padding: 0;
        }
        .custom-combobox-input {
            margin: 0;
            padding: 5px 10px;
        }
    </style>


</head>
<body>
<div class="top_nav">
    <a href="Dashboard">
        <img src=""/>
        <h2>CommuteRX</h2>
    </a>
</div>
<div class="welcome">
    <ul>
        <li><h3><a href="Dashboard"></a></h3></li>
        <li>WELCOME, <?php echo $_SESSION['username'];?>!</li>
        <li><a href="Logout"><span class="icon-switch">

				</span>Log Out</a></li>
    </ul>
</div>
<div class="user_page_location">
    <?php //echo("<h2>$user_page_location</h2>"); ?>

</div>

    <div class="navigation_btns">
        <a href="Dashboard">
            <div class="my_dashboard">
                <h2><i class="icon-home"></i>  My Dashboard</h2>
            </div>
        </a>
        <a href="Routes">
            <div class="register_new_patient">
                <h2><i class="icon-user-plus">

                </i>  Add Route</h2>
            </div>
        </a>
        <a href="">
            <div class="patient_database">
                <h2><i class="icon-users">

                    </i>  My Account</h2>
            </div>
        </a>
    </div>
