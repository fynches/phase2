<div class="custom-exp">
	{!! Form::open(array('url'=>'/create-experience', 'class'=>'form-horizontal','method'=>'POST','id'=>'experience_create','files'=>true)) !!}
        <div class="row">
            <div class="col-sm-12">
                <h4 class="ds-title">Add a custom experiences</h4></div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <input type="text" name="experience_custom_url" id="experience_custom_url" class="form-control" placeholder="Enter the Yelp URL">
                </div>
                <p class="center" style="text-align: left;"> ( <b>Example: </b> https://www.yelp.com/biz/the-beehive-san-francisco-2?osq=Bars )</p>
            </div>
        </div>
        <div id="modal-loader" class="scrap_loader"  style="display: none; text-align: center;">
            <img src="{{ asset('assets/global/img/loading.gif') }}">
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="upload" id="exp_upload">
                    <label>
                        <input id="exp_image" name="exp_image" class="input-file experience_img_preview" type="file">
                        <i class="fa fa-camera" aria-hidden="true"></i>
                        <input name="yelpimage" id="yelpimage" type="hidden" val="">
                    </label>
                </div>
            </div>
            <div class="col-sm-9">
                <div class="form-group">
                    <input type="text" name="exp_name" id="exp_name" class="form-control" placeholder="Experience title" maxlength="120">
                </div>
                <div class="form-group">
                    <textarea class="form-control" maxlength="240" rows="4" id="description" name="description" placeholder="Experience description"></textarea>
                    <div id="create_exp_charnum">0 / 240 characters remaining</div>
                </div>
                <div class="gft-amount">
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="form-group">
                                <input type="text" name="gift_needed" id="gift_needed" class="form-control number" placeholder="Gift needed">
                                <span>$</span>
                            </div>
                        </div>
                        <input type="hidden" name="event_id" id="event_id" value="{{ $event_id or '0'}}">
                        <div class="col-sm-5"><a href="javascript:void(0)" id="add_experience" class="commont-btn">ADD</a></div>
                    </div>
                </div>
            </div>
        </div>
    {!! Form::close() !!}
</div>

<div class="search-box">
    <div class="row">
        <div class="col-sm-12">
            <h4 class="ds-title">Search and Add</h4></div>
    </div>
    <div class="row">
        <div class="col-sm-12">
        	{!! Form::open(array('url'=>'/search-experience', 'class'=>'form-horizontal','method'=>'POST','id'=>'search_exps','files'=>true)) !!}
            <div class="ds-search">
                <div class="form-group search location">
                    <!-- <input type="text" class="form-control search_experience_name" name="search_experience_name" placeholder="Search for an experience"> -->
                    <input type="text" class="form-control search_experience_name" id="search_for_an_experience" name="search_experience_name" placeholder="Search for an experience">
                </div>
                <div class="form-group location">
                	<i class="fa fa-location-arrow" aria-hidden="true"></i>
                	 <input id="searchInput" name="searchInput" class="form-control" type="text" value="{{$current_location ?? ''}}" placeholder="Current location">
                	 <div style="display: none;" class="map" id="map" style="width: 100%; height: 300px;"></div>
                	<!-- <input type="text" class="form-control" id="location" name="location" value="{{$location ?? ''}}" placeholder="Current Location"> -->
                </div>
                
                <div class="form_area">
				     <input type="hidden" class="current_location_name" name="current_location_name" id="location" value="{{$current_location}}">
				     <!-- <input type="text" name="lat" id="lat">
				     <input type="text" name="lng" id="lng"> -->
				</div>
                <!-- <div class="form-group location">
                    <i class="fa fa-location-arrow" aria-hidden="true"></i>
                    <input type="text" class="form-control current_location_name" name="current_location_name" value="{{$current_location}}" placeholder="Current Location">
                </div> -->
                <button type="button" class="search_expers" value="Search" name="Search">
                    <i class="fa fa-search" aria-hidden="true"></i>Search
                </button>
            </div>
             {!! Form::close() !!}
        </div>
    </div>
    <div id="modal-loader" class="modal_loader"  style="display: none; text-align: center;">
        <img src="{{ asset('assets/global/img/loading.gif') }}">
    </div>
    <div class="row" style="display: none;">
        <div class="col-sm-12">
            <h3>Search results</h3>
            <p>Showing 6 of 120 results for "zoo"</p>
        </div>
    </div>
</div>

  
<div class="row cust-box show-search-exp">
	
</div>	

<div class="row hide" id="show_pagination">
    <div class="col-sm-12">
        <div class="showpaging">
            
        </div>
        <!-- <ul class="pagination">
            <li>
                <a href="#" aria-label="Previous">
                    <span aria-hidden="true"><img src="{{ asset('front/images/left-arrow.png') }}" alt="" title=""></span>
                </a>
            </li>
            <li class="active"><a href="#">Page 1</a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
            <li>
                <a href="#" aria-label="Next">
                    <span aria-hidden="true"><img src="{{ asset('front/images/right-arrow1.png') }}" alt="" title=""></span>
                </a>
            </li>
        </ul> -->
        <p class="pb-0 pt-2">Powered by Yelp</p>
    </div>
</div>

{{Html::script("/front/common/experience/search_experience.js")}}
