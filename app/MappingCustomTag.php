<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class MappingCustomTag extends Authenticatable
{
    use Notifiable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table= 'mapping_custom_tag';

   function getTags(){
      //return $this->belongsTo('App\MappingEventMedia','id','event_id');
       return $this->belongsTo('App\Tag','tag_id','id');
    }
    
    //protected $fillable = ['email'];
}
