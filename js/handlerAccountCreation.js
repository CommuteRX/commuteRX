//Credit: Followed guide http://www.sitepoint.com/use-jquerys-ajax-function/
// and http://www.9lessons.info/2014/07/ajax-php-login-page.html for how to incorporate shake animation effect

$(document).ready(function()
{
    $('#register_username').focus(); // Focus to the username field on body loads
    $('#Register').click(function(){ // Create `click` event function for Register

        var username = $('#register_username'); // Get the username field
        var password = $('#register_password'); // Get the password field
        var first_name = $('#register_first_name'); // Get the first name field
        var last_name = $('#register_last_name'); // Get the last name field
        var professional_title = $('#register_title'); // Get the title field

        var register_result = $('.register_result'); // Get the register result div

        register_result.html('loading..'); // Set the pre-loader can be an animation

        // Check if the first_name value is empty
        if(first_name.val() == '')
        {
            password.focus();
            register_result.html('<span class="error">Enter your first name</span>');
            return false;
        }

        // Check if the last_name value is empty
        if(last_name.val() == '')
        {
            password.focus();
            register_result.html('<span class="error">Enter your last name</span>');
            return false;
        }

        // Check if the title value is empty
        if(professional_title.val() == '')
        {
            password.focus();
            register_result.html('<span class="error">Enter your professional title</span>');
            return false;
        }

        // Check if the username value is empty
        if(username.val() == '')
        {
            username.focus(); // focus to the filed
            register_result.html('<span class="error">Enter the username</span>');
            return false;
        }

        // Check if the password value is empty
        if(password.val() == '')
        {
            password.focus();
            register_result.html('<span class="error">Enter the password</span>');
            return false;
        }

        // Check the username and password values is not empty and make the ajax request
        if(username.val() != '' && password.val() != '' && first_name.val() != '' && last_name.val() != '' && professional_title.val() != '')
        {
            var UrlToPass = 'action=register&register_username='+username.val()+'&register_password='+password.val()+'&register_first_name='+first_name.val()+'&register_last_name='+last_name.val()+'&register_title='+professional_title.val();

            // Send the credential values to accountRegister.php using Ajax in POST method
            $.ajax(
                {
                type : 'POST',
                data : UrlToPass,
                url  : 'validateRegistration',
                success: function(responseText)  // Get the result and assign to each cases
                {
                    if(responseText != 1){
                        //Shake animation effect.
                        $('#box').shake();
                        register_result.html('<span id="incorrect_login" class="error">Username taken!</span>' +responseText);
                    }
                    else if(responseText == 1){

                        register_result.html('SUCCESS!');
                        window.location = 'Dashboard';
                        //todo would be nice to thank the user ontop of the login page they get sent to
                        // alternatively they could get their session vars set when they register...but..
                        ///console.log(responseText);
                    }
                    else{
                        alert('Problem with sql query' + responseText);
                    }
                }
            });
        }
        return false;
    });
});