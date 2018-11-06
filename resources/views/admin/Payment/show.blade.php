@extends('layouts.admin')
@section('content')
<div class="portlet light">
    <div class="portlet-title">
        <div class="caption caption-md">
            <i class="icon-globe theme-font hide"></i>
            <span class="caption-subject font-blue-madison bold uppercase">Funding Report Details</span>
        </div>
    </div>
    <div class="form-horizontal">
        <div class="form-group">
            <label class="col-lg-2 control-label">Id :</label>
            <div class="col-lg-10">
            	<p class="form-control-static"><?php echo $funding_report->id; ?></p>
            </div>
        </div>
        <div class="line line-dashed line-lg pull-in"></div>
        <div class="form-group">
            <label class="col-lg-2 control-label">Event Title :</label>
            <div class="col-lg-10">
                <p class="form-control-static"><?php echo $funding_report->title; ?></p>
            </div>
        </div>
        <div class="line line-dashed line-lg pull-in"></div>
        <div class="form-group">
            <label class="col-lg-2 control-label">Experience Name :</label>
            <div class="col-lg-10">
                <p class="form-control-static"><?php echo $funding_report->exp_name; ?></p>
            </div>
        </div>
        
        
        <div class="line line-dashed line-lg pull-in"></div>
        <div class="form-group">
            <label class="col-lg-2 control-label">Donated User Name :</label>
            <div class="col-lg-10">
                <div class="form-control-static"><?php echo $funding_report->first_name.' '.$funding_report->last_name; ?></div>
            </div>
        </div> 
        
        <div class="line line-dashed line-lg pull-in"></div>
        <div class="form-group">
            <label class="col-lg-2 control-label">Donated Amount :</label>
            <div class="col-lg-10">
                <div class="form-control-static">$<?php echo $funding_report->donated_amount; ?></div>
            </div>
        </div>
        
        <div class="line line-dashed line-lg pull-in"></div>
        <div class="form-group">
            <label class="col-lg-2 control-label">Additional Amount :</label>
            <div class="col-lg-10">
                <div class="form-control-static">$<?php echo $funding_report->bonus_amount; ?></div>
            </div>
        </div> 
        
        <div class="line line-dashed line-lg pull-in"></div>
        <div class="form-group">
            <label class="col-lg-2 control-label">Transaction Id :</label>
            <div class="col-lg-10">
                <div class="form-control-static"><?php echo $funding_report->transaction_id; ?></div>
            </div>
        </div> 
        
        <div class="line line-dashed line-lg pull-in"></div>
        <div class="form-group">
            <label class="col-lg-2 control-label">Status :</label>
            <div class="col-lg-10">
                <div class="form-control-static"><?php echo $funding_report->status; ?></div>
            </div>
        </div> 
        
        <div class="line line-dashed line-lg pull-in"></div>
        <div class="form-group">
            <label class="col-lg-2 control-label">Make Annoymas :</label>
            <div class="col-lg-10">
                <div class="form-control-static"><?php echo $funding_report->make_annoymas; ?></div>
            </div>
        </div> 
        
        <div class="line line-dashed line-lg pull-in"></div>
	    <div class="form-group">
	        <label class="col-lg-2 control-label">Description:</label>
	        <div class="col-lg-10">
	            <p class="form-control-static"> {!!html_entity_decode($funding_report->description)!!} </p>
	        </div>
	    </div> 

        <div class="line line-dashed line-lg pull-in"></div>
        <div class="form-group">
        <label class="col-lg-2 control-label"></label>
        <div class="col-lg-10">
            <a class="btn btn-default btn-sm btn-dark" href="{{ URL::to('/admin/payment/') }}">Cancel</a>
        </div>
    </div>
        
        
    </div>
 </div>   
@endsection