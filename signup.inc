<?php
// signup.inc template file
?>
<div id="signUpLightBox" class="lightBox">

    <div class="signUpTitle">
    SIGN UP FOR GARAGER
    </div>
<table class="forminp">
	<form id='signup_form' action='' method='post' >
		<tr>
			<td><label for='fname'>First Name</label></td>
			<td><label for='lname'>Last Name</label></td>
	    </tr>
	    <tr>
			<td><input type='text' name='fname' id='fname' value=''></td>
			<td><input type='text' name='lname' id='lname' value=''></td>
		</tr>
		<tr>
		    <td><label for='email'>Email<font color='red'> *</font></label></td>
			<td><label for='zip'>Zip Code</label></td>
		</tr>
		<tr>
	  	    <td><input type='text' name='email' id='email' value='' required></td>
			<td><input type='text' name='zip' id='zip' value=''></td>
		</tr>
		<tr>
			<td><label for='uname'>Username<font color='red'> *</font></label></td>
			<td></td>
		</tr>
		<tr>
		<td><input type='text' name='uname' id='uname' value='' required></td>
		<td></td>
		</tr>
		<tr>
			<td><label for='pass'>Password<font color='red'> *</font></label></td>
			  <td></td>
		</tr>
		<tr>
			<td><input type='password' name='pass' id='pass' value='' required></td>
			<td></td>
		</tr>
		<tr>
		<td><label for='pass2'>Confirm Password</label></td>
		<td></td>
		</tr>
		<tr>
		    <td><input type='password' name='pass2' id='pass2' value='' required></td>
			<td></td>
		</tr>
		<tr colspan=2>
		    <td id='agreement'>By clicking Sign Up, I agree to Garagers <a href='#'>Terms of Use</a> and <a href='privacy.php'>Privacy Statement</a></td>
		</tr>
		<input type='hidden' name='submitted' id='submitted' value='1'>
		<tr colspan=2>
			<td><input id='signup_button' type='submit' value='Sign Up'></td>
		</tr>
	</form>
</table>
    <div class="errorMessage">	
   	</div>
</div>

<?php
// End signup.inc template file
?>


