<?php session_start(); 

	// This page is used to update information in database after user click on the FinishPurchase button to check out on the shoppingcart page
	
	if(isSet($_SESSION['user_id'])){ //
	
	   include('config.php');
	   $con = mysqli_connect($host,$username,$password,$db_name1);
	   $con2 = mysqli_connect($host,$username,$password,$db_name2);

	   // For storing errors:
	   $errorMessage = "";


	   // get user_id from session variable
	   $id = $_SESSION['user_id'];	
	   
	   if (mysqli_connect_errno($con)||mysqli_connect_errno($con2)){
	   	    // fail to connect to database
		    echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  
		} else {
		    // connect to database success

			
            /* update the item to hasSold in database */
		    $query = "UPDATE item
				      SET hasSold=1
				      WHERE item_id = ". intval($_POST['item_id']);

			$result = mysqli_query($con2, $query);
			if(mysqli_affected_rows($con2) == 0) $errorMessage = '<p>result is null<br>errormsg'.mysqli_error($con).'</p>';
			else{
		
			}	
			
			 //query the seller name from database 
        
			$query = "SELECT customer.accountinfo.user_id, customer.accountinfo.user_name
					  From customer.accountinfo
					  Inner join itemseller on customer.accountinfo.user_id = itemseller.user_id
				  	  WHERE itemseller.item_id = ". intval($_POST['item_id']);
		

        	$result = mysqli_query($con2, $query);

			if($result==null) $errorMessage = '<p>result is null<br>errormsg'.mysqli_error($con2).'</p>';
			else if($result->num_rows == null) $errorMessage = '<p>result row num is 0</p>';
			else
			{
				$row = $result->fetch_array();
				
				$seller_name = $row['user_name'];
			    $seller_id = $row['user_id'];
				
        	}

        	// get the item name from the database
        	$query = "SELECT name
					  From item
				  	  WHERE item_id = ". intval($_POST['item_id']);
		

        	$result = mysqli_query($con2, $query);

			if($result==null) $errorMessage = '<p>result is null<br>errormsg'.mysqli_error($con2).'</p>';
			else if($result->num_rows == null) $errorMessage = '<p>result row num is 0</p>';
			else
			{
				$row = $result->fetch_array();
				
				$item_name = $row['name'];
				
        	}

        	// get the item price from database
        	/* query the item price from database */
			$query = "SELECT price
				  	  FROM item
				      WHERE item_id = ". intval($_POST['item_id']);
			
			$result = mysqli_query($con2, $query);

			if($result==null) $errorMessage = '<p>result is null<br>errormsg'.mysqli_error($con2).'</p>';
			else if($result->num_rows == null) $errorMessage = '<p>result row num is 0</p>';
			else
			{
				$row = $result->fetch_array();
				
				$item_price = $row['price'];
				
        	}

			
			
			// insert order information to the transaction table in database
			$query = "INSERT INTO transaction(seller_id, buyer_id, item_id, date) VALUES ('".$seller_id."','".$_SESSION['user_id']."','".$_POST['item_id']."',NOW())";

			$result = mysqli_query($con2, $query);
			$order_id=$con2->insert_id;
			if(mysqli_affected_rows($con2) == 0) $errorMessage = '<p>result is null<br>errormsg'.mysqli_error($con).'</p>';
			else{
		
			}


			
            // delete the record in the shoppingcart
			$query = "DELETE FROM shoppingcart
            		  WHERE item_id = ". intval($_POST['item_id'])." AND user_id = ". intval($id);

			$result = mysqli_query($con2, $query);
			
			if(mysqli_affected_rows($con2) == 0) $errorMessage = '<p>result is null<br>errormsg'.mysqli_error($con).'</p>';
			else{
		
			}

			//get the current time
			$date = date("Y/m/d");


			if($errorMessage != ""){

					header('Content-Type: application/json');
		        	echo json_encode(array('Error'=>$errorMessage));
		        	//echo $errorMessage;
			}
			else{


				 	$successMessage  = '<div class="completed_checkout">
				 						<div class="completed_checkout_title">
				 						<p>Thanks for purchasing!</p>
				 						</div>
				 						<div class="completed_checkout_info">
				 						Order ID: '.$order_id.'<br>
				 						Date: '.$date.'<br>
				 						Item Name: '.$item_name.' <br>
				 						Order From: '.$seller_name.'<br>
				 						Picked Up from Seller<br>
				 						Price: $'.$item_price.' 
				 						</div>
				 						<div class="completed_checkout_payment">
				 						Paid with:<br>
				 						$ '.$item_price.'- Credit Card 
				 						</div>
				 						</div>
						    			';

	   
	   				header('Content-Type: application/json');
	   				echo json_encode(array('Success'=>$successMessage));

			}


		
		} 

	}





?>