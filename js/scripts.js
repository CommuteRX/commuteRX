//Credit: Followed guide http://www.sitepoint.com/use-jquerys-ajax-function/
// and http://www.9lessons.info/2014/07/ajax-php-login-page.html for how to incorporate shake animation effect


$(document).ready(function()
{
    $('#username').focus(); // Focus to the username field on body loads
    $('#login').click(function(){ // Create `click` event function for login
        var username = $('#username'); // Get the username field
        var password = $('#password'); // Get the password field
        var login_result = $('.login_result'); // Get the login result div
        login_result.html('loading..'); // Set the pre-loader can be an animation
        if(username.val() == ''){ // Check the username values is empty or not
            username.focus(); // focus to the filed
            login_result.html('<span class="error">Enter the username</span>');
            return false;
        }
        if(password.val() == ''){ // Check the password value is empty
            password.focus();
            login_result.html('<span class="error">Enter the password</span>');
            return false;
        }
        if(username.val() != '' && password.val() != ''){ // Check the username and password values is not empty and make the ajax request
            var UrlToPass = 'action=login&username='+username.val()+'&password='+password.val();
            $.ajax({ // Send the credential values to  ajaxLogin.php using Ajax in POST menthod
                type : 'POST',
                data : UrlToPass,
                url  : 'ajaxLogin.php',
                success: function(responseText){ // Get the result and assign to each cases
                    if(responseText == 0){
                        //Shake animation effect.
                        $('#box').shake();
                        login_result.html('<span id="incorrect_login" class="error">Username or Password Incorrect!</span>');
                    }
                    else if(responseText == 1){
                        window.location = 'dashboard.php';
                    }
                    else{
                        alert('Problem with sql query');
                    }
                }
            });
        }
        return false;
    });
});