<!DOCTYPE html>
<html>
<head>
	<title>Fynches- @yield('pagetitle')</title>
	<link rel="shortcut icon" type="image/png" href="{{asset('favicon.png')}}"/>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name=viewport content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i|Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
	@yield('head-section')	
	{!! HTML::style('/front/css/font-awesome.min.css') !!}
	{!! HTML::style('/front/css/bootstrap.css') !!}
	{!! HTML::style('/front/css/aos.css') !!}
	{!! HTML::style('/front/css/all.css') !!}
	{!! HTML::style('/front/css/owl.carousel.min.css') !!}
	{!! HTML::style('/front/css/style.css') !!}
	{!! HTML::style('/front/css/custom.css') !!}
	{!! HTML::style('/front/css/responsive.css') !!}
</head>

<body>
@include('site.header.header')
@yield('content')
@include('site.footer.footer')

{{Html::script("/front/js/jquery.min.js")}}
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>	
{{Html::script("/front/js/owl.carousel.min.js")}}	
{{Html::script("/front/js/aos.js")}}
{{Html::script("/front/js/css3-animate-it.js")}}
{{Html::script("/front/js/bootstrap.min.js")}}
{{Html::script("/front/js/custom.js")}}

@yield('jsscript')
</body>
</html>