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
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}else{
		  
		  	if (isset($_POST['item_id'])){
		  		$item_id = $_POST['item_id'];
		  
		  		$query = "SELECT 0 FROM favoriteitem WHERE user_id=".$_SESSION['user_id']." AND item_id=".$item_id;
		  		$result = mysqli_query($con,$query);
		  		if($result){
					if($result->num_rows == 0){
			  			$query = "INSERT INTO favoriteitem(user_id, item_id) VALUES (".$_SESSION['user_id'].",".$item_id.")";
			  			if(mysqli_query($con,$query))
							header("HTTP/1.0 200 OK");
			  			else
							header("HTTP/1.0 404 Page not found");
					}else{
			 			$query = "DELETE FROM favoriteitem WHERE user_id=".$_SESSION['user_id']." AND item_id=".$item_id;
			  			if(mysqli_query($con,$query))
							header("HTTP/1.0 200 OK");
			  			else
							header("HTTP/1.0 404 Page not found");
					}
		  		}else{
					header("HTTP/1.0 404 Page not found");
		  		}
		  	}

		  	if (isset($_POST['gsale_id'])){
		  		$gsale_id = $_POST['gsale_id'];
		  
		  		$query = "SELECT 0 FROM favoritegaragersale WHERE user_id=".$_SESSION['user_id']." AND garagersale_id=".$gsale_id;
		  		$result = mysqli_query($con,$query);
		  		if($result){
					if($result->num_rows == 0){
			  			$query = "INSERT INTO favoritegaragersale(user_id, garagersale_id) VALUES (".$_SESSION['user_id'].",".$gsale_id.")";
			  			if(mysqli_query($con,$query))
							header("HTTP/1.0 200 OK");
			  			else
							header("HTTP/1.0 404 Page not found");
					}else{
			 			$query = "DELETE FROM favoritegaragersale WHERE user_id=".$_SESSION['user_id']." AND garagersale_id=".$gsale_id;
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
	  }
	?>