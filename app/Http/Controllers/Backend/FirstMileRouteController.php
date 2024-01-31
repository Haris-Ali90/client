<?php

namespace App\Http\Controllers\Backend;


use App\AmazonEntry;
use App\Classes\Client;
use App\Classes\Fcm;
use App\CTCEntry;
use App\Hub;
use App\HubStore;
use App\Joey;
use App\JoeyRoute;
use App\JoeyRouteLocations;
use App\LogRoutes;
use App\RouteHistory;
use App\Sprint;
use App\UserDevice;
use App\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FirstMileRouteController extends BackendController
{
    //get first mile route list
    public function firstMileRoutesList(Request $request)
    {
        date_default_timezone_set("America/Toronto");

        if(empty($request->input('date'))){
            $date = date('Y-m-d');
        }
        else{
            $date = $request->input('date');
        }


        $routes = JoeyRoute::join('joey_route_locations','joey_route_locations.route_id' ,'=', 'joey_routes.id')
            ->Leftjoin('joeys', 'joeys.id', '=', 'joey_routes.joey_id')
            ->whereNull('joey_route_locations.deleted_at')
            ->whereNull('joey_routes.deleted_at')
            ->where('mile_type',1)
            ->where('date', 'LIKE', $date.'%')
            ->groupBy('joey_route_locations.route_id')
            ->get(['joey_routes.id', 'joey_routes.joey_id', 'joeys.first_name', 'joeys.last_name', 'joey_routes.date', 'joey_route_locations.route_id', 'joey_route_locations.task_id']);


        return backend_view('first_mile.route.list',compact('routes'));
    }

    //first mile get route order details
    public function getRouteDetail(Request $request, $routeId)
    {
        $vendorId = JoeyRouteLocations::where('route_id',$routeId)->pluck('task_id');

        //for testing not remove this code.....

//        $sprintTask = Sprint::with('sprintTasks', 'sprintTasks.taskMerchants', 'sprintTasks.task_Location', 'sprintTasks.taskContact')
//            ->whereIn('creator_id',$vendorId)
//            ->whereIn('status_id',[61,111])
//            ->whereDate('created_at', '=', $request->get('date'))
//            ->get();

        $routeDetails = Sprint::join('sprint__tasks', 'sprint__sprints.id', '=', 'sprint__tasks.sprint_id')
            ->join('merchantids','merchantids.task_id','=','sprint__tasks.id')
            ->join('sprint__contacts','sprint__tasks.contact_id','=','sprint__contacts.id')
            ->join('locations','sprint__tasks.location_id','=','locations.id')
            ->where('sprint__tasks.type', '=', 'pickup')
            ->whereIn('sprint__sprints.creator_id',$vendorId)
            ->whereIn('sprint__sprints.status_id',[61,111])
            ->whereDate('sprint__sprints.created_at', '=', $request->get('date'))
            ->get(['sprint__tasks.type','sprint__tasks.ordinal','sprint__tasks.sprint_id','merchantids.merchant_order_num','merchantids.tracking_id','sprint__contacts.name','sprint__contacts.phone','sprint__contacts.email','locations.address','locations.postal_code','locations.latitude','locations.longitude']);

        $hub = Hub::find(auth()->user()->hub_id);

        return json_encode(['routes'=>$routeDetails, 'hub' => $hub]);
    }

    //edit page of routes
    public function firstMileRouteEdit(Request $request, $routeId,$hubId){

        $route = $this->hubRouteEdit($routeId, $request);
        return backend_view('first_mile.route.edit',['route'=>$route,'hub_id'=>$hubId,"route_id"=>$routeId]);

    }

    //get data of route for edit page
    public function hubRouteEdit($routeId, $request){

        $vendorId = JoeyRouteLocations::where('route_id',$routeId)->pluck('task_id');

        $routeDetails = Sprint::join('sprint__tasks', 'sprint__sprints.id', '=', 'sprint__tasks.sprint_id')
            ->join('merchantids','merchantids.task_id','=','sprint__tasks.id')
            ->join('sprint__contacts','sprint__tasks.contact_id','=','sprint__contacts.id')
            ->join('locations','sprint__tasks.location_id','=','locations.id')
            ->where('sprint__tasks.type', '=', 'pickup')
            ->whereIn('sprint__sprints.creator_id',$vendorId)
            ->whereIn('sprint__sprints.status_id',[61,111])
            ->whereDate('sprint__sprints.created_at', '=', $request->get('date'))
            ->get(['sprint__tasks.id as task_id','sprint__tasks.type','sprint__tasks.ordinal','sprint__tasks.sprint_id','merchantids.merchant_order_num','merchantids.tracking_id','sprint__sprints.status_id','locations.address','locations.postal_code','locations.latitude','locations.longitude']);

        return $routeDetails;

    }

    //transfer route to joey
    public function routeTransfer(Request $request)
    {
        $routedata= JoeyRoute::where('id',$request->input('route_id'))->first();

        $joey_id=$routedata->joey_id;
        $routedata->joey_id=$request->input('joey_id');
        $routedata->save();

        // amazon entry data updated for joey tranfer in route
        $joey_data=Joey::where('id','=',$request->input('joey_id'))->first();
        // AmazonEntry::where('route_id','=',$request->get('route_id'))->
        //              whereNUll('deleted_at')->whereNull('delivered_at')->whereNotIn('task_status_id',[104,105,106,107,108,109,110,111,112,131,135,136,101,102,103])->
        //              update(['joey_id'=>$request->input('joey_id'),'joey_name'=>$joey_data->first_name." ".$joey_data->last_name]);

        $task_ids=JoeyRouteLocations::where('route_id','=',$request->get('route_id'))->whereNull('deleted_at')->pluck('task_id');

        $ctcEntriesSprintId=CTCEntry::whereIn('task_id',$task_ids)
            ->whereNUll('deleted_at')
            ->whereNotIn('task_status_id',[104,105,106,107,108,109,110,111,112,131,135,136,101,102,103])
            ->pluck('sprint_id');
        if ($ctcEntriesSprintId) {
            CTCEntry::whereIn('sprint_id',$ctcEntriesSprintId)->
            update(['joey_id'=>$request->input('joey_id'),'joey_name'=>$joey_data->first_name." ".$joey_data->last_name]);
        }
        if($joey_id==null)
        {
            $route_history =new  RouteHistory();
            $route_history->route_id=$request->input('route_id');
            $route_history->joey_id=$request->input('joey_id');
            $route_history->route_location_id=NULL;
            $route_history->status=0;
            $route_history->save();
        }
        else
        {
            $route_history =new  RouteHistory();
            $route_history->route_id=$request->input('route_id');
            $route_history->joey_id=$request->input('joey_id');
            $route_history->route_location_id=NULL;
            $route_history->status=1;
            $route_history->save();
        }

        $deviceIds = UserDevice::where('user_id',$request->input('joey_id'))->where('is_deleted_at', 0)->pluck('device_token');
        $subject = 'New Route '.$request->input('route_id');
        $message = 'You have assigned new route';
        Fcm::sendPush($subject, $message,'ecommerce',null, $deviceIds);
        $payload =['notification'=> ['title'=> $subject,'body'=> $message,'click_action'=> 'ecommerce'],
            'data'=> ['data_title'=> $subject,'data_body'=> $message,'data_click_action'=> 'ecommerce']];
        $createNotification= [
            'user_id' => $request->input('joey_id'),
            'user_type'  => 'Joey',
            'notification'  => $subject,
            'notification_type'  => 'ecommerce',
            'notification_data'  => json_encode(["body"=> $message]),
            'payload'            => json_encode($payload),
            'is_silent'          => 0,
            'is_read'            => 0,
            'created_at'         => date('Y-m-d H:i:s')
        ];
        UserNotification::create($createNotification);

        if($joey_id != null)
        {
            if ($joey_id != $request->input('joey_id'))
            {
                $deviceIds = UserDevice::where('user_id',$joey_id)->where('is_deleted_at', 0)->pluck('device_token');
                $subject = 'Route Transferred '.$request->input('route_id');
                $message = 'Your route has been transferred to another joey';
                Fcm::sendPush($subject, $message,'ecommerce',null, $deviceIds);
                $payload =['notification'=> ['title'=> $subject,'body'=> $message,'click_action'=> 'ecommerce'],
                    'data'=> ['data_title'=> $subject,'data_body'=> $message,'data_click_action'=> 'ecommerce']];
                $createNotification= [
                    'user_id' => $joey_id,
                    'user_type'  => 'Joey',
                    'notification'  => $subject,
                    'notification_type'  => 'ecommerce',
                    'notification_data'  => json_encode(["body"=> $message]),
                    'payload'            => json_encode($payload),
                    'is_silent'          => 0,
                    'is_read'            => 0,
                    'created_at'         => date('Y-m-d H:i:s')
                ];
                UserNotification::create($createNotification);
            }
        }

        return response()->json(['status' => '1', 'body' => ['route_id'=>$request->route_id,'joey_id'=>$request->joey_id]]);

    }

    // get data for map route
    public function RouteMap(Request $request, $route_id){

        $vendorId = JoeyRouteLocations::where('route_id',$route_id)->pluck('task_id');

        $routes = Sprint::join('sprint__tasks', 'sprint__sprints.id', '=', 'sprint__tasks.sprint_id')
            ->join('merchantids','merchantids.task_id','=','sprint__tasks.id')
            ->join('sprint__contacts','sprint__tasks.contact_id','=','sprint__contacts.id')
            ->join('locations','sprint__tasks.location_id','=','locations.id')
            ->where('sprint__tasks.type', '=', 'pickup')
            ->whereIn('sprint__sprints.creator_id',$vendorId)
            ->whereIn('sprint__sprints.status_id',[61,111])
            ->whereDate('sprint__sprints.created_at', '=', $request->get('date'))
            ->get(['sprint__tasks.type','sprint__tasks.ordinal','sprint__tasks.sprint_id','locations.address','locations.postal_code','locations.latitude','locations.longitude']);

        //for old condition not remove this code...

//        $routes = JoeyRouteLocations::join('sprint__tasks','task_id','=','sprint__tasks.id')
//            ->join('locations','location_id','=','locations.id')
//            ->where('route_id','=',$route_id)
//            ->whereNull('joey_route_locations.deleted_at')
//            ->orderBy('joey_route_locations.ordinal')
//            ->get(['type','route_id','joey_route_locations.ordinal','sprint_id','address','postal_code','latitude','longitude']);

        $i=0;
        $data=[];

        foreach($routes as $route){

            $data[] = $route;

            $lat[0] = substr($route->latitude, 0, 2);
            $lat[1] = substr($route->latitude, 2);
            $data[$i]['latitude'] = floatval($lat[0].".".$lat[1]);

            $long[0] = substr($route->longitude, 0, 3);
            $long[1] = substr($route->longitude, 3);
            $data[$i]['longitude'] = floatval($long[0].".".$long[1]);
            $i++;

        }
        return json_encode($data);
    }

    // re route of routes
//    public function reRoute(Request $request, $hubId,$routeId){
//
//        $vendorId = JoeyRouteLocations::where('route_id',$routeId)->pluck('task_id');
//
//        $route = Sprint::join('sprint__tasks', 'sprint__sprints.id', '=', 'sprint__tasks.sprint_id')
//            ->join('merchantids','merchantids.task_id','=','sprint__tasks.id')
//            ->join('sprint__contacts','sprint__tasks.contact_id','=','sprint__contacts.id')
//            ->join('locations','sprint__tasks.location_id','=','locations.id')
//            ->where('sprint__tasks.type', '=', 'pickup')
//            ->whereIn('sprint__sprints.creator_id',$vendorId)
//            ->whereIn('sprint__sprints.status_id',[61,111])
//            ->whereDate('sprint__sprints.created_at', '=', $request->get('date'))
//            ->get(['sprint__tasks.id as task_id','sprint__sprints.creator_id','sprint__tasks.sprint_id','locations.address','locations.postal_code','locations.latitude','locations.longitude']);
//
//
//        dd($route);
//        $Date =$request->get('create_date');
//        $hubStores = HubStore::with('stores')->whereNull('deleted_at')->where('hub_id', auth()->user()->hub_id)->pluck('vendor_id');
//        $route = Sprint::with('Vendor')
//            ->whereIn('status_id',[61,111])
//            ->whereDate('created_at', '=', $Date)
//            ->whereIn('creator_id', $hubStores)
//            ->groupBy('creator_id')
//            ->get();
//
//        $route = JoeyRouteLocations::join('sprint__tasks','joey_route_locations.task_id','=','sprint__tasks.id')
//            ->join('locations','location_id','=','locations.id')
////            ->join('merchantids','merchantids.task_id','=','joey_route_locations.task_id')
//            ->whereNull('joey_route_locations.deleted_at')
//            ->where('route_id','=',$routeId)
//            ->whereNotIn('status_id',[17,36])
//            ->get(['joey_route_locations.task_id','sprint_id','address','latitude','longitude','due_time','etc_time']);
//
//        if($route->count()<1){
//            return "No order to route";
//        }
//
//        foreach($route as $routeLoc){
//
//            $lat[0] = substr($routeLoc->latitude, 0, 2);
//            $lat[1] = substr($routeLoc->latitude, 2);
//            $latitude=$lat[0].".".$lat[1];
//
//            $long[0] = substr($routeLoc->longitude, 0, 3);
//            $long[1] = substr($routeLoc->longitude, 3);
//            $longitude=$long[0].".".$long[1];
//
//            $orders[$routeLoc->creator_id]= array(
//                "location" => array(
//                    "name" => $routeLoc->address,
//                    "lat" => $latitude,
//                    "lng" => $longitude
//                ),
//                "start" => "09:00",
//                "end" => "21:00",
//                "load" => 1,
//                "duration" => 2
//            );
//        }
//
//        $hubPick = Hub::find($hubId);
//        $address = urlencode($hubPick->address);
//        // google map geocode api url
//        $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyDTK4viphUKcrJBSuoidDqRhVA4AWnHOo0";
//
//        // get the json response
//        $resp_json = file_get_contents($url);
//
//        // decode the json
//        $resp = json_decode($resp_json, true);
//
//        // response status will be 'OK', if able to geocode given address
//        if($resp['status']=='OK'){
//            $hubLat = $resp['results'][0]['geometry']['location']['lat'];
//            $hubLong = $resp['results'][0]['geometry']['location']['lng'];
//        }
//        else{
//            $error = new LogRoutes();
//            $error->error = 'hub address or format is incorrect';
//            $error->save();
//
//            // $this->setResponse('hub address or format is incorrect',400);
//            // return $this->getResponse();
//
//            echo '<script>alert("hub address or format is incorrect")</script>';
//            echo "<script> window.history.back(); </script>";
//
//        }
//
//        $joey['joey'] = array(
//            "start_location" => array(
//                "id" => 1,
//                "name" => $hubPick->address,
//                "lat" => $hubLat,
//                "lng" => $hubLong
//            ),
//            "shift_start" => '09:00',
//            "shift_end" => '21:00'
//        );
//
//        $options = array(
//            "shortest_distance" => true,
//            "polylines" => true
//        );
//
//        $payload = array(
//            "visits" => $orders,
//            "fleet" => $joey,
//            "options" => $options
//        );
//
//        dd($payload);
//
//        $client = new Client( '/vrp' );
//        $client->setData($payload);
//        $apiResponse= $client->send();
//
////        dd($apiResponse);
//
//        if(!empty($apiResponse->solution)){
//            foreach($apiResponse->solution as $solution){
//                for($i=1;$i<count($solution);$i++){
//                    JoeyRouteLocations::where('task_id','=',$solution[$i]->location_id)->delete();
//
//                    $routeLoc = new JoeyRouteLocations();
//                    $routeLoc->route_id = $routeId;
//                    $routeLoc->ordinal = $i;
//                    $routeLoc->task_id = $solution[$i]->location_id;
//                    $routeLoc->arrival_time = $solution[$i]->arrival_time;
//                    $routeLoc->finish_time = $solution[$i]->finish_time;
//                    $routeLoc->distance = $solution[$i]->distance;
//                    $routeLoc->save();
//                }
//            }
//            return "Route R-".$routeId." Rerouted Successfully";
//        }
//
//        return "Reroute FAILED";
//    }
}