@extends('layouts.app_front')

@section('header')
	@if(Auth::guard('site')->check())
		@include('site.dashboard_header')
	@else
		@include('site.inner_header')
	@endif  
@stop


@section('pagetitle', 'Search')
@section('content')

<!-- Searchbox Sec -->
<section class="search-box">
	<div class="container">
		@include('layouts.front-notifications')
		 {!! Form::open(array('url'=>'', 'class'=>'form-horizontal add_expereinces','method'=>'POST','id' =>'search_exp')) !!} 
			<div class="row">
				<div class="col-sm-12">
					<div class="ds-search">
						<div class="form-group search">
		                  	<input type="text" class="form-control search_experience_name" id="search_for_an_experience" name="search_for_an_experience" value="{{ $search_title ?? '' }}" placeholder="Search for an experience">
		                </div>
		                
		                <div class="form-group location">
		                	<i class="fa fa-location-arrow" aria-hidden="true"></i>
		                	 <input id="searchInput" name="searchInput" class="form-control" type="text" value="{{ $current_location ?? ''}}" placeholder="Current location">
		                	 <div style="display: none;" class="map" id="map" style="width: 100%; height: 300px;"></div>
		                	<!-- <input type="text" class="form-control" id="location" name="location" value="{{$location ?? ''}}" placeholder="Current Location"> -->
		                </div>
		                
		                <div class="form_area">
						     <input type="hidden" class="current_location_name" name="location" id="location" value="{{ $current_location ?? ''}}">
						     <!-- <input type="text" name="lat" id="lat">
						     <input type="text" name="lng" id="lng"> -->
						</div>
						
		                <button type="button" class="search_result" value="Search" name="Search" >
		                    <i class="fa fa-search" aria-hidden="true"></i>Search
		                </button>
		            </div>
				</div>
			</div>
		 {!! Form::close() !!}		
	</div>
</section>
<!-- End -->
<!-- Search-result Sec -->
<section class="search-result slider-sec home-search-result" id="search_yelp_experiences">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h3>Search results</h3>
				<?php 
					if($total_count!="0")
					{
						$per_page= $perPage;
					}else{
						$per_page= "0";
					}
				?>
				<p>Showing {{ $per_page ?? '0'}} of {{$total_count ?? '0'}} results for "{{$search_title ?? ''}}"</p>
			</div>
		</div>
		<div class="row recomanded_experiences">
			<?php //pr($results);die;?>
			@if(count($results)>0)
				@foreach($results as $key=>$val)
					
					<div class="col-sm-6 col-lg-4" id="exp_ids_{{$val['id']}}">
			  			<div class="card">
			  				@if(in_array($val['id'], $yelpIds))
						  <div class="card-header exp-added">
						  	@else
						  	<div class="card-header">
						  	@endif	
						  	<img src="{{ $val['image_url'] }}" alt="" title="">
						  		<div class="verify">
							  		<span>ADDED</span>
							  		<div class="cls">
							  			<a href="javascript:void(0)"><i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;Remove</a>
							  			<input type="hidden" name="session_yelp_id" id="session_yelp_id_{{$val['id']}}" value="0">
							  		</div>
							  	</div>		 
						  </div> 
						  <div class="card-body">
						    <h5 class="card-title">{{$val['name']}}</h5>
						    <div class="ds-yelp">
						    	<i class="fa fa-yelp" aria-hidden="true"></i>
						    	<fieldset class="rating">
						    		<input type="radio" <?php if($val['rating'] == "5"){ echo 'checked="checked"'; }?> id="star5_{{$val['id']}}" name="rating_{{$val['id']}}" value="5" /><label class = "full" for="star5" title="Awesome - 5 stars"></label>
								    <input type="radio" <?php if($val['rating'] == "4.5"){ echo 'checked="checked"'; }?> id="star4half_{{$val['id']}}" name="rating_{{$val['id']}}" value="4 and a half" /><label class="half" for="star4half" title="Pretty good - 4.5 stars"></label>
								    <input type="radio" <?php if($val['rating'] == "4"){ echo 'checked="checked"'; }?> id="star4_{{$val['id']}}" name="rating_{{$val['id']}}" value="4" /><label class = "full" for="star4" title="Pretty good - 4 stars"></label>
								    <input type="radio" <?php if($val['rating'] == "3.5"){ echo 'checked="checked"'; }?>id="star3half_{{$val['id']}}" name="rating_{{$val['id']}}" value="3 and a half" /><label class="half" for="star3half" title="Meh - 3.5 stars"></label>
								    <input type="radio" <?php if($val['rating'] == "3"){ echo 'checked="checked"'; }?> id="star3_{{$val['id']}}" name="rating_{{$val['id']}}" value="3" /><label class = "full" for="star3" title="Good - 3 stars"></label>
								    <input type="radio" <?php if($val['rating'] == "2.5"){ echo 'checked="checked"'; }?> id="star2half_{{$val['id']}}" name="rating_{{$val['id']}}" value="2 and a half" /><label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>
								    <input type="radio" <?php if($val['rating'] == "2"){ echo 'checked="checked"'; }?> id="star2_{{$val['id']}}" name="rating_{{$val['id']}}" value="2" /><label class = "full" for="star2" title="Average - 2 stars"></label>
								    <input type="radio" <?php if($val['rating'] == "1.5"){ echo 'checked="checked"'; }?> id="star1half_{{$val['id']}}" name="rating_{{$val['id']}}" value="1 and a half" /><label class="half" for="star1half" title="Meh - 1.5 stars"></label>
								    <input type="radio" <?php if($val['rating'] == "1"){ echo 'checked="checked"'; }?> id="star1_{{$val['id']}}" name="rating_{{$val['id']}}" value="1" /><label class = "full" for="star1" title="Bed - 1 star"></label>
								    <input type="radio" <?php if($val['rating'] == "0.5"){ echo 'checked="checked"'; }?> id="starhalf_{{$val['id']}}" name="rating_{{$val['id']}}" value="half" /><label class="half" for="starhalf" title="Sucks big time - 0.5 stars"></label>
								    
								</fieldset>
						    </div>
						    <address>
						    	<?php
						    		$address ="";
									$address1 ="";
						    		if(isset($val['location']['display_address'][0]))
									{
										$address=$val['location']['display_address'][0];
										echo $val['location']['display_address'][0];
									}
									?>
								<br>
						    	
						    	<?php
						    			
						    		if(isset($val['location']['display_address'][1]))
									{
										$address1=$val['location']['display_address'][1];
										echo $val['location']['display_address'][1];
									}
						    	?>
						    	
						    </address>
						    
						    @if(Auth::guard('site')->check())
							   
							    	@if(in_array($val['id'], $yelpIds))
							    		<a href="javascript:void(0)" data-id="{{$val['id']}}" id="recomanded_<?php echo $val['id'];?>" class="commont-btn add-perfect-exp disable">ADD</a>
							    	@else
							    		<a href="javascript:void(0)" data-id="{{$val['id']}}" id="recomanded_<?php echo $val['id'];?>" class="commont-btn add-perfect-exp">ADD</a>
							    	@endif		
							    	<input type="hidden" name="yelp_exp_id_{{$val['id']}}" value="{{$val['id']}}">
							    	<input type="hidden" id="exp_name_{{$val['id']}}" name="exp_name_{{$val['id']}}" value="{{$val['name']}}">
									<input type="hidden" id="image_{{$val['id']}}" name="image_{{$val['id']}}" value="{{$val['image_url']}}">
									<input type="hidden" name="description" value="{{$address}}">
									<input type="hidden" class="my_event_id" name="my_event_id"  value="0">
									<input type="hidden" class="total_exp_add" name="total_exp_add"  value="0">
									<input type="hidden" id="session_flag_{{$val['id']}}" name="session_flag_{{$val['id']}}" value="0">
							   	
							  @else
							  	<a href="javascript:void(0)" class="commont-btn add-perfect-exp" href="javascript:void(0)"  data-toggle="modal" data-target="#login">ADD</a>
							  @endif  	
						  </div>
						</div>
	  				</div>
				
				@endforeach
			@endif	
	  	</div>
	  	
	    <div class="row" id="show_pagination">
		    <div class="col-sm-12">
		        <div class="showpaging">
		            <div>
		            	{!! $pagination !!}
		            </div>	
		        </div>
		        <p class="pb-0 pt-2 text-center">Powered by Yelp</p>
		    </div>
		</div>
	</div>
</section>
<!-- End -->

<!-- Green box sec -->
<section class="green-box">
	<div class="container">
		<div class="row">
			<div class="col-md-7">
				<p>You've added '<span class="total_exprience_count">{{count($yelpIds) ?? '0'}}</span>' experience</p>
			</div>
			<div class="col-md-5">
				<a href="javascript:void(0)" data-toggle="modal" data-target="#cre-event" class="commont-btn">CONTINUE</a>
			</div>
		</div>
	</div>
</section>
<!-- End -->

@include('site.modal.addExperience-modal')

{{Html::script("/front/common/experience/search_experience.js")}}
{{Html::script("/front/common/experience/experience_create.js")}}

@endsection