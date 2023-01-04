<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurants extends Model
{
    protected $table = 'restaurants';
    protected $fillable = [
        'place_id','search', 'name','geometry_lat','geometry_lng','rating','user_ratings_total','types','vicinity','icon_url','full_data','photo'
    ];
}
