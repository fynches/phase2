 $(document).ready(function () {
 		$("#betaForm").validate({
	        ignore: [],
	        highlight: function (element) {
	            $(element).parent('div').addClass('has-error');
	        },
	        unhighlight: function (element) {
	            $(element).parent('div').removeClass('has-error');
	        },
	        errorElement: 'span',
	        errorClass: 'help-block help-block-error',
	        errorElement: 'div',
	        rules: {
				firstName: {required: true},
				email: {required: true}
			},
	        errorPlacement: function (error, element) {         
	                error.insertAfter(element);           
	        },
	        success: function (element) {
	            $(element).parent('.form-group').removeClass('has-error');
        	}
		});
		
		$( '#betaForm' ).on( 'submit', function(e) {
			e.preventDefault();
			if($(this).valid()) {
				$.ajax({
					type: 'post',
					url: '/betaSignupUser',
					data: {
						'_token': $('input[name=_token]').val(),
						'firstName': $('input[name=firstName]').val(),
						'email': $('input[name=email]').val()
					},
				   success: function(data) {
					   	if(data.status == "0"){
							var html = '';
							$.each(data.errors, function(key, value){								
								html += '<p>'+value+'</p>';
							});
							$('#betaErrorMessage').html(html);
						}else {
							$('#betaErrorMessage').html('');
							$("#betaForm")[0].reset();
							$('#betaSuccessMessage').html(data).fadeIn('slow');
							$('#betaSuccessMessage').html("We will get back to you shortly").fadeIn('slow')
							$('#betaSuccessMessage').delay(15000).fadeOut('slow');
						}
						
					} 
				}).done(function(data) {
				});
			}
			
		});
    });