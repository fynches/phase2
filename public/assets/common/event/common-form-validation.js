var html_addimage = $(".file_input_div").html();

$(document).ready(function () {  
	
	$(function () {
        'use strict';
        $('#img_upload_start').fileupload({
        	acceptFileTypes: /^image\/(gif|jpe?g|png|bmp)$|^application\/(pdf|msword)$|^text\/plain$|(\.|\/)(mp4|doc|docx|xls|xlsx|mov)$/i,
        	minFileSize: 1,
        	maxNumberOfFiles: 12,
            url: window.base_url + '/admin/multiple_img_upload',
            autoUpload: false            
        });
    }).bind('fileuploadsend', function (e, responsedata) {
			//console.log('aaa',responsedata.files[0].name);
			var img_name = responsedata.files[0].name;
			$('#multiple_event_images').val(img_name);
	});

	// $("form").submit(function(e){
// 		
		// var img_flag= false;
		// $( ".preview" ).each(function( index ) {
			// var img_title= $(this).find('a').attr('title');
			// if(img_title==undefined)
			// {
				// //alert('please upload image');
				// $('#img_upload_div').append('<p id="img_upload_err"><label id="title-error" class="error" for="title">Please upload image.</label></p>');
				// img_flag = false;
			// }else{
				// //alert('thanks');
				// img_flag=  true;
			// }
		// });
		// return img_flag;
	// });

	
	$(document).on('click', '.cancel', function(){
		
		$(this).parent().parent().fadeOut('fast',function() {
			$(this).remove()
		});
	});
	
	$(document).on('click', '.cancel_all', function(){
		
		$(this).parent().parent().fadeOut('fast',function() {
			$('.template-upload').remove();
			$('#img_upload_div').show();
			
		});
	});
	
    $(function() {
        var date = new Date();
        date.setDate(date.getDate());
        $( "#event_publish_date" ).datepicker({format: 'dd-mm-yyyy'});
		$( "#event_end_date" ).datepicker({format: 'dd-mm-yyyy'});
    });  
     
    $('#event_publish_date').on('changeDate', function(event) {
        var date = new Date($(this).datepicker('getDate')); 
        var nowDate = new Date(date);
        
        $("#event_end_date").datepicker({format: 'dd-mm-yyyy'});
        $( "#event_end_date" ).datepicker('setStartDate',nowDate);
    });
    
    $('#event_end_date').on('changeDate', function(event) {
        var date = new Date($(this).datepicker('getDate')); 
        var nowDate = new Date(date);
        $("#event_publish_date").datepicker({format: 'dd-mm-yyyy'});
        $( "#event_publish_date" ).datepicker('setEndDate',nowDate);
    });
    
    

    $('#event').load(function(){});

    CKEDITOR.replace('description',
    {
        toolbar: 'Basic',  
        uiColor: '#9AB8F3'
    });

        CKEDITOR.on('instanceReady', function () {
            $.each(CKEDITOR.instances, function (instance) {
                CKEDITOR.instances[instance].document.on("keyup", CK_jQ);
                CKEDITOR.instances[instance].document.on("paste", CK_jQ);
                CKEDITOR.instances[instance].document.on("keypress", CK_jQ);
                CKEDITOR.instances[instance].document.on("blur", CK_jQ);
                CKEDITOR.instances[instance].document.on("change", CK_jQ);
            });
        }); 
    //ckeditor juery validation remove when user type any thing into it
    function CK_jQ() { 
        for (instance in CKEDITOR.instances) {
            var str = CKEDITOR.instances[instance].getData();
            if(getPlainText(str)!=""){
                $("#description-error").remove();
            } 
            CKEDITOR.instances[instance].updateElement();
        }
    }
    //ckeditor remove space trim date when user type in ckeditor and ignore p tag for checking
    function getPlainText( strSrc ) {
        var resultStr = "";
        
        // Ignore the <p> tag if it is in very start of the text
        if(strSrc.indexOf('<p>') == 0)
            resultStr = strSrc.substring(3); 
            resultStr = resultStr.replace(/<p>/gi, "\r\n\r\n");
            // Replace <br /> with one newline
            resultStr = resultStr.replace(/<br \/>/gi, "\r\n");
            resultStr = resultStr.replace(/<br>/gi, "\r\n"); 
             
            // Strip off other HTML tags. 
            resultStr = resultStr.replace(/\r?\n|\r/gm," "); // remove line breaks   
            resultStr = resultStr.replace(/\s\s+/g, " ").trim(); 
            return  resultStr.replace( /<[^<|>]+?>/gi,'' );
    }
    

    $('#event').validate({
        ignore: [],   
        rules: {
            user_id: "required",
            title: "required",
            publish_url: {
	            remote: {
					    //url: "/validate-publish-url",
					    url: '/admin/event/validate-publish-url/',
					    type: "get",
					    data: {
					      _token: function() {
					        return "{{csrf_token()}}"
					      }
					    }
					  },
	        },
            first_name:"required",
            last_name:"required",
            age_range: "required",
            child_name: "required",
            event_publish_date: "required",
            event_end_date : "required",
            //zipcode: "required",
            zipcode: {
                required: true,
                maxlength: 5
            },
            description:"required"
        },
        messages: {
            user_id: "Please select user.",
            title: "Please enter your event title.",
            publish_url: "Publish url already exits,Please try another",
            age_range: "Please select age range.",
            event_publish_date:"Please choose event publish date",
            event_end_date : "Please choose event end date",
            //zipcode: "Please enter zipcode",
            zipcode: {
                required: "Please enter zipcode",
                maxlength: "Zipcode must be maximum 5 characters long."
            },
            description:"Please enter descriptions"
        },
        errorPlacement: function(error, element) 
        {
            if (element.attr("name") == "description"){ 
                error.insertAfter("#cke_description");
            }else{
                error.insertAfter(element);
            }  
        }
    });

    // Image change ans show over their
    var bannerOption = $('#image_type').val();
    if(bannerOption == "0"){
        $(".videoFields").hide();
    }else{
        $(".imageFields").hide();
    }
    $('.uploadBanner').change(function(){
        var bannerOption = $(this).val();
        
        if(bannerOption == "1")
        {
            $(".videoFields").show();
            $(".imageFields").hide();
            $("input[type='radio']").change();
        }
        else
        {
            $(".imageFields").show();
            $(".videoFields").hide();
        }
    });

    $('#upload_img').click(function(){
      $('.images').click();
      return false;
    });

    $('#upload_background_img').click(function(){
       $('.background_image').click();
       return false;
    });

    $(".images").change(function () {
        readURL(this);
    });

    $(".background_image").change(function () {
        readURLBackground(this);
    }); 

    $("input[name= 'flag_video']").on('change', function(){
        var value = $('input[name=flag_video]:checked').val(); 
        if($('input[name=flag_video]:checked').val() == '1'){ 
            $('.video_file').show();
            $('.video_url').hide();
            $('#file').prop('checked', true);
            $('#url').prop('checked', false);
        }else{
            $('.video_file').hide();
            $('.video_url').show();
            $('#file').prop('checked', false);
            $('#url').prop('checked', true);
        }
        return false;
    });

    function readURL(input) {
        if (input.files && input.files[0]) {

            var reader = new FileReader();
            reader.onload = function (e) {

                if($('#preview_image').length === 0){
                    $("#profileImg").html("<a href = '"+e.target.result + "' target='_blank'> <img id='preview_image' src = '"+e.target.result + "' class='m-r-sm' style='max-width: 255px; max-height: 255px;'></a> ");
                }else{
                    $('#preview_image').attr('src', e.target.result);
                }

            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function readURLBackground(input) {

        if (input.files && input.files[0]) {

            var reader = new FileReader();
            reader.onload = function (e) {

                if($('#preview_background').length === 0){
                    $("#preview_background").html("<a href = '"+e.target.result + "' target='_blank'> <img id='preview_image' src = '"+e.target.result + "' class='m-r-sm' style='max-width: 255px; max-height: 255px;'></a> ");
                }else{
                    $('#preview_background').attr('src', e.target.result);
                }

            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $(".image-checkbox").each(function () {
	  if ($(this).find('input[type="checkbox"]').first().attr("checked")) {
	    	$(this).addClass('image-checkbox-checked');
	  }
	  else {
	    $(this).removeClass('image-checkbox-checked');
	  }
	});
	
	
	$(".image-checkbox").on("click", function (e) {
	  $(this).toggleClass('image-checkbox-checked');
	  var $checkbox = $(this).find('input[type="checkbox"]');
	  $checkbox.prop("checked",!$checkbox.prop("checked"))
	
	  e.preventDefault();
	});

});
