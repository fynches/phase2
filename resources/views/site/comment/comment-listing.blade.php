<?php $defaultPath = 'storage/avatar.jpg';?>
@if(isset($my_experience) && count($my_experience) > 0 && count($my_experience['getEventExperiences']) > 0)		
<div class="comment-box">
	<div class="row">
		<div class="col-md-12">
			<div class="brd-title">
				<h3>Comments</h3>
			</div>
		</div>
	</div>
	
	@if(count($comments) > 0)
	<div class="row">
		<div class="col-sm-12">
			<div class="addcmt border-btm">
				{!! Form::open(array('url'=>'/add-comment', 'class'=>'form-horizontal my_cmmt_frmss','method'=>'POST','id'=>'comment_forms')) !!}
				 	<div class="row pb-4">
				 		<div class="col-sm-2">
				 			<div class="circle-img">
				 				<img class="circle-img" src="{{ asset($defaultPath) }}" alt="" title="">
				 			</div>
				 		</div>
				 		<div class="col-sm-10">
				 			<div class="form-group">
							    <textarea class="form-control comment-desc" rows="5" id="comment_description"  name="comment_description" placeholder="Add a comment" maxlength="1000"></textarea>
							    <a href="javascript:void(0)" class="commont-btn add-comment">ADD COMMENT</a>
							    <label class="custom_msgs">1000 characters remaining</label>
							    <input type="hidden" id="event_id" name="event_id" value="{{ $event_id or '' }}">
							</div>
				 		</div>
				 	</div>
				 {!! Form::close() !!}	
			</div>
			<div class="comment-loop"> 
			<?php 
			printCategory($tree, 0, Auth::guard('site')->id());
			?>
			</div>
			<div class="load-html"></div>			
		</div>
	</div>
	
	@else
		<div class="row">
            <div class="col-sm-12">
            	{!! Form::open(array('url'=>'/add-comment', 'class'=>'form-horizontal joy','method'=>'POST','id'=>'comment_new_forms')) !!}
                    <div class="addcmt mb-4">
                        <div class="row">
                        	
                            <div class="col-sm-2">
                                <div class="circle-img">
                                	<img class="circle-img" src="{{ asset($defaultPath) }}" alt="" title="">
                                </div>
                            </div>
                            
                            <div class="col-sm-10 ">
								<div class="form-group">
									<textarea class="form-control comment-desc" rows="3" id="comment_description"  name="comment_description" placeholder="Add a comment" maxlength="1000"></textarea>
                                    <a href="javascript:void(0)" class="commont-btn add-comment">ADD COMMENT</a>
                                    <label class="custom_msgs" style="padding: 0px !important;">1000 characters remaining</label>
									<input type="hidden" name="event_id" value="{{ $event_id or '' }}">
								</div>
							</div>
                        </div>
                    </div>
                {!! Form::close() !!}	
                <div class="comment-loop">
					<h3 class="mb-0 comment">No comments yet</h3>
				</div>
				<div class="load-html"></div>
            </div>
        </div>
	 @endif
	
	@if($comments->hasMorePages())
	<div class="load-more-div">
		<a href="javascript:void(0);" class="load-more">Load more</a>
	</div>
	@endif
</div>
@endif