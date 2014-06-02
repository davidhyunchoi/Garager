<?php

	// This page is used to reset password by entering the user's email address
	
	if(isSet($_POST['submitted'])){
	
	   include('config.php');
	   $dbc = mysqli_connect($host,$username,$password,$db_name1);

	   // For storing errors:
	   $errorMessage = "";
	   
	   if (mysqli_connect_errno($dbc)){
	   	    // fail to connect to database
		    echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  
		} else {
		    // connect to database success

	  		// Validate the email address:
	        if (filter_var($_POST['reset_email'], FILTER_VALIDATE_EMAIL)) {
	
		       // Check for the existence of that email address...
		       $q = 'SELECT * FROM accountinfo WHERE email="'.  mysqli_real_escape_string ($dbc, $_POST['reset_email']) . '"';
		       $r = mysqli_query ($dbc, $q);
		
				if (mysqli_num_rows($r) == 1) { 
				    // Retrieve the user entry:
					$row = mysqli_fetch_array ($r, MYSQLI_ASSOC); 


					$passcode = hash('ripemd160', uniqid(rand(), true));
					//$passcode = 'newpass';

	
					$q = "UPDATE accountinfo SET password='".$passcode."' WHERE email='".$_POST['reset_email']."'";
					$r = mysqli_query ($dbc, $q);
					if(mysqli_affected_rows($dbc) == 1){
						

						// send email section
	  					$body = "Your password http://localhost:8888/Garager-Temp/reset_password.php?code=".$passcode."&email=".$_POST['reset_email'];
						$isMailSuccess = mail ($_POST['reset_email'], 'Your temporary password.', $body, 'From: auto@garager.com');
					
						if($isMailSuccess){
							
							
							$successMessage= "";
			  				$successMessage .= "<div class='ResetPWDLightBox'><p>Thanks! An email has been sent to the email address you entered to reset your password</p></div>";
			  		  		
			  		  		//$successMessage .= "<div class='ResetPWDLightBox'><p>Welcome to Garager, "."</p>";
			                //$successMessage .= "<p><a href='new_stuff.php' class='sign_up_process_link'>Let's get you started</a></p></div>";
			
			  				header('Content-Type: application/json');
			  				echo json_encode(array('Success'=>$successMessage));

				    	}
					}
					

				} else { 
				    // No database entry match.
					$errorMessage.= "<p><font color='red'>The submitted email address does not match those on file!</font></p> "; 
				}
		
			} else { 
			        // No valid address submitted.
					$errorMessage.= "<p><font color='red'>Please enter a valid email address!</font></p> "; 
			} 

			if($errorMessage != ""){

				header('Content-Type: application/json');
		        echo json_encode(array('Error'=>$errorMessage));
			}


		
		} 

	}
	
	
	if(isSet($_GET['code'])&&isSet($_GET['email'])){

	   include('config.php');
	   $dbc = mysqli_connect($host,$username,$password,$db_name1);

	   // For storing errors:
	   $errors = array();
	   
	   if (mysqli_connect_errno($dbc)){
	   	    // fail to connect to database
		    echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  
		} else {

			// Check for the existence of that email address...
		    $q = 'SELECT * FROM accountinfo WHERE email="'.  mysqli_real_escape_string ($dbc, $_GET['email']) . '"';
		    $r = mysqli_query ($dbc, $q);
			
		    if (mysqli_num_rows($r) == 1) { 
		    	// Retrieve the user entry:
				$row = mysqli_fetch_array ($r, MYSQLI_ASSOC); 
				if($row['password'] == $_GET['code']){
					$email = $_GET['email'];
					$code = $_GET['code'];
					include('header_reset.php');
					echo "<div class='reset_pwd_page'>
					     <h1>Reset Password</h1><br>
						 <form action='complete_reset_password.php?code=$code' method='POST'>
						 Enter your new password: <br>
						 <input type='password' name='new_pwd' required><br>
						 Confirm your new password: <br>
						 <input type='password' name='cfm_new_pwd' required><br>
						 <input type='hidden' name='email' value='$email'>
						 <input type='submit' name='submit' value='Submit'>
						 </form>
						 </div>";
					include('footer_reset.php');
				}
				else{
					// URL code don't match the database entry.
					$errors['email'] = 'The URL code does not match those on file!';
					echo $errors['email'];
				}
			}
			else{
				// No database entry match the URL email address.
				$errors['email'] = 'The URL email address does not match those on file!';
				echo $errors['email'];

			}


		}	

	}
	
	
	
	







	


			
?>