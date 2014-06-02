<?php session_start();
include ('config.php');

if (isset ($_SESSION['user_id']) or isset($_GET['user_id']) ){	
	$id = $_SESSION['user_id'];
	if(isset($_GET['user_id']) and !empty($_GET['user_id']))
		$id = intval($_GET['user_id']);

	if(isset ($_GET['link'])){
		$link = strip_tags($_GET['link']);
		$con = mysqli_connect($host,$username,$password,$db_name1);
		$con2 = mysqli_connect($host,$username,$password,$db_name2);
		$result2 = null;
		
		if (mysqli_connect_errno($con)){
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		else{
			$query = "";
			if ($link == 'Followers'){
				$query  = "SELECT follower_user_id as id, accountinfo.user_name as name, image from following
						   INNER JOIN user ON follower_user_id = user.user_id 
						   INNER JOIN accountinfo ON accountinfo.user_id = user.user_id
						   LEFT OUTER JOIN userpictures ON user.picture_id = userpictures.picture_id 
						   WHERE following_user_id = ". intval($id). ";";
						  $result = mysqli_query($con, $query);	
			}
			else if ($link == 'Following'){
				$query  = "SELECT following_user_id as id, accountinfo.user_name as name, image from following
						   INNER JOIN user ON following_user_id = user.user_id 
						   INNER JOIN accountinfo ON accountinfo.user_id = user.user_id
						   LEFT OUTER JOIN userpictures ON user.picture_id = userpictures.picture_id 
						   WHERE follower_user_id = ". intval($id). ";";	  
						  $result = mysqli_query($con, $query);
			}
						  
			else if ($link == 'Faves'){
				$query  = "SELECT A.item_id as id, item.name as name, item.starting_date, item.sale_type, image, item.price, COUNT(B.user_id) as faves from favoriteitem A, favoriteitem B, item, merchandisepictures ";
				$query .= "where A.user_id = ". intval($id) . " and A.item_id = item.item_id and A.item_id = B.item_id ";
				$query .= "and item.main_picture_id = merchandisepictures.picture_id ";	  
				$query .= "GROUP BY A.item_id, item.name, item.starting_date, item.sale_type, image, item.price;";
						  $result = mysqli_query($con2, $query);

				$query  = "SELECT garagersale.garagersale_id, garagersale.name, user_name, address.street_address, address.city, address.state, address.zip_code, date, MIN(itempictures.picture_id), image
						   FROM garagersale
						   INNER JOIN favoritegaragersale ON favoritegaragersale.garagersale_id = garagersale.garagersale_id
						   LEFT JOIN customer.address ON garagersale.address_id = customer.address.address_id
						   LEFT JOIN customer.accountinfo ON garagersale.garagerseller_id = accountinfo.user_id
						   LEFT JOIN garagersalelistings ON garagersalelistings.garagersale_id = garagersale.garagersale_id
						   LEFT JOIN itempictures ON garagersalelistings.item_id = itempictures.item_id
						   LEFT JOIN merchandisepictures ON itempictures.picture_id = merchandisepictures.picture_id
						   WHERE favoritegaragersale.user_id = ". intval($id) . " GROUP BY garagersale.garagersale_id, name, address.street_address, address.city, address.state, address.zip_code, date
						   ORDER BY date DESC;";  
						  $result2 = mysqli_query($con2, $query);
			}

			else if ($link == 'Buys'){
				$query  = "SELECT transaction.item_id as id, item.name as name, image FROM transaction, item, merchandisepictures "; 
				$query .= "where transaction.buyer_id = ". intval($id) . " and transaction.item_id = item.item_id ";
				$query .= "and item.main_picture_id = merchandisepictures.picture_id;";   
						  $result = mysqli_query($con2, $query);
			}
			
			else if ($link == 'Sells'){
				$query  = "SELECT transaction.item_id as id, item.name as name, image FROM transaction, item, merchandisepictures "; 
				$query .= "where transaction.seller_id = ". intval($id) . " and transaction.item_id = item.item_id ";
				$query .= "and item.main_picture_id = merchandisepictures.picture_id;";   
						  $result = mysqli_query($con2, $query);
			}

			else if ($link == 'Tags'){

				$query  = "SELECT tags.tag_id as id, tags.tag_name as tag_name FROM tags, customer.favoritetags  
						   WHERE customer.favoritetags.user_id = ". intval($id) . " and customer.favoritetags.tag_id = tags.tag_id";  
			               $result= mysqli_query($con2, $query);

			}

			else if ($link == 'Garager sales'){
				$query  = "SELECT garagersale.garagersale_id, garagersale.name, user_name, address.street_address, address.city, address.state, address.zip_code, date, MIN(itempictures.picture_id), image
						   FROM garagersale
						   LEFT JOIN customer.address ON garagersale.address_id = customer.address.address_id
						   LEFT JOIN customer.accountinfo ON garagersale.garagerseller_id = accountinfo.user_id
						   LEFT JOIN garagersalelistings ON garagersalelistings.garagersale_id = garagersale.garagersale_id
						   LEFT JOIN itempictures ON garagersalelistings.item_id = itempictures.item_id
						   LEFT JOIN merchandisepictures ON itempictures.picture_id = merchandisepictures.picture_id
						   WHERE garagersale.garagerseller_id = ". intval($id) . " GROUP BY garagersale.garagersale_id, name, address.street_address, address.city, address.state, address.zip_code, date
						   ORDER BY date DESC";
						   $result = mysqli_query($con2, $query);
			}

			else if ($link == 'Check ins'){
				$query  = "SELECT garagersale.garagersale_id, garagersale.name, user_name, address.street_address, address.city, address.state, address.zip_code, date, MIN(itempictures.picture_id), image 
						   FROM garagersale
						   INNER JOIN checkin ON checkin.garagersale_id = garagersale.garagersale_id
						   LEFT JOIN customer.address ON garagersale.address_id = customer.address.address_id
						   LEFT JOIN customer.accountinfo ON garagersale.garagerseller_id = accountinfo.user_id
						   LEFT JOIN garagersalelistings ON garagersalelistings.garagersale_id = garagersale.garagersale_id
						   LEFT JOIN itempictures ON garagersalelistings.item_id = itempictures.item_id
						   LEFT JOIN merchandisepictures ON itempictures.picture_id = merchandisepictures.picture_id
						   WHERE checkin.user_id = ". intval($id) . " GROUP BY garagersale.garagersale_id, name, address.street_address, address.city, address.state, address.zip_code, date
						   ORDER BY date DESC";
						  $result = mysqli_query($con2, $query);
			}
			
			else {
				$result = null;
			}
			
			if(($result == null or $result->num_rows == null) and ($result2 == null or $result2->num_rows == null)){
				if($_SESSION['user_id'] == $id)
					echo "&nbsp; &nbsp; &nbsp;You do not have any ". $link . " yet!";
				else 
					echo "&nbsp; &nbsp; &nbsp;The user does not have any ". $link . " yet!";
			}
			
			else{		  
				$response = "";

				while($row = $result->fetch_array()){

					if($link == 'Garager sales' || $link == 'Check ins'){
						$full_date = strtotime($row['date']);
						$date = ($full_date != null)? date('l',$full_date) .", ". strtoupper(date('M',$full_date)) . " " . date('j',$full_date):"No date specified<br/>";
						$time = ($full_date != null)? date('g',$full_date ) .":". date('i',$full_date) . " " . date('a',$full_date) : "";
						$address = ($row['street_address'] != null)? $row['street_address']."<br/>". $row['city'].", ". $row['state']."<br/>". $row['zip_code']:"<br/>No address specified<br/>";
						$sale_image = ($row[image] != null)? "<img id= 'gsale_pic" . $row['garagersale_id'] . "' class='garagersale_pics' title= '". $row['name'] ."' src='data:image/jpg;base64,". base64_encode($row['image'])."'/>"
															:"<img id= '" . $row['garagersale_id'] . "' class='garagersale_pics' title= '". $row['name'] ."' src='images/icons/garager_icon.jpg' ></img>";


						$response .=  "<a href='GaragerSale.php?gsale_id=". $row['garagersale_id'] ."' class='garagersale'>".$sale_image . "<p class='garagersale_text'><b>". $row['name'] ."</b><br/><b>". $row['user_name'] ."</b></p>
							  		   <p class='garagersale_full_text'><b>". $row['name'] ."</b><br/>" .$address. "<br/>". $time . "<br/>". $date ."</p></a>";
				
					
					}
					else if ($link == 'Faves')
						$response .= "<a href='itemLightBox' class='lightbox_trigger ". $row['sale_type']. "' price='" . $row['price'] . "' date='" . $row['starting_date'] . "' faves='" . $row['faves'] . "'><img id= '" . $row['id'] . "' class='conright-expics' src='data:image/jpg;base64,". base64_encode($row['image']) ."' title= '". $row['name'] ."'/></a>";
					
					else if ($link == 'Buys' || $link == 'Sells')
						$response .= "<img id= '" . $row['id'] . "' class='conright-expics' src='data:image/jpg;base64,". base64_encode($row['image']) ."' title= '". $row['name'] ."'/>";

					else if ($link == 'Tags')				
						$response .= "<span id= '" . $row['id'] . "' class='tag'><a class='user_tag' href='search.php?tag=".$row['tag_name']."'> " . strtoupper($row['tag_name']) . "</a><a href='#' class='clear_tag'>x</a></span>";
					
					else{
						$image_src = isset($row['image'])? "data:image/jpg;base64,". base64_encode($row['image']) : "images/icons/default_avatar.jpg";
						$response .= "<a href='profile.php?user=". $row['id'] ."'><img id= '" . $row['id'] . "' class='conright-expics' src='".$image_src."' title= '". $row['name'] ."'/></a>";
					}
				}

				if (isset($result2)){
					while($row = $result2->fetch_array()){
						$full_date = strtotime($row['date']);
						$date = ($full_date != null)? date('l',$full_date) .", ". strtoupper(date('M',$full_date)) . " " . date('j',$full_date):"No date specified<br/>";
						$time = ($full_date != null)? date('g',$full_date ) .":". date('i',$full_date) . " " . date('a',$full_date) : "";
						$address = ($row['street_address'] != null)? $row['street_address']."<br/>". $row['city'].", ". $row['state']."<br/>". $row['zip_code']:"<br/>No address specified<br/>";
						$sale_image = ($row[image] != null)? "<img id= 'gsale_pic" . $row['garagersale_id'] . "' class='garagersale_pics' title= '". $row['name'] ."' src='data:image/jpg;base64,". base64_encode($row['image'])."'/>"
															:"<img id= '" . $row['garagersale_id'] . "' class='garagersale_pics' title= '". $row['name'] ."' src='images/icons/garager_icon.jpg' ></img>";


						$response .=  "<a href='GaragerSale.php?gsale_id=". $row['garagersale_id'] ."' class='garagersale'>".$sale_image . "<p class='garagersale_text'><b>". $row['name'] ."</b><br/><b>". $row['user_name'] ."</b></p>
							  		   <p class='garagersale_full_text'><b>". $row['name'] ."</b><br/>" .$address. "<br/>". $time . "<br/>". $date ."</p></a>";
				
					}
				}

				echo $response;
				mysqli_free_result($result);
			}
		
			
		}
	}
	else{
		header("HTTP/1.0 404 Not Found");
	}
}

else{
	header("HTTP/1.0 404 Not Found");
}	
	
?>
