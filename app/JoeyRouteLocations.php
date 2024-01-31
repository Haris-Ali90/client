<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class JoeyRouteLocations extends Model
{
    use SoftDeletes;
    /**
     * Table name.
     *
     * @var array
     */
    public $table = 'joey_route_locations';

    /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = [
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "id",
        "route_id",
        "ordinal",
        "task_id",
        "arrival_time",
        "finish_time",
        "distance",
        "created_at",
        "updated_at",
        "deleted_at",
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    // get joey route locations
    public static  function getDurationOfRoute($id)
    {
        $data=self::where('route_id','=',$id)->whereNull('deleted_at')->orderby('id');
        //$first_element=" 11:30:00";
        $first_element=$data->first();

        $last_element=self::where('route_id','=',$id)->whereNull('deleted_at')->orderby('id','DESC')->first();

        //" 12:30:00";
        //$data->last();
        if(strpos($first_element->arrival_time,'-') && strpos($first_element->arrival_time,'T'))
        {
            $arrival_time=explode("T",$first_element->arrival_time);
            $last_element=explode("T",$last_element->finish_time);
            $arrival_time=$arrival_time[0]." ".explode("-",$arrival_time[1])[0];
            $last_element=$last_element[0]." ".explode("-",$last_element[1])[0];
            $date1= new \DateTime($arrival_time);
            $date2 = new \DateTime($last_element);
            $interval = $date1->diff($date2);
            // echo($interval->h.":".$interval->i."".$interval->s);
            return $interval->format("%H:%I:%S");

        }
        if(!empty($first_element) && !empty($last_element))
        {

            $arrival_time=explode(":",$first_element->arrival_time);
            $finish_time=explode(":",$last_element->finish_time);
            if(isset($arrival_time[1]) && isset($finish_time[1]))
            {

                $duration[0]=$finish_time[0]-$arrival_time[0];
                $duration[1]=$finish_time[1]-$arrival_time[1];
                if($duration[1]<0)
                {
                    $duration[0]--;
                    $duration[1]=60+$duration[1];
                }
                if($duration[0]<10)
                {
                    $duration[0]="0".$duration[0];
                }
                if($duration[1]<10)
                {
                    $duration[1]="0".$duration[1];
                }

                return $duration[0].':'.$duration[1].':00';
            }





        }
        return 0;

    }

    /**
     * Get joey routs sprint task history.
     */
    public function SprintTaskHistory()
    {
        return $this->hasMany( SprintTaskHistory::class,'sprint__tasks_id', 'task_id');
    }

    public function TotalOrderDropsCompletedCount()
    {
        // gating current routs tasks ids
        $tasks_ids = $this->GetAllTaskIds();
        return SprintTaskHistory::whereIn('sprint__tasks_id',$tasks_ids)->where('status_id',17)->count();
    }

    /**
     * Scope a query to only not deleted records.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotDeleted($query)
    {
        return $query->where('deleted_at', null);
    }
	
	    public function joeyRoute()
    {
        return $this->belongsTo(JoeyRoutes::class,'route_id','id')->whereNull('deleted_at');
    }

    public function taskSprintConfirmation()
    {
        return $this->belongsTo(SprintConfirmation::class,'task_id','task_id')
            ->whereNotNull('attachment_path')->orderBy('id','desc')->select('attachment_path');
    }

    public function routeHistory()
    {
        return $this->hasMany(RouteHistory::class,'route_location_id','id')->whereNull('deleted_at')->whereNotNull('joey_id')->orderBy('created_at','desc');
    }

}