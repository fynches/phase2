@extends('layouts.admin')
@section('content')
<div class="portlet light">
    <div class="portlet-title">
        <div class="caption caption-md">
            <i class="icon-globe theme-font hide"></i>
            <span class="caption-subject font-blue-madison bold uppercase">EmailTemplate Details</span>
        </div>
    </div>
    <div class="form-horizontal">
        <div class="form-group">
            <label class="col-lg-2 control-label">Id :</label>
            <div class="col-lg-10">
                <p class="form-control-static"><?php echo $EmailTemplates->id; ?></p>
            </div>
        </div>
        <div class="line line-dashed line-lg pull-in"></div>
        <div class="form-group">
            <label class="col-lg-2 control-label">Title :</label>
            <div class="col-lg-10">
                <p class="form-control-static"><?php echo $EmailTemplates->subject; ?></p>
            </div>
        </div>
        <div class="line line-dashed line-lg pull-in"></div>
        <div class="form-group">
            <label class="col-lg-2 control-label">Slug :</label>
            <div class="col-lg-10">
                <p class="form-control-static"><?php echo $EmailTemplates->slug; ?></p>
            </div>
        </div>
        <div class="line line-dashed line-lg pull-in"></div>
        <div class="form-group">
            <label class="col-lg-2 control-label">Content :</label>
            <div class="col-lg-10">
                <div class="form-control-static"><?php echo $EmailTemplates->content; ?></div>
            </div>
        </div> 

        <div class="line line-dashed line-lg pull-in"></div>
        <div class="form-group">
        <label class="col-lg-2 control-label"></label>
        <div class="col-lg-10">
            <a class="btn btn-default btn-sm btn-dark" href="{{ URL::to('/admin/emailtemplates/') }}">Cancel</a>
        </div>
    </div>
        
        
    </div>
 </div>   
@endsection