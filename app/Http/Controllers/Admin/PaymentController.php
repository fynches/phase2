<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Session;
use Auth;
use App\GlobalSetting;
use App\ActivityLog;
use App\FundingReport;
use App\Event;
use App\Experience;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Input;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Laravel\Socialite\Facades\Socialite;
use Yajra\Datatables\Datatables;

class PaymentController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }
	
	public function index() {
    	return view('admin/Payment/index', ['title_for_layout' => 'List Funding Reports']);
    } 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     
    // added by devang mavani for display global setting 
    public function getData() {
    	
		
		$start = $_GET['start'];
         
		return Datatables::of(FundingReport::groupby('funding_report.id')
                                ->join('event', 'event.id', '=', 'funding_report.event_id')
                                ->leftjoin('experience', 'experience.id', '=', 'funding_report.experience_id')
								->join('users', 'users.id', '=', 'funding_report.user_id')
                                ->select('funding_report.id', 'funding_report.donated_amount', 'funding_report.bonus_flag', 'funding_report.bonus_amount',
                                 'funding_report.transaction_id','funding_report.make_annoymas','event.title','experience.exp_name','users.first_name','users.last_name')
                                //->where('funding_report.status', "Active")
                        )
                        ->addColumn('srno', $start + 1)
                        ->make(true);
    }
	
	public function show($id) {

        $funding_report = FundingReport::groupby('funding_report.id')
                                ->join('event', 'event.id', '=', 'funding_report.event_id')
                                ->leftjoin('experience', 'experience.id', '=', 'funding_report.experience_id')
								->join('users', 'users.id', '=', 'funding_report.user_id')
                                ->select('funding_report.id', 'funding_report.donated_amount', 'funding_report.bonus_flag', 'funding_report.bonus_amount',
                                 'funding_report.transaction_id','funding_report.make_annoymas','event.title','experience.exp_name','users.first_name','users.last_name','funding_report.status','funding_report.description')
								 ->where('funding_report.id',$id)->first();
		//pr($funding_report->toArray());die;	
        if (empty($funding_report)) {
            Session::flash('error_msg', 'Funding Reports not found.');
            return redirect('/admin/payment');
        }
        return view('admin/Payment/show', ['title_for_layout' => 'Funding Reports Details',
            'funding_report' => $funding_report]);
    }
}
