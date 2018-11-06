@extends('layouts.app_front')

@section('header')
	@include('site.inner_header')
@stop

@section('pagetitle', 'Event')
@section('content')

<!-- Banner Sec -->
<section class="banner-sec success-pg">
	<div class="container">
		@if(!Auth::guard('site')->check())
		 @include('layouts.front-notifications') 
		@endif 
		<div class="content sign-up-sec">
			<div class="row">
				<div class="col-sm-12">
					<?php 
			    		$email ="";
						$email = session()->get( 'email' );
					?>
					
					<h2>Your payment was successful! </h2>
					<p>We've sent a receipt to the email address {{$email}}</p>
					<a href="{{ url('event') }}" class="commont-btn">WANT TO CREATE YOUR OWN EVENT?</a>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- End -->
<!-- How It Work Sec -->
<section class="how-wrok-sec home-common success-work">
	<div class="container">
		<img src="{{ asset('front/images/group.png') }}" alt="" title="" class="pos-img"> 
	</div>
</section>
<!-- End -->

<script type="text/javascript">
	$( document ).ready(function() {
		$('header').removeAttr( "class" );
});
</script>
@endsection