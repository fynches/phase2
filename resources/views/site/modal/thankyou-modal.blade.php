<div class="modal fade" id="feedback" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title">Send a Thank You!</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	{!! Form::open(array('url'=>'/send-thankyou', 'class'=>'form-horizontal send-thankyou','method'=>'POST','id'=>'send-thankyou-form','files'=>true)) !!}
			<div class="form-group">
				<input type="email" name="email" class="form-control to_user_id" value="" id="email" placeholder="User email">
            </div>
            <div class="form-group">
              <input type="text" name="subject" id="subject" class="form-control" placeholder="Subject" value="{{ 'Thank you for funding' }}">
            </div>
            <div class="form-group">
			    <textarea class="form-control" id="description" name="description" rows="6" placeholder="Type here…"></textarea>
			    <input type="hidden" name="funding_report_id" class="funding_report_id" value="">
			</div>
			<input type="submit" name="send" value="SEND">
		{!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
