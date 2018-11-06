<!-- Forgot Password Modal -->
<div class="modal fade" id="banking-information" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
      				
      					<div class="form-group my_content">
		                  <h4>You have added banking information on previous event. below are details..</h4>
		                  <h4>Stripe User Id : {{$user_last_event_stripe_id}}</h4>
		                  <h5> If you want to add new banking information then <a href="{{$stripe_url}}">click here</a></h5>
		                  
		                  <label id="not-exits-email-id" class="custom_error" for="invalid-cridentials" style="display: none;">Email id not found.</label>
		                  <label id="inactive-email-id" class="custom_error" for="invalid-cridentials" style="display: none;">You are Inactive by Admin. Please contact to Admin.</label>
		                </div>
		                
		                <input type="button" class="submit_btns hide" name="" value="Save Event" id="bank_submit_info">
		                <input type="button" class="cancel_btns" name="" value="Ok" id="bank_info">
		           		
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
