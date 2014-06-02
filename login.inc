<?php
// login.inc template file
?>
<div id="logInLightBox" class="lightBox">
        <div class="logInInTitle">
           LOGIN
        </div>
        
            <!-- Insert pic of Facebook icon -->
            <div id="facebook">
                <a href="http://www.facebook.com"><img src="images/icons/facebook.jpg"></a>
            </div>
           
        

        <div class="LoginGeneral">
        OR Sign in with your Username or Email<p>
        </div>
        <div class="errorMessage">
        </div>
        <div class="signInBox">
        <form id='login_form' action='' method='post' >
            <input id="username" name="user_name" type="text" value="Username or Email"><br>
            <input id="password" name="password" type="text" value="Password"><br>
            <input type='hidden' name='submitted' id='submitted' value='1'>
            <input id='login_button' type='submit' value='LOG IN'>
        </form> 
        </div>
        <div id="rememberMe">
        Remember me 
        <input type="checkbox">
        </div>
    <!--    <div class="bottomLine">
            <p>Forgot your password?</p>
            <a href="#" id="bottonTitleReset">Reset it here</a>
        </div>
    -->
    <div id="reset">
        Forget your password?
        <a href="ResetPWDLightBox" id="bottonTitleReset" class="lightbox_trigger">Reset it here</a>
    </div>

    </div>
<?php
// End login.inc template file
?>