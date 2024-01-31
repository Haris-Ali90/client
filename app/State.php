<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{

    protected $table = 'states';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     public $timestamps = false;
    protected $guarded = [
        // 'id', 'country_id', 'tax_id','name','code',
    ];




}
