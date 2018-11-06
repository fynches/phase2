<!DOCTYPE html>
<html>
<head>
	<title>Fynches- @yield('pagetitle')</title>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name=viewport content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

	<link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i|Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/front/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="/front/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="/front/css/aos.css">
	<link rel="stylesheet" type="text/css" href="/front/css/all.css">
	<link rel="stylesheet" type="text/css" href="/front/css/owl.carousel.min.css">
	<link rel="stylesheet" type="text/css" href="/front/css/style.css">
	<link rel="stylesheet" type="text/css" href="/front/css/responsive.css">
	
	<script>
		window.base_url = '<?php echo url('/'); ?>';
		window.AuthUser = "{{{ (Auth::user()) ? Auth::user() : null }}}";
    </script>
    

	
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAp_IdiINObhBDXN7TwuFkmBAGhiR-jC54&sensor=false&libraries=places"></script>
</head>
<body>
	
<?php  $controller = Request::segment(1);

//echo $name = Route::currentRouteName();
//echo "@@@@@@@@@@@@@@".$controller;
//echo $actionName = Route::getActionName();exit;
?>

@yield('header')

@include('errors.common_errors')
 
 @yield('content')

<section class="join-party-sec">
	<!-- <div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-6">
				<h3>Join the party!</h3>
			</div>
			<div class="col-sm-12 col-md-6">
				<a href="javascript:void(0)" class="commont-btn">CREATE AND EXPERIENCE</a>
			</div>
		</div>
	</div> -->
</section>
<!-- End -->
@include('site.footer')
{{Html::script("/front/js/jquery.loading.js")}}
{{Html::script("/front/common/comman.js")}}
</body>
</html>
