<div class="page-sidebar navbar-collapse collapse">
    <?php
    $controller = Request::segment(2);
    $method = Request::segment(3);
    $authId = Auth::user()->user_type;
    ?>
    <!-- BEGIN SIDEBAR MENU -->
    <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
    <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
    <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
    <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
    <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
    <ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">

        <li <?php echo ($controller == 'dashboard' ? 'class="nav-item start active"' : 'class="nav-item start"'); ?>>
            <a href="{{ url('/admin/dashboard')}}">
                <i class="icon-home"></i>
                <span class="title">Dashboard</span>
            </a>
        </li>
        
        <li <?php echo ($controller == 'betaSignup' || $controller == 'user'  ? 'class="nav-item start active open"' : 'class="nav-item"'); ?>>
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="icon-user"></i>
                <span class="title">Manage Users</span>
                <span class="arrow"></span>
            </a>

            <ul class="sub-menu">

                <li <?php echo ($controller == 'betaSignup' ? 'class="nav-item start active open"' : 'class="nav-item start"'); ?>>
                    <a href="{{ url('/admin/betaSignup')}}">
                        <i class="icon-user"></i>
                        <span class="title">Beta Signup User</span>
                    </a>
                </li>

                @if($authId == "1")
                <li <?php echo ($controller == 'user' && $method == 'admin_index' || $method=='admin_create' || $method=='edit_admin' || $method=='show_admin_info' ? 'class="nav-item start active open"' : 'class="nav-item start"'); ?>>
                    <a href="{{ url('/admin/user/admin_index')}}">
                        <i class="icon-user"></i>
                        <span class="title"> Admin User</span>
                    </a>
                </li> 
                @endif
                
                <li <?php echo ($controller == 'user' && ($method != 'admin_index' && $method!='admin_create' && $method!='edit_admin' && $method!='show_admin_info')  ? 'class="nav-item start active open"' : 'class="nav-item start"'); ?>>
                    <a href="{{ url('/admin/user')}}">
                        <i class="icon-user"></i>
                        <span class="title"> User</span>
                    </a>
                </li>   
            </ul>
        </li>

        
        <li <?php echo ($controller == 'event' || $controller == 'testimonial' || $controller == 'events'  || $controller == 'experience' || $controller == 'tag' ? 'class="nav-item start active open"' : 'class="nav-item"'); ?>>
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="icon-diamond"></i>
                <span class="title">Manage Modules</span>
                <span class="arrow"></span>
            </a>

            <ul class="sub-menu">
            	
            	<li <?php echo ($controller == 'event' ? 'class="nav-item start active open"' : 'class="nav-item start"'); ?>>
		            <a href="{{ url('/admin/event')}}" class="nav-link nav-toggle">
		                <i class="icon-tag"></i>
		                <span class="title">Events</span>
		            </a>
		        </li>
		        
		        <!-- <li <?php echo ($controller == 'experience' ? 'class="nav-item start active open"' : 'class="nav-item start"'); ?>>
		            <a href="{{ url('/admin/experience')}}" class="nav-link nav-toggle">
		                <i class="icon-tag"></i>
		                <span class="title">Experiences</span>
		            </a>
		        </li> -->

            <!-- <li <?php echo ($controller == 'tag' ? 'class="nav-item start active open"' : 'class="nav-item start"'); ?>>
		            <a href="{{ url('/admin/tag')}}" class="nav-link nav-toggle">
		                <i class="icon-tag"></i>
		                <span class="title">Experience Keywords</span>
		            </a>
		        </li>-->

                <li <?php echo ($controller == 'testimonial' ? 'class="nav-item start active open"' : 'class="nav-item start"'); ?>>
                    <a href="{{ url('/admin/testimonial')}}" class="nav-link nav-toggle">
                        <i class="icon-tag"></i>
                        <span class="title"> Testimonial </span>
                    </a>
                </li>
			</ul>
        </li>
        
        <li <?php echo ($controller == 'cms' || $controller == 'emailtemplates' || $controller == 'staticblock' || $controller == 'globalsetting' || $controller == 'country' || $controller == 'state' || $controller == 'payment'  ? 'class="nav-item start active open"' : 'class="nav-item"'); ?>>
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="icon-settings"></i>
                <span class="title">Configuration</span>
                <span class="arrow"></span>
            </a>

            <ul class="sub-menu">

                <li <?php echo ($controller == 'cms' ? 'class="nav-item start active open"' : 'class="nav-item start"'); ?>>
		            <a href="{{ url('/admin/cms')}}" class="nav-link nav-toggle">
		                <i class="icon-tag"></i>
		                <span class="title">Static Content Pages</span>
		            </a>
		        </li>
		        
		         <li <?php echo ($controller == 'emailtemplates' ? 'class="nav-item start active open"' : 'class="nav-item start"'); ?>>
		            <a href="{{ url('/admin/emailtemplates')}}" class="nav-link nav-toggle">
		                <i class="icon-tag"></i>
		                <span class="title">Email Templates</span>
		            </a>
		        </li>

				<li <?php echo ($controller == 'staticblock' ? 'class="nav-item start active open"' : 'class="nav-item start"'); ?>>
		            <a href="{{ url('/admin/staticblock')}}" class="nav-link nav-toggle">
		                <i class="icon-tag"></i>
		                <span class="title">Static Block</span>
		            </a>
		        </li>
		        
		        <li <?php echo ($controller == 'country' ? 'class="nav-item start active open"' : 'class="nav-item start"'); ?>>
		            <a href="{{ url('/admin/country')}}" class="nav-link nav-toggle">
		                <i class="icon-tag"></i>
		                <span class="title">Country</span>
		            </a>
		        </li>
		        
		        <li <?php echo ($controller == 'state' ? 'class="nav-item start active open"' : 'class="nav-item start"'); ?>>
		            <a href="{{ url('/admin/state')}}" class="nav-link nav-toggle">
		                <i class="icon-tag"></i>
		                <span class="title">State</span>
		            </a>
		        </li>
		        
		        <li <?php echo ($controller == 'globalsetting' ? 'class="nav-item start active open"' : 'class="nav-item start"'); ?>>
		            <a href="{{ url('/admin/globalsetting')}}" class="nav-link nav-toggle">
		                <i class="icon-tag"></i>
		                <span class="title">Global Setting</span>
		            </a>
		        </li>
		       
		       
		        <li <?php echo ($controller == 'payment' ? 'class="nav-item start active open"' : 'class="nav-item start"'); ?>>
		            <a href="{{ url('/admin/payment')}}" class="nav-link nav-toggle">
		                <i class="icon-tag"></i>
		                <span class="title">Funding Report</span>
		            </a>
		        </li>
               

            </ul>
        </li>
        
    </ul>
    <!-- END SIDEBAR MENU -->
</div>