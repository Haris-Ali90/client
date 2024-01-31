<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{

    protected $table = 'cities';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     public $timestamps = false;
    protected $guarded = [
        // 'id', 'country_id', 'city_id', 'name','timezone'
    ];




}
