

<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.7
Version: 4.7.5
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <title>Fynches| User Login</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Fynches" name="description" />
        <meta content="" name="author" />
        <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        {{Html::style("http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all")}}
        {{Html::style("/assets/global/plugins/font-awesome/css/font-awesome.min.css")}}
        {{Html::style("/assets/global/plugins/simple-line-icons/simple-line-icons.min.css")}}
        {{Html::style("/assets/global/plugins/bootstrap/css/bootstrap.min.css")}}
        {{Html::style("/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css")}}
        
        {{Html::style("/assets/global/css/components.min.css")}}
        {{Html::style("/assets/global/css/plugins.min.css")}}
        {{Html::style("/assets/pages/css/login-2.min.css")}}
        
        
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        
        <link rel="shortcut icon" href="favicon.ico" /> </head>
    <!-- END HEAD -->

    <body class="login">
        <!-- BEGIN LOGO -->
        <div class="logo">
            <a href="{{ url('/') }}">
                <!-- {{ HTML::image('/assets/pages/img/logo-big-white.png', 'Logo', array('style' => 'height: 17px;')) }} -->
                <h3>Fynches</h3>
            </a>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN LOGIN -->
        <div class="content">
            <!-- BEGIN LOGIN FORM -->
            @include('errors.common_errors')
            @yield('content')
                        
<!--             END LOGIN FORM 
             BEGIN FORGOT PASSWORD FORM -->
            
            <!-- END FORGOT PASSWORD FORM -->
            <!-- BEGIN REGISTRATION FORM -->
            
            <!-- END REGISTRATION FORM -->
        </div>
        <div class="copyright hide"> <?php echo date("Y"); ?> © Fynches. Admin Dashboard Template. </div>
        <!-- END LOGIN -->
        <!--[if lt IE 9]>
        <script src="..//assets/global/plugins/respond.min.js"></script>
        <script src="..//assets/global/plugins/excanvas.min.js"></script> 
        <script src="..//assets/global/plugins/ie8.fix.min.js"></script> 
        <![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        {{Html::script("/assets/global/plugins/jquery.min.js")}}
        {{Html::script("/assets/global/plugins/bootstrap/js/bootstrap.min.js")}}
        {{Html::script("/assets/global/plugins/js.cookie.min.js")}}
        {{Html::script("/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js")}}
        
        {{Html::script("/assets/global/plugins/jquery.blockui.min.js")}}
        {{Html::script("/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js")}}
        
        {{Html::script("/assets/global/plugins/jquery-validation/js/jquery.validate.min.js")}}
        {{Html::script("/assets/global/plugins/jquery-validation/js/additional-methods.min.js")}}
        {{Html::script("/assets/global/plugins/select2/js/select2.full.min.js")}}
        {{Html::script("/assets/global/scripts/app.min.js")}}
        {{Html::script("/assets/pages/scripts/login.min.js")}}
       
    </body>
</html>
