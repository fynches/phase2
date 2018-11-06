<?php 
$name = Route::currentRouteName();
?>

<!-- Header Sec -->
    <header class="inner-pg">
        <nav class="navbar navbar-expand-lg navbar-default">
            <div class="container">
                <a class="navbar-brand" href="{{ url ('/') }}">
                    <img src="{{ asset('front/images/Fynches_Logo_Teal.png') }}" alt="">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto">
                        @if($name === "dashboard.index" || $name === "search")
                        <li>
                            <div class="form-group">
                            	<form id="dashboard_search" class="ds-bord" name="dashboard_search" method="post">
	                                <input type="text" class="form-control search expandright" type="search"  id="search_for_an_experience" name="search_for_an_experience" value="" placeholder="Search for an experience">
	                                <button type="submit" class="home_search">
	                                    <i class="fa fa-search" aria-hidden="true"></i>
	                                </button>
	                             </form>   
                            </div>
                        </li>
                        @endif
                        <li class="nav-item dropdown">
                        	<?php 
					        	$users= Auth::guard('site')->user();
								$user_name ="";
								if(count($users) > 0)
								{
									$user_name= $users->first_name.' '.$users->last_name;
								} 
					        	
					        ?>
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		         			 {{$user_name}}
		       				 </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="/dashboard">Dashboard</a>
                                <a class="dropdown-item" href="{{ route('site.logout') }}">Log Out</a>
                                <!-- <a class="dropdown-item" href="#">Something else here</a> -->
                            </div>
                        </li>
                    </ul>
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
