<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dispatch extends Model
{
    use SoftDeletes;
    public $table = 'dispatch';

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    /**
     * Relation With Sprint To Get Vendor
     *
     */
    public function Sprint()
    {
        return $this->belongsTo(Sprint::class,'sprint_id','id');
    }

    public function joey()
    {
        return $this->belongsTo(Joey::class);
    }

}
