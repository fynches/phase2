<?php

namespace App;

use DB;
use Mail;
use Illuminate\Database\Eloquent\Model;

Class GlobalSetting extends Model {

    protected $table = 'global_setting';

    /*
     * Added By: Devang Mavani
     * Reason: update gloabl setting to database
     */

    public static function globalsetting_update($params) {
		
		if($params['global_setting_id']!="")
		{
			$global_setting = GlobalSetting::find($params['global_setting_id']);
			$global_setting->secret_key = $params['secret_key'];
	        $global_setting->publish_key = $params['publish_key'];
			$global_setting->stripe_client_id = $params['stripe_client_id'];
			$global_setting->commission = $params['commission'];
			$global_setting->fb_client_id = $params['fb_client_id'];
			$global_setting->fb_client_secret = $params['fb_client_secret'];
			$global_setting->fb_redirect = $params['fb_redirect'];
			$global_setting->google_plus_client_id = $params['google_plus_client_id'];
			$global_setting->google_plus_secret = $params['google_plus_secret'];
			$global_setting->google_plus_redirect = $params['google_plus_redirect'];
			$global_setting->save();
			return $global_setting;
		}else{
			
			$global_setting = new GlobalSetting;
			$global_setting->secret_key = $params['secret_key'];
	        $global_setting->publish_key = $params['publish_key'];
			$global_setting->stripe_client_id = $params['stripe_client_id'];
			$global_setting->commission = $params['commission'];
			$global_setting->fb_client_id = $params['fb_client_id'];
			$global_setting->fb_client_secret = $params['fb_client_secret'];
			$global_setting->fb_redirect = $params['fb_redirect'];
			$global_setting->google_plus_client_id = $params['google_plus_client_id'];
			$global_setting->google_plus_secret = $params['google_plus_secret'];
			$global_setting->google_plus_redirect = $params['google_plus_redirect'];
			$global_setting->save();
        	return $global_setting;
		}
    }

}

?>
