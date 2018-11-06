@extends('layouts.app_front')
@section('header')
  @include('site.dashboard_header')
@stop
@section('pagetitle', 'Event')
@section('content')

<section class="form-sec process-sec blue-bg">
	<div class="container">
		<h2>Creating your event</h2>
			
				<div class="row" id="round_step">
					<div class="col-md-12">
						<ul>
							<li class="active">
								<div class="round-box"></div>
								<p>Age of recipient</p>
							</li>
							<li class="">
								<div class="round-box"></div>
								<p>Location</p>
							</li>
							<li class="">
								<div class="round-box"></div>
								<p>Favourite Activites</p>
							</li>
							<li class="">
								<div class="round-box"></div>
								<p>Date</p>
							</li>
						</ul>
					</div>
				</div>
				
				
		        <div class="row" id="event_section_one">
	        	  <div class="col-md-12">
	        	  	<div class="inner-process">
	        	  		<h2 class="title">Age of Recipient </h2>
	        	  		<form id="event_step_one_form">
							<div class="row">
								<div class="col-sm-7">
									<div class="form-group">
					                  <input type="text" name="age_range" maxlength="3" id="age_range" class="form-control number" placeholder="Enter age in years">
					                </div>
								</div>
								<div class="col-sm-5">
									<input type="button" name="next" value="Next" id="first_next">
								</div>
							</div>
						</form>
						<p class="cst-title">Your child’s age will not be displayed on your page. We use it to show you suggestions for age-appropriate experiences. </p>
	        	  	</div>
	              </div>  
		        </div>
		        
		        <!-- section two start -->
		     	<div class="row" id="event_section_two" style="display: none;">
	        	  <div class="col-md-12">
	        	  	<div class="inner-process">
	        	  		<h2 class="title">Location </h2>
	        	  		<form id="event_step_two_form">
							<div class="row">
								<div class="col-sm-7">
									<div class="form-group">
					                  <input type="text" class="form-control number" name="zipcode" id="zipcode" value="" placeholder="Enter Zip">
					                </div>
								</div>
								<div class="col-sm-5">
									<input type="button" name="next" value="Next" id="second_next">
								</div>
							</div>
						</form>
						<p class="cst-title">We won't display this information, just use it to give you suggestion for expirences in your area.</p>
	        	  	</div>
	              </div>  
		        </div>  
		        <!-- section two end -->
	        
		        
	        
		        <!-- Join the Party Sec -->
		    	<div class="row" id="event_section_three" style="display: none;">
	        	  <div class="col-md-12">
	        	  	<div class="inner-process img-box">
	        	  		<h2 class="title">Favorite activites </h2>
	        	  		<p>We 'll use this information to suggest expirences your child will love</p>
	        	  		<form id="event_step_three_form" class="">
		        	  		<ul>
		        	  			@if(count($tags) > 0)
		        	  				@foreach($tags as $key=>$val)
		        	  				<?php
		        	  					$defaultPath = config('constant.imageNotFound');
                    
										$tag_image = $val->image;
							            
					                    if ($tag_image && $tag_image != "") {
					                        
					                       $imgPath = 'storage/tagImages/' . $tag_image;
					                       
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
			        	  			<li id="{{$val->id}}">
			        	  				<label class="bg-box">
										  <input type="checkbox" id="tag_checkbox" class="custom_tags" name="tag_id[]" value="{{$val->id}}">
										  <img src="{{$imgPath}}" alt="" title="">
										  <p>{{$val->tag_name}}</p>
										  <span class="checkmark"></span>
										</label>
			        	  			</li>
		        	  				@endforeach
		        	  			@endif	
		        	  			<li onClick="showHideDiv('divMsg')">
		        	  				<p>Other</p>
		        	  			</li>
		
		        	  		</ul>
	        	  		
	        	  		
	        	  			<div id="divMsg">
								<div class="row"> 
									<div class="col-sm-9 other_custom_tag">
						                <div class="form-group">
						                  <input type="text" class="form-control" id="other_tag" name="other_tag" placeholder="Comma separated keywords">
						                </div>
									</div>
									<div class="col-sm-3">
										<input type="button" name="next" value="Next" class="third_next">
									</div>
								</div>
							</div>
						</form>	
	        	  	</div>
	              </div>  
		        </div>
				<!-- End -->
				
				<!-- Join the Party Sec -->
				<div class="row" id="event_section_four" style="display: none;">
	        	  <div class="col-md-12">
	        	  	<div class="inner-process">
	        	  		<h2 class="title">Date </h2>
	        	  		{!! Form::open(array('url'=>'/store-event', 'class'=>'form-horizontal','method'=>'POST','id'=>'event_step_four_form','files'=>true)) !!}
	        	  			<div class="row">
								<div class="col-sm-7">
					                <div class="input-group date form-group event_end_date">
					                    <input type="text" class="form-control" id="event_end_date" name="event_end_date" autocomplete="off" placeholder="Campaign End Date">
					                    <span class="input-group-addon">
					                        <i class="fa fa-calendar"></i>
					                    </span>
					                </div>
								</div>
								<div class="col-sm-5">
									<input type="hidden" id="event_age_range" name="event_age_range" value="">
									<input type="hidden" id="event_zipcode" name="event_zipcode" value="">
									<input type="hidden" id="event_favourite_activity" name="event_favourite_activity" value="">
									<input type="hidden" id="event_other_tags" name="event_other_tags" value="">
									<input type="submit" name="next" value="Next" id="fourth_next">
								</div>
							</div>
						{!! Form::close() !!}
						<p class="cst-title">We 'll remind the people you share with to sponsor before this date.</p>
	        	  	</div>
	              </div>  
		        </div>
				<!-- End -->
		</div>	
</section>



{{Html::script("/front/common/event/event_create.js")}}

@endsection
