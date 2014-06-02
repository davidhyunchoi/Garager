<?php

	// This page is used to reset password by entering the user's email address
	
	if(isSet($_POST['submit'])){
	
	   include('config.php');
	   $dbc = mysqli_connect($host,$username,$password,$db_name1);

	   // For storing errors:
	   $errors = array();
	   
	   if (mysqli_connect_errno($dbc)){
	   	    // fail to connect to database
		    echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  
		} else {
			$email = $_POST['email'];

			if(isSet($_POST['new_pwd'])){

				$new_pwd = $_POST['new_pwd'];

				if(isSet($_POST['cfm_new_pwd'])){

					$cfm_new_pwd = $_POST['cfm_new_pwd'];

					if($new_pwd == $cfm_new_pwd){


						// Check for the existence of that email address...
		       			$q = 'SELECT * FROM accountinfo WHERE email="'.  mysqli_real_escape_string ($dbc, $email) . '"';
		       			$r = mysqli_query ($dbc, $q);

		       			// Retrieve the user entry:
						$row = mysqli_fetch_array ($r, MYSQLI_ASSOC); 
		
						if ($_GET['code'] == $row['password']) { 
				    		

							$q = "UPDATE accountinfo SET password='".hash('ripemd160', $new_pwd)."' WHERE email='".$email."'";
							$r = mysqli_query ($dbc, $q);

							if(mysqli_affected_rows($dbc) == 1){

								include('header_reset.php');
								echo "<div class='reset_pwd_page'>
					     		<h1>Congratulations! You have successfully changed your password.</h1><br>
						 		</div>";
								include('footer_reset.php');

							}
							
						

						}



					}
					else{
						$errors['pwd_not_equal'] = 'Please check if your passwords match and try again';
					}
				}
				else{

					$errors['cfm_new_pwd'] = 'Please confirm your new password';
				}
			}
			else{

				$errors['new_pwd'] = 'Please enter your new password';
			}

		}




	}






?>