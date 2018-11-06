<?php
// My common functions
function pr($data)
{
    echo "<pre>";
    return print_r($data);
}
$defaultPath = 'storage/avatar.jpg';


if(!function_exists('printCategory')){
	function printCategory($categories, $level=0, $user_id=0) {			
		//echo $user_id;	
		if(count($categories))
		{
		
			foreach($categories as $c)
			{	
				//pr($c);exit;
				//echo "<li>" . $c['comment'];
				echo '<div class="addcmt comment-item" style="padding-left:'.$level.'px">
					<div class="row pb-4">
						<div class="col-md-2 col-sm-3">
							<div class="circle-img">
								<img class="circle-img" src="../storage/avatar.jpg" alt="" title="">
							</div>
						</div>
						<div class="col-md-10 col-sm-9">
							<h4>'.$c['name'].'</h4>
							<p>'.$c['comment'].'</p>
							<ul>
								<li>'.$c['human_date'].'</li>
								<li><a href="javascript:void(0)" class="replay-comment">Reply</a></li>';
								if($c['user_id'] == $user_id){
									echo '<li><a href="javascript:void(0)" onClick="DeleteComment('.$c['id'].')" class="delete-comment">Delete</a></li>';
								}								
							echo '</ul>
						</div>
					</div>
				</div>';
				echo '<div class="replaycmt hide">
					<div class="row pb-4">
						<div class="col-sm-12">
							<div class="row">
								<div class="col-md-2 col-sm-3">
									<div class="circle-img">
										<img class="circle-img" src="../storage/avatar.jpg" alt="" title="">
									</div>
								</div>
								<div class="col-md-10 col-sm-9">
									<div class="form-group">
										<textarea class="form-control comment-desc" rows="5" data-parent="'.$c['id'].'" placeholder="Add your replay" name="comment_description"></textarea>';
										
										if (Auth::guard('site')->id()){
											echo '<a href="javascript:void(0)" class="commont-btn add-comment">ADD COMMENT</a>';
										}else {
											echo '<a href="javascript:void(0)" class="commont-btn" data-toggle="modal" data-target="#login">ADD COMMENT</a>';
										}
										echo '<label>1000 characters remaining</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>';	
				//Rest of what ever you want to do with each row

				if (isset($c['children'])) {
					printCategory($c['children'], $level+50, $user_id);
				}
				//echo "</li>";
				
			}
			//echo "</ul>";

			// /return $html;
		}
	}
}
?>