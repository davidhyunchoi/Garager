<?php session_start(); ?>
<?php 
	header("Cache-Control: no-cache, must-revalidate"); 
	include('config.php'); 
	$con = mysqli_connect($host,$username,$password,$db_name1); 
	$con2 = mysqli_connect($host,$username,$password,$db_name2);

	if(isset($_SESSION['user_id'])){
		$id = $_SESSION['user_id'];
		$_PAGE['address_success'] = "";
		$_PAGE['password_success'] = "";

		if (mysqli_connect_errno($con)){
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}else{
			if(isset($_POST['add_address'])){ // ADD ADDRESS
				$_ERROR = [];
				$_PAGE['address_success'] = false;
				if( (isset($_POST['street']) && ($_POST['street'] == "")) || (isset($_POST['city']) && ($_POST['city'] == "")) || (isset($_POST['state']) && ($_POST['state'] == "")) || (isset($_POST['zip']) && ($_POST['zip'] == "")) || (isset($_POST['country']) && ($_POST['country'] == "")) ){
					$_ERROR['required_address_fields'] = true;	
				}
				/* More validation

				*/

				if (!isset($_ERROR['required_address_fields'])){
					$raw = $_POST['street'] . " " . $_POST['apt'];
					$street = mysqli_real_escape_string($con, $raw);
					$city = mysqli_real_escape_string($con, $_POST['city']);
					$state = mysqli_real_escape_string($con, $_POST['state']);
					$zip = mysqli_real_escape_string($con, $_POST['zip']);
					$country = mysqli_real_escape_string($con, $_POST['country']);
								
					$query = "INSERT INTO address (street_address, city, state, zip_code, country) VALUES ('".$street."','".$city."','".$state."',".intval($zip).",'".$country."')";
					$result = mysqli_query($con, $query);
					if($result != null){
						$record_id = mysqli_insert_id($con);
						$query = "INSERT INTO addresslistings (user_id, address_id) VALUES ('".$_SESSION['user_id']."','".$record_id."')";
						$result = mysqli_query($con, $query);
						if($result == null)
							$_PAGE['address_success'] = false;
						else
							$_PAGE['address_success'] = true;
					}
					else
						$_PAGE['address_success'] = false;
				}

			}

			if(isset($_POST['delete_alternate']) or isset($_POST['delete_main'])){
				$_ERROR = [];
				$_PAGE['address_success'] = false;
				$address_id = mysqli_real_escape_string($con, $_POST['address']);
				$query = "DELETE address.* FROM address INNER JOIN addresslistings ON addresslistings.address_id = address.address_id WHERE addresslistings.user_id = " . $_SESSION['user_id'] . " and addresslistings.address_id = " . $address_id . ";";
				$result = mysqli_query($con, $query);
				if($result == null)
					$_PAGE['address_success'] = false;
				else
					$_PAGE['address_success'] = true;
			}

			if(isset($_POST['set_main'])){
				$_ERROR = [];
				$_PAGE['address_success'] = false;
				$address_id = mysqli_real_escape_string($con, $_POST['address']);
				$query = "UPDATE user SET main_address_id =" . $address_id . "  WHERE user.user_id = " . $_SESSION['user_id'];
				$result = mysqli_query($con, $query);
				if($result == null)
					$_PAGE['address_success'] = false;
				else
					$_PAGE['address_success'] = true;
			}

			if(isset($_POST['upload_photo'])){
				$_PAGE['photo_success'] = false;
				
				if ($_FILES['photo']['size'] > 0) {
 					$tmpName  = $_FILES['photo']['tmp_name'];
 				}
				
				if($tmpName != null){
					
					$_ERROR = [];
					$size = intval($_FILES["photo"]["size"]) / 1024;
					$ext = pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);						
					$format = mysqli_real_escape_string($con, strtolower($ext)); 

					$check_type = exif_imagetype($tmpName);
					if ($check_type === false || !($check_type == IMAGETYPE_GIF || $check_type == IMAGETYPE_JPEG || $check_type == IMAGETYPE_PNG)){
						$_ERROR['invalid_file_format'] = true;
					}else{						
						$fp   = fopen($tmpName, 'r');
  						$data = fread($fp, filesize($tmpName));
  						$image = mysqli_real_escape_string($con, $data);

  						$query = "SELECT picture_id FROM user WHERE user_id = " . $_SESSION['user_id'] ;
  						$result = mysqli_query($con, $query);
  						if($result == null || $result->fetch_array()['picture_id'] == null){
  							$query1 = "INSERT INTO userpictures (image,format,size) VALUES ('". $image ."', '" .$format . "', " . intval($size) . " )";
  							mysqli_query($con, $query1);
  							$pic_id = mysqli_insert_id($con);

  							$query2 = "UPDATE user SET picture_id = ". $pic_id . " WHERE user_id = " . $_SESSION['user_id'];
  							$result = mysqli_query($con, $query2);
  							if($result == null){
								$_PAGE['photo_success'] = false;
							}
							else{
								$_PAGE['photo_success'] = true;
							}
  						}

  						else{
  							$query = "UPDATE userpictures INNER JOIN user ON userpictures.picture_id = user.picture_id
							SET image = '". $image ."' , format = '" .$format . "' , size = " . intval($size) . " WHERE user.user_id = " . $_SESSION['user_id']; 
							$result = mysqli_query($con, $query);
							if($result == null){
								$_PAGE['photo_success'] = false;
							}
							else{
								$_PAGE['photo_success'] = true;	
							}
						}

						
  						fclose($fp);
					}
				}
			}

			if(isset($_POST['change_password'])){
				$_ERROR = [];
				$_PAGE['password_success'] = false;
				
				if ($_POST['new_pwd1'] != $_POST['new_pwd2'] ){
					$_ERROR['password mismatch'] = true;
				}
				
				else if ($_POST['curr_pwd'] == $_POST['new_pwd1'] ){
					$_ERROR['password unchanged'] = true;
				}
				
				else{

					$current_pwd = mysqli_real_escape_string($con, $_POST['curr_pwd']);
					$new_pwd = mysqli_real_escape_string($con, $_POST['new_pwd1']);

					$query = "SELECT password FROM accountinfo WHERE user_id = ". $_SESSION['user_id'];
		 			$result = mysqli_query($con, $query);

		 			if ($result != null){
		 				$row = $result->fetch_array();
		 				if($row['password']!=hash('ripemd160', $current_pwd)){
		 					$_ERROR['incorrect password'] = true;
		 				}
		 				else{
		 					$pwd_hash = hash('ripemd160', $new_pwd);	
		 					$query2 = "UPDATE accountinfo SET password = '". $pwd_hash ."' WHERE user_id = ". $_SESSION['user_id'];
		 					$result2 = mysqli_query($con, $query2);
		 					if($result2 != null){
		 						$_PAGE['password_success'] = true;
		 					}
		 					else{
		 						$_PAGE['password_success'] = false;
		 					}
		 				}
		 			}
		 			else{
		 				$_PAGE['password_success'] = false;
		 			}

				}

			}


			if(isset($_POST['submit'])){ // SAVE PROFILE DATA
				$_ERROR = [];
				$_PAGE['profile_success'] = false;
				$name = mysqli_real_escape_string($con, $_POST['name']);
		 		$user_name = mysqli_real_escape_string($con, $_POST['user_name']); 
		 		$email = mysqli_real_escape_string($con, $_POST['email']); 
		 		$description = mysqli_real_escape_string($con, $_POST['description']); 

		 		

		 		if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
		 			$_ERROR['invalid_email'] = true;		 				

				if($_SESSION['user_name'] != trim($_POST['user_name'])){
		 			$query = "SELECT 1 FROM accountinfo WHERE user_name = '". $user_name ."'";
		 			$result = mysqli_query($con, $query);
		 			if(empty($user_name))
		 				$_ERROR['empty_user_name'] = true;

		 			if($result != null and $result->num_rows != 0)
		 				$_ERROR['duplicate_user_name'] = true;
		 		}
 				
		 		if(empty($_ERROR)){
					$query = "UPDATE accountinfo SET user_name = '". $user_name ."', email = '". $email . "' WHERE accountinfo.user_id = " . $_SESSION['user_id'] . ";";
					$result = mysqli_query($con, $query);
					if($result != null){
						$_SESSION['user_name'] = $user_name;
						$_PAGE['profile_success'] = true;
					}
					else
						$_PAGE['profile_success'] = false;
					

					if($name != null and $_PAGE['profile_success'] === true){
						list($first, $last) = explode(' ', $name, 2); 
						$query = "UPDATE user SET first_name = '". $first ."', last_name = '". $last . "', description = '". $description . "' WHERE user.user_id = " . $_SESSION['user_id'] . ";";
						$result = mysqli_query($con, $query);
						if($result == null){
							$_PAGE['profile_success'] = false;
						}else
							$_PAGE['profile_success'] = true;

					}
					
				}

			}

			if(isset($_POST['save_tags'])){
				$_ERROR = [];				
				$tags = array();

				if(isset($_POST['tag'])){
					foreach($_POST['tag'] as $tag){
  						if($tag != '')
  							array_push($tags, mysqli_real_escape_string($con2, $tag));
					}
				}

				foreach($tags as $tag){
					$query = "INSERT INTO tags (tag_name) VALUES ('".$tag . "')";
					mysqli_query($con2, $query);
				}

				if(isset($_POST['category'])){
					foreach($_POST['category'] as $tag){
  						if($tag != ''){
  							array_push($tags, mysqli_real_escape_string($con2, $tag));
  							$query = "INSERT INTO tags (tag_name) VALUES ('".$tag . "')";
  							mysqli_query($con2, $query);
  						}
					}
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
  						array_push($tags, mysqli_real_escape_string($con, $tag));
				}
				$taglist = join("','",$tags);

				$query = "DELETE FROM favoritetags WHERE user_id = ". $_SESSION['user_id'] . " and tag_id IN ('$taglist')";
				$result = mysqli_query($con, $query);
				if($result == null or $result === false) 
					$_PAGE['tag_success'] = false;
				
				
				if(!isset($_PAGE['tag_success']))
					$_PAGE['tag_success'] = true;


			}

			$user = array();
			$query = "SELECT user.first_name, user.last_name, accountinfo.user_name, accountinfo.email, userpictures.image, user.description, 
					  address.address_id, address.street_address, address.city, address.state, address.zip_code, address.country
				  	  FROM user
				  	  INNER JOIN accountinfo ON user.user_id = accountinfo.user_id
				  	  LEFT JOIN userpictures ON user.picture_id = userpictures.picture_id
				  	  LEFT JOIN address ON user.main_address_id = address.address_id
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

			$_PAGE['user_name'] = $user['user_name'];
			$_PAGE['email'] = $user['email'];

			$_PAGE['current_pic'] = isset($user['image']) ? "<a href='profile.php'><img id='current_pic' src='data:image/jpg;base64,". base64_encode($row['image']) ."'/></a>" 
														: "<a href='profile.php'><img id='current_pic' src='images/icons/default_avatar.jpg'/></a>";
			$_SESSION['profile_pic'] = isset($user['image']) ? "<a id='profile_link' href='profile.php'><img src='data:image/jpg;base64,". base64_encode($row['image']) ."'/></a>" 
														: "<a id='profile_link' href='profile.php'><img src='images/icons/default_avatar.jpg'/></a>";
					
			
			$_PAGE['name'] = (isset($user['first_name']) && isset($user['last_name'])) ? $user['first_name'] . " " . $user['last_name'] : "";
			$_PAGE['description'] = (isset($user['description']))? $user['description'] : "";
			$_PAGE['main_address'] = (isset($user['address_id']))? $user['street_address']. "<br/>" . $user['city']. ", " . $user['state']. " " . $user['zip_code']. "<br/> " . $user['country'] : "";
			$_PAGE['main_address_id'] = (isset($user['address_id']))? $user['address_id'] : 0;

			$addresses = array();
		    $query = "SELECT addresslistings.address_id, address.street_address, address.city, address.state, address.zip_code, address.country
		    		  FROM addresslistings
		    		  INNER JOIN address ON addresslistings.address_id = address.address_id
				  	  WHERE addresslistings.user_id = ". intval($id) . " and addresslistings.address_id <> " . $_PAGE['main_address_id'] ;
		
			$result = mysqli_query($con, $query);
			if($result==null) ;
			else if($result->num_rows == null);
			else{
				while($row = $result->fetch_array()){
					$address = $row['street_address']. "<br/>" . $row['city']. ", " . $row['state']. " " . $row['zip_code']. "<br/> " . $row['country'];
					$addresses[$row['address_id']] = $address;					
				}
				mysqli_free_result($result);
			}

		 	$_PAGE['addresses'] = $addresses;

		}	
	

	}

	else{
		header('location: new_stuff.php');
	}


?>



<html>
<head>
<link href='http://fonts.googleapis.com/css?family=Titillium+Web:400,600,300' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="styles/header.css"/>
<link rel="stylesheet" type="text/css" href="styles/account.css"/>
<link rel="stylesheet" type="text/css" href="styles/sidemenu.css"/>
<link rel="stylesheet" type="text/css" href="styles/megadrawer.css"/>
<link rel="stylesheet" type="text/css" href="styles/shoppingcart_dropdown.css"/>
<link rel="stylesheet" type="text/css" href="styles/footer.css"/>
<link href="styles/jquery.comtagit.css" rel="stylesheet" type="text/css">
<link href="styles/tagit.ui-zendesk.css" rel="stylesheet" type="text/css">
<link rel="shortcut icon" href="images/icons/garagerads-20131102-favicon.ico">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="scripts/account_jquery.js"></script>
<script src="scripts/tag-it.js" type="text/javascript" charset="utf-8"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script>
    $(function()
    {
            $('#search').tagit(
            {
              allowSpaces: true,
              placeholderText: 'Add new tags ...',
              fieldName: 'tag'
            });         
    });
</script>
<script>	
	<?php if (isset($_PAGE["tag_success"])) { ?>
		$(document).ready(function(){
			$('#Tags').click();	
		});	
	<?php }  ?>	

	<?php if (isset($_PAGE["address_success"]) && $_PAGE["address_success"] != "") { ?>
		$(document).ready(function(){
			 $("html, body").animate({ scrollTop: $(document).height() }, "slow");
		});	
	<?php }  ?>	
</script>

</head>

<body>
<?php include('header.inc'); ?>
<?php include('sidemenu.inc');?>
<?php include('megadrawer.html');?>
<div class='main lightBoxBackground'>
<div class='container'>

	<div class='account_header'>
		<ul>
			<li class='header_selected' id='Settings'><a href='account.php'>Settings</a></li>
			<li><a href='#' id='Messages'>Messages</a></li>
			<li><a href='#' id='Buys'>Buys</a></li>
			<li><a href='#' id='Sells'>Sells</a></li>
			<li><a href='#' id='Tags'>Tags</a></li>
		</ul>
	</div>

	<div class='history'>
		<?php if (isset($_PAGE['tag_success']) and $_PAGE['tag_success'] === true) echo
			 "<p class='success'>Success: Tags updated successfully!</p>";
			  elseif (isset($_PAGE['tag_success']) and $_PAGE['tag_success'] === false) echo
			  "<p class='error'>Error: Error occurred during update.</p>";
		?>
		<form method='post' action=''>
		<div class='piclist'>

		</div>
		<input type='submit' id='update_tags' name='update_tags' class='save_button' value='Update'>
		</form>
	</div>
	<div class='add_tag'>
		<hr/>
		<form method='post' action=''>
		<div class='add_tag_div'>
			<h5>Add Custom Tags</h5>
			<p class='tag_box'><textarea name='tag[]' rows='1' maxlength='20' cols='20' placeholder='Tag'></textarea></p>
			<p><a id='add_tag' href=''>+ add tag</a></p>
		</div>
		<div class='add_tag_div'>
			<h5>Select Garager Categories</h5>
			<!--<p class='category_box'><textarea name='category[]' rows='1' maxlength='20' cols='20' placeholder='Category'></textarea></p> -->
			<table class='cat_listings'>	
				<tr>
					<td><input type="checkbox" name="category[]" value="ART">ART</input></td>
					<td><input type="checkbox" name="category[]" value="BOOKS">BOOKS</input></td>
					<td><input type="checkbox" name="category[]" value="CLOTHING">CLOTHING</input></td>
					<td><input type="checkbox" name="category[]" value="JEWELRY">JEWELRY</input></td>
					<td><input type="checkbox" name="category[]" value="FURNITURE">FURNITURE</input></td>
				</tr>
				<tr>					
					<td><input type="checkbox" name="category[]" value="HOME & GARDEN">HOME & GARDEN</input></td>
					<td><input type="checkbox" name="category[]" value="MUSIC">MUSIC</input></td>
					<td><input type="checkbox" name="category[]" value="SPORTS">SPORTS</input></td>
					<td><input type="checkbox" name="category[]" value="TECH">TECH</input></td>
					<td><input type="checkbox" name="category[]" value="TOYS">TOYS</input></td>
				</tr>
				<tr>
					<td><input type="checkbox" name="category[]" value="WHEELS">WHEELS</input></td>
					<td><input type="checkbox" name="category[]" value="FEATURED">FEATURED</input></td>
					<td><input type="checkbox" name="category[]" value="VINTAGE">VINTAGE</input></td>
					<td><input type="checkbox" name="category[]" value="COLLECTIBLE">COLLECTIBLE</input></td>
					<td><input type="checkbox" name="category[]" value="UNIQUE">UNIQUE</input></td>
				</tr>
				<tr>
					<td><input type="checkbox" name="category[]" value="MISCELLANEOUS">MISCELLANEOUS</input></td>
					<td><input type="checkbox" name="category[]" value="$1">$1</input></td>
				</tr>
			</table>
		</div>
		<input type='submit' name='save_tags' class='save_button' value='Add'>
		</form>
	</div>
	
	<div class='personal' id='personal'>
		<?php  
			  if (isset($_PAGE['profile_success']) and $_PAGE['profile_success'] === true) echo
			  "<p class='success'>Success: Profile updated successfully!</p>";
			  elseif (isset($_PAGE['photo_success']) and $_PAGE['photo_success'] === true) echo
			  "<p class='success'>Success: Photo updated successfully!</p>";
			  elseif (isset($_ERROR['empty_user_name'])) echo 
				"<p class='error'>Error updating profile: User name can not be blank!</p>";
			  elseif (isset($_ERROR['duplicate_user_name'])) echo 
				"<p class='error'>Error updating profile: User name already exists!</p>";
			  elseif (isset($_ERROR['invalid_email'])) echo 
				"<p class='error'>Error updating profile: Please enter a valid email!</p>";
			  elseif (isset($_ERROR['invalid_file_format'])) echo
				"<p class='error'>Error uploading photo: Invalid file format!</p>";
			  elseif ((isset($_PAGE['profile_success']) and $_PAGE['profile_success'] === false) or ((isset($_PAGE['photo_success']) and $_PAGE['photo_success'] === false)) ) echo
			  	"<p class='error'>Error updating profile: Please try again</p>";
			  else;
		?>
		
			<div class='profile'>	
				<h5>Profile Picture <a href=''>edit</a></h5>
				<div class='profile_pic'>
					<?php echo $_PAGE['current_pic']; ?>
					<form enctype='multipart/form-data' method='post' action=''>
						<input type='file'  accept='image/*'  name='photo' id='photo' value='' onchange='readURL(this)'/>
						<input name="MAX_FILE_SIZE" value="102400" type="hidden">
						<input type='submit' name='upload_photo' id='upload' class='upload_button' value='Upload Photo'/>
					</form>
				</div>
			<form method='post' action=''>
				<h5>Advanced Profile</h5>
				<div class='desc_div'><textarea class='description_box' name='description' rows='5'  maxlength='255' placeholder='Enter a description'><?php if ($_PAGE['description'] != "") echo $_PAGE['description']; ?></textarea></div>
				<input type='submit' name='submit' class='save_button' value='Save'>
			</div>
			<div class='account'>	
				<h5>Your Name <a href=''>edit</a></h5>
				<textarea id='name_box' name='name' rows='1' cols='30' maxlength='40' placeholder='First Last'><?php if ($_PAGE['name'] != "") echo $_PAGE['name']; ?></textarea>
				<p id='name_text'><?php echo $_PAGE['name']; ?></p>
				<hr/>
				<h5>Username <a href=''>edit</a></h5>
				<textarea id='username_box' name='user_name' rows='1' maxlength='40' cols='30' placeholder='Username'><?php if ($_PAGE['user_name'] != "") echo $_PAGE['user_name']; ?></textarea>
				<p id='user_name_text'><?php echo $_PAGE['user_name']; ?></p>
				<hr/>
				<h5>Your Email <a href=''>edit</a></h5>
				<textarea id='email_box' name='email' rows='1' cols='30' maxlength='40' placeholder='Email address'><?php if ($_PAGE['email'] != "") echo $_PAGE['email']; ?></textarea>
				<p id='email_text'><?php echo $_PAGE['email']; ?> </p>
				<hr/>
				<h5>Billing Info <a href=''>add card</a></h5>
				<div class='credit_card'> </div>
			</div>
		</form>
			<div class='connect'>
				<h5>Connect Accounts</h5>
				<p><img class='connect_img' src='images/icons/connect-facebook.png'/><a href='https://www.facebook.com/'> Connect with Facebook</a></p>
				<p><img class='connect_img' src='images/icons/twitter.png'/><a href='https://https://twitter.com//'> Connect with Twitter</a></p>
				<hr/>
				<p><a href='#' id='reset_link'> Reset your password</a></p>
				<?php 
					if ($_PAGE['password_success'] === true) echo 
						"<p class='success'>Success: Password update completed!</p>";
					elseif (isset($_ERROR['password mismatch'])) echo 
						"<p class='error'>Error: Password update failed. New passwords do not match</p>";
					elseif (isset($_ERROR['password unchanged'])) echo 
						"<p class='error'>Error: Password update failed. Old and new passwords are the same</p>";
					elseif (isset($_ERROR['incorrect password'])) echo 
						"<p class='error'>Error: Password update failed. Current password is incorrect</p>";
					else;
				?>
				<form id='reset' method='post' action=''>
					<input type='password' id='curr_pwd' name='curr_pwd' rows='1' size='25' maxlength='20' placeholder='Current password'></textarea>
					<input type='password' id='new_pwd1' name='new_pwd1' rows='1' size='25' maxlength='20' placeholder='New password'></textarea>
					<input type='password' id='new_pwd2' name='new_pwd2' rows='1' size='25' maxlength='20' placeholder='Re-type new password'></textarea>
					<input type='submit' name='change_password' class='change_button' value='Change Password'/>
				</form>
				<p><a href='#'> Email preferences</a></p>
				<p><a id='close_account' href='#'> Close Account</a></p>
			</div>
	</div>
	
	<div class='address' id='address'>
		<?php 
			if (isset($_ERROR['required_address_fields'])) echo 
				"<p class='error'>Error: Address update failed. Please fill out all required fields</p>";
		    elseif ($_PAGE['address_success'] === true) echo 
				"<p class='success'>Success: Address updated successfully!</p>";
			elseif ($_PAGE['address_success'] === false) echo 
				"<p class='error'>Error: Address update failed. Please try again</p>";
			else;
		?> 
		<div class='adress_listings'>
			<div class='address_record' id='main_address'>
			<form class='' method='post' action=''>
				<h5>Your Shipping Address</h5>
				<?php if ($_PAGE['main_address'] != "") echo
				"<p>" . $_PAGE['name'] . "</p>
				<p>" . $_PAGE['main_address'] . "</p>
				<input type='hidden' name='address' value='" . $_PAGE['main_address_id']. "'>
				<input type='submit' name='delete_main' id='' class='save_button' value='Delete Address'>"; ?>
			</form>
			<p class='warning'><small>By clicking delete, the address will also be removed from any sales associated</small></p>
			</div>
			<hr/>
			<div class='address_record' id='alternate_address'>
			<h5>Other Addresses</h5>
				<?php foreach ($_PAGE['addresses'] as $id => $address) {
						echo "<form class='' method='post' action=''>
								<p>" . $address . "</p>
								<input type='hidden' name='address' value='" . $id. "'>
								<input type='submit' name='delete_alternate' id='' class='save_button' value='Delete Address'>
								<input type='submit' name='set_main' id='' class='save_button' value='Make Default'>
						    	<p class='warning'><small>By clicking delete, the address will also be removed from any sales associated</small></p>
						    </form>";
					}
				?>
				
			</div>
		</div>
		<div class='new_address'>
			<form class='' method='post' action=''>
				<div id='address_row1' class='address_row'>
					<h5>Address <b>*</b></h5>
					<textarea id='street_box' name='street' rows='1' maxlength='70' placeholder='Street Address'></textarea>
				</div>
				<div id='address_row2' class='address_row'>
					<div id='apt'>
						<h5>Apartment/Suite/Floor</h5>
						<textarea id='apt_box' name='apt' rows='1' maxlength='30' placeholder=''></textarea>
					</div>
					<div id='city'>
						<h5>City <b>*</b></h5>
						<textarea id='city_box' name='city' rows='1' maxlength='30' placeholder='City'></textarea>
					</div>
				</div>
				<div id='address_row3' class='address_row'>
					<div id='state'>
						<h5>State/Province <b>*</b></h5>
						<textarea id='state_box' name='state' rows='1' maxlength='20' placeholder='State'></textarea>
					</div>
					<div id='zip'>
						<h5>Zip/Postal Code <b>*</b></h5>
						<textarea id='zip_box' name='zip' rows='1' maxlength='10' placeholder='Zip Code'></textarea>
					</div>
					<div id='country'>
						<h5>Country <b>*</b></h5>
						<textarea id='country_box' name='country' rows='1' maxlength='20' placeholder='Country'></textarea>
					</div>
				</div>
				<input type='submit' name='add_address' id='' class='add_button' value='Add Address'>
			</form>
		</div>
	</div>

	
	
</div>


<?php include('createSaleLightBox.inc');?>
<?php include('footer.html'); ?>
</div>
<?php include('shoppingcart_dropdown.php');?>
</body>
</html>

