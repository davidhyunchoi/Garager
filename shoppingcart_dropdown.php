<?php

include ('config.php');

/*End Simulate signed on user*/

$con = mysqli_connect($host,$username,$password,$db_name1); 
$con2 = mysqli_connect($host,$username,$password,$db_name2);

if (isSet ($_SESSION['user_id']))
{
	$id = $_SESSION['user_id'];	
	//$id = 2;

	/* Queries to Customer */
	
	if (mysqli_connect_errno($con) || mysqli_connect_errno($con2))
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	else
	{
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
		
		else
		{
			while($row = $result->fetch_array())
			{
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
		else
		{
			while($row = $result->fetch_array())
			{
				$user[$row['item_id']]['item_name'] = $row['name'];
			}
        }

        /* query the seller name from database */
		$query = "SELECT shoppingcart.item_id, customer.accountinfo.user_name, customer.accountinfo.user_id
				From shoppingcart
				LEFT join item on item.item_id = shoppingcart.item_id 
				LEFT join itemseller on itemseller.item_id = item.item_id
				LEFT join customer.accountinfo on customer.accountinfo.user_id = itemseller.user_id

				  WHERE shoppingcart.user_id = ". intval($id);


        $result = mysqli_query($con2, $query);

		if($result==null) $error = '<p>result is null<br>errormsg'.mysqli_error($con2).'</p>';
		else if($result->num_rows == null) $error = '<p>result row num is 0</p>';
		else
		{
			while($row = $result->fetch_array())
			{
				$user[$row['item_id']]['seller_name'] = $row['user_name'];
				$user[$row['item_id']]['seller_id'] = $row['user_id'];
			}
        }

        /* query the item price from database */
		$query = "SELECT price, shoppingcart.item_id
				  FROM shoppingcart
				  INNER JOIN item ON item.item_id = shoppingcart.item_id
				  WHERE shoppingcart.user_id = ". intval($id);

        $result = mysqli_query($con2, $query);
		if($result==null) $error = '<p>result is null<br>errormsg'.mysqli_error($con2).'</p>';
		else if($result->num_rows == null) $error = '<p>result row num is 0</p>';
		else
		{
			while($row = $result->fetch_array())
			{
				/**/
				$items[] = $row['item_id'];
				/**/
				$user[$row['item_id']]['price'] = $row['price'];
			}
        }	
	}
}

?>

<?php 
echo '
<div id="shopcart_wrap">
    	<div id="checkout_item_list" style="overflow:scroll">';
if(isSet($items))
{
	for($index = 0; $index < count($items); $index++)
	{
		echo '
	    	<div class="checkout_item">
		    	<div class="orderImage">';
		    	if(!isSet($user[$items[$index]]['image'])) echo $error; else echo $user[$items[$index]]['image'];
	  			echo '
		    	</div>
		    	<div class="item_name">'.$user[$items[$index]]['item_name'].'</div>
		    	<div class="seller_name"><a href="profile.php?user='.$user[$items[$index]]['seller_id'].'">'.$user[$items[$index]]['seller_name'].'</a></div>
		    	<div class="item_price">$'.$user[$items[$index]]['price'].'</div>
		    	<div class="h_line"></div>
	        </div>';	
    }
}
    	else
{
	echo '<h1>Add some items in your shoppingcart</h1>';
}
echo '
		</div>
		<a id="closeCart" href="#">close
		</a>
    	<a href="shoppingcart.php"><button id="checkout" type="submit">Checkout</button></a>
</div>';

?>