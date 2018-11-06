<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\MappingEventMedia;
use App\MappingCustomTag;
use App\Experience;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Event extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table= 'events';
    protected $dates = ['deleted_at'];
    //protected $fillable = ['email'];
   
   //Event create while admin add new event 
    public static function eventcreate($data){
      $event = new Event;
      $event->user_id=$data['user_id'];
      $event->title=$data['title'];
      $event->description=$data['description'];
        $event->first_name=$data['first_name'];
        $event->last_name=$data['last_name'];
      $event->event_publish_date=date('Y-m-d',strtotime($data['event_publish_date'])).' 00:00:00';
      $event->event_end_date=date('Y-m-d',strtotime($data['event_end_date'])).' 00:00:00';
      $event->zipcode=$data['zipcode'];
      $event->status=$data['status'];
      $event->publish_url=$data['publish_url'];
      //$event->publish_url=$data['publish_url'];
      $event->save();
      //for child info
      $EventId = $event->id;
        $childInfo = new ChildInfo;
        $childInfo->event_id = $EventId;
        $childInfo->first_name = $data['child_name'];
        $childInfo->age_range = $data['age_range'];
        $childInfo->save();
 
      return $event;
    }

    //Event update while admin edit new event
    public static function eventupdate($data,$id=null){
       
      $event = Event::find($id);
      $event->user_id=$data['user_id'];
      $event->title=$data['title'];
      $event->description=$data['description'];
      $event->event_publish_date=date('Y-m-d',strtotime($data['event_publish_date'])).' 00:00:00';
      $event->event_end_date=date('Y-m-d',strtotime($data['event_end_date'])).' 00:00:00';
      $event->zipcode=$data['zipcode'];
      $event->status=$data['status'];
	  $event->publish_url=$data['publish_url'];
	  
	  $event->save(); 
      $EventId = $event->id;
       
      //Image name save in event mapping media
      $MappingEventMedia = array(); 
	  
      if($data['image_type']=="0"){ 
        if(isset($data['event_image'])){ 
          $MappingEventMedia = MappingEventMedia::where('event_id',$EventId)->get()->toArray();
          if(count($MappingEventMedia)>0){
            foreach($MappingEventMedia as $key=>$val){
              $destinationPath = 'storage/Videos/'; 
              if($val['video'] != '' && $val['image_type']=='1')
              {
                $name = $val['video'];
                if (file_exists($destinationPath . $name)) {
                    $unlink_success = File::delete($destinationPath . $name);
                } 
              }
            }
          }

    		  DB::table('mapping_event_media')->where('event_id', '=', $EventId)->delete();	
    		  foreach($data['event_image'] as $key=>$val){
    		  	$MappingEventMedia = new MappingEventMedia;
    		  	$MappingEventMedia->image = $val;
      			$MappingEventMedia->image_type = '0';
      			$MappingEventMedia->event_id = $EventId;
    		    $MappingEventMedia->status = 'Active'; 
    		    $MappingEventMedia->save();
    		  }          
        }  
      }else if($data['image_type']=="1"){ 
        $MappingEventMedia = MappingEventMedia::where('event_id',$EventId)->get()->toArray();
        if(count($MappingEventMedia)>0){
          foreach($MappingEventMedia as $key=>$val){
            $destinationPath = 'storage/Event/';
            $destinationThumbPath = 'storage/Event/thumb/'; 
            if($val['image'] != '')
            {
              $name = $val['image'];
              if (file_exists($destinationPath . $name)) {
                  $unlink_success = File::delete($destinationPath . $name);
              }
              if (file_exists($destinationThumbPath . $name)) {
                  $unlink_success = File::delete($destinationPath . $name);
              } 
            }
          }
        } 

        DB::table('mapping_event_media')->where('event_id', '=', $EventId)->delete(); 

        $MappingEventMedia = array('video'=>$data['video'],'image_type'=>'1','event_id'=>$EventId); 
        if($data['flag_video']=='1'){
          $MappingEventMedia['flag_video'] = '1';
        }
		    $MappingEventMedia = DB::table('mapping_event_media')->insert($MappingEventMedia);
      }
      //pr($MappingEventMedia);exit;
      if(isset($data['keywords_activities'])){
    	  if(count($data['keywords_activities']) > 0){
    	  	DB::table('mapping_custom_tag')->where('event_id', '=', $EventId)->delete();	
    	  	foreach($data['keywords_activities'] as $key=>$val){
      			$MappingTags = new MappingCustomTag;
      			$MappingTags->user_id=$data['user_id'];
      			$MappingTags->tag_id=$val;
      			$MappingTags->event_id = $EventId;
      			$MappingTags->save();
      		}
    	  }
      } 
      return $event;
    }

    //get User with compare to user_id
    function getUser(){ 
	    return $this->belongsTo('App\User','user_id','id');
	  }
    //get User with compare to id
	  public function users(){ 
	    return $this->hasOne('App\User','id','user_id');
	  }

    //get date of event media with mapping table
    function getEventMappingMdedia(){
      //return $this->belongsTo('App\MappingEventMedia','id','event_id');
      return $this->hasMany('App\MappingEventMedia');
    }

    //get fund report of event with fundereport table
    function getFundingReport(){ 
      return $this->belongsTo('App\FundingReport','id','experience_id');
    }	
	
    //get event tags which is selected for event
    function getEventTags(){
      //return $this->belongsTo('App\MappingEventMedia','id','event_id');
      return $this->hasMany('App\MappingCustomTag');
    }
	
	function getCustomTags(){
		return $this->hasMany('App\CustomTag');
	}

   

    //get event experiences
    function getEventExperiences(){ 
      return $this->hasMany('App\Experience');
    }

   function getFundingEventExperiences(){ 
      return $this->hasMany('App\Experience','id','experience_id');
    }

    function getOneEventExperiences(){ 
	return $this->belongsTo('App\Experience','experience_id','id');
    }	

    //get event fund reporty with has many
    function getEventFundingReport(){ 
      return $this->hasMany('App\FundingReport');
    }

	//get event fund reporty with has many
    function getComments(){ 
      return $this->hasMany('App\Comment');
    }
	
	function getCommentUser(){ 
	    return $this->hasMany('App\User');
	  }
	
	function FundingReport(){ 
      //return $this->belongsTo('App\FundingReport','id','experience_id');
      return $this->hasMany('App\FundingReport');
    }
    
   
}
