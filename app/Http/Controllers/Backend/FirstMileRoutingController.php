<?php

namespace App\Http\Controllers\Backend;

use App\Classes\Client;
use App\Classes\Fcm;
use App\Hub;
use App\HubStore;
use App\JoeyRoute;
use App\RouteHistory;
use App\RoutingZones;
use App\SlotJob;
use App\Slots;
use App\Sprint;
use App\Task;
use App\UserDevice;
use App\UserNotification;
use Illuminate\Http\Request;

class FirstMileRoutingController extends BackendController
{
    // create job id for first mile route by routific and ctc 2022-03-21
    public function storeFirstMileRoute(Request $request){

        date_default_timezone_set('America/Toronto');
        // pluck vendor ids for get hub stores
        $Date =$request->get('create_date');
        $hubStores = HubStore::with('stores')->whereNull('deleted_at')->where('hub_id', auth()->user()->hub_id)->pluck('vendor_id');
        $sprints = Sprint::with('Vendor')
            ->whereIn('status_id',[61,111])
            ->whereDate('created_at', '=', $Date)
            ->whereIn('creator_id', $hubStores)
            ->groupBy('creator_id')
            ->get();

        if(count($sprints) < 1){
            return response()->json( ['status_code'=>400,"error"=>'No Order in this hub']);
        }

        $orders = array();

        foreach($sprints as $sprint){

            $lat[0] = substr($sprint->vendor->location->latitude, 0, 2);
            $lat[1] = substr($sprint->vendor->location->latitude, 2);
            $latitude=$lat[0].".".$lat[1];

            $long[0] = substr($sprint->vendor->location->longitude, 0, 3);
            $long[1] = substr($sprint->vendor->location->longitude, 3);
            $longitude=$long[0].".".$long[1];

            $orders[$sprint->creator_id]= array(
                "location" => array(
                    "name" => $sprint->vendor->business_address,
                    "lat" => $latitude,
                    "lng" => $longitude
                ),
                "load" => $sprints->count(),
                "duration" => 2
            );

        }

        $hubPick = Hub::where('id','=',$request->hub_id)->first();
        $zone = RoutingZones::where('hub_id','=',$request->hub_id)->first();
        $address = urlencode($hubPick->address);
        // google map geocode api url
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyDTK4viphUKcrJBSuoidDqRhVA4AWnHOo0";

        // get the json response
        $resp_json = file_get_contents($url);

        // decode the json
        $resp = json_decode($resp_json, true);

        // response status will be 'OK', if able to geocode given address
        if($resp['status']=='OK'){
            $hubLat = $resp['results'][0]['geometry']['location']['lat'];
            $hubLong = $resp['results'][0]['geometry']['location']['lng'];
        }

        //joey slots count
        $joeycounts=Slots::join('vehicles','slots.vehicle','=','vehicles.id')
            ->where('slots.hub_id','=',$request->hub_id)
            ->where('slots.mile_type', '=', 1)
            ->whereNull('slots.deleted_at')
            ->get(['vehicles.capacity','vehicles.min_visits','slots.start_time','slots.end_time','slots.hub_id','slots.joey_count','custom_capacity']);

        if(count($joeycounts)<1){
            return response()->json( ['status_code'=>400,"error"=>'No slot in this hub']);
        }
        $j=0;
        foreach($joeycounts as $joe){
            if(!empty($joe->joey_count)){
                $joeycount= $joe->joey_count;
            }
            if(!isset($joeycount) || empty($joeycount)){
                return response()->json( ['status_code'=>400,"error"=>'Joey count should be greater than 1 in slot']);
            }


            for($i=1;$i<=$joeycount;$i++){
                if(empty($joe->custom_capacity)){
                    $capacity = $joe->capacity;
                }
                else{
                    $capacity = $joe->custom_capacity;
                }
                $shifts["joey_".$j] = array(
                    "start_location" => array(
                        "id" => $j,
                        "name" => $hubPick->address,
                        "lat" => $hubLat,
                        "lng" => $hubLong
                    ),
                    "end_location" => array(
                        "id" => $j,
                        "name" => $hubPick->address,
                        "lat" => $hubLat,
                        "lng" => $hubLong
                    ),
                    "shift_start" => date('H:i',strtotime($joe->start_time)),
                    "shift_end" => date('H:i',strtotime($joe->end_time)),
                    "capacity" => $capacity,
                    "min_visits_per_vehicle" => $joe->min_visits
                );
                $j++;
            }
        }


        $options = array(
            "shortest_distance" => true,
            "polylines" => true
        );

        $payload = array(
            "visits" => $orders,
            "fleet" => $shifts,
            "options" => $options
        );

        $client = new Client( '/vrp-long' );
        $client->setData($payload);
        $apiResponse= $client->send();

        if(!empty($apiResponse->error)){
            return response()->json( ['status_code'=>400,"error"=>$apiResponse->error]);
        }


        $slotjob  = new  SlotJob();
        $slotjob->job_id=$apiResponse->job_id;
        $slotjob->hub_id=$request->hub_id;
        $slotjob->engine = 1;
        $slotjob->mile_type = 1;
        $slotjob->unserved=null;
        $slotjob->save();

        $job = "Request Submited Job_id ".$apiResponse->job_id;

        return response()->json( ['status_code'=>200,"success"=> $job]);
    }

    public function firstMileDeleteRoute($routeId){

        $route= JoeyRoute::where('id',$routeId)->first();
        if ($route){
            if (isset($route->joey_id)) {
                $deviceIds = UserDevice::where('user_id', $route->joey_id)->where('is_deleted_at', 0)->pluck('device_token');
                $subject = 'Deleted Route ' . $routeId;
                $message = 'Your route has been deleted ';
                Fcm::sendPush($subject, $message, 'ecommerce', null, $deviceIds);
                $payload = ['notification' => ['title' => $subject, 'body' => $message, 'click_action' => 'ecommerce'],
                    'data' => ['data_title' => $subject, 'data_body' => $message, 'data_click_action' => 'ecommerce']];
                $createNotification = [
                    'user_id' => $route->joey_id,
                    'user_type' => 'Joey',
                    'notification' => $subject,
                    'notification_type' => 'ecommerce',
                    'notification_data' => json_encode(["body" => $message]),
                    'payload' => json_encode($payload),
                    'is_silent' => 0,
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];

                UserNotification::create($createNotification);
            }
        }

        JoeyRoute::where('id',$routeId)->update(['deleted_at'=>date('y-m-d H:i:s')]);
        return  "Route R-".$routeId." deleted Successfully";

    }

    public function getFirstMileRouteHistory($id)
    {
        $routeData = $this->getRouteHistory($id);
        return backend_view('first_mile.route.history',['routes'=>$routeData,'route_id'=>$id]);
    }

    public function getRouteHistory($id)
    {
        $routeData=RouteHistory::join('joeys','route_history.joey_id','=','joeys.id')
            ->leftjoin('merchantids','merchantids.task_id','=','route_history.task_id')
            ->leftjoin('dashboard_users','route_history.updated_by','=','dashboard_users.id')
            ->where('route_history.route_id','=',$id)
            ->whereNull('route_history.deleted_at')
            ->orderBy('route_history.created_at')->
            get(['route_history.id','route_history.route_id','route_history.status','route_history.joey_id','route_history.route_location_id','route_history.created_at'
                ,'route_history.ordinal','joeys.first_name','joeys.last_name','merchantids.tracking_id','route_history.type','route_history.updated_by','dashboard_users.full_name']);

        return $routeData;
    }
}
