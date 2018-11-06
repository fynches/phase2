<!-- Header Sec -->
<header class="inner-pg">
	<nav class="navbar navbar-expand-lg navbar-default">
      <div class="container">
        <a class="navbar-brand" href="{{ url ('/') }}">
          <img src="{{ asset('front/images/Fynches_Logo_Teal.png') }}" width="150" height="30" alt="">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
        	<?php 
        	$users= Auth::guard('site')->user();
					$user_name ="";
					if(count($users) > 0)
					{
						$user_name= $users->first_name.' '.$users->last_name;
					} 
        	
        	?>
          <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
		        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		          {{ $user_name }}
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
$(document).ready(function () {
	$('.join-party-sec').remove();
});
</script>
