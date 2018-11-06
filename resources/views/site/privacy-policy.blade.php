@extends('layouts.app_front')

@section('header')
	@include('site.inner_header')
@stop

@section('pagetitle', 'Privacy Policy')
@section('content')

<!-- Breadcrumb Sec-->
<section class="main-beadcrumb">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<ol class="breadcrumb">
				  <li class="breadcrumb-item"><a href="{{ '/' }}">Home</a></li>
				  <li class="breadcrumb-item active"> Privacy policy</li>
				</ol>
			</div>
		</div>
	</div>
</section>
<!-- End -->

<!-- Join the Party Sec -->
@if(count($privacyPolicy) > 0)
	{!!html_entity_decode($privacyPolicy->description)!!}
@else
	<section class="banner-sec">
	<div class="container">
		<div class="content sign-up-sec">
			<div class="row">
				<div class="col-sm-12">
					<h2>No Privacy Policy Added.</h2>
				</div>
			</div>
		</div>
	</div>
</section>	
@endif	
<!-- End -->
@endsection

