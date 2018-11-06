@extends('layouts.app')
@section('content')
@if (Session::has('message'))
   <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif

<form class="login-form" action="{{ url('admin/login') }}" method="post" id="admin_login_frm">
    {!! csrf_field() !!}
    <div class="form-title">
        <span class="form-title">Welcome.</span>
        <span class="form-subtitle">Please login.</span>
    </div>
    <div class="alert alert-danger display-hide">
        <button class="close" data-close="alert"></button>
        <span> Enter any email and password. </span>
    </div>
    <div class="form-group">
        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
        <label class="control-label visible-ie8 visible-ie9">Email</label>
        <input class="form-control form-control-solid placeholder-no-fix" type="email" autocomplete="off" placeholder="Enter email" name="email" id="email" value="{{ old('email') }}"/> </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">Password</label>
        <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" id="password" placeholder="Password" name="password" /> </div>

    <div class="form-actions">
        <div class="pull-left">
           <button type="submit" class="btn red btn-block uppercase">Login</button>
        </div>
        <div class="pull-right forget-password-block">
            <a href="{{ url('/admin/password/reset') }}" id="forget-password" class="forget-password">Forgot Password?</a>
        </div>
    </div>
</form>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<script type="text/javascript">
$(document).ready(function () {

	$('#admin_login_frm').validate({
	    rules: {
	        email: {
	            required: true,
	            email: true,
	        },
	        password: {
	            required: true,
	        },
	        
	    },
	    messages: {
	        email: {
	            required: "Please enter email address.",
	            email: "Please enter a valid email address."
	        },
	        password: "Please enter password."
	    },
			
	});
});	
</script>

<style>
label.error{
    color: red;
}
</style>
@endsection
