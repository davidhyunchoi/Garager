<?php session_start(); 

	// This page is used to retrieve user's address from database after user click on the BuyNow button to check out on the shoppingcart page
	
	if(isSet($_SESSION['user_id'])){ 
	
	   include('config.php');
	   $con = mysqli_connect($host,$username,$password,$db_name1);

	   // For storing errors:
	   $errorMessage = "";

	   
	   //get user_id from session variable
	   $id = $_SESSION['user_id'];	
	   
	   if (mysqli_connect_errno($con)){
	   	    // fail to connect to database
		    echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  
		} else {
		    // connect to database success

            /* query the item picture from database */
		    $query = "SELECT *
				      FROM address
				      LEFT JOIN user ON address.address_id = user.main_address_id
				      WHERE user.user_id = ". intval($id);

			$result = mysqli_query($con, $query);
			if($result==null) $errorMessage = '<p>result is null<br>errormsg'.mysqli_error($con).'</p>';
			else if($result->num_rows == null) 
				$errorMessage = '<br><div class="billInfoTitle">
											Bill To
										</div>
										<a id="addInfoButton" href="billingInfoLightBox" class="lightbox_trigger">ADD BILLING INFO</a>
										<hr width="90%">
										<div class="billInfo">
										'.$_SESSION['user_name'].'<br>
										NO STREET ADDRESS HAS BEEN ENTERED<br>
							            NO CITY, STATE, ZIPCODE HAVE BEEN ENTERED<br>
							            NO COUNTRY HAS BEEN ENTERED<br>
							            Visa<br>
							            </div>
							            <div class="billInfoTitle">
								        Ship To
										</div>
										<a id="addInfoButton" href="shippingInfoLightBox" class="lightbox_trigger">ADD SHIPPING INFO</a>
										<hr width="90%">
										<div class="shipInfo">
										NO STREET ADDRESS HAS BEEN ENTERED
										</div>
										<div class="finishPurchase" id="">
										<form class="finishPurchase_form" action="" method="post">
										<input id="finishPurchaseButton" type="submit" value="Finish Purchase $">
						    			</form>
						    			</div>
						    			';
			else{
				while($row = $result->fetch_array()){
				
				 	$successMessage  = '<br><div class="billInfoTitle">
											Bill To
										</div>
										<a id="addInfoButton" href="billingInfoLightBox" class="lightbox_trigger">ADD BILLING INFO</a>
										<hr width="90%">
										<div class="billInfo">
										'.$_SESSION['user_name'].'<br>
										'.$row['street_address'].'<br>
							            '.$row['city'].', '.$row['state'].' '.$row['zip_code'].'<br>
							            '.$row['country'].'<br>
							            Visa<br>
							            </div>
							            <div class="billInfoTitle">
								        Ship To
										</div>
										<a id="addInfoButton" href="shippingInfoLightBox" class="lightbox_trigger">ADD SHIPPING INFO</a>
										<hr width="90%">
										<div class="shipInfo">
										'.$row['street_address'].'
										</div>
										<div class="finishPurchase" id="">
										<form class="finishPurchase_form" action="" method="post">
										<input type="hidden" name="item_id" id="" value="'.$_POST['item_id'].'">
										<input id="finishPurchaseButton" type="submit" value="Finish Purchase $">
						    			</form>
						    			</div>
						    			';

	   
	   				header('Content-Type: application/json');
	   				echo json_encode(array('Success'=>$successMessage));

				}
			}	

			if($errorMessage != ""){

				header('Content-Type: application/json');
		        echo json_encode(array('Error'=>$errorMessage));
		      
			}


		
		} 

	}





?>