@extends('layouts.app_front')

@section('header')
	@include('site.inner_header')
@stop

@section('pagetitle', 'FAQ')
@section('content')

<!-- Breadcrumb Sec-->
<section class="main-beadcrumb">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<ol class="breadcrumb">
				  <li class="breadcrumb-item"><a href="{{ '/' }}">Home</a></li>
				  <li class="breadcrumb-item active"> Faq</li>
				</ol>
			</div>
		</div>
	</div>
</section>
<!-- End -->

@if(count($faq) > 0)
	{!!html_entity_decode($faq->description)!!}
@else
	<section class="banner-sec">
	<div class="container">
		<div class="content sign-up-sec">
			<div class="row">
				<div class="col-sm-12">
					<h2>No Faq Added.</h2>
				</div>
			</div>
		</div>
	</div>
</section>	
@endif	
<!-- End -->
@endsection

