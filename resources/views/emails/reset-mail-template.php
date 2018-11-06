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
	.btn.green:not(.btn-outline){
		color: #FFF;
	    background-color: #32c5d2;
	    border-color: #32c5d2;
	}
	.custom_color {
    display: inline-block;
    margin-bottom: 0;
    font-weight: 400;
    text-align: center;
    vertical-align: middle;
    touch-action: manipulation;
    cursor: pointer;
    border: 1px solid transparent;
    white-space: nowrap;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857;
    border-radius: 4px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
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
		<?php  if(!empty($toEmail)){ ?>
		<div class="badge-sec">
				<div class="badge-title">
					<span>Click here for change password</span>
					<ul style="display:table;width:auto;list-style-type:none;padding: 0 0 10px;margin: 0 auto;" class="socail-sec">
						<a type="button" href="<?php echo $link;?>" class="custom_color">Reset Password</a>
					</ul> 
				</div>
		</div>
		<?php } ?>
		<!-- <p style="margin-bottom: 0px; margin-top:10px; text-align: center;">To check book history, badges won, leaderboard, reviews written and more, visit the <br> <a href="#" style="color:#2e75b5; text-decoration:underline;font-weight: 600;">My Story</a> page on the app.</p> -->
		<p style="margin-bottom: 20px; margin-top:0px; text-align: center;">Have a query? Send us an email at <a href="#" style="color:#2e75b5; text-decoration:underline;font-weight: 600;">team@fynches.com</a></p>
		<div class="footer-sec">
			<h4 style="margin-bottom: 0px; margin-top:0px; text-align: center;padding: 5px 0; color:#000;">Connect with us on</h4>
			<ul style="display:table;width:auto;list-style-type:none;padding: 0 0 10px;margin: 0 auto;" class="socail-sec">
				<li style="float: left;width:auto;"><a href="#"><img src="<?php echo $message->embed($twitterlogo); ?>"></a></li>
				<li style="float: left;width:auto;"><a href="#"><img src="<?php echo $message->embed($facebooklogo); ?>"></a></li>
				<li style="float: left;width:auto;"><a href="#"><img src="<?php echo $message->embed($instagramlogo); ?>"></a></li>
			</ul> 
			<ul style="display:table;width:auto;list-style-type:none;padding: 0;margin: 0 auto 10px;">
				<li style="float: left;width:auto;"><a href="www.fynches.com">Terms of Use</a></li>
				<li style="float: left;width:auto;"><a href="www.fynches.com">Privacy Policy</a></li>
			</ul>
			<p style="margin-bottom: 0px; margin-top:0px; text-align: center;padding: 5px 0;">Copyright 2018 @ Fynches</p>
		</div>
	</div>
</div>
</body>
</html>
