<!-- Forgot Password Modal -->
<div class="modal fade" id="forgotpassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title">Forgot Password</h2>
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
      				<form id="forgot_password" method="POST" action="">
      					{{ csrf_field() }}
      					<div class="form-group">
		                  <input type="text" id="forgot_email" name="forgot_email" class="form-control" autocomplete="off"  placeholder="Email">
		                  <label id="not-exits-email-id" class="custom_error" for="invalid-cridentials" style="display: none;">Email id not found.</label>
		                  <label id="inactive-email-id" class="custom_error" for="invalid-cridentials" style="display: none;">You are Inactive by Admin. Please contact to Admin.</label>
		                </div>
		                <input type="button" name="" value="Submit" id="forgot_pass">
		            </form>
      			</div>
      		</div>
      	</div>
      </div>
    </div>
  </div>
</div>
<!--- End -->
