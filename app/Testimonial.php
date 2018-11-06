<?php

namespace App;

use DB;
use Mail;
use Illuminate\Database\Eloquent\Model;

Class Testimonial extends Model {

    protected $table = 'testimonial';

    /*
     * Added By: Devang Mavani
     * Reason: Add testimonial to database
     */

    public static function testimonial_insert($data) {

        $testimonial = new Testimonial;
        $testimonial->name = $data['name'];
        $testimonial->image = $data['image'];
        $testimonial->description = $data['description'];
		$testimonial->author_name = $data['author_name'];
		$testimonial->status = $data['status'];
        $testimonial->save();
        return $testimonial;
    }

    /*
     * Added By: Devang Mavani
     * Reason: update testimonial to database
     */

    public static function testimonial_update($params) {
		
		//pr($params);die;
        $testimonial = Testimonial::find($params['update_id']);
		
		$testimonial->name = $params['name'];
        $testimonial->image = $params['image'];
        $testimonial->description = $params['description'];
		$testimonial->author_name = $params['author_name'];
		$testimonial->status = $params['status'];
		$testimonial->save();
		
        return $testimonial;
    }
}

?>
