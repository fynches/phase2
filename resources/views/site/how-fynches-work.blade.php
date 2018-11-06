@extends('layouts.app_front')
@section('pagetitle', 'How Fynches Works')
@section('content')

<!-- Breadcrumb Sec-->
<section class="main-beadcrumb">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<ol class="breadcrumb">
				  <li class="breadcrumb-item"><a href="{{ '/' }}">Home</a></li>
				  <li class="breadcrumb-item active"> How Fynches Works</li>
				</ol>
			</div>
		</div>
	</div>
</section>
<!-- End -->

@if(count($how_fynches_works) > 0)
	{!!html_entity_decode($how_fynches_works->description)!!}
@else
	<section class="banner-sec">
	<div class="container">
		<div class="content sign-up-sec">
			<div class="row">
				<div class="col-sm-12">
					<h2>No record Added.</h2>
				</div>
			</div>
		</div>
	</div>
</section>	
@endif	
<!-- End -->
@endsection

