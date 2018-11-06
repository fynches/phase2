<!-- Forgot Password Modal -->
<div class="modal fade" id="add-banking-info" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title">Banking Information</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<div id="modal-loader" class="modal_loader"  style="display: none; text-align: center;">
            <img src="{{ asset('assets/global/img/loading.gif') }}">
        </div>
      	<div class="login">
      		<div class="row">
      			<div class="col-sm-12">
      					<?php
				      		$stripe_client_id="";
							$stripe_url="";
							if(count($global_data) > 0)
							{
								$stripe_client_id= $global_data->stripe_client_id;
								$stripe_url= "https://connect.stripe.com/oauth/authorize?response_type=code&client_id=".$stripe_client_id."&scope=read_write";
							}
							//echo "@@@@@@@@@".$stripe_user_id;
				      	?>
      					<div class="form-group">
		                  @if($stripe_user_id!="0")
		                  <h4>You have already added banking information. below are details.</h4>
		                  <h4>Stripe User Id : {{$stripe_user_id}}</h4>
		                  @endif
		                  @if($stripe_user_id=="0")
		                  	<h5>You have not added banking information yet, please click below url for added banking information.</h5>
		                  	<a href="{{$stripe_url ?? ''}}">Click here</a>
		                  @else
		                  	<h5> If you want to add new banking information then <a href="{{$stripe_url ?? ''}}">click here</a></h5>
		                  	<a href="{{$stripe_url ?? ''}}">click here</a>
		                  @endif
		                  <label id="not-exits-email-id" class="custom_error" for="invalid-cridentials" style="display: none;">Email id not found.</label>
		                  <label id="inactive-email-id" class="custom_error" for="invalid-cridentials" style="display: none;">You are Inactive by Admin. Please contact to Admin.</label>
		                </div>
		                <input type="button" name="" value="Cancel" id="bank_info">
		           
      			</div>
      		</div>
      	</div>
      </div>
    </div>
  </div>
</div>
<!--- End -->

<script type="text/javascript">

$(document).on('click', '#bank_info', function(e) {
	$('#banking-information').modal('hide');
});
</script>
