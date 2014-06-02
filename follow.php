<?php session_start();?>

	<?php
	  if(!isSet($_SESSION['user_id'])){
		echo "<p>You need to be log in to do that!</br>
		Already have an account? Then you should <a href='logInLightBox' class='lightbox_trigger'>Sign In</a>.</br>
		Not a member yet? Well you should <a href='signUpLightBox' class='lightbox_trigger'>Sign Up</a> !</p>";
	  }else{		
		
		include('config.php');					//File containing the password
		$con = mysqli_connect($host,$username,$password,$db_name1);		
		if (mysqli_connect_errno($con)){
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}else{
		  $query0 = "SELECT user_id FROM accountinfo WHERE user_name = '".$_POST['user_name']."'";

		  $result = mysqli_query($con,$query0);
		  if($row=$result->fetch_assoc()){
		  	$seller_id = $row['user_id'];
			$query = "SELECT 0 FROM following WHERE follower_user_id=".$_SESSION['user_id']." AND following_user_id=".$seller_id ."";
			$result = mysqli_query($con,$query);
			if($result){
			  if($result->num_rows == 0){
				$query = "INSERT INTO following(follower_user_id, following_user_id) VALUES (".$_SESSION['user_id'].",".$seller_id.")";
				if(mysqli_query($con,$query))
				  header("HTTP/1.0 200 OK");
			  }else{
				$query = "DELETE FROM following WHERE follower_user_id=".$_SESSION['user_id']." AND following_user_id=".$seller_id;
				if(mysqli_query($con,$query))
				  header("HTTP/1.0 200 OK");
				else{
				  header("HTTP/1.0 404 Page not found");
				 }

			  }
			}else{
				  header("HTTP/1.0 404 Page not found");
			}
		  }
		}
	  }
	?>
	  
