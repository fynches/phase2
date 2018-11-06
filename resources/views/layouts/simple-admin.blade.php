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
        <?php $controller_name = Request::segment(2); ?>
        <title>Save The Moment - Admin</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin Theme #1 for statistics, charts, recent events and reports" name="description" />
        <meta content="" name="author" />
        <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        {{Html::style("http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all")}}
        {{Html::style("/resources/assets/global/plugins/font-awesome/css/font-awesome.min.css")}}
        {{Html::style("/resources/assets/global/plugins/simple-line-icons/simple-line-icons.min.css")}}
        {{Html::style("/resources/assets/global/plugins/bootstrap/css/bootstrap.min.css")}}
        
        {{Html::style("/resources/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css")}}
        {{Html::style("/resources/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css")}}
        {{Html::style("/resources/assets/global/plugins/morris/morris.css")}}
        {{Html::style("/resources/assets/global/plugins/fullcalendar/fullcalendar.min.css")}}
        {{Html::style("/resources/assets/global/plugins/jqvmap/jqvmap/jqvmap.css")}}
        
        {{Html::style("/resources/assets/global/css/components.min.css")}}
        {{Html::style("/resources/assets/global/css/plugins.min.css")}}
        {{Html::style("/resources/assets/layouts/layout/css/layout.min.css")}}
        {{Html::style("/resources/assets/layouts/layout/css/themes/darkblue.min.css")}}
        {{Html::style("/resources/assets/layouts/layout/css/custom.min.css")}}
        {{Html::script("/resources/assets/global/plugins/jquery.min.js")}}
        
        <link rel="shortcut icon" href="favicon.ico" /> </head>
    <!-- END HEAD -->

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
        <div class="page-wrapper">
            
            @include('layouts.header')
            <!-- END HEADER -->
            <!-- BEGIN HEADER & CONTENT DIVIDER -->
            <div class="clearfix"> </div>
            <!-- END HEADER & CONTENT DIVIDER -->
            <!-- BEGIN CONTAINER -->
            <div class="page-container">
                <!-- BEGIN SIDEBAR -->
                @include('layouts.navigation')
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                        <!-- BEGIN PAGE BAR -->
                        <div class="page-bar">
                            <ul class="page-breadcrumb">
                                <li>
                                    <a href="{{ url('/admin/dashboard')}}">Home</a>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <li>
                                    <span><?php echo $title_for_layout; ?></span>
                                </li>
                            </ul>
                        </div>
                        <!-- END PAGE BAR -->
                        <h1 class="page-title"> <?php
                                        echo $title_for_layout;
                                        $controller = Request::segment(2);
                                        $action = Request::segment(3);
                                        if ($action != 'create' && $controller != 'changepassword' && $controller != 'dashboard') {
                                            echo '<a class="add-new-btn-listing btn btn-default pull-right" href="' . url('/admin/' . $controller_name . '/create') . '">Add New</a> ';
                                        }
                                        ?></small>
                        </h1>
                                        @yield('content')
                                    </div>
                    <!-- END CONTENT BODY -->
                </div>
                </div>
            <!-- END CONTAINER -->
            <!-- BEGIN FOOTER -->
            <div class="page-footer">
                <div class="page-footer-inner"> 2016 &copy; Metronic Theme By
                    <a target="_blank" href="http://keenthemes.com">Keenthemes</a> &nbsp;|&nbsp;
                    <a href="http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes" title="Purchase Metronic just for 27$ and get lifetime updates for free" target="_blank">Purchase Metronic!</a>
                </div>
                <div class="scroll-to-top">
                    <i class="icon-arrow-up"></i>
                </div>
            </div>
            <!-- END FOOTER -->
        </div>
    <!-- BEGIN CORE PLUGINS -->
        
        {{Html::script("/resources/assets/global/plugins/bootstrap/js/bootstrap.min.js")}}
        {{Html::script("/resources/assets/global/scripts/app.min.js")}}
        <!--{{Html::script("/resources/assets/pages/scripts/dashboard.min.js")}}-->
        {{Html::script("/resources/assets/layouts/layout/scripts/layout.min.js")}}
        {{Html::script("/resources/assets/layouts/layout/scripts/demo.min.js")}}
        {{Html::script("/resources/assets/layouts/global/scripts/quick-nav.min.js")}}
        <!--<script src="../assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>-->
        
        <script>
            $(document).ready(function()
            {
                $('#clickmewow').click(function()
                {
                    $('#radio1003').attr('checked', 'checked');
                });
            })
        </script>
    </body>

</html>