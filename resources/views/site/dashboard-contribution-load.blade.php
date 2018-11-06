<div class="col-md-12">
	@if(count($user_contribution) > 0)
		@foreach($user_contribution as $key=>$val)
			<?php
				$contribution_amount="0";
				$donated_date="";
				
				if(isset($val->getEventMappingMdedia) && count($val->getEventMappingMdedia) > 0)
				{
					//pr($val->getEventMappingMdedia[0]->image);die;
					if($val->getEventMappingMdedia[0]->image_type=="2") //facebook images
					{
						$imgPath = $val->getEventMappingMdedia[0]->image;	
					}
					else if($val->getEventMappingMdedia[0]->image_type=="0")
					{
						$event_image = $val->getEventMappingMdedia[0]->image;
									
						if ($event_image && $event_image != "") {
							$imgPath = 'storage/Event/' . $event_image;
							
							if (file_exists($imgPath))
	                        {
	                            $imgPath = $imgPath;
	                        } else {
	                            $imgPath = $defaultPath;
	                        }
						}else{
							$imgPath = $defaultPath;
						}
					}
				}
				
			?>
			<div class="card">
                <div class="row">
                    <div class="col-md-4 col-lg-3">
                        <div class="card-header">
                            <img src="{{ asset($imgPath) }}" class="w-100">
                            <span>UNPUBLISHED</span>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-9">
                        <div class="card-body ds-inner-board">
                            <h4>{{ $val->title }}</h4>
                           	@if(isset($val->FundingReport))
                           		@foreach($val->FundingReport as $key4=>$val4)
									<?php
										if(isset($val4->created_at))
										{
											$donated_date= date("m/d/Y", strtotime($val4->created_at));
										}
										
										if(isset($val4->getEventExperiences))
										{
											$exp_name= $val4->getEventExperiences->exp_name;
											$donated_amount= $val4->donated_amount;
										}else{
											$exp_name="Additional Amount";
											$donated_amount= $val4->bonus_amount;
										}
										$contribution_amount += $donated_amount;
										//pr($val4->getEventExperiences->toArray());die;
									?>
									<p>{{ $exp_name }} : Contributed ${{$donated_amount}} on {{$donated_date}}</p>
								@endforeach
							@endif
                            <h5 class="float-right text-right"><span>Total:</span> ${{$contribution_amount}}</h5>
                        </div>
                    </div>
                </div>
            </div>
		@endforeach
	@endif
</div>

<div class="contribution_pagination">
	{!! $user_contribution->render('pagination.fynches') !!}
</div>