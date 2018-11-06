<?php

namespace App\Http\Controllers\Site;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Offer;
use App\Company;
use App\Templates;
use DB;
use Session;

use Route;
use App\ActivityLog;
use Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Laravel\Socialite\Facades\Socialite;
use Yajra\Datatables\Datatables;
use App\Site;
use App\Event;
use App\Experience;
use App\FundingReport;
use App\Testimonial;
use App\StaticBlock;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    	$this->middleware('guest:site');
    }

   /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    	$data= $request->all();
		//pr($data);die;
    	$static_block = StaticBlock::all();
		$testimonails= Testimonial::where('status', "Active")->get();
		$user_id = Auth::guard('site')->id();
    	$users = Auth::guard('site')->user();
		$user_contribution=array();
		$get_user_events=array();
		
		$add_condition="";
		
		if(isset($data['search_title']) && $data['search_title']!="")
		{
			$title= $data['search_title'];
			$user_events =  Event::with(['getEventExperiences' => function ($q) use ($user_id){
                    $q->select('id','event_id','exp_name','description','image','experience_from','gift_needed','status');
                }])->with(['getEventMappingMdedia' => function ($q) {
		            $q->select('video', 'image','image_type','id','event_id','flag_video');
		        }])->with(['FundingReport' => function ($q) use ($user_id){
                	$q->select('*');
					//$q->where('user_id','!=',$user_id);
				}])->select('*')->where('user_id',$user_id)->whereIn('status', array('1', '4'))->where('title', 'like', '%' . $title . '%')
				->orderBy('created_at', 'desc')
				->paginate(5);
		}else{
			$user_events =  Event::with(['getEventExperiences' => function ($q) use ($user_id){
                    $q->select('id','event_id','exp_name','description','image','experience_from','gift_needed','status');
                }])->with(['getEventMappingMdedia' => function ($q) {
		            $q->select('video', 'image','image_type','id','event_id','flag_video');
		        }])->with(['FundingReport' => function ($q) use ($user_id){
                	$q->select('*');
					//$q->where('user_id','!=',$user_id);
				}])->select('*')->where('user_id',$user_id)->whereIn('status', array('1', '4'))
				->orderBy('created_at', 'desc')
				->paginate(5);
		}
		
				
		
		$user_contribution = Event::whereHas('FundingReport' , function ($q) use ($user_id){
                    $q->select('id','event_id','user_id','experience_id','description','donated_amount','status');
					$q->where('user_id',$user_id);
					//$q->where('status','succeeded');
					$q->whereIn('status',array('succeeded','pending'));
				})->with(['getEventMappingMdedia' => function ($q) {
		            $q->select('video', 'image','image_type','id','event_id','flag_video');
		        }])->with(['FundingReport' => function ($q) use ($user_id){
                	$q->select('*');
					$q->where('user_id','=',$user_id);

					$q->with('getEventExperiences');
				}])->select('id', 'title')->whereIn('status', array('1', '4'))
				->orderBy('created_at', 'desc')
				->paginate(2);	
		//pr($user_contribution);
				
		if ($request->ajax()) {
			  if($request->type!="" && $request->type=="event")
			  {
			  	return view('site.dashboard-load',compact('user_events','user_contribution','static_block','testimonails'));	
			  }else{
			  	return view('site.dashboard-contribution-load',compact('user_events','user_contribution','static_block','testimonails'));
			  }
              
        }
		
		return view('site.dashboard',compact('user_events','user_contribution','static_block','testimonails'));
		
		//pr($get_user_events->toArray());die;
		//return view('site.dashboard', ['users' => $users,'user_events'=>$get_user_events,'user_contribution' =>$user_contribution,'static_block' => $static_block, 'testimonails' => $testimonails]);	
	} 
	
	public function show()
	{
		$users = Auth::guard('site')->user();
		return view('site.dashboard', ['users' => $users]);
	}   
    
}
