<?php
namespace App;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Site extends Authenticatable
{
    protected $guard = 'site';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	
	public static function usercreate($params,$service) {
        
		if(count($params) > 0)
		{
			$provider="";
			if($service=="facebook")
			{
				$provider='1';
			}else {
				$provider='2';
			}
			$name= explode(' ',$params['name']);
			$first_name= $name[0];
			$last_name= $name[1];
			
			$site = new Site;
	        $site->first_name = $first_name;
	        $site->last_name = $last_name;
	        $site->email = $params['email'];
			$site->provider =$provider;
	        
			$site->save();
		}
        
         pr($site->save());die;
        
        if($site->save()){ 
            return $site;
        }else{
            Session::flash('error_msg', 'Something went wrong!, Please try again');
        }
        
    }
   }
