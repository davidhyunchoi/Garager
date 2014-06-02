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

<div style="margin-top:50px; padding-left: 50px; padding-right: 50px; min-height:100%; font-family: 'Titillium Web', Arial, sans-serif;">

<h1>Privacy Policy</h1>

<p><b>What do we use your information for?</b></p>

<p>Any of the information we collect from you may be used to send periodic emails. The email address you provide may be used to send you information, respond to inquiries, and/or other requests or questions.</p>

<p><b>How do we protect your information?</b></p>
<p>We implement a variety of security measures to maintain the safety of your personal information when you enter, submit, or access your personal information.

<p><b>Do we use cookies?</b></p>

<p>We do not use cookies.</p>

<p><b>Do we disclose any information to outside parties? </b></p>

<p>We do not sell, trade, or otherwise transfer to outside parties your personally identifiable information. This does not include trusted third parties who assist us in operating our website, conducting our business, or servicing you, so long as those parties agree to keep this information confidential. We may also release your information when we believe release is appropriate to comply with the law, enforce our site policies, or protect ours or others' rights, property, or safety. However, non-personally identifiable visitor information may be provided to other parties for marketing, advertising, or other uses.</p>

<p><b>California Online Privacy Protection Act Compliance</b></p>
Because we value your privacy we have taken the necessary precautions to be in compliance with the California Online Privacy Protection Act. We therefore will not distribute your personal information to outside parties without your consent.</p>

<p><b>Children's Online Privacy Protection Act Compliance</b></p>
<p>We are in compliance with the requirements of COPPA (Childrens Online Privacy Protection Act), we do not collect any information from anyone under 13 years of age. Our website, products and services are all directed to people who are at least 13 years old or older.</p>

<p><b>Online Privacy Policy Only</b></p>

<p>This online privacy policy applies only to information collected through our website and not to information collected offline.</p>

<p><b>Changes to our Privacy Policy</b></p>
<p>If we decide to change our privacy policy, we will post those changes on this page.</p>

<p>This policy was last modified December 2013.</p>

<p><b>Contacting Us</b><p>
<p>If there are any questions regarding this privacy policy you may contact us using the information below.</p>

<p>Email <a href='mailto:garagerinfo@gmail.com'>garagerinfo@gmail.com</a> or write us at Garager Corporate Office, 2620 56th Ave., Oakland, CA 94605.</p>
</div>
<?php include('megadrawer.html');?>
<?php include('shoppingcart_dropdown.php');?>
<?php include('footer.html');?>
<?php include('createSaleLightBox.inc');?>
<div class="lightBoxBackground"></div>
</body>
</html>