<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use File;

class StaticBlock extends Model {

    protected $table = 'static_block';

    /*
     * Added By: Karnik
     * 
     * Reason: users static block create
     */

    public static function staticblockCreate($params) {


        $staticblock = new StaticBlock;
        $staticblock->title = $params['title'];
        $slug = str_replace(' ', '-', strtolower($params['title']));
        $staticblock_slug = StaticBlock::where('slug', $slug)->count();
        $staticblock->slug = ($staticblock_slug == 0) ? $slug : $slug . '-' . $staticblock_slug;
        $staticblock->description = $params['description'];
		$staticblock->save();

        return $staticblock;
    }

    /*
     * Added By: Karnik
     * 
     * Reason: users static block update
     */

    public static function staticblockUpdate($params) {
        
        $staticblock = StaticBlock::find($params['update_id']);
        
        $staticblock->title = $params['title'];
        $staticblock->description = $params['description'];
		$staticblock->save();

        return $staticblock;
    }

}
