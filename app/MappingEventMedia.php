<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class MappingEventMedia extends Authenticatable
{
    use Notifiable;
    //use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table= 'mapping_event_media';
    protected $dates = ['deleted_at'];
    //protected $fillable = ['email'];
   

     
   
}
