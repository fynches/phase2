$(document).on('keypress', '.number', function(event) {

    var $this = $(this);
    
    if ((event.which != 46 || $this.val().indexOf('.') != -1) &&
       ((event.which < 48 || event.which > 57) &&
       (event.which != 0 && event.which != 8))) {
           event.preventDefault();
    }
});

$(document).on('click', '.delete_events', function(event) {
	if (confirm('Are you sure you want to delete this event?')) {
		var event_id= $(this).attr('data-id');
		var redirect_page= window.base_url+'/delete-event/'+event_id;
		window.location.href= redirect_page;
	} else{
        return false;
    }
});

$(document).ready(function () {
	
	$('#add-custome-exp').click(function(e){		
		$('#tabs a[href="#pane-B"]').tab('show');
	})

	//get zipcode by ip address function for step 2 form
	var zipcode_val= $('#zipcode').val();
	
	if(zipcode_val=="")
	{
		$.get("http://ipinfo.io", function(response) {
			console.log('response is ',response);
			console.log('zipcode is ',response.postal);
		$("#zipcode").val(response.postal);
		}, "jsonp");	
	}
	
	//event step one form validation
	
	$('#event_step_one_form').validate({
	    rules: {
	        age_range: {
	            required: true,
	            number:true,
	        },
	    },
	    messages: {
	        age_range: "Please enter age.",
	        number:"Please enter valid age",
	    },
	});
	
	//event step two form validation
	
	$('#event_step_two_form').validate({
	    rules: {
	        zipcode: {
	            required: true,
	            number:true,
	            maxlength: 5
	        },
	        
	    },
	    messages: {
	        zipcode: {
                required: "Please enter zipcode",
                number:"Please enter valid zipcode",
                maxlength: "Zipcode must be maximum 5 characters long."
            },
	    },
			
	});
	
	//event step three form validation
	
	$('#event_step_three_form').validate({
	    rules: {
	        "tag_id[]": { 
                     required: function(element) {
				        if ( $('#other_tag').val() == "" ) {
				          return true;
				        }else{
				        	return false;
				        }
				    },
                    //minlength: 1 
            } 
	    },
	    messages: {
	       "tag_id[]": "Please select at least one favorite activites."
	    },
	    errorPlacement: function(error, element) 
        {
            if (element.attr("name") == "tag_id[]"){ 
                error.insertAfter("#divMsg");
            }else{
                error.insertAfter(element);
            }  
        }
			
	});
	
	//event step four form validation
	
	$('#event_step_four_form').validate({
	    rules: {
	        event_end_date: {
	            required: true,
	        },
	        
	    },
	    messages: {
	        event_end_date: "Please select event end date."
	    },
	});
	
	//event image upload or video upload popup form validation
	
	$('#popup_uploads').validate({
	    rules: {
	        youtube_url: {
	            required: function(element){
	            	return $(".local_uploads").val()=="";
        		}
	        },
	    },
	    messages: {
	        youtube_url: "Please upload video or enter youtube url."
	    },
	});
	
	function getyoutubeId(url) {
	    var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
	    var match = url.match(regExp);
	
	    if (match && match[2].length == 11) {
	        return match[2];
	    } else {
	        return 'error';
	    }
	}

	$('#event_upload_popup').click(function(){
		
		$('.dynamic_imgs').html('');
		if($("#popup_uploads").valid())
		{
			var selected_radio = $('input[name=upload_section]:checked').val();
			$('#video_option').val(selected_radio);
			var $el = $('.local_uploads');
		    $el.wrap('<form>').closest('form').get(0).reset();
		    $el.unwrap();
			
			var video_name= $('#video_name').val();
			var youtube_url = $('#youtube_url').val();
			
			$('#video').remove();
			
			if(youtube_url!="")
			{
				$('.dynamic_imgs').show();
				if (youtube_url.toLocaleLowerCase().indexOf("youtube")!=-1)
				{
					var youtubeId = getyoutubeId(youtube_url);
					var youtube_url_value = 'https://www.youtube.com/embed/'+ youtubeId;
					var append_video_html = '<li class="img-wrap remove_video"><span class="close youtube_cancel">×</span><iframe width="260" height="120" src="//www.youtube.com/embed/' + youtubeId + '" frameborder="0" allowfullscreen></iframe></li>';
													
					$('.dynamic_imgs').html(append_video_html);
					$( '<input type="hidden" id="video" name="video" value='+youtube_url+'>' ).insertAfter( $( "#flag_video" ) );
					$('#flag_video').val('0');
					$('#video').val(youtube_url_value);
				}
			}
			
			$('#upload_photos').modal('hide');
		}
	});
	
	$(document).on('click', '.remove_video', function(event) {
		$(this).remove();
		$('.video_image_name').remove();
		$('#youtube_url').val('');
		$('.dynamic_imgs').hide();
	});
	
	//event-create form form validation
	
	var edit_event_id= $('#event_id').val();
	
	$('#event_create_form').validate({
	    rules: {
	    	// "image[]": { 
                     // required: true,
            // }, 
	        event_title: {
	            required: true,
	            remote: {
					    url: '/validate-event-url/',
					    type: "get",
					    data: {
					       event_id:edit_event_id,	
					      _token: function() {
					        return "{{csrf_token()}}"
					      }
					    }
					  },
	        },
	        event_publish_date:{
	        	required: true,
	        },
	        description:{
	        	required: true,
	        },
	        publish_url: {
	        	required: true,
	            remote: {
					    url: '/validate-url/',
					    type: "get",
					    data: {
					       event_id:edit_event_id,	
					      _token: function() {
					        return "{{csrf_token()}}"
					      }
					    }
					  },
	        },
	    },
	    messages: {
	    	// "image[]": {
                // required: "Please upload an image",
            // },
	        event_title: {
                required: "Please enter event title",
                remote:"Publish url already exits,Please try another",
            },
            event_publish_date: {
                required: "Please select date",
            },
            description: {
                required: "Please enter description",
            },
            publish_url: {
                required: "Please enter event publish url",
                remote:"Publish url already exits,Please try another",
            },
	    },
	    errorPlacement: function(error, element) 
        {
            if (element.attr("name") == "event_publish_dated"){ 
                error.insertAfter("#event_date");
            }else if (element.attr("name") == "image[]"){ 
                error.insertAfter(".custom_video");
            }else{
                error.insertAfter(element);
            }  
        }
			
	});
	
	
	//if step 1 form valid then hide first step and show second step
	
	$('#first_next').click(function(){
		
		if($("#event_step_one_form").valid())
		{
			$('#event_section_one').hide();
			$('#event_section_two').show();
			$('#round_step ul li.active').removeClass('active').next('li').addClass("active");
		}
	});
	
	//if step 2 form valid then hide second step and show third step
	
	$('#second_next').click(function(){
		
		if($("#event_step_two_form").valid())
		{
			$('#event_section_two').hide();
			$('#event_section_three').show();
			$('#round_step ul li.active').removeClass('active').next('li').addClass("active");
		}
	});
	
	//if step 3 form valid then hide third step and show fourth step
	
	$(document).on('click','.third_next',function(event){
    	if($("#event_step_three_form").valid())
		{
			$('#event_section_three').hide();
			$('#event_section_four').show();
			$("html, body").animate({scrollTop: 0}, 1000);
			$('#round_step ul li.active').removeClass('active').next('li').addClass("active");
		}
    });
    
    $('#descriptions').keyup(function () {
    	//$('.text_remain').hide();
        var begin = 0;
        var max = 1000;
        var len = $(this).val().length;
      //  alert(len);
        if (len >= max) {
            $('#charnum').text(' you have reached the limit');
        } else {
            var char = begin + len;
            $('#charnum').text(char + ' / 1000 text remaining');
        }
    });
    $('#descriptions').keyup();
	
	//if step 4 form valid then get values from step1,step2,step3 and step4 and form submit
	
	$('#fourth_next').click(function(){
		
		if($("#event_step_four_form").valid())
		{
			var age_range_val= $('#age_range').val();
			var zip_code_val= $('#zipcode').val();
			var favourite_activity_val = $('[id*="tag_checkbox"]:checked').map(function() { return $(this).val().toString(); } ).get().join(",");
			var other_tag_val= $('#other_tag').val(); 
			
			//alert(zip_code_val);
			$('#event_age_range').val(age_range_val);
			$('#event_zipcode').val(zip_code_val);
			$('#event_favourite_activity').val(favourite_activity_val);
			$('#event_other_tags').val(other_tag_val);
			
			$("#event_step_four_form").submit();
		}
	});
	
	//step 3 checkbox toggle functionality
	
	$("#event_section_three ul li").click(function() {
        
        if($(this).find(':checkbox').prop("checked") == true){
        	$(this).find(':checkbox').attr('checked','checked');
        	$('#static_div').remove();
        	//alert($('#divMsg').css('display') );
        	if ($('#divMsg').css('display') == 'none')
			{
				$( '<div id="static_div"><div class="row"><div class="col-sm-3"><input type="button" name="next" value="Next" class="third_next"></div></div></div>').insertAfter( "#event_step_three_form ul" );
			}
        }

        else if($(this).find(':checkbox').prop("checked") == false){
    	    $(this).find(':checkbox').removeAttr('checked','checked');
        }
    });
	
	
 $(function() {
 		var event_id= $('#event_id').val();
 		if(event_id=="0" || event_id == undefined)
		{
			 var date = new Date();
	        date.setDate(date.getDate());
	        var end_date = $( "#event_end_date" ).val();
	        
	        date.setDate(date.getDate());
	        
	        
	        $('.event_end_date').datepicker({format:'mm/dd/yyyy', startDate: date,'autoclose': true });
			$( ".event_publish_date" ).datepicker({'format': 'mm/dd/yyyy',startDate: date,'autoclose': true});
	        $( ".event_publish_date" ).datepicker('setEndDate',end_date );
		}
		else
		{
			 var date = new Date();
			 console.log(date);
	         date.setDate(date.getDate());
	         var end_date = $( "#edit_event_end_date" ).val();
	         date.setDate(date.getDate());
	        
	         $('.event_end_date').datepicker({format:'mm/dd/yyyy', startDate: date,'autoclose': true });
			 $( ".event_publish_date" ).datepicker({'format': 'mm/dd/yyyy',startDate: date,'autoclose': true});
	         $( ".event_publish_date" ).datepicker('setEndDate',end_date );
		}
       
	});  
     	
});

$(document).on('click','.addeventimage .close',function(event){
	$(this).parent('li').remove();
});


$(document).on('keyup', '#event_title', function(e) {
	var title =  $.trim($(this).val());
	title = convertToSlug(title);
	$('#publish_url').val(title);
});

$(document).on('keyup', '#publish_url', function(e) {
	var title = $(this).val();
	title = convertToSlug(title);
	$(this).val(title);
});

function convertToSlug(Text)
{
    return Text
        .toLowerCase()
        .replace(/ /g,'-')
        .replace(/[^\w-]+/g,'');
}

$(function() {
    // Multiple images preview in browser
    var imagesPreview = function(input, placeToInsertImagePreview) {
    	
    	var video_name = $('#video').val();
    	
    	if(video_name!="" && video_name!= undefined)
    	{
    		//alert(video_name);
    		$('.dynamic_imgs').html('');
    		$('#video').val('');
    		$('.video_image_name').remove();	
    	}
    	
		var file_type= input.files[0].type;
		
		var ext = input.files[0].name.split('.').pop().toLowerCase();
		var selected_radio = $('input[name=upload_section]:checked').val();
		//alert(selected_radio);
		var edit_event_id= $('#event_id').val();
		if (input.files && file_type== 'image/jpeg' || file_type== 'image/png') {
            var filesAmount = input.files.length;
            var count_img= $('.dynamic_imgs li').length;
			$('.dynamic_imgs').show();
			$('#youtube_url').val('');
			for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();
				
				if(count_img >= 5)
				{
					alert('You cannot upload more than 5 images');
					$('#upload_photos').modal('hide');
					return false;
				}else{
					
					reader.onload = function(event) {
	                    $('<li class="img-wrap addeventimage"><span class="close">&times;</span><img src='+event.target.result+'><input type="hidden" name="event_images[]" value='+event.target.result+'></li>').appendTo(placeToInsertImagePreview);
	                    $('#upload_photos').modal('hide');
                	}
                	count_img++;
				}
              reader.readAsDataURL(input.files[i]);
              $('#flag_video').val("0");
            }
        }else{
        		
        	   if($.inArray(ext, ['webm','mkv','flv','wmv','mp4']) == -1) {
        	   		if(selected_radio=="video")
        	   		{
        	   			alert('invalid extension!');	
        	   		}else{
        	   			alert('invalid extension!');
        	   		}
				    
				    return false;
				}
				
				var reader = new FileReader();
				
				var video_name= input.files[0].name;
        		$('#youtube_url').val(video_name);
        		$('.dynamic_imgs').show();
        		
        		 var fileInput = document.getElementById('filebutton');
			     var fileUrl = window.URL.createObjectURL(fileInput.files[0]);
			     var append_video_html = '<li class="img-wrap remove_video"><span class="close youtube_cancel">×</span><video height="120px" controls><source src="'+fileUrl+'" type="video/mp4"><source src="'+fileUrl+'" type="video/ogg"></video></li>';
				 $('.dynamic_imgs').html(append_video_html);
				 
				 $('#flag_video').val('1');
				 $('#video').val(video_name);
				 //alert(video_name);
				 //$('.local_uploads').val(video_name);
				 $('#upload_photos').modal('hide');
        }

    };

    $('#filebutton').on('change', function() {
    	imagesPreview(this, '.dynamic_imgs');
    });
    
    $('.local_upload').on('click',function(){
    	$('.local_uploads').click();
    });
    
    $('.show_modal').on('click',function(){
    	$('.local_uploads').removeAttr("disabled");
	 });
	 
	 $('#upload_photos').on('hidden.bs.modal', function () {
	 	//$(".local_uploads").attr("disabled","disabled");
	});
});


 	window.fbAsyncInit = function() {
        FB.init({
            appId      : '2043136885924472',
            xfbml      : true,
            version    : 'v2.8'
        });
        
    // FB.AppEvents.logPageView();
    };

    (function(d, s, id){
         var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    function logInWithFacebook(){
    	
    	FB.getLoginStatus(function(response){
        	//console.log('5555',response.status);
            if(response.status === 'connected'){
            	
                var fb_access_token= response.authResponse.accessToken;
		         $('body').loading('start');
	        	 $.ajax({
	            	type: "GET",
	           		url:"https://graph.facebook.com/v3.0/me/photos?fields=picture%2Cname&limit=100&type=uploaded&access_token="+fb_access_token+"",
		            success: function (response) {
		               $('.dynamic_imgs').show();	
		               $('.dynamic_imgs').html('');
		               var images= 	response.data;
		              // $('.dynamic_imgs').html('');
		               for (var i=0; i<response.data.length; i++) {
		               	
		               		var count_img= $('.dynamic_imgs li').length;
							
							//alert(count_img);
							if(i > 4)
		               		{
		               			break; 
		               		}
		               		
							if(count_img >= 5)
							{
								alert('You cannot upload more than 5 images');
								$('#upload_photos').modal('hide');
								$('body').loading('stop');
		                		$('#upload_photos').modal('hide');
								return false;
							}
							else
							{
								count_img++;
								$('<li class="img-wrap addeventimage"><span class="close">&times;</span><img src='+response.data[i].picture+'><input type="hidden" name="facebook_images[]" value='+response.data[i].picture+' class="facebook_images"></li>').appendTo('.dynamic_imgs');
							}
						}
						$('body').loading('stop');
		                $('#upload_photos').modal('hide');
		                
		            }
	        	});
            }  else {
               FB.login(function(response){
        	    	var fb_access_token= response.authResponse.accessToken;
		        	$('body').loading('start');
		        	 $.ajax({
		            	type: "GET",
		           		url:"https://graph.facebook.com/v3.0/me/photos?fields=picture%2Cname&limit=100&type=uploaded&access_token="+fb_access_token+"",
			            success: function (response) {
			            
			               $('.dynamic_imgs').show();	
			               $('.dynamic_imgs').html('');
			               var images= 	response.data;
			               
			               for (var i=0; i<response.data.length; i++) {
			               		
			               		var count_img= $('.dynamic_imgs li').length; 
			               		
			               		if(i > 4)
			               		{
			               			break; 
			               		}
			               		
			               		if(count_img >= 5)
								{
									alert('You cannot upload more than 5 images');
									$('#upload_photos').modal('hide');
									$('body').loading('stop');
			                		$('#upload_photos').modal('hide');
									return false;
								}else{
									count_img++;
									$('<li class="img-wrap addeventimage"><span class="close">&times;</span><img src='+response.data[i].picture+'><input type="hidden" name="facebook_images[]" value='+response.data[i].picture+' class="facebook_images"></li>').appendTo('.dynamic_imgs');
								}
			               	}
							$('body').loading('stop');
			                $('#upload_photos').modal('hide');
			            }
		        	});
		
		        });
            }
        });
    }
   

$(".submit_event").click(function(){
	
	
    if($('#event_create_form').valid())
    {
    	$('#update_stripe_flag').val("");
    	$('body').loading('start');
		$('#experience_create :input').clone().hide().appendTo('#event_create_form');
	    $('#event_create_form').submit();
    }else{
    	$("html, body").animate({scrollTop: 0}, 1000);	
    }
});

$(document).on('click','.connect_to_stripe',function(event){
	
	var event_id= $('#event_id').val();
	
	if(event_id!="" && event_id!="0")
	{
		$('#update_stripe_flag').val("0");	
	}
	
	$('#event_publish_flag').val("1");
	$('#banking-information').modal('hide');
	$('body').loading('start');
	$('#experience_create :input').clone().hide().appendTo('#event_create_form');
	$('#event_create_form').submit();
});

$(document).on('click','.submit_btns',function(event){
	
	var event_id= $('#event_id').val();
	
	if(event_id=="" && event_id=="0")
	{
		$('#update_stripe_flag').val("0");	
	}else{
		$('#update_stripe_flag').val("1");
	}
	
	$('#event_publish_flag').val("1");
	$('#banking-information').modal('hide');
	$('body').loading('start');
	$('#experience_create :input').clone().hide().appendTo('#event_create_form');
	$('#event_create_form').submit();
});





$(".save_and_publish").click(function(){
	
	//alert($('#experience_create').valid());
	var event_id= $('#event_id').val();
	var user_last_event_stripe_id= $('#user_last_event_stripe_id').val();
	var custom_exp_form= $('#experience_create').valid();
    if($('#event_create_form').valid())
    {
    	var chk_session_vals="0";
    	var user_stripe_account_id= $('.user_stripe_account_id').val();
    	var connect_stripe_url = $('.connect_stripe_url').val();
    	
    	
    	$('.recomanded_experiences').each(function () {
    		
    		if($(this).find('.card-header').hasClass('exp-added'))
    		{
    			chk_session_vals = "1";
    		}
    		
    	});
    	
    	//alert(chk_session_vals);
    	if($('.already_added_exp').val()=="1" || $('#home_page_yelp_session').val() =="1")
    	{
    		chk_session_vals = "1";
    	}
    	
    	
    	// console.log("chk_session_vals",chk_session_vals);
    	// console.log("user_stripe_account_id",user_stripe_account_id);
    	// console.log("connect_stripe_url",connect_stripe_url);
    	//alert(event_id);
    	
    	if($('#pane-A').hasClass('active'))
    	{
    		if(chk_session_vals =="0")
    		{
    			$('.banking-information').click();
    			var content="<h4>You haven't added any experience. please add at least one experience for publish an event. </h4>";
    			$('.my_content').html(content);
    			return false;
    		}
    		if(user_last_event_stripe_id!="0")
    		{
    			$('.banking-information').click();
    			var content=  "<h4>You haven't added any banking information for this event but we found connected banking information from previous event:</h4>"+
		                  	  "<h4>Stripe User Id : "+user_last_event_stripe_id+"</h4>"+
		                  	  "<h5> <a href='#' class='connect_to_stripe'> <u> Click here to add new banking information. </u></a></h5>"+
		                  	  "<br/>OR";
		                  	  
		                  	  
    			$('.my_content').html(content);
    			$('.submit_btns').removeClass('hide');
    			$('.cancel_btns').addClass('hide');
    			return false;
    		}
    		
    		else if(user_stripe_account_id=="0")
    		{
    			$('.banking-information').click();
    		//	alert(event_id);
    			if(event_id=="0" || event_id== undefined)
    			{
    				var content=  "<h4>You haven't added any banking information for this event.click below url to add banking information and then publish an event.</h4>"+
		                  	  "<h5> <a href='#' class='connect_to_stripe'> <u> Click here to add new banking information. </u></a></h5>";
		            $('.cancel_btns').addClass('hide');      	  
		                  	  	
    			}else{
    				var content="<h4>You haven't added any banking information for this event. click below url to add banking information and then publish an event. </h4>"+
    						"<h5> <a href="+connect_stripe_url+" ><u>click here</u></a></h5>";
    			}
    			$('.my_content').html(content);
    			return false;
    		}else{
    			 $('#experience_create :input').clone().hide().appendTo('#event_create_form');
				 $('#event_create_form').submit();
    		}
    	}
    	else{
    		//alert('2222');
    		//alert(custom_exp_form);
    		$('.show-search-exp').each(function () {
    		
	    		if($(this).find('.card-header').hasClass('exp-added'))
	    		{
	    			chk_session_vals = "1";
	    		}
	    		
	    	});
	    	
	    	if(chk_session_vals =="0" && custom_exp_form==false)
    		{
    			$('.banking-information').click();
    			var content="<h4>You haven't added any experience. please add at least one experience for publish an event. </h4>";
    			$('.my_content').html(content);
    			return false;
    		}
    		
	    	else if(user_last_event_stripe_id!="0")
    		{
    			$('.banking-information').click();
    			var content=  "<h4>You haven't added any banking information for this event but we found connected banking information from previous event:</h4>"+
		                  	  "<h4>Stripe User Id : "+user_last_event_stripe_id+"</h4>"+
		                  	  "<h5> <a href='#' class='connect_to_stripe'> <u> Click here to add new banking information. </u></a></h5>"+
		                  	  "<br/>OR";
    			$('.my_content').html(content);
    			$('.submit_btns').removeClass('hide');
    			$('.cancel_btns').addClass('hide');
    			return false;
    		}
	    	
    		else if(user_stripe_account_id=="0")
    		{
    			$('.banking-information').click();
    			if(event_id=="0" || event_id== undefined)
    			{
    				var content="<h4>You haven't added any banking information for this event. click below url to add banking information and then publish an event. <br/> <a href='#' class='connect_to_stripe'><u>click here</u></h4>";
    				$('.cancel_btns').addClass('hide');			
    			}else{
    				var content="<h4>You haven't added any banking information information. click below url to add banking information and then publish an event. </h4>"+
    						"<h5> <a href="+connect_stripe_url+" ><u>click here</u></a></h5>";
    			}
    			$('.my_content').html(content);
    			return false;
    		}else{
    			 $('#experience_create :input').clone().hide().appendTo('#event_create_form');
				 $('#event_create_form').submit();
    		}
	    	
    	}
    	
    	
    }else{
    	$("html, body").animate({scrollTop: 0}, 1000);	
    }
});




$("form input:radio").change(function () {
	
    if ($(this).val() == "images") {
       $('#event_video_section').hide();
        $('#event_img_section').show();
    } else {
        $('#event_img_section').hide();
        $('#event_video_section').show();
    }
});



$(document).on('click', '.search_expers', function(e) {
	
	//alert('111');
	var redirect_url = window.base_url+'/search-experience-ajax';
	var search_terms= $('.search_experience_name').val();
	var location= $('.current_location_name').val();
	var event_id = $('#event_id').val();
	
	if(search_terms!="" && location!="")
	{
		$('body').loading('start');
		$.ajax({
			headers: {
		    	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		  	},
	        type: "get",
	        data: {search_terms: search_terms,location:location,event_id:event_id,token:$('meta[name="csrf-token"]').attr('content')},
	        url: redirect_url,
	        success: function (res) {
	        	//alert(res);
	            if(res)
	            {
					$('#pane-B .showpaging').html(res.pagination);
					$('#pane-B #show_pagination').removeClass('hide');
	            	$('#pane-B .show-search-exp').html(res.html);
	            	$('body').loading('stop');
	            }
	        }
    	});
	}
	
});

/* Paginations for search  */
$(document).on('click', '#pane-B .pagination li a', function(e) {
	//alert('444');
	event.preventDefault();
	var myurl = $(this).attr('href');
	var page=$(this).attr('href').split('page=')[1];
	var event_id = $('#event_id').val();	

	var search_terms= $('.search_experience_name').val();
	var location= $('.current_location_name').val();
	
	if(search_terms!="" && location!="")
	{
		$('body').loading('start');
		$.ajax({
			headers: {
		    	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		  	},
	        type: "get",
	        data: {search_terms: search_terms,location:location,event_id:event_id,token:$('meta[name="csrf-token"]').attr('content')},
	        url: myurl,
	        success: function (res) {
	        	//alert(res);
	            if(res)
	            {
					$('#pane-B .showpaging').html(res.pagination);
					$('#pane-B #show_pagination').removeClass('hide');
	            	$('#pane-B .show-search-exp').html(res.html);
				}
				$('body').loading('stop');
			},
			error: function (xhr, status, errorThrown) {
				$('body').loading('stop');
			}
    	});
	}
	
});

/* Paginations for recommnded  */
$(document).on('click', '#pane-A .pagination li a', function(e) {
	//alert('555');
	event.preventDefault();
	var myurl = $(this).attr('href');	
	var favourite_activity = $("#favourite_activity").val();
	var other_tags = $("#other_tags").val();
	var events_id = $("#events_id").val() || 0;
	$('body').loading('start');
	$.ajax({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		type: "get",
		data: { events_id:events_id, other_tags: other_tags,favourite_activity: favourite_activity, token:$('meta[name="csrf-token"]').attr('content')},
		url: myurl,
		success: function (res) {
			//alert(res);
			if(res)
			{
				$('#pane-A .showpaging').html(res.pagination);
				$('#pane-A #show_pagination').removeClass('hide');
				$('#pane-A .recomanded_experiences').html(res.html);
			}
			$('body').loading('stop');
		},
		error: function (xhr, status, errorThrown) {
			$('body').loading('stop');
		}
	});
	
	
});



$(document).on('click','.recomanded_experience',function(e){
	//alert('666');
	var current_page= $(this).attr('data-id');
	var redirect_url = window.base_url+'/get-recomanded-experiences';
	// alert(redirect_url);
	// return false;
	if(current_page!="0" && search_terms!="")
	{
		$.ajax({
			headers: {
				    	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				  	},
	        type: "POST",
	        data: {search_terms: search_terms,location:location,token:$('meta[name="csrf-token"]').attr('content')},
	        url: redirect_url,
	        success: function (res) {
	        	//alert(res);
	            if(res)
	            {
	            	
	            }
	        }
    	});
	}
});



function save_and_publish(event_id)
{
	var publish_event_name= $('#publish_url').val();
	var redirect_page= window.base_url+'/create-experience/'+event_id;
	$('body').loading('start');
	
	if(event_id!="" && event_id!="0")
	{
			
		$.ajax({
			type: "GET",
			url: '/save-publish-event/'+event_id,
			success: function (res) {
				//alert(res);
				if(res)
				{
					//$('body').loading('stop');
					//window.location.href= redirect_page;
					$('#experience_create :input').clone().hide().appendTo('#event_create_form');
					$('#event_create_form').submit();
				}
			}
		});
	   	
	}
}	

//if step 3 form textbox show hide function

function showHideDiv(ele) {
	
		var srcElement = document.getElementById(ele);
		
		if (srcElement != null) {
			
			if (srcElement.style.display == "block") {
				srcElement.style.display = 'none';
				$('#static_div').show();
				$('#event_step_three_form').removeClass('fav_actives');
			}
			else {
				$('#event_step_three_form').addClass('fav_actives');
				$('#static_div').hide();
				srcElement.style.display = 'block';
			}
			return false;
		}
}



