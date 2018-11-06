@extends('layouts.app_front')

@section('header')
	@include('site.inner_header')
@stop

@section('pagetitle', 'Contact Us')
@section('content')

<!-- Breadcrumb Sec-->
<section class="main-beadcrumb">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<ol class="breadcrumb">
				  <li class="breadcrumb-item"><a href="{{'/'}}">Home</a></li>
				  <li class="breadcrumb-item active">Contact</li>
				</ol>
			</div>
		</div>
	</div>
</section>
<!-- End -->

<!-- Contact Sec -->
<section class="contact">
	<div class="container">
		@include('layouts.front-notifications')
		<div class="row">
			<div class="col-md-12">
				<h2>How can we help you? </h2>
				<p>
					@if(count($contactUs) > 0)
						{!!html_entity_decode($contactUs->description)!!}
					@endif
				</p>
				{!! Form::open(array('url'=>'/contact-us', 'class'=>'form-horizontal','method'=>'POST','id'=>'contact_us_form','files'=>true)) !!}
					<div class="form-group">
	                  <input type="email" name="email" class="form-control" id="email" placeholder="User email">
	                </div>
	                <div class="form-group">
	                  <input type="text" id="subject" name="subject" class="form-control" placeholder="Subject">
	                </div>
	                <div class="form-group">
					    <textarea class="form-control" name="description" id="description" rows="9" placeholder="Type here…"></textarea>
					</div>
					<input type="submit" name="send" value="SEND">
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</section>
<!-- End -->

<script type="text/javascript">
	$(document).ready(function(){
		$('#contact_us_form').validate({
	    rules: {
	        email: {
	            required: true,
	            email:true
	        },
	        subject:{
	        	required: true,
	        },
	        description:{
	        	required: true,
	        }
	    },
	    messages: {
	        email: {
	            required: "Please enter email",
	            email:"Please enter valid email"
	        },
	        subject:{
	        	required: "Please enter subject",
	        },
	        description:{
	        	required: "Please enter description",
	        }
	    },
			
	});
});
</script>	
@endsection

