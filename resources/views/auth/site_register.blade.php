@extends('layouts.app_front')
@section('header')
	@include('site.inner_header')
@stop
@section('pagetitle', 'SignUp')
@section('content')

<?php 
$data = session()->all();
$msg="";
if(count($data) > 0)
{
	if(isset($data['success_msg']))
	{
		$msg = $data['success_msg'];
	}
}
?>
<section class="form-sec signin">
	<div class="container">
		<h2>Letʼs get started</h2>
		<p>We’ll walk you through the process of creating experiences and linking them to your special event.</p>

		<div class="login-frm">
			 @include('layouts.front-notifications')
			
			<div class="row">
				
				<div class="col-sm-8 cust-frm">
					<form method="POST" action="{{ route('site.register') }}" id="site_register">
						 @csrf
						<div class="row">
							<div class="col-sm-6 text-right">
								<a href="<?php echo url('/').'/redirect/facebook/register';?>" class="sign-up"><i class="fa fa-facebook" aria-hidden="true"></i>Facebook Sign Up</a>
							</div>
							<div class="col-sm-6">
								<a href="<?php echo url('/').'/redirect/google/register';?>" class="sign-up gmail"><i class="fa fa-google-plus" aria-hidden="true"></i>Google Plus</a>
							</div>
						</div>
						<div class="brd-title">
							<h3>OR</h3>
						</div>
						<div class="row ds-padding">
							<div class="col-sm-6">
								<div class="form-group">
				                  <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name">
				                </div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
				                  <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name">
				                </div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
				                  <input type="email" class="form-control" id="register_email" name="email" placeholder="Email">
				                </div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
				                  <input type="password" class="form-control" id="password" name="password" placeholder="Password">
				                </div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
				                  <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password">
				                </div>
							</div>
						</div>
						
						
						<div class="row">
							<div class="col-sm-12">
								<input type="submit" name="" value="Sign Up">
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<p class="privcy">By signing up, you agree to the <a target="_blank" href="{{ url('privacy-policy') }}">Privacy Policy &amp; Terms of Service</a></p>
							</div>
						</div>
					</form>
				</div>
				<div class="col-sm-4">
					<h4>Experiences are the path to happiness.</h4>
					<ul>
						<li>1. Create an experience</li>
						<li>2. Share with friends & family</li>
						<li>3. Gift to your kids</li>
					</ul>
					<h5>Have an account?<a href="javascript:void(0)" class="login_popup"  data-toggle="modal" data-target="#login">Log in</a></h5>
				</div>
			</div>
		</div>
      </div>	
</section>

<script type="text/javascript">
$( document ).ready(function() {
	var chk_login_session='<?php echo Auth::guard('site')->check();?>';
	
	if(chk_login_session=="1")
	{
		window.location =  window.base_url+'/dashboard';
	}
	var session_msg= '<?php echo $msg;?>';
	if(session_msg == "Email Verify successfully.")
	{
		$('.login_popup').click();
	}
});
</script>
@endsection
