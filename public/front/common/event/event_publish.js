$('.owl-carousel').owlCarousel({
	items:1,	
    loop:true,
    margin:10,
    responsiveClass:true,
    autoplay: true,
    navigation: true,
    dots: false,
    responsive:{
        0:{
            items:1,
            nav:true
        },
        600:{
            items:1,
            nav:false
        },
        1000:{
            items:1,
            nav:false

        }
    }
});



function give_gift(exp_id)
{
	$('.gift_need_'+exp_id).html('<input type="text" class="paid_amount number" name="paid_amount" id="paid_amount_'+exp_id+'" placeholder="Enter gift amount"><input type="hidden" name="exp_ids[]" id="exp_id" value='+exp_id+'>');	
	
	$('.publish_exp_'+exp_id+' .card-header').addClass(' gft-amount');	
	//$('.publish_exp_'+exp_id+' .card-header span').remove();
	//$('.publish_exp_'+exp_id+' .card-header img').after('<div class="verify" id="verify_'+exp_id+'"><img src="'+window.base_url+'/front/images/verified.png" alt="" title=""><div class="cls" data-exp-id='+exp_id+'><a href="javascript:void(0)"><i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;Cancel</a></div></div>');
}



$(document).on('blur', '.paid_amount', function(e) {
	$this = $(this);
	if($(this).val()!="" && $(this).val()!=undefined)
	{
		$this.closest('.card').find('.card-header').addClass('cart-added');	
	}else{
		$this.closest('.card').find('.card-header').removeClass('cart-added');
	}
	
	CalculateSum();
});


$(document).on("keypress", ".paid_amount", function(e) {
	    if (e.which == 13) {
            $this = $(this);
			$this.closest('.card').find('.card-header').addClass('cart-added');
			CalculateSum();
        }
});


$(document).on('keyup', '.bonus_amount', function(e) {
	
	var total_sum=0;
	var total_items_array = new Array();
	var total_items="";
	var bonus_amount= $('.bonus_amount').val();
	//console.log("bonus_amount",bonus_amount);
	if(bonus_amount!="")
	{	
		//bonus_amount= bonus_amount.toFixed(2);
		$('.total_amount').html('Total: $ '+bonus_amount+'');
		$('.bonus_amount_value').val(bonus_amount);
		$( ".my_experiences" ).each(function( index ) {
		
			if($(this).find('.paid_amount').val()!=undefined)
			{
				
				var experience_id= $(this).find('.paid_amount').next().val();
				var paid_value= $(this).find('.paid_amount').val();
				var actual_amount_value= $(this).closest('div').find('.actual_gift_needed').val();
				var received_val = $(this).closest('div').find('.received_vals').val();
				var remaining_amount= actual_amount_value - received_val;
				
				//console.log("55555555555",remaining_amount);
				
				if(paid_value!="")
				{
					total_sum += parseFloat(paid_value);
					total_items_array.push({exp_id: experience_id,  value:  paid_value,actual_amount_value:remaining_amount});	
				}else{
					$('.gift_need_'+experience_id).html('<a href="javascript:void(0)" onclick="give_gift('+experience_id+')" data-exp-id="'+experience_id+'" class="commont-btn give_gift">GIVE THIS GIFT</a>');
				}
				
				var final_amount=  parseFloat(bonus_amount) + parseFloat(total_sum);
				
				 $('.total_amount').html('Total: $ '+final_amount.toFixed(2)+'');
				 
				 if(final_amount!="0")
				 {
					
					if($('.checkout-sec').hasClass('green-box')==false)
					{
						$('.checkout-sec').addClass('green-box');
					}
				 }
				 
				 total_items= JSON.stringify(total_items_array);
				 
				 $('#total_items').val(total_items);
			}
	  	});
	}else{
		//total_sum= total_sum.toFixed(2);
		$('.total_amount').html('Total: $ '+total_sum+'');	
		$( ".my_experiences" ).each(function( index ) {
		
			if($(this).find('.paid_amount').val()!=undefined)
			{
				
				var experience_id= $(this).find('.paid_amount').next().val();
				var paid_value= $(this).find('.paid_amount').val();
				
				if(paid_value!="")
				{
					total_sum += parseFloat(paid_value);
					total_items_array.push({exp_id: experience_id,  value:  paid_value});	
				}else{
					$('.gift_need_'+experience_id).html('<a href="javascript:void(0)" onclick="give_gift('+experience_id+')" data-exp-id="'+experience_id+'" class="commont-btn give_gift">GIVE THIS GIFT</a>');
				}
				
				$('.total_amount').html('Total: $ '+total_sum.toFixed(2)+'');	
				total_items= JSON.stringify(total_items_array);
				
				 if(total_sum!="0")
				 {
					
					if($('.checkout-sec').hasClass('green-box')==false)
					{
						$('.checkout-sec').addClass('green-box');
					}
				 }
				
				$('#total_items').val(total_items);
			}
	  	});
	}
	
});


   	 
// $(document).on('click', '.cls', function(e) {
// 	
	// var total_items_array = new Array();
	// var total_sum=0;
	// var total_items="";
	// var experience_id= $(this).attr('data-exp-id');
// 	
	// $('.publish_exp_'+experience_id+' .card-header').removeClass(' gft-amount');
	// $('#verify_'+experience_id).remove();
	// $('.publish_exp_'+experience_id+' .card-header img').after('<span id="exp_fund">FUNDED!</span>');
// 	
	// $('.gift_need_'+experience_id).next().val();
// 	
	// $('.gift_need_'+experience_id).html('<a href="javascript:void(0)" onclick="give_gift('+experience_id+')" data-exp-id="'+experience_id+'" class="commont-btn give_gift">GIVE THIS GIFT</a>');
// 	
// 	
	// // $( ".my_experiences" ).each(function( index ) {
// // 		
		// // if($(this).find('.paid_amount').val()!=undefined)
		// // {
// // 			
			// // var experience_id= $(this).find('.paid_amount').next().val();
			// // var paid_value= $(this).find('.paid_amount').val();
// // 			
			// // if(paid_value!="")
			// // {
				// // total_sum += parseFloat(paid_value);
				// // total_items_array.push({exp_id: experience_id,  value:  paid_value});	
			// // }else{
				// // $('.gift_need_'+experience_id).html('<a href="javascript:void(0)" onclick="give_gift('+experience_id+')" data-exp-id="'+experience_id+'" class="commont-btn give_gift">GIVE THIS GIFT</a>');
			// // }
			 // // $('.total_amount').html('Total333: $ '+total_sum.toFixed(2)+'');
// // 			 
			 // // total_items= JSON.stringify(total_items_array);
			 // // $('#total_items').val(total_items);
		// // }
  	// // });
// 	
// 	
// });

$(document).on('click', '.checkout_page', function(e) {
	
	var total_items_val= $('#total_items').val();
	
	if(total_items_val!="")
	{
		$('#checkout_form').submit();	
	}else{
		alert('Please enter any of one gift');
		return false;
	}
});

$(document).on('keyup', '.paid_amount', function(event) {
 
    var currentVal = $(this).val();

    if (currentVal.length == 1 && (event.which == 48 || event.which == 96)) {
      currentVal = currentVal.slice(0, -1);
    }
    $(this).val(currentVal);
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

$(document).on('click', '.remove_fund', function(e) {
	$this = $(this);
	console.log('call');
	if(confirm("Are you sure want to remove from cart?")){
		$this.closest('.card-header').removeClass('cart-added');
		$this.closest('.card').find('.paid_amount').val('');
		CalculateSum();
	}
	
});

$('.fb-share').click(function(e) {
	e.preventDefault();
	window.open($(this).attr('href'), 'fbShareWindow', 'height=450, width=550, top=' + ($(window).height() / 2 - 275) + ', left=' + ($(window).width() / 2 - 225) + ', toolbar=0, location=0, menubar=0, directories=0, scrollbars=0');
	return false;
});


function CalculateSum(){
	
	var total_items_array = new Array();
	var total_sum=0;
	var total_items="";

	$( ".my_experiences" ).each(function( index ) {
		
		if($(this).find('.paid_amount').val()!=undefined)
		{
			
			var experience_id= $(this).find('.paid_amount').next().val();
			var paid_value= $(this).find('.paid_amount').val();
			var actual_amount_value= $(this).closest('div').find('.actual_gift_needed').val();
			var currently_received_vals= $(this).closest('div').find('.received_vals').val();
			var remaining_vals= actual_amount_value - currently_received_vals;
			var bonus_amt= parseFloat(paid_value) - parseFloat(remaining_vals);
			
			//console.log("ggg",remaining_vals + '<'+ paid_value)
			if(remaining_vals < paid_value)
			{
				var current_bonus_amt_val= $('#bonus_amount').val();
				 $(this).find('.paid_amount').val(remaining_vals);
				 //console.log("555",bonus_amt);
				 if(current_bonus_amt_val=="" || current_bonus_amt_val==undefined)
				 {
				 	$('#bonus_amount').val(bonus_amt);	
				 	$('.bonus_amount_value').val(bonus_amt);
				 }else{
				 	var add_bonus_sum= parseFloat(current_bonus_amt_val) + parseFloat(bonus_amt);
				 	$('#bonus_amount').val(add_bonus_sum);
				 	$('.bonus_amount_value').val(add_bonus_sum);
				 }
			}
			//console.log( $(this).closest('div').find('.received_vals').val());
			//console.log(actual_amount_value+'<'+ $(this).parent().parent().find('.received_val').val());
			
			var received_val = $(this).closest('div').find('.received_vals').val();
			var remaining_amount= actual_amount_value - received_val;
			var paid_value= $(this).find('.paid_amount').val();
			
			if(paid_value!="")
			{
				total_sum += parseFloat(paid_value);
				total_items_array.push({exp_id: experience_id,  value:  paid_value,actual_amount_value:remaining_amount});	
			}else{
				$('.gift_need_'+experience_id).html('<a href="javascript:void(0)" onclick="give_gift('+experience_id+')" data-exp-id="'+experience_id+'" class="commont-btn give_gift">GIVE THIS GIFT</a>');
			}
			
			var bonus_amount= $('.bonus_amount').val();
			
			//console.log(parseFloat(total_sum,2));
			if(bonus_amount!="")
			{
				//sum of fund amount and bonus amount
				var final_amount=  parseFloat(bonus_amount) + parseFloat(total_sum);
				$('.total_amount').html('Total: $ '+final_amount.toFixed(2)+'');
				
			}else{
				$('.total_amount').html('Total: $ '+total_sum.toFixed(2)+'');	
			}
			 
			if(total_sum!="0")
			{
				if($('.checkout-sec').hasClass('green-box')==false)
				{
					$('.checkout-sec').addClass('green-box');
				}
			}
			 total_items= JSON.stringify(total_items_array);
			 $('#total_items').val(total_items);
		}
  	});
}

