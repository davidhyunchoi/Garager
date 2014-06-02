<?php session_start(); 
include ('config.php');

$con = mysqli_connect($host,$username,$password,$db_name1); 
$con2 = mysqli_connect($host,$username,$password,$db_name2);
$options = array();
$cat = array();
$zip = "";


	/*           parsing the parameters                */


$tagset = explode('&',$_SERVER["QUERY_STRING"]);
		foreach($tagset as $tag => $name)
		{
			list($ignore, $value) = explode("=", $name);
			if($ignore == 'tag')
			{
				$iden[$tag] = $value;
			}
			else if ($ignore == 'option')
			{
				$options[$tag] = $value;
			}
			else if ($ignore == 'category')
			{
				$category = $value;
				$_SESSION['category'] = $category;
				$_SESSION['subcategory'] = "";
			}
			else if ($ignore == 'subcategory')
			{
				$subcategory = $value;
				$_SESSION['subcategory'] = $subcategory;
			}
		}	

		
	/*                 functions                       */
	
	function userPerfectRequest()
	{
		return "SELECT DISTINCT user_id, user_name, image, caption
				FROM user
				NATURAL JOIN userpictures
				NATURAL JOIN accountinfo
				NATURAL JOIN addresslistings
				NATURAL JOIN address
				WHERE ";
	}
		
	function itemPerfectSeedQuery($param = array())
	{
	  $res = "SELECT DISTINCT item_id, item.name as item_name, main_picture_id, price, starting_date, sale_type, COUNT(favoriteitem.user_id) as faves, image, caption
				FROM item
				INNER JOIN merchandisepictures ON item.main_picture_id=merchandisepictures.picture_id
				";
	  if(isSet($param['user']))
	  {
		$res .= "NATURAL JOIN itemseller
				 NATURAL JOIN customer.accountinfo
				 ";
	  }
	  if(isSet($param['zip']))
	  {
		$res .= "NATURAL JOIN offlinesale
				 NATURAL JOIN customer.address
				";
	  }
	  
	  $res .= "NATURAL LEFT JOIN favoriteitem
				WHERE hasSold = 0 AND (TIMEDIFF(item.end_date, NOW()) >= 0 OR ISNULL(item.end_date)) ";
		  
	  if(isSet($param['user']))
	  {
		$res .= " AND user_name='".$param['user']."'";
	  }
	  if(isSet($param['zip']))
	  {
		$res .= " AND zip_code='".$param['zip']."'";
	  }
	  $res .= " GROUP BY item.item_id, item.main_picture_id, item.starting_date, item.sale_type, item_name, image, item.price
				   			ORDER BY starting_date DESC ";
	  return $res;
	}
			
	function itemPerfectNameWrappQuery($param = array())
	{
	  $res = "SELECT DISTINCT item_id, item_name, sale_type, main_picture_id, price, starting_date, faves, image, caption
				FROM (".itemPerfectSeedQuery($param).") AS free WHERE item_name ='".$param['name']."' ";
	  return $res;
	}
			
	function itemPerfectCatWrappQuery($param = array())
	{
	  $res = "SELECT DISTINCT item_id, item_name, main_picture_id, price, sale_type, starting_date, faves, image, caption
				FROM (";
	  if(isSet($param['name']))
	  {
		$res .= itemPerfectNameWrappQuery($param);
	  }
	  else
		$res .= itemPerfectSeedQuery($param);
	  $res .= ") AS iname
			  NATURAL JOIN itemcategory
			  NATURAL JOIN category
			  WHERE category_name = '".$param['cat']."' OR subcategory_name = '".$param['cat']."'";
			  
	return $res;
	}
			
	function itemPerfectTagWrappQuery($param = array())
	{
	  $res = "SELECT DISTINCT item_id, item_name, main_picture_id, price, sale_type, starting_date, faves, image, caption
				FROM (";
	  if(isSet($param['cat']))
		$res .= itemPerfectCatWrappQuery($param);
	  else
		if(isSet($param['name']))
		  $res .= itemPerfectNameWrappQuery($param);
		else
		  $res .= itemPerfectSeedQuery($param);
	  $res .= ") AS cat
			  NATURAL JOIN itemtags
			  NATURAL JOIN tags
			 WHERE tag_name='".$param['tag']."'";
	  return $res;
	}

	function gsalePerfectSeedQuery($param = array())
	{
	  $res = "SELECT DISTINCT garagersale_id, garagersale.name as gsale_name, user_name, street_address, city, state, zip_code, date, MIN(itempictures.picture_id), image, caption, COUNT(favoritegaragersale.user_id) as faves
				FROM garagersale
				INNER JOIN customer.accountinfo ON garagersale.garagerseller_id = customer.accountinfo.user_id
				NATURAL LEFT JOIN customer.address
				NATURAL LEFT JOIN garagersalelistings
				NATURAL LEFT JOIN item
				NATURAL LEFT JOIN itempictures
				NATURAL LEFT JOIN merchandisepictures
				NATURAL LEFT JOIN favoritegaragersale
			  WHERE (TIMEDIFF(garagersale.date,NOW()) >= 0 OR ISNULL(garagersale.date)) ";
		  
	  if(isSet($param['user']))
	  {
		$res .= " AND user_name='".$param['user']."' ";
	  }
	  if(isSet($param['zip']))
	  {
		$res .= " AND zip_code='".$param['zip']."' ";
	  } 
	  $res .= "GROUP BY garagersale_id, gsale_name, user_name, street_address, city, state, zip_code, date
			  ORDER BY garagersale.date DESC";
	  
	  return $res;
	}
			
	function gsalePerfectNameWrappQuery($param = array())
	{
	  $res = "SELECT DISTINCT garagersale_id, gsale_name, user_name, street_address, city, state, zip_code, date, image, caption, faves
				FROM (".gsalePerfectSeedQuery($param).") AS free WHERE gsale_name ='".$param['name']."' ";
	  return $res;
	}
			
	function gsalePerfectCatWrappQuery($param = array())
	{
	  $res = "SELECT DISTINCT garagersale_id, gsale_name, user_name, street_address, city, state, zip_code, date, image, caption, faves
				FROM (";
	  if(isSet($param['name']))
	  {
		$res .= gsalePerfectNameWrappQuery($param);
	  }
	  else
		$res .= gsalePerfectSeedQuery($param);
	  $res .= ") AS gname
			  NATURAL JOIN garagersalecategory
			  NATURAL JOIN category
			  WHERE category_name = '".$param['cat']."' OR subcategory_name = '".$param['cat']."'";
			  
	return $res;
	}
			
	function gsalePerfectTagWrappQuery($param = array())
	{
	  $res = "SELECT DISTINCT garagersale_id, gsale_name, user_name, street_address, city, state, zip_code, date, image, caption, faves
				FROM (";
	  if(isSet($param['cat']))
		$res .= gsalePerfectCatWrappQuery($param);
	  else
		if(isSet($param['name']))
		  $res .= gsalePerfectNameWrappQuery($param);
		else
		  $res .= gsalePerfectSeedQuery($param);
	  $res .= ") AS cat
			  NATURAL JOIN garagersaletags
			  NATURAL JOIN tags
			 WHERE tag_name='".$param['tag']."'";
	  return $res;
	}

	
		

if (isset ($_SESSION['user_id']))

{
	
	$id = $_SESSION['user_id'];	
	/* Queries to Customer */
	
	if (mysqli_connect_errno($con)){
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}else{
		/* Get User values*/
		$user = array();
		$query = "SELECT user_id, city, state, description
				  FROM user
				  LEFT JOIN address ON  user.main_address_id = address.address_id
				  WHERE user.user_id = ". intval($id) . "
				  LIMIT 1;";
		
		$result = mysqli_query($con, $query);
		if($result==null) ;
		else if($result->num_rows == null);
		else{
			$row = $result->fetch_array();
			$user = $user + $row;
			mysqli_free_result($result);	
		}		
		
		$_PAGE['user_name'] = $_SESSION['user_name'];
		$_PAGE['profile_pic'] = $_SESSION['profile_pic'];
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
			if(!mysqli_more_results($con)) break;
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
		$query .= "SELECT COUNT(item_id) as buys FROM transaction WHERE buyer_id = ". intval($id) . " LIMIT 1;"; // Buys
		$query .= "SELECT COUNT(item_id) as sells FROM transaction WHERE seller_id = ". intval($id) . " LIMIT 1;"; // Sells

		

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
			if(!mysqli_more_results($con2)) break;
		}while(mysqli_next_result($con2));

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

		/** My Garager Sale Listings **/

		$sales = "";
		$query = "SELECT garagersale_id, name, date, customer.address.street_address, customer.address.city, customer.address.state, customer.address.zip_code
				 FROM garagersale
				 LEFT OUTER JOIN customer.address ON garagersale.address_id = customer.address.address_id
				 WHERE garagerseller_id = ". $_SESSION['user_id'] . " ORDER BY date DESC LIMIT 5";
 
		$result = mysqli_query($con2, $query);
		if($result == null or $result->num_rows == 0){
			$sales = "<p>No Garager Sales Found</p>";
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
		
		mysqli_free_result($result);

		$_PAGE['sidebar3'] ="<h4>My Garager Listings</h4>". $sales;


	$_PAGE['favorites'] = isset($user_activity['favorites']) ? $user_activity['favorites'] : 0;
	$_PAGE['checkins'] = isset($user_activity['checkins']) ? $user_activity['checkins'] : 0;		
	$_PAGE['garager_sales'] = isset($user_activity['garagersales']) ? $user_activity['garagersales'] : 0;		
	$_PAGE['buys'] = isset($user_activity['buys']) ? $user_activity['buys'] : 0;
	$_PAGE['sells'] = isset($user_activity['sells']) ? $user_activity['sells'] : 0;	

	$_PAGE['banner'] = "<img class='conright-img' src='images/banners/banner2.png' alt='banner'/>";	// Banner when logged in

	$_PAGE['follow'] = "";
	$_PAGE['sidebar1'] = "<div class='sidebar1-top'>
							<div class='follow'>". $_PAGE['follow'] . "</div>
							<p class='userpic'>". $_PAGE['profile_pic']. "<img id='star' src='images/icons/star_icon.png' alt='hasstar'></p>	
							<p class='username'>". $_PAGE['user_name']. " </p>
							<p class='userlocation'>" . $_PAGE['user_location']. " </p>
							<p class='history'><a id='Buys'><b>" . $_PAGE['buys']. " Buys  </b></a>| <a id='Sells'><b>" . $_PAGE['sells']. " Sells</b></a><br></p>
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
} else
{
	$_PAGE['banner'] = "<img class='conright-img' src='images/banners/banner.jpg' alt='banner'/>"; // Banner when not logged in
	$_PAGE['sidebar1'] = "<div class='ads'><img class='signupimage' src='images/icons/signup.png' alt='signupnow!'/>
						  <img id='newad' src='images/banners/ad.png' alt='AD'/></div>";
	$_PAGE['sidebar2']="";
	$_PAGE['sidebar3']=""; 

}	
	$_PAGE['items'] = "";
	$_PAGE['search'] = "<p>TAGS</p>";

	
	if (isset($iden)){
		foreach($iden as $key => $value){
			$_PAGE['search'] .= "<span class='stored_search_tag' id= '". $value ."'>
							<a class='searched_tag' href='search.php?tag=".$value. "'>".urldecode(strtoupper($value))."</a><a href='#' class='clear_tag'>x</a></span> ";
		}
	}

	else if (isset($_SESSION['category']) and isset($_SESSION['subcategory'])){
		if ($_SESSION['subcategory'] != "")
		$_PAGE['search'] .= "<span class='stored_search_category' id= '". $_SESSION['category'] ."'>
							<a class='searched_tag' href='search.php?category=".$_SESSION['category']. "&subcategory=".$_SESSION['subcategory']."'>". urldecode (strtoupper($_SESSION['subcategory']))."</a>
							</span> ";

	 	else 
	 	$_PAGE['search'] .= "<span class='stored_search_category' id= '". $_SESSION['category'] ."'>
							<a class='searched_tag' href='search.php?category=".$_SESSION['category']."'>". urldecode (strtoupper($_SESSION['category']))."</a>
							</span> ";	

	}
	else{
	}


	$_PAGE['sort'] = "<div class='sort_nav'><p>SORT </p>
  						
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

	$_PAGE['title'] ="<h2>Search Results</h2>";
	
	/* Get Page Items */
		$PAGE['items'] = "";
		$query = "";
		
	/*-----------------   NON PERFECT MATCH   -----------------*/
		if(!isSet($_GET['category']) && isSet($options) && in_array('perfect', $options))
		{
		
		/* user search */
		/* need customer DB*/
		
		  if(isSet($_GET['type']) && $_GET['type']=='user')
		  {
			$query = "SELECT DISTINCT user_id, user_name, image, caption
					  FROM user
					  NATURAL JOIN userpictures
					  NATURAL JOIN accountinfo
					  ";
			if(isSet($_GET['option']) && in_array('zip', $options))
			{
			  $query = $query."NATURAL JOIN addresslistings
					  NATURAL JOIN address
					  ";
			}
			$query = $query."WHERE ";
			$i = 0;
			foreach($iden as $key => $value)
			{
			  if ($i!=0)
			  {
			  $query .= "OR ";
			  }
			  $query = $query."user_name LIKE '%".$value."%' ";
			  if(isSet($_GET['option']) && in_array('zip', $options))
			  {
				$query = $query."OR zip_code='".$value."' ";
			  }
			  $i++;
			}
		  }
		
		/* item and garager sale search */
		/* need merchandise DB */
		
		  if((isSet($_GET['type']) && $_GET['type']=='item') || !(isSet($_GET['type'])))
		  {
			$query = "SELECT DISTINCT item_id, item.name as item_name, main_picture_id, price, COUNT(favoriteitem.user_id) as faves, image, caption, sale_type, item.starting_date
					  FROM item
					  INNER JOIN merchandisepictures ON item.main_picture_id=merchandisepictures.picture_id
					  NATURAL LEFT JOIN favoriteitem
					  ";
			if((isSet($_GET['type']) && in_array('tag',$options)) || !(isSet($_GET['type'])))
			{
			  $query .= "NATURAL LEFT JOIN itemtags
					  NATURAL LEFT JOIN tags
					  ";
			}
			if((isSet($_GET['type']) && in_array('category',$options)) || !(isSet($_GET['type'])))
			{
			  $query .= "NATURAL LEFT JOIN itemcategory
					  NATURAL LEFT JOIN category
					  ";
			}
			if((isSet($_GET['type']) && in_array('user',$options)) || !(isSet($_GET['type'])))
			{
			  $query .= "NATURAL LEFT JOIN itemseller
					  NATURAL LEFT JOIN customer.accountinfo
					  ";
			}
			if((isSet($_GET['type']) && in_array('zip',$options)) || !(isSet($_GET['type'])))
			{
			  $query .= "NATURAL LEFT JOIN offlinesale
					  NATURAL LEFT JOIN customer.address
					  ";
			}		
		  
			$query .= "WHERE (hasSold = 0 AND (TIMEDIFF(item.end_date, NOW()) >= 0 OR ISNULL(item.end_date))) AND (";
			$i = 0;
			foreach($iden as $key => $value)
			{
			  if ($i!=0)
			  {
			  $query .= "OR ";
			  }
			  $query .= "item.name LIKE '%".$value."%' ";
			  if((isSet($_GET['type']) && in_array('user',$options)) || !(isSet($_GET['type'])))
			  {
				$query .= "OR user_name LIKE '%".$value."%' ";
			  }
			  if((isSet($_GET['type']) && in_array('tag',$options)) || !(isSet($_GET['type'])))
			  {
				$query .= "OR tag_name='".$value."' ";
			  }
			  if((isSet($_GET['type']) && in_array('category',$options)) || !(isSet($_GET['type'])))
			  {
				$query .= "OR category_name='".$value."' OR subcategory_name='".$value."' ";
			  }
			  if((isSet($_GET['type']) && in_array('zip',$options)) || !(isSet($_GET['type'])))
			  {
				$query .= "OR zip_code='".$value."' ";
			  }
			  $i++;
			}
			$query .=") GROUP BY item.item_id, item.main_picture_id, item.starting_date, item.sale_type, item_name, image, item.price
				   			ORDER BY starting_date DESC";
			
			
		/* garager sale search */		
			$query .= ";SELECT DISTINCT garagersale_id, garagersale.name as gsale_name, user_name, street_address, city, state, zip_code, date, MIN(itempictures.picture_id), image, caption, COUNT(favoritegaragersale.user_id) as faves
					  FROM garagersale
					  NATURAL LEFT JOIN garagersalelistings
					  NATURAL LEFT JOIN itempictures
					  NATURAL LEFT JOIN merchandisepictures
					  NATURAL LEFT JOIN favoritegaragersale
					  NATURAL LEFT JOIN customer.address
					  LEFT JOIN customer.accountinfo ON accountinfo.user_id=garagersale.garagerseller_id
					  
					  ";
			if((isSet($_GET['type']) && in_array('tag',$options)) || !(isSet($_GET['type'])))
			{
			  $query .= "NATURAL LEFT JOIN garagersaletags
					  NATURAL LEFT JOIN tags
					  ";
			}
			if((isSet($_GET['type']) && in_array('category',$options)) || !(isSet($_GET['type'])))
			{
			  $query .= "NATURAL LEFT JOIN garagersalecategory
					  NATURAL LEFT JOIN category
					  ";
			}

			$query .= "WHERE (TIMEDIFF(garagersale.date,NOW()) >= 0 OR ISNULL(garagersale.date)) AND (";
			$i = 0;
			foreach($iden as $key => $value)
			{
			  if ($i!=0)
			  {
			  $query .= "OR ";
			  }
			  $query .= "garagersale.name LIKE '%".$value."%' ";
			  if((isSet($_GET['type']) && in_array('user',$options)) || !(isSet($_GET['type'])))
			  {
				$query .= "OR user_name LIKE '%".$value."%' ";
			  }
			  if((isSet($_GET['type']) && in_array('tag',$options)) || !(isSet($_GET['type'])))
			  {
				$query .= "OR tag_name='".$value."' ";
			  }
			  if((isSet($_GET['type']) && in_array('category',$options)) || !(isSet($_GET['type'])))
			  {
				$query .= "OR category_name='".$value."' OR subcategory_name='".$value."' ";
			  }
			  if((isSet($_GET['type']) && in_array('zip',$options)) || !(isSet($_GET['type'])))
			  {
				$query .= "OR zip_code='".$value."' ";
			  }
			  $i++;
			}
			$query .=")
			GROUP BY garagersale.garagersale_id, name, user_name, address.street_address, address.city, address.state, address.zip_code, date
			ORDER BY garagersale.date DESC";			
		  }
		
		}
	/*-----------------     PERFECT MATCH    -----------------*/
		else if(!isSet($_GET['category']) && (!isSet($_GET['option']) || !(in_array('perfect', $options))))
		{
		
		/* user search */
		/* need customer DB*/
		  if(isSet($_GET['type']) && $_GET['type']=='user')
		  {
			if(in_array('name',$options))
			{
			  if(!in_array('zip',$options)) //if I want a perfect match on just a name,
			  {
				if(sizeof($iden) > 1)   //I should submit only one
				  $_PAGE['title'] .= "<p>Your search cannot be matched. For an exact match, please enter one username only.</p>";
				else{
				  $query = "SELECT DISTINCT user_id, user_name, image, caption
							FROM user
							NATURAL JOIN userpictures
							NATURAL JOIN accountinfo
							WHERE user_name ='".$_GET['tag']."'
					  ";
				}
			  }else{
				//if I want a perfect match including a name, I can only have one non-zip-code-like entry
				$i = 0;
				unSet($name);
				foreach($iden as $key => $value)
				{
				  if(!(preg_match('/[0-9]{5}/',$value)))
				  {
					$name = $value;
					$i++;
				  }
				}
				
				if($i > 1)
				  $_PAGE['title'] .= "<p>Your search cannot be matched. For an exact match, please enter one username only.</p>";
				else
				{
				  $query = "SELECT DISTINCT t0.user_id, t0.user_name, t0.image, t0.caption
							  FROM (";
				  $i = 0;
				  foreach($iden as $key => $value)
				  {
					if($value != $name)
					{
					  if($i!=0)
						$query .= " INNER JOIN (";
					  $query .= userPerfectRequest()." user_name='".$name."' AND zip_code='".$value."') AS t".$i;
					  if($i!=0)
						$query .= " ON t".($i-1).".user_id=t".$i.".user_id";
					  $i++;
					}
				  }
				}
			  }
			}else{
			  if(in_array('zip',$options))  //if I am only matching zip codes
			  {
				foreach($iden as $key => $value)
				{
				  if(!(preg_match('/[0-9]{5}/',$value)))  //and I have something that is not a zip code ...
				  {
					$_PAGE['title'] .= "<p>Your search cannot be matched. This doesn't look like a ZIP code, does it?</p>";
				  }
				}
				  $query = "SELECT DISTINCT t0.user_id, t0.user_name, t0.image, t0.caption
							  FROM (";
				  $i = 0;
				  foreach($iden as $key => $value)
				  {
					if($i!=0)
					  $query .= " INNER JOIN (";
					$query .= userPerfectRequest()." zip_code='".$value."') AS t".$i;
					if($i!=0)
					  $query .= " ON t".($i-1).".user_id=t".$i.".user_id";
					$i++;
				  }
				
			  }
			  else
			  {
				$query = "SELECT DISTINCT user_id, user_name, image, caption
							FROM user
							NATURAL JOIN userpictures
							NATURAL JOIN accountinfo
							WHERE user_name LIKE '%".$_GET['tag']."%'
					  ";
			  }
			}
		  }
		
		
		
		
		/* item and garager sale search */
		/* need merchandise DB */
		
		  if((isSet($_GET['type']) && $_GET['type']=='item') || !(isSet($_GET['type'])))
		  {
			if(isSet($_GET['option']) && in_array('zip', $options))   //if we want a perfect match with a zip code
			{
			  $i=0;
			  foreach($iden as $value)
			  {
				if((preg_match('/[0-9]{5}/',$value)))
				{
				  $zip = $value;
				  $i++;
				}
			  }
			  if($i > 1)   // we should only have one
			  {
				$_PAGE['title'] .= "<p>Your search cannot be matched. For an exact match, please enter only one ZIP code.</p>";
			  }
			}
			if(isSet($_GET['option']) && in_array('category', $options))   //if we want a perfect match with category
			{
			  $cat = array();  //let's see wich of the tags are categories
			  $request = "SELECT category_name, subcategory_name
							FROM category
							WHERE ";
			  $i = 0;
			  foreach($iden as $value)
			  {
				if($i != 0)
				  $request .= " OR ";
				$request .= " category_name ='".$value."' OR subcategory_name ='".$value."' ";
				$i++;
			  }
			  $result = mysqli_query($con2,$request);
			  $i = 0;
			  while($row = $result->fetch_assoc())
			  {
				if(in_array(strtolower($row['category_name']), $iden) && !(in_array(strtolower($row['category_name']), $cat)))
				{
				  $cat[$i] = strtolower($row['category_name']);
				  $i++;
				}
				if(in_array(strtolower($row['subcategory_name']), $iden) && !(in_array(strtolower($row['subcategory_name']), $cat)))
				{
				  $cat[$i] = strtolower($row['subcategory_name']);
				  $i++;
				}
			  }
			  if($i==0)
				$_PAGE['title'] .= "<p>Your search cannot be matched. The terms you entered are not valid categories.</p>";
			}
			

			foreach($iden as $key => $value)
			{
			  if(($value == $zip) || (in_array(strtolower($value),$cat)))
				unSet($iden[$key]);
			}
			
			$param = array();
			
			if(in_array('zip',$options))
				$param['zip'] = $zip;
			
			$count = 0;
						  
			if(in_array('user',$options))
			{
			  foreach($iden as $key => $val0)
			  {
				$param['user'] = $val0;
				if(in_array('name',$options))
				{
				  foreach($iden as $key => $val1)
				  {
					if($val1 != $val0)
					{
					  $param['name'] = $val1;
					  if(in_array('category',$options))
					  {
						foreach($cat as $key => $val2)
						{
						  $param['cat'] = $val2;
						  if(in_array('tag',$options))
						  {
 							if($count != 0)
							  $query .= ";";
							$query .= "SELECT DISTINCT t0.item_id, t0.item_name, t0.image, t0.caption, t0.main_picture_id, t0.price, t0.faves, t0.sale_type, t0.starting_date
										FROM (";
							$i = 0;
							foreach($iden as $key => $val3)
							{
							  if($val3 != $val0 && $val3 != $val1)
							  {
								$param['tag'] = $val3;
								if($i != 0)
								  $query .= " INNER JOIN (";
								$query .= itemPerfectTagWrappQuery($param).") as t".$i;
								if($i!=0)
								  $query .= " ON t".($i-1).".item_id=t".$i.".item_id ";
								$i++;
							  }
							}
							$count++;

							$query .= ";SELECT DISTINCT t0.garagersale_id, t0.gsale_name, t0.user_name, t0.street_address, t0.city, t0.state, t0.zip_code, t0.date, t0.image, t0.caption, t0.faves
										FROM (";
							$i = 0;
							foreach($iden as $key => $val3)
							{
							  if($val3 != $val0 && $val3 != $val1)
							  {
								$param['tag'] = $val3;
								if($i != 0)
								  $query .= " INNER JOIN (";
								$query .= gsalePerfectTagWrappQuery($param).") as t".$i;
								if($i!=0)
								  $query .= " ON t".($i-1).".garagersale_id=t".$i.".garagersale_id ";
								$i++;
							  }
							}
							$count++;

							
						  }
						  else
						  {
							if(sizeof($iden)>2)
							  $_PAGE['title'] .= "<p>Your search cannot be matched. You entered too many tags.</p>";
							else
							{
							if($count != 0)
								$query .= ";";
							  $query .= itemPerfectCatWrappQuery($param);
							$count++;
							
							  $query .= ";".gsalePerfectCatWrappQuery($param);
							}
						  }
						}
					  }
					  else
					  {
						if(in_array('tag',$options))
						{
						  if($count != 0)
							  $query .= ";";
							$query .= "SELECT DISTINCT t0.item_id, t0.item_name, t0.image, t0.caption, t0.main_picture_id, t0.price, t0.faves, t0.sale_type, t0.starting_date
										FROM (";
							$i = 0;
							foreach($iden as $key => $val3)
							{
							  if($val3 != $val0 && $val3 != $val1)
							  {
								$param['tag'] = $val3;
								if($i != 0)
								  $query .= " INNER JOIN (";
								$query .= itemPerfectTagWrappQuery($param).") as t".$i;
								if($i!=0)
								  $query .= " ON t".($i-1).".item_id=t".$i.".item_id ";
								$i++;
							  }
							}
							$count++;
									  
							$query .= ";SELECT DISTINCT t0.garagersale_id, t0.gsale_name, t0.user_name, t0.street_address, t0.city, t0.state, t0.zip_code, t0.date, t0.image, t0.caption, t0.faves
										FROM (";
							$i = 0;
							foreach($iden as $key => $val3)
							{
							  if($val3 != $val0 && $val3 != $val1)
							  {
								$param['tag'] = $val3;
								if($i != 0)
								  $query .= " INNER JOIN (";
								$query .= gsalePerfectTagWrappQuery($param).") as t".$i;
								if($i!=0)
								  $query .= " ON t".($i-1).".garagersale_id=t".$i.".garagersale_id ";
								$i++;
							  }
							}
							$count++;
						}
						else
						{
						  if(sizeof($iden)>2)
							$_PAGE['title'] .= "<p>Your search cannot be matched. You entered too many tags.</p>";
						  else
						  {
							if($count != 0)
							  $query .= ";";
							$query .= itemPerfectNameWrappQuery($param);
							$count++;
							
							$query .= ";".gsalePerfectNameWrappQuery($param); 
						  }
						  
						}
					  }
					}
				  }
				  
				}
				else
				{
				  if(in_array('category',$options))
				  {
					foreach($cat as $val2)
					{
					  $param['cat'] = $val2;
					  if(in_array('tag',$options))
					  {
						if($count != 0)
						  $query .= ";";
						$query .= "SELECT DISTINCT t0.item_id, t0.item_name, t0.image, t0.caption, t0.main_picture_id, t0.price, t0.faves, t0.sale_type, 
									FROM (";
						$i = 0;
						foreach($iden as $key => $val3)
						{
						  if($val3 != $val0)
						  {
							$param['tag'] = $val3;
							if($i != 0)
							  $query .= " INNER JOIN (";
							$query .= itemPerfectTagWrappQuery($param).") as t".$i;
							if($i!=0)
							  $query .= " ON t".($i-1).".item_id=t".$i.".item_id ";
							$i++;
						  }
						}
						$count++;

						$query .= ";SELECT DISTINCT t0.garagersale_id, t0.gsale_name, t0.user_name, t0.street_address, t0.city, t0.state, t0.zip_code, t0.date, t0.image, t0.caption, t0.faves
									FROM (";
						$i = 0;
						foreach($iden as $key => $val3)
						{
						  if($val3 != $val0)
						  {
							$param['tag'] = $val3;
							if($i != 0)
							  $query .= " INNER JOIN (";
							$query .= gsalePerfectTagWrappQuery($param).") as t".$i;
							if($i!=0)
							  $query .= " ON t".($i-1).".garagersale_id=t".$i.".garagersale_id ";
							$i++;
						  }
						}
						$count++;
						
					  }
					  else
					  {
						if(sizeof($iden) > 1)
						  $_PAGE['title'] .= "<p>Your search cannot be matched. You entered too many tags</p>";
						else
						{
						  if($count != 0)
							$query .= ";";
						  $query .= itemPerfectCatWrappQuery($param);
						  $count++;
						  
						  $query .= ";".gsalePerfectCatWrappQuery
						  ($param);
						}
					  }
					}
				  }
				  else
				  {
					if(in_array('tag',$options))
					{
					  if($count != 0)
						$query .= ";";
					  $query .= "SELECT DISTINCT t0.item_id, t0.item_name, t0.image, t0.caption, t0.main_picture_id, t0.price, t0.faves, t0.sale_type, t0.starting_date
										FROM (";
					  $i = 0;
					  foreach($iden as $key => $val3)
					  {
						if($val3 != $val0)
						{
						  $param['tag'] = $val3;
						  if($i != 0)
							$query .= " INNER JOIN (";
						  $query .= itemPerfectTagWrappQuery($param).") as t".$i;
						  if($i!=0)
							$query .= " ON t".($i-1).".item_id=t".$i.".item_id ";
								$i++;
						 }
					  }
					  $count++;

					  $query .= ";SELECT DISTINCT t0.garagersale_id, t0.gsale_name, t0.user_name, t0.street_address, t0.city, t0.state, t0.zip_code, t0.date, t0.image, t0.caption, t0.faves
										FROM (";
					  $i = 0;
					  foreach($iden as $key => $val3)
					  {
						if($val3 != $val0)
						{
						  $param['tag'] = $val3;
						  if($i != 0)
							$query .= " INNER JOIN (";
						  $query .= gsalePerfectTagWrappQuery($param).") as t".$i;
						  if($i!=0)
							$query .= " ON t".($i-1).".garagersale_id=t".$i.".garagersale_id ";
								$i++;
						 }
					  }
					  $count++;

					}
					else
					{
					  if(sizeof($iden)>1)
						$_PAGE['title'] .= "<p>Your search cannot be matched. You entered too many tags</p>";
					  else
					  {
						if($count != 0)
						  $query .= ";";
						$query .= itemPerfectSeedQuery($param);
						$count++;
						
						$query .=";".gsalePerfectSeedQuery($param);
					  }
						  
					}
				  }
				}
			  }
			}
			  
			  else
			  {
				if(in_array('name',$options))
				{
				  foreach($iden as $val1)
				  {
					$param['name'] = $val1;
					if(in_array('category',$options))
					{
					  foreach($iden as $val2)
					  {
						$param['cat'] = $val2;
						if(in_array('tag',$options))
						{
						  if($count != 0)
							$query .= ";";
						  $query .= "SELECT DISTINCT t0.item_id, t0.item_name, t0.image, t0.caption, t0.main_picture_id, t0.price, t0.faves, t0.sale_type, t0.starting_date
										FROM (";
						  $i = 0;
						  foreach($iden as $key => $val3)
						  {
							if($val3 != $val1)
							{
							  $param['tag'] = $val3;
							  if($i != 0)
								$query .= " INNER JOIN (";
							  $query .= itemPerfectTagWrappQuery($param).") as t".$i;
							  if($i!=0)
								$query .= " ON t".($i-1).".item_id=t".$i.".item_id ";
							  $i++;
							}
						  }
						  $count++; 

						  $query .= ";SELECT DISTINCT t0.garagersale_id, t0.gsale_name, t0.user_name, t0.street_address, t0.city, t0.state, t0.zip_code, t0.date, t0.image, t0.caption, t0.faves
										FROM (";
						  $i = 0;
						  foreach($iden as $key => $val3)
						  {
							if($val3 != $val1)
							{
							  $param['tag'] = $val3;
							  if($i != 0)
								$query .= " INNER JOIN (";
							  $query .= gsalePerfectTagWrappQuery($param).") as t".$i;
							  if($i!=0)
								$query .= " ON t".($i-1).".garagersale_id=t".$i.".garagersale_id ";
							  $i++;
							}
						  }
						  $count++; 
						  
						}
						else
						{
						  if(sizeof($iden) > 1)
							$_PAGE['title'] .= "<p>Your search cannot be matched. You entered too many tags</p>";
						  else
						  {
							if($count != 0)
							  $query .= ";";
							$query .= itemPerfectCatWrappQuery($param);
							$count++;
							
							$query .= ";".gsalePerfectCatWrappQuery($param);
						  }
						}
					  }
					}
				  else
				  {
					if(in_array('tag',$options))
					{
					  if($count != 0)
						$query .= ";";
					  $query .= "SELECT DISTINCT t0.item_id, t0.item_name, t0.image, t0.caption, t0.main_picture_id, t0.price, t0.faves, t0.sale_type, t0.starting_date
										FROM (";
					  $i = 0;
					  foreach($iden as $key => $val3)
					  {
						if($val3 != $val1)
						{
						  $param['tag'] = $val3;
						  if($i != 0)
							$query .= " INNER JOIN (";
						  $query .= itemPerfectTagWrappQuery($param).") as t".$i;
						  if($i!=0)
							$query .= " ON t".($i-1).".item_id=t".$i.".item_id ";
						  $i++;
						}
					  }
					  $count++;

					  $query .= ";SELECT DISTINCT  t0.garagersale_id, t0.gsale_name, t0.user_name, t0.street_address, t0.city, t0.state, t0.zip_code, t0.date, t0.image, t0.caption, t0.faves
										FROM (";
					  $i = 0;
					  foreach($iden as $key => $val3)
					  {
						if($val3 != $val1)
						{
						  $param['tag'] = $val3;
						  if($i != 0)
							$query .= " INNER JOIN (";
						  $query .= gsalePerfectTagWrappQuery($param).") as t".$i;
						  if($i!=0)
							$query .= " ON t".($i-1).".garagersale_id=t".$i.".garagersale_id ";
						  $i++;
						}
					  }
					  $count++;
					  
					}
					else
					{
					  if(sizeof($iden)>1)
						$_PAGE['title'] .= "<p>Your search cannot be matched. You entered too many tags.</p>";
					  else
					  {
						if($count != 0)
						  $query .= ";";
						$query .= itemPerfectNameWrappQuery($param);
						$count++;
						
						$query .= ";".gsalePerfectNameWrappQuery($param);
					  }
					}
				  }

				  }
				  }
				else
				{
				  if(in_array('category',$options))
				  {
					foreach($cat as $val2)
					{
					  $param['cat'] = $val2;
					  if(in_array('tag',$options))
					  {
						if($count != 0)
						  $query .= ";";
						$query .= "SELECT DISTINCT t0.item_id, t0.item_name, t0.image, t0.caption, t0.main_picture_id, t0.price, t0.faves, t0.sale_type, t0.starting_date
										FROM (";
						$i = 0;
						foreach($iden as $key => $val3)
						{
						  $param['tag'] = $val3;
						  if($i != 0)
							$query .= " INNER JOIN (";
						  $query .= itemPerfectTagWrappQuery($param).") as t".$i;
						  if($i!=0)
							$query .= " ON t".($i-1).".item_id=t".$i.".item_id ";
						  $i++;
						}
						$count++; 

						$query .= ";SELECT DISTINCT  t0.garagersale_id, t0.gsale_name, t0.user_name, t0.street_address, t0.city, t0.state, t0.zip_code, t0.date, t0.image, t0.caption, t0.faves
										FROM (";
						$i = 0;
						foreach($iden as $key => $val3)
						{
						  $param['tag'] = $val3;
						  if($i != 0)
							$query .= " INNER JOIN (";
						  $query .= gsalePerfectTagWrappQuery($param).") as t".$i;
						  if($i!=0)
							$query .= " ON t".($i-1).".garagersale_id=t".$i.".garagersale_id ";
						  $i++;
						}
						$count++; 
						
					  }
					  else
					  {
						if(sizeof($iden) != 0)
							$_PAGE['title'] .= "<p>Your search cannot be matched. You entered too many tags.</p>";
						else
						{
						  if($count != 0)
							$query .= ";";
						  $query .= itemPerfectCatWrappQuery($param);
						  $count++;
						  
						  $query .= ";".gsalePerfectCatWrappQuery($param);
						}
					  }
					}
				  }
				  else
				  {
// 					if(in_array('tag',$options))
// 					{
					  if($count != 0)
						$query .= ";";
					  $query .= "SELECT DISTINCT t0.item_id, t0.item_name, t0.image, t0.caption, t0.main_picture_id, t0.price, t0.faves, t0.sale_type, t0.starting_date
										FROM (";
					  $i = 0;
					  foreach($iden as $key => $val3)
					  {
						$param['tag'] = $val3;
						if($i != 0)
						  $query .= " INNER JOIN (";
						$query .= itemPerfectTagWrappQuery($param).") as t".$i;
						if($i!=0)
						$query .= " ON t".($i-1).".item_id=t".$i.".item_id ";
						$i++;
					  }
					  $count++; 

					  $query .= ";SELECT DISTINCT  t0.garagersale_id, t0.gsale_name, t0.user_name, t0.street_address, t0.city, t0.state, t0.zip_code, t0.date, t0.image, t0.caption, t0.faves
										FROM (";
					  $i = 0;
					  foreach($iden as $key => $val3)
					  {
						$param['tag'] = $val3;
						if($i != 0)
						  $query .= " INNER JOIN (";
						$query .= gsalePerfectTagWrappQuery($param).") as t".$i;
						if($i!=0)
						$query .= " ON t".($i-1).".garagersale_id=t".$i.".garagersale_id ";
						$i++;
					  }
					  $count++; 
					  
// 					}
/*					else
					{
					  
					  if(sizeof($iden) != 0)
						$_PAGE['title'] .= "<p>Your search cannot be matched. You entered too many tags.</p>";
					  else
					  {
						if($count != 0)
						  $query .= ";";
						$query .= itemPerfectSeedQuery($param);
						$count++;
						
						$query .= ";".gsalePerfectSeedQuery($param);
					  }
					}
	*/			  }
				}
			  }
			}
		}
		
	/*-----------------       CATEGORY      -----------------*/
		
		else if(isSet($_GET['category']))
		{

		$subcategory_query = isset($_GET['subcategory']) ? " AND (subcategory_name='".$subcategory . "')": "";
		$query  = "SELECT item.item_id, item.name as item_name, main_picture_id, price, COUNT(favoriteitem.user_id) as faves, image, caption, item.sale_type, item.starting_date
					  FROM item
					  NATURAL JOIN itemcategory
					  NATURAL JOIN category
					  LEFT JOIN merchandisepictures ON merchandisepictures.picture_id=item.main_picture_id
					  NATURAL LEFT JOIN favoriteitem
					  WHERE (hasSold=0 OR hasSold=NULL)
					    AND (TIMEDIFF(item.end_date, NOW()) >= 0 OR ISNULL(item.end_date))
					    AND category_name='".$category."' ".$subcategory_query."
					  GROUP BY item.item_id, item.main_picture_id, item.starting_date, item.sale_type, item_name, image, item.price
					  ORDER BY starting_date DESC";

						
	 	$query .= ";SELECT DISTINCT garagersale.garagersale_id, garagersale.name as gsale_name, user_name, street_address, city, state, zip_code, date, MIN(itempictures.picture_id), image, caption, COUNT(favoritegaragersale.user_id) as faves
				   FROM garagersale
				   NATURAL JOIN garagersalecategory
				   NATURAL JOIN category
				   NATURAL LEFT JOIN customer.address
				   LEFT JOIN customer.accountinfo ON garagersale.garagerseller_id = accountinfo.user_id
				   NATURAL LEFT JOIN garagersalelistings
				   NATURAL LEFT JOIN itempictures
				   NATURAL LEFT JOIN merchandisepictures
				   NATURAL LEFT JOIN favoritegaragersale
				   WHERE (TIMEDIFF(garagersale.date,NOW()) >= 0 OR ISNULL(garagersale.date))
				   AND (category.category_name = '".$category."') " .$subcategory_query. "
				   GROUP BY garagersale.garagersale_id, name, user_name, address.street_address, address.city, address.state, address.zip_code, date
				   ORDER BY garagersale.date DESC";
		}
		
	/*-----------------       RESULTS      -----------------*/
//  	echo $query;
		if(isSet($_GET['type']) && $_GET['type']=='user')
		{
		  mysqli_multi_query($con,$query);
		  do{
			$result = mysqli_store_result($con);
			if(!$result);
			else
			{
			  while($row = $result->fetch_assoc())
			  {
				$_PAGE['items'] .= "<a href='profile.php?user=". $row['user_id'] ."'><img id= '" . $row['user_id'] . "' class='conright-expics' src='data:image/jpg;base64,". base64_encode($row['image']) ."' title= '". $row['user_name'] ."'/></a>";
			  }
			}
			mysqli_free_result($result);
			if(!mysqli_more_results($con)) break;
		  }while(mysqli_next_result($con));
		}
		
		else if((isSet($_GET['type']) && $_GET['type']=='item') || !isSet($_GET['type']))
		{
		  mysqli_multi_query($con2,$query);
		  do{
			$result = mysqli_store_result($con2);
			if(!$result);
			else
			{
			  while($row = $result->fetch_assoc()){
				if(isSet($row['item_id']))
				{
				  $item = "<a href='itemLightBox' class='lightbox_trigger ". $row['sale_type']. "' price='" . $row['price'] . "' date='" . $row['starting_date'] . "' faves='" . $row['faves'] . "'><img id= '" . $row['item_id'] . "' class='conright-expics' src='data:image/jpg;base64,". base64_encode($row['image']) ."' alt='".$row['caption']."'/></a>
			  ";

				  $_PAGE['items'] .= $item;
				}
				else if(isSet($row['garagersale_id']))
				{
				date_default_timezone_set('America/New_York');
				$full_date = strtotime($row['date']);
				$date = ($full_date != null)? date('l',$full_date) .", ". strtoupper(date('M',$full_date)) . " " . date('j',$full_date):"No date specified<br/>";
				$time = ($full_date != null)? date('g',$full_date ) .":". date('i',$full_date) . " " . date('a',$full_date) : "";
				$address = ($row['street_address'] != null)? $row['street_address']."<br/>". $row['city'].", ". $row['state']."<br/>". $row['zip_code']:"<br/>No address specified<br/>";
				$sale_image = ($row['image'] != null)? "<img id= 'gsale_pic" . $row['garagersale_id'] . "' class='garagersale_pics' title= '". $row['gsale_name'] ."' src='data:image/jpg;base64,". base64_encode($row['image'])."'/>"
											 :"<img id= '" . $row['garagersale_id'] . "' class='garagersale_pics' title= '". $row['gsale_name'] ."' src='images/icons/garager_icon.jpg' ></img>";

				$item = "<a href='GaragerSale.php?gsale_id=". $row['garagersale_id'] ."' class='garagersale' date='" . $row['date'] . "' faves='" . $row['faves'] . "'>".$sale_image . "<p class='garagersale_text'><b>". $row['gsale_name'] ."</b><br/><b>". $row['user_name'] ."</b></p>
							  <p class='garagersale_full_text'><b>". $row['gsale_name'] ."</b><br/>" .$address. "<br/>". $time . "<br/>". $date ."</p></a>";
				
				$_PAGE['items'] .= $item;
				}
			  }
			  mysqli_free_result($result);
			}
			
			if(!mysqli_more_results($con2)) break;
		  }while(mysqli_next_result($con2));
		}
		
		
		if($_PAGE['items'] == "") 
			$_PAGE['title'] .= "<p>No results returned for this search</p>";



include('template.php');

?>
