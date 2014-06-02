<?php session_start();?>

	<?php
	  if(!isSet($_SESSION['user_id'])){
		
	  }else{		
	    $user_id = $_SESSION['user_id'];
	    $item_id = $_POST['item_id'];
	    include('config.php'); //File containing the password
	    $con = mysqli_connect($host,$username,$password,$db_name1);
	    $con2 = mysqli_connect($host,$username,$password,$db_name2);
	    if (mysqli_connect_errno($con)){
	      echo "Failed to connect to MySQL: " . mysqli_connect_error();
	    }
	    else{
	      $query1 = "INSERT INTO shoppingcart (user_id,item_id) VALUES (".$user_id.",".$item_id.")"; 
	     
	      $result = mysqli_query($con2,$query1);
	      if($result){
		header("HTTP/1.0 200 OK");
	      }
	      else{
		header("HTTP/1.0 404 Page not found");
	      }

	      

	    }

	  }
	
	?>
	  
