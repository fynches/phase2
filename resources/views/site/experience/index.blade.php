<section class="experiences slider-sec search-result event-info">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="brd-title">
					<h3>Experiences</h3>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<ul id="tabs" class="nav nav-tabs" role="tablist">
			      <li class="nav-item">
			          <a id="tab-A" href="#pane-A" class="nav-link active" data-toggle="tab" role="tab">My experiences</a>
			      </li>
			      
			      <li class="nav-item">
			      		@if($event_edit_flag_upto_days=="")
			          		<a id="tab-B" href="#pane-B" class="nav-link" data-toggle="tab" role="tab"><i class="fa fa-plus" aria-hidden="true"></i>Add experiences</a>
			          	@else
			          		<a class="nav-link" data-toggle="tab" role="tab"><i class="fa fa-plus" aria-hidden="true"></i>Add experiences</a>
			          	@endif	
			      </li>
			  	</ul>

				<div id="content" class="tab-content" role="tablist">
				      <div id="pane-A" class="card tab-pane fade show active" role="tabpanel" aria-labelledby="tab-A">
				        <div  role="tab" id="heading-A">
				        	
				        		
				          			@if(isset($my_experience)  && isset($my_experience['getEventExperiences']) &&  count($my_experience['getEventExperiences']) > 0)
						          		<div class="row recomanded_experiences">
						          			
						          		  	@foreach($my_experience['getEventExperiences'] as $key=>$val)
						          		  	<?php
						          		  	
									        $URL=$val['image'];
						          		  	if(strpos($URL, "https://") !== false)
											{
												$flag= '1';
											}else{
												$flag= '2';
											}
											
											if($flag == "2")
											{
												$defaultPath = 'storage/no-img.jpg';
										        $ExpImage = $val['image'];
										
										        if ($ExpImage && $ExpImage != "") {
										            
										            $imgPath = 'storage/experienceImages/thumb/' . $ExpImage;
										           
										            if (file_exists($imgPath))
										            {
										                $imgPath = $imgPath;
										            } else {
										                $imgPath = $defaultPath;
										            }
										        } else {
										            $imgPath = $defaultPath;
										        }
											}else{
												$imgPath = $val['image'];
												if(!$imgPath){
													$imgPath = $defaultPath;
												}
											}
									        
											
											$received_amount="0";
											$remaining_amount="";
											
											if(isset($my_experience) && count($my_experience['FundingReport']) >0)
											{
												foreach($my_experience['FundingReport'] as $val2)
												{
													if($val->id == $val2->experience_id)
													{
														$received_amount += $val2['donated_amount'];	
													}
													
												}
												 $received_amount = $received_amount;
												 
											}
									        ?>
									        
						          		  		<div class="col-sm-4 exp_form_<?php echo $val->id;?>">
								          			<div class="card">
													  <div class="card-header">
													  	<img src="{{ asset($imgPath) }}" alt="" title="">
													  	@if($event_edit_flag_upto_days=="")
														  	<div class="edit">
														  		<a href="javascript:void(0)"><i class="fa fa-pencil edit_experience" data-id="{{$val->id}}" aria-hidden="true"></i></a>
														  	</div>
														@endif 	
													  	<label class="edit_exp_img">
													  		 <input id="filebutton2" name="filebutton" class="input-file" type="file">
															<i class="fa fa-camera" aria-hidden="true"></i>
														</label>
													  </div> 
													  <div class="card-body">
													    <h5 class="card-title">
													    	
													    	{{$val->exp_name}} </h5>
													    <p class="card-text">{{$val->description}} </p>
													    
													  </div>
													  <div class="card-footer">
													  	<div class="row">
													  		<div class="col-3 col-sm-3">
													  			<h4>${{$received_amount}}</h4>
													  			<span>Received</span>
													  		</div>
													  		<div class="col-3 col-sm-3">
													  			<!-- <h4>${{ $val->gift_needed }}</h4> -->
													  			@if($received_amount > $val->gift_needed)
																	<h4>$0</h4>
																@else
																	<h4>{{$val->gift_needed - $received_amount}}</h4>
																@endif
													  			<span>Needed</span>
													  		</div>
													  		
													  		<div class="col-6 col-sm-6">
													  			
													  			@if($event_edit_flag_upto_days=="" && $received_amount == "0")
													  				<a href="javascript:void(0)" onclick="delete_experience({{$val->id}})" class="commont-btn">DELETE</a>
													  			@else
													  				<a href="javascript:void(0)" class="commont-btn disable">DELETE</a>
													  			@endif	
													  		</div>
													  	</div>
													  </div>
													</div>
								          		</div>
								          		
								          		<div class="col-sm-4 comment_update_form_<?php echo $val->id;?>" style="display: none;">
								          			{!! Form::open(array('url'=>'/update-experience', 'class'=>'form-horizontal experience_update','method'=>'POST','id'=>'experience_update_'.$val->id,'files'=>true)) !!}
									          			<div class="card" id="exp_val_<?php echo $val->id;?>">
														  <div class="card-header add_exp_img">
														  	<img src="{{ asset($imgPath) }}" alt="" title="" id="my_exp_image">
														  	<label class="edit_exp_img_<?php echo $val->id;?>">
														  		 <input id="update_img" name="update_img" class="input-file" type="file">
																<i class="fa fa-camera" aria-hidden="true"></i>
															</label>
														  </div>
														   
														  <div class="card-body">
														    <input type="text" id="edit_exp_name" maxlength="120" name="edit_exp_name" value="{{$val->exp_name}}">
														    <textarea class="form-control edit_exp_desc" maxlength="240" id="edit_description_{{$val->id}}" data-id={{$val->id}} name="edit_description" rows="4" placeholder="Write a short description of your event">{{$val->description}}</textarea>
														    <div id="edit_exp_charnum_{{$val->id}}" class="edit_exp_charnum">0 / 240 characters remaining</div>
														  </div>
														  <div class="card-footer">
														  	<div class="row">
														  		<div class="col-3 col-sm-3">
														  			<h4>${{$received_amount}}</h4>
														  			<span>Received</span>
														  		</div>
														  		<div class="col-3 col-sm-3">
														  			<?php
														  				
														  				$remaining_amount = ($val->gift_needed - $received_amount);
										 
																		 if($val->gift_needed < $received_amount)
																		 {
																		 	$remaining_amount = $received_amount - $val->gift_needed.' (Amount is more then gift needed)';
																		 }
														  			
														  			?>
														  			<input type="text" class="number" id="edit_gift_needed" name="edit_gift_needed" value="{{$val->gift_needed}}">
														  			<input type="hidden" id="actual_gift_needed_{{$val->id}}" name="actual_gift_needed_{{$val->id}}" value="{{$val->gift_needed}}">
														  			<input type="hidden" id="actual_received_amt_{{$val->id}}" name="actual_received_amt_{{$val->id}}" value="{{$received_amount}}">
														  			<span>Needed</span>
														  		</div>
														  		<div class="col-6 col-sm-6">
														  			<input type="hidden" id="old_images" name="old_images" value="{{$val->image}}">
														  			<input type="hidden" id="exp_from" name="exp_from" value="{{$val->experience_from}}">
														  			<input type="hidden" id="edit_exp_id" name="edit_exp_id" value="{{$val->id}}">
														  			<a href="javascript:void(0)" onclick="update_experience({{$val->id}})" class="commont-btn">Update</a>
														  		</div>
														  	</div>
														  </div>
														</div>
													{!! Form::close() !!}	
								          		</div>
								          	@endforeach
						          			<input type="hidden" class="already_added_exp" value="1">
						          		</div>
				          				@else
					          			<div class="row">
		                                    <div class="col-sm-12">
		                                        <div class="add-exp">
		                                            <h3 class="mb-0">You haven't added any experiences yet!</h3>
		                                            <p>Checkout recommendations for you below, or create your own! <span>* (add at least one)</span></p>
		                                            <a id="add-custome-exp" href="javascript:void(0)" class="commont-btn">ADD CUSTOM EXPERIENCE</a>
		                                        </div>
		                                    </div>
	                                	</div>
                                	
	                                	<div class="row" style="">
		                                    <div class="col-sm-12">
		                                        <h4 class="ds-title">Recommended for you</h4>
		                                    </div>
		                                </div>
	                                
		                                <div class="row recomanded_experiences">
		                                	@if(count($results)>0)
												@foreach($results as $key=>$val)
													
													<div class="col-sm-4" id="exp_ids_{{$val['id']}}">
											  			<div class="card">
											  				<?php
											  				
											  					if(in_array($val['id'], $yelpIds))
											  					{
											  						$add_session_class=  'exp-added';
											  					}else{
											  						$add_session_class="";
											  					}
											  				?>
														  <div class="card-header <?php echo $add_session_class; ?>">
														  		
																<?php $defaultPath = config('constant.imageNotFound');?>
																@if($val['image_url']!="")	
																	<img src="{{ $val['image_url'] }}" alt="" title="">
																@else
																	<img src="{{ asset($defaultPath) }}" alt="" title="">
																@endif  
																@if(isset($yelpIds) && count($yelpIds) >0)
														  			   @if (in_array($val['id'], $yelpIds))
														  				 <span>Added!</span>
														  				@endif
														  		@endif
														  		<div class="verify">
															  		<span>Added!</span>
															  		<div class="cls">
															  			<a href="javascript:void(0)" data-id="{{$val['id']}}"><i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;Remove</a>
															  			<input type="hidden" class="yelp_exp_session_vals" name="session_yelp_id" id="session_yelp_id_{{$val['id']}}" value="0">
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
															    {!! Form::open(array('url'=>'/add-experience', 'class'=>'form-horizontal add_expereinces','method'=>'POST','id' =>'add_exps_'.$val['id'])) !!}
															    	<?php //echo "@@@@@@@@@@@".$event_id;?>
															    	@if(in_array($val['id'], $yelpIds))
															    		<?php
															    		$yelpExperiences = Session::get('yelp_experience');
																		$amounts="0";
																		foreach($yelpExperiences as $key2=>$val2)
																		{
																			if($val2['yelp_id']==$val['id'])
																			{
																				$amounts= $val2['amount'];
																			}
																		}
															    	?>
															    		<a href="javascript:void(0)" data-id="{{$val['id']}}" id="recomanded_<?php echo $val['id'];?>" class="commont-btn add-perfect-exp hide"></a>
															    		<input type="text" data-id="{{$val['id']}}" class="number valid yelp-amount current_id_<?php echo $val['id']?>" id="yelp_amount_<?php echo $val['id']?>" name="yelp_amount" value="{{$amounts}}" aria-required="true" aria-invalid="false">
															    			
															    	@else
															    		<a href="javascript:void(0)" data-id="{{$val['id']}}" id="recomanded_<?php echo $val['id'];?>" class="commont-btn add-perfect-exp">ADD</a>
															    	@endif		
															    	<input type="hidden" name="yelp_exp_id_{{$val['id']}}" value="{{$val['id']}}">
															    	<input type="hidden" id="exp_name_{{$val['id']}}" name="exp_name_{{$val['id']}}" value="{{$val['name']}}">
																	<input type="hidden" id="image_{{$val['id']}}" name="image_{{$val['id']}}" value="{{$val['image_url']}}">
																	<input type="hidden" name="description" value="{{$address}}">
																	<input type="hidden" class="my_event_id" name="my_event_id"  value="0">
																	<input type="hidden" id="session_flag_{{$val['id']}}" name="session_flag_{{$val['id']}}" value="0">
															    {!! Form::close() !!}	
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
										        <p class="pb-0 pt-2">Powered by Yelp</p>
										    </div>
										</div>
	                                	@endif
				          
				        </div>
				      </div>

					  	<div id="pane-B" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-B">
								<div  role="tab" id="heading-B">
									@include('site.experience.create')									
								</div>
						</div>
				       	
						@include('site.comment.comment-listing')
					   
			</div>
		</div>
	</div>
</section>

<!-- Review Sec-->
<section class="review">
	<div class="container">
		<div class="row">
			
			<div class="col-md-12">
				<h3>Review &amp; update your event</h3>
				<div class="btns">
					@if($event_id=="0")
						<a href="javascript:void(0)" class="commont-btn submit_event" id="submit_event">SAVE</a>
					@endif
					@if($stripe_user_id=="" || $stripe_user_id=="0" || count($my_experience->getEventExperiences) == 0)
						<a href="javascript:void(0)" class="commont-btn save_and_publish">SAVE &amp; PUBLISH</a>
					@else
						<a href="javascript:void(0)" onclick="save_and_publish({{$event_id}})"  class="commont-btn">SAVE &amp; UPDATE</a>
					@endif		
				</div>	
			</div>
			
			
		</div>
	</div>
</section>

@include('site.modal.addExperience-modal')
<!-- End -->
