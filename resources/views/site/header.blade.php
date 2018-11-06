<!-- Header Sec -->
<header>
	<nav class="navbar navbar-expand-lg navbar-default">
      <div class="container">
        <a class="navbar-brand" href="{{ url ('/') }}">
        	<img src="{{ asset('front/images/Fynches_Logo_Teal.png') }}" alt="" title="" height="30" width="150">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
          	
          	<li class="nav-item">
              <a class="nav-link" href="{{ url('aboutUs') }}">ABOUT</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{env('SITE_URL')}}/blog">BLOG</a>
            </li>
            
            @if(Auth::guard('site')->check())             
             <li class="nav-item dashboard-item">
                <a class="nav-link" href="{{ url('dashboard') }}">Dashboard</a>
              </li>              
           @endif
           
            <li class="nav-item">
            	<?php $users= Auth::guard('site')->user();?>
            	@if(Auth::guard('site')->check())
             		<a class="nav-link" href="{{ route('site.logout') }}">LOGOUT</a>	
            	@else
              		<a class="nav-link" href="javascript:void(0)"  data-toggle="modal" data-target="#login">SIGN IN</a>
              	@endif	
            </li>
            
             <li class="nav-item">
                <a class="nav-link" href="{{ url('event') }}">GET STARTED</a>
              </li>
          </ul>
           
         <form id="dashboard_search" name="dashboard_search" method="post">
              <a class="src-img" href="javascript:void(0);"><i class="fa fa-search home_search" aria-hidden="true"></i></a>
              <input class="search expandright" type="search"  id="search_for_an_experience" name="search_for_an_experience" value="" placeholder="Search for an experience">	
         </form>	  
          		
        </div>
      </div>
    </nav>
</header>

<script type="text/javascript">

$(document).ready(function() {
    $("form[name='dashboard_search']").submit(function(){
    	return false;
    });
});
$(document).on('keydown','.expandright',function(e){
	
	var key = e.which;
	
	if(key === 13)
	{
		var search_exp = $('#search_for_an_experience').val();
		
		if(search_exp!="")
		{
			var redirect_page= window.base_url+'/find-perfect-experience/'+search_exp+'/chicago';
			window.location.href= redirect_page;	
		}
	}
});

$(document).on('click', '.home_search', function(e) {
	var search_exp = $('#search_for_an_experience').val();
	
	if(search_exp!="")
	{
		var redirect_page= window.base_url+'/find-perfect-experience/'+search_exp+'/chicago';
		window.location.href= redirect_page;	
	}
});
</script>

<!-- End -->

@include('site.modal.login')
@include('site.modal.forgot-password')

{{Html::script("/front/common/user/site_login.js")}}
