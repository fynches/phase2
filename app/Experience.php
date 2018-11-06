<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Experience extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table= 'experience';
    protected $dates = ['deleted_at'];
    //protected $fillable = ['email'];
   
    public static function experiencecreate($data){

      $experience = new Experience;
      $experience->event_id=$data['event_id'];
      $experience->exp_name=$data['exp_name'];
      $experience->image=$data['image'];      
      $experience->description=$data['description'];
      $experience->experience_from="0";
      $experience->gift_needed=$data['gift_needed'];
      $experience->status=$data['status'];
	  
	  
      $experience->save();
      return $experience;
    }


    public static function experienceupdate($data,$id=null){

    	$experience = Experience::find($id);

    	$experience->event_id=$data['event_id'];
        $experience->exp_name=$data['exp_name'];
        $experience->image=$data['image'];      
        $experience->description=$data['description'];
        $experience->experience_from="0";
        $experience->gift_needed=$data['gift_needed'];
        $experience->status=$data['status'];
        $experience->save();
        return $experience;
    }


    function getEvent(){ 
      return $this->belongsTo('App\Event','event_id','id');
    }

    function getAllEvent(){ 
      return $this->hasMany('App\Event','id','event_id');
    }
	
    function FundingReport(){ 
      //return $this->belongsTo('App\FundingReport','id','experience_id');
      return $this->hasMany('App\FundingReport');
    }
    
    function events(){ 
     return $this->hasOne('App\Event','id','event_id');
   }

    function getUser(){ 
	    return $this->belongsTo('App\User','user_id','id');
   }
   
}
