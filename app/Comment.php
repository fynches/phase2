<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class Comment extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table= 'comments';
	
	function getUser(){ 
	    return $this->belongsTo('App\User','user_id','id');
	  }
	
	function getEvent(){ 
      return $this->belongsTo('App\Event','event_id','id');
    }

    static function buildTree($elements, $parentId = 0) {    
		$branch = array();
		foreach ($elements as $key => $element) 
		{
            //echo $key;
			if ($element['parent_id'] == $parentId) 
			{
				$children = self::buildTree($elements, $element['id']);				
				if ($children) 
				{
					$element['children'] = $children;
				}
				
				$branch[] = $element;
			}
		}
	
		return $branch;
	}

    
}
