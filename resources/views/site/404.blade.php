<!DOCTYPE html>
<html>
<head>
	<title>Fynches-404 Error</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name=viewport content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link href="https://fonts.googleapis.com/css?family=Rancho" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Bitter:400,400i,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto+Slab:300,400,700" rel="stylesheet">
<!-- 	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker3.css"> -->
	{{Html::style("/front/css/font-awesome.min.css")}}
	{{Html::style("/front/css/bootstrap.min.css")}}
	{{Html::style("/front/css/owl.carousel.min.css")}}
	{{Html::style("/front/css/style.css")}}
</head>
<body class="full-body-bg">

<!-- Header Sec -->
<header class="inner-pg">
	<nav class="navbar navbar-expand-lg navbar-default">
      <div class="container">
        <a class="navbar-brand" href="{{ url ('/') }}">
          <img src="{{ asset('front/images/Fynches_Logo_Teal.png') }}" width="150" height="30" alt="">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <!-- <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link" href="#">ABOUT</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">BLOG</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">SIGN IN</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">GET STARTED</a>
            </li>
          </ul>
         <form>
              <a class="src-img" href="javascript:void(0);"><i class="fa fa-search" aria-hidden="true"></i></a>
              <input class="search expandright" name="searchtext" placeholder="Search" required="" type="search">
         </form>
        </div> -->
      </div>
    </nav>
</header>
<!-- End -->

<!-- Join the Party Sec -->
<section class="form-sec process-sec blue-bg error-page">
	<div class="container">
		<div class="banner-content-sec">
			<h2>404</h2>
			<h3>Ooups!</h3>
			<p>The web page not found try again.</p>
			<a href="{{'/'}}" class="commont-btn">Back To Home</a>
		</div>
      </div>	
</section>
<!-- End -->

<!-- Footer Sec -->
<footer class="cm-ftr">
	<div class="container">
		<div class="row">
			<div class="col-sm-12"><a href="javascript:void(0)" class="logo"><img src="{{ asset('front/images/Fynches_Logo_Teal.png') }}" alt="" title=""></a></div>
		</div>
		<div class="row pt-2 pb-0">
			<div class="col-md-12 col-lg-10">
				<!-- <ul class="list-unstyled">
					<li><a href="javascript:void(0)">Create Experience</a></li>
					<li><a href="javascript:void(0)">About</a></li>
					<li><a href="javascript:void(0)">Blog</a></li>
					<li><a href="javascript:void(0)">FAQ</a></li>
					<li><a href="javascript:void(0)">Contact Us</a></li>
				</ul> -->
			</div>
			<div class="col-md-12 col-lg-2">
				<div class="social-sec">
					<a target="__blank" href="https://twitter.com/fynches"><i class="fa fa-twitter" aria-hidden="true"></i></a>
					<a target="__blank" href="https://www.facebook.com/fynchescom"><i class="fa fa-facebook" aria-hidden="true"></i></a>
					<a target="__blank" href="https://www.instagram.com/fynches"><i class="fa fa-instagram" aria-hidden="true"></i></a>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<ul class="list-unstyled copyright">
					<li>&copy; 2018 All Right Reserved</li>
					<li><a target="_blank" href="{{ url('terms-condition') }}">Term &amp; Conditions</a></li>
					<li><a target="_blank" href="{{ url('privacy-policy') }}">Privacy Policy</a></li>
				</ul>
			</div>
		</div>
	</div>
</footer>
<!-- End -->

{{Html::script("/front/js/jquery.min.js")}}
{{Html::script("/front/js/bootstrap.min.js")}}

</body>
</html>