<!-- Login Modal -->
<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title">Log In To Your Account</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<div class="login">
      		<div class="brd-title">
				<h3>OR</h3>
			</div>
      		<div class="row">
      			<div class="col-sm-6">
					<a href="<?php echo url('/').'/redirect/facebook/login';?>" class="sign-up"><i class="fa fa-facebook" aria-hidden="true"></i>Facebook Log In</a>
					<a href="<?php echo url('/').'/redirect/google/login';?>" class="sign-up gmail"><i class="fa fa-google-plus" aria-hidden="true"></i>Google Plus Log In</a>
				</div>
					
      			<div class="col-sm-6">
      				<form id="site_login" method="POST" action="{{ route('site.login.submit') }}">
      					{{ csrf_field() }}
      					<div class="form-group">
		                  <input type="email" id="login_email" name="login_email" class="form-control" autocomplete="off"  placeholder="Email">
		                </div>
		                <div class="form-group">
		                  <input type="password" id="passwords" name="password" class="form-control"  placeholder="Password">
		                  <label id="invalid-cridentials" class="custom_error" for="invalid-cridentials" style="display: none;">Invalid Credentials.</label>
		                  <label id="inactive-user" class="custom_error" for="invalid-cridentials" style="display: none;">You are inactive by admin, please contact to admin.</label>
		                  <label id="inactive-token" class="custom_error" for="invalid-cridentials" style="display: none;"> Email Verify link sent to your mail.Please check your mail.</label>
		                  <label id="email-not-exists" class="custom_error" for="invalid-cridentials" style="display: none;"> Email id not exits.</label>
		                </div>
		                <input type="button" name="" value="Log In" id="log_in_site">
		                <a href="javascript:void(0)" id="forgot_passwords"  data-toggle="modal" data-target="#forgotpassword">Forgot your password?</a>
		            </form>
      			</div>
      		</div>
      		<div id="modal-loader"  style="display: none; text-align: center;">
            	<img src="{{ asset('assets/global/img/loading.gif') }}">
        	</div>
      		<div class="row">
      			<div class="col-sm-12">
      				<p>Don't have an account?<a href="{{ url('signup') }}">Sign up for free</a></p>
      			</div>
      		</div>
      	</div>
      </div>
    </div>
  </div>
</div>
<!--- End -->