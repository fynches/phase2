<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Auth;

class ActivityLog extends Model
{
    protected $table='activity_log';
	
	
    public static function createlog($uId,$module,$msg,$userType) {
    	// DB::table('activity_log')->insert([
    		// [
    			// 'user_id' 	=> $uId,
    			// 'details' 	=> $msg,
    			// 'ip' 	  	=> $_SERVER['REMOTE_ADDR'],    		
    		// ],    		
		// ]);  
		
		 $activity_log = new ActivityLog;
         $activity_log->user_id = $uId;
         $activity_log->details = $msg;
		 $activity_log->ip = $_SERVER['REMOTE_ADDR'];
         $activity_log->save();
         return $activity_log;   	
    }
}
