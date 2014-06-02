<?php session_start();


	  if(isSet($_POST['submitted'])){		//To display if the form has already been submitted
		include('config.php');					//File containing the password
		$con = mysqli_connect($host,$username,$password,$db_name1);
		
		if (mysqli_connect_errno($con)){
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}else{
		  $request="SELECT user_name, password, email, user_id FROM accountinfo WHERE user_name='". $_POST['user_name'] ."' OR email='". $_POST['user_name']."' LIMIT 1";
		  $result = mysqli_query($con,$request);
		  if($result->num_rows == 0){
			$processed = 0;
		  }else{
			$row = $result->fetch_array();
			if($row['password']!=hash('ripemd160', $_POST['password'])){
			  $processed = 0;
			}else{
			  
			  //echo "<p>Welcome back ".$row['user_name']."<\p>";
			  $_SESSION['user_name'] = $row['user_name'];
			  $_SESSION['user_id'] = $row['user_id'];
			  			  
			  $processed = 1;

			  $query = "SELECT image
				  FROM user
				  LEFT JOIN userpictures ON  user.picture_id = userpictures.picture_id
				  WHERE user.user_id = ".$_SESSION['user_id'] ." LIMIT 1;";
			   $result = mysqli_query($con,$query);
			   if($result->num_rows == 0){}
			   else{
			   	$row = $result->fetch_array();
			   	$_SESSION['profile_pic'] = isset($row['image']) ? "<a id='profile_link' href='profile.php'><img src='data:image/jpg;base64,". base64_encode($row['image']) ."'/></a>" 
														: "<a id='profile_link' href='profile.php'><img src='images/icons/default_avatar.jpg'/></a>";
			   }

			}
		  }
		}
	  }
	  if(!isSet($processed) or !$processed){
		if(isSet($processed) and !$processed){
		
			$errorMessage = "<p><font color='red'>No match found. Care to try again?</font></p>";
			
			header('Content-Type: application/json');

			echo json_encode(array('Error'=>$errorMessage));
		}
	  }
        
?>