<?php
// sidemenu.inc template file
?>
<div id="sidemenu">
<input type="text" name="search" id="menu_search" value="Search">
<span id="search_button_span"><img id="search_button" src="images/icons/search_icon.png"/></span>
 <ul id="nav">
  <li class="topics">
    <a >CATEGORIES</a>
    <ul>
		<li class="subTopics">
			<a class="unselected">ART &nbsp;&nbsp;&nbsp; > </a>
			<ul id='Art'>
				<li><a>Photography</a></li>
				<li><a>Sculpture</a></li>
				<li><a>Crafts</a></li>
				<li><a>Collectible</a></li>
			</ul>
        </li>
		<li class="subTopics">
			<a class="unselected">BOOKS &nbsp;&nbsp;&nbsp; > </a>
			<ul id='Books'>
				<li><a>Art Books</a></li>
				<li><a>Cook Books</a></li>
				<li><a>Fiction</a></li>
				<li><a>Non-Fiction</a></li>
				<li><a>Collectible</a></li>
			</ul>
        </li>
		<li class="subTopics">
			<a class="unselected">CLOTHING &nbsp;&nbsp;&nbsp; > </a>
			<ul id='Clothing'>
				<li><a>Baby & Child</a></li>
				<li><a>Mens</a></li>
				<li><a>Women</a></li>
				<li><a>Accessories</a></li>
				<li><a>Vintage</a></li>
			</ul>
        </li>
		<li class="subTopics">
			<a class="unselected">JEWELRY &nbsp;&nbsp;&nbsp; > </a>
			<ul id='Jewelry'>
				<li><a>Watches</a></li>
				<li><a>Rings</a></li>
				<li><a>Earrings</a></li>
				<li><a>Bracelets & Necklaces</a></li>
				<li><a>Brooches & Pins</a></li>
				<li><a>Mens</a></li>
				<li><a>Vintage</a></li>
			</ul>
        </li>
		<li class="subTopics">
			<a class="unselected">FURNITURE &nbsp;&nbsp;&nbsp; > </a>
			<ul id='Furniture'>
				<li><a>Tables</a></li>
				<li><a>Chairs</a></li>
				<li><a>Couches</a></li>
				<li><a>Desks</a></li>
				<li><a>Bookcases</a></li>
				<li><a>Beds</a></li>
				<li><a>Trunks</a></li>
				<li><a>Sideboards</a></li>
				<li><a>Armories</a></li>
				<li><a>Lighting</a></li>
				<li><a>Rugs & Flooring</a></li>
				<li><a>Vintage</a></li>
			</ul>
        </li>
		<li class="subTopics">
            <a class="unselected">HOME & GARDEN &nbsp;&nbsp;&nbsp; > </a>
            <ul id='Home_and_Garden'>
                <li><a>Kitchen</a></li>
                <li><a>Bath</a></li>
                <li><a>Dining & Bar</a></li>
                <li><a>Decor</a></li>
                <li><a>Outdoor</a></li>
                <li><a>Garden</a></li>
                <li><a>Pets</a></li>
                <li><a>Tools</a></li>
                <li><a>Vintage</a></li>
            </ul>
        </li>
		<li class="subTopics">
            <a class="unselected">MUSIC &nbsp;&nbsp;&nbsp; > </a>
            <ul id='Music'>
                <li><a>Sheet & Books</a></li>
                <li><a>Instruments</a></li>
                <li><a>Accessories</a></li>
                <li><a>Collectible</a></li>
            </ul>
        </li>
		<li class="subTopics">
            <a class="unselected">SPORTS &nbsp;&nbsp;&nbsp; > </a>
            <ul id='Sports'>
                <li><a>Equipment</a></li>
                <li><a>Wearables</a></li>
                <li><a>Accessories</a></li>
                <li><a>Collectible</a></li>
            </ul>
        </li>
        <li class="subTopics">
            <a class="unselected">TECH &nbsp;&nbsp;&nbsp; > </a>
            <ul id='Tech'>
                <li><a>Computer</a></li>
                <li><a>Games</a></li>
                <li><a>Mobile</a></li>
                <li><a>Photo & Video</a></li>
                <li><a>Stereo & Radio</a></li>
                <li><a>TV</a></li>
                <li><a>Vintage</a></li>
            </ul>
        </li>
        <li class="subTopics">
            <a class="unselected">TOYS &nbsp;&nbsp;&nbsp; > </a>
			<ul id='Toys'>
                <li><a>Arts & Crafts</a></li>
                <li><a>Games</a></li>
                <li><a>Dolls</a></li>
                <li><a>Electronics</a></li>
                <li><a>Electronics</a></li>
                <li><a>Riding Toys</a></li>
                <li><a>Collectible</a></li>
                <li><a>Other</a></li>
            </ul>
        </li>
        <li class="subTopics">
			<a class="unselected">WHEELS &nbsp;&nbsp;&nbsp; > </a>
            <ul id='Wheels'>
                <li><a>Vehicles</a></li>
                <li><a>Bikes</a></li>
                <li><a>Motorbikes</a></li>
                <li><a>Non-motorized</a></li>
                <li><a>Skates</a></li>
                <li><a>Collectible</a></li>
            </ul>
        </li>
		<li class="subTopics"><a href='new_stuff.php'>NEW STUFF</a></li>
		<li class="subTopics"><a>FEATURED</a></li>
		<li class="subTopics"><a>VINTAGE</a></li>
		<li class="subTopics"><a>COLLECTIBLE</a></li>
		<li class="subTopics"><a>UNIQUE</a></li>
		<li class="subTopics"><a>MISCELLANEOUS</a></li>
		<li class="subTopics"><a>$1</a></li>		
    </ul>
  </li>
  <li class="topics">
		<a href="garagerPageSetup.php">CREATE GARAGER SALE</a>
  </li>
  <li class="topics">
		<a>HOW IT WORKS</a>
        <ul>
			<li class="subTopics"><a href="GaragerHome.php#sites">Video</a></li>
        </ul>
  </li>
  <li class="topics">
        <a href="GaragerHome.php#sites">MAP STUFF</a>
  </li>

  <?php if(isset($_SESSION['user_id'])){
            echo  '<li class="topics">
                   <a href="account.php">ACCOUNT</a>
                   </li>';
        }
  ?> 
 
  <li class="topics">
        <a href="shoppingcart.php">CHECKOUT</a>
  </li>
  <li class="topics">
        <a href="contact.php">CONTACT</a>
  </li>
  
    <?php if(isset($_SESSION['user_id'])){
            echo '<li class="topics">
                    <a id="logout">LOG OUT</a>
                  </li>';
        } 
    ?>    
 </ul>
</div>

<?php
// End sidemenu.inc template file
?>