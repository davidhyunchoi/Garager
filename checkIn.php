<?php session_start();?>

	<?php
	  if(!isSet($_SESSION['user_id'])){
		echo "<p>You need to be log in to do that!</br>
		Already have an account? Then you should <a href=#>Sign In</a>.</br>
		Not a member yet? Well you should <a href=#>Sign Up</a> !</p>";
	  }else{
		
		
		
		include('config.php');					//File containing the password
		$con = mysqli_connect($host,$username,$password,$db_name2);		
		if (mysqli_connect_errno($con)){
		  header("HTTP/1.0 404 Page not found");
		}else{
		  
		$gsale_id = $_POST['gsale_id'];
		  
		  $query = "SELECT 0 FROM checkin WHERE user_id=".$_SESSION['user_id']." AND garagersale_id=".$gsale_id;
		  $result = mysqli_query($con,$query);
		  if($result){
			if($result->num_rows == 0){
			  $query = "INSERT INTO checkin(user_id, garagersale_id) VALUES (".$_SESSION['user_id'].",".$gsale_id.")";
			  if(mysqli_query($con,$query))
				header("HTTP/1.0 200 OK");
			  else
				header("HTTP/1.0 404 Page not found");
			}else{
			  $query = "DELETE FROM checkin WHERE user_id=".$_SESSION['user_id']." AND garagersale_id=".$gsale_id;
			  if(mysqli_query($con,$query))
				header("HTTP/1.0 200 OK");
			  else
				header("HTTP/1.0 404 Page not found");
			}
		  }else{
			header("HTTP/1.0 404 Page not found");
		  }
		}
	  }
	?>