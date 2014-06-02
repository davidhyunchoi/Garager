<?php session_start();

include('config.php');
$con = mysqli_connect($host,$username,$password,$db_name1); 
$con2 = mysqli_connect($host,$username,$password,$db_name2);

?>

<link rel="stylesheet" type="text/css" href="styles/itemLightBox.css"/>

<div id="itemLightBox" class="lightBox">
  <div class="left"> 


    <script>
      
      $('.bpic').click(function(){
      var src = $(this).attr('src');
      
      $('#topPic').attr('src',src);

      });

      $('#folllowSeller').click(function(){
      var user = $('#sellerName').children('p:first').text();
      var linkText = $(this).text();
      $.post('follow.php',{'user_name': user},function(data){
      $('#folllowSeller').animate("normal", function() {
      if (linkText == 'Follow') $('#folllowSeller').children().first().text('Unfollow');
        		else $('#folllowSeller').children().first().text('Follow');
        	});
		}).error(function(){
        	//$('.follow').after("<p style='color:red;font-size:10px;'>Error occurred. Please try again!</p>");
        	alert("Error occurred. Please try again!");
		});
		});

	  
	  $('#addToCart').click(function(){
	      var item = $(this).attr('value');
	      var linkText = $(this).text();
	      var cartText = $(this).text();
	      $.post('addToCart.php',{'item_id' : item},function(data){

	         if(linkText == 'Add to Cart') $('#addToCart').children('p').first().text('In Cart');
	      }
	      );

	  });

    </script>
    
    <?php
      
  $id = $_GET['item_id'];
          
	  $query ="SELECT itempictures.picture_id, image FROM itempictures, item, merchandisepictures WHERE itempictures.item_id =".intval($id)." AND itempictures.picture_id = merchandisepictures.picture_id LIMIT 1";
	  

          $result = mysqli_query($con2, $query);
          if($result->num_rows == null){
	  }
	  else{
	    
	    while($row = $result->fetch_array()){
	      echo "<a><img id='topPic' class='conright-expics' src='data:image/jpg;base64,". base64_encode($row['image']) ."' title= '". $row['name'] ."' /></a>";
	    }
	    
	  }

	  mysqli_free_result($result);
	  
	  $query ="SELECT itempictures.picture_id, image FROM itempictures, merchandisepictures WHERE itempictures.item_id =".intval($id)." AND itempictures.picture_id = merchandisepictures.picture_id ";
	  
          $result = mysqli_query($con2, $query);
          if($result->num_rows == null){
	  }
	  else{
	    echo"<div class='bottomPics'>";
	    while($row = $result->fetch_array()){
	      echo "<img class='bpic' src='data:image/jpg;base64,". base64_encode($row['image']) ."' title= '". $row['name'] ."' />";
	    }
	    echo"</div></div>";
	  }
	  
       
    ?>
    
    

    <div class="middle">
      <div class="itemTitle">
	
	<?php 
	    
	    $id = $_GET['item_id'];
		   
		   $query = "SELECT item.name, item.description, item.price FROM item WHERE item.item_id =". intval($id);

		   $result = mysqli_query($con2, $query);
		   
         	   if($result->num_rows == null){}
		   else{
		     
		     while($row = $result->fetch_array()){
		       echo "<p><b>".$row['name']."</b></p>";
		       echo "<p><b>$".$row['price']."</b></p></div>";
		       echo "<div id= '".$id."' class='itemDescription'>";
		       echo "<p>".$row['description']."</p></div>";  
		     }		
       
		   }
	       
	?>
	
	
	<div class="itemShare">
	  <p>Share</p>
	  <img src="images/icons/share_facebook.png">
	    <img src="images/icons/share_twitter.png">
	      <img src="images/icons/share_icon.png">
                
                <?php

                  $pic_id = $_GET['item_id'];
                  $query = "SELECT 0 FROM favoriteitem WHERE user_id=".$_SESSION['user_id']." AND item_id=".$pic_id;
                  $result = mysqli_query($con2,$query);
                  if($result != null){
			echo "<p id='fave'>Fave</p>";
                    if($result->num_rows == 0)
                      echo "<img id='fave_img' class='fave_image' src='images/icons/favorites_icon.png'/>";
                    else 
                      echo "<img id='unfave_img' class='fave_image' src='images/icons/fave.png'/>";
                  }         

                ?>
		</div>
	      </div>
	      <div class="right">
		<div class="profile">

		  <?php
		     
		     $id = $_GET['item_id'];
		     
		     $query = "SELECT customer.user.user_id, customer.accountinfo.user_name, customer.userpictures.image, customer.userpictures.caption,customer.address.street_address, customer.address.city, customer.address.state, customer.address.country, customer.address.zip_code
FROM merchandise.item 
INNER JOIN itemseller ON itemseller.item_id = item.item_id
INNER JOIN customer.user ON itemseller.user_id = customer.user.user_id 
INNER JOIN customer.accountinfo ON customer.accountinfo.user_id = customer.user.user_id
LEFT JOIN customer.userpictures ON customer.user.picture_id = customer.userpictures.picture_id 
LEFT JOIN customer.address ON customer.user.main_address_id = customer.address.address_id WHERE item.item_id=". intval($id);

		     $result = mysqli_query($con2, $query);
		   

         	   if($result->num_rows == null){}
		   else{
		     
		     while($row = $result->fetch_array()){
		       echo "<a href='profile.php?user=".$row['user_id']."'><img id='sellerPhoto' src='data:image/jpg;base64,". base64_encode($row['image']) ."' title= '". $row['user_name'] ."' /></a>"; 
		       
		       	if (isset($_SESSION['user_id']) and  $_SESSION['user_id']!= $row['user_id']){
									
					$query2 = "SELECT 0 FROM following WHERE follower_user_id=".$_SESSION['user_id']." AND following_user_id=".$row['user_id']."";
					$result2 = mysqli_query($con,$query2);
					if($result2 != null){
						if($result2->num_rows == 0)
							echo "<a id='folllowSeller'><p>Follow</p></a>";
						else 
							echo "<a id='folllowSeller'><p>Unfollow</p></a>";
					}
				}

		       echo "<a id='sellerName'><p>".$row['user_name']."</p></a>";
		       echo "<p id='sellerAddress'>".$row['city'].", ".$row['state']."</p>";
		       echo "<p id='sellerZip'>".$row['zip_code']."</p>";
		     }		
       
		   }



		     ?>

		  <a id="sendMessage" href="#" ><img src="images/icons/send_message.png"><p>Message Seller</p></a>
		</div>
	<?php
		echo "<div class='garageSale'>";
			
		$query ="SELECT sale_type FROM item WHERE item_id =".$_GET['item_id'];
		
		$result = mysqli_query($con2,$query); 	

		while($row = $result->fetch_array()){
			if($row['sale_type'] == "online"){
				echo"<p id='OnOrOff'>This item is available for purchase at an online sale only.</p>";
			}
			else if($row['sale_type'] == "offline"){
				echo"<p id='OnOrOff'>This item is available for purchase at an offline sale only.</p>";
			}
			else{
				echo"<p id='OnOrOff'>This item is available for purchase both online and offline.</p>";
			}

		
		}
		
	
		  

		  
		     $query = "SELECT 1 FROM shoppingcart WHERE user_id =".$_SESSION['user_id']." AND item_id=".$_GET['item_id'];
		     $result = mysqli_query($con2, $query);
		     if($result->num_rows > 0){
		       echo ' <a id="addToCart" value="'.$_GET['item_id'].'" href="#"><p>In Cart</p></a>';
		     }
		     else{
		       echo ' <a id="addToCart" value="'.$_GET['item_id'].'" href="#"><p>Add to Cart</p></a>';
		     }

		$query ="SELECT garagersale_id FROM garagersalelistings WHERE item_id =".$_GET['item_id'];
		
		$result = mysqli_query($con2,$query); 	

		while($row = $result->fetch_array()){
		  
		  echo "<a id='seeGaragerSale' href='GaragerSale.php?gsale_id=".$row['garagersale_id']."'><p>See Garager Sale</p></a>";
		}

		?>

		</div>
	      </div>
	    </div>
	    
	    <?php ?>
