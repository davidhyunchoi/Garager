<?php session_start();
if (!isset($_SESSION['user_id'])){
header('location: new_stuff.php');}?>
<!DOCTYPE html>
<html>
<head>
<link href='http://fonts.googleapis.com/css?family=Titillium+Web:400,600,300' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="AddImage.css"/>
<link rel="stylesheet" type="text/css" href="itemDescriptionLightBox.css"/>
<link rel="stylesheet" type="text/css" href="styles/header.css"/>
<link rel="stylesheet" type="text/css" href="styles/sidemenu.css"/>
<link rel="stylesheet" type="text/css" href="styles/megadrawer.css"/>
<link rel="stylesheet" type="text/css" href="styles/footer.css"/>
<link rel="stylesheet" type="text/css" href="styles/shoppingcart_dropdown.css"/>
<link href="styles/jquery.comtagit.css" rel="stylesheet" type="text/css">
<link href="styles/tagit.ui-zendesk.css" rel="stylesheet" type="text/css">
<link rel="shortcut icon" href="images/icons/garagerads-20131102-favicon.ico">
<link href="styles/jquery.comtagit.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
<script src="scripts/common_jquery.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
<script src="scripts/tag-it.js" type="text/javascript" charset="utf-8"></script>
<script src="scripts/singlesale.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>GaragerSaleSetup</title>

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

<?php include('header.inc');?>
<?php include('sidemenu.inc');?>
<?php include('megadrawer.html');?>




<body>
	<?php include('itemDescription.inc');?>

	<div class="addItem">
		<div class="addImageTitle">
			<p class="uploadTitle">Add Images</p>
			<p class="uploadSubTitle">- Add up to 4 images per item, edit the item details and add the next item.</p>
			<p id="cancel" class="uploadCancelIcon" style="cursor:pointer">CANCEL</p>
		</div>
		<div class="upload">
      <div class="dragandrop" id="draganddrophandler">
  			<img id="upload2" src="images/icons/drag.png" draggable="true" ondragstart="drag(event)" width="250" height="200"><br>
      </div>
			<form id="addImage1" class="upload_image" enctype="multipart/form-data" action="upload.php" method="post">
				<img class="selectFile" src="images/icons/selectfile.png" style="cursor:pointer" />
				<input class="selectButton" type="file" multiple name="image" style="display:none" />
				<img class="uploadPic" src="images/icons/upload.png" style="cursor:pointer" />
				<input class="image_upload" type="button" name="submit" style="display:none" />
			</form>
		</div>
		<div class="uploadsmall">
			<div class="itemTitle">
				<p class="itemName">ITEM 1</p>
				<p class="deleteButton" style="cursor:pointer">DELETE</p>
			</div>
			<div class="smallPics">
				<div class="singlePic">
					<img id="small1" src="" draggable="true" ondragstart="drag(event)">
					<div class="itemBar">
						<p class="leftCursor" style="cursor:pointer"> < </p>
						<p class="rightCursor" style="cursor:pointer"> > </p>
						<img class="deleteItem" src="images/icons/deleteItem.png" style="cursor:pointer">
					</div>
				</div>
				<div class="singlePic">
					<img id="small2" src="" draggable="true" ondragstart="drag(event)">
					<div class="itemBar">
						<p class="leftCursor" style="cursor:pointer"> < </p>
						<p class="rightCursor" style="cursor:pointer"> > </p>
						<img class="deleteItem" src="images/icons/deleteItem.png" style="cursor:pointer">
					</div>
				</div>
				<div class="singlePic">
					<img id="small3" src="" draggable="true" ondragstart="drag(event)">
					<div class="itemBar">
						<p class="leftCursor" style="cursor:pointer"> < </p>
						<p class="rightCursor" style="cursor:pointer"> > </p>
						<img class="deleteItem" src="images/icons/deleteItem.png" style="cursor:pointer">
					</div>
				</div>
				<div class="singlePic">
					<img id="small4" src="" draggable="true" ondragstart="drag(event)">
					<div class="itemBar">
						<p class="leftCursor" style="cursor:pointer"> < </p>
						<p class="rightCursor" style="cursor:pointer"> > </p>
						<img class="deleteItem" src="images/icons/deleteItem.png" style="cursor:pointer">
					</div>
				</div>
			</div>
			<div class="editBar">
				<img class="lightbox_trigger editDetails" id="editDetails" href="itemDescriptionLightBox" src="images/icons/editDetails.png" style="cursor:pointer" /><br>
				<img class="addNextItem" id="addNextItem" src="images/icons/addNextItem.png" style="cursor:pointer" />
			</div>
		</div>
    <div class="subbutton">
      <input id="button2" type="button" name="submittotal" value="Review and Publish">
    </div>
	</div>



		




</body>
</html>

<?php include('megadrawer.html');?>
<?php include('shoppingcart_dropdown.php');?>
<?php include('footer.html');?>
