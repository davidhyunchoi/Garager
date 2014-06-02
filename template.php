<!DOCTYPE html>
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
<link href="styles/jquery.comtagit.css" rel="stylesheet" type="text/css">
<link href="styles/tagit.ui-zendesk.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="createSaleLightBox.css"/>
<link rel="stylesheet" type="text/css" href="styles/process.css"/>
<link rel="shortcut icon" href="images/icons/garagerads-20131102-favicon.ico">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
<script src="scripts/common_jquery.js"></script>
<script src="scripts/tag-it.js" type="text/javascript" charset="utf-8"></script>
<script src="scripts/process_jquery.js"></script>


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
$(document.body).ready(function(){
  
    $.get('get_user_data.php', {'link': 'Tags'} ,function(data){      
      $('.history1').append(data); 
  });
});
/**/

/* 3Step ADD CUSTOM TAGS*/
$(document).ready(function(){
  $(document.body).on('click','.clear_tag',function(e){
    e.preventDefault();
    var tag = $(this).parent().attr('id');
    $('#'+tag).replaceWith('<input type="hidden" name="deleted[]" value="'+tag+'"/>');  
  });
/*
  $(document.body).on('click','.add_tag1',function(e){
    e.preventDefault();
    $('.tag_box1').first().clone(true).insertBefore($(this).parent());
  });
*/

  $(document.body).on('click','#reset_link',function(e){
    e.preventDefault();
    $('#reset').slideToggle('slow');
  });

  <?php if (isset($_PAGE["tag_success"])) { ?>
    $('#Tags').click();
  <?php }  ?>
});
/* end 3step */

</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>

<?php include('header.inc');?>
<?php include('sidemenu.inc');?>
<?php include('signup.inc');?>
<?php include('login.inc');?>
<?php include('reset_pwd.inc');?>

<div class='container'>
	
	
	<! Content varies by page. Set $_PAGE content >
  <div class='content-right'>
	<?php    
    echo isset($_PAGE['banner']) ?   $_PAGE['banner'] : "" ; 
  ?>
</div>
		
	<div class='sidebar'>
		<?php
			echo "
				<div id='sidebar1'>". $_PAGE['sidebar1'] . "</div>	
				<div class='sidebar2'>" . $_PAGE['sidebar2']. "</div>	
				<div class='sidebar3'>" . $_PAGE['sidebar3']. "</div>
			" ;
	?>
	</div>
	
	<div class="sorttypelist">	
		<?php echo isset($_PAGE['sort']) ?   $_PAGE['sort'] : "" ;?>		
	</div>

	<div class="searchlist">	
		<?php echo isset($_PAGE['search']) ?   $_PAGE['search'] : "" ;?>	
	</div>

	<div class="mainTitle">
		<?php echo isset($_PAGE['title']) ?   $_PAGE['title'] : "" ;?>			
	</div>
		
	<div class="piclist">
		<?php echo isset($_PAGE['items']) ?   $_PAGE['items'] : "" ;?>
	</div>
	
	
</div>

<?php include('megadrawer.html');?>
<?php include('shoppingcart_dropdown.php');?>
<?php include('footer.html');?>
<?php include('createSaleLightBox.inc');?>
<div class="lightBoxBackground">
</div>

</body>
</html>