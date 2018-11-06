@extends('layouts.app_front')
@section('header')
  @include('site.event_create_header')
@stop
@section('pagetitle', 'Funding Report')
@section('content')

<!-- Dashboard Sec-->

		<?php
			$total_amount_raised="0";
			$total_donated_users="0";
			$total_sum_amount="0";
			$per_bonus_amount_received="0";
			$event_title = "";
			$donated_user_id=array();
			
			if(count($funding_reports) > 0)
			{
				foreach($funding_reports as $key=>$val)
				{
					if(isset($val->FundingReport) && count($val->FundingReport) > 0)
					{
						foreach($val->FundingReport as $key2=>$val2)
						{
							if($val2['status'] == 'succeeded')
							{
								if($val2['donated_amount']!="0" && $val2['bonus_amount']!="0")
					        	{
					        		$per_event_amount_recevived = $val2['donated_amount'] - $val2['bonus_amount'];
					        	}
								else if($val->gift_needed < $val2['donated_amount'])
								{
									$per_event_amount_recevived= $val->gift_needed;	
								}else{
									$per_event_amount_recevived = $val2['donated_amount'];
								}
								
								$donated_user_id[]= $val2->getUser->id;
								$total_amount_raised += $per_event_amount_recevived;
							}
							
						}
					}
				}
			}
			
			//pr($bonus_amount);die;
			if(count($bonus_amount) > 0)
			{
      			foreach($bonus_amount as $key=>$val)
      			{
      				if($val->event_id == $current_event_id && $val->status=="succeeded")
					{
						$per_bonus_amount_received += $val->bonus_amount;	
					}
				}
			}
			
      		if(count($donated_user_id) > 0)
			{
				$total_donated_fund_users= array_unique($donated_user_id);
				$total_donated_users= count($total_donated_fund_users);
			}			
			
			$total_sum_amount = $per_bonus_amount_received + $total_amount_raised;
		//	pr($donated_user_id);
			
		?>
		
<section class="dashboard contact">
	<div class="container">
		@include('layouts.front-notifications')
		<div class="row">
			<div class="col-sm-12"><h2>{{$event_title}}<br> Funding report</h2></div>
		</div>
		<div class="row">
      		<div class="col-4 col-md-4">
      			
      			<h3 class="price">${{$total_amount_raised + $per_bonus_amount_received}}</h3>
      			<span>Total Amount </span>
      		</div>
      		<div class="col-4 col-md-4">
      			<h3 class="price">{{$total_donated_users}}</h3>
      			<span>No. Of <br>of donors  </span>
      		</div>
      		<div class="col-4 col-md-4">
      			<?php
      			    $average_percentage="0";					
      				if($total_sum_amount!="0" && $total_donated_users!="0")
					{
						$average_percentage = $total_sum_amount / $total_donated_users; 
						$average_percentage= number_format($average_percentage ,2);
					}
      			?>
      			<h3 class="price"> {{ $average_percentage }}</h3>
      			<span>Average Amount <br>per donors </span>
      		</div>
      	</div>
      	
      	<?php 
      	$per_event_amount_recevived="0";
		if(!isset($preview_url))
		{
			$preview_url="";
		}
		
		//pr($funding_reports->toArray());die;
		 ?>
      	
      	@if(count($funding_reports) > 0)
      		@foreach($funding_reports as $key=>$val)
      		<?php $per_event_amount_recevived="0"; ?>
      			<div class="cust-box pt-3 pb-3 funding_divs">
					<div class="row ">
						<div class="col-md-3 text-center">
							@if(isset($val->FundingReport) && count($val->FundingReport) > 0)
						    		@foreach($val->FundingReport as $key2=>$val2)
						    			<?php
						    			if($val2->status=="succeeded")
										{
						    				if($val2->bonus_flag=='0')
											{
												$per_event_amount_recevived += $val2->donated_amount;	
											}else{
												$per_event_amount_recevived += $val2->donated_amount - $val2->bonus_amount;
											}
						    				
											if($val->gift_needed < $per_event_amount_recevived)
											{
											 	$per_event_amount_recevived= $val->gift_needed;
											} 
										}
						    			?>
						    		@endforeach
							  @else
							  		<?php  $per_event_amount_recevived = "0"; ?>  		
							  @endif  	
							  
							  
								<?php
			                    $percentage_funded="0";
			                	if($per_event_amount_recevived!="0" && $val->gift_needed!="0" && $val2->status=="succeeded") 
								{
									$percentage_funded = $per_event_amount_recevived / $val->gift_needed * 100;
									$percentage_funded= round($percentage_funded);
									$add_circle_class = 'p'.$percentage_funded;
								}
								else{
									$add_circle_class = "";
								}
								
								//echo $val->gift_needed.'===='.$per_event_amount_recevived;
								if($val->gift_needed < $per_event_amount_recevived)
								{
									$add_circle_class = 'p'."100";
								}
								
								//pr($val->FundingReport[0]->getEvent);die;
								//echo "@@@@@@@@@@@@@@".$val->FundingReport[0]->getEvent['id'];
			                	?>
								
							        
			                <div class="c100 <?php echo $add_circle_class; ?> center">
			                	
			                    <span>${{$per_event_amount_recevived}}</span>
			                    <div class="slice">
			                        <div class="bar"></div>
			                        <div class="fill"></div>
			                    </div>
			                </div>
			                <?php
			                    $percentage_funded="0";
			                	if($per_event_amount_recevived!="0" && $val->gift_needed!="0")
								{
									$percentage_funded = $per_event_amount_recevived / $val->gift_needed * 100;
									$percentage_funded= number_format($percentage_funded ,2);
								}
								
								if($val->gift_needed < $per_event_amount_recevived)
								{
									$percentage_funded = "100";
								}
								$per_event_amount_recevived="";
			                ?>
			                <span class="process-txt">Goal: ${{$val->gift_needed}} <br> {{ $percentage_funded }}% funded</span>
						</div>
						<div class="col-md-9 pt-3">
							<h4>{{ $val->exp_name }}</h4>
							<div class="ds-more">
								<!-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididun</p> -->
								<span class="more">
									{{$val->description}}
									<!-- <a href="javascript:void(0)">read more</a> --> 
								</span>
							</div>
							<h4>Supporters</h4>
							<div class="table-responsive">
								<table class="table">
								    <tbody>
								    	
								    	@if(isset($val->FundingReport) && count($val->FundingReport) > 0)
								    		@foreach($val->FundingReport as $key2=>$val2)
								    			<?php
								    			//	pr($val2);
								    			?>
								    			  <tr>
								    			  	
								    			  	<td>
								    			  		
								    			  		@if($val2->make_annoymas=="No")
								    			  			{{$val2->getUser->first_name.' '.$val2->getUser->last_name }}
								    			  		@else
								    			  			Anonymous User
								    			  		@endif		
								    			  	</td>
								    			  	
											        <td>
											        	@if($val2->make_annoymas=="No")
											        		{{$val2->getUser->email}}
											        	@else
											        		-
											        	@endif		
											        	
											        </td>
											        <td>
											        	<?php
											        	if($val2['donated_amount']!="0" && $val2['bonus_amount']!="0")
											        	{
											        		$per_event_amount_recevived = $val2['donated_amount'] - $val2['bonus_amount'];
											        	}
														else if($val->gift_needed < $val2['donated_amount'])
														{
															$per_event_amount_recevived= $val->gift_needed;	
														}else{
															$per_event_amount_recevived = $val2['donated_amount'];
														}
											        	// echo $val->gift_needed.'==='.$val2['donated_amount'];
											        		// if($val->gift_needed < $val2['donated_amount'])
															// {
																// $per_event_amount_recevived= $val->gift_needed;	
// 															 	
															// }else{
																// $per_event_amount_recevived = $val2['donated_amount'];
															// }
											        	?>
											        	${{$per_event_amount_recevived}}</td>
											        <?php
											        	$add_disable_class="";
											        	if($val2->sent_mail=="1")
														{
															$add_disable_class = "disable";
														}
											        ?>
											        <td>
											        	<?php //echo "@@@@@@@".$val2->payment_method;?>
											        	@if($val2->sent_mail=="0" && $val2->make_annoymas=="No" && $val2->status == "succeeded")
											        		<a href="javascript:void(0)" data-toggle="modal" data-id="{{$val2->id}}" data-email="{{$val2->getUser->email}}" data-target="#feedback" class="commont-btn send-thankyou-mail <?php echo $add_disable_class;?>">Send Thank You</a>
											        	@elseif($val2->payment_method == "1" && $val2->status == "pending")
											        		<select name="payment_status" class="payment_status" data-id="{{$val2->id}}">
																<option value="">Select Status</option>
																<option value="pending" selected="selected">Pending</option>
																<option value="succeeded">Accepted</option>
															</select>	
											        	@endif	
											        </td>
											      </tr>
											@endforeach
								     	@else
								     		<p>No Supporter Yet. </p>
								     	@endif	
								    </tbody>
			  					</table>
			  				</div>
						</div>
					</div>
				</div>
      		
      		@endforeach
      	@endif
      	
      	<?php
      		$per_bonus_amount_received="0";
			$perentage_funded="0";
			$add_circle_class="";
			$percentage_funded="0";
			$user_name="";
			$user_email="";
      	//	pr($bonus_amount->toArray());die;
      	?>
      	
      	@if(count($bonus_amount) > 0)
      		@foreach($bonus_amount as $key=>$val)
      			<?php
      				
					
					//pr($val);
					if($val->event_id == $current_event_id && $val->status=="succeeded")
					{
						$per_bonus_amount_received += $val->bonus_amount;	
						$add_circle_class= "p100";
						
					}
    			?>
      		@endforeach	
      		
      			<?php
      				if($per_bonus_amount_received!="" && $per_bonus_amount_received!="0")
					{
						$perentage_funded="100";
					}
      			?>
	      		<div class="cust-box pt-3 pb-3">
						<div class="row ">
							<div class="col-md-3 text-center">
				                <div class="c100 <?php echo $add_circle_class; ?> center">
		                			<span>${{$per_bonus_amount_received}}</span>
				                    <div class="slice">
				                        <div class="bar"></div>
				                        <div class="fill"></div>
				                    </div>
				                </div>
				                
				                <span class="process-txt">Additional Amount: ${{$per_bonus_amount_received}} <br> {{$perentage_funded}}% funded</span>
							</div>
							<div class="col-md-9 pt-3">
								<h4>Additional Amount</h4>
								
								<h4>Supporters</h4>
								<div class="table-responsive">
									<table class="table">
									    <tbody>
									    	@foreach($bonus_amount as $key=>$val)
									    	 @if($val->event_id == $current_event_id)
									    	 <?php
									    	 	$per_bonus_amount_received = $val->bonus_amount;
												$user_name= $val->getUser->first_name.' '.$val->getUser->last_name;
												$user_email= $val->getUser->email;
									    	 ?>	
									    	 <tr>
										        <td>
										        	@if($val->make_annoymas=="No")
										        		{{$user_name }}
										        	@else
										        		Anonymous User
										        	@endif		
										        </td>
										        <td>
										        	@if($val->make_annoymas=="No")
										        		{{$user_email}}
										        	@else
										        		-
										        	@endif		
										        </td>
										        <td>${{$per_bonus_amount_received}}</td>
										        <td>
										        	@if($val->sent_mail=="0" && $val->make_annoymas=="No" && $val->status == "succeeded")
											        		<a href="javascript:void(0)" data-toggle="modal" data-id="{{$val->id}}" data-email= "{{$user_email}}" data-target="#feedback" class="commont-btn send-thankyou-mail">Send Thank You</a>
										        	@elseif($val->payment_method == "1" && $val->status == "pending")
										        		
										        		<select name="payment_status" class="payment_status" data-id="{{$val->id}}">
															<option value="">Select Status</option>
															<option value="pending" selected="selected">Pending</option>
															<option value="succeeded">Accepted</option>
														</select>	
										        	@endif
										        		
										        </td>
										      </tr>
										     @endif 
										     @endforeach 
									    </tbody>
				  					</table>
				  				</div>
							</div>
						</div>
					</div>
	   	@endif		
	</div>
</section>

<style>
.funding_divs .morecontent span {
    display: none;
}
.funding_divs .morelink {
    display: block;
}

</style>
<!-- End -->
{{Html::script("/front/common/event/funding-report.js")}}
@include('site.modal.thankyou-modal')

@endsection