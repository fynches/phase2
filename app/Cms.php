<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\MappingEventMedia;
use Illuminate\Support\Facades\DB;

class Cms extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table= 'cms';
    protected $dates = ['deleted_at'];
    //protected $fillable = ['email'];

    public static function cmscreate($data){

        $cms = new Cms;
        $cms->title=$data['title'];
        $cms->description=$data['description'];
        $cms->slug=$data['slug']; 
        $cms->status=$data['status'];
        if(isset($data['image'])){
          $cms->featured_image = $data['image'];
        }
        $cms->save(); 
        return $cms;
    }


    public static function cmsupdate($data,$id){

        $cms = Cms::find($id);
        $cms->title=$data['title'];
        $cms->description=$data['description'];
        $cms->slug=$data['slug']; 
        $cms->status=$data['status'];
        if(isset($data['image'])){
          $cms->featured_image = $data['image'];
        }
        $cms->save(); 
        return $cms;
    }
   
    
}
