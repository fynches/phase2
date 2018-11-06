<!DOCTYPE html>
<html>
<head>
	<title>Fynches</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name=viewport content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i|Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="front/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="front/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="front/css/aos.css">
	<link rel="stylesheet" type="text/css" href="front/css/all.css">
	<link rel="stylesheet" type="text/css" href="front/css/owl.carousel.min.css">
	<link rel="stylesheet" type="text/css" href="front/css/style.css">
	<link rel="stylesheet" type="text/css" href="front/css/responsive.css">
</head>
<body>
<!-- Header Sec -->
@include('layouts.beta-header')
<!-- End Header -->

<!-- Banner Sec -->
<section class="banner-sec beta-banner-sec">
	<div class="inner-banner">
		<div class="container">
			<div class="row">
				<div class="col-sm-12 col-md-6 col-lg-7 col-xl-6 order-sm-1 order-md-2 order-lg-2 order-xl-2">
					<div class="rgt-img">
						<img data-aos="fade-up" src="{{ asset('front/img/ballon.png')}}" alt="ballon" title="" class="img-fluid" />
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
			      		<img src="{{ asset('front/img/img5.png')}}" alt="" title="" class="img-fluid">
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
<section class="how-it-work">
	<div class="container">
		<div class="row">
			<div class="col-md-12"><h2 class="title">How It Works</h2></div>
		</div>
		<div class="row">
			<div class="col-sm-12 col-md-4 col-lg-4 text-center pt-3">
				<div class="cust-icon"><img src="{{ asset('front/img/flag.png')}}" alt="flag" title=""></div>
				<h3>Create Your Gift Page</h3>
				<p>Fynches will auto-magically create a gift page for your child that you can easily customize.</p>
			</div>
			<div class="col-sm-12 col-md-4 col-lg-4 text-center pt-3">
				<div class="cust-icon"><img src="{{ asset('front/img/Gift.png')}}" alt="gift" title=""></div>
				<h3>Add Your Gifts</h3>
				<p>Choose form our amazing collection of curated gifts and experiences and add them to your gift page.</p>
			</div>
			<div class="col-sm-12 col-md-4 col-lg-4 text-center pt-3">
				<div class="cust-icon"><img src="{{ asset('front/img/bird.png')}}" alt="bird" title=""></div>
				<h3>Share Your Gift Page</h3>
				<p>Easily share your Fynches gift page with friends and family so they can purchase a gift for your child.</p>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 text-center"><button class="btn common pink-btn"data-toggle="modal" data-target="#largeModal">SIGN UP FOR EARLY ACCESS</button></div>
		</div>
	</div>
</section>
<!-- End -->
<!-- Gifts and Experiences Sec -->
<section class="experience">
	<div class="container">
		<div class="row">
			<div class="col-md-12 text-center">
				<h2 class="title">Gifts and Experiences Your Child Will Love.</h2>
				<p>From swimming lessons to adventure parks we have everything you need to make your child’s next gift an unforgettable experience.</p>
				<button class="btn common btn-border" data-toggle="modal" data-target="#largeModal">SIGN UP FOR EARLY ACCESS</button>
			</div>
		</div>
	</div>
</section>
<!-- End -->
<!-- Gifting Sec -->
<section class="gifting">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-sm-12 col-md-5 col-lg-5 order-sm-1 order-md-2 order-lg-2"><img src="{{ asset('front/img/Gifting-Easy.png')}}" alt="Gifting" title="" class="img-fluid"></div>
			<div class="col-sm-12  col-md-7 col-lg-7 order-sm-2 order-md-1 order-lg-1 pr-0">
				<h2 class="title">Gifting Made Easy-Peasy For You and Your Guests</h2>
				<p>Fynches takes the guessing out of gifting. No more dreaded gift duplicates, endless gift questions, or last-minute trips to a store.</p>
				<ul>
					<li>Reduce the time your friends and family have to spend searching for a gift.</li>
					<li>Avoid duplicate gifts, over-gifting, and constantly answering the “what should we get?” question.</li>
					<li>Help friends and family gift experiences that will help your child grow and create magical memories.</li>
				</ul>
				<button class="btn common pink-btn" data-toggle="modal" data-target="#largeModal">SIGN UP FOR EARLY ACCESS</button>
			</div>
		</div>
	</div>
</section>
<!-- End -->
<!-- Kids and Parents Sec -->
<section class="slider-sec">
  <h2 class="title">Kids and Parents Love Fynches</h2>
  <div class="owl-carousel">
    <div class="item">
      <div class="lft-sec">
      	<div class="rounded-circle">
      		<img src="{{ asset('front/img/Mom.png')}}" alt="" title="" class="img-fluid">
      	</div>
      </div>
      <div class="rgt-sec">
      	<blockquote>
		    <p>Fynches is my go-to birthday solution for my daughter. Love it! </p>
		    <footer>- Heather M.</footer>
		</blockquote>
      </div>
    </div>
    <div class="item">
      <div class="lft-sec">
      	<div class="rounded-circle">
      		<img src="{{ asset('front/img/Couple.png')}}" alt="" title="" class="img-fluid">
      	</div>
      </div>
      <div class="rgt-sec">
      	<blockquote>
		    <p>Fynches is my go-to birthday solution for my daughter. Love it! </p>
		    <footer>- Heather M.</footer>
		</blockquote>
      </div>
    </div>
    <div class="item">
      <div class="lft-sec">
      	<div class="rounded-circle">
      		<img src="{{ asset('front/img/Mom.png')}}" alt="" title="" class="img-fluid">
      	</div>
      </div>
      <div class="rgt-sec">
      	<blockquote>
		    <p>Fynches is my go-to birthday solution for my daughter. Love it! </p>
		    <footer>- Heather M.</footer>
		</blockquote>
      </div>
    </div>
    <div class="item">
      <div class="lft-sec">
      	<div class="rounded-circle">
      		<img src="{{ asset('front/img/Couple.png')}}" alt="" title="" class="img-fluid">
      	</div>
      </div>
      <div class="rgt-sec">
      	<blockquote>
		    <p>Fynches is my go-to birthday solution for my daughter. Love it! </p>
		    <footer>- Heather M.</footer>
		</blockquote>
      </div>
    </div>
  </div>
</section>
<!-- End -->
<!-- Happiness Is Our Mission Sec -->
<section class="our-mission">
	<div class="container">
		<div class="row">
			<div class="col-md-12 text-center">
				<h2 class="title">Happiness Is Our Mission </h2>
				<p>We believe that providing fun and enriching gift experiences helps children lead healthier and happier lives.</p>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 col-md-6 col-lg-6">
				<div class="lft-img">
					<img src="{{asset('front/img/img1.png')}}" alt="" title="" class="img-fluid">
				</div>
			</div>
			<div class="col-sm-12 col-md-6 col-lg-6">
				<ul>
					<li>Experiences help kids develop friendships, empathy, curiosity, and set them on a path of self-discovery.</li>
					<li>Gifts that offer a child a chance to learn, explore, and have fun offer lasting memories they will cherish forever. </li>
					<li>Scientific research has proven that providing kids with meaningful experiences helps them lead happier lives.</li>
				</ul>
			</div>
		</div>
	</div>
</section>
<!-- End -->
<!-- Footer Sec -->
<footer class="footer">
	<div class="container">
		<div class="footer-top">
			<div class="row align-items-center">
				<div class="col-12 col-sm-6 col-md-6 col-lg-6 ftr-logo">
					<a href="javascript:void(0)"><img src="{{ asset('front/img/f-logo.png')}}" alt="logo" title=""></a>
				</div>
				<div class="col-12 col-sm-6 col-md-6 col-lg-6 text-right">
					<ul class="social">
						<li><a href="javascript:void(0)"><i class="fab fa-twitter"></i></a></li>
						<li><a href="javascript:void(0)"><i class="fab fa-facebook-f"></i></a></li>
						<li><a href="javascript:void(0)"><i class="fab fa-instagram"></i></a></li>
						<li><a href="javascript:void(0)"><i class="fab fa-pinterest-p"></i></a></li>
					</ul> 
				</div>
			</div>
		</div>
		<div class="footer-btm">
			<div class="row align-items-center">
				<div class="col-lg-12">
					<p>&copy; 2019 Fynches. All rights reserved</p>
				</div>
			</div>
		</div>
	</div>
</footer>
<!-- End -->
@include('site.beta-form')
<!-- End -->
<script type="text/javascript" src="{{ asset('front/js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('front/js/custom.js')}}"></script>
<script type="text/javascript" src="{{ asset('front/js/aos.js')}}"></script>
<script type="text/javascript" src="{{ asset('front/js/css3-animate-it.js')}}"></script>
<script type="text/javascript" src="{{ asset('front/js/bootstrap.min.js')}}"></script>  
<script type="text/javascript" src="{{ asset('front/js/owl.carousel.min.js')}}"></script>

<script type="text/javascript" src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.js')}}"></script>  
<script type="text/javascript" src="{{ asset('assets/global/plugins/jquery-validation/js/additional-methods.js')}}"></script>  
<script type="text/javascript">
	AOS.init({
  duration: 1200,
});
</script>
</body>
</html>