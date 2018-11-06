<div class="col-md-12">                	
	@if(count($user_events) > 0)
                		@foreach($user_events as $key=>$val)
                			<?php
                				//pr($val->toArray());die;
                				$defaultPath = config('constant.imageNotFound');
                				$event_status="";
								$imgPath="";
								$event_publish_date="";
								$event_upublish_end_date="";
								$days_remaining="0";
								$received_amount="0";
								$per_event_amount_recevived="0";
								$actutal_received_amount="0";
								$request_amount="0";
								$bonus_amount="0";
								$publish_class = "";
                				if($val->status =="1")
								{
									$event_status ="PUBLISHED";
									$publish_class = "published";
								}
								else if($val->status =="4")
								{
									$event_status ="UNPUBLISHED";
									$publish_class = "unpublished";
								}
								
								if(isset($val->event_publish_date))
								{
									if($val->status=="1")
									{
										$event_publish_date = date("m/d/Y", strtotime($val->updated_at));	
									}
								}
								
								if(isset($val->event_end_date) && $val->event_end_date!="1970-01-01 00:00:00")
								{
									$current_date= date('Y-m-d');
									$due_date= date('Y-m-d',strtotime('+30 days',strtotime($val->event_end_date)));
									$date1 = new DateTime($current_date);  //current date or any date
									$date2 = new DateTime($due_date);   //Future date
									$diff = $date2->diff($date1)->format("%a");  //find difference
									
									if($val->event_end_date < $current_date)
									{
										$days_remaining="0";
									}
									else {
										$days_remaining = intval($diff);   //rounding days
									}
								}
								
								if(isset($val->getEventExperiences))
								{
									foreach($val->getEventExperiences as $key2=>$val2)
									{
										$request_amount += $val2->gift_needed;
									}
								}
								
								if(isset($val->FundingReport))
								{
									foreach($val->FundingReport as $key3=>$val3)
									{
										
										if($val3->status=="succeeded")
										{
											if($val3->donated_amount!="" && $val3->bonus_amount!="")
											{
												$received_amount += $val3->donated_amount - $val3->bonus_amount;
											}else{
												$received_amount += $val3->donated_amount;
											}
										}
									}
								}
								
								//echo "@@@@@@@@@@@@".$received_amount;
								//get bonus amount
								if(isset($val->FundingReport))
								{
									foreach($val->FundingReport as $key3=>$val3)
									{
										if($val3->status=="succeeded")
										{
											$bonus_amount += $val3->bonus_amount;
										}
									}
								}								
								
								$actutal_received_amount =  $received_amount + $bonus_amount;
								
							?>
			<div class="card">
				<div class="row align-items-center">
					<div class="col-md-3">
						<div class="card-header {{$publish_class}}">
							<?php
							$defaultPath = config('constant.imageNotFound');
							if(isset($val->getEventMappingMdedia) && count($val->getEventMappingMdedia) > 0)
							{?>
								
								<?php	
								//echo "@@@@@@@@".$val->getEventMappingMdedia[0]['image_type'];
									if($val->getEventMappingMdedia[0]['image_type']=="2") //facebook images
									{ ?>
										<img src="{{$val->getEventMappingMdedia[0]['image']}}" alt="" title="">
								<?php 
									}
									else if($val->getEventMappingMdedia[0]['image_type']=="0")
									{
										
										$event_image = $val->getEventMappingMdedia[0]['image'];
										
										if ($event_image && $event_image != "") {
											$imgPath = 'storage/Event/' . $event_image;
											//echo $imgPath;
											if (file_exists($imgPath))
											{
												$imgPath = $imgPath;
											} else {
												$imgPath = $defaultPath;
											}
										}
										else
										{
											$imgPath = $defaultPath;
										}?>
						
								<img src="{{ asset($imgPath) }}" alt="" title="">
											
								<?php					
									} 
								
									//for local video showing
									
									if($val->getEventMappingMdedia[0]['flag_video']=="1" && $val->getEventMappingMdedia[0]['video']!="") //for local uploaded video show
									{
										$video = $val->getEventMappingMdedia[0]['video'];                    
										if ($video && $video != "") {                        
											$imgPath = 'storage/Videos/' . $video;                       
											if (file_exists($imgPath)){
												$imgPath = $imgPath;
											} else {
												$imgPath = $defaultPath;
											}
										} else {
											$imgPath = $defaultPath;
										}
									?>
									<video height="120px" controls>
											<source src="{{ asset($imgPath) }}" type="video/mp4">
											<source src="{{ asset($imgPath) }}" type="video/ogg">
											Your browser does not support the video tag.
									</video>
								<?php }

								//for youtube video showing
								
								if($val->getEventMappingMdedia[0]['flag_video']=="0" && $val->getEventMappingMdedia[0]['video']!="") 
								{?>
										
									<iframe width="260" height="120" src="{{$val->getEventMappingMdedia[0]['video']}}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
							<?php }else{
									$imgPath = $defaultPath;
									}
									}else{
								$imgPath = $defaultPath;
							}?>
							<span>{{$event_status}}</span>
						</div>
					</div>
					<div class="col-md-9">
						<div class="card-body">
							<h4>{{$val->title}}</h4>
							<p>{{$val->description}} </p>
							<span>Received ${{$actutal_received_amount}}</span>
							<span>Requested ${{$request_amount}}</span>
							<div class="row pt-2 align-items-center">
								<div class="col-md-6">
                                    @if($event_status=="UNPUBLISHED")
                                    <span>End date: {{$event_upublish_end_date}}</span>
                                    	<span>Publish Date:N/A</span>
                                    @else
                                    	<span class="note">{{$days_remaining}} days remaining</span>
                                    	<span>Publish Date:{{ $event_publish_date }}</span>
                                    @endif
                                    
                                </div>
								<div class="col-md-6">
									<ul>
										@if($event_status!="UNPUBLISHED")
                                        	<li><a href="{{'/share-event/'.$val->id}}"><i class="fa fa-share" aria-hidden="true"></i></a></li>
                                        @endif	
										<!-- <li><a href="javascript:void(0)"><i class="fa fa-share" aria-hidden="true"></i></a></li> -->
										<li><a href="{{'/create-experience/'.$val->id}}"><i class="fa fa-pencil" aria-hidden="true"></i></a></li>
										<li class="delete_events" data-id="{{$val->id}}"><a href="{{'/delete-event/'.$val->id}}"><i class="fa fa-trash" aria-hidden="true"></i></a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		@endforeach
	@else
		<h3>No events Found</h3>	
	@endif
</div>
<div class="event_pagination">
	{!! $user_events->render('pagination.dashboard') !!}
</div>