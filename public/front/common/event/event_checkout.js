$( document ).ready(function() {
	//$('header').removeAttr( "class" );
   $('.join-party-sec').remove();	
});

function remove_gift(id,amount)
{
	if (confirm('Are you sure you want to remove this gift?')) 
	{
		var total_amount= $('#total_amount').html();
		var actual_amount= total_amount.replace("$", "");
		console.log(actual_amount+'==='+amount);
		var remaining_amount= actual_amount - amount;
		$('.checkout_'+id+'').remove();
		$('#total_amount').html('$'+remaining_amount);
		
		var total_item_count= $('#chkout_tbl tr').length;
		var publish_url= $('#event_publish_url').val();
		
		if(total_item_count == "0")
		{
			window.location = publish_url;
		}
	} else{
        return false;
    }
}

function remove_bonus_gift(amount)
{
	if (confirm('Are you sure you want to remove this gift?')) 
	{
		var total_amount= $('#total_amount').html();
		var actual_amount= total_amount.replace("$", "");
		
		console.log('amout is ',actual_amount +'-'+ amount);
		var remaining_amount= actual_amount - amount;
		$('.bonus-checkout').remove();
		$('#total_amount').html('$'+remaining_amount);
		
		var total_item_count= $('#chkout_tbl tr').length;
		var publish_url= $('#event_publish_url').val();
		
		if(total_item_count == "0")
		{
			window.location = publish_url;
		}
	} else{
        return false;
    }
}

$(document).on('focusout','.paid_exp_amount',function(e){
	
	var paid_value= $(this).val();
	//var remaining_gift_value= $('.actual_gift_needed').val();
	var remaining_gift_value= $(this).prev('.actual_gift_needed').val();
	var bonus_amt_vals= $('#bonus_amout_val').val();
	var additional_amts=0;
	var final_amt=0;
	
	//console.log(bonus_amt_vals);
	if(paid_value!="" && paid_value!=undefined)
	{
		if(paid_value > remaining_gift_value)
		{
			$(this).val(remaining_gift_value);
			console.log('before final',paid_value+'-'+remaining_gift_value);
			additional_amts= parseFloat(paid_value) - parseFloat(remaining_gift_value);
			//console.log('final',additional_amts);
			final_amt = parseFloat(bonus_amt_vals) + parseFloat(additional_amts);
			//console.log('final amount is ',final_amt);
			$('#bonus_amout_val').val(final_amt);
				
		}
	}
});

$(document).on('keyup', '.paid_exp_amount', function(e) {
	
	var total_sum=0;
	var total_items_array = new Array();
	var additional_amt=0;
	
	$( ".events-chkout" ).each(function( index ) {
		
		if($(this).find('.paid_exp_amount').val()!=undefined)
		{
			var paid_value= $(this).find('.paid_exp_amount').val();
			
			
			if(paid_value!="")
			{
				total_sum += parseFloat(paid_value);
			}
			
			// total_items_array.push({exp_id: experience_id,  value:  paid_value,actual_amount_value:actual_amount_value});
			 $('#total_amount').html('$'+total_sum.toFixed(2));
			 $('#final_amount').val(total_sum);
		}
  	});
	
});
$(document).on('keyup', '.fund_message', function(e) {

	var begin = 0;
	var max = 1000;
	var len = $(this).val().length;
	  
	if (len >= max) {
	    $('#charnum').text(' you have reached the limit');
	} else {
	    var char = begin + len;
	    
	    $('#charnum').text(char + ' / 1000 text remaining');
		}
});
$('.fund_message').keyup();

if ($('#tab-A').hasClass("active")){
	$('.pay_by_chk').val("0");
}else{
	$('.pay_by_chk').val("1");
}


$(document).on('click', '#tab-A', function(e) {
	$('.billing_infos').removeClass('hide');
	$('.pay_by_chk').val('0');
});


$(document).on('click', '#tab-B', function(e) {
	//$('.billing_infos').addClass('hide');
	$('.pay_by_chk').val('1');
});

$( document ).ready(function() {
	
	if($('#payment_methods').val()=="1")
	{
		$('#tab-B').click();
		$('.pay_by_chk').attr('checked','checked');
		$('.billing_infos').removeClass('hide');
		$('.pay_by_chk').val('1');
	}
	
	jQuery(function($) {
      $('[data-numeric]').payment('restrictNumeric');
      $('.cc-number').payment('formatCardNumber');
     // $('.cc-exp').payment('formatCardExpiry');
     // $('.cc-cvc').payment('formatCardCVC');
      $.fn.toggleInputError = function(erred) {
        this.parent('.form-group').toggleClass('has-error', erred);
        return this;
      };
    });
    
    
    $.validator.addMethod("chk_month_year", function(value, element) {
    	var d = new Date();
    	var current_year = d.getFullYear();
    	var current_month= d.getMonth() + 1;
    	//console.log(value+'=='+current_year)
    	if(value==current_year)
    	{
    		var selected_month=  $('#expiration_date').val();
    		if(selected_month >= current_month)
    		{
    			return true;
    		}else{
    			return false;
    		}
    	}else{
    		return true;
    	}
  		
	}, "Invalid month and year");

	$('#confirm_payment').validate({
		    rules: {
		    	gift_val:{
		    		required:true,
		    		number:true,
		    	},
		        description: {
		            required: true,
		        },
		        make_annoymas: { // <- NAME of every radio in the same group
            			required: true
        		},
		        credit_card_number: {
					required: {
								depends: function(element) {
								if ($('#tab-A').hasClass("active")){
									return true;
								}else{
									return false;
								}
							}
						},
					},
		        expiration_date: {
		            required: {
								depends: function(element) {
								if ($('#tab-A').hasClass("active")){
									return true;
								}else{
									return false;
								}
							}
						},
		        },
		        expiration_year: {
		            required: {
								depends: function(element) {
								if ($('#tab-A').hasClass("active")){
									return true;
								}else{
									return false;
								}
							}
						},
						chk_month_year:true,	
		        },
		        cvv_no: {
		            required: {
								depends: function(element) {
								if ($('#tab-A').hasClass("active")){
									return true;
								}else{
									return false;
								}
							}
						},
		            number:true
		        },
		        first_name: {
		            required: true,
		        },
		        last_name: {
		            required: true,
		        },
		        address: {
		            required: true,
		        },
		        // floor: {
		            // required: true,
		        // },
		        city: {
		            required: true,
		        },
		        state: {
		            required: true,
		        },
		        zipcode: {
		            required: true,
		            number:true,
		            maxlength: 5
		        },
		        country: {
		            required: true,
		        },
		        phone_no: {
		            required: true,
		            number:true
		        },
		        email: {
					required: {
								depends: function(element) {
								if ($('#already_fynches_user').val()=="0"){
									return true;
								}else{
									return false;
								}
							}
						},
						email: true,
						remote: {
					    url: '/duplicate-Email/',
					    type: "get",
					    data: {
					      _token: function() {
					        return "{{csrf_token()}}"
					      }
					    }
					  },
					},
				confirm_email: {
					required: {
								depends: function(element) {
								if ($('#already_fynches_user').val()=="0"){
									return true;
								}else{
									return false;
								}
							}
						},
						 email: true,
						 equalTo: "#email"
					},
				password: {
					required: {
								depends: function(element) {
								if ($('#already_fynches_user').val()=="0"){
									return true;
								}else{
									return false;
								}
							}
						},
					},
				confirm_password: {
					required: {
								depends: function(element) {
								if ($('#already_fynches_user').val()=="0"){
									return true;
								}else{
									return false;
								}
							}
						},
						 equalTo: "#password"
					},	
				pay_by_check: {
		            required: {
								depends: function(element) {
								if ($('#tab-B').hasClass("active")){
									return true;
								}else{
									return false;
								}
							}
						},
		            
		        },			
						        
		    },
		    messages: {
		    	gift_val:{
		    		required : "Please enter amount.",
		    		number:"Please enter valid amount.",
		    	},
		        description: "Please enter comment.",
		        make_annoymas: "Please choose make annoymas.",
		        credit_card_number: {
		        	required : "Please enter credit card number.",
		        },
		        expiration_date: "Please select Expiry date.",
		        expiration_year: {
		        	required:"Please select Expiry year",
		        	chk_month_year: "Invalid month and year"
		        },
		        cvv_no: {
		        	required : "Please enter cvv number.",
		        	number : "Please enter valid number"
		        },
		        first_name: "Please enter first name.",
		        user_first_name: "Please enter first name.",
		        last_name: "Please enter last name.",
		        address: "Please enter address.",
		        //floor: "Please enter floor.",
		        city: "Please enter city.",
		        state: "Please select state.",
		        zipcode: {
	                required: "Please enter zipcode",
	                number:"Please enter valid zipcode",
	                maxlength: "Zipcode must be maximum 5 characters long."
	            },
		        country: "Please select country.",
		        phone_no: {
	                required: "Please enter phone no",
	                number:"Please enter valid phone no",
	            },
		        email: {
		        	required: "Please enter email.",
		        	email: "Please valid email address",
		        	remote:"Email id already exits,Please try another",
		        },
		        confirm_email: {
		        	required: "Please enter confirm email.",
		        	email: "Please valid email address",
		        	equalTo: "Email and Confirm email not match",
		        },
		        password: {
		        	required: "Please enter password.",
		        },
		        confirm_password: {
		        	required: "Please enter confirm password.",
		        	equalTo: "Password and confirm password not match",
		        },
		        pay_by_check:{
		        	required: "Please check checkbox.",
		        }
		    },
		     errorPlacement: function(error, element) 
	        {
	            if (element.attr("name") == "make_annoymas"){ 
	            	error.insertAfter('.radio_btns_ul');
	            }else if(element.attr("name") == "pay_by_check")
	            {
	            	error.insertAfter('.custom_chksbox');
	            }
	            else{
	                error.insertAfter(element);
	            }  
	        }
	});

});

$(document).on('click', '.pay_by_chk', function(e) {
	if ($(this).is(':checked')){
	    $('.pay_by_chk').val("1");
	    $('.billing_infos').removeClass('hide');
	  }
	  else{
	    $('.pay_by_chk').val("0");
	    $('.billing_infos').addClass('hide');
	  }
});

$(document).on('click', '.review_confirm', function(e) {
	
	var payment_method= $('.pay_by_chk').val();
	
	if($("#confirm_payment").valid())
	{
		$('#confirm_payment').submit();	
	}else{
		$("html, body").animate({scrollTop: 20}, 1000);
	}
});

$(document).on('change', '.country', function(e) {
	
	var country_id= $(this).val();
	
	$.ajax({
        type: "GET",
        url: '/get-States/'+country_id,
        success: function (res) {
        	
            if(res)
            {
            	$(".dynamic_state").empty();
                $(".dynamic_state").append('<option value="">Select</option>');
                $.each(res,function(key,value){
                	$(".dynamic_state").append('<option value="'+value.id+'">'+value.name+'</option>');
                });
            }
        }
    });
});

$(document).on('click', '.pay_by_check', function(event) {
	
	var payment_method= $('input[name=pay_by_check]:checked').val();
	//alert(payment_method);
	if (payment_method == "1") {
   		$('.billing_infos').removeClass('hide');
	}else{
		$('.billing_infos').addClass('hide');
	}
});

$(document).on('keypress', '.number', function(event) {

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

$(document).on('paste', '.number', function(e) {

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
