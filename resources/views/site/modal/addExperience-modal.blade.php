<!-- Event Modal -->
<div class="modal fade" id="cre-event" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title">Choose an Event to add your selected Experience(s)</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<ul>
      		@if(isset($user_events) && count($user_events) >0)
      			@foreach($user_events as $key=>$val)
		      		<li>
		      			<label class="md-radio">{{$key}}
						  <input type="radio" <?php echo $loop->index===0 ? 'checked="checked"': '';?> name="event_id" value="{{$val}}" name="radio2">
						  <span class="checkmark"></span>
						</label>
		      		</li>
      			@endforeach
      		@endif	
      	</ul>
      	<label class="md-radio"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Create New Event
		  <input type="radio" name="event_id" value="0">
		  <input type="hidden" class="yelp_id" name="yelp_id" value="0">
		  <span class="checkmark"></span>
		</label>
		<a href="javascript:void(0)" class="commont-btn add_new_experience" data-toggle="modal" data-target="#cre-event">NEXT</a>
      </div>
    </div>
  </div>
</div>


<!--- End -->
