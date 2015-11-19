
<!DOCTYPE html>
<html lang="en">
<head>
    <?php echo("<title>$page_title</title>"); ?>
    <meta charset="utf-8" />
    <link href='http://fonts.googleapis.com/css?family=Lora' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet' type='text/css'>


    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script type="text/javascript" language="javascript" src="TableFilter/tablefilter.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>



    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

    <link rel="stylesheet" href="css/stylesheet.css" type="text/css">
    

    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    <style type="text/css">
        .modal-alert{
            margin: 20px;
        }
    </style>
    <script>
        var phpDate = new Date("<?php echo $datePickerDate;?>");
        $(function() {
            $( "#datepicker" ).datepicker();
            $( "#datepicker" ).datepicker( "setDate", phpDate);
        });
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
    <script>
        (function( $ ) {
            $.widget( "custom.combobox", {
                _create: function() {
                    this.wrapper = $( "<span>" )
                        .addClass( "custom-combobox" )
                        .insertAfter( this.element );

                    this.element.hide();
                    this._createAutocomplete();
                    this._createShowAllButton();
                },

                _createAutocomplete: function() {
                    var selected = this.element.children( ":selected" ),
                        value = selected.val() ? selected.text() : "";

                    this.input = $( "<input>" )
                        .appendTo( this.wrapper )
                        .val( value )
                        .attr( "title", "" )
                        .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
                        .autocomplete({
                            delay: 0,
                            minLength: 0,
                            source: $.proxy( this, "_source" )
                        })
                        .tooltip({
                            tooltipClass: "ui-state-highlight"
                        });

                    this._on( this.input, {
                        autocompleteselect: function( event, ui ) {
                            ui.item.option.selected = true;
                            this._trigger( "select", event, {
                                item: ui.item.option
                            });
                        },

                        autocompletechange: "_removeIfInvalid"
                    });
                },

                _createShowAllButton: function() {
                    var input = this.input,
                        wasOpen = false;

                    $( "<a>" )
                        .attr( "tabIndex", -1 )
                        .attr( "title", "Show All Items" )
                        .tooltip()
                        .appendTo( this.wrapper )
                        .button({
                            icons: {
                                primary: "ui-icon-triangle-1-s"
                            },
                            text: false
                        })
                        .removeClass( "ui-corner-all" )
                        .addClass( "custom-combobox-toggle ui-corner-right" )
                        .mousedown(function() {
                            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
                        })
                        .click(function() {
                            input.focus();

                            // Close if already visible
                            if ( wasOpen ) {
                                return;
                            }

                            // Pass empty string as value to search for, displaying all results
                            input.autocomplete( "search", "" );
                        });
                },

                _source: function( request, response ) {
                    var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
                    response( this.element.children( "option" ).map(function() {
                        var text = $( this ).text();
                        if ( this.value && ( !request.term || matcher.test(text) ) )
                            return {
                                label: text,
                                value: text,
                                option: this
                            };
                    }) );
                },

                _removeIfInvalid: function( event, ui ) {

                    // Selected an item, nothing to do
                    if ( ui.item ) {
                        return;
                    }

                    // Search for a match (case-insensitive)
                    var value = this.input.val(),
                        valueLowerCase = value.toLowerCase(),
                        valid = false;
                    this.element.children( "option" ).each(function() {
                        if ( $( this ).text().toLowerCase() === valueLowerCase ) {
                            this.selected = valid = true;
                            return false;
                        }
                    });

                    // Found a match, nothing to do
                    if ( valid ) {
                        return;
                    }

                    // Remove invalid value
                    this.input
                        .val( "" )
                        .attr( "title", value + " didn't match any item" )
                        .tooltip( "open" );
                    this.element.val( "" );
                    this._delay(function() {
                        this.input.tooltip( "close" ).attr( "title", "" );
                    }, 2500 );
                    this.input.autocomplete( "instance" ).term = "";
                },

                _destroy: function() {
                    this.wrapper.remove();
                    this.element.show();
                }
            });
        })( jQuery );

        $(function() {
            $( "#combobox" ).combobox();
            $( "#toggle" ).click(function() {
                $( "#combobox" ).toggle();
            });
        });
    </script>

</head>
<body>
<div class="top_nav">
    <a href="dashboard.php">
        <img src="img/heart_white.png"/>
        <h2>MedChart</h2>
    </a>
</div>
<div class="welcome">
    <ul>
        <li><h3><a href="dashboard.php"></a></h3></li>
        <li>WELCOME, <?php echo $user_name;?>!</li>
        <li><a href="logout.php"><span class="icon-switch">

				</span>Log Out</a></li>
    </ul>
</div>
<div class="user_page_location">
    <?php echo("<h2>$user_page_location</h2>"); ?>

</div>

    <div class="navigation_btns">
        <a href="dashboard.php">
            <div class="my_dashboard">
                <h2><i class="icon-home"></i>  My Dashboard</h2>
            </div>
        </a>
        <a href="patientList.php">
            <div class="patient_database">
                <h2><i class="icon-users">

                </i>  Patient Database</h2>
            </div>
        </a>
        <a href="registerPatient.php">
            <div class="register_new_patient">
                <h2><i class="icon-user-plus">

                </i>  Register New Patient</h2>
            </div>
        </a>
    </div>
