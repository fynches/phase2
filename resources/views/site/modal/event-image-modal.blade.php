<!-- Upload photos Modal from local or facebook -->
<div class="modal fade" id="upload_photos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title">Upload Photos</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<div id="modal-loader"  style="display: none; text-align: center;">
            <img src="{{ asset('assets/global/img/loading.gif') }}">
        </div>
      	<div class="login">
      		<div class="row">
      			<div class="col-sm-12">
      				<form id="popup_uploads" method="POST" action="">
      					
      					<label class="ds-radio">Images
						  <input type="radio" checked="checked" name="upload_section" id="upload_image_section" value="images">
						  <span class="checkmark"></span>
						</label>
						
						<label class="ds-radio">Video
						  <input type="radio" name="upload_section" id="upload_video_section" value="video">
						  <span class="checkmark"></span>
						</label>
						
      					<!-- <input type="radio" name="upload_section" id="upload_section" value="images">Images
      					<input type="radio" name="upload_section" id="upload_section" value="video">Video -->
      					
      					<div class="form-group" id="event_img_section">
      					 <input type="button" class="local_upload" name="image[]" value="Upload From Computer">
		                   <p> OR </p> 
  						  <input type="button" id="facebook_photose" onclick="logInWithFacebook()" value="Upload With Facebook">
  						  <!-- <a  href="{{ url('redirect/facebook/login/event') }}" target="_blank">Upload WIth Facebook</a> -->
		                </div>
		                
		                <div class="form-group" id="event_video_section" style="display:none;">
      					 <!-- <input type="file" class="local_upload_video" name="video_name" id="video_name"> -->
      					 <input type="button" class="local_upload" name="image[]" value="Upload From Computer">
      					
		                   <p> OR Youtube Url </p> 
		                    <div class="form-group">
			                  <input type="text" name="youtube_url" id="youtube_url" value="" class="form-control" placeholder="Enter Youtube Url">
			                </div>
			                <input type="button" name="" value="Submit" id="event_upload_popup">	
		                </div>
		               
		            </form>
      			</div>
      		</div>
      	</div>
      </div>
    </div>
  </div>
</div>
<!--- End -->