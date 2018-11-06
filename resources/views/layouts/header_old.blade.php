<!-- BEGIN HEADER INNER -->
<div class="page-header-inner">
    <!-- BEGIN LOGO -->
    <div class="page-logo">
        <a ui-sref="dashboard" href="<?php echo '//'.$_SERVER['HTTP_HOST'].'/dashboard'; ?>">
            {{ HTML::image('/assets/pages/img/Fynches_Logo_Teal.png', 'Logo', array('style' => 'height: 30px;','class' => 'logo-default')) }}
            <!--{{ HTML::image('/resources/assets/layouts/layout4/img/logo-light.png', 'Logo', array('class' => 'logo-default')) }}-->
            <!-- <h3>Fynches</h3> -->
        </a>
        <div class="menu-toggler sidebar-toggler">
            <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
        </div>
    </div>
    <!-- END LOGO -->
    <!-- BEGIN RESPONSIVE MENU TOGGLER -->
    <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
    <!-- END RESPONSIVE MENU TOGGLER -->
    <!-- BEGIN PAGE ACTIONS -->
    <!-- DOC: Remove "hide" class to enable the page header actions -->

    <!-- END PAGE ACTIONS -->
    <!-- BEGIN PAGE TOP -->
    <div class="page-top">
        <!-- BEGIN HEADER SEARCH BOX -->
        <!-- DOC: Apply "search-form-expanded" right after the "search-form" class to have half expanded search box -->

        <!-- END HEADER SEARCH BOX -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <li class="separator hide"></li>
                
                <!-- BEGIN USER LOGIN DROPDOWN -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li class="dropdown dropdown-user dropdown-dark">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <span class="username username-hide-on-mobile"> <?php
                            if (Auth::check()) {
                                echo Auth::user()->first_name . " " . Auth::user()->last_name;
                                $authId = Auth::user()->id;
                            }
                            ?> </span>
                        <?php
                        $defaultPath = config('constant.imageNotFound');
                        $profileImage = Auth::user()->profile_image;
                       
                        if ($profileImage && $profileImage != "") {
                            
                            $imgPath = 'storage/adminProfileImages/thumb/' . $profileImage;
                           
                            if (file_exists($imgPath))
                            {
                                $imgPath = $imgPath;
                            } else {
                                $imgPath = $defaultPath;
                            }
                        } else {
                            $imgPath = $defaultPath;
                        }
                        ?>
                        <!-- DOC: Do not remove below empty space(&nbsp;) as its purposely used -->
                        <img alt="profile"  class="img-circle" src="{{ asset($imgPath) }}" /> </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <li>
                            <a href="{{URL::to("/admin/user/" . $authId . "/edit")}}">
                            <i class="icon-user"></i> Edit Profile </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('/admin/changepassword')}}">
                            <i class="icon-lock"></i> Change Password </a>
                        </li>
                       
                        <li>
                        	<a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                         </li>   
                    </ul>
                </li>
                <!-- END USER LOGIN DROPDOWN -->
                <!-- BEGIN QUICK SIDEBAR DROPDOWN -->
                <!-- END QUICK SIDEBAR DROPDOWN -->
            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END PAGE TOP -->
</div>
<!-- END HEADER INNER -->
