 $(document).ready(function()
{   
	
	var addtime = 0;
	var height1 = 330;
	var itemtitle;
	var itemprice;
	var itemdescription;
	var itemcategory;
	var itemtags;
	var itemshippingtype;
	var itemshippingcost;
	var itemtitlea = [];
	var itempricea = [];
	var itemdescriptiona = [];
	var itemcategorya = [];
	var itemtagsa = [];
	var itemshippingtypea = [];
	var itemshippingcosta = [];


  /*when focus on textarea, clear default content*/
  $("textarea").focus(function(event){
  	$(this).text("");
  	$(this).unbind(event);
  });

 $('#checkskip').on('change',function(e) {
    if ($(this).prop('checked')) {
        $(".innerpara2").hide();
        $(".para2").css('height','30px');
    } else {
        $(".innerpara2").show();
        $(".para2").css('height','300px');
    };
});

$('#cancel').click(function(){
     var $dialog = $('<div id="dialog-confirm" title="Cancel Garager Sale"><p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure to cancel this garager sale?</p></div>').dialog({
            resizable: false,
      		height:140,
      		modal: true,
            buttons: [
                  {
                        text: "Yes",
                        click: function(){
                              $dialog.remove();
                              var url = "new_stuff.php";    
							               $(location).attr('href',url);
                        }
                  },
                  {
                        text: "No",
                        click: function(){
                              $dialog.remove();
                        }
                  }
            ]
      });

}); 


  $(document.body).on('click','#editDetails',function(e){ 
 		//alert(this.id);
        e.preventDefault();
        var id=this.id;
        var boxId= $(this).attr("href");
       if(boxId == "itemDescriptionLightBox"){
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
  		}


  		$("#savelightbox").unbind("click").click(function(){
  			if ($("#itemtitle").val() != "Enter Title"){
  				itemtitle = $("#itemtitle").val();}
  			else {
  				itemtitle = "";
  			}
  			//alert(itemtitle);
  			if ($("#itemprice").val() != "Enter Price"){
  				itemprice = $("#itemprice").val();}
  			else{
  				itemprice = "";
  			}
  			//alert(itemprice);
  			if ($("#itemdescription").val() != "Description"){
  				itemdescription = $("#itemdescription").val();}
  			else{
  				itemdescription = "";
  			}
  			//alert(itemdescription);
  			itemcategory = $("#itemcategory").val();
  			//alert(itemcategory);
  			if ($("#itemtags").val() != "Enter custome tags, seperated by commas"){
  				itemtags = $("#itemtags").val();}
  			else{
  				itemtags = "";
  			}
  			//alert(itemtags);
  			if ($("#itemshippingtype").val() != "volvo"){
  				itemshippingtype = $("#itemshippingtype").val();}
  			else{
  				itemshippingtype = "";
  			}
  			//alert(itemshippingtype);
  			if ($("#itemshippingcost").val() != "Shipping cost"){
  				itemshippingcost = $("#itemshippingcost").val();}
  			else{
  				itemshippingcost = "";
  			}
  			//alert(itemshippingcost);
  			$("#addImage1").trigger("reset");
  			$('.'+boxId).css('display','none'); 
  			$('.lightBoxBackground').css('display','none'); 
  			//alert(id);
  		}); 
});


   
  /*create garager sale; drag and drop*/  
	$("#selectFile").click(function () {
	    $("#selectButton").trigger('click');
	});

	$("#uploadPic").click(function () {
		if ($('#upload2').attr('src') != "images/icons/drag.png")
	    	$("#image_upload").trigger('click');
	});

$("#addNextItem").click(function(){
		addtime = addtime+1;
		var current = addtime;
	    height1 = height1 + 300;
		$('.para3').css('height', height1);
		$("#para3test").append('<div class="contentdynamic" id="dynamic'+addtime+'"><div class="upload"><div class="dragandrop" id="draganddrophandler'+addtime+'"><img id="upload2'+addtime+'" src="images/icons/drag.png" draggable="true" ondragstart="drag(event)" width="170" height="170"><br></div><form id="upload_image'+addtime+'" enctype="multipart/form-data" action="upload.php" method="post"><img class="buttonclick" src="images/icons/selectfile.png" id="selectFile'+addtime+'" style="cursor:pointer" /><input id="selectButton'+addtime+'" type="file" name="image" style="display:none" /><img class="buttonclick" src="images/icons/upload.png" id="uploadPic'+addtime+'" style="cursor:pointer" /><input id="image_upload'+addtime+'" type="button" name="submit" style="display:none" /></form></div><div class="uploadsmall" ondrop="drop(event)" ondragover="allowDrop(event)"><div class="itemTitle"><p class="itemName">ITEM 1</p><p class="deleteButton" id="deletediv'+addtime+'">DELETE</p></div><div class="smallPics"><div class="singlePic"><img id="small1'+addtime+'" src="" draggable="true" ondragstart="drag(event)"><div class="itemBar"><p class="leftCursor" style="cursor:pointer"> < </p><p class="rightCursor" style="cursor:pointer"> > </p><img class="deleteItem" src="images/icons/deleteItem.png" style="cursor:pointer"></div></div><div class="singlePic"><img id="small2'+addtime+'" src="" draggable="true" ondragstart="drag(event)"><div class="itemBar"><p class="leftCursor" style="cursor:pointer"> < </p><p class="rightCursor" style="cursor:pointer"> > </p><img class="deleteItem" src="images/icons/deleteItem.png" style="cursor:pointer"></div></div><div class="singlePic"><img id="small3'+addtime+'" src="" draggable="true" ondragstart="drag(event)"><div class="itemBar"><p class="leftCursor" style="cursor:pointer"> < </p><p class="rightCursor" style="cursor:pointer"> > </p><img class="deleteItem" src="images/icons/deleteItem.png" style="cursor:pointer"></div></div><div class="singlePic"><img id="small4'+addtime+'" src="" draggable="true" ondragstart="drag(event)"><div class="itemBar"><p class="leftCursor" style="cursor:pointer"> < </p><p class="rightCursor" style="cursor:pointer"> > </p><img class="deleteItem" src="images/icons/deleteItem.png" style="cursor:pointer"></div></div></div><div class="editBar"><img class="lightbox_trigger1" href="itemDescriptionLightBox" src="images/icons/editDetails.png" id="editDetails'+addtime+'" style="cursor:pointer;float:left;width:105px;height:25px;" /><br><input class="addNextButton" type="button" id="addNextItem'+addtime+'" name="addnext" value="ADD NEXT ITEM"></div></div></div>');

		$("#selectFile"+current).click(function () {
	    	$("#selectButton"+current).trigger('click');
		});

		$("#uploadPic"+current).click(function () {
			console.log("#uploadPic"+current);
			console.log(current);
		if ($('#upload2'+current).attr('src') != "images/icons/drag.png")
	    	$("#image_upload"+current).trigger('click');
		});

		function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
           
            reader.onload = function (e) {
                $('#upload2'+current).attr('src', e.target.result);
            
            };
            reader.readAsDataURL(input.files[0]);
       	 
    		}
    	}

    	$("#selectButton"+current).change(function(){
        	readURL(this);
    	});

    	function handleFileUpload(files,obj)
			{
   				for (var i = 0; i < files.length; i++) 
  				 {
       				 var fd = new FormData();
       				 fd.append('file', files[i]);
        			 previewfile(files[i]);
 
   				}
			}

		function previewfile(file) {
  
    			var reader = new FileReader();
    			reader.onload = function (e) {
     			
     			
      			$('#upload2'+current).attr('src', e.target.result);
      
  				};
    			reader.readAsDataURL(file);
    		
  			} 

  		$(document).ready(function()
			{
				var obj = $('#draganddrophandler'+current);
				obj.on('dragenter', function (e) 
				{
   				 e.stopPropagation();
    			 e.preventDefault();
    			 $(this).css('border', '2px solid #0B85A1');
				});

				obj.on('dragover', function (e) 
				{
     			e.stopPropagation();
     			e.preventDefault();
				});

				obj.on('drop', function (e) 
				{
     			$(this).css('border', '2px dotted #0B85A1');
     			e.preventDefault();
     			var files = e.originalEvent.dataTransfer.files;
     			//We need to send dropped files to Server
    			 handleFileUpload(files,obj);
				});

				$(document).on('dragenter', function (e) 
				{
    			e.stopPropagation();
    			e.preventDefault();
				});

				$(document).on('dragover', function (e) 
				{
  				e.stopPropagation();
  				e.preventDefault();
  				obj.css('border', '2px dotted #0B85A1');
				});

				$(document).on('drop', function (e) 
				{
    				e.stopPropagation();
   				    e.preventDefault();
				});
 
			});


	$('#image_upload'+current).click(function(){
	    var formData = new FormData($('#upload_image'+current)[0]);
	    //ondrop="drop(event)" ondragover="allowDrop(event)"
	  	 if ($("#upload2"+current).attr("src") != "images/icons/drag.png"){
	    
	    	for(var j=1; j<=4; j++){
	    		if($("#small"+j+current).attr("src") == ""){
	    			$("#small"+j+current).attr("src", $("#upload2"+current).attr("src"));
	    			break;
	    		}
	    	}
	    	$("#upload2"+current).attr("src","images/icons/drag.png");
	    }
	    $("#upload_image"+current).trigger("reset");
	});


    $("#addNextItem"+current).click(function(){
    		$("#addNextItem").click();
    });

   $(".leftCursor").click(function(){
		var thisID = $(this).parent().prev().attr("id");
		if(thisID == "small"+"1"+current){
			//do nothing
		}
		else{
			console.log("yes");
			var thisSrc = $(this).parent().prev().attr("src");
			var prevSrc = $(this).parent().parent().prev().children().first().attr("src");
			$(this).parent().prev().attr("src", prevSrc);
			$(this).parent().parent().prev().children().first().attr("src", thisSrc);
		}
	});

	$(".rightCursor").click(function(){
		var thisID = $(this).parent().prev().attr("id");
		if(thisID == "small"+"4"+current){
			//do nothing
		}
		else{
			console.log("yes");
			var thisSrc = $(this).parent().prev().attr("src");
			console.log(thisSrc);
			var nextSrc = $(this).parent().parent().next().children().first().attr("src");
			console.log(nextSrc);
			$(this).parent().prev().attr("src", nextSrc);
			$(this).parent().parent().next().children().first().attr("src", thisSrc);
		}
	});

	$(".deleteItem").click(function(){
		$(this).parent().prev().attr("src", "");
		swapEmptyPic1();
	}); 

	function swapEmptyPic1(){
		for(var index = 1; index<=4; index++){
			var Id = 'small' + index;

			if($(document.body).find("#" + Id+current).attr("src") == ""){
				console.log("current empty is " + index +current);
				var temp = index;
				while($(document.body).find("#small" + (temp + 1)+current).attr("src") != "" && temp <= 3){
					console.log("temp is " + temp);
					var j = temp + 1;
					$(document.body).find("#small" + temp+current).attr("src", $(document.body).find("#small" + (j)+current).attr("src"));
					temp++;
				}
				$(document.body).find("#small" + temp+current).attr("src","");
			}
		}
	}

	$("#deletediv"+current).click(function(){
		$("#dynamic"+current).remove();
		height1 = height1 - 300;
		$('.para3').css('height', height1);
		addtime = addtime -1;
	}); 
});



 //if (addtime >=1){
for (var j=1;j<= 10;j++){
	//alert(i);
  (function(i) {
  	$(document.body).on('click','#editDetails'+i, function(e){ 
  		//$(".lightbox_trigger1").each(function(){
 		//alert(this.id);
 		//alert(i);
 		//alert(this.id);
        e.preventDefault();
        var id=this.id;
        var boxId= $(this).attr("href");
       if(boxId == "itemDescriptionLightBox"){
  			var offset = $(this).offset();
  			$('.'+boxId).css('display','block'); 
  			$('.'+boxId).css('top', offset.top-100);
  			$('.'+boxId).css('left', offset.left-700);
	        $('.lightBoxBackground').css('display','block'); 
	        $('.lightBoxBackground').animate({'opacity':'.6'},100,'linear');
	        $('.'+boxId).find(topPic1).attr("src", $(document.body).find("#small1"+i).attr("src"));
	        $('.'+boxId).find(firstBottomPic1).attr("src", $(document.body).find("#small1"+i).attr("src"));
	        $('.'+boxId).find(secondBottomPic1).attr("src", $(document.body).find("#small2"+i).attr("src"));
	        $('.'+boxId).find(thirdBottomPic1).attr("src", $(document.body).find("#small3"+i).attr("src"));
	        $('.'+boxId).find(fourthBottomPic1).attr("src", $(document.body).find("#small4"+i).attr("src"));
  		}

  		$("#savelightbox").unbind("click").click(function(){
  			//alert(i);
  			if ($("#itemtitle").val() != "Enter Title"){
  				itemtitlea[i-1] = $("#itemtitle").val();
  				//alert(itemtitlea[i-1]);
  			}
  			else {
  				itemtitlea[i-1] = "";
  				//alert(itemtitlea[i-1]);

  			}
  			//alert(itemtitle);
  			if ($("#itemprice").val() != "Enter Price"){
  				itempricea[i-1] = $("#itemprice").val();}
  			else{
  				itempricea[i-1] = "";
  			}
  			//alert(itemprice);
  			if ($("#itemdescription").val() != "Description"){
  				itemdescriptiona[i-1] = $("#itemdescription").val();}
  			else{
  				itemdescriptiona[i-1] = "";
  			}
  			//alert(itemdescription);
  			itemcategory = $("#itemcategory").val();
  			//alert(itemcategory);
  			if ($("#itemtags").val() != "Enter custome tags, seperated by commas"){
  				itemtagsa[i-1] = $("#itemtags").val();}
  			else{
  				itemtagsa[i-1] = "";
  			}
  			//alert(itemtags);
  			if ($("#itemshippingtype").val() != "volvo"){
  				itemshippingtypea[i-1] = $("#itemshippingtype").val();}
  			else{
  				itemshippingtypea[i-1] = "";
  			}
  			//alert(itemshippingtype);
  			if ($("#itemshippingcost").val() != "Shipping cost"){
  				itemshippingcosta[i-1] = $("#itemshippingcost").val();}
  			else{
  				itemshippingcosta[i-1] = "";
  			}

  			//alert(id);
  			$('#addImage1').trigger("reset");
  			$('.'+boxId).css('display','none'); 
  			$('.lightBoxBackground').css('display','none'); 

  		});
	});
  })(j);

}

 
	function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
           
            reader.onload = function (e) {
                $('#upload2').attr('src', e.target.result);
            
            };
            reader.readAsDataURL(input.files[0]);
       	 
    	}
    }

    $("#selectButton").change(function(){
        readURL(this);
    });



	/* upload pic AJAX*/
			function handleFileUpload(files,obj)
			{
   				for (var i = 0; i < files.length; i++) 
  				 {
       				 var fd = new FormData();
       				 fd.append('file', files[i]);
        			 previewfile(files[i]);
 
   				}
			}

			function previewfile(file) {
  
    			var reader = new FileReader();
    			reader.onload = function (e) {
     			
     			
      			$('#upload2').attr('src', e.target.result);
      
  				};
    			reader.readAsDataURL(file);
    		
  			} 

			$(document).ready(function()
			{
				var obj = $ ('#draganddrophandler');
				obj.on('dragenter', function (e) 
				{
   				 e.stopPropagation();
    			 e.preventDefault();
    			 $(this).css('border', '2px solid #0B85A1');
				});

				obj.on('dragover', function (e) 
				{
     			e.stopPropagation();
     			e.preventDefault();
				});

				obj.on('drop', function (e) 
				{
     			$(this).css('border', '2px dotted #0B85A1');
     			e.preventDefault();
     			var files = e.originalEvent.dataTransfer.files;
     			//We need to send dropped files to Server
    			 handleFileUpload(files,obj);
				});

				$(document).on('dragenter', function (e) 
				{
    			e.stopPropagation();
    			e.preventDefault();
				});

				$(document).on('dragover', function (e) 
				{
  				e.stopPropagation();
  				e.preventDefault();
  				obj.css('border', '2px dotted #0B85A1');
				});

				$(document).on('drop', function (e) 
				{
    				e.stopPropagation();
   				    e.preventDefault();
				});
 
				});


  
	$('#image_upload').click(function(){
	    var formData = new FormData($('#upload_image')[0]);
	    //ondrop="drop(event)" ondragover="allowDrop(event)"
	  	 if ($("#upload2").attr("src") != "images/icons/drag.png"){
	    
	    	for(var j=1; j<=4; j++){
	    		if($("#small" + j).attr("src") == ""){
	    			$("#small" + j).attr("src", $("#upload2").attr("src"));
	    			break;
	    		}
	    	}
	    	$("#upload2").attr("src","images/icons/drag.png");
	    }
	    $("#upload_image").trigger("reset");
	});


 	$(".leftCursoro").click(function(){
		var thisID = $(this).parent().prev().attr("id");
		if(thisID == "small"+"1"){
			//do nothing
		}
		else{
			console.log("yes");
			var thisSrc = $(this).parent().prev().attr("src");
			var prevSrc = $(this).parent().parent().prev().children().first().attr("src");
			$(this).parent().prev().attr("src", prevSrc);
			$(this).parent().parent().prev().children().first().attr("src", thisSrc);
		}
		console.log("ah");
		console.log($(".selectButton")[0].files);
	});

	$(".rightCursoro").click(function(){
		var thisID = $(this).parent().prev().attr("id");
		if(thisID == "small"+"4"){
			//do nothing
		}
		else{
			console.log("yes");
			var thisSrc = $(this).parent().prev().attr("src");
			console.log(thisSrc);
			var nextSrc = $(this).parent().parent().next().children().first().attr("src");
			console.log(nextSrc);
			$(this).parent().prev().attr("src", nextSrc);
			$(this).parent().parent().next().children().first().attr("src", thisSrc);
		}
	});


	$(".deleteItemo").click(function(){
		$(this).parent().prev().attr("src", "");
		swapEmptyPic();
	});

	function swapEmptyPic(){
		for(var index = 1; index<=4; index++){
			var Id = 'small' + index;

			if($(document.body).find("#" + Id).attr("src") == ""){
				console.log("current empty is " + index);
				var temp = index;
				while($(document.body).find("#small" + (temp + 1)).attr("src") != "" && temp <= 3){
					console.log("temp is " + temp);
					var j = temp + 1;
					$(document.body).find("#small" + temp).attr("src", $(document.body).find("#small" + (j)).attr("src"));
					temp++;
				}
				$(document.body).find("#small" + temp).attr("src","");
			}
		}
	}


		$("#button2").click(function(){
		var formdata = new FormData($("#paraform")[0]);
		for (var i=1; i<=4; i++){
	    	if($("#small" + i).attr("src") != ""){
	    		formdata.append("small"+i, $(document.body).find("#small"+i).attr("src"));
	    		console.log($(document.body).find("#small"+i).attr("src"));
	    	}
	    	else{
	    		console.log("empty");
	    	}  	
		}

		for (var j=1; j<= addtime; j++)
			for (var i=1; i<=4; i++){
				if($("#small" + i+j).attr("src") != ""){
	    		formdata.append("small"+i+j, $(document.body).find("#small"+i+j).attr("src"));
	    		console.log($(document.body).find("#small"+i+j).attr("src"));
	    	}
	    	else{
	    		console.log("empty");
	    	}  	
		}

		formdata.append("addtime", addtime);
		formdata.append("itemtitle", itemtitle);
		formdata.append("itemprice", itemprice);
		formdata.append("itemdescription", itemdescription);
		formdata.append("itemcategory", itemcategory);
		formdata.append("itemtags", itemtags);
		formdata.append("itemshippingtype", itemshippingtype);
		formdata.append("itemshippingcost", itemshippingcost);

		for (var j=1; j<= addtime; j++){
			if (j <=10){
				//alert(j);
				//alert(itemtitlea[j-1]);
				formdata.append("itemtitlea"+j, itemtitlea[j-1]);
				formdata.append("itempricea"+j, itempricea[j-1]);
				formdata.append("itemdescriptiona"+j, itemdescriptiona[j-1]);
				formdata.append("itemcategorya"+j, itemcategorya[j-1]);
				formdata.append("itemtagsa"+j, itemtagsa[j-1]);
				formdata.append("itemshippingtypea"+j, itemshippingtypea[j-1]);
				formdata.append("itemshippingcosta"+j, itemshippingcosta[j-1]);
			}
		}

		
		$.ajax({
	        url: 'upload.php',  //Server script to process data
	        type: 'POST',
	        xhr: function() {  // Custom XMLHttpRequest
	            var myXhr = $.ajaxSettings.xhr();
	            if(myXhr.upload){ // Check if upload property exists
	                myXhr.upload.addEventListener('progress',progressHandlingFunction, false); // For handling the progress of the upload
	            }
	            return myXhr;
	        },
	        //Ajax events
	        //beforeSend: beforeSendHandler,
	        success:completeHandler3,
	        // error: errorHandler,
	        data: formdata,
	        cache: false,
	        contentType: false,
	        processData: false
	    });

	});

    function progressHandlingFunction(e){
		console.log("in progress");
	}

	function completeHandler3(e){
	    console.log(e);
      //alert(e);
       var url = "new_stuff.php";    
       $(location).attr('href',url);
	  	
	}

/*Item description light box js */
	$('.itemDescriptionLightBox>.middle>.itemDescription>.enterTitle').focus(function(){
		if($(this).val() == 'Enter Title'){
			$(this).val("");
		}
	});
	$('.itemDescriptionLightBox>.middle>.itemDescription>.enterPrice').focus(function(){
		if($(this).val() == 'Enter Price'){
			$(this).val("");
		}
	});
	$('.itemDescriptionLightBox>.middle>.itemDescription>.itemDes').focus(function(){
		if($(this).val() == "Description"){
			$(this).val('');
		}
		
	});
	$('.itemDescriptionLightBox>.middle>.itemDescription>.itemTags').focus(function(){
		if($(this).val() == 'Enter custome tags, seperated by commas'){
			$(this).val("");
		}
	});
	$('.itemDescriptionLightBox>.middle>.itemDescription>.shippingCost').focus(function(){
		if($(this).val() == 'Shipping cost'){
			$(this).val("");
		}

	});
	$('#login_form>#password').focus(function(){
		$(this).val("");
		$(this).attr('type', 'password');
	});	

	$("#firstBottomPic1").click(function(){
		$(document.body).find(topPic1).attr("src", $(document.body).find(small1).attr("src"));
	});
	$("#secondBottomPic1").click(function(){
		$(document.body).find(topPic1).attr("src", $(document.body).find(small2).attr("src"));
	});
	$("#thirdBottomPic1").click(function(){
		$(document.body).find(topPic1).attr("src", $(document.body).find(small3).attr("src"));
	});
	$("#fourthBottomPic1").click(function(){
		$(document.body).find(topPic1).attr("src", $(document.body).find(small4).attr("src"));
	});




});
