	var total_exprience = 0;	
	/* Remove added exprience from search page */
	$(document).on('click','.exp-added .verify a',function(e){	
		
		var yelp_id= $(this).attr('data-id');
		var event_id= $('#event_id').val();
		
		$this = $(this);
		$this.closest('.exp-added').removeClass('exp-added');
		$this.closest('.card').find('.card-body .commont-btn').removeClass('disable');
		
		//var yelp_id= $this.closest('.card').find('.card-body .commont-btn').attr('data-id');
		total_exprience--;		
		//alert(event_id);
		//delete experience if click on remove button
		if(event_id!=undefined && event_id!="0")
		{
			$.ajax({
					headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
				type: "POST",
				data: {yelp_id: yelp_id,event_id:event_id,token:$('meta[name="csrf-token"]').attr('content')},
				url: '/delete-yelp-experience',
				success: function (res) {		
					
					$('#recomanded_'+yelp_id).removeClass('hide');
	        	  	$(".total_exprience_count").html(res.count); 
					$('#session_yelp_id_'+yelp_id).val('0');
					$('#yelp_amount_'+yelp_id+'').css('display','none');
					$('#recomanded_'+yelp_id).show();
				}
			});
		
		}else{
			alert(yelp_id);
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
				type: "POST",
				data: {yelp_id: yelp_id,token:$('meta[name="csrf-token"]').attr('content')},
				url: '/search-and-remove',
				success: function (res) {	
					$('#recomanded_'+yelp_id).removeClass('hide');	        	  
					$(".total_exprience_count").html(res.count); 
					$('#session_yelp_id_'+yelp_id).val('0');
					$('#yelp_amount_'+yelp_id+'').css('display','none');
					$('#recomanded_'+yelp_id).show();
				}
			});
		}
	});

	//Experience form validation
	$(document).on('click','.add-perfect-exp',function(e){	
			
		var event_id= $('#event_id').val();
		var yelp_id= $(this).attr('data-id');
		var exp_name= $('#exp_name_'+yelp_id).val();
		var image= $('#image_'+yelp_id).val();
		
		$('#recomanded_'+yelp_id).hide();
		$('<input type="text" data-id="'+yelp_id+'" class="number valid yelp-amount current_id_'+yelp_id+'" id="yelp_amount_'+yelp_id+'" name="yelp_amount" value="" aria-required="true" aria-invalid="false">').insertAfter( "#recomanded_"+yelp_id+"" );
		
		
	});

	$(document).on('focusout keydown','.yelp-amount',function(e){
		//e.preventDefault();
		
		var event_id= $('#event_id').val();
		//var yelp_id= $(this).prev().attr('data-id');
		var yelp_id= $(this).attr('data-id');
		$('#session_flag_'+yelp_id).val("1");
		var exp_name= $('#exp_name_'+yelp_id).val();
		var image= $('#image_'+yelp_id).val();
		var session_flag= $('#session_flag_'+yelp_id).val();
		var chk_session_val= $('#session_yelp_id_'+yelp_id).val();
		var yelp_amount= $(this).val();
		
		
		//alert(yelp_amount);
		if(event_id!="" && event_id!="0" && event_id!=undefined)
		{
			var redirect_url = window.base_url+'/search-and-add-with-event';			
			if(exp_name!="")
			{
				  var key = e.which;
				  if(key === 13 || e.type === "focusout")
				  {
				  
				  	  if(yelp_amount!="")
				  	  {
				  	  	 //console.log("yelp amount is ",yelp_amount);
				  		  if(yelp_amount < 5 )
						  {
						 	$('.min_amount_error').remove();
							$('#exp_ids_'+yelp_id).append('<label class="error min_amount_error" id="exp_name-error" for="exp_name">Please enter minimum $5.</label>');
							return false;
						  }else{
						  	$('.min_amount_error').remove();
						 	$('#yelp_amount').css('display','none');
					        $('#exp_ids_'+yelp_id+' .card-header').addClass('exp-added');
					        total_exprience++;
					  	    $.ajax({
							headers: {
								'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
							},
							type: "POST",
							data: {event_id:event_id,yelp_id: yelp_id,exp_name:exp_name,image:image,amount:yelp_amount,flag:session_flag,token:$('meta[name="csrf-token"]').attr('content')},
							url: redirect_url,
							success: function (res) {
								//$('#yelp_amount_'+yelp_id+'').css('display','none');
							    //$('#recomanded_'+yelp_id).show();
							    //$('#recomanded_'+yelp_id).addClass('disable');
							    $('#session_yelp_id_'+yelp_id).val('1');
							    $(".total_exprience_count").html(res.count);
								//$(".total_exprience_count").html(res.count); 
								//location.reload(); 
							 }
			    			});
						  }	
				  			
					      
			    	 }
				  }
			}
		}
		else
		{
			//console.log(chk_session_val);
			//console.log(image);
			if(exp_name!="")
			{
				 var redirect_url = window.base_url+'/search-and-add';
				 var key = e.which;
				 var yelp_amount= $(this).val();
				 var chk_session_val= $('#session_yelp_id_'+yelp_id).val();
				 //alert(chk_session_val);
				 //console.log('session value is',chk_session_val);
				  if (e.type == "focusout" || key == 13)  
				  {
				  	if(yelp_amount!="")
				  	{
				  			//alert('fasf');
				  		//console.log('myvaluesis',chk_session_val);
				  		if(yelp_amount < 5 )
						{
							$('.min_amount_error').remove();
							$('#exp_ids_'+yelp_id).append('<label class="error min_amount_error" id="exp_name-error" for="exp_name">Please enter minimum $5.</label>');
							return false;
						}else{
							$('.min_amount_error').remove();
							$('#yelp_amount').css('display','none');
						  	$('#exp_ids_'+yelp_id+' .card-header').addClass('exp-added');
							total_exprience++;
							//$(".total_exprience_count").html(total_exprience); 
							//console.log('hereeee',yelp_id);
						    $.ajax({
								headers: {
							    	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
							  	},
					        type: "POST",
					        data: {yelp_id: yelp_id,exp_name:exp_name,image:image,amount:yelp_amount,flag:session_flag,token:$('meta[name="csrf-token"]').attr('content')},
					        url: redirect_url,
					        success: function (res) {			        	  
					        	 // $('#yelp_amount_'+yelp_id+'').css('display','none');
								  //$('#recomanded_'+yelp_id).show();
								 // $('#recomanded_'+yelp_id).addClass('disable');
								  $('#session_yelp_id_'+yelp_id).val('1');
								  $(".total_exprience_count").html(res.count);
								  return false;
						    	}
				    		});
						}
				  	  	
					  }
				} 
			}
		}
	});
	
	$('#add_experience').click(function(){
		
		var event_id = $('#event_id').val();
		if(event_id == '0')
		{
			$('#event_create_form').valid();
			if(!$("#event_create_form").valid())
			{
				$("html, body").animate({scrollTop: 0}, 1000);
				return false;	
			}else{
				$('#experience_create').valid();
			}
			
			if($("#experience_create").valid())
			{
				$('#event_create_form :input').clone().hide().appendTo('#experience_create');
				$('#experience_create').submit();
			}
			
			//alert('please add event first');
			return false;
		}else{
			if($('#experience_create').valid())
		    {
			    $('#experience_create').submit();
		    }	
		}
		
	});
	
	$('#experience_create').validate({
	    rules: {
	    	
	    	// exp_image: {
	             // required: true,
	        // },
	        exp_image: {
					required: {
								depends: function(element) {
									console.log("abcd",$('.img_preview').length);
								if ($('.img_preview').length== "0"){
									console.log('nathi');
									return true;
								}
							}
						},
					},
	        exp_name: {
	             required: true,
	             maxlength: 120
	        },
	        // description: {
	             // required: true,
	        // },
	        gift_needed: {
	              required: true,
	              min:5,
	              number:true,
	         },
	    },
	    messages: {
	    	//exp_image: "Please upload an image.",
	        exp_image: "Please upload an image.",
	        exp_name: {
	             required: "Please enter experience title.",
	             //maxlength: "Max length limit reached.",
	        },
	        //description: "Please enter description.",
	        gift_needed: {
	        	required :"Please enter gift needed.",
	        	min: "Please enter minimum $5",
	        	number : "Please enter valid amount"	
	        }
	    },
	     errorPlacement: function(error, element) 
        {
            if (element.attr("name") == "exp_image"){ 
                error.insertAfter("#exp_upload");
            }else{
                error.insertAfter(element);
            }  
        }
	});
	
	
	$.validator.addMethod('check_min_amount', function (value, element, param) {
    	
    	if(value < 5)
    	{
    		return false;
    	}else{
    		return true;
    	}
    	
	}, 'Please enter minimum $5.');
	
	
	$('.experience_update').each(function () {
		var edit_exp_id= $(this).find('#edit_exp_id').val();
		
		$(this).validate({
		    rules: {
		    	
		    	edit_exp_name: {
		             required: true,
		        },
		        // edit_description: {
		             // required: true,
		        // },
		       
		        edit_gift_needed: { required: true, check_min_amount:true, min: function(){
		        	
		        		if($('#actual_received_amt_'+edit_exp_id).val() < $('#actual_gift_needed_'+edit_exp_id).val()){
		                	return parseFloat($('#actual_received_amt_'+edit_exp_id).val());
		                }else{
		                	return parseFloat($('#actual_gift_needed_'+edit_exp_id).val());
		                }
		          }},
		    },
		    messages: {
		    	
		        edit_exp_name: "Please enter experience title.",
		        //edit_description: "Please enter description.",
		        edit_gift_needed: {required:"Please enter gift needed.", min:"you cannot enter less than received amount"},
		    },
		     errorPlacement: function(error, element) 
	        {
	            if (element.attr("name") == "edit_gift_needed"){ 
	            	error.insertAfter("#exp_val_"+edit_exp_id);
	            }else{
	                error.insertAfter(element);
	            }  
	        }
		});
	});
	
	
	$('#description').keyup(function () {
	    	
		var begin = 0;
		var max = 240;
		var len = $(this).val().length;
	        
		if (len >= max) {
		    $('#create_exp_charnum').text(' you have reached the limit');
		} else {
		    var char = begin + len;
		    $('#create_exp_charnum').text(char + ' / 240 text remaining');
		}
    	});
   	 $('#description').keyup();
   	 
   	 
   	 
   	 $('.edit_exp_desc').keyup(function () {
   	 	
   	 	var edit_exp_id= $(this).attr('data-id');	
	    
		var begin = 0;
		var max = 240;
		var len = $(this).val().length;
	      
		if (len >= max) {
		    $('#edit_exp_charnum_'+edit_exp_id).text(' you have reached the limit');
		} else {
		    var char = begin + len;
		    
		    $('#edit_exp_charnum_'+edit_exp_id).text(char + ' / 240 text remaining');
		}
    	});
   	 $('#edit_description').keyup();
	
	$('.number').keypress(function(event) {
    	
	    var $this = $(this);
	    
	    if ((event.which != 46 || $this.val().indexOf('.') != -1) &&
	       ((event.which < 48 || event.which > 57) &&
	       (event.which != 0 && event.which != 8))) {
	           event.preventDefault();
	    }
	
	    var text = $(this).val();
	    if ((event.which == 46) && (text.indexOf('.') == -1)) {
	        setTimeout(function() {
	            if ($this.val().substring($this.val().indexOf('.')).length > 3) {
	                $this.val($this.val().substring(0, $this.val().indexOf('.') + 3));
	            }
	        }, 1);
	    }
	
	    if ((text.indexOf('.') != -1) &&
	        (text.substring(text.indexOf('.')).length > 2) &&
	        (event.which != 0 && event.which != 8) &&
	        ($(this)[0].selectionStart >= text.length - 2)) {
	            event.preventDefault();
	    }      
	});
	
	$('.number').bind("paste", function(e) {
		var text = e.originalEvent.clipboardData.getData('Text');
		if ($.isNumeric(text)) {
		    if ((text.substring(text.indexOf('.')).length > 3) && (text.indexOf('.') > -1)) {
		        e.preventDefault();
		        $(this).val(text.substring(0, text.indexOf('.') + 3));
		   }
		}
		else {
		        e.preventDefault();
		     }
	});
	
	$(function() {
    // images preview in browser
    var imagesPreview = function(input, placeToInsertImagePreview) {
    	
		var file_type= input.files[0].type;
		
            var filesAmount = input.files.length;
			if (input.files && file_type== 'image/jpeg' || file_type== 'image/png') {
				
				for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function(event) {
                	$( '<img  class="img_preview" src='+event.target.result+'>' ).insertAfter( placeToInsertImagePreview );
                }
              reader.readAsDataURL(input.files[i]);
            }
				
			}else{
				alert('Please upload valid image');
				return false;
			}
            
    };
    
    var updateimagesPreview = function(input, placeToInsertImagePreview) {
    	
		var file_type= input.files[0].type;
		
            var filesAmount = input.files.length;
			if (input.files && file_type== 'image/jpeg' || file_type== 'image/png') {
				
				for (i = 0; i < filesAmount; i++) {
	                var reader = new FileReader();
	
	                reader.onload = function(event) {
	                //	console.log('heydev',event.target.result);
	                	$("#my_exp_image").attr("src",event.target.result);
	                }
	              reader.readAsDataURL(input.files[i]);
	            }
			}else{
				alert('Please upload only image');
				return false;
			}
            
    };

    $('#exp_image').on('change', function() {
    	$('.img_preview').remove();
    	imagesPreview(this, '.experience_img_preview');
    });
    
    $('#update_img').on('change', function() {
    	 updateimagesPreview(this, '.add_exp_img');
    });
    
    
    
    $('.edit_experience').on('click',function(){
    	
    	$('.recomanded_experiences form').css('padding','0px');
    	var exp_id= $(this).attr('data-id');
    	
    	$('#description').css('border','1px solid #FBD534!important');
    	$('.edit_exp_img_'+exp_id).css('display','block');
    	$('.exp_form_'+exp_id).css('display','none');
    	$('.comment_update_form_'+exp_id).css('display','block');
    	
    	var len = $('#edit_description_'+exp_id).val().length;
    	var begin = 0;
    	var char = begin + len;
    	$('#edit_exp_charnum_'+exp_id).text(char + ' / 240 text remaining');
    	
    });
    
    //comment form validation
	
	$('#comment_form').validate({
	    rules: {
	        comment_description: {
	            required: true,
	        },
	    },
	    messages: {
	        comment_description: "Please enter comment.",
	    },
	});

	$('#experience_custom_url').on('blur', function() {
		$this = $(this);
		var $url = $this.val();
		if($url!=""){
			$('body').loading('start');
			$.ajax({
				type: 'POST',
				url: '/scrap-exprience',
				data: { yelpurl: $url },
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},		
				success:function(data){
					var yelpdata = data.detail;
					$('body').loading('stop');
					if(data.image != ""){
						//console.log(data);
						//console.log("yelp image isss  ",data.image);
						$("#exp_name").val(yelpdata.name);
						$('#yelpimage').val(data.image);
						$('#exp_image-error').remove();
						var imageurl = '/storage/temp-ex/'+data.image;
						$('.img_preview').remove();
						var preview = $('<img class="img_preview" src="'+imageurl+'">');
						$("#exp_image").after(preview);
					}else{
						var error = '<label class="error url-exp" for="exp_name">No result found, make sure you url is correct or not.</label>';
						if($(".url-exp").length>0){
							$(".url-exp").remove();
						}
						$this.after(error);
					}
					

				},
				error:function(){
				console.log('Error');
				}
			});
		}
    });
});

$( document ).ready(function() {
	
	$("html, body").animate({scrollTop: 0}, 1000);
});

function update_experience(id)
{
	$('#experience_update_'+id).submit();
}

function delete_experience(id)
{
	var event_id= $('#event_id').val();
	
	if (confirm('Are you sure you want to delete this experience?')) {
        $.ajax({
            type: "GET",
            url: '/experience/delete/' + id, //resource
            success: function (affectedRows) {
            	history.go(0);
            }
        });
    }
}

function add_comment()
{
	if($('#comment_form').valid())
	{
		$('#comment_form').submit();
	}
	
}
