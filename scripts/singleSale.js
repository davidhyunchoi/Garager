$(document).ready(function()
{   
	var imagearray = [];
	var imagemax = 4;
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

	/*Global array to store pic src */
	var formData = new FormData();
	var picSrc = new Array();
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

	function handleFileUpload(files,obj){
		for (var i = 0; i < files.length; i++) {
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

	$('.image_upload').click(function(){
	    var formData = new FormData($('#upload_image')[0]);
	    //ondrop="drop(event)" ondragover="allowDrop(event)"
	  	 if ($("#upload2").attr("src") != "images/icons/drag.png"){
	    
	    	for(var j=1; j<=4; j++){
	    		if($("#small"+j).attr("src") == ""){
	    			$("#small"+j).attr("src", $("#upload2").attr("src"));
	    			break;
	    		}
	    	}
	    	$("#upload2").attr("src","images/icons/drag.png");
	    }
	    $("#upload_image").trigger("reset");
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


	/* Upload Image JS */
	$(".selectFile").click(function () {
	    $(".selectButton").trigger('click');
	});

	$(".uploadPic").click(function () {
	    $(".image_upload").trigger('click');
	});

	function readURL(input) {
		var inputFiles = input.files;
		var count = 1;
        for(var i=0; i<inputFiles.length; i++){
        	var file = inputFiles[i];
        	// var formData = new FormData();
        	formData.append("image1", file);
        	var reader = new FileReader();
            reader.onload = function (e) {
            	while($('#small'+ count).attr('src') !='' && count<=4){
            		count++;
            	}
            	if(count <= 4){
            		$('#small'+ count).attr('src', e.target.result);
					// formData.append("image1", e.target.result);
            		picSrc[count-1] = e.target.result;
            	}
            }
            reader.readAsDataURL(file);	
        }
    }
 

    $(".selectButton").change(function(){
        readURL(this);
    });

	/* upload pic AJAX*/
	$('#button2').click(function(){
		console.log("ah");
        var formdata = new FormData();
        // formdata.append("data", $(document.body).find("#small1").attr("src"));
        // formdata.append("data1", $(document.body).find("#small2").attr("src"));
        // console.log($(document.body).find("#small1").attr("src"));
        // console.log($(document.body).find("#small2").attr("src"));
        // console.log(formdata);
        // formdata.append("image1", '');
        for (var i=1; i<=4; i++){
	    	if($("#small" + i).attr("src") != ""){
	    		formdata.append("small"+i, $(document.body).find("#small"+i).attr("src"));
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
	        success: completeHandler,
	        // error: errorHandler,
	        data: formdata,
	        cache: false,
	        contentType: false,
	        processData: false
	    });
	});

	function progressHandlingFunction(e){
		var done = e.position || e.loaded, total = e.totalSize || e.total;
		console.log((done/1024) + "  " + (total/1024));
        console.log('xhr progress: ' + (Math.floor(done/total*1000)/10) + '%');
	}

	function completeHandler(e){
	    // // console.log(e);
	    // // var pic = e;
	    // var index = 0;
	    // for(var i=1; i<=4; i++){
	    // 	if($("#small" + i).attr("src") == ""){
	    // 		// console.log(e);
	    // 		$("#small" + i).attr("src", 'data:image/jpg;base64,' + e);
	    // 		index++;
	    // 	}
	    // }
	    window.location.replace("new_stuff.php");
	}
	
	$(document.body).on('click','#editDetails',function(e){ 
  		//$(".lightbox_trigger1").each(function(){
 		//alert(this.id);
 		// alert(this.id);
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
  			// $("#addImage1").trigger("reset");
  			$('.'+boxId).css('display','none'); 
  			$('.lightBoxBackground').css('display','none'); 
  		});
    //}); 
	});



	/* swap pic JS */
	$(".leftCursor").click(function(){
		var thisID = $(this).parent().prev().attr("id");
		if(thisID == "small"+"1"){
			//do nothing
		}
		else{
			var thisSrc = $(this).parent().prev().attr("src");
			var prevSrc = $(this).parent().parent().prev().children().first().attr("src");
			$(this).parent().prev().attr("src", prevSrc);
			$(this).parent().parent().prev().children().first().attr("src", thisSrc);
		}
		// console.log("ah");
		// console.log($(".selectButton")[0].files);
	});

	$(".rightCursor").click(function(){
		var thisID = $(this).parent().prev().attr("id");
		if(thisID == "small"+"4"){
			//do nothing
		}
		else{
			// console.log("yes");
			var thisSrc = $(this).parent().prev().attr("src");
			// console.log(thisSrc);
			var nextSrc = $(this).parent().parent().next().children().first().attr("src");
			// console.log(nextSrc);
			$(this).parent().prev().attr("src", nextSrc);
			$(this).parent().parent().next().children().first().attr("src", thisSrc);
		}
	});

	$(".deleteItem").click(function(){
		$(this).parent().prev().attr("src", "");
		swapEmptyPic();
	});

	function swapEmptyPic(){
		for(var index = 1; index<=4; index++){
			var Id = 'small' + index;

			if($(document.body).find("#" + Id).attr("src") == ""){
				var temp = index;
				while($(document.body).find("#small" + (temp + 1)).attr("src") != "" && temp <= 3){
					var j = temp + 1;
					$(document.body).find("#small" + temp).attr("src", $(document.body).find("#small" + (j)).attr("src"));
					temp++;
				}
				console.log(temp);
				$(document.body).find("#small" + temp).attr("src","");
			}
		}

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
