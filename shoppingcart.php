<?php session_start(); 

include ('config.php');

/*End Simulate signed on user*/

$con = mysqli_connect($host,$username,$password,$db_name1); 
$con2 = mysqli_connect($host,$username,$password,$db_name2);

if (isSet ($_SESSION['user_id'])){
	$id = $_SESSION['user_id'];	
	//$id = 2;

	/* Queries to Customer */
	
	if (mysqli_connect_errno($con) || mysqli_connect_errno($con2)){
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}else{
		/* Get User values*/
		$user = array();
		$items = array();

		/* query the item picture from database */
		$query = "SELECT image, shoppingcart.item_id
				  FROM merchandisepictures
				  LEFT JOIN itempictures ON merchandisepictures.picture_id = itempictures.picture_id
				  LEFT JOIN shoppingcart ON shoppingcart.item_id = itempictures.item_id
				  WHERE shoppingcart.user_id = ". intval($id);

		$result = mysqli_query($con2, $query);
		if($result==null) $error = '<p>result is null<br>errormsg'.mysqli_error($con2).'</p>';
		else if($result->num_rows == null) $error = '<p>result row num is 0</p>';
		else{
			while($row = $result->fetch_array()){
				/*$items[] = $row['item_id'];*/
				$user[$row['item_id']] = array();
				$user[$row['item_id']]['image'] = isset($row['image']) ? "<a href='#'><img src='data:image/jpg;base64,". base64_encode($row['image']) ."'/></a>" 
														: "<a href='#'><img src='images/icons/default_avatar.jpg'/></a>";
			}
		}

		/* query the item name from database */
		$query = "SELECT name, shoppingcart.item_id
				  FROM shoppingcart
				  LEFT JOIN item ON item.item_id = shoppingcart.item_id
				  WHERE shoppingcart.user_id = ". intval($id);

        $result = mysqli_query($con2, $query);
		if($result==null) $error = '<p>result is null<br>errormsg'.mysqli_error($con2).'</p>';
		else if($result->num_rows == null) $error = '<p>result row num is 0</p>';
		else{
			while($row = $result->fetch_array()){
				$user[$row['item_id']]['item_name'] = $row['name'];
			}
        }


        
        /* query the seller name from database */
        
		$query = "SELECT shoppingcart.item_id, customer.accountinfo.user_name, customer.accountinfo.user_id, customer.userpictures.image
				From shoppingcart
				LEFT join item on item.item_id = shoppingcart.item_id 
				LEFT join itemseller on itemseller.item_id = item.item_id
				LEFT join customer.accountinfo on customer.accountinfo.user_id = itemseller.user_id
				LEFT join customer.user on customer.user.user_id = itemseller.user_id
				LEFT JOIN customer.userpictures on customer.user.picture_id = userpictures.picture_id

				  WHERE shoppingcart.user_id = ". intval($id);
		
/*
		$query = "SELECT image,shoppingcart.item_id, customer.accountinfo.user_name, customer.accountinfo.user_id, customer.user.picture_id
				From shoppingcart
				Inner join onlinesale on onlinesale.item_id = shoppingcart.item_id 
				Inner join itemseller on itemseller.item_id = onlinesale.item_id
				Inner join customer.accountinfo on customer.accountinfo.user_id = itemseller.user_id
				Inner join customer.user on customer.user.picture_id = customer.accountinfo.user_id
				WHERE shoppingcart.user_id = ". intval($id);
*/
        $result = mysqli_query($con2, $query);

		if($result==null) $error = '<p>result is null<br>errormsg'.mysqli_error($con2).'</p>';
		else if($result->num_rows == null) $error = '<p>result row num is 0</p>';
		else
		{
			while($row = $result->fetch_array())
			{
				$user[$row['item_id']]['seller_name'] = $row['user_name'];
				$user[$row['item_id']]['seller_id'] = $row['user_id'];
				$user[$row['item_id']]['seller_picture'] = isset($row['image']) ? "<a href='#'><img src='data:image/jpg;base64,". base64_encode($row['image']) ."'/></a>" 
														: "<a href='#'><img src='images/icons/default_avatar.jpg'/></a>";
			}
        }

        /* seler picture*/

        

        /* query the item price from database */
		$query = "SELECT price, shoppingcart.item_id
				  FROM shoppingcart
				  LEFT JOIN item ON item.item_id = shoppingcart.item_id
				  WHERE shoppingcart.user_id = ". intval($id);

        $result = mysqli_query($con2, $query);
		if($result==null) $error = '<p>result is null<br>errormsg'.mysqli_error($con2).'</p>';
		else if($result->num_rows == null) $error = '<p>result row num is 0</p>';
		else{
			while($row = $result->fetch_array()){
				$items[] = $row['item_id'];
				$user[$row['item_id']]['price'] = $row['price'];
			}
        }	
	}
}

?>



<!doctype html>
<html>
<head>
<link href='http://fonts.googleapis.com/css?family=Titillium+Web:400,600,300' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="styles/header.css"/>
<link rel="stylesheet" type="text/css" href="styles/sidemenu.css"/>
<link rel="stylesheet" type="text/css" href="styles/signup.css" >
<link rel="stylesheet" type="text/css" href="styles/login.css" >
<link rel="stylesheet" type="text/css" href="styles/megadrawer.css"/>
<link rel="stylesheet" type="text/css" href="styles/footer.css"/>
<link rel="stylesheet" type="text/css" href="styles/template.css"/>
<link rel="stylesheet" type="text/css" href="styles/itemLightBox.css"/>
<link rel="stylesheet" type="text/css" href="styles/reset_pwd.css"/>
<link rel="stylesheet" type="text/css" href="styles/shoppingcart_dropdown.css"/>
<link rel="stylesheet" type="text/css" href="styles/shoppingcart.css"/>
<link rel="stylesheet" type="text/css" href="styles/shipping_info.css"/>
<link rel="stylesheet" type="text/css" href="styles/billing_info.css"/>
<link href="styles/jquery.comtagit.css" rel="stylesheet" type="text/css">
<link href="styles/tagit.ui-zendesk.css" rel="stylesheet" type="text/css">
<link rel="shortcut icon" href="images/icons/garagerads-20131102-favicon.ico">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
<script src="scripts/common_jquery.js"></script>
<script src="scripts/tag-it.js" type="text/javascript" charset="utf-8"></script>
<script src="scripts/multisale.js"></script>
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
  
$(document).ready(function(){

  $('#advanced').hide();

  $('#advsearch').click(function()
  { 
        if ($('#advanced').is(':visible')) 
        {
            $('#advanced').slideUp();
        }
        else
        {
            $('#advanced').slideDown();
        }
  });
});

$(document).ready(function(){

  // $('#useronly').attr("checked", true);  
  // $('#itemgsaleonly').attr("checked", true);

   $('#useronly').click(function () {
   var x = $('#useronly:checked').length;
      if (x > 0)
      {
          $('#itemgsaleonly').attr("checked", false);

          $('#name').attr('disabled', false);
          $('#zip').attr('disabled', false);  

          $('#users').attr('disabled', true);
          $('#users').attr("checked", false);

          $('#tags').attr('disabled', true);
          $('#tags').attr("checked", false);

          $('#cat').attr('disabled', true);
          $('#cat').attr("checked", false);

          $('#iszip').attr('disabled', true);
          $('#iszip').attr("checked", false);

          $('#isname').attr('disabled', true);  
          $('#isname').attr("checked", false);
  
      }
   });
});

$(document).ready(function(){
   $('#itemgsaleonly').click(function () {
   var x = $('#itemgsaleonly:checked').length;
      if (x > 0)
      {
          $('#useronly').attr("checked", false);

          $('#name').attr('disabled', true);
          $('#name').attr("checked", false);

          $('#zip').attr('disabled', true);
          $('#zip').attr("checked", false);

          $('#users').attr('disabled', false);
          $('#tags').attr('disabled', false);
          $('#cat').attr('disabled', false);
          $('#iszip').attr('disabled', false);
          $('#isname').attr('disabled', false);         
      }
   });
});

$(document).ready(function(){
   $('.clear').click(function () {

        $('#useronly').attr("checked", false);
        $('#itemgsaleonly').attr("checked", false);

        $('#name').attr('disabled', false);
        $('#name').attr("checked", false);

        $('#zip').attr('disabled', false);
        $('#zip').attr("checked", false); 

        $('#users').attr('disabled', false);
        $('#users').attr("checked", false);
 
        $('#tags').attr('disabled', false);
        $('#tags').attr("checked", false);

        $('#cat').attr('disabled', false);
        $('#cat').attr("checked", false);

        $('#iszip').attr('disabled', false);
        $('#iszip').attr("checked", false);

        $('#isname').attr('disabled', false);  
        $('#isname').attr("checked", false);

   });
});
</script>
</head>

<body>



<?php include('header.inc');?>
<?php include('sidemenu.inc');?>
<?php include('signup.inc');?>
<?php include('login.inc');?>
<?php include('reset_pwd.inc');?>
<?php include('shipping_info.inc');?>
<?php include('billing_info.inc');?>

<div class="shopping_cart_page">
<?php 
if(isSet($items)){
	for($index = 0; $index < count($items); $index++){

		echo '<!--Checkout Items-->
        
		<div class="itemcheckoutlist" id="itemcheckoutlist'.$items[$index].'">
		<!--Item information box-->
		<div class="itemInfo">
			<div class="orderFrom">';
					if(!isSet($user[$items[$index]]['seller_picture'])) echo $error; else echo $user[$items[$index]]['seller_picture'];
					echo '<span class="orderFromText">Order From <a href="profile.php?user='.$user[$items[$index]]['seller_id'].'">'.$user[$items[$index]]['seller_name'].'</a></span>
				</div>
				<div class="orderImage1">';
		if(!isSet($user[$items[$index]]['image'])) echo $error; else echo $user[$items[$index]]['image'];
	  		echo '</div>
				<div class="remove">
					<form class="Remove_form" action="" method="post">
					<input type="hidden" name="item_id" id="" value="'.$items[$index].'">
					<input id="RemoveButton" type="submit" name="submit" value="REMOVE">
					</form>
				</div>
				<div class="orderDetail">
					<div class="itemName1">'.$user[$items[$index]]['item_name'].'</div>
					<div class="itemPrice1">
						$'.$user[$items[$index]]['price'].'
					</div>
					<hr width="95%">
				<div class="itemShipment">
					Available for (select one):
				</div>
				<div class="itemShipmentCheckbox">
					<form action="">
					<input type="radio" name="shipChoice"> Standarding shipping to U.S. and Canada + $6
				</br>
					<input type="radio" name="shipChoice"> Pick Up
					</form>
					<p>
				</div>
				<hr width="95%">
				<div class="addMessages">
					<input class="addMsg" type="checkbox" name="'.$items[$index].'"> Add a message
				</div>
				<span class="textarea1" id="Msg'.$items[$index].'"><textarea rows="2" cols="40" placeholder="Add a Message..."></textarea></span>
				</div>
			</div>
			<!--end-->
			<!--checkout order box-->
			<div class="itemOrder" id="itemOrder'.$items[$index].'">
				<div class="itemOrderTitle">
					<p>Your Order</p>
				</div>
				<hr width="90%">
				<div class="itemOrderPrice">
					<table>
						<tr><td id="ItemTotal">Item Total</td><td id="ItemTotalNum">$'.$user[$items[$index]]['price'].'</td></tr>
						<tr><td id="PickUp">Pick Up</td><td id="PickUpNum">$</td></tr>
						<tr><td id="EstimatedTax">Estimated Tax</td><td id="EstimatedTaxNum">$</td></tr>
					</table>
				</div>
					<hr width="90%">
				<div class="itemOrderTotalBox">
					<div class="itemOrderTotal">
						Order Total
					</div>
					<div class="itemOrderTotalPrice">
						<span id="price">$</span>
					</div>
				</div>
				<div class="BuyNow'.$items[$index].'" id="">
				<form class="BuyNow_form" action="" method="post" name="'.$items[$index].'">
				<input type="hidden" name="item_id" id="" value="'.$items[$index].'">
				<input id="BuyNowButton" type="submit" name="submit" value="BUY NOW">
				</form>
				</div>
			</div>
			<div class="h_line1" id="h_line1'.$items[$index].'"></div>

			<!--end-->
		</div>
				<!--end-->';

	} 
}
else{

	echo '<h1>No items in your shoppingcart</h1>';
}

?>
</div>
<?php include('megadrawer.html');?>
<?php include('shoppingcart_dropdown.php');?>
<?php include('footer.html');?>
<div class="lightBoxBackground">
</div>

</body>
</html>


