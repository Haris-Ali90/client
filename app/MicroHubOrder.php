<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Hub;
use App\Sprint;

class MicroHubOrder extends Model
{
    use SoftDeletes;
    public $table = 'orders_actual_hub';

    protected $guarded = [];

//    protected $hidden = ['created_at','updated_at'];

    /**
     * The attributes that should be append to toArray.
     *
     * @var array
     */
    protected $appends = [];

    public function hub()
    {
        return $this->belongsTo(Hub::class, 'hub_id', 'id');
    }

    public function sprint()
    {
        return $this->belongsTo(Sprint::class, 'sprint_id', 'id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'sprint_id', 'sprint_id')->whereIn('ordinal',[2,4]);
    }

    public function bundleOrderID()
    {
        return $this->hasMany(self::class, 'bundle_id', 'bundle_id')->where('scanned_by',auth()->user()->id);
    }

}

