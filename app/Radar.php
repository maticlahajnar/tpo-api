<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Radar extends Model
{
    protected $fillable = [
        'name', 'lat', 'long', 'type', 'speed_limit'
    ];  
}