@extends('layouts.app_front')
@section('content')
<!-- Banner Sec -->
<section class="banner-sec beta-banner-sec">
	<div class="inner-banner">
		<div class="container">
			<div class="row">
				<div class="col-sm-12 col-md-6 col-lg-7 col-xl-6 order-sm-1 order-md-2 order-lg-2 order-xl-2">
					<div class="rgt-img">
						<img data-aos="fade-up" src="{{asset('front/img/ballon.png')}}" alt="ballon" title="" class="img-fluid" />
					</div>
				</div>
				<div class="col-sm-12 col-md-6 col-lg-5 col-xl-6 order-sm-2 order-md-1 order-lg-1 order-xl-1">
					<div class="lft-cnt" >
						<h2 class="">The First Gift Registry for Kids.</h2>
						<p>Part Gift List, Part Party Invitation, and All Fun.Parents Use Fynches to Make Birthday Gifting and Planning Easy.</p>
						<button class="btn common pink-btn" data-toggle="modal" data-target="#largeModal">SIGN UP FOR EARLY ACCESS</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="gift-page">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-sm-12 col-md-2 col-lg-1">
					<div class="rounded-circle">
			      		<img src="{{asset('front/img/img5.png')}}" alt="" title="" class="img-fluid">
			      	</div>
				</div>
				<div class="col-sm-12 col-md-10 col-lg-11">
					<h3>Childhood is about playing, discovery, and encouraging dreams.  </h3>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- End -->

<!-- How It Work Sec -->
@if(count($howItWorks) > 0)
	{!!html_entity_decode($howItWorks->description)!!}
@endif
<!-- End -->

<!-- Gifts and Experiences Sec -->
@if(count($giftsExperiences) > 0)
	{!!html_entity_decode($giftsExperiences->description)!!}
@endif
<!-- End -->

<!-- Gifting Sec -->
@if(count($gifting) > 0)
	{!!html_entity_decode($gifting->description)!!}
@endif
<!-- End -->

<!-- Kids and Parents Sec -->
<section class="slider-sec">
  <h2 class="title">Kids and Parents Love Fynches</h2>
  <div class="owl-carousel">
  @if(count($testimonails) > 0)
	@foreach($testimonails as $key=>$val)
		<?php				
		$defaultPath = 'storage/avatar.jpg';
		$TestimonaialImage = $val->image;
		if ($TestimonaialImage && $TestimonaialImage != "") {			
			$imgPath = 'storage/testimonialImages/' . $TestimonaialImage;
			if (file_exists($imgPath))
			{
				$imgPath = $imgPath;
			} else {
				$imgPath = $defaultPath;
			}
		} else {
			$imgPath = $defaultPath;
		}?>
	<div class="item">
      <div class="lft-sec">
      	<div class="rounded-circle">
      		<img src="{{asset($imgPath)}}" alt="" title="" class="img-fluid">
      	</div>
      </div>
      <div class="rgt-sec">
      	<blockquote>
		  	{!!html_entity_decode($val->description)!!}
			<footer>- {{ $val->name }}.</footer>
		</blockquote>
      </div>
    </div>
	@endforeach
	@endif
    
  </div>
</section>
<!-- End -->

<!-- Happiness Is Our Mission Sec -->
@if(count($ourMission) > 0)
	{!!html_entity_decode($ourMission->description)!!}
@endif
<!-- End -->

<!-- SignUp Model -->
@include('site.beta-signup.beta-signup-modal')
<!-- End -->
@endsection

@section('jsscript')
{{Html::script("/front/common/beta-signup/betasignup.js")}}
<script type="text/javascript">	
	$( document ).ready(function() {
		AOS.init({
			duration: 1200,
		});
	});
</script>
@endsection