<?php session_start();
	
	  // ----------------------- Processing Screen 1 --------------------------

	  if(isSet($_POST['submitted'])){
		include "config.php";
		$con = mysqli_connect($host,$username,$password,$db_name1);
		
		if (mysqli_connect_errno($con)){
		  echo "Failed to connect to MySQL: ".mysqli_connect_error();
		}else{
		  $error = 0;
		  $processed = 0;
		  $request="SELECT user_name, email FROM accountinfo WHERE user_name='". $_POST['uname']."' OR email='". $_POST['email']."' LIMIT 2";
		  $result = mysqli_query($con,$request);

		  if($result->num_rows != 0){
			while($row = $result->fetch_assoc()){
			  if($row['user_name'] == $_POST['uname'])
				$error += 1;
			  if($row['email'] == $_POST['email'])
				$error += 2;
			}
		  }
// 		  if(isSet($_POST['zip']) and !checkZip($_POST['zip']))
// 			$error +=4;
// 		  
		  if($_POST['pass']!=$_POST['pass2'])
			$error +=8;
		  
		  if($error==0){
			$request = "INSERT INTO user(first_name,last_name) VALUES ('".$_POST['fname']."','".$_POST['lname']."')";
			mysqli_query($con,$request);
			$id = $con->insert_id;
			
			$request = "INSERT INTO accountinfo(user_id, user_name, email, password) VALUES (".$id.",'".$_POST['uname']."','".$_POST['email']."','".hash('ripemd160', $_POST['pass'])."')";
			mysqli_query($con,$request);
			
			  $successMessage= "";
			  
			  $_SESSION['user_name'] = $_POST['uname'];
			  $_SESSION['user_id'] = $id;

			  $query = "SELECT image
				  FROM user
				  LEFT JOIN userpictures ON  user.picture_id = userpictures.picture_id
				  WHERE user.user_id = ".$_SESSION['user_id'] ." LIMIT 1;";
			   $result = mysqli_query($con,$query);
			   if($result->num_rows == 0){}
			   else{
			   	$row = $result->fetch_array();
			   	$_SESSION['profile_pic'] = isset($row['image']) ? "<a href='profile.php'><img src='data:image/jpg;base64,". base64_encode($row['image']) ."'/></a>" 
														: "<a href='profile.php'><img src='images/icons/default_avatar.jpg'/></a>";
			   }
			  
			  $successMessage .= "<div class='signUpLightBox'><p>Welcome to Garager, ".$_POST['uname']."</p>";
			  $successMessage .= "<p><a href='new_stuff.php' class='sign_up_process_link'>Let's get you started</a></p></div>";
			  		  
			  $processed = 1;
			  header('Content-Type: application/json');
			  echo json_encode(array('Success'=>$successMessage));

			  $_SESSION['threeStep'] = 1;
		  }
		}
	  }	  
	  
	  // ----------------------- Screen 1 --------------------------

	  if(!isSet($_POST['submitted']) or !$processed){
		if(isSet($error)){
		  $errorMessage = "";
		  if($error%2==1)
			$errorMessage.= "<p style='margin-left: -8px; margin-top: -5px; font-size: 8pt'><font color='red'>This username already exists in our database</font></p> ";
		  if(($error/2)%2==1)
			$errorMessage.= "<p style='margin-left: -8px; margin-top: -5px; font-size: 8pt'><font color='red'>This email address is already linked to an account</font></p> ";
		  if(($error/8)%2==1)
			$errorMessage.= "<p style='margin-left: -8px; margin-top: -5px; font-size: 8pt'><font color='red'>The confirmation password is different from the given password</font></p> ";
		  if(($error/4)%2==1)
			$errorMessage.= "<p style='margin-left: -8px; margin-top: -5px; font-size: 8pt'><font color='red'>Oops, your zipcode doesn't look like one!</font></p> ";
			
		  header('Content-Type: application/json');
		  echo json_encode(array('Error'=>$errorMessage));
		}
	
		
	  }
	  
	  

?>