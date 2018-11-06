<!-- Header Sec -->
<header class="inner-pg event">
	<div class="header-top-sec">
		<nav class="navbar navbar-expand-lg navbar-light">
			<div class="container">
			  <a class="navbar-brand" href="{{ url ('/') }}">
			  	<img src="{{ asset('front/images/Fynches_Logo_Teal.png') }}" alt="">
			  </a>
			  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			    <span class="navbar-toggler-icon"></span>
			  </button>
				
			  <div class="collapse navbar-collapse" id="navbarSupportedContent">
			  	<?php 
		        	$users= Auth::guard('site')->user();
					$user_name ="";
					if(count($users) > 0)
					{
						$user_name= $users->first_name.' '.$users->last_name;
					} 
		        	
					$controller = Request::segment(1);
					
		        ?>
		        
			    <ul class="navbar-nav ml-auto">
		            <li class="nav-item dropdown">
				        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				          {{ $user_name }}
				        </a>
				        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
						  <a class="dropdown-item" href="/dashboard">Dashboard</a>
				          <a class="dropdown-item" href="{{ route('site.logout') }}">Log Out</a>
				        </div>
				      </li>
		          </ul>
					
					
				   <ul class="navbar-nav mr-auto btm-menu">
				   	 <li class="nav-item <?php if($controller=="create-event" || $controller=="create-experience"){echo 'active';}?>">
				   	 		@if($current_event_id=="0")
				        		<a class="nav-link ds-disable" href="javascript:void(0)">Event editor</a>
				        	@else
				        		<a class="nav-link" href="{{'/create-experience/'.$current_event_id}}">Event editor</a>
				        	@endif	
				      </li>
				   
				      <?php
				      		$stripe_client_id="";
							$stripe_url="";
							if(count($global_data) > 0)
							{
								$stripe_client_id= $global_data->stripe_client_id;
								$stripe_url= "https://connect.stripe.com/oauth/authorize?response_type=code&client_id=".$stripe_client_id."&scope=read_write";
							}
							
							// echo "@@@@@@@@@@@@@@".$current_event_id;
							// echo '<br/>';
							// echo "@@@@@@@@@@@@@@".count($user_last_event_stripe_id);
						
				      ?>
				      	  
					      <li class="nav-item">
					      	@if($current_event_id=="0" && $user_last_event_stripe_id=="0")
					        	<a class="nav-link ds-disable" href="javascript:void(0)">Banking information</a>
					        @elseif($user_last_event_stripe_id=="0")
					        	<a class="nav-link" href="{{$stripe_url}}">Banking information</a>
					        @else
					        	 <a class="nav-link" href="javascript:void(0)"  data-toggle="modal" data-target="#banking-information">Banking information</a> 
					        @endif	
					      </li>
					     
					      <li class="nav-item <?php if($controller=="funding-report"){echo 'active';}?>" >
					      	@if($current_event_id!="0" && $event_status=="1")
				        		<a class="nav-link" href="{{$fundingRepotUrl}}">Funding report</a>
				        	@else
				        		<a class="nav-link ds-disable" href="javascript:void(0)">Funding report</a>
				        	@endif	
				      	  </li>
					      
					      <li class="nav-item <?php if($controller=="share-event"){echo 'active';}?>">
					      		@if($current_event_id!="0" && $event_status=="1")
				        			<a class="nav-link" href="{{'/share-event/'.$current_event_id}}">Share event</a>
				        		@else
				        			<a class="nav-link ds-disable" href="javascript:void(0)">Share event</a>
				        		@endif	
				     	  </li>
				      	  
				      	  <li class="nav-item">
				      	  	<?php //echo "@@@@@@@@@@".$event_status;?>
				      	  	@if($current_event_id!="0" && $event_status=="1" &&  count($my_experience) > 0)
				        		<a class="nav-link" href="{{$preview_url}}">Preview</a>
				        	@else
				        		<a class="nav-link ds-disable" href="javascript:void(0)">Preview</a>
				        	@endif	
				      	 </li>
				      	 
				      	 <input type="hidden" class="user_stripe_account_id" value="{{$user_last_event_stripe_id}}">
				      	 <input type="hidden" class="connect_stripe_url" value="{{$stripe_url ?? ''}}">
				      	 <input type="hidden" class="banking-information" data-toggle="modal" data-target="#banking-information">
				      	 <!-- <a class="nav-link" href="javascript:void(0)"  data-toggle="modal" data-target="#banking-information">Banking information</a>
				      	 <a class="nav-link" href="javascript:void(0)"  data-toggle="modal" data-target="#banking-information">Banking information</a> -->
				      	 
				   </ul>
			  </div>
			</div>
		</nav>
	</div>
</header>

@include('site.modal.banking-information')