<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Tag extends Authenticatable
{
    use Notifiable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table= 'tags';
    
    //protected $fillable = ['email'];
   
    public static function tagcreate($data){

      $tag = new Tag;
      $tag->tag_name=$data['tag_name'];
	  $tag->image=$data['image'];
      $tag->status=$data['status'];
	  $tag->save();
      return $tag;
    }


    public static function tagupdate($data,$id=null){

    	$tag = Tag::find($id);
		$tag->tag_name=$data['tag_name'];
		$tag->image=$data['image'];
        $tag->status=$data['status'];
		
        $tag->save();
        return $tag;
    }

}
