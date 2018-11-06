var html_addimage = $(".file_input_div").html();

function randomPassword(length) {
        var chars = "abcdefghijklmnopqrstuvwxyz!@#$%^&*()-+<>ABCDEFGHIJKLMNOP1234567890";
        var pass = "";
        for (var x = 0; x < length; x++) {
            var i = Math.floor(Math.random() * chars.length);
            pass += chars.charAt(i);
        }
        return pass;
    }

    function generate() {
        
        var auto_pass = randomPassword(8);
        $('#password,#confirmpassword').val(auto_pass);
    }
    
 $(document).ready(function () {
	
	$("form").submit(function(e){
    	if ($("#user").valid()) {
			$(this).find(':input[type=submit]').prop('disabled', true);
		}
    });

	$('.onlynumallow').ForceNumericOnly();
        $('#user').validate({
            rules: {
                firstname: "required",
                lastname: "required",
                email: {
                    required: true,
                    email: true,
                },
                password: {
                    required: true,
                    minlength: 6
                },
                user_type: "required"
            },
            messages: {
                firstname: "Please enter your first name.",
                lastname: "Please enter your last name.",
                password: {
                    required: "Please provide password.",
                    minlength: "Your password must be at least 6 characters long."
                },
                email: {
                    required: "Please enter email address.",
                    email: "Please enter a valid email address."
                },
                user_type: "Please select user type."
            },
	   			
        });
    });

