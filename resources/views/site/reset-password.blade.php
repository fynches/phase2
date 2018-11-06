@extends('layouts.app_front')
@section('pagetitle', 'Reset Password')
@section('content')

<section class="form-sec signin changepass">
	<div class="container">
		<div class="login-frm">
			<div class="row">
				<div class="col-sm-8 cust-frm">
					<form method="POST" action="{{ url('update_passwords') }}" id="reset_pass_frms">
						 {{ csrf_field() }}
						@include('layouts.front-notifications')
						<div class="row">
							<div class="col-sm-12">
								<h3>Change Password</h3>
							</div>
						</div>
						
						<div class="row ds-padding">
							<div class="col-sm-12">
								<div class="form-group">
				                  <input id="email" type="email" class="form-control" placeholder="Enter email address" autocomplete="off" name="email" value=""> 
				                </div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
				                  <input class="form-control" type="password" autocomplete="off" placeholder="Password" name="password" id="password"/> 
				                </div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
				                  <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" placeholder="Confirm password" required>
				                </div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-sm-12">
								<input type="submit" name="" value="Update">
							</div>
						</div>
						
					</form>
				</div>
				
			</div>
		</div>
      </div>	
</section>

<script type="text/javascript">
$('#reset_pass_frms').validate({
	    rules: {
	    	email: {
	            required: true,
	            email: true,
	        },
	        password: {
	            required: true,
	            minlength: 6
	        },
	        password_confirmation: {
	                required: true,
	                equalTo: "#password",
	                minlength: 6
	            }
	    },
	    messages: {
	    	email: {
	            required: "Please enter email address.",
	            email: "Please enter a valid email address."
	        },
	        password: {
	                required: "Please enter password.",
	                minlength: "Your password must be at least 6 characters long."
	            },
	        password_confirmation: {
	                required: "Please enter confirm password.",
	                minlength: "Your password must be at least 6 characters long.",
	                equalTo: "Password and confirm password must be same."
	            }
	    },
			
	});
</script>
@endsection

