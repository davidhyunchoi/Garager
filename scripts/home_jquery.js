$(document).ready(function(){        
  
<!-- Search Portion -->
  
<!-- -->
          
  $(window).load(function() {
    $('.flexslider').flexslider();
  });
  
    
  //Lightbox function
  $('.lightbox_trigger').click(function(e) {
    e.preventDefault();
    var boxId= $(this).attr("href");
    $('#'+boxId).css('display','block'); 
    $('.lightBoxBackground').css('display','block'); 
    $('.lightBoxBackground').animate({'opacity':'.6'},100,'linear');
  });  

  //Click anywhere on the page to get rid of lightbox window
  $(document).on('click', '.lightBoxBackground', function() { //must use live, as the lightbox element is inserted into the DOM
      $('.lightBox').css('display','none');
      $('.lightBoxBackground').css('display','none');
      close_mega_drawer();
  });


 //  $('.log_in_link').click(function(){
        // $('.hero_slide,.logInLightBox').animate({'opacity':'.6'},100,'linear');
        // $('.logInLightBox').animate({'opacity':'1.0'},100,'linear');
        // $('.hero_slide,.logInLightBox').css('display','block');        
 //  });
/*  
  $('.categories_link').click(function(){
          if($('.megadrawer').css('display') == 'block')
                  close_mega_drawer();
        else {
                $('.megadrawer').animate({'z-index':'30'},100,'linear');
                $('.megadrawer').css('display','block');        
        }
  });
  
  $('.login_box_close').click(function(){
                close_login_box();
  });
*/  
  // $('.flexslider').click(function(){
                // close_login_box();
                // close_mega_drawer();
  // });  
  
 //  function close_login_box(){
 //          $('.logInLightBox').animate({'opacity':'0'},100,'linear');
        // $('.hero_slide').animate({'opacity':'1.0'},100,'linear', function(){
        //         $('.logInLightBox').css('display','none');
        // });
 //  }
  /*
  function close_mega_drawer(){
        $('.megadrawer').animate({'z-index':'-30'},100,'linear');
        $('.megadrawer').css('display','none');
  }
  
  $('.categories_row li').click(function(){
    var category = $(this).parent().attr('id');
    var subcategory = $(this).text();
    var params = jQuery.param({"category": category, "subcategory": subcategory});

    window.location = 'search.php?'+params;
  });  
*/

  $('#logout').click(function(){
    $.get('logout.php',function(data){
      location.reload();
    });
  });


  /*Bottom part SellStuff section AJAX */
  $(document.body).on("click", "#SellStuff", function(e){
    e.preventDefault();
    $.get('SellStuff.php',function(data){
      document.getElementById("sidekick").innerHTML=data;
    });
  });

  /*Bottom part AppGallery section AJAX */
  $(document.body).on("click", "#AppGallery", function(e){
    e.preventDefault();
    $.get('AppGallery.php',function(data){
      document.getElementById("sidekick").innerHTML=data;
    });
  });


  $(document.body).on("click", "#closeIcon", function(e){
    e.preventDefault();
    $.get('FeatureLink.php',function(data){
      document.getElementById("sidekick").innerHTML=data;
    });
  });

  $(document.body).on("click", "#mapstuff", function(e){
    e.preventDefault();
    $.get('map.php',function(data){
      document.getElementById("sidekick").innerHTML=data;
    });
  });

  $(document.body).on("click", "#howitworks", function(e){
    e.preventDefault();
    $.get('howitworks.php',function(data){
      document.getElementById("sidekick").innerHTML=data;
    });
  });

  $(document.body).on("click", "#searchLink", function(e){
    e.preventDefault();
    $.get('searchLink.php',function(data){
      document.getElementById("sidekick").innerHTML=data;
    });
  });


  $(document.body).on("click", "#pricing", function(e){
    e.preventDefault();
    $.get('pricing.php',function(data){
      document.getElementById("sidekick").innerHTML=data;
    });
  });



  
});
