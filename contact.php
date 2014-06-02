<?php session_start(); ?>

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
<link rel="shortcut icon" href="images/icons/garagerads-20131102-favicon.ico">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="scripts/common_jquery.js"></script>
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
$(document).ready(function(){
	//Begin Queries for Search
	$('#advanced').hide();

  	$('#advsearch').click(function(){ 
        if ($('#advanced').is(':visible')){
            $('#advanced').slideUp();
        }
        else {
            $('#advanced').slideDown();
        }
    });

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


    // End Queries for Search
});
</script>
</head>

<body style="margin:0; min-height: 100%;">


<?php include('header.inc'); ?>
<?php include('sidemenu.inc');?>
<?php include('megadrawer.html');?>
<?php include('signup.inc');?>
<?php include('login.inc');?>
<?php include('reset_pwd.inc');?>

<div style="margin-top:50px; padding-left: 50px; padding-right: 50px; height:100%; font-family: 'Titillium Web', Arial, sans-serif;">
<h1>Contact Us</h1>

<p>Garager wants to hear from you. For general inquires:</p>

<p>Email <a href='mailto:garagerinfo@gmail.com'>garagerinfo@gmail.com</a> </p>

<p>or write us at Garager Corporate Office, 2620 56th Ave., Oakland, CA 94605.</p>

<hr/>
<P>Please visit our facebook page:</p>
<p><a href='http://www.facebook.com/garagerco'>www.facebook.com/garagerco</a></p>

<hr/>
<p>Please follow us on Twitter:</p>
<p><a href='https://twitter.com/garagercompany'>@garagercompany</a></p>
</div>
<?php include('megadrawer.html');?>
<?php include('shoppingcart_dropdown.php');?>
<?php include('footer.html');?>
<?php include('createSaleLightBox.inc');?>
<div class="lightBoxBackground"></div>
</body>
</html>
