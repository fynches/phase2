<!DOCTYPE html>
<html>
<head>
	
	<title>Fynches</title>
	<link href="https://fonts.googleapis.com/css?family=Fredoka+One|Ubuntu:300,400,500,700" rel="stylesheet">
	<style type="text/css">
	p{font-size: 15px; line-height: 22px; color: #444; padding: 15px 0; margin: 0;float: left;width: 100%;}
	body {font-family: 'Ubuntu', sans-serif;}
	*{box-sizing: border-box;}
	.wapper{float:left;width: 100%;}
	.container{width: 600px;margin: 0 auto;}
	img{max-width: 100%;}
	.header-sec {float: left;width: 100%;padding:10px 10px;background: #2d5f98;}
	.footer-sec{float: left;width: 100%;padding:15px 10px;background: #2d5f98;}
	.logo-sec{float: left;width: 115px;margin-right: 50px;}
	.child-profile-sec{float: left;width:415px;}
	.child-profile-sec h5 {margin: 10px 0;font-size: 16px;font-weight: 500;color: #444;}
	.badge-sec {float: left;width: 100%;padding:25px;border: 2px solid #ffd865;}
	.badge-sec img {display: table;margin: 0 auto 20px;max-width: 100px;}
	.badge-title img{max-width: 100%;margin-bottom: 0;}
	.badge-title{width: 300px;margin:0 auto; display: table;position: relative;text-align: center;}
	.badge-title span {float: left;width: 100%;text-align: center;color: #2e75b5;font-size: 16px;font-weight:600;position: absolute;top: 40%;left: 50%;-webkit-transform: translate(-50%, -50%);transform: translate(-50%, -50%);}
	.footer-sec li{position: relative;padding-right: 10px;margin-right: 10px; color: #FFF;}
	.footer-sec li:before{content: "|"; position: absolute;right: 0;top: 0;}
	.footer-sec li:last-child:before{content: none;}
	.footer-sec li:last-child{margin-right: 0;padding-right: 0;}
	.footer-sec .socail-sec li:before{content: none;}
	.footer-sec .socail-sec li img{width: 16px;}
	.footer-sec li a, .footer-sec li p, .footer-sec li{color:#FFF; text-decoration:underline}
	.footer-sec p{color:#000;}
</style>
</head>
<body style="margin:0 auto; padding:0px; background: #fff">
<div class="wapper">
	<div class="container">
		<div class="header-sec">
			<div class="logo-sec">
				  <a href="<?php echo '//'.$_SERVER['HTTP_HOST']; ?>"><img src="<?php echo $message->embed($avatar); ?>" alt="Logo" title="Logo" style="height: 30px;margin-top: 5px;"> </a>
			</div>
		</div>
		<p style="margin-bottom: 20px; margin-top:10px;"><?php echo $content; ?></p>
		<p style="margin-bottom: 20px; margin-top:0px; text-align: center;">Have a query? Send us an email at <a href="mailto:team@fynches.com" style="color:#2e75b5; text-decoration:underline;font-weight: 600;">team@fynches.com</a></p>
		<div class="footer-sec">
			<h4 style="margin-bottom: 0px; margin-top:0px; text-align: center;padding: 5px 0; color:#000;">Connect with us on</h4>
			<ul style="display:table;width:auto;list-style-type:none;padding: 0 0 10px;margin: 0 auto;" class="socail-sec">
				<li style="float: left;width:auto;"><a href="https://twitter.com/fynches"><img src="<?php echo $message->embed($twitterlogo); ?>"></a></li>
				<li style="float: left;width:auto;"><a href="https://www.facebook.com/fynchescom"><img src="<?php echo $message->embed($facebooklogo); ?>"></a></li>
				<li style="float: left;width:auto;"><a href="https://www.instagram.com/fynches"><img src="<?php echo $message->embed($instagramlogo); ?>"></a></li>
			</ul> 
			<ul style="display:table;width:auto;list-style-type:none;padding: 0;margin: 0 auto 10px; cursor: pointer;">
				<li style="float: left;width:auto;"><a href="<?php echo url('terms-condition'); ?>">Terms of Use</a></li>
				<li style="float: left;width:auto;"><a href="<?php echo url('privacy-policy'); ?>">Privacy Policy</a></li>
			</ul>
			<p style="margin-bottom: 0px; margin-top:0px; text-align: center;padding: 5px 0;">Copyright 2018 @ Fynches</p>
		</div>
	</div>
</div>
</body>
</html>
