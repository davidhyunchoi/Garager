$(document).ready(function()
{
  $("li .search_link").click(function (e) 
  {
	  if ($('#search_wrap').is(':hidden'))
      { 
	   	   document.getElementById('search_wrap').style.display = "block";
	   	   document.getElementById('search_wrap').style.zIndex = 100;
      }
      else
      {
      	   document.getElementById('search_wrap').style.display = "none";
      }
	  e.stopPropagation();
  });	
});

$(document).ready(function()
{
	$('html').on('click', function(evt)
	{
		 if(!$('#search_wrap').has(evt.target).length == 0)
		 {
		 	document.getElementById('search_wrap').style.display = "block";
		 }
		 else
		 {
			document.getElementById('search_wrap').style.display = "none"; 
		 }
	});
});
//
$(document).on("click", "#closeCart", function(){
    $('#shopcart_wrap').slideUp( "slow", function() {});
		$('#shopcart_wrap').css('z-index','-30');
		$('#shopcart_wrap').css('display','none');
  });
//

$(document).ready(function(){	
	//close_side_menu();
	var currentSort = "";

	$('.categories_link').click(function(){
		$('#sidemenu').hide();
		if($('.megadrawer').css('display') == 'block')
			close_mega_drawer();
		else {
			$('.megadrawer').animate( "fast", function() {
				$('.megadrawer').css('display','block');
				$('.megadrawer').css('z-index','30');
			});				
		}
	});
	
	$('body').click(function(e)  {
		if(e.target.className !== 'drawer_main' && e.target.className !== 'drawer_footer'
			&& e.target.className !== 'categories_link'){
			close_mega_drawer();
		}

	});

	function close_mega_drawer(){
		$('.megadrawer').animate( "fast", function() {
			$('.megadrawer').css('z-index','-30');
			$('.megadrawer').css('display','none');	
		});		
	}
	
/* shoppingcart lightbox */
	$('.shoppingcart_link').click(function(){
		if($('#shopcart_wrap').css('display') == 'block')
			close_shopcart();
		else {
			$('#shopcart_wrap').slideDown( "slow", function() {});
			$('#shopcart_wrap').css('z-index','30');
			$('#shopcart_wrap').css('display','block');	
		}
	});

	function close_shopcart(){
		$('#shopcart_wrap').slideUp( "slow", function() {});
		$('#shopcart_wrap').css('z-index','-30');
		$('#shopcart_wrap').css('display','none');
	}

/* end of shoppingcart lightbox */

/* 3step lightbox */
	$('#select_category').click(function(){
		if($('#select_list').css('display') == 'block')
			close_select_list();
		else {
			$('#select_list').slideUp( "slow", function() {});
			$('#select_list').css('z-index','50');
			$('#select_list').css('display','block');	
		}
	});

	function close_select_list(){
		$('#select_list').slideDown( "slow", function() {});
		$('#select_list').css('z-index','-30');
		$('#select_list').css('display','none');
	}
/* end of 3step lightbox */

    $('.topics>a').click(function() {
		
		if($(this).parent().children('ul').is(':hidden')){
			$('#nav>li>ul').delay(20).slideUp(500);
			$(this).parent().children('ul').delay(20).slideDown(500);
		}
		else{
			$(this).parent().children('ul').delay(20).slideUp(500);
		}			
	});

	$('#nav>li>ul>li').click(function() {
		$('#nav>li>ul>li>ul').delay(20).slideUp(500);
		$(this).children('ul').delay(20).slideDown(500);

		$('.selected').each(function(index){ 
			$(this).text($(this).text().replace('∨','>'));
			$(this).attr('class','unselected');
		});

		$(this).children().first().attr('class','selected');
		$(this).children().first().text($(this).children().first().text().replace('>','∨'));

	});
		
		
    $('.menu').click(function(){
		close_mega_drawer();
		$('#menu_search').val('Search');	
		$('#nav>li>ul').slideUp(500);
		$('#nav>li>ul>li>ul').slideUp(500);		
		$('#sidemenu').slideToggle('normal');
    });

    $(document.body).on('click','#profile_link a', function(e){
    	e.preventDefault();
		close_mega_drawer();
		$('#profile_menu').css('z-index',100);
		$('#profile_menu').slideToggle('fast');
    });
	
	$(document.body).on('click','.userinfo a',function(){		
		var link = $(this).parent().attr('id');
		var user = $(this).parent().parent().siblings('.username').attr('id');
		var count = 0;
		if (user == null) user = "";
		$.get("get_user_data.php", {'link': link, 'user_id': user} ,function(data){	
			if(link == 'Followers' || link == 'Following'){
         		$('.sorttypelist').css('visibility','hidden');
         		$('.searchlist').css('visibility','hidden');
       		}
       		else{
         		$('.sorttypelist').css('visibility','visible');
         		$('.searchlist').css('visibility','visible');
       		}	  		
			$('.piclist').empty();
			$('.piclist').append(data);	
			count = $('.piclist').children().length
		    $('#'+link).children('a').children('.list_count').text(count);				
		});
		//alert(count);
		$('.mainTitle').empty();
		$('.mainTitle').append('<h2>'+link+'</h2>');
		
		$('.userinfo a').css('color','#404754');
		$('.history a').css('color','#404754');
		$(this).css('color','#FF5E00');
	});

	$('.history a').click(function(){		
		var link = $(this).attr('id');
		var user = $(this).parent().parent().siblings('username').attr('id');
		$.get("get_user_data.php", {'link': link, 'user_id': user} ,function(data){			
			$('.piclist').empty();
			$('.piclist').append(data);
		});
		$('.mainTitle').empty();
		$('.mainTitle').append('<h2>'+link+'</h2>');
		$('.history a').css('color','#404754');
		$('.userinfo a').css('color','#404754');
		$(this).css('color','#FF5E00');

	});
	
    //Lightbox function
    //$('.lightbox_trigger').click(function(e) {
        $(document.body).on('click', '.lightbox_trigger',function(e){ 
        e.preventDefault();
        var boxId= $(this).attr("href");
        if(boxId == "itemLightBox"){
            var id = $(this).children().first().attr('id');
            $.get('itemLightBox.php', {'item_id':id},function(data){
           		$("#itemLightBox").remove();
            	$(".container").before(data);
        	});
  		}

      /* if(boxId == "itemDescriptionLightBox"){
  			var offset = $(this).offset();
  			$('.'+boxId).css('display','block'); 
  			$('.'+boxId).css('top', offset.top-100);
  			$('.'+boxId).css('left', offset.left-700);
	        $('.lightBoxBackground').css('display','block'); 
	        $('.lightBoxBackground').animate({'opacity':'.6'},100,'linear');
	        $('.'+boxId).find(topPic1).attr("src", $(document.body).find(small1).attr("src"));
	        $('.'+boxId).find(firstBottomPic1).attr("src", $(document.body).find(small1).attr("src"));
	        $('.'+boxId).find(secondBottomPic1).attr("src", $(document.body).find(small2).attr("src"));
	        $('.'+boxId).find(thirdBottomPic1).attr("src", $(document.body).find(small3).attr("src"));
	        $('.'+boxId).find(fourthBottomPic1).attr("src", $(document.body).find(small4).attr("src"));
  		}*/

  	
       	$('#'+boxId).css('display','block'); 
        $('.lightBoxBackground').css('display','block'); 
        $('.lightBoxBackground').animate({'opacity':'.6'},100,'linear');
    	

    }); 
	
	//Click anywhere on the page to get rid of lightbox window
	$(document).on('click', '.lightBoxBackground', function() { //must use live, as the lightbox element is inserted into the DOM
      $('.lightBox').css('display','none');
      $('.lightBoxBackground').css('display','none');
	});

	
	$('#signup_form').submit(function(){	
		$.post('signup.php', $('#signup_form').serialize(),function(json){
			if(json.Error){
				$('.errorMessage').empty();
				$('.errorMessage').append(json.Error);
			}
			else{
				window.location = 'new_stuff.php';
			}
		});		
		return false;
	});	

	$('.Remove_form').submit(function(){
		var formId= $(this).children().first().attr('value');
		$.post('removeFromShoppingcart.php', {'item_id': formId},function(json){
			if(json.Error){
				$('#itemcheckoutlist'+formId).empty();
				$('#itemcheckoutlist'+formId).append(json.Error);
			}
			else{
				$('#itemcheckoutlist'+formId).empty();
				$('#itemcheckoutlist'+formId).height(0);	
			}
		});		
		return false;
	});	

	$(".addMsg").click(function(){
   		if($(this).is(':checked')){
   			var formId= $(this).attr("name");
   			//alert("checked"+formId);
   			$('#Msg'+formId).css('display','block');
   		}	
	});


	$('.BuyNow_form').submit(function(){
		//$('.BuyNow').empty();
		//$('.BuyNow').append('<a id="BuyNowButton" href="#">Finish Purchase $</a>');		
		//return false;
		var formId= $(this).children().first().attr('value');
		//var item_id = $(this).children().first().attr('value');
		$.post('checkout.php', {'item_id': formId},function(json){
			if(json.Error){
				$('.BuyNow'+formId).empty();
				$('.BuyNow'+formId).append(json.Error);
				$('#itemcheckoutlist'+formId).height(570);
				$('#h_line1'+formId).css({top:"570px"});
			}
			else{
				$('.BuyNow'+formId).empty();
				$('.BuyNow'+formId).append(json.Success);
				$('#itemcheckoutlist'+formId).height(570);
				$('#h_line1'+formId).css({top:"570px"});
			}
		});		
		return false;
	});	

	$(document.body).on('submit','.finishPurchase_form',function(){
		var item_id = $(this).children().first().attr('value');
		//alert(item_id);
		$.post('finishPurchase.php', {'item_id': item_id},function(json){

			if(json.Error){
				$('#itemOrder'+item_id).empty();
				$('#itemOrder'+item_id).append(json.Error);
				$('#itemcheckoutlist'+item_id).height(300);
				$('#h_line1'+item_id).css({top:"300px"});
			}
			else{
				$('#itemOrder'+item_id).empty();
				$('#itemOrder'+item_id).append(json.Success);
				$('#itemcheckoutlist'+item_id).height(300);
				$('#h_line1'+item_id).css({top:"300px"});
			}


			
		
		});		
		return false;
	});	

	$('#shippingInfo_form').submit(function(){
		//var formId= $(this).attr("name");
		$('.lightBox').css('display','none');
      	$('.lightBoxBackground').css('display','none');
		var res = {};
		$("#shippingInfo_form input, #shippingInfo_form select").each(function(i, obj) {
    		res[obj.name] = $(obj).val();
		})

		//$('.BuyNowtest7').empty();
		//$('.BuyNowtest7').append(' '+res[add1]+' ');	//' '+res[add1]+' '
		$.post('', $('#shippingInfo_form').serialize(),function(json){
			$('.shipInfo').empty();
			$('.shipInfo').append(''+res['add1']+' ');
		});		
		return false;
	});	

	$('#billingInfo_form').submit(function(){
		//var formId= $(this).attr("name");
		$('.lightBox').css('display','none');
      	$('.lightBoxBackground').css('display','none');
		var res = {};
		$("#billingInfo_form input, #billingInfo_form select").each(function(i, obj) {
    		res[obj.name] = $(obj).val();
		})

		//$('.BuyNowtest7').empty();
		//$('.BuyNowtest7').append(' '+res[add1]+' ');	//' '+res[add1]+' '
		$.post('', $('#billingInfo_form').serialize(),function(json){
			$('.billInfo').empty();
			$('.billInfo').append(''+res['firstName']+res['lastName']+'<br>'+res['add1']+'<br>'+res['city']+' ,'+res['state']+' '+res['zip']+'<br> USA <br>Visa<br>');
		});		
		return false;
	});
	
	$('#login_form').submit(function(){	
		$.post('login.php', $('#login_form').serialize(),function(json){
			if(json.Error){
				$('.errorMessage').empty();
				$('.errorMessage').append(json.Error);				
			}
			else {
				location.reload();
			}
		});		
		return false;
	});	

	$('#login_form>#username').focus(function(){
		if($(this).val() == 'Username or Email'){
			$(this).val("");
		}
	});

	$('#login_form>#password').focus(function(){
		$(this).val("");
		$(this).attr('type', 'password');
	});	

	$('#reset_pwd_form').submit(function(){	
		$.post('reset_password.php', $('#reset_pwd_form').serialize(),function(json){
			if(json.Error){
				$('.errorMessage').empty();
				$('.errorMessage').append(json.Error);
			}
			else{
				$('#ResetPWDLightBox').empty();
				$('#ResetPWDLightBox').append(json.Success);
			}
		});		
		return false;
	});	

	$('.categories_row li').click(function(){
		var category = $(this).parent().attr('id');
  		var subcategory = $(this).text();
		var params = jQuery.param({"category": category, "subcategory": subcategory});

		window.location = 'search.php?'+params;
	});

	$('.categories_row p').click(function(){
		var category = $(this).text();
		var params = jQuery.param({"category": category});

		window.location = 'search.php?'+params;
	});

	$('.subTopics li a').click(function(){
		var category = $(this).parent().parent().attr('id');
  		var subcategory = $(this).text();
		var params = jQuery.param({"category": category, "subcategory": subcategory});

		window.location = 'search.php?'+params;
	});

	$('.footer_tag').click(function(){
		var tag = $(this).find('a').attr('id');
		var params = jQuery.param({"tag": tag});

		window.location = 'search.php?'+params;
	});

	$('#logout, #log_out').click(function(){
		$.get('logout.php',function(data){
			location.reload();
		});
	});

	$('#menu_search').click(function(){
    	$(this).val("");
 	});

 	$('#search_button').click(function(){
		var query = $('#menu_search').val();
		var params = jQuery.param({"tag": query});

		window.location = 'search.php?'+params;
	});

	$("#menu_search").keyup(function(event){
    	if(event.keyCode == 13){
        	$("#search_button").click();
    	}
	});

	$(document.body).on('mouseenter','.garagersale_text',function(){
		$(this).siblings('.garagersale_full_text').first().slideUp('slow',function(){
			$(this).siblings('.garagersale_text').first().css('display','none');
			$(this).css('display','block');			
		});		
	});

	$(document.body).on('mouseleave','.garagersale_full_text',function(){
		$(this).siblings('.garagersale_text').first().slideUp('slow',function(){
			$(this).siblings('.garagersale_full_text').first().css('display','none');
			$(this).css('display','block');			
		});
	});


	$(document.body).on('mouseleave','#sidemenu', function(){	
		$(this).slideUp('slow',function(){
			$('.selected').each(function(index){ 
				$(this).text($(this).text().replace('∨','>'));
				$(this).attr('class','unselected');
			});
			$('#nav>li>ul>li>ul').hide();
			$('#nav>li>ul').hide();	
		});		
	});

	$(document.body).on('mouseleave','#profile_menu', function(){
		$(this).slideUp('normal');
	});

	$('.sort_option').mouseover(function(){
		currentSort = $(this).children('.sort_label').first().text();
		if ($(this).attr('id') == 'sort_type')			
			$(this).children('.sort_label').first().text('Type'); 
		else
			$(this).children('.sort_label').first().text('Relevance'); 
		}).mouseout(function(){
			$(this).children('.sort_label').first().text(''+ currentSort); 
	});

	$(document.body).on('click','.sort_by_type a', function(e){
		e.preventDefault();	
		var type = 	$(this).attr('id');
		currentSort = $(this).text();
	
		if(type == 'sort_garager_sales'){
			$('.piclist').children('a').hide();
			$('.piclist').children('.garagersale').show();
		}
		else if(type == 'sort_online'){
			$('.piclist').children('a').hide();
			$('.piclist').children('.online').show();
			$('.piclist').children('.onlineoffline').show(); 
		}
		else if(type == 'sort_offline'){
			$('.piclist').children('a').hide();
			$('.piclist').children('.offline').show();
			$('.piclist').children('.onlineoffline').show();
		}
		else if(type == 'sort_all'){
			$('.piclist').children('a').show();
		}
		else{

		}
		$('#sort_type').trigger('mouseleave');

	});

	$(document.body).on('click','.sort_by_relevance a', function(e){
		e.preventDefault();	
		var relevance = 	$(this).attr('id');
		currentSort = $(this).text();
	
		if(relevance == 'sort_price_low'){
			$('.garagersale').hide();
			$('.piclist a:not(.garagersale)').sort(function(a,b) {
     			return parseFloat($(a).attr('price')) > parseFloat($(b).attr('price'));
			}).appendTo('.piclist');
		}	

		else if(relevance == 'sort_price_high'){
			$('.garagersale').hide();
			$('.piclist a:not(.garagersale)').sort(function(a,b) {
     			return parseFloat($(a).attr('price')) < parseFloat($(b).attr('price'));
			}).appendTo('.piclist');
		}

		else if(relevance == 'sort_newest'){
			$('.piclist a').sort(function(a,b) {
     			return $(a).attr('date') < $(b).attr('date');
			}).appendTo('.piclist');
		}	

		else if(relevance == 'sort_popular'){
			$('.piclist a').sort(function(a,b) {
     			return $(a).attr('faves') < $(b).attr('faves');
			}).appendTo('.piclist');
		}	

	});

	$('.searchlist').ready(function(){		
		var tag_length = $('.searchlist').children('span').length;
		if(tag_length == 1){
			$('.searchlist').children('span').first().children('.clear_tag').hide();
		}
	});

	$(document.body).on('click','.clear_tag',function(e){
		e.preventDefault();
		var tags = "";
		var clear = $(this).siblings('.searched_tag').first().text();
		var tag_length = $('.searchlist').children('span').length;
		$('.searched_tag').each(function(){			
			if($(this).text() != clear)
				tags+= "tag=" + encodeURIComponent($(this).text().toLowerCase()) + "&";
		});
		
		if (tags != "")
			window.location = "search.php?" + tags;
	});

	$(document.body).on('click','.clear_user_tag',function(){
		var tags = "";
		var hide = $(this).parent().attr('id');
		var tag = hide.substring(4);
		$('.piclist a').each(function(){	
			if($(this).attr('tags') != null){
				$(this).attr('tags', $(this).attr('tags').split(tag).join(''));
				if ($(this).attr('tags').trim() == "")
					$(this).hide(); 		
			}
				
		});
		$('#'+hide).hide();
	});

	$(document.body).on('click','.fave_image', function(){	
		var item = $(this).parent().siblings('.itemDescription:first').attr('id');
		var linkIcon = $(this).attr('id');
		$.post('favorite.php',{'item_id': item},function(data){
			$('.fave_image').animate("normal", function() {
        		if (linkIcon == 'fave_img'){
        			$('.fave_image').attr('src','images/icons/fave.png');
        			$('.fave_image').attr('id','unfave_img');
        		} 
        		else {
        			$('.fave_image').attr('src','images/icons/favorites_icon.png');
        			$('.fave_image').attr('id','fave_img');
        		}
        	});
		}).error(function(){
        	//$('.follow').after("<p style='color:red;font-size:10px;'>Error occurred. Please try again!</p>");
        	alert("Error occurred. Please try again!");
        });
			
	});

	$('.follow p').click(function(){
		var user = $(this).parent().siblings('.username:first').text();
		var linkText = $(this).text();
		$.post('follow.php',{'user_name': user},function(data){
			$('.follow').animate("normal", function() {
        		if (linkText == 'Follow') $('.follow').children().first().text('Unfollow');
        		else $('.follow').children().first().text('Follow');
        	});
		}).error(function(){
        	//$('.follow').after("<p style='color:red;font-size:10px;'>Error occurred. Please try again!</p>");
        	alert("Error occurred. Please try again!");
        });
	});

	$('.sug_foll_button p').click(function(){
		var user = $(this).parent().siblings('.sug_foll_text:first').children('p:first').text();
		var followBoxId = $(this).parent().parent().attr('id');
		$.post('follow.php',{'user_name': user},function(){
			$('#'+followBoxId).fadeOut("normal", function() {
        		$('#'+followBoxId).remove();
        	});
        }).error(function(){
        	//$('#'+followBoxId).append("<p style='color:red;font-size:10px;'>Error occurred trying to follow user!</p>");
        	alert("Error occurred. Please try again!");
        });
	});

	$('#checkin_sale').click(function(){
		var gsale_id = $(this).parent().attr('id');
		var linkText = $(this).text();
		$.post('checkIn.php',{'gsale_id': gsale_id},function(data){
			$('#checkin_sale').animate("normal", function() {
        		if (linkText == 'Check-in') $('#checkin_sale').text('Cancel Check-in');
        		else $('#checkin_sale').text('Check\-in');
        	});
		}).error(function(){
        	//$('.checkin').after("<p style='color:red;font-size:10px; float:right'>Error occurred. Please try again!</p>");
       		alert("Error occurred. Please try again!");
        });
	});

	$('#fave_sale').click(function(){
		var gsale_id = $(this).parent().attr('id');
		var linkText = $(this).text();
		$.post('favorite.php',{'gsale_id': gsale_id},function(data){
			$('#fave_sale').animate("normal", function() {
        		if (linkText == 'Fave') $('#fave_sale').text('Cancel Fave');
        		else $('#fave_sale').text('Fave');
        	});
		}).error(function(){
        	//$('.checkin').after("<p style='color:red;font-size:10px; float:right'>Error occurred. Please try again!</p>");
       		alert("Error occurred. Please try again!");
        });
	});



	$(".OneSale").hover(
		function(){
			$(this).find('p').css("color","white");
			$(this).find('img').attr("src","images/icons/onesale_red.png")
		},
		function(){
			$(this).find('p').css("color","black");
			$(this).children().first().css("color","#FF6000");
			$(this).find('img').attr("src","images/icons/onesale.png")
		});
	$(".MultiSale").hover(
		function(){
			$(this).find('p').css("color","white");
			$(this).find('img').attr("src","images/icons/multisale_red.png")
		},
		function(){
			$(this).find('p').css("color","black");
			$(this).children().first().css("color","#FF6000");
			$(this).find('img').attr("src","images/icons/multisale.png")
		});
  	
});