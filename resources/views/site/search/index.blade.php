@if(count($results)>0)
		@foreach($results as $key=>$val)
			<div class="col-md-6 col-lg-4" id="exp_ids_{{$val['id']}}">
		        <div class="card">
		        	<?php 
		        	//pr($yelpIds);
		        	$add_class="";
		        	if (in_array($val['id'], $yelpIds))
					{
						$add_class="exp-added";
					}?>
		            <div class="card-header <?php echo $add_class;?>">
		            	
					<?php $defaultPath = config('constant.imageNotFound');?>
		            @if($val['image_url']!="")	
				  		<img src="{{ $val['image_url'] }}" alt="" title="">
				  	@else
				  		<img src="{{ asset($defaultPath) }}" alt="" title="">
				  	@endif
				  		@if(isset($yelpIds) && count($yelpIds) >0)
				  			   @if (in_array($val['id'], $yelpIds))
				  				 <span>ADDED!</span> 
				  				@endif
				  		@endif
						<div class="verify">
							<span>Added!</span>
							<div class="cls">
								<a href="javascript:void(0)" data-id="{{$val['id']}}"><i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;Remove</a>
								<input type="hidden" name="session_yelp_id" id="session_yelp_id_{{$val['id']}}" value="0">
							</div>
						</div>
				  				 
				   </div> 
		            <div class="card-body">
		                <h5 class="card-title">{{$val['name']}} </h5>
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
						<?php
						//pr($yelpExperiences->toArray());
						?>
		                @if(Auth::guard('site')->check())
		                	<?php
		                		//check from database
		                		$amounts="0";
		                		if(isset($yelp_saved_Experiences) && count($yelp_saved_Experiences) > 0)
								{
									
									foreach($yelp_saved_Experiences as $key3=>$val3)
									{
										//pr($val3);
										//echo $val3->yelp_exp_id.'==='.$val['id'];
										if($val3->yelp_exp_id==$val['id'])
										{
											$amounts= $val3->gift_needed;
										}
									}
								}
		                	?>
					   		@if (in_array($val['id'], $yelpIds))
					    	<?php
					    		//check from session
					    		$yelpExperiences = Session::get('yelp_experience');
								if(count($yelpExperiences) > 0)
								{
									foreach($yelpExperiences as $key2=>$val2)
									{
										if($val2['yelp_id']==$val['id'])
										{
											$amounts= $val2['amount'];
										}
									}
								}
							?>
					    		<input type="text" data-id="{{$val['id']}}" class="number valid yelp-amount current_id_<?php echo $val['id']?>" id="yelp_amount_<?php echo $val['id']?>" name="yelp_amount" value="{{$amounts}}" aria-required="true" aria-invalid="false">
					    	@else
					    		<a href="javascript:void(0)" data-id="{{$val['id']}}" id="recomanded_<?php echo $val['id']?>" class="commont-btn add-perfect-exp">ADD</a>
					    	@endif	
					    	<a href="javascript:void(0)" data-id="{{$val['id']}}" id="recomanded_<?php echo $val['id']?>" class="commont-btn add-perfect-exp hide">ADD</a>	
					    	<input type="hidden" name="yelp_exp_id_{{$val['id']}}" value="{{$val['id']}}">
					    	<input type="hidden" id="exp_name_{{$val['id']}}" name="exp_name_{{$val['id']}}" value="{{$val['name']}}">
							<input type="hidden" id="image_{{$val['id']}}" name="image_{{$val['id']}}" value="{{$val['image_url']}}">
							<input type="hidden" name="description" value="{{$address}}">
							<input type="hidden" class="my_event_id" name="my_event_id"  value="0">
							<input type="hidden" id="session_flag_{{$val['id']}}" name="session_flag_{{$val['id']}}" value="0">
					   	
					  @else
					  	<a href="javascript:void(0)" class="commont-btn add-perfect-exp" href="javascript:void(0)"  data-toggle="modal" data-target="#login">ADD</a>
					  @endif  	
		            </div>
		        </div>
		    </div>
		@endforeach		
@else
	<h3>No result found</h3>
@endif		

