<?php session_start();
if (!isset($_SESSION['user_id'])){
header('location: new_stuff.php');}?>
<!DOCTYPE html>
<html>
<head>
<link href='http://fonts.googleapis.com/css?family=Titillium+Web:400,600,300' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="styles/header.css"/>
<link rel="stylesheet" type="text/css" href="styles/sidemenu.css"/>
<link rel="stylesheet" type="text/css" href="styles/megadrawer.css"/>
<link rel="stylesheet" type="text/css" href="styles/footer.css"/>
<link rel="stylesheet" type="text/css" href="styles/template.css"/>
<link rel="stylesheet" type="text/css" href="styles/shoppingcart_dropdown.css"/>
<link rel="stylesheet" type="text/css" href="styles/garagerpage.css"/>
<link rel="stylesheet" type="text/css" href="itemDescriptionLightBox.css"/>
<link href="styles/jquery.comtagit.css" rel="stylesheet" type="text/css">
<link href="styles/tagit.ui-zendesk.css" rel="stylesheet" type="text/css">
<link rel="shortcut icon" href="images/icons/garagerads-20131102-favicon.ico">
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
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
<?php include('megadrawer.html');?>


 
<div class="bar">
	<div class="para1">
		<form id="paraform">1. Description of sale -Enter info about your sale.<span id="cancel">CANCEL</span><br>
			<hr class="line">
			<div class="para1col1">
			<span class="greytext">Title of Garager Sale(for multiple items only)</span><br>
			<input class="texttitle" id="texttest" type="text" name="saletitle" value=""><br>
			<textarea class="textdescription" name="saledescription" form="paraform" id="textarea1">Enter your sale description here.</textarea>
		<!--</form>-->
		</div>
	</div>
	<div class="para2">
		<!--<form id="para2form" form="para1form">-->
		2. Location Details - Enter location,date and time. &nbsp;&nbsp;
			<span class="skiptwo"><input type="checkbox" name="skipstep2" id="checkskip">Skip Step - I"m selling my stuff online only&nbsp;&nbsp;&nbsp;</span><br>	
		<hr class="line">
		<div class="innerpara2">
	   	<div class="pcol1">
	   		 <div class="row1">
     		 	<span class="greytext">Address</span><br>
    		 	<input class= "texttitle" type="text" name="address"><br>
    		 </div>
    		 <div class="prow2">
    		 	<div class="row2col1">
     		 		<span class="greytext">Apartment/Suit/Floor</span></br>
     				<input class="apart" type="text" name="apartment">
     			</div>
     			<div class="row2col2">
     				<span class="greytext">City</span><br>
     				<input class="city" type="text" name="city">
     			</div>
    		</div>
    		<div class="prow3">
    			<div class="row3col1">
    				<span class="greytext">State/Province</span></br>
     				<input class="apart" type="text" name="state">
    			</div>
    			<div class="row3col2">
    				<span class="greytext">Zip/Postal Code</span></br>
     				<input class="city" type="text" name="zipcode">
    			</div>
    		</div>
	    </div>

	    <div class="pcol2">
	    	<div class="col2col1">
	    		<span class="greytext">Start Date</span><br>
	    		<input type="date" class="col2text" value="" name="startdate"><br><br>
	    		<span class="greytext">Time Start</span><br>
	    		<input type="time" class="col2text" value="From" name="starttime"><br><br>
	    	</div>
	    	<div class="col2col2">
	    		<span class="greytext">End Date</span><br>
	    		<input type="date" class="col2text" value="" name="enddate"><br><br>
	    		<span class="greytext">Time End</span><br>
	    		<input type="time" class="col2text" value="To" name="endtime"><br>
	    	</div>	
	    </div>
	   </form>
	</div>
	</div>
	<div class="para3" id="para3test">
		<?php include('itemDescription.inc');?>
		
		<div class="addImageTitle">
			<p class="uploadTitle">3. Add Images</p>
			<p class="uploadSubTitle">- Add up to 4 images per item, edit the item details and add the next item.</p>	
		</div>
		<hr class="line">
		<div class="contentdynamic" id="dynamic">
		<div class="upload" >
			
			<div class="dragandrop" id="draganddrophandler">
			<img id="upload2" src="images/icons/drag.png" draggable="true" ondragstart="drag(event)" width="170" height="170"><br>
			</div>
			<form id="upload_image" enctype="multipart/form-data" action="upload.php" method="post">
				<img class="buttonclick" src="images/icons/selectfile.png" id="selectFile" style="cursor:pointer" />
				<input  id="selectButton" type="file" name="image" style="display:none" />
				<img class="buttonclick" src="images/icons/upload.png" id="uploadPic" style="cursor:pointer" />
				<input id="image_upload" type="button" name="submit" style="display:none" />
			</form>
		</div>
		<div class="uploadsmall" ondrop="drop(event)" ondragover="allowDrop(event)">
			<div class="itemTitle">
				<p class="itemName">ITEM 1</p>
				<p class="deleteButton" id="deletediv">DELETE</p>
			</div>

			<div class="smallPics">
				<div class="singlePic">
					<img id="small1" src="" draggable="true" ondragstart="drag(event)">
					<div class="itemBar">
						<p class="leftCursoro" style="cursor:pointer"> < </p>
						<p class="rightCursoro" style="cursor:pointer"> > </p>
						<img class="deleteItemo" src="images/icons/deleteItem.png" style="cursor:pointer">
					</div>
				</div>
				<div class="singlePic">
					<img id="small2" src="" draggable="true" ondragstart="drag(event)">
					<div class="itemBar">
						<p class="leftCursoro" style="cursor:pointer"> < </p>
						<p class="rightCursoro" style="cursor:pointer"> > </p>
						<img class="deleteItemo" src="images/icons/deleteItem.png" style="cursor:pointer">
					</div>
				</div>
				<div class="singlePic">
					<img id="small3" src="" draggable="true" ondragstart="drag(event)">
					<div class="itemBar">
						<p class="leftCursoro" style="cursor:pointer"> < </p>
						<p class="rightCursoro" style="cursor:pointer"> > </p>
						<img class="deleteItemo" src="images/icons/deleteItem.png" style="cursor:pointer">
					</div>
				</div>
				<div class="singlePic">
					<img id="small4" src="" draggable="true" ondragstart="drag(event)">
					<div class="itemBar">
						<p class="leftCursoro" style="cursor:pointer"> < </p>
						<p class="rightCursoro" style="cursor:pointer"> > </p>
						<img class="deleteItemo" src="images/icons/deleteItem.png" style="cursor:pointer">
					</div>
				</div>
			</div>
			<div class="editBar">
				<img class="lightbox_trigger1" href="itemDescriptionLightBox" src="images/icons/editDetails.png" id="editDetails" style="cursor:pointer" /><br>
				<!--<img src="images/icons/addNextItem.png" id="addNextItem" style="cursor:pointer" />-->
				<input  class="addNextButton" type="button" id="addNextItem" name="addnext" value="ADD NEXT ITEM">
			</div>
		</div>
	</div>
</div>

	<div class="subbutton">
			<input id="button2" type="button" name="submittotal" value="Review and Publish">
	</div>

</div>

<?php include('footer.html');?>
<?php include('shoppingcart_dropdown.php');?>
<div class="lightBoxBackground">
</div>

</body>
</html>