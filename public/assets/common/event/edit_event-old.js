var html_addimage = $(".file_input_div").html();

$(document).ready(function () {  
	
	$(function () {
        'use strict';
        $('#event').fileupload({
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


	
	$(document).on('click', '.cancel', function(){
		
		$(this).parent().parent().fadeOut('fast',function() {
			$(this).remove()
		});
	});
	
	$(document).on('click', '.edit_cancel', function(){
		var checkstr =  confirm('are you sure you want to delete this image?');
		
		if(checkstr == true)
		{
			$(this).parent().parent().fadeOut('fast',function() {
			 	$(this).remove();
 			});
			var delete_img_id = $(this).attr('data-id');
			
       	 	$.ajax({
                type: "GET",
                url: window.base_url + '/admin/event/delete_event_img/'+delete_img_id,
                success:function(result){}
                });
       	}
		else
		{ 
			return false;
		}
		
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
        $( "#datepicker" ).datepicker({format: 'dd-mm-yyyy', startDate: date});
		$( "#datepicker2" ).datepicker({format: 'dd-mm-yyyy', startDate: date});
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
            //description: "required",
            age_range: "required",
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
            //description: "Please enter event descriptions.",
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
    $("input[type='radio']").change();
    if(bannerOption == "0"){
        $(".videoFields").hide();
    }else{
        $(".imageFields").hide();
    }
    $('.uploadBanner').change(function(){
        var bannerOption = $(this).val();
        //alert(bannerOption);
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

});
