<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class FundingReport extends Authenticatable
{
    use Notifiable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table= 'funding_report';
    
    //protected $fillable = ['email'];
   
   function getUser(){ 
	    return $this->belongsTo('App\User','user_id','id');
	  }

   function getEventExperiences(){ 
      return $this->belongsTo('App\Experience','experience_id','id');
    }

    function getEvent(){ 
      return $this->belongsTo('App\Event','event_id','id');
      //return $this->hasOne('App\Event','event_id','id');
      //return $this->hasMany('App\Event');
    }

   

}
