<?php session_start(); 

include ('config.php');

$con = mysqli_connect($host,$username,$password,$db_name1); 
$con2 = mysqli_connect($host,$username,$password,$db_name2);

	/* Queries to Merchandise */
	
	if (mysqli_connect_errno($con2)){
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}else{
		/* Get User id */	
		$query = "SELECT user_id
					FROM garagersale
					INNER JOIN customer.accountinfo ON garagersale.garagerseller_id = customer.accountinfo.user_id
					WHERE garagersale_id =".intval($_GET['gsale_id']);

		mysqli_multi_query($con2, $query);
		do {
			$result = mysqli_store_result($con2);
			if(!$result){				
			}
			else{
				$row = $result->fetch_array();
				$id = $row['user_id'];
			}
			mysqli_free_result($result);
		}while(mysqli_next_result($con2));	
	}

	/* Queries to Customer */
	
	if (mysqli_connect_errno($con)){
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}else{
		/* Get User values*/
		$user = array();
		$query = "SELECT user_id, user_name, image, city, state, description
				  FROM user
				  NATURAL JOIN accountinfo
				  NATURAL LEFT JOIN userpictures
				  LEFT JOIN address ON  user.main_address_id = address.address_id
				  WHERE user_id = ". intval($id) . "
				  LIMIT 1;";
		$result = mysqli_query($con, $query);
		if($result==null) ;
		else if($result->num_rows == null);
		else{
			$row = $result->fetch_array();
			$user = $user + $row;
			mysqli_free_result($result);	
		}		
		
		$_PAGE['user_name'] = (isset($user['user_name']))? $user['user_name']: "user name unspecified";
		$_PAGE['profile_pic'] = (isset($user['image']))? "<a id='profile_link' href='profile.php?user=".$user['user_id']."'><img src='data:image/jpg;base64,". base64_encode($row['image']) ."'/></a>" 
 						      : "<a id='profile_link' href='profile.php?user=".$user['user_id']."'><img src='images/icons/default_avatar.jpg'/></a>";
 		$_PAGE['user_location'] = (isset($user['city']) && isset($user['state'])) ? $user['city'] . ", " . $user['state'] : "Location unavailable";
		$_PAGE['description'] = (isset($user['description']))? $user['description']."<br/><br/>" : "No user description...<br/><br/>";

		/* Get User info counts */		
		$query  = "SELECT COUNT(follower_user_id) as followers FROM following WHERE following_user_id = ". intval($id) . " LIMIT 1;"; // Followers
		$query .= "SELECT COUNT(following_user_id) as following FROM following WHERE follower_user_id = ". intval($id) . " LIMIT 1;"; // Following		
		$user_info = array();
		mysqli_multi_query($con, $query);
		do {
			$result = mysqli_store_result($con);
			if(!$result){				
			}
			else{
			$row = $result->fetch_array();
			$user_info = $user_info + $row;
			}
			mysqli_free_result($result);
		}while(mysqli_next_result($con));
		
		$_PAGE['followers'] = isset($user_info['followers']) ? $user_info['followers'] : 0;
		$_PAGE['following'] = isset($user_info['following']) ? $user_info['following'] : 0;		
	}
	
	/* Queries to Merchandise */
	
	if (mysqli_connect_errno($con2)){
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}else{
		/* Get User Activity counts */	
		$query  = "SELECT (COUNT(DISTINCT item_id) + COUNT(DISTINCT garagersale_id)) as favorites FROM customer.user LEFT JOIN merchandise.favoriteitem ON  customer.user.user_id = merchandise.favoriteitem.user_id LEFT JOIN merchandise.favoritegaragersale ON  merchandise.favoriteitem.user_id = merchandise.favoritegaragersale.user_id WHERE user.user_id = ".intval($id)." LIMIT 1;"; // Favorites
		$query .= "SELECT COUNT(garagersale_id) as checkins FROM checkin WHERE user_id = ". intval($id) . " LIMIT 1;"; // Check-ins
		$query .= "SELECT COUNT(garagersale_id) as garagersales FROM garagersale WHERE garagerseller_id = ". intval($id) . " LIMIT 1;"; // Garager sales
		
		$user_activity = array();
		mysqli_multi_query($con2, $query);
		do {
			$result = mysqli_store_result($con2);
			if(!$result){				
			}
			else{
				$row = $result->fetch_array();
				$user_activity = $user_activity + $row;
			}
			mysqli_free_result($result);
		}while(mysqli_next_result($con2));

	
		$_PAGE['favorites'] = isset($user_activity['favorites']) ? $user_activity['favorites'] : 0;
		$_PAGE['checkins'] = isset($user_activity['checkins']) ? $user_activity['checkins'] : 0;		
		$_PAGE['garager_sales'] = isset($user_activity['garagersales']) ? $user_activity['garagersales'] : 0;
		
		/*  Get Garager Sale info  */
			$query = "SELECT name, description, date, garagerseller_id, customer.address.street_address, customer.address.city, customer.address.state, customer.address.zip_code, customer.address.country 
							FROM garagersale 
							LEFT JOIN customer.address ON customer.address.address_id=garagersale.address_id 
							WHERE garagersale_id=".$_GET['gsale_id'];
			$result = mysqli_query($con2,$query);
		    if($result->num_rows == 0){  
			}else{
			  $row = $result->fetch_assoc();
			}
			
			$full_date = strtotime($row['date']);
				$date = ($full_date != null)? date('l',$full_date) .", ". strtoupper(date('M',$full_date)) . " " . date('j',$full_date):"No date specified<br/>";
				$time = ($full_date != null)? date('g',$full_date ) .":". date('i',$full_date) . " " . date('a',$full_date) : "";
				
			$address = ($row['street_address'] != null)? $row['street_address'].", ". $row['city'].", ". $row['state'].", ". $row['zip_code']:"No address specified	";
			
		$g_sale = "	<div class='saleinfo_header'>
					  <div class='garager_icon'>
						<img src='images/icons/garager_icon.jpg' alt='garager_icons'>
					  </div>
					  <div class='saleinfo'>
						<h2 id='headinfo'>".$row['name']."
						</h2> 
						<p id='contentinfo'>".$date.", ".$time."<br>
        	            ".$address."<br>
						</p>
					</div>";
		  
		    if(isSet($_SESSION['user_id'])){
				if ($_SESSION['user_id'] != $row['garagerseller_id']){	
					$query = "SELECT 0 FROM favoritegaragersale WHERE user_id=".$_SESSION['user_id']." AND garagersale_id=".$_GET['gsale_id'];
					
					$g_sale .= "<div class='right'>
								  <div id='". $_GET['gsale_id']."' class='fave'>
									<p id='fave_sale'><img id='sale_fave_img' src='images/icons/favorites_icon.png'/>";
					$result = mysqli_query($con2,$query);
					if($result != null){
						if($result->num_rows == 0)
							$g_sale .= "Fave";
						else 
							$g_sale .= "Cancel Fave";
					}
					$g_sale .= "</p>
						  </div>";


					$query = "SELECT 0 FROM checkin WHERE user_id=".$_SESSION['user_id']." AND garagersale_id=".$_GET['gsale_id'];
					$result = mysqli_query($con2,$query);
					$g_sale .= "<div id='". $_GET['gsale_id']."' class='checkin'>
								  <p id='checkin_sale'><img id='checkin_img' src='images/icons/checkin_icon.png'/>";
					if($result != null){
						if($result->num_rows == 0)
							$g_sale .= "Check-in";
						else 
							$g_sale .= "Cancel Check-in";
					}
					$g_sale .= "</p>
							  </div>
							 </div>";
				}
			}
			
			$g_sale .= "</div>
		
		<div class='sale_des'>
        	<p id='des'>".$row['description']."</p>";
			
			$query = "SELECT DISTINCT tag_name 
							FROM garagersale 
							INNER JOIN garagersaletags ON garagersaletags.garagersale_id=garagersale.garagersale_id 
							INNER JOIN tags ON garagersaletags.tag_id=tags.tag_id 
							WHERE garagersale.garagersale_id=".$_GET['gsale_id'];
		    $result = mysqli_query($con2,$query);
			$g_sale .= "<p id='Tags'>Tags: <span style='color: #ff6012'>";
		    if($result->num_rows == 0){
			  
			}else{
			  while($row = $result->fetch_assoc()){
				$g_sale .= $row['tag_name'].", ";
			  }
			}
			$g_sale .= "</span></p>
        </div>";
			
	

	/*Follow or Unfollow*/

	if($id != $_SESSION['user_id']){

		$query = "SELECT 0 FROM following WHERE follower_user_id=".$_SESSION['user_id']." AND following_user_id=".$id."";
		$result = mysqli_query($con,$query);
			if($result != null){
				if($result->num_rows == 0)
					$_PAGE['follow'] = "<p>Follow</p>";
				else 
					$_PAGE['follow'] = "<p>Unfollow</p>";
			}
	}
	else{
	  $_PAGE['edit'] = "<p>Edit</p>";
	}
	
	$_PAGE['sidebar1'] = "<div class='sidebar1-top'>
						 ".(isSet($_PAGE['follow'])?"<div class='follow'>". $_PAGE['follow'] . "</div>":"<div class='gsale_edit'>". $_PAGE['edit'] . "</div>")."
							<p class='userpic'>". $_PAGE['profile_pic']. 
							"<img id='star' src='images/icons/star_icon.png' alt='hasstar'>
						   	</p>	

							<p class='username'>". $_PAGE['user_name']. " </p>
							<p class='userlocation'>" . $_PAGE['user_location']. " </p>
							
							<p>" . $_PAGE['description']. "</p>
							<ul class='userinfo'>
								<li id='Faves'><img src='images/icons/favorites_icon.png' alt='faves'><a><p class='list_count'>" . $_PAGE['favorites']. "</p> <p class='list_title'> Faves</p></a></li>
								<li id='Followers'><img src='images/icons/followers_icon.png' alt='followers'><a><p class='list_count'>" . $_PAGE['followers']. "</p> <p class='list_title'> Followers</p></a></li>
								<li id='Following'><img src='images/icons/following_icon.png' alt='following'><a><p class='list_count'>" . $_PAGE['following']. "</p> <p class='list_title'> Following</p></a></li>
								<li id='Check ins'><img src='images/icons/checkin_icon.png' alt='checkins'><a><p class='list_count'>" . $_PAGE['checkins']. "</p> <p class='list_title'> Check-ins</p></a></li>
								<li id='Garager sales'><img src='images/icons/garager_sales_icon.png' alt='garagersales'><a><p class='list_count'>" . $_PAGE['garager_sales']. "</p> <p class='list_title'> Garager sales</p></a></li>
							</ul>

							<p class='userinfo'><img src='images/icons/sendmsg.jpg' alt='sendmsg'><a> Send a message</a></p>
						  </div>";

	$_PAGE['sidebar3'] ="<h3>Nearby Garager Sales</h3>"."<div id='sidebar2'>
			<img src='images/icons/garagerads.jpg' alt='garagerads'> 
			<p class='ads-name'>
				Garager Sale<br>
				San Francisco, CA
			</p>
			<p class='ads-info'>
				3456 Valencia St,
				Sunday, October 6 <br>
				10-4pm
			</p>
            <p class='ads-content'>
            	this is an ad which supposed to have some content bla bla
            </p>
		</div>	
		
		<div id='sidebar2'>
			<img src='images/icons/garagerads.jpg' alt='garagerads'> 
			<p class='ads-name'>
				Garager Sale<br>
				San Francisco, CA
			</p>
			<p class='ads-info'>
				3456 Valencia St,
				Sunday, October 6 <br>
				10-4pm
			</p>
            <p class='ads-content'>
            	this is an ad which supposed to have some content bla bla
            </p>
		</div>	
";	

	$_PAGE['banner'] = $g_sale; // Banner when not logged in

	/* May not be applicable for this page. Appears for Garager Sale page */ 
	$_PAGE['sidebar3'] ="";

  	$_PAGE['sort'] = $filter."<div class='sort_nav'><p>SORT:</p>
  						
  						  <div class='sort_option' id='sort_type'><a href='#' class=sort_label>Type</a>
  						   <ul>
							<li class='sort_by_type'><a href='#' id='sort_online'>ITEMS FOR SALE ONLINE</a></li>
							<li class='sort_by_type'><a href='#' id='sort_offline'>ITEMS FOR SALE OFFLINE</a></li>
							<li class='sort_by_type'><a href='#' id='sort_all'>ALL</a></li>
						   </ul>
						   </div>
						   <div class='sort_option' id='sort_relevance'><a href='#' class=sort_label>Relevance</a>
  						   <ul>
  							<li class='sort_by_relevance'><a href='#' id='sort_price_low'>PRICE - LOW TO HIGH</a></li>
							<li class='sort_by_relevance'><a href='#' id='sort_price_high'>PRICE - HIGH TO LOW</a></li>
							<li class='sort_by_relevance'><a href='#' id='sort_popular'>MOST POPULAR</a></li>
							<li class='sort_by_relevance'><a href='#' id='sort_newest'>NEWEST</a></li>
						   </ul>
						  </div>
					   </div>";

	$_PAGE['title'] ="<h2>Available Items</h2>";	
	
	/* Get Page Items */
		$PAGE['items'] = "";	
		$items_array = array();

		$query  = "SELECT item.item_id, item.main_picture_id, item.starting_date, item.sale_type, item.name as name, image, item.price, COUNT(favoriteitem.user_id) as faves
				   FROM item
				   NATURAL JOIN garagersalelistings
				   LEFT JOIN merchandisepictures ON item.main_picture_id = merchandisepictures.picture_id
				   NATURAL LEFT JOIN favoriteitem
				   WHERE (hasSold=0 OR hasSold=NULL)
				   AND (TIMEDIFF(item.end_date, NOW()) >= 0 OR ISNULL(item.end_date))
				   AND garagersale_id = ".intval($_GET['gsale_id'])."
				   GROUP BY item.item_id, item.main_picture_id, item.starting_date, item.sale_type, item.name, image, item.price
				   ORDER BY item.starting_date DESC";

		
		$result = mysqli_query($con2, $query);
		if($result == null or $result->num_rows == 0){}
		else{
			while($row = $result->fetch_array()){	
				$item = "<a href='itemLightBox' class='lightbox_trigger ". $row['sale_type']. "' price='" . $row['price'] . "' date='" . $row['starting_date'] . "' faves='" . $row['faves'] . "'><img id= '" . $row['item_id'] . "' class='conright-expics' src='data:image/jpg;base64,". base64_encode($row['image']) ."' title= '". $row['name'] ."' /></a>";
				array_push($items_array, array(value => $item, date =>  $row['starting_date']));
			}
		}
	}
	
		

		function cmp($a, $b){
					if ($a['date'] == $b['date']) {return 0;}
					if ($b['date'] == null) {return -1;}
					return ($a['date'] > $b['date']) ? -1 : 1;}
			usort($items_array, "cmp");

			foreach ($items_array as $an_item)
				$_PAGE['items'] .= $an_item['value'];

		mysqli_free_result($result);
		mysqli_free_result($result2);
	/* End Page Items */
	
include('template.php');


?>