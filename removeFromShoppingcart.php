<?php session_start(); 

	
	if(isSet($_SESSION['user_id'])){ 
	
	   include('config.php');
	  $con2 = mysqli_connect($host,$username,$password,$db_name2);

	   // For storing errors:
	   $errorMessage = "";

	   
	   $id = $_SESSION['user_id'];	
	   
	   if (mysqli_connect_errno($con2)){
	   	    // fail to connect to database
		    echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  
		} else {
		    
		    $query = "DELETE FROM shoppingcart
            		  WHERE item_id = ". intval($_POST['item_id'])." AND user_id = ". intval($id);

			$result = mysqli_query($con2, $query);
			if(mysqli_affected_rows($con2) == 0) $errorMessage = '<p>result is null<br>errormsg'.mysqli_error($con2).'</p>';
			else{
						
				 	$successMessage  = 'Item remove from shoppingcart success.';
	   
	   				header('Content-Type: application/json');
	   				echo json_encode(array('Success'=>$successMessage));

				}
			}	

			if($errorMessage != ""){

				header('Content-Type: application/json');
		        echo json_encode(array('Error'=>$errorMessage));
		        //echo $errorMessage;
			}


		
		

	}





?>