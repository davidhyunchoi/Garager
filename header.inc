<?php
// header.inc template file
include('createSaleLightBox.inc');
?>

<div class="header"> 
	<div class="menu_home">
		<ul>
			<li class="menu"><a href="#" class="menu_link">&#9776;</a></li>
			<li class="home"><a href="GaragerHome.php">garager</a></li>
		</ul>
	</div>		
	<div class="explore">
		<ul>
			<li><a href="#" class="categories_link">CATEGORIES</a></li>
			<li><a href="new_stuff.php">NEW STUFF</a></li>
		</ul>
	</div>	
	<div class="login_signup">
       	<?php if(!isset($_SESSION['user_id'])){
		echo '<ul><li id="login"><a href="logInLightBox" id="log_in_link" class="lightbox_trigger">LOG IN</a></li>
            <li id="or2">&nbsp;or&nbsp;</li>
            <li id="signup"><a href="signUpLightBox" id="sign_up_link" class="lightbox_trigger">SIGN UP</a></li>
        </ul>' ;
		}
		?>
    </div>	
	<div class="link_list">
		<ul>
			<li  id='profile_link'><?php if(isset($_SESSION['profile_pic'])) { echo $_SESSION['profile_pic']; } ?><li>
			<li><a href="#" class="search_link"><img src="images/icons/Garager_searchbutton.png"/></a></li>
			    <li><a href="createSaleLightBox" class="lightbox_trigger"><img src="images/icons/Garager_salebutton.png"/></a>
                </li>
			<li><a href="#" class="shoppingcart_link"><img src="images/icons/Garager_cartbutton.png"/></a></li>
		</ul>
	<div id="search_wrap">
            <p class="label">Search Stuff</p>
            <a class="clear" onclick="$('#search').tagit('removeAll'); return false;" href="#">Clear Search</a> 
            <form id="searchform" action="search.php" method="get">
            
            <div id="cb">
            <input type="checkbox" id="perf" name="option" value="perfect" /><label for="perf">Partial Matches</label>
            </div>

            <ul id="search"></ul>   
            <a href="#" id="advsearch">Advanced Search >>></a>
            <div id="advanced">
            <input type="radio" id="useronly" name="type" value="user"/><label for="useronly">Users only</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="checkbox" id="name" name="option" value="name"/><label for="name">Name</label>
                <input type="checkbox" id="zip" name="option" value="zip"/><label for ="zip">Zip Code</label>
            <br/>   
            <input type="radio" id="itemgsaleonly" name="type" value="item"/><label for="itemgsaleonly">Items & Garager sale only</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="checkbox" id="users" name="option" value="user"/><label for="users">Users</label>
                <input type="checkbox" id="tags" name="option" value="tag"/><label for="tags">Tags</label>
                <input type="checkbox" id="cat" name="option" value="category"/><label for="cat">Category</label>
                <input type="checkbox" id="iszip" name="option" value="zip"/><label for="iszip">Zip Code</label>
                <input type="checkbox" id="isname" name="option" value="name"/><label for="isname">Name</label>
            </div>
            
            <div id="bot">
            <button id="enter">Enter Search</button>
            </form>
            <p class="desc">Search for Username, address, zipcode, category or tag. <br/>   
            Enter one or more options, separated by commas. Use cursor keys to edit.
            </p>    
            </div>
        </div>   
        <?php if(isset($_SESSION['user_id'])) echo "
        <div id='profile_menu'>
            <ul>
                <li><a href='profile.php'>View Profile</a></li>
                <li><a href='account.php'>Account Settings</a></li>
                <li><a id='log_out' href='#'>Log Out</a></li>
            </ul>
        </div>  ";
        ?> 
	</div>

</div>  
 
<?php
// End header.inc template file
?>

