@extends('layouts.admin')
@section('content')
<div class="portlet light">
    <div class="portlet-title">
        <div class="caption caption-md">
            <i class="icon-globe theme-font hide"></i>
            <span class="caption-subject font-blue-madison bold uppercase">Country Details</span>
        </div>
    </div>
    <div class="form-horizontal">
        <div class="form-group">
            <label class="col-lg-2 control-label">Id :</label>
            <div class="col-lg-10">
                <p class="form-control-static"><?php echo $country->id; ?></p>
            </div>
        </div>
        <div class="line line-dashed line-lg pull-in"></div>
        <div class="form-group">
            <label class="col-lg-2 control-label">Country Name :</label>
            <div class="col-lg-10">
                <p class="form-control-static"><?php echo $country->name; ?></p>
            </div>
        </div>
        
	    
	    

        <div class="line line-dashed line-lg pull-in"></div>
        <div class="form-group">
        <label class="col-lg-2 control-label"></label>
        <div class="col-lg-10">
            <a class="btn btn-default btn-sm btn-dark" href="{{ URL::to('/admin/country/') }}">Cancel</a>
        </div>
    </div>
        
        
    </div>
 </div>   
@endsection