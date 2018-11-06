@extends('layouts.app_front')

@section('header')
	@include('site.inner_header')
@stop

@section('pagetitle', 'Need Help')
@section('content')

<!-- Breadcrumb Sec-->
<section class="main-beadcrumb">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<ol class="breadcrumb">
				  <li class="breadcrumb-item"><a href="{{ '/' }}">Home</a></li>
				  <li class="breadcrumb-item active"> Need Help</li>
				</ol>
			</div>
		</div>
	</div>
</section>
<!-- End -->

@if(count($need_help) > 0)
	{!!html_entity_decode($need_help->description)!!}
@else
	<section class="banner-sec">
	<div class="container">
		<div class="content sign-up-sec">
			<div class="row">
				<div class="col-sm-12">
					<h2>No Need Help Added.</h2>
				</div>
			</div>
		</div>
	</div>
</section>	
@endif	
<!-- End -->
@endsection

