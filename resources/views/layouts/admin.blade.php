<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js" data-ng-app="MetronicApp"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js" data-ng-app="MetronicApp"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <?php $controller_name = Request::segment(2); ?>
        <title>Fynches - Admin</title>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        {{Html::style("http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all")}}
        {{Html::style("/assets/global/plugins/font-awesome/css/font-awesome.min.css")}}
        {{Html::style("/assets/global/plugins/simple-line-icons/simple-line-icons.min.css")}}
        {{Html::style("/assets/global/plugins/bootstrap/css/bootstrap.min.css")}}
        
        <!-- Multiple image upload css start -->
        
        <!-- Generic page styles -->
        {{Html::style("/assets/global/fileupload/css/style.css")}}
		<!-- blueimp Gallery styles -->
		<link rel="stylesheet" href="https://blueimp.github.io/Gallery/css/blueimp-gallery.min.css">
		<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
		{{Html::style("/assets/global/fileupload/css/jquery.fileupload.css")}}
		{{Html::style("/assets/global/fileupload/css/jquery.fileupload-ui.css")}}
		<!-- CSS adjustments for browsers with JavaScript disabled -->
		
		<noscript>{{Html::style("/assets/global/fileupload/css/jquery.fileupload-noscript.css")}}</noscript>
		<noscript>{{Html::style("/assets/global/fileupload/css/jquery.fileupload-ui-noscript.css")}}</noscript>
        
        <!-- Multiple image upload css end -->

        {{Html::style("/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css")}}
        {{Html::style("/assets/global/plugins/morris/morris.css")}}

        <!--{{Html::style("/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css")}}-->
        <!--{{Html::style("/assets/global/plugins/datatables/datatables.min.css")}}-->

        {{Html::style("/assets/global/css/components-rounded.min.css")}}
        {{Html::style("/assets/global/css/plugins.min.css")}}
        {{Html::style("/assets/layouts/layout4/css/layout.min.css")}}
        {{Html::style("/assets/layouts/layout4/css/themes/light.min.css")}}
        {{Html::style("/assets/layouts/layout4/css/custom.min.css")}}
        {{Html::style("/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css")}}
        {{Html::style("/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css")}}
        {{Html::style("/assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css")}}
        {{Html::style("/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css")}}
        {{Html::style("/assets/global/plugins/fancybox/source/jquery.fancybox.css")}}
        
        <!--/var/www/html/savethemoment/assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css-->
        <!--/var/www/html/savethemoment/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css-->
        <!--/var/www/html/savethemoment/assets/global/plugins/bootstrap/css/bootstrap.min.css-->
        {{Html::style("/assets/common/common.css")}}

        {{Html::script("/assets/global/plugins/jquery.min.js")}}
        
        <!-- Multiple image upload js start -->
        
        
        <script id="template-upload" type="text/x-tmpl">
		{% for (var i=0, file; file=o.files[i]; i++) { %}
		    <tr class="template-upload fade">
		        <td>
		            <span class="preview"></span>
		        </td>
		        <td>
		            <p class="name">{%=file.name%}</p>
		            <strong class="error text-danger"></strong>
		        </td>
		        <td>
		            <p class="size">Processing...</p>
		            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
		        </td>
		        <td>
		            {% if (!i && !o.options.autoUpload) { %}
		                <button class="btn btn-primary start" disabled>
		                    <i class="glyphicon glyphicon-upload"></i>
		                    <span>Start</span>
		                </button>
		            {% } %}
		            {% if (!i) { %}
		                <button class="btn btn-warning cancel">
		                    <i class="glyphicon glyphicon-ban-circle"></i>
		                    <span>Cancel</span>
		                </button>
		            {% } %}
		        </td>
		    </tr>
		{% } %}
		</script>
		<!-- The template to display files available for download -->
		<script id="template-download" type="text/x-tmpl">
			
		{% for (var i=0, file; file=o.files[i]; i++) { %}
		    <tr class="template-download fade">
		        <td>
		            <span class="preview">
		                {% if (file.thumbnailUrl) { %}
		                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
		                    <input type="hidden" value="{%=file.name%}" id="event_image" name="event_image[]">
		                {% } %}
		            </span>
		        </td>
		        <td>
		            <p class="name">
		                {% if (file.url) { %}
		                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
		                {% } else { %}
		                    <span>{%=file.name%}</span>
		                {% } %}
		            </p>
		            {% if (file.error) { %}
		                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
		            {% } %}
		        </td>
		        <td>
		            <span class="size">{%=o.formatFileSize(file.size)%}</span>
		        </td>
		        <td>
		            {% if (file.deleteUrl) { %}
		                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
		                    <i class="glyphicon glyphicon-trash"></i>
		                    <span>Delete</span>
		                </button>
		                <input type="checkbox" name="delete" value="1" class="toggle">
		            {% } else { %}
		                <button class="btn btn-warning cancel">
		                    <i class="glyphicon glyphicon-ban-circle"></i>
		                    <span>Cancel</span>
		                </button>
		            {% } %}
		        </td>
		    </tr>
		{% } %}
		</script>
        
        <!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
		{{Html::script("/assets/global/fileupload/js/jquery.ui.widget.js")}}
		<!-- The Templates plugin is included to render the upload/download listings -->
		<script src="https://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
		<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
		<script src="https://blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
		<!-- The Canvas to Blob plugin is included for image resizing functionality -->
		<script src="https://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
		<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
		
		<!-- blueimp Gallery script -->
		<script src="https://blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
		<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
		
		{{Html::script("/assets/global/fileupload/js/jquery.iframe-transport.js")}}
		<!-- The basic File Upload plugin -->
		{{Html::script("/assets/global/fileupload/js/jquery.fileupload.js")}}
		<!-- The File Upload processing plugin -->
		{{Html::script("/assets/global/fileupload/js/jquery.fileupload-process.js")}}
		<!-- The File Upload image preview & resize plugin -->
		{{Html::script("/assets/global/fileupload/js/jquery.fileupload-image.js")}}
		<!-- The File Upload audio preview plugin -->
		{{Html::script("/assets/global/fileupload/js/jquery.fileupload-audio.js")}}
		<!-- The File Upload video preview plugin -->
		{{Html::script("/assets/global/fileupload/js/jquery.fileupload-video.js")}}
		<!-- The File Upload validation plugin -->
		{{Html::script("/assets/global/fileupload/js/jquery.fileupload-validate.js")}}
		<!-- The File Upload user interface plugin -->
		{{Html::script("/assets/global/fileupload/js/jquery.fileupload-ui.js")}}
		<!-- The main application script -->
		
		
		<!-- Multiple image upload js end -->
        
        {{Html::script("/assets/global/plugins/jquery-validation/js/jquery.validate.js")}}
        
       <!--  <script src="//cdn.ckeditor.com/4.4.3/basic/ckeditor.js"></script> -->
        <!-- <script src="//cdn.ckeditor.com/4.4.3/basic/adapters/jquery.js"></script> -->
        {{Html::script("/assets/global/plugins/jquery-validation/js/additional-methods.js")}}
        {{Html::script("/assets/global/plugins/morris/morris.min.js")}}
        {{Html::script("/assets/global/plugins/morris/raphael-min.js")}}
        {{Html::script("/assets/global/plugins/jquery.sparkline.min.js")}}

        {{Html::script("/assets/global/plugins/datatables/datatables.all.min.js")}}
        {{Html::script("/assets/global/scripts/datatable.js")}}
        {{Html::script("/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js")}}
        {{Html::script("/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js")}}
        {{Html::script("/assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js")}}
        {{Html::script("/assets/common/general.js")}} 
        <!--/var/www/html/savethemoment/assets/common/bootstrap-colorpicker.js-->
<!--        /var/www/html/savethemoment/assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css-->
        <script>
            window.base_url = '<?php echo url('/'); ?>';
            /* data tables */
            var oTable;
            function  delete_record(id, slider = null) {
                var controller = '<?php echo $controller_name ?>';
                if (slider != null) {
                    controller = "sliders";
                }
                var token = '<?php echo csrf_token() ?>';
                var pageurl = '<?php echo url('/admin') ?>/' + controller + '/' + id;
                var confirm_flag = confirm("Are you sure you want to delete # " + id + "?");
                if (confirm_flag === true) {
                    $.ajax({
                        url: pageurl,
                        method: 'DELETE',
                        data: {'_token': token},
                        success: function (result) {
                            if (slider != null) {
                                jQuery("#" + slider).fadeOut('slow', function () {
                                    jQuery("#" + slider).remove();
                                });
                            } else {
                                if (result == 1) {
                                    $("#row_" + id).fadeOut('slow', function () {
                                        oTable.ajax.reload();
                                    });
                                }
                            }
                        }
                    });
                }
            }
        </script>
    </head>

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-sidebar-closed-hide-logo">

        <div class="page-header navbar navbar-fixed-top ng-scope">
            @include('layouts.header')
        </div>
        <div class="clearfix"> </div>
        <div class="page-container">
            <div class="page-sidebar-wrapper"> 
                @include('layouts.navigation')
            </div>
            <div class="page-content-wrapper">
                <div class="page-content">
                    <div class="page-title">
                        <h1><span>{{$title_for_layout}}</span>
                            <?php
//                            $controller = Request::segment(2);
//                            $action = Request::segment(3);
                            ?>
                        </h1>
                    </div>
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="dashboard">Home</a>
                            <i class="fa fa-circle"></i>
                        </li>
                        <li class="active"> {{$title_for_layout}} </li>
                    </ul>
                    @include('layouts.notifications')
                    @include('errors.common_errors')
                    <div class="fade-in-up"> 
                        @yield('content')
                    </div>
                </div>
            </div>
            <a href="javascript:;" class="page-quick-sidebar-toggler">
                <i class="icon-login"></i>
            </a>
        </div>
        <!--<div class="page-footer-inner"> <?php echo date("Y"); ?> &copy; Metronic by keenthemes. </div>-->
        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
        {{Html::script("/assets/global/plugins/jquery-migrate.min.js")}}
        {{Html::script("/assets/global/plugins/ckeditor/ckeditor.js")}}
        {{Html::script("/assets/global/plugins/ckeditor/config.js")}}
        {{Html::script("/assets/global/plugins/bootstrap/js/bootstrap.min.js")}}
        {{Html::script("/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js")}}
        {{Html::script("/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js")}}
        {{Html::script("/assets/global/plugins/jquery.blockui.min.js")}}
        {{Html::script("/assets/global/plugins/js.cookie.min.js")}}
        {{Html::script("/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js")}}
        {{Html::script("/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js")}}
        {{Html::script("/assets/global/plugins/fancybox/source/jquery.fancybox.js")}}
        
        {{Html::script("/assets/global/scripts/app.min.js")}}
        <!--{{Html::script("/assets/pages/scripts/dashboard.min.js")}}-->
        {{Html::script("/assets/layouts/layout4/scripts/layout.min.js")}}
        {{Html::script("/assets/layouts/global/scripts/quick-sidebar.min.js")}}  
        {{Html::script("/assets/layouts/global/scripts/quick-nav.min.js")}}
        {{Html::script("/assets/layouts/layout4/scripts/demo.min.js")}}

    </body>
    <!-- END BODY -->
</html>
