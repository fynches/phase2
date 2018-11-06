$(document).on('click', '.send-thankyou-mail', function(e) {
	var funding_report_id = $(this).attr('data-id');
	var email_id = $(this).attr('data-email');
	$('.to_user_id').val(email_id);
	$('.funding_report_id').val(funding_report_id);
});

$(document).on('click', '.send-thankyou', function(e) {
    
    if($("#send-thankyou-form").valid())
    {
    	$('#feedback').modal('hide');
    	$('body').loading('start');
    }
    
});

$( document ).ready(function() {
	$('#send-thankyou-form').validate({
	    rules: {
		 email: {
		     required: true,
		     email:true
		 },
		subject:{
			required: true,
		},
		description:{
			required: true,
		}
	    },
	    messages: {
		 email: {
		     required: "Please enter email",
		     email:"Please enter valid email"
		 },
		subject:{
			required: "Please enter subject",
		},
		description:{
			required: "Please enter description",
		}
	    },
		
	});
});

$(document).on('click','.copy_url', function(e){
	$(this).addClass('disable');
});

$.validator.addMethod('check_min_one_email', function (value, element, param) {
    
    var total_mail_counts= $('.send_user_mails').length;
    if(total_mail_counts=="0")
    {
    	return false;
    }else{
    	return true;
    }
   // console.log('total email count',$('.send_user_mails').length);
    
    //return false; // return bool here if valid or not.
}, 'Please enter atleast one email id.');

$('#event_share_form').validate({
    rules: {
        email: {  check_min_one_email: true },
        subject:{
        	required: true,
        },
        description:{
        	required: true,
        }
    },
    messages: {
        subject:{
        	required: "Please enter subject",
        },
        description:{
        	required: "Please enter description",
        }
    },
		
});

$( document ).ready(function() {
	
	$("html, body").animate({scrollTop: 0}, 1000);
});

$(document).on('change', '.payment_status', function(e) {
	
	var payment_status= $(this).val();
	var funding_id= $(this).attr('data-id');
	if (confirm('Are you sure you want to update payment status?')) {
		if(payment_status!="" && funding_id!="")
		{
			$.ajax({
		        type: "GET",
		        url: '/get-payment-status/'+payment_status+'/'+funding_id,
		        success: function (res) {
		        	
		            if(res)
		            {
		            	$(".payment_status").remove();
		            	location.reload();
		            }
		        }
		    });
		 }
	}else{
		$('.payment_status').val('pending');
		return false;
	}
	
});


$('.multiple-val-input').on('click', function(){
    $(this).find('input:text').focus();
});
$('.multiple-val-input ul input:text').on('input propertychange', function(){
    $(this).siblings('span.input_hidden').text($(this).val());
    var inputWidth = $(this).siblings('span.input_hidden').width();
    $(this).width(inputWidth);
});
$('.multiple-val-input ul input:text').on('keypress change', function(event){
	//console.log("aaaaaaaaaa",event.type)
    if(event.which == 32 || event.which == 44 || event.type == "change" || event.type =="blur"){
    	
        var toAppend = $(this).val();
        
        if(toAppend!="")
        {
        	if(!validateEmail(toAppend)){
	        	alert('please enter valid email address');
	        	return false;
        	}
        }
        
        
        if(toAppend!=''){
        	$('<li><a href="#">×</a> <input type="hidden" class="custom_mail_ids" id="user_emails" name="user_emails[]" value="'+toAppend+'"><div class="send_user_mails">'+toAppend+'</div></li>').insertBefore($(this));
            $(this).val('');
        } else {
            return false;
        }
        return false;
    };
});

$(document).on('click','.fb-share', function(e){
	e.preventDefault();
	window.open($(this).attr('href'), 'fbShareWindow', 'height=450, width=550, top=' + ($(window).height() / 2 - 275) + ', left=' + ($(window).width() / 2 - 225) + ', toolbar=0, location=0, menubar=0, directories=0, scrollbars=0');
	return false;
});

$(document).ready(function() {
    // Configure/customize these variables.
    var showChar = 100;  // How many characters are shown by default
    var ellipsestext = "...";
    var moretext = "read more >";
    var lesstext = "read less";
    

    $('.more').each(function() {
        var content = $(this).html();
 
        if(content.length > showChar) {
 
            var c = content.substr(0, showChar);
            var h = content.substr(showChar, content.length - showChar);
 
            var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';
 			$(this).html(html);
        }
 
    });
 
    $(".morelink").click(function(){
    	//alert($(this).hasClass("less"));
        if($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
    });
});

// $(document).on('focusout keydown', '#custom_url', function(event) {
// 	
	// var $this = $(this);
	// var event_id= $('#event_id').val();
	// var publish_url= $(this).val();
// 	
	// var publish_event_name = publish_url.substring(publish_url.lastIndexOf("/") + 1, publish_url.length);
// 	
// 	
	// $.ajax({
        // type: "GET",
        // url: '/validate-url/',
        // data: {"publish_url": publish_event_name,"event_id":event_id},
		// success: function (res) {
//         	
            // if(res)
            // {
            	// if(res=="true")
            	// {
            		// $('#url-exists-error').removeClass('hide').addClass('error');
            	// }else{
            		// $('#url-exists-error').removeClass('error').addClass('hide');
            	// }
            // }
        // }
    // });
// });


$(document).on('click','.multiple-val-input ul li a', function(e){
    e.preventDefault();
    $(this).parents('li').remove();
});

function validateEmail(email) {
    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(email);
}

function copyToClipboard(element) {
 
  $(this).addClass('disable');
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).val()).select();
  document.execCommand("copy");
  $temp.remove();
}
