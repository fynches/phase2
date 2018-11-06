<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChildInfo extends Model
{
    protected $table = 'child_info';
    protected $fillable = ['event_id', 'first_name', 'age_range', 'recipient_image'];
}
