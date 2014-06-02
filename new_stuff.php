<?php session_start(); 

include ('config.php');

$con = mysqli_connect($host,$username,$password,$db_name1); 
$con2 = mysqli_connect($host,$username,$password,$db_name2);

  
if (isSet ($_SESSION['user_id'])){
	$id = $_SESSION['user_id'];	
	/* Queries to Customer */
	
	if (mysqli_connect_errno($con)){
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}else{
		/** 3 STEP PROCESS **/
		if(isset($_POST['save_tags'])){
        $_ERROR = [];       
        $tags = array();

        foreach($_POST['tag'] as $tag){
            if($tag != '')
              array_push($tags, mysql_real_escape_string($tag));
        }

        foreach($tags as $tag){
          $query = "INSERT INTO tags (tag_name) VALUES ('".$tag . "')";
          mysqli_query($con2, $query);
        }

        foreach($_POST['category'] as $tag){
            if($tag != '')
              array_push($tags, mysql_real_escape_string($tag));
        }

        $taglist = join("','",$tags);
        $query = "SELECT tag_id FROM tags WHERE tag_name IN ('$taglist')";
        $result = mysqli_query($con2, $query);

        if($result != null){
          while($row = $result->fetch_array()){
            $query = "INSERT INTO favoritetags (user_id, tag_id) VALUES ('". $_SESSION['user_id'] . "'," . $row['tag_id'] .")";
            $res = mysqli_query($con, $query);
            if($res == null or $res === false) 
              $_PAGE['tag_success'] = false;
          }

          if(!isset($_PAGE['tag_success']))
            $_PAGE['tag_success'] = true;
        } 

      }
      if(isset($_POST['update_tags'])){
        $_ERROR = [];
        $tags = array();

        foreach($_POST['deleted'] as $tag){
            if($tag != '')
              array_push($tags, mysql_real_escape_string($tag));
        }
        $taglist = join("','",$tags);

        $query = "DELETE FROM favoritetags WHERE user_id = ". $_SESSION['user_id'] . " and tag_id IN ('$taglist')";
        $result = mysqli_query($con, $query);
        if($result == null or $result === false) 
          $_PAGE['tag_success'] = false;
        
        
        if(!isset($_PAGE['tag_success']))
          $_PAGE['tag_success'] = true;

      }

      if($_POST['get_started_button']) {
    		unset($_SESSION['threeStep']);
    		header('location : new_stuff.php');
  	  }

      /** END 3 STEP PROCESS  **/

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

	
		$_PAGE['favorites'] = isset($user_activity['favorites']) ? $user_activity['favorites'] : 0;
		$_PAGE['checkins'] = isset($user_activity['checkins']) ? $user_activity['checkins'] : 0;		
		$_PAGE['garager_sales'] = isset($user_activity['garagersales']) ? $user_activity['garagersales'] : 0;		
		$_PAGE['buys'] = isset($user_activity['buys']) ? $user_activity['buys'] : 0;
		$_PAGE['sells'] = isset($user_activity['sells']) ? $user_activity['sells'] : 0;				
	}

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

	/** My Garager Sale Listings **/

		$sales = "";
		$query = "SELECT garagersale_id, name, date, customer.address.street_address, customer.address.city, customer.address.state, customer.address.zip_code
				 FROM garagersale
				 LEFT OUTER JOIN customer.address ON garagersale.address_id = customer.address.address_id
				 WHERE garagerseller_id = ". $id . " ORDER BY date DESC LIMIT 5";

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

	  if (isset($_SESSION['threeStep']) && $_SESSION['threeStep']==1){

		$_PAGE['banner'] =

				" <div id='slider_background'>
				  <img src='images/banners/3step.JPG'>
				  
				  <div id='slider'>
				    <ul>
				      <li>
				        <div class='step1'>

				          <a href='#'' class='control_next'>></a>
				          <a href='#' class='control_prev'><</a>
				          
				          <div class='step123'>1 <span class='fade'>2 3</span> Welcome to <b>garager</b>
				          </div>
				      
				        

				          <div class='description'>
				            See the newest items people are selling in the NEW STUFF feed below. Customize your feed by selecting your favorite categories. Add custom tags to refine your results - add as many as you want!
				          </div>

				          <div id='select_category'>
				            SELECT GARAGER CATEGORIES
				          </div>

				<!-- new added -->
				<div id='select_list'>
				    
				    <form method='post' action=''>


				    
				      
				      
				      <table class='cat_listings'>  
				        <tr>
				          <td><input type='checkbox' name='category[]' value='ART'>ART</input></td>
				          <td><input type='checkbox' name='category[]' value='BOOKS'>BOOKS</input></td>
				          <td><input type='checkbox' name='category[]' value='CLOTHING'>CLOTHING</input></td>
				          <td><input type='checkbox' name='category[]' value='JEWELRY'>JEWELRY</input></td>
				          <td><input type='checkbox' name='category[]' value='FURNITURE'>FURNITURE</input></td>
				        </tr>
				        <tr>          
				          <td><input type='checkbox' name='category[]' value='HOME & GARDEN'>HOME & GARDEN</input></td>
				          <td><input type='checkbox' name='category[]' value='MUSIC'>MUSIC</input></td>
				          <td><input type='checkbox' name='category[]' value='SPORTS'>SPORTS</input></td>
				          <td><input type='checkbox' name='category[]' value='TECH'>TECH</input></td>
				          <td><input type='checkbox' name='category[]' value='TOYS'>TOYS</input></td>
				        </tr>
				        <tr>
				          <td><input type='checkbox' name='category[]' value='WHEELS'>WHEELS</input></td>
				          <td><input type='checkbox' name='category[]' value='FEATURED'>FEATURED</input></td>
				          <td><input type='checkbox' name='category[]' value='VINTAGE'>VINTAGE</input></td>
				          <td><input type='checkbox' name='category[]' value='COLLECTIBLE'>COLLECTIBLE</input></td>
				          <td><input type='checkbox' name='category[]' value='UNIQUE'>UNIQUE</input></td>
				        </tr>
				        <tr>
				          <td><input type='checkbox' name='category[]' value='MISCELLANEOUS'>MISCELLANEOUS</input></td>
				          <td><input type='checkbox' name='category[]' value='$1'>$1</input></td>
				        </tr>
				      </table>
				    
				    <input type='submit' name='save_tags' class='save_button' value='Add'>
				    </form>
				</div>

				<!--end-->


				          <form method='post' action=''>
				          <div id='add_custom_tags'>
				            <!--<button id='add_it'>ADD CUSTOM TAGS</button>-->
				             <div class='add_tag_div1'>
				              <p class='tag_box1'><textarea name='tag[]' rows='1' maxlength='20' cols='10' placeholder='Tag'></textarea></p>
				              <p class='tag_box1'><textarea name='tag[]' rows='1' maxlength='20' cols='10' placeholder='Tag'></textarea></p>
				              <p class='tag_box1'><textarea name='tag[]' rows='1' maxlength='20' cols='10' placeholder='Tag'></textarea></p>
				          <!--    <a class='add_tag1' href=''>Add more</a>   -->
				            </div>
				            <input type='submit' name='save_tags' class='save_button' value='Add Custom Tags'>
				          </div>
				          </form>



				<!-- update part and post form-->

				          
				<!--new-->

				      <div class='history1' >
				          <form method='post' action=''>
				            <div class='piclist1'>

				            </div>
				            <input type='submit' id='update_tags' name='update_tags' class='save_button2' value='Update'>
				          </form>
				      </div>

				<!--end-->


				        </div>
				      </li>

				      <li >
				        <div class='step2'>
				          
				        <a href='#' class='control_next'>></a>
				          <a href='#' class='control_prev'><</a>

				          <div class='step123'><span class='fade'>1</span> 2 <span class='fade'> 3</span> Welcome to <b>garager</b>
				          </div>
				        
				        <div class='description2'>
				          Click on the smiley face to add your profile photo and edit your settings. Start following others and favoriting stuff you like. Click on the search icon to search for a special item or find garaged sales in your area by entering your zip. Buy, sell and have fun building your garaged community.
				          </div>

				          <div id='smileface'>
				            <a href='account.php'><img src='images/icons/default_avatar.jpg'></a>
				          </div>

				        </div>
				      </li>

				      <li>
				        <div class='step3'>

				        <a href='#' class='control_next'>></a>
				          <a href='#' class='control_prev'><</a>

				          <div class='step123'><span class='fade'>1 2</span> 3 Welcome to <b>garager</b>
				          </div>

				        <div class='description2'>
				            It is s a snap! Click on the garaged tag above to begin selling your stuff. You can use garager to sell items online or promote your old-school, offline garage/yard/tag/estate sale. Reach a wider audience, sell your stuff quicker, build community. Heck, we’ll even post your sale page on craigslist.
				          </div>

				          <div id='step3_garager_sale'>
				            <a href='createSaleLightBox' class='lightbox_trigger'><img src='images/icons/garager_icon.jpg'></a>
				          </div>

				          <form method='post' action=''>
				          <!--<a href='#' id='get_started_button'>Get Started</a>-->
				          <input type='submit' id='get_started_button' name='get_started_button' value='Get Started'>
				          </form>

				        </div>
				    </li>
				    </ul>  

				  </div>
				  </div>";
	}

	else
		$_PAGE['banner'] = "<img class='conright-img' src='images/banners/banner2.png' alt='banner'/>";	// Banner when logged in
	
	$_PAGE['follow'] = "";
	$_PAGE['sidebar1'] = "<div class='sidebar1-top'>
							<div class='follow'>". $_PAGE['follow'] . "</div>
							<p class='userpic'>". $_PAGE['profile_pic']. 
							"<img id='star' src='images/icons/star_icon.png' alt='hasstar'>
						   	</p>	

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

	$_PAGE['sidebar2'] ="<h4>Who to Follow</h4>". $suggested;	
	$_PAGE['sidebar3'] ="<h4>My Garager Listings</h4>". $sales;


}	
/* Banner may be slide show independent of logging in */
else{
	$_PAGE['banner'] = "<img class='conright-img' src='images/banners/banner.jpg' alt='banner'/>"; // Banner when not logged in
	$_PAGE['sidebar1'] = "<div class='ads'><img class='signupimage' src='images/icons/signup.png' alt='signupnow!'/>
						  <img id='newad' src='images/banners/ad.png' alt='AD'/></div>";
	$_PAGE['sidebar2'] = "";
	$_PAGE['sidebar3'] = "";	
}
	/* May not be applicable for this page. Appears for Garager Sale page */ 

	$_PAGE['search'] ="";

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

	$_PAGE['title'] ="<h2>New Stuff</h2>";	
	
	/* Get Page Items */
		$_PAGE['items'] = "";	
		$items_array = array();
		$faves_array = array();	
		array_push($faves_array, '0');

		if(isset($_SESSION['user_id'])){
			$query_faves = "SELECT item.item_id, item.main_picture_id, item.starting_date, item.sale_type, item.name as name, image, item.price, COUNT(favoriteitem.user_id) as faves
							FROM item
							LEFT JOIN merchandisepictures ON item.main_picture_id = merchandisepictures.picture_id
							INNER JOIN itemcategory ON item.item_id = itemcategory.item_id
							INNER JOIN category ON itemcategory.category_id = category.category_id
							INNER JOIN customer.favoritetags ON customer.favoritetags.user_id =  " . $_SESSION['user_id'] . "
							INNER JOIN tags ON customer.favoritetags.tag_id = tags.tag_id AND category.category_name = tags.tag_name
							LEFT JOIN favoriteitem ON favoriteitem.item_id = item.item_id
							WHERE (hasSold=0 OR hasSold=NULL)
							AND (TIMEDIFF(item.end_date, NOW()) >= 0 OR ISNULL(item.end_date))
							GROUP BY item.item_id, item.main_picture_id, item.starting_date, item.sale_type, item.name,  item.price
							UNION
							SELECT item.item_id, item.main_picture_id, item.starting_date, item.sale_type, item.name as name, image, item.price, COUNT(favoriteitem.user_id) as faves
							FROM item
							LEFT JOIN merchandisepictures ON item.main_picture_id = merchandisepictures.picture_id
							INNER JOIN itemtags ON item.item_id = itemtags.item_id
							INNER JOIN customer.favoritetags ON itemtags.tag_id =  customer.favoritetags.tag_id 
							AND  customer.favoritetags.user_id =  " . $_SESSION['user_id'] . "
							LEFT JOIN favoriteitem ON favoriteitem.item_id = item.item_id
							WHERE (hasSold=0 OR hasSold=NULL)
							AND (TIMEDIFF(item.end_date, NOW()) >= 0 OR ISNULL(item.end_date))
							GROUP BY item.item_id, item.main_picture_id, item.starting_date, item.sale_type, item.name,  item.price
							ORDER BY starting_date DESC";

			$result_faves = mysqli_query($con2, $query_faves);
			if($result_faves == null or $result_faves->num_rows == 0){}
			else{
				while($row = $result_faves->fetch_array()){
					$item = "<a href='itemLightBox' class='lightbox_trigger ". $row['sale_type']. "' price='" . $row['price'] . "' date='" . $row['starting_date'] . "' faves='" . $row['faves'] . "'><img id= '" . $row['item_id'] . "' class='conright-expics' src='data:image/jpg;base64,". base64_encode($row['image']) ."' title= '". $row['name'] ."' /></a>";
					$_PAGE['items'] .= $item;
					array_push($faves_array,$row['item_id']); 
				}
			}

		}
			

		$faves_array = join(',',$faves_array);

		$query  = "SELECT item.item_id, item.main_picture_id, item.starting_date, item.sale_type, item.name as name, image, item.price, COUNT(favoriteitem.user_id) as faves
				   FROM item
				   LEFT JOIN merchandisepictures ON item.main_picture_id = merchandisepictures.picture_id
				   LEFT JOIN favoriteitem ON favoriteitem.item_id = item.item_id
				   WHERE (hasSold=0 OR hasSold=NULL)
				   AND (TIMEDIFF(item.end_date, NOW()) >= 0 OR ISNULL(item.end_date)) 
				   AND item.item_id NOT IN (".$faves_array.")
				   GROUP BY item.item_id, item.main_picture_id, item.starting_date, item.sale_type, item.name, image, item.price
				   ORDER BY item.starting_date DESC";

		
		$query2 = "SELECT garagersale.garagersale_id, garagersale.name, user_name, address.street_address, address.city, 
						  address.state, address.zip_code, garagersale.date, MIN(itempictures.picture_id), image, COUNT(favoritegaragersale.user_id) as faves
					FROM garagersale
					LEFT JOIN favoritegaragersale ON favoritegaragersale.garagersale_id = garagersale.garagersale_id
					LEFT JOIN customer.address ON garagersale.address_id = customer.address.address_id
					LEFT JOIN customer.accountinfo ON garagersale.garagerseller_id = accountinfo.user_id
					LEFT JOIN garagersalelistings ON garagersalelistings.garagersale_id = garagersale.garagersale_id
					LEFT JOIN itempictures ON garagersalelistings.item_id = itempictures.item_id
					LEFT JOIN merchandisepictures ON itempictures.picture_id = merchandisepictures.picture_id
					WHERE (TIMEDIFF(garagersale.date,NOW()) >= 0 OR ISNULL(garagersale.date))
					GROUP BY garagersale.garagersale_id, name, user_name, address.street_address, address.city, address.state, address.zip_code, date
					ORDER BY garagersale.date DESC";

		$result = mysqli_query($con2, $query);
		if($result == null or $result->num_rows == 0){}
		else{
			while($row = $result->fetch_array()){	
				$item = "<a href='itemLightBox' class='lightbox_trigger ". $row['sale_type']. "' price='" . $row['price'] . "' date='" . $row['starting_date'] . "' faves='" . $row['faves'] . "'><img id= '" . $row['item_id'] . "' class='conright-expics' src='data:image/jpg;base64,". base64_encode($row['image']) ."' title= '". $row['name'] ."' /></a>";
				array_push($items_array, array('value' => $item, 'date' =>  $row['starting_date']));
			}
		}
		
		$result2 = mysqli_query($con2, $query2);
		if($result2 == null or $result2->num_rows == 0){}
		else{
			date_default_timezone_set('America/New_York');
			while($row = $result2->fetch_array()){				 
				$full_date = strtotime($row['date']);
				$date = ($full_date != null)? date('l',$full_date) .", ". strtoupper(date('M',$full_date)) . " " . date('j',$full_date):"No date specified<br/>";
				$time = ($full_date != null)? date('g',$full_date ) .":". date('i',$full_date) . " " . date('a',$full_date) : "";
				$address = ($row['street_address'] != null)? $row['street_address']."<br/>". $row['city'].", ". $row['state']."<br/>". $row['zip_code']:"<br/>No address specified<br/>";
				$sale_image = ($row['image'] != null)? "<img id= 'gsale_pic" . $row['garagersale_id'] . "' class='garagersale_pics' title= '". $row['name'] ."' src='data:image/jpg;base64,". base64_encode($row['image'])."'/>"
											 :"<img id= '" . $row['garagersale_id'] . "' class='garagersale_pics' title= '". $row['name'] ."' src='images/icons/garager_icon.jpg' ></img>";

				$item = "<a href='GaragerSale.php?gsale_id=". $row['garagersale_id'] ."' class='garagersale' date='" . $row['date'] . "' faves='" . $row['faves'] . "'>".$sale_image . "<p class='garagersale_text'><b>". $row['name'] ."</b><br/><b>". $row['user_name'] ."</b></p>
							  <p class='garagersale_full_text'><b>". $row['name'] ."</b><br/>" .$address. "<br/>". $time . "<br/>". $date ."</p></a>";
				
				array_push($items_array, array('value' => $item, 'date' =>  $row['date']));
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