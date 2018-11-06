@extends('layouts.app_front')
@section('header')
	@include('site.header')
@stop
@section('pagetitle', 'Event')
@section('content')


<section class="slider-sec checkout-confirm checkout-details payment-gift">
	<div class="container">
		@include('layouts.front-notifications')
		<div class="row">
			<?php
				$user_name="User";
				$stripe_user_ids="";
				
				//pr($my_experience);die;
				if(count($to_user_details) > 0)
				{
					$user_name= $to_user_details->first_name.' '.$to_user_details->last_name;
					$stripe_user_ids= $my_experience[0]->getEvent->stripe_user_id;
				}
				
			?>
			
			<div class="col-md-12">
				<h2>Your Gift to {{$user_name}}</h2>
				<ol class="breadcrumb">
				  <li class="breadcrumb-item"><a target="_blank" href="{{$publish_url}}">Event Page</a></li>
				  <li class="breadcrumb-item active">Payment Method</li>
				  <li class="breadcrumb-item"><a>Confirm Gift</a></li>
				</ol>
			</div>
		</div>
		
		{!! Form::open(array('url'=>'/store-checkout-data', 'class'=>'form-horizontal','method'=>'POST','id'=>'confirm_payment','files'=>true)) !!}
			<div class="confirm-payment">
				<div class="row">
					<div class="col-md-12">
						<h3>Gift Details</h3>
					</div>
				</div>
				<div class="gft-detail">
					<div class="row">
						<div class="col-md-12">
							<div class="row pt-3">
								<div class="col-md-3"><h4 class="text-right">Your Gifts:</h4></div>
								<div class="col-md-9">
									<div class="table-responsive">
									    <table class="table" id="chkout_tbl">
									      <tbody>
									      	<?php $total_gift_amount="0";?>
									      	@if(count($my_experience) > 0)
									      	@foreach($my_experience as $key=>$val)
								      		<?php
											//pr($val->gift_needed);die;
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
											//pr($total_items);
											if(count($total_items) >0 )
											{
												foreach($total_items as $key1=>$val2)
												{
													if($val->id == $val2->exp_id)
													{
														$amount= $val2->value;
														$actual_gift_needed= $val2->actual_amount_value;
														
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
														
														$experience_ids= $val2->exp_id;
														$total_gift_amount +=$amount;
													}
												}
											}
											
											$final_array= array('exp_id'=>$val2->exp_id,'value'=>$amount,'actual_amount_value'=>'0');
											$final_json= json_encode($final_array);
											
											//echo "@@@@@@@@@@".$final_json;
											
									        ?>
									      	<tr class="checkout_{{$val->id}} events-chkout">
									          <td><div class="tbl-img"><img src="{{ asset($imgPath) }}" alt="" title=""></div></td>
									          <td>
									          	<h4>{{$val->exp_name}}</h4>
									          	<p>{{$val->description}}</p>
									          </td>
									          <td>
									          	<input type="hidden" class="number actual_gift_needed" name="actual_gift_needed[]" id="actual_gift_needed{{$key+1}}" value="{{$actual_gift_needed}}">
									          	<input type="text" class="number paid_exp_amount" name="gift_val[]" id="gift_val_{{$key+1}}" value="{{$amount}}">
									          	<input type="hidden" name="bonus_amt[]" value="{{$bonus_amt}}">
									          	<input type="hidden" class="gift_needed_for_exp" value="{{$val->gift_needed}}">
									          	<input type="hidden" name="exp_id[]" id="exp_ids{{$key+1}}" value="{{$experience_ids}}">
									          	
									          	<span class="ds-doller">$</span>
									          	<a href="#" onclick="remove_gift({{$val->id}},{{$amount}})"><i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;Remove gift</a>
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
										          	<input type="text" class="number paid_exp_amount" name="bonus_amout_val" id="bonus_amout_val" value="{{ $bonus_amount }}">
										          	<span class="ds-doller">$</span>
										          	 <a href="#" onclick="remove_bonus_gift({{$bonus_amount}})"><i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;Remove gift</a> 
										          </td>
									        	</tr>
									        @endif
									      </tbody>
									    </table>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="table-responsive ds-total">
											    <table class="table">
											      <tbody>
											      	<tr>
											          <td><h4>Total Gift Amount:</h4></td>
											          <td>
											          	<?php
											          		if($bonus_amount!="" && $bonus_amount!="0")
															{
																$total_gift_amount = $total_gift_amount+$bonus_amount;
															}
											          	?>
											          	<span id="total_amount">${{$total_gift_amount}}</span>
											          	<input type="hidden" id="final_amount" name="final_amount" value="{{$total_gift_amount}}">
											          </td>
											          
											        </tr>
											      </tbody>
											    </table>
											 </div>
										</div>
									</div>
								</div>
							</div>
	
							<div class="row">
								<div class="col-md-3"><h4 class="text-right p-0">Comment: </h4></div>
								<div class="col-md-9">
									<div class="form-group">
										@if(isset($get_session_form_data))
									    	<textarea class="form-control fund_message" maxlength="1000" rows="4" placeholder="Send a message" id="description" name="description">{{$get_session_form_data['description'] ?? ''}} </textarea>
									    @else
									    <textarea class="form-control fund_message" maxlength="1000" rows="4" placeholder="Send a message" id="description" name="description"></textarea>
									    @endif
									    <div id="charnum">0 / 1000 characters remaining</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3"><h4 class="text-right p-0">Make anonymous:</h4></div>
								<div class="col-md-9">
									<?php
										$make_annoymas_yes_chked="";
										$make_annoymas_no_chked="";
										$by_defult="";
										//pr($get_session_form_data);
										if($get_session_form_data['make_annoymas']=="Yes")
										{
											$make_annoymas_yes_chked='checked="checked"';
										}else if($get_session_form_data['make_annoymas']=="No"){
											$make_annoymas_no_chked='checked="checked"';
										}else{
											$by_defult='checked="checked"';
										}
									?>
									<ul class="radio_btns_ul">
										<li>
											<label class="ds-radio">No
											  <input type="radio" <?php echo $make_annoymas_no_chked; ?> <?php echo $by_defult; ?> name="make_annoymas" value="No">
											  <span class="checkmark"></span>
											</label>
										</li>
										<li>
											<label class="ds-radio">Yes
											  <input type="radio" <?php echo $make_annoymas_yes_chked; ?> name="make_annoymas" value="Yes">
											  <span class="checkmark"></span>
											</label>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		
		
			<div class="payment-info accordian-sec">
				<div class="row">
					<div class="col-md-12">
						<h3>Payment Information</h3>
					</div>
				</div>
	
				<div class="row">
					<div class="col-md-9 offset-md-3">
						<ul id="tabs" class="nav nav-tabs" role="tablist">
							<li class="nav-item">
							  <a id="tab-A" href="#pane-A" class="nav-link active" data-toggle="tab" role="tab">Pay by Credit Card</a>
							</li>
							<li class="nav-item">
							  <a id="tab-B" href="#pane-B" class="nav-link" data-toggle="tab" role="tab">Offline Payment</a>
							</li>
							<input type="hidden" id="payment_methods" name="payment_methods" value="{{ $get_session_form_data['pay_by_check'] ?? '0'}}">
						</ul>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div id="content" class="tab-content" role="tablist">
							<div id="pane-A" class="tab-pane fade show active" role="tabpanel" aria-labelledby="tab-A">
								<div  role="tab" id="heading-A">
									<div class="row">
										<div class="col-md-3"><h4 class="pt-2">Credit card number:</h4></div>
										<div class="col-md-9">
											<input type="text" class="cc-number" name="credit_card_number" value="{{$get_session_form_data['credit_card_number']}}" placeholder="Credit card number" id="credit_card_number" name="credit_card_number">
										</div>
									</div>
									<div class="row">
										<div class="col-md-3"><h4 class="pt-2">Expiration date:</h4></div>
										<div class="col-md-9">
											<ul class="ds-fix-select">
												<li>
													<select id="expiration_date" name="expiration_date">
														<option value="">MM</option>
														<option value="1" <?php if($get_session_form_data['expiration_date'] == "1") {echo 'selected="selected"';}?>>1</option>
														<option value="2" <?php if($get_session_form_data['expiration_date'] == "2") {echo 'selected="selected"';}?>>2</option>
														<option value="3" <?php if($get_session_form_data['expiration_date'] == "3") {echo 'selected="selected"';}?>>3</option>
														<option value="4" <?php if($get_session_form_data['expiration_date'] == "4") {echo 'selected="selected"';}?>>4</option>
														<option value="5" <?php if($get_session_form_data['expiration_date'] == "5") {echo 'selected="selected"';}?>>5</option>
														<option value="6" <?php if($get_session_form_data['expiration_date'] == "6") {echo 'selected="selected"';}?>>6</option>
														<option value="7" <?php if($get_session_form_data['expiration_date'] == "7") {echo 'selected="selected"';}?>>7</option>
														<option value="8" <?php if($get_session_form_data['expiration_date'] == "8") {echo 'selected="selected"';}?>>8</option>
														<option value="9" <?php if($get_session_form_data['expiration_date'] == "9") {echo 'selected="selected"';}?>>9</option>
														<option value="10" <?php if($get_session_form_data['expiration_date'] == "10") {echo 'selected="selected"';}?>>10</option>
														<option value="11" <?php if($get_session_form_data['expiration_date'] == "11") {echo 'selected="selected"';}?>>11</option>
														<option value="12" <?php if($get_session_form_data['expiration_date'] == "12") {echo 'selected="selected"';}?>>12</option>
													</select>
												</li>
												<li>
													<select id="expiration_year" name="expiration_year">
														<option value="">YY</option>
														<option value="2018" <?php if($get_session_form_data['expiration_year'] == "2018") {echo 'selected="selected"';}?>>2018</option>
														<option value="2019" <?php if($get_session_form_data['expiration_year'] == "2019") {echo 'selected="selected"';}?>>2019</option>
														<option value="2020" <?php if($get_session_form_data['expiration_year'] == "2020") {echo 'selected="selected"';}?>>2020</option>
														<option value="2021" <?php if($get_session_form_data['expiration_year'] == "2021") {echo 'selected="selected"';}?>>2021</option>
														<option value="2022" <?php if($get_session_form_data['expiration_year'] == "2022") {echo 'selected="selected"';}?>>2022</option>
														<option value="2023" <?php if($get_session_form_data['expiration_year'] == "2023") {echo 'selected="selected"';}?>>2023</option>
														<option value="2024" <?php if($get_session_form_data['expiration_year'] == "2024") {echo 'selected="selected"';}?>>2024</option>
														<option value="2025" <?php if($get_session_form_data['expiration_year'] == "2025") {echo 'selected="selected"';}?>>2025</option>
														<option value="2026" <?php if($get_session_form_data['expiration_year'] == "2026") {echo 'selected="selected"';}?>>2026</option>
														<option value="2027" <?php if($get_session_form_data['expiration_year'] == "2027") {echo 'selected="selected"';}?>>2027</option>
														<option value="2028" <?php if($get_session_form_data['expiration_year'] == "2028") {echo 'selected="selected"';}?>>2028</option>
													</select>
												</li>
											</ul>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3"><h4 class="pt-2">Security code:</h4></div>
										<div class="col-md-9">
											<ul>
												<li><input type="password" value="{{$get_session_form_data['cvv_no']}}" class="cc-cvc" name="cvv_no" maxlength="4" placeholder="Code" id="cvv_no"></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
	
							<div id="pane-B" class="tab-pane fade" role="tabpanel" aria-labelledby="tab-B">
								<div class="row">
									<div class="col-6 col-sm-3"><h4 class="text-right p-0">Pay By Cheque/Cash:</h4></div>
									<div class="col-6 col-sm-9 paybycheck">
										
										<label class="checkbox-de custom_chksbox">
					                        <input type="checkbox" class="pay_by_chk" name="pay_by_check" value="0" checked="checked">
					                        <span class="checkmark"></span>
					                      </label>
									</div>
								</div>
							</div>
							
							
							<div class="billing_infos">
									<div class="row">
										<div class="col-md-12">
											<h3>Billing Information</h3>
										</div>
									</div>
									<?php
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
									<div class="row">
										<div class="col-md-3"><h4 class="pt-2">First:</h4></div>
										<div class="col-md-9">
											<div class="row">
												<div class="col-md-5">
													<input type="text" class="first_name" name="first_name" value="{{ $get_session_form_data['first_name'] ?? $first_name}}" placeholder="First Name">
												</div>
												<div class="col-md-7">
													<div class="row">
														<div class="col-md-3"><h4 class="pt-2">Last:</h4></div>
														<div class="col-md-9">
															<input type="text" class="last_name" name="last_name" value="{{ $get_session_form_data['last_name'] ?? $last_name }}" placeholder="Last Name">
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3"><h4 class="pt-2">Address:</h4></div>
										<div class="col-md-9">
											<input type="text" class="address" name="address" value="{{ $get_session_form_data['address'] }}" placeholder="Street Address">
											<input type="text" class="floor" name="floor" value="{{ $get_session_form_data['floor'] }}"  placeholder="Apt / Floor (optional)">
										</div>
									</div>
									<div class="row">
										<div class="col-md-3"><h4 class="pt-2">City:</h4></div>
										<div class="col-md-9">
											<div class="row">
												<div class="col-md-5">
													<input type="text" class="city" name="city" value="{{ $get_session_form_data['city'] }}" placeholder="City">
												</div>
												
												<div class="col-md-7">
													<div class="row">
														<div class="col-md-3 ds-cust-space"><h4 class="pt-2">Country:</h4></div>
														<div class="col-md-9">
															<select name="country" class="country">
																	<option value="">Select Country</option>
																@if(isset($country) && count($country) >0)
																	@foreach($country as $key=>$val)
																	 <?php
																	 $selected_country="";
																	 if($get_session_form_data['country'] ==  $val['id'])
																	 {
																	 	$selected_country= 'selected="selected"';
																	 }?>
																	<option value="{{$val['id']}}" <?php echo $selected_country; ?>>{{$val['name']}}</option>
																	@endforeach
																@endif
															</select>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3"><h4 class="pt-2">Zip/Postal Code:</h4></div>
										<div class="col-md-9">
											<div class="row">
												<div class="col-md-5">
													<input type="text" class="zipcode number" value="{{ $get_session_form_data['zipcode'] }}" maxlength="5" class="zipcode" name="zipcode" placeholder="Zip/Postal Code">
												</div>
												<div class="col-md-7">
													<div class="row">
														<div class="col-md-3 ds-cust-space"><h4 class="pt-2">State:</h4></div>
														<div class="col-md-9">
															<select class="dynamic_state state_dropdown" name="state">
																<option value="" id="select_state">Select State</option>
																@if(isset($state) && count($state) >0)
																	@foreach($state as $key=>$val)
																	 <?php
																	 $selected_state="";
																	 if($get_session_form_data['state'] ==  $val['id'])
																	 {
																	 	$selected_state= 'selected="selected"';
																	 }?>
																	<option value="{{$val['id']}}" <?php echo $selected_state; ?>>{{$val['name']}}</option>
																	@endforeach
																@endif
															</select>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3"><h4 class="pt-2">Phone:</h4></div>
										<div class="col-md-9">
											<input type="text" class="phone_no number" value="{{ $get_session_form_data['phone_no'] }}" name="phone_no" placeholder="Phone">
											<input type="hidden" id="event_publish_url" name="event_publish_url" value="{{$publish_url}}">
											<input type="hidden" name="event_id" value="{{$my_experience[0]->event_id ?? '0'}}">
											<input type="hidden" name="to_stripe_user_id" value="{{$stripe_user_ids ?? ''}}">
										</div>
									</div>
									
									@if(!Auth::guard('site')->check())
									<div class="row">
										<div class="col-md-3"><h4 class="pt-2">Email:</h4></div>
										<div class="col-md-9">
											<input type="email" id="email" name="email" value="{{$email ?? ''}}" placeholder="Email">
											<input type="hidden" id="already_fynches_user" name="already_fynches_user" value="{{$user_details->id ?? '0'}}">
										</div>
									</div>
									<div class="row">
										<div class="col-md-3"><h4 class="pt-2">Confirm Email:</h4></div>
										<div class="col-md-9">
											<input type="email" id="confirm_email" name="confirm_email" value="{{$email ?? ''}}" placeholder="Confirm Email">
										</div>
									</div>
									<div class="row">
										<div class="col-md-3"><h4 class="pt-2">Password:</h4></div>
										<div class="col-md-9">
											<input type="password" id="password" name="password" placeholder="Password">
										</div>
									</div>
									<div class="row">
										<div class="col-md-3"><h4 class="pt-2">Confirm Password:</h4></div>
										<div class="col-md-9">
											<input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password">
										</div>
									</div>
								
									@else
									<input type="hidden" id="already_fynches_user" name="already_fynches_user" value="{{Auth::guard('site')->id() ?? '0'}}">
									@endif
								</div>
							
						</div>
					</div>
				</div>
				
				@if(!Auth::guard('site')->check())
					<div class="row pt-4">
						<div class="col-md-9 offset-md-3">
							<p>Already have a Fynches account?<a href="javascript:void(0)"  data-toggle="modal" data-target="#login">Sign in</a></p>
						</div>
					</div>
				@endif
			</div>
		{!! Form::close() !!}
			
			<div class="review-btn">
				<div class="row">
					<div class="col-md-12">
						
						<a href="javascript:void(0)" class="commont-btn review_confirm">REVIEW &amp; CONFIRM</a>
					</div>
				</div>
			</div>
	</div>
</section>

{{Html::script("/front/common/event/event_checkout.js")}}
{{Html::script("/front/common/event/jquery.payment.js")}}

@endsection