$(document).ready(function(){
	//Begin Queries for Search
	$("li .search_link").click(function (e) {
	  if ($('#search_wrap').is(':hidden')){ 
	   	document.getElementById('search_wrap').style.display = "block";
	   	document.getElementById('search_wrap').style.zIndex = 100;
      }
      else{
      	document.getElementById('search_wrap').style.display = "none";
      }
      e.stopPropagation();
  	});

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
    /* End Queries for Search */

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

	/* Categories dropdown */
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

	$('.categories_row li').click(function(){
		var category = $(this).parent().attr('id');
  		var subcategory = $(this).text();
		var params = jQuery.param({"category": category, "subcategory": subcategory});

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

	/* End Categories dropdown */

	/* Side menu */
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

	$('#logout, #log_out').click(function(){
		$.get('logout.php',function(data){
			location.reload();
		});
	});

    /* End Side menu */

    /* Profile menu */
    $(document.body).on('click','#profile_link a', function(e){
    	e.preventDefault();
		close_mega_drawer();
		$('#profile_menu').css('z-index',100);
		$('#profile_menu').slideToggle('fast');
    });

    $(document.body).on('mouseleave','#profile_menu', function(){
		$(this).slideUp('normal');
	});

    /* End Profile menu */

    /* Create Garager Sale */
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

	/* End Create Garager Sale */

	/* Lightbox Trigger */
    $(document.body).on('click', '.lightbox_trigger',function(e){ 
        e.preventDefault();
        var boxId= $(this).attr("href");
   		$('#'+boxId).css('display','block'); 
        	//$('.lightBoxBackground').css('display','block'); 
       	$('.lightBoxBackground').animate({'opacity':'.6'},100,'linear');
    }); 
	
	//Click anywhere on the page to get rid of lightbox window
	$(document).on('click', '.lightBoxBackground', function() { //must use live, as the lightbox element is inserted into the DOM
      $('.lightBox').css('display','none');
      $('.lightBoxBackground').css('opacity','1');
	});

	/* End Lightbox Trigger */

	/* Account Page Scripts */

	$(document.body).on('click','.account a',function(e){
		e.preventDefault();
		var field = $(this).parent().next('textarea').attr('id');
		$('#'+field).slideToggle('fast');	
	});

	$(document.body).on('click','.profile a',function(e){
		e.preventDefault();
		$('#photo').css('display','block');	
		$('#upload').css('display','block');	
	});

	$(document.body).on('click','#Messages, #Buys, #Sells, #Tags',function(e){
		e.preventDefault();
		var link = $(this).attr('id');
		$.get('get_user_data.php', {'link': link} ,function(data){			
			$('.piclist').empty();
			$('.piclist').append('<h5>'+link+'</h5>');	
			$('.piclist').append(data);	
		}).complete(function(){
			$('#personal').css('display','none');
			$('#address').css('display','none');	
			$('.history').css('display','block');
			if(link=='Tags') {
				$('#update_tags').css('display','block');
				$('.add_tag').css('display','block');
			}
			else{
				$('#update_tags').css('display','none');
				$('.add_tag').css('display','none');
				$('.error').remove();
				$('.success').remove();
			}
			$('.header_selected').toggleClass('header_selected');
			$('#'+link).parent().addClass('header_selected');
		});

	});

	$(document.body).on('click','.clear_tag',function(e){
		e.preventDefault();
		var tag = $(this).parent().attr('id');
		$('#'+tag).replaceWith('<input type="hidden" name="deleted[]" value="'+tag+'"/>');	
	});

	$(document.body).on('click','#add_tag',function(e){
		e.preventDefault();
		$('.tag_box').first().clone(true).insertBefore($(this).parent());
	});


	$(document.body).on('click','#reset_link',function(e){
		e.preventDefault();
		$('#reset').slideToggle('slow');
	});

	$(document.body).on('click','#close_account',function(e){
		e.preventDefault();
		if (confirm('Are you sure you want to close your Garager account?')) {
    		$.post('close_account.php',{},function(){
    			location.reload();
    		});
		}
		
	});

});
