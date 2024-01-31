<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{

    protected $table = 'vendors';
    protected $guarded =[];

    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;

    }
    public function location()
    {
        return $this->belongsTo(Locations::class,'location_id','id');
    }

    public function getVendorOrdersCount($date)
    {
        return $this->hasMany(Sprint::class,'creator_id','id')
            ->whereIn('status_id',[61,111])
            ->whereNull('deleted_at')
            ->whereDate('created_at', '=', $date)->count();
    }


}
