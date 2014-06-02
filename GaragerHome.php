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
                Inner join onlinesale on onlinesale.item_id = shoppingcart.item_id 
                Inner join itemseller on itemseller.item_id = onlinesale.item_id
                Inner join customer.accountinfo on customer.accountinfo.user_id = itemseller.user_id
                Inner join customer.user on customer.user.user_id = itemseller.user_id
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
                  LEFT JOIN onlinesale ON onlinesale.item_id = shoppingcart.item_id
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
<meta charset="utf-8">
<title>Garager</title>
<link href="styles/style_home.css" rel="stylesheet" type="text/css">
<link href='http://fonts.googleapis.com/css?family=Titillium+Web:400,600,300,100' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="styles/flexslider.css" type="text/css">
<link href="styles/jquery.tagit.css" rel="stylesheet" type="text/css">
<link href="styles/tagit.ui-zendesk.css" rel="stylesheet" type="text/css">

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
<script src="scripts/tag-it.js" type="text/javascript" charset="utf-8"></script>
<script src="scripts/jquery.flexslider.js"></script>
<script type="text/javascript" src="scripts/home_jquery.js"></script>
<script type="text/javascript" src="scripts/common_jquery.js"></script>

<link rel="stylesheet" type="text/css" href="styles/header_homepage.css"/>
<link rel="stylesheet" type="text/css" href="styles/sidemenu.css"/>
<link rel="stylesheet" type="text/css" href="styles/signup.css" >
<link rel="stylesheet" type="text/css" href="styles/login.css" >
<link rel="stylesheet" type="text/css" href="styles/megadrawer.css"/>
<link rel="stylesheet" type="text/css" href="styles/footer_homepage.css"/>
<link rel="stylesheet" type="text/css" href="styles/itemLightBox.css"/>
<link rel="stylesheet" type="text/css" href="styles/reset_pwd.css"/>
<link rel="stylesheet" type="text/css" href="styles/shoppingcart_dropdown.css"/>
<link rel="stylesheet" type="text/css" href="styles/shoppingcart.css"/>



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
<!-- -->

</head>
<body>

<?php include('header_homepage.inc');?>
<?php include('sidemenu.inc');?>
<?php include('signup.inc');?>
<?php include('login.inc');?>

<?php
echo '
<div class="container">
        <section class="hero">
       <div class= "hero_slide">
       <div class= "flexslider">

                <ul class="slides">
           <li>
                                <img src="images/banners/Garager_Login_Home1.jpg"/>
                                <article class="headline">                                
                                <div>
                                <h2>BUY stuff.</h2>
                                Purchase items online or offline from around the corner or miles away.  Easy.  Fast.  Fun.</div><p>
                                <a href="signUpLightBox" class="article_button lightbox_trigger">Sign up for Garager</a>
                                </article>
           </li>

           <li>
                                <img src="images/banners/Garager_Login_Home2.jpg"/>
                                <article class="headline">                                
                                <div>
                                <h2>SELL stuff.</h2>
                                Sell all your earthly possessions – or maybe just one item - by reaching buyers outside a 3-block radius (although they’re invited, too!).</div><p>
                                <a href="signUpLightBox" class="article_button lightbox_trigger">Sign up for Garager</a>
                                </article>
           </li>

           <li>
                                <img src="images/banners/Garager_Login_Home4.jpg"/>
                                <article class="headline">                                
                                <div>
                                <h2>ENJOY stuff.</h2>
                                Get it done quickly and easily, so you can get out there and enjoy all the time you’ve saved using garager to buy and sell.</div><p>
                                <a href="signUpLightBox" class="article_button lightbox_trigger">Sign up for Garager</a>
                                </article>
           </li>
           <li>
                                <img src="images/banners/Garager_Login_Home2.jpg"/>
                    <article class="headline">                                
                        <div>
                        <h2>SEARCH stuff.</h2>
                              Find treasures in your own backyard.  Make it a garager day by visiting all the garage/yard/tag/estate sales in your neighborhood.</div><p>
                      <a href="signUpLightBox" class="article_button lightbox_trigger">Sign up for Garager</a>

                                  </article>
           </li>
           <li>
                                <img src="images/banners/Garager_Login_Home6.jpg"/>
                                <article class="headline">
                                <div>
                                <h2>BUILD community.</h2>
                                It’s about putting a face with a name. It’s about giving new life to old treasures. It’s about meeting your neighbors and buying and selling together.</div><p>
                                <a href="signUpLightBox" class="article_button lightbox_trigger">Sign up for Garager</a>
                                </article>
           </li>
           <li>
                                <img src="images/banners/Garager_Login_Home3.jpg"/>
                    <article class="headline">                                
                        <div>
                        <h2>MARKET stuff.</h2>
                              It’s a new way to sell. Get more people to come to your Saturday yard sale. Get a better price for your antique by selling it online. Do it all on garager.</div><p>
                      <a href="signUpLightBox" class="article_button lightbox_trigger">Sign up for Garager</a>

                                  </article>
           </li>
           </ul>
        </div>


        

<section class="sidekick" id="sidekick">
    <div id="sites">
                <div id="sites_row1">
                    <div>
                        <a id="howitworks" href="#"><img src="images/icons/how_it_works.png"/></a>
                        <a href="new_stuff.php"><img src="images/icons/buy_stuff.png"/></a>
                        <a id="SellStuff" href="#"><img src="images/icons/sell_stuff.png"/></a>
                        <a id="searchLink" href="#"><img src="images/icons/search.png"/></a>
                    </div>
                </div>
            <div id="sites_row2">
                    <div>
                        <a id="mapstuff" href="#"><img src="images/icons/map_stuff.png"/></a>
                        <a href="new_stuff.php"><img src="images/icons/explore.png"/></a>
                        <a id="AppGallery" href="#"><img src="images/icons/app_gallery.png"/></a>
                        <a id="pricing" href="#"><img src="images/icons/pricing.png"/></a>
                    </div>
            </div>    
          </div>
        </section>
   
</div>

<div class="lightBoxBackground">
</div>';

?>
<?php include('megadrawer.html');?>
<?php include('shoppingcart_dropdown.php');?>
<?php include('footer.html');?>


</body>
</html>
