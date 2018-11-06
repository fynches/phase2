@extends('layouts.app_front')

@section('header')
	@include('site.header')
@stop

@section('pagetitle', 'Event')
@section('content')

<!-- Breadcrumb Sec -->
<section class="slider-sec checkout-confirm">
	<div class="container">
		<div class="row">
			<?php
			
				$user_name="User";
				$stripe_user_ids="";
				$event_id="0";
				$event_url="";
				
				if(count($to_user_details) > 0)
				{
					$user_name= $to_user_details->first_name.' '.$to_user_details->last_name;
					
				}
				//pr($data);die;
				if(count($data) > 0)
				{
					$event_id= $data['event_id'];
					$stripe_user_ids= $data['to_stripe_user_id'];
					$event_url= 'create-experience/'.$event_id;
				}
				
			?>
			<div class="col-md-12">
				<h2>Your Gift to {{$user_name}}</h2>
				<ol class="breadcrumb">
				  <li class="breadcrumb-item"><a href="{{$event_url}}">Event Page</a></li>
				  <li class="breadcrumb-item"><a href="{{'/checkout-event'}}">Payment Method</a></li>
				  <li class="breadcrumb-item active">Confirm Gift</li>
				</ol>
			</div>
		</div>
		
		{!! Form::open(array('url'=>'/confirm_gift', 'class'=>'form-horizontal','method'=>'POST','id'=>'confirm_payment','files'=>true)) !!}
		<div class="confirm-payment">
			<div class="row">
				<div class="col-md-12">
					<h3>Review &amp; Confirm Payment</h3>
				</div>
			</div>
			<div class="row">
				<div class="col-md-8">
					<div class="gft-details">
						<div class="row">
							<div class="col-md-12">
								<h3>Gift Details</h3>
							</div>
						</div>
						<div class="row pt-3">
							<div class="col-md-3 offset-md-1 pt-3 pr-0"><h4>Your Gifts:</h4></div>
							<div class="col-md-8 pl-0">
								<div class="table-responsive">
								    <table class="table">
								      <tbody>
								      	<?php $total_gift_amount="0";?>
									      	@if(count($my_experience) > 0)
									      	@foreach($my_experience as $key=>$val)
								      		<?php
											//pr($total_items);die;
									        $defaultPath = 'storage/no-img.jpg';
									        $ExpImage = $val['image'];
									
									        if ($ExpImage && $ExpImage != "") {
												
												if($val['experience_from'] == 1){
													$imgPath = $ExpImage;
												}else{
													$imgPath = 'storage/experienceImages/thumb/' . $ExpImage;
													if (file_exists($imgPath))
													{
														$imgPath = $imgPath;
													} else {
														$imgPath = $defaultPath;
													}
												}
									            
									        } else {
									            $imgPath = $defaultPath;
									        }
											
											$amount="";
											$experience_ids="";
											$bonus_amt="0";
											
											if(count($total_items) >0 )
											{
												foreach($total_items as $key1=>$val2)
												{
													if($val->id == $val2['exp_id'])
													{
														$amount= $val2['value'];
														$actual_gift_needed= $val2['actual_amount_value'];
														
														if($actual_gift_needed !="" && $actual_gift_needed!="0")
														{
															if($amount > $actual_gift_needed)
															{
																$amount = $amount;
																$bonus_amt= $amount - $actual_gift_needed;
															}else{
																$amount = $amount;
																$bonus_amt="0";
															}
														}
														
														$experience_ids= $val2['exp_id'];
														$total_gift_amount +=$amount;
													}
												}
											}
											
											
									        ?>
									        
									        <tr>
									          <td><div class="tbl-img"><img src="{{ asset($imgPath) }}" alt="" title=""></div></td>
									          <td>
									          	<h4>{{$val->exp_name}}</h4>
									          	<p>{{$val->description}}</p>
									          </td>
									          <td>
									          	<span>${{$amount}}</span>
									          	<input type="hidden" class="number actual_gift_needed" name="actual_gift_needed[]" id="actual_gift_needed{{$key+1}}" value="{{$actual_gift_needed}}">
									          	<input type="hidden" class="paid_exp_amount" name="gift_val[]" id="gift_val_{{$key+1}}" value="{{$amount}}">
									          	<input type="hidden" name="bonus_amt[]" value="{{$bonus_amt}}">
									          	<input type="hidden" name="exp_id[]" id="exp_ids{{$key+1}}" value="{{$experience_ids}}">
									          	<input type="hidden"  name="already_fynches_user" value="{{$data['already_fynches_user']}}">
									          	<input type="hidden"  name="credit_card_number" value="{{$data['credit_card_number']}}">
									          	<input type="hidden"  name="expiration_date" value="{{$data['expiration_date']}}">
									          	<input type="hidden"  name="expiration_year" value="{{$data['expiration_year']}}">
									          	<input type="hidden"  name="description" value="{{$data['description']}}">
									          	<input type="hidden"  name="make_annoymas" value="{{$data['make_annoymas']}}">
									          	<input type="hidden"  name="cvv_no" value="{{$data['cvv_no']}}">
									          	<input type="hidden"  name="final_amount" value="{{$total_gift_amount}}">
									          	<input type="hidden"  name="to_stripe_user_id" value="{{$stripe_user_ids ?? ''}}">
									          </td>
								        	</tr>
									        
									        
								      	@endforeach
									        @else
									        <tr>
									        	<td>No Experience Added</td>
									        </tr>
									        @endif
									        
									        @if($bonus_amount!="0")
									        	<tr class="checkout events-chkout bonus-checkout">
										          <td><div class="tbl-img"><img src="{{ asset($defaultPath) }}" alt="" title=""></div></td>
										          <td>
										          	<h4>Additional Amount</h4>
										          </td>
										          <td>
										          	<span>${{$bonus_amount}}</span>
										          	<input type="hidden" name="bonus_amout_val" value="{{$bonus_amount}}">
										          </td>
									        	</tr>
									        <input type="hidden" id="event_id" name="event_id" value="{{$event_id}}">	
									        @endif
								      </tbody>
								    </table>
								 </div>
							</div>
						</div>
						<div class="row pt-2">
							<div class="col-md-12">
								<div class="table-responsive ds-total">
								    <table class="table">
								      <tbody>
								      	<tr>
								      	<?php
							          		if($bonus_amount!="" && $bonus_amount!="0")
											{
												$total_gift_amount = $total_gift_amount+$bonus_amount;
											}
							          	?>
								          <td><h4>Total Gift Amount:</h4></td>
								          <td><span>${{$total_gift_amount}}</span></td>
								        </tr>
								      </tbody>
								    </table>
								 </div>
							</div>
						</div>
						<div class="row pt-4 pb-3">
							<div class="col-sm-4 text-right"><h4>Message:</h4></div>
							<div class="col-sm-8"><p class="mb-0">{{ $data['description'] }}</p></div>
						</div>
						<div class="row">
							<div class="col-sm-4 text-right"><h4>Make anonymous?</h4></div>
							<div class="col-sm-8"><p class="mb-0">
								{{ $data['make_annoymas'] }}
							</p></div>
						</div>
					</div>
				</div>

				<div class="col-md-4">
					<div class="gft-details payment">
						<?php
						//pr($data);
							$first_name="";
							$last_name="";
							$email="";
							if(count($user_details) >0)
							{
								$first_name= $user_details->first_name;
								$last_name= $user_details->last_name;
								$email=  $user_details->email;
							}
						?>
						<h3 class="mb-4">Payments </h3>
						<?php
							$newstring="";
							$credit_card_no = $data['credit_card_number'];
							$newstring = substr($credit_card_no, -4);
						?>
						 @if(isset($data['pay_by_check']))
							@if($data['pay_by_check']=="0")
								<p class="card_type">...&nbsp;...&nbsp;...&nbsp;<?php echo $newstring; ?>  </p>
							else
								<p class="card_type">...&nbsp;...&nbsp;...&nbsp;<?php echo $newstring; ?>  </p>
							@endif
						@else
								<p class="card_type">...&nbsp;...&nbsp;...&nbsp;<?php echo $newstring; ?> </p>	
						@endif 	
						
						<address>
							<?php 
							
							$country_name="";
							$state_name= "";
							if(count($state) > 0)
							{
								foreach($state as $key=>$val)
								{
									if($val['id'] ==  $data['state'] )
									{
										$state_name = $val['name'];
									}
								}
							}
							
							if($data['country']=="1")
							{
								$country_name="USA";
							}else if($data['country']=="2")
							{
								$country_name="Canada";
							}else{
								$country_name="India";
							}
							?>
							{{ $data['first_name'].' '.$data['last_name'] }}<br> {{ $data['floor'] }} {{$data['address']}}<br> {{$data['city'].', '.$state_name}}  <br> {{ $data['zipcode']}}  <br> {{$country_name}}
						</address>
						
						<a href="tel:(123)456-7890">{{ $data['phone_no']}}</a>
						<a>{{ $data['email'] ?? $email}}</a>
						<input type="hidden"  name="email" value="{{$data['email'] ?? $email}}">
						<input type="hidden"  name="first_name" value="{{$data['first_name'] ?? ''}}">
						<input type="hidden"  name="last_name" value="{{$data['last_name'] ?? ''}}">
						<input type="hidden"  name="password" value="{{$data['password'] ?? ''}}">
						<input type="hidden"  name="payment_method" value="{{$data['pay_by_check'] ?? '0'}}">
						<input type="hidden" id="event_id" name="event_id" value="{{$my_experience[0]->event_id ?? '0'}}">
						<!-- <p>Password:&nbsp;.....</p> -->
					</div>
					<a href="javascript:void(0)" class="commont-btn mt-4 review_confirm">SUBMIT PAYMENT</a>
				</div>
			</div>
		</div>
		{!! Form::close() !!}
	</div>
</section>
<!-- End -->

<script type="text/javascript">

$(document).ready(function(){
	var card_no ='<?php echo $newstring;?>';
	var card_type="";
	if(card_no!='' && card_no != undefined)
	{
		card_type= $.payment.cardType(card_no);
		$('.card_type').append(card_type);
	}
});
</script>

{{Html::script("/front/common/event/event_checkout.js")}}
{{Html::script("/front/common/event/jquery.payment.js")}}

@endsection