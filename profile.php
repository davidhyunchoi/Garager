<?php session_start(); 

include ('config.php');

$con = mysqli_connect($host,$username,$password,$db_name1); 
$con2 = mysqli_connect($host,$username,$password,$db_name2);
$user_found = false;

if (isset($_SESSION['user_id'])) {
	$_PAGE['user_id'] = intval($_SESSION['user_id']);
	$_PAGE['user_name'] = $_SESSION['user_name'];
	if  (!isset($_GET['user'])) $user_found = true;
}

if (isset($_GET['user'])){
	$id = intval($_GET['user']);
	if (mysqli_connect_errno($con)){
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}else{
		$query = "SELECT user_name FROM accountinfo WHERE accountinfo.user_id = ". $id . "  LIMIT 1";		
		$result = mysqli_query($con, $query);
		if($result==null) ;
		else if($result->num_rows == null);
		else{
			$row = $result->fetch_array();
			if (isset($row['user_name'])) {
				$_PAGE['user_name'] = $row['user_name'];
				$_PAGE['user_id'] = $id;
				$user_found = true;
			}
		}
		mysqli_free_result($result);		
	}
}

/* Static on page */
$_PAGE['banner'] = "<img class='conright-img' src='images/banners/banner2.png' alt='banner'/>";
$_PAGE['follow'] = "";

if (isset($_PAGE['user_id'])){
	$id = $_PAGE['user_id'];
	$user = array();
		
	$query = "SELECT user_id, image, city, state, description
			  FROM user
			  LEFT JOIN userpictures ON  user.picture_id = userpictures.picture_id
		  	  LEFT JOIN address ON  user.main_address_id = address.address_id
			  WHERE user.user_id = ". $id . "
				  LIMIT 1;";
		
	$result = mysqli_query($con, $query);
	if($result==null) ;
	else if($result->num_rows == null);
	else{
		$row = $result->fetch_array();
		$user = $user + $row;
		mysqli_free_result($result);	
	}	


	$_PAGE['profile_pic'] = isset($user['image']) ? "<a href='profile.php?user=".$_PAGE['user_id']."'><img src='data:image/jpg;base64,". base64_encode($user['image']) ."'/></a>" : "<a href='profile.php?user=".$_PAGE['user_id']."'>
						<img src='images/icons/default_avatar.jpg'/></a>";
	$_PAGE['user_location'] = (isset($user['city']) && isset($user['state'])) ? $user['city'] . ", " . 
								  $user['state'] : "Location unavailable";
	$_PAGE['description'] = (isset($user['description']))? $user['description']."<br/><br/>" : "No user description...<br/><br/>";

	/*Follow or Unfollow*/

	if($id != $_SESSION['user_id']){

		$query = "SELECT 0 FROM following WHERE follower_user_id=".$_SESSION['user_id']." AND following_user_id=".$_PAGE['user_id']."";
		$result = mysqli_query($con,$query);
			if($result != null){
				if($result->num_rows == 0)
					$_PAGE['follow'] = "<p>Follow</p>";
				else 
					$_PAGE['follow'] = "<p>Unfollow</p>";
			}
	}

	/* Get User info counts */		
	$query  = "SELECT COUNT(follower_user_id) as followers FROM following WHERE following_user_id = ".	$id . " LIMIT 1;"; // Followers
	$query .= "SELECT COUNT(following_user_id) as following FROM following WHERE follower_user_id = ".  $id . " LIMIT 1;"; // Following		
	
	$user_info = array();
	mysqli_multi_query($con, $query);
	do {
		$result = mysqli_store_result($con);
		if(!$result){}
		else{
			$row = $result->fetch_array();
			$user_info = $user_info + $row;
		}
		mysqli_free_result($result);
		if(!mysqli_more_results($con)) break;
	}while(mysqli_next_result($con));	
	
	/* Queries to Merchandise */
	
	if (mysqli_connect_errno($con2)){
	 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}else{
		/* Get User Activity counts */	
		$query  = "SELECT (COUNT(DISTINCT item_id) + COUNT(DISTINCT garagersale_id)) as favorites FROM customer.user LEFT JOIN merchandise.favoriteitem ON  customer.user.user_id = merchandise.favoriteitem.user_id LEFT JOIN merchandise.favoritegaragersale ON  merchandise.favoriteitem.user_id = merchandise.favoritegaragersale.user_id 
				   WHERE user.user_id = ".intval($id)." LIMIT 1;"; // Favorites
		$query .= "SELECT COUNT(garagersale_id) as checkins FROM checkin 
				   WHERE user_id = ". $id . " LIMIT 1;"; // Check-ins
		$query .= "SELECT COUNT(garagersale_id) as garagersales FROM garagersale 
				   WHERE garagerseller_id = ". $id . " LIMIT 1;"; // Garager sales
		$query .= "SELECT COUNT(item_id) as buys FROM transaction 
				   WHERE buyer_id = ". $id . " LIMIT 1;"; // Buys
		$query .= "SELECT COUNT(item_id) as sells FROM transaction 
				   WHERE seller_id = ". $id . " LIMIT 1;"; // Sells

		$user_activity = array();
		mysqli_multi_query($con2, $query);
		do {
			$result = mysqli_store_result($con2);
			if(!$result){}
			else{
				$row = $result->fetch_array();
				$user_activity = $user_activity + $row;
			}
			mysqli_free_result($result);
			if(!mysqli_more_results($con2)) break;
		}while(mysqli_next_result($con2));
	
		/* Need to determine what is accessible and clickable */

		$_PAGE['followers'] = isset($user_info['followers']) ? $user_info['followers'] : 0;
		$_PAGE['following'] = isset($user_info['following']) ? $user_info['following'] : 0;	
		$_PAGE['favorites'] = isset($user_activity['favorites']) ? $user_activity['favorites'] : 0;
		$_PAGE['checkins'] = isset($user_activity['checkins']) ? $user_activity['checkins'] : 0;		
		$_PAGE['garager_sales'] = isset($user_activity['garagersales']) ? $user_activity['garagersales'] : 0;
		$_PAGE['buys'] = isset($user_activity['buys']) ? $user_activity['buys'] : 0;
		$_PAGE['sells'] = isset($user_activity['sells']) ? $user_activity['sells'] : 0;					

	}

	if (isset($_SESSION['user_id'])){
		/* Currently, user's buys and sells are viewable by the user */

		$_PAGE['buys'] = ($_SESSION['user_id'] == $_PAGE['user_id'])? "<a id='Buys'><b>" . $_PAGE['buys']. " Buys  </b></a>" : "<b>" . $_PAGE['buys']. " Buys  </b>";
		$_PAGE['sells'] = ($_SESSION['user_id'] == $_PAGE['user_id'])? "<a id='Sells'><b>" . $_PAGE['sells']. " Sells  </b></a>" : "<b>" . $_PAGE['sells']. " Sells  </b>";
		$_PAGE['sidebar1'] = "<div class='sidebar1-top'>
							<div class='follow'>". $_PAGE['follow'] . "</div>
							<p class='userpic'>". $_PAGE['profile_pic']. 
							"<img id='star' src='images/icons/star_icon.png' alt='hasstar'>
						   	</p>	
							<p id='". $_PAGE['user_id'] ."' class='username'>". $_PAGE['user_name']. " </p>
							<p class='userlocation'>" . $_PAGE['user_location']. " </p>
							<p class='history'> ".$_PAGE['buys']." | " .$_PAGE['sells'] . "<br></p>
							<ul class='userinfo'>
								<li id='Faves'><img src='images/icons/favorites_icon.png' alt='faves'><a><p class='list_count'>" . $_PAGE['favorites']. "</p> <p class='list_title'> Faves</p></a></li>
								<li id='Followers'><img src='images/icons/followers_icon.png' alt='followers'><a><p class='list_count'>" . $_PAGE['followers']. "</p> <p class='list_title'> Followers</p></a></li>
								<li id='Following'><img src='images/icons/following_icon.png' alt='following'><a><p class='list_count'>" . $_PAGE['following']. "</p> <p class='list_title'> Following</p></a></li>
								<li id='Check ins'><img src='images/icons/checkin_icon.png' alt='checkins'><a><p class='list_count'>" . $_PAGE['checkins']. "</p> <p class='list_title'> Check-ins</p></a></li>
								<li id='Garager sales'><img src='images/icons/garager_sales_icon.png' alt='garagersales'><a><p class='list_count'>" . $_PAGE['garager_sales']. "</p> <p class='list_title'> Garager sales</p></a></li>
							</ul>
							<p>" . $_PAGE['description']. "</p>
							<p class='userinfo'><img src='images/icons/sendmsg.jpg' alt='sendmsg'><a> Send a message</a></p>
						  </div>";

		/* Suggested Followers */
		$query  = "SELECT accountinfo.user_id, user_name, COUNT( A.follower_user_id ) as followers, image FROM accountinfo 
		   		   LEFT JOIN following A ON A.following_user_id = accountinfo.user_id
		   		   INNER JOIN user ON accountinfo.user_id = user.user_id
		   		   LEFT JOIN userpictures ON user.picture_id = userpictures.picture_id
		   		   WHERE accountinfo.user_id NOT IN (SELECT DISTINCT B.following_user_id FROM following B 
		   		   WHERE B.follower_user_id = ".intval($_SESSION['user_id'])." ) AND accountinfo.user_id <> ". intval($_SESSION['user_id'])." GROUP BY accountinfo.user_id ORDER BY COUNT( A.follower_user_id ) DESC LIMIT 3";

		$result = mysqli_query($con, $query);
		if($result->num_rows == null){
			$suggested = "There are no suggested users to follow";
		}
		else{
			$suggested = "";
			$row_count = 1;
			while($row = $result->fetch_array()){
				$image_src = isset($row['image'])? "data:image/jpg;base64,". base64_encode($row['image']) : "images/icons/default_avatar.jpg";
				$suggested .= "<div id='sug_foll_".$row_count."' class='sug_foll_div'>
				<div class='sug_foll_image'><a href='profile.php?user=" . $row['user_id'] ."''><img src ='".$image_src."'/></a>
				</div><div class='sug_foll_text'><p>" . $row['user_name'] . "</p><p>". $row['followers'] ." <img src='images/icons/followers_icon.png'/></p></div><div class='sug_foll_button'><p>Follow</p></div></div>";
				$row_count = $row_count + 1;
			}
		}
		mysqli_free_result($result);

		$_PAGE['sidebar2'] ="<h4>Who to Follow</h4>". $suggested;

	}

	else {
		$_PAGE['follow'] = "";
		$_PAGE['sidebar1'] = "<div class='sidebar1-top'>
							<div class='follow'>". $_PAGE['follow'] . "</div>
							<p class='userpic'>". $_PAGE['profile_pic']. 
							"<img id='star' src='images/icons/star_icon.png' alt='hasstar'>
						   	</p>	

							<p id='". $_PAGE['user_id'] ."' class='username'>". $_PAGE['user_name']. " </p>
							<p class='userlocation'>" . $_PAGE['user_location']. " </p>
							<p class='history'><b>" . $_PAGE['buys']. " Buys  </b> | <b>" . $_PAGE['sells']. " Sells</b><br></p>
							<ul class='userinfo'>
								<li id='Faves'><img src='images/icons/favorites_icon.png' alt='faves'><a><p class='list_count'>" . $_PAGE['favorites']. "</p> <p class='list_title'> Faves</p></a></li>
								<li id='Followers'><img src='images/icons/followers_icon.png' alt='followers'><a><p class='list_count'>" . $_PAGE['followers']. "</p> <p class='list_title'> Followers</p></a></li>
								<li id='Following'><img src='images/icons/following_icon.png' alt='following'><a><p class='list_count'>" . $_PAGE['following']. "</p> <p class='list_title'> Following</p></a></li>
								<li id='Check ins'><img src='images/icons/checkin_icon.png' alt='checkins'><a><p class='list_count'>" . $_PAGE['checkins']. "</p> <p class='list_title'> Check-ins</p></a></li>
								<li id='Garager sales'><img src='images/icons/garager_sales_icon.png' alt='garagersales'><a><p class='list_count'>" . $_PAGE['garager_sales']. "</p> <p class='list_title'> Garager sales</p></a></li>
							</ul>
							<p>" . $_PAGE['description']. "</p>
							<p class='userinfo'><img src='images/icons/sendmsg.jpg' alt='sendmsg'><a> Send a message</a></p>
						  </div>";
	}
	$_PAGE['items'] = "";
	$_PAGE['sidebar3'] ="";	
	$_PAGE['search'] = "<p>TAGS:</p>";
  	$_PAGE['sort'] = "<div class='sort_nav'><p>SORT:</p>
					  <div class='sort_option' id='sort_type'><a href='#' class=sort_label>Type</a>
					   <ul>
						<li class='sort_by_type'><a href='#' id='sort_garager_sales'>GARAGER SALES</a></li>
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


	if ($user_found == true){
		$_PAGE['title'] = ($id == ($_SESSION['user_id']))? "<h2>My Stuff</h2>": "<h2>".$_PAGE['user_name']."'s Stuff</h2>";	

		$items_array = array();	
		$item_ids = array();
		array_push($item_ids, '0');

		$query  = "SELECT item.item_id, item.main_picture_id, item.starting_date, item.sale_type, item.name as name, GROUP_CONCAT(itemtags.tag_id) as tags,  image, item.price, COUNT(favoriteitem.user_id) as faves  
			   FROM item
			   INNER JOIN merchandisepictures ON item.main_picture_id = merchandisepictures.picture_id 
			   INNER JOIN itemseller ON itemseller.item_id = item.item_id 
			   LEFT JOIN itemtags ON itemtags.item_id = item.item_id
			   LEFT JOIN tags ON tags.tag_id = itemtags.tag_id
			   LEFT JOIN favoriteitem ON favoriteitem.item_id = item.item_id				   
			   WHERE itemseller.user_id =  ". $id ."  
			   GROUP BY item.item_id, item.main_picture_id, item.starting_date, item.sale_type, item.name, image, item.price 
			   ORDER BY item.starting_date DESC";
		
		$query2 = "SELECT garagersale.garagersale_id, garagersale.name, user_name, address.street_address, 
					  address.city, address.state, address.zip_code, date, MIN(itempictures.picture_id), GROUP_CONCAT(garagersaletags.tag_id) as tags, image, COUNT(favoritegaragersale.user_id) as faves
	     		FROM garagersale
				LEFT JOIN favoritegaragersale ON favoritegaragersale.garagersale_id = garagersale.garagersale_id
				LEFT JOIN customer.address ON garagersale.address_id = customer.address.address_id
				LEFT JOIN customer.accountinfo ON garagersale.garagerseller_id = accountinfo.user_id
				LEFT JOIN garagersalelistings ON garagersalelistings.garagersale_id = garagersale.garagersale_id
				LEFT JOIN itempictures ON garagersalelistings.item_id = itempictures.item_id
				LEFT JOIN merchandisepictures ON itempictures.picture_id = merchandisepictures.picture_id
				LEFT JOIN garagersaletags ON garagersaletags.garagersale_id = garagersale.garagersale_id
			    LEFT JOIN tags ON tags.tag_id = garagersaletags.tag_id
				WHERE garagersale.garagerseller_id = ". $id ."
				GROUP BY garagersale.garagersale_id, name, address.street_address, address.city, address.state, address.zip_code, date
				ORDER BY date DESC";
			 
		$result = mysqli_query($con2, $query);
		if($result == null or $result->num_rows == 0){}
		else{
			while($row = $result->fetch_array()){
				$tags = ($row['tags'] != null) ? str_replace(',', ' ', $row['tags']) : 0;
				$item = "<a href='itemLightBox' tags='". $tags ."' class='lightbox_trigger ". $row['sale_type']. "' price='" . $row['price'] . "' date='" . $row['starting_date'] . "' faves='" . $row['faves'] . "'><img id= '" . $row['item_id'] . "' class='conright-expics' src='data:image/jpg;base64,". base64_encode($row['image']) ."' title= '". $row['name'] ."' /></a>";
				array_push($items_array, array('value' => $item, 'date' =>  $row['starting_date']));
				array_push($item_ids, $row['item_id']);
			}
		}
		
		$result2 = mysqli_query($con2, $query2);
		if($result2 == null or $result2->num_rows == 0){}
		else{
			date_default_timezone_set('America/New_York');
			while($row = $result2->fetch_array()){
				$tags = ($row['tags'] != null) ? str_replace(',', ' ', $row['tags']) : 0;
				$full_date = strtotime($row['date']);
				$date = ($full_date != null)? date('l',$full_date) .", ". strtoupper(date('M',$full_date)) . " " . date('j',$full_date):"No date specified<br/>";
				$time = ($full_date != null)? date('g',$full_date ) .":". date('i',$full_date) . " " . date('a',$full_date) : "";
				$address = ($row['street_address'] != null)? $row['street_address']."<br/>". $row['city'].", ". $row['state']."<br/>". $row['zip_code']:"<br/>No address specified<br/>";
				$sale_image = ($row['image'] != null)? "<img id= 'gsale_pic" . $row['garagersale_id'] . "' class='garagersale_pics' title= '". $row['name'] ."' src='data:image/jpg;base64,". base64_encode($row['image'])."'/>":"<img id= '" . $row['garagersale_id'] . "'class='garagersale_pics' title= '". $row['name'] ."' src='images/icons/garager_icon.jpg' ></img>";

				$item = "<a href='GaragerSale.php?gsale_id=". $row['garagersale_id'] ."' tags='". $tags ."' class='garagersale'  date='" . $row['date'] . "' faves='" . $row['faves'] . "'>".$sale_image . "<p class='garagersale_text'><b>". $row['name'] ."</b><br/><b>". $row['user_name'] ."</b></p>
							  <p class='garagersale_full_text'><b>". $row['name'] ."</b><br/>" .$address. "<br/>". $time . "<br/>". $date ."</p></a>";
				
				array_push($items_array, array('value' => $item, 'date' =>  $row['date']));
			}

			
		}

		function cmp($a, $b){
				if ($a['date'] == $b['date']) {return 0;}
				if ($b['date'] == null) {return -1;}
				return ($a['date'] > $b['date']) ? -1 : 1;
			}
			usort($items_array, "cmp");

			foreach ($items_array as $an_item)
				$_PAGE['items'] .= $an_item['value'];	

		$item_ids = join(',',$item_ids);

		$query_tags = "SELECT DISTINCT tags.tag_id, tag_name FROM tags, itemtags 
					   WHERE itemtags.tag_id = tags.tag_id AND itemtags.item_id IN (". $item_ids .")";
		$result_tags = mysqli_query($con2, $query_tags);
		if($result_tags == null or $result_tags->num_rows == 0){}
		else{
			while($row = $result_tags->fetch_array()){
				$_PAGE['search'] .= "<span class='stored_search_tag' id= 'tag_". $row['tag_id'] ."'>
							<a class='searched_user_tag' href='search.php?tag=".$row['tag_name']. "'>". urldecode(strtoupper($row['tag_name']))."</a><a href='#' class='clear_user_tag'>x</a></span> ";

			}

		}
		$_PAGE['search'] .= "<br/>";

		/* End Page Items */
		mysqli_free_result($result);
		mysqli_free_result($result2);
		mysqli_free_result($result_tags);

		/** My Garager Sale Listings **/
		if($id == ($_SESSION['user_id'])){
			$_PAGE['sidebar3'] = "<h4>My Garager Listings</h4>";
			$sales = "";
			$query = "SELECT garagersale_id, name, date, customer.address.street_address, customer.address.city, customer.address.state, customer.address.zip_code
					 FROM garagersale
					 LEFT OUTER JOIN customer.address ON garagersale.address_id = customer.address.address_id
					 WHERE garagerseller_id = ". $id . " ORDER BY date DESC LIMIT 5";

			$result = mysqli_query($con2, $query);
			if($result == null or $result->num_rows == 0){
				$sale = "<p>No Garager Sales Found</p>";
			}
			else{
				while($row = $result->fetch_array()){
					date_default_timezone_set('America/New_York');
					$city_state = ($row['city'] != null)? $row['city'].", ". $row['state'] . " " . $row['zip_code'] . "<br/>" : "";
					$street = ($row['street_address'] != null)? $row['street_address'] : "No address specified<br/>";
					$full_date = strtotime($row['date']);
					$date = ($full_date != null)? date('l',$full_date) .", ". date('F',$full_date) . " " . date('j',$full_date):"No date specified<br/>";
					$time = ($full_date != null)? date('g',$full_date ) .":". date('i',$full_date) . " " . date('a',$full_date) : "";
					
					$sales .= "<div class='garager_sale_listing'>
								<p class='ads-name'><a href='GaragerSale.php?gsale_id=".$row['garagersale_id']."'><img src='images/icons/garagersales_grey.png' alt='garagerads'></a>". strtoupper($row['name']) ."<br/>" . $city_state ."</p>
								<p class='ads-info'>". $street . "<br/>". $date . "<br/>". $time . "</p>
							</div>";
				}

			}
			$_PAGE['sidebar3'] .= $sales;
		}

	}

	else{
		$_PAGE['title'] = "<h2>User not found</h2>";
	}

}

else{
	/*$_PAGE['title'] = "<h2>Oops!</h2><p>You need to be logged in to view user pages!</br>
		Already have an account? Then, you should <a href='logInLightBox' class='lightbox_trigger'>Log In</a>.</br>
		Not a member yet? Well, you should <a href='signUpLightBox' class='lightbox_trigger'>Sign Up</a> !</p>" ;
	*/
	header('Location: /Garager/new_stuff.php');
}

include('template.php');
?>