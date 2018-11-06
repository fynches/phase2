$(document).ready(function () {

	$('#site_login').validate({
	    rules: {
	        login_email: {
	            required: true,
	            email: true,
	        },
	        password: {
	            required: true,
	        },
	        
	    },
	    messages: {
	        login_email: {
	            required: "Please enter email address.",
	            email: "Please enter a valid email address."
	        },
	        password: "Please enter password."
	    },
			
	});
	
	$('#forgot_password').validate({
	    rules: {
	        forgot_email: {
	            required: true,
	            email: true,
	        },
	    },
	    messages: {
	        forgot_email: {
	            required: "Please enter email address.",
	            email: "Please enter a valid email address."
	        },
	    },
			
	});
	
	
	$('#site_register').validate({
	    rules: {
	    	first_name: {
	            required: true,
	        },
	        last_name: {
	            required: true,
	        },
	        email: {
	            required: true,
	            email: true,
	            remote: {
					    url: "/validate_email",
					    type: "get",
					    data: {
					      _token: function() {
					        return "{{csrf_token()}}"
					      }
					    }
					  },
	        },
	        password: {
	            required: true,
	            minlength: 6
	        },
	        confirm_password: {
	                required: true,
	                equalTo: "#password",
	                minlength: 6
	            }
	    },
	    messages: {
	    	first_name: {
	            required: "Please enter first name.",
	        },
	        last_name: {
	            required: "Please enter last name.",
	        },
	        email: {
	            required: "Please enter email address.",
	            email: "Please enter a valid email address.",
	            remote:"The email has already been taken."
	        },
	        password: {
	                required: "Please enter password.",
	                minlength: "Your password must be at least 6 characters long."
	            },
	        confirm_password: {
	                required: "Please enter confirm password.",
	                minlength: "Your password must be at least 6 characters long.",
	                equalTo: "Password and confirm password must be same."
	            }
	    },
			
	});
	
	$("#login_email,#passwords").keypress(function(){
		$('#invalid-cridentials').css('display','none');
		$('#inactive-user').css('display','none');
		$('#inactive-token').css('display','none');
		$('#email-not-exists').css('display','none');
		
	});
	
	
	$(document).on('keydown','#site_login',function(e){	
	  		var key = e.which;
	  		if(key === 13)
	  		{
	  			$("#log_in_site").click();
	  		}
	});
	
	$( "#site_register" ).submit(function( event ) {
		
		if($('#site_register').valid())
		{
			$('body').loading('start');	
		}
		
	});
	
	$( "#forgot_password" ).submit(function( event ) {
		
		return false;
		
	});
	
	$("#log_in_site").click(function(){
	    
	    var email_id= $('#login_email').val();
	    var password = $('#passwords').val();
	    
	    if($('#site_login').valid())
	    {
	    	$('body').loading('start');
		    $.ajax({
		    		headers: {
				    	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				  	},
		            type: "POST",
		            //url: 'login',
		            url : window.base_url+'/login', 
		            dataType: 'json',
		            data: {email: email_id, password: password},
		            success: function (response) {
		            	//alert(response.status);
		                //swal("Good job!", "you are login!", "error");
		                $('#login').loading('stop');
		                if (response.status == "1")
		                {
		                	if(response.reirect_url == window.base_url+'/signup')
		                	{
		                		//window.location =  window.base_url;
		                		window.location =  window.base_url+'/dashboard';
		                	}else{
		                		window.location =  response.reirect_url;	
		                		//window.location =  window.base_url+'/dashboard';
		                	}
		                	
		                }else if(response.status == "2"){
		                	$('#inactive-user').css('display','block');
		                	$('#invalid-cridentials').css('display','none');
							$('#inactive-token').css('display','none');
							$('#email-not-exists').css('display','none');
							$('body').loading('stop');
		                }else if(response.status == "4"){
		                	window.location =  window.base_url + '/checkout-event';
		                }else if(response.status == "5"){
		                	$('#inactive-token').css('display','block');
		                	$('#invalid-cridentials').css('display','none');
							$('#inactive-user').css('display','none');
							$('#email-not-exists').css('display','none');
							$('body').loading('stop');
		                }else if(response.status == "6"){
		                	$('#email-not-exists').css('display','block');
		                	$('#invalid-cridentials').css('display','none');
							$('#inactive-user').css('display','none');
							$('#inactive-token').css('display','none');
							$('body').loading('stop');
							
		                }else{
		                	$('#invalid-cridentials').css('display','block');
		                	$('#inactive-user').css('display','none');
							$('#inactive-token').css('display','none');
							$('#email-not-exists').css('display','none');
							$('body').loading('stop');
		                }
		            }
		        });
	    }
	    
	});
	
	$("#forgot_email").keypress(function(){
		$('#not-exits-email-id').css('display','none');
		$('#inactive-email-id').css('display','none');
		
	});
	
	$('#forgot_passwords').click(function(){
		$('#login').modal('hide');
	});
	
	$("#forgot_pass").click(function(){
	    
	    var email_id= $('#forgot_email').val();
	    
	    if($('#forgot_password').valid())
	    {
	    	$('#forgotpassword').loading('start');
	    	
		    $.ajax({
		    		headers: {
				    	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				  	},
		            type: "POST",
		            url: window.base_url+'/forgot_password',
		            data: {email: email_id,token:$('meta[name="csrf-token"]').attr('content')},
		            success: function (response) {
		                //swal("Good job!", "you are login!", "error");
		                //alert(response);
		                $('#forgotpassword').loading('stop');
		                if(response == "1"){
		                	//location.reload();
		                	window.location =  window.base_url+'/signup';
		                }
		                else if(response == "2")
		                {
		                	$('#inactive-email-id').css('display','block');
		                	$('body').loading('stop');
		                }else if(response == "3"){
		                	$('#not-exits-email-id').css('display','block');
		                	$('body').loading('stop');
		                }
		                
		            }
		        });
	    }
	    
	});
});

