<?php

namespace App\Http\Controllers\Backend;

use App\AmazonEntry;
use App\AssignMiJob;
use App\Classes\Client;
use App\Classes\Fcm;
use App\CTCEntry;
use App\Hub;
use App\HubStore;
use App\Joey;
use App\JoeyRoute;
use App\JoeyRouteLocations;
use App\LogRoutes;
use App\MicroHubOrder;
use App\MiJob;
use App\MiJobDetail;
use App\RouteHistory;
use App\RoutingZones;
use App\SlotJob;
use App\Slots;
use App\Sprint;
use App\Task;
use App\UserDevice;
use App\UserNotification;
use App\Vendor;
use Illuminate\Http\Request;

class MidMileController extends BackendController
{
    // get mid mile hub list with orders Count
    public function index(Request $request)
    {
        $hubId = auth()->user()->hub_id;
        $Date =$request->get('create_date');

        $hubStores = HubStore::with('stores')->whereNull('deleted_at')->where('hub_id', auth()->user()->hub_id)->pluck('vendor_id');
        $sprints = Sprint::with('Vendor')
            ->whereIn('status_id',[128])
//            ->whereDate('created_at', '=', date('Y-m-d'))
            ->whereIn('creator_id', $hubStores)
            ->groupBy('creator_id')
            ->get();

        $hubIds = [];
        foreach($sprints as $sprint){
            $hubIds[] = MicroHubOrder::where('sprint_id',$sprint->id)->pluck('hub_id');
        }

        $hubs = Hub::with('sprint')->whereNull('deleted_at')->find($hubIds);
        return backend_view('mid_mile.index', ['data'=> $hubs, 'id' => $hubId]);
    }

    //get mid mile order count
    public function getMidMileOrderCount($hub_id, $date)
    {
        $vendorIds = HubStore::where('hub_id', $hub_id)->WhereNull('deleted_at')->pluck('vendor_id');
        $sprints = Sprint::with('Vendor')
            ->whereIn('status_id',[128])
            ->whereIn('creator_id', $vendorIds)
            ->groupBy('creator_id')
            ->get();
        $hubIds = [];
        $orderCount = 0;
        foreach($sprints as $sprint){
            $hubIds[] = MicroHubOrder::where('sprint_id',$sprint->id)->pluck('hub_id');
        }
        $hubs = Hub::with('sprint')->whereNull('deleted_at')->find($hubIds);

        foreach ($hubs as $hub){
            $orderCount+=$hub->sprint->count();
        }

        $joeyCount = Slots::where('hub_id', '=',  $hub_id)
            ->WhereNull('slots.deleted_at')
            ->where('mile_type',2)
            ->sum('joey_count');

        $vehicleTyp = Slots::where('hub_id', '=',  $hub_id)
            ->join('vehicles', 'vehicles.id', '=', 'slots.vehicle')
            ->WhereNull('slots.deleted_at')
            ->where('mile_type',2)
            ->get(['vehicles.name', 'slots.joey_count']);

        if($joeyCount==null){
            $joeyCount=0;
        }

        if($vehicleTyp->isEmpty()){
            $vehicleTyp[0]=['name'=>'','joey_count'=>''];
        }

        $response = ['orders' => $orderCount, 'joeys_count' => $joeyCount, 'slots_detail' => $vehicleTyp];

        return json_encode($response);


    }

    public function createJobIdForMidMile(Request $request)
    {
        date_default_timezone_set('America/Toronto');
        // pluck vendor ids for get hub stores
        $Date =$request->get('create_date');
        $hubId = auth()->user()->hub_id;
        $vendorIds = HubStore::with('stores')->whereNull('deleted_at')->where('hub_id', auth()->user()->hub_id)->pluck('vendor_id');
        $sprints = Sprint::with('Vendor')
            ->whereIn('status_id',[128])
            ->whereIn('creator_id', $vendorIds)
            ->groupBy('creator_id')
            ->get();

        $hubIds = [];
        $orderCount = 0;
        foreach($sprints as $sprint){
            $hubIds[] = MicroHubOrder::where('sprint_id',$sprint->id)->pluck('hub_id');
        }
        $otherHubs = Hub::with('sprint')->whereNull('deleted_at')->find($hubIds);


        $mineHub = Hub::find(auth()->user()->hub_id);
        $consolidatedHub = Hub::where('is_consolidated',1)->where('city__id', $mineHub->city__id)->first();



        $orders = array();
        foreach ($otherHubs as $hub){
            if(count($hub->sprint) < 1){
                return response()->json( ['status_code'=>400,"error"=>'No Order in this hub']);
            }
            foreach($hub->sprint as $sprint){

                $hubLatitude = (float)substr($hub->hub_latitude, 0, 8) / 1000000;
                $hubLongitude = (float)substr($hub->hub_longitude, 0, 9) / 1000000;
                $orders[$hub->id]= array(
                    "location" => array(
                        "name" => $hub->address,
                        "lat" => $hubLatitude,
                        "lng" => $hubLongitude
                    ),
                    "load" => $hub->sprint->count(),
                    "duration" => 2
                );

            }
        }

        $consolidatedHubLatitude = (float)substr($consolidatedHub->hub_latitude, 0, 8) / 1000000;
        $consolidatedHubLongitude = (float)substr($consolidatedHub->hub_longitude, 0, 9) / 1000000;

        $orders[$consolidatedHub->id] = array(
            "location" => array(
                "name" => $consolidatedHub->address,
                "lat" => $consolidatedHubLatitude,
                "lng" => $consolidatedHubLongitude
            ),
            "load" => $hub->sprint->count(),
            "duration" => 2
        );


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
            ->where('slots.mile_type', '=', 2)
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
        $slotjob->engine = 2;
        $slotjob->mile_type = 2;
        $slotjob->unserved=null;
        $slotjob->save();

        $job = "Request Submited Job_id ".$apiResponse->job_id;

        return response()->json( ['status_code'=>200,"success"=> $job]);
    }

    // get mid mile slots list
    public function slotsListData($id)
    {
        $slots = Slots::whereNull('deleted_at')->where('hub_id','=',$id)->where('mile_type',2)->orderBy('id' , 'DESC')->get();
        return backend_view('mid_mile.slots.list', ['data'=> $slots, 'id'=> $id] );
    }

    // store mid mile slots data
    public function storeMidMileSlot(Request $request)
    {
        $slot = new Slots();
        $slot->hub_id = $request->input('hub_id');
        $slot->vehicle = $request->input('vehicle');
        $slot->start_time = $request->input('start_time');
        $slot->end_time = $request->input('end_time');
        $slot->joey_count = $request->input('joey_count');
        $slot->custom_capacity = $request->input('custom_capacity');
        $slot->mile_type = 2;
        $slot->save();
        return back()->with('success','Slot Added Successfully!');
    }

    //get data of edit options
    public function getMidMileEditSlot($id)
    {
        $data=Slots::where('id','=',$id)->first();
        $d=['data'=>$data];
        return json_encode($d);
    }

    //mid mile slot update
    public function midMileSlotUpdate(Request $request)
    {
        $id = $request->input('id_time');
        $slotsupdate = Slots::where('id', '=', $id)->first();
        $slotsupdate->vehicle = $request->input('vehicle_edit');
        $slotsupdate->start_time = $request->input('start_time_edit');
        $slotsupdate->end_time = $request->input('end_time_edit');
        $slotsupdate->joey_count = $request->input('joey_count_edit');
        $slotsupdate->custom_capacity = $request->input('custom_capacity_edit');
        $slotsupdate->save();
        return back()->with('success','Slot Updated Successfully!');

    }

    //delete mid mile slot
    public function midMileSlotDelete(Request $request)
    {
        $id = $request->input('delete_id');
        Slots::where('id','=',$id)->update(['deleted_at'=>date('Y-m-d h:i:s')]);
        return redirect()->back()->with('success','slot Deleted Successfully!');
    }

    //get detail of slot
    public function getDetailOfMidMile($id)
    {
        $data=Slots::where('id','=',$id)->first();
        $d=['data'=>$data];
        return json_encode($d);
    }

    // mid mile job list
    public function getMidMileJobList(Request $request){

        $hubId = auth()->user()->hub_id;
        $date=$request->get('date');
        $hub_id=$request->get('id');
        if(empty($date)){
            $date=date('Y-m-d');
        }
        $fistMileJobs = $this->getRoutificJob($date,$hubId);
        return backend_view('mid_mile.job.list',compact('fistMileJobs','hubId'));
    }

    public function getRoutificJob($date,$id){

        $datas = SlotJob::whereNull('slots_jobs.deleted_at')
            ->where('slots_jobs.created_at','like',$date.'%')
            ->where('slots_jobs.hub_id','=',$id)
            ->where('slots_jobs.mile_type','=',2)
            ->get(['job_id','status','slots_jobs.id','engine']);

        return $datas;
    }

    public function createRouteForMidMile($id){

        $url= "/jobs";

        $client = new Client($url);
        $client->setJobID($id);
        $apiResponse = $client->getJobResults();

        $job=SlotJob::where('job_id','=',$id)->first();

        if($apiResponse['status']=='finished'){

            $solution = $apiResponse['output']['solution'];


            if($apiResponse['output']['num_unserved'] > 0){
                return response()->json([
                    "status_code" => 400,
                    "status" => "Route Creation Error",
                    "output"=>$apiResponse['output']['num_unserved'] .' orders is un served'
                ]);

            }

            if(!empty($solution)){
                 SlotJob::where('job_id','=',$job->job_id)->update(['status'=>$apiResponse['status']]);

                foreach ($solution as $key => $value){

                    if(count($value)>1){

                        $Route = new JoeyRoute();

                        //$Route->joey_id = $key;
                        $Route->date =date('Y-m-d H:i:s');
                        $Route->hub = $job->hub_id;
                        $Route->zone = $job->zone_id;
                        if(isset($apiResponse['output']['total_working_time'])){
                            $Route->total_travel_time=$apiResponse['output']['total_working_time'];
                        }
                        else{
                            $Route->total_travel_time=0;
                        }
                        if(isset($apiResponse['output']['total_distance']))
                        {
                            $Route->total_distance=$apiResponse['output']['total_distance'];
                        }
                        else
                        {
                            $Route->total_distance=0;
                        }
                        $Route->mile_type = 2;
                        $Route->save();

                        $removeArray = array_slice($value, 1, -1);

                        for($i=0;$i<count($removeArray);$i++){

                            JoeyRouteLocations::where('task_id','=',$removeArray[$i]['location_id'])->update(['deleted_at'=>date('Y-m-d H:i:s')]);

                            $routeLoc = new JoeyRouteLocations();
                            $routeLoc->route_id = $Route->id;
                            $routeLoc->ordinal = $i+1;
                            $routeLoc->task_id = $removeArray[$i]['location_id'];

                            if(isset($removeArray[$i]['distance']) && !empty($removeArray[$i]['distance'])){
                                $routeLoc->distance = $removeArray[$i]['distance'];
                            }

                            if(isset($removeArray[$i]['arrival_time']) && !empty($removeArray[$i]['arrival_time'])){
                                $routeLoc->arrival_time = $removeArray[$i]['arrival_time'];
                                if(isset($removeArray[$i]['finish_time'])){
                                    $routeLoc->finish_time = $removeArray[$i]['finish_time'];
                                }
                            }
                            $routeLoc->save();

                        }
                    }
                }

                return response()->json([
                    "status_code" => 200,
                    "output"=> 'Route Create Successfully'
                ]);

            }
        }

        else{

            $error = new LogRoutes();
            $error->error = $job->job_id." is in ".$apiResponse['status'];
            $error->save();

            return back()->with('error','Routes creation is in process');
        }
    }

    public function getMidMileRouteHistory($id)
    {
        $routeData = $this->getRouteHistory($id);
            return backend_view('mid_mile.route.history',['routes'=>$routeData,'route_id'=>$id]);
    }

    // route history list
    public function getRouteHistory($id)
    {
        $routeData=RouteHistory::join('joeys','route_history.joey_id','=','joeys.id')
            ->leftjoin('merchantids','merchantids.task_id','=','route_history.task_id')
            ->leftjoin('dashboard_users','route_history.updated_by','=','dashboard_users.id')
            ->where('route_history.route_id','=',$id)
            ->whereNull('route_history.deleted_at')
            ->orderBy('route_history.created_at')->
            get(['route_history.id','route_history.route_id','route_history.status','route_history.joey_id','route_history.route_location_id',\DB::raw("CONVERT_TZ(route_history.created_at,'UTC','America/Toronto') as created_at")
                ,'route_history.ordinal','joeys.first_name','joeys.last_name','merchantids.tracking_id','route_history.type','route_history.updated_by','dashboard_users.full_name']);

        return $routeData;
    }


    // get route edit list of mid mile
    public function midMileRouteEdit($routeId,$hubId){

        $route = $this->hubRouteEdit($routeId);
        return backend_view('mid_mile.route.edit',['route'=>$route,'hub_id'=>$hubId,"route_id"=>$routeId]);

    }

    //edit list query
    public function hubRouteEdit($routeId){

        $route = JoeyRouteLocations::join('sprint__tasks','joey_route_locations.task_id','=','sprint__tasks.id')
            ->Join('merchantids','merchantids.task_id','=','sprint__tasks.id')
            ->join('locations','location_id','=','locations.id')
            ->join('sprint__sprints','sprint_id','=','sprint__sprints.id')
            ->where('route_id','=',$routeId)
            ->whereNull('joey_route_locations.deleted_at')
            ->whereNull('sprint__sprints.deleted_at')
            ->whereNotNull('merchantids.tracking_id')
            ->orderBy('joey_route_locations.ordinal','asc')
            ->get([
                'joey_route_locations.id',
                'merchantids.merchant_order_num',
                'joey_route_locations.task_id',
                'merchantids.tracking_id',
                'sprint_id',
                'type',
                'due_time',
                'etc_time',
                'address',
                'postal_code',
                'joey_route_locations.arrival_time',
                'joey_route_locations.finish_time',
                'joey_route_locations.distance',
                'sprint__sprints.status_id',
                'joey_route_locations.is_transfered',
                'joey_route_locations.ordinal'
            ]);

        return $route;

    }

    // delete route of mid mile
    public function midMileDeleteRoute($routeId)
    {
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

    // delete job of mid mile
    public function deleteMidMileJob(Request $request){

        SlotJob::where('id','=',$request->get('delete_id'))->update(['status'=>'finished','deleted_at'=>date('Y-m-d h:i:s')]);
        return redirect()->back();
    }

    //get mid mile route list
    public function midMileRoutesList(Request $request)
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
            ->where('mile_type',2)
            ->where('date', 'LIKE', $date.'%')
            ->groupBy('joey_route_locations.route_id')
            ->get(['joey_routes.id', 'joey_routes.joey_id', 'joeys.first_name', 'joeys.last_name', 'joey_routes.date', 'joey_route_locations.route_id', 'joey_route_locations.task_id']);

        return backend_view('mid_mile.route.list',compact('routes'));
    }

    //mid mile Detail route list po up
    public function getRouteDetail(Request $request, $routeId)
    {
        $hubId = JoeyRouteLocations::where('route_id',$routeId)->pluck('task_id');
        $sprintId = MicroHubOrder::whereIn('hub_id', $hubId)->pluck('sprint_id');

        $routeDetails = Sprint::join('sprint__tasks', 'sprint__sprints.id', '=', 'sprint__tasks.sprint_id')
            ->join('merchantids','merchantids.task_id','=','sprint__tasks.id')
            ->join('sprint__contacts','sprint__tasks.contact_id','=','sprint__contacts.id')
            ->join('locations','sprint__tasks.location_id','=','locations.id')
            ->where('sprint__tasks.type', '=', 'pickup')
            ->whereIn('sprint__sprints.status_id',[128])
            ->whereIn('sprint__sprints.id', $sprintId)
//            ->whereDate('sprint__sprints.created_at', '=', $request->get('date'))
            ->get(['sprint__tasks.type','sprint__tasks.ordinal','sprint__tasks.sprint_id','merchantids.merchant_order_num','merchantids.tracking_id','sprint__contacts.name','sprint__contacts.phone','sprint__contacts.email','locations.address','locations.postal_code','locations.latitude','locations.longitude']);

        $hub = Hub::find(auth()->user()->hub_id);

        return json_encode(['routes'=>$routeDetails, 'hub' => $hub]);
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

//        $task_ids=JoeyRouteLocations::where('route_id','=',$request->get('route_id'))->whereNull('deleted_at')->pluck('task_id');
//
//        $amazonEntriesSprintId=AmazonEntry::whereIn('task_id',$task_ids)
//            ->whereNUll('deleted_at')
//            ->whereNotIn('task_status_id',[104,105,106,107,108,109,110,111,112,131,135,136,101,102,103])
//            ->pluck('sprint_id');
//
//        if($amazonEntriesSprintId)
//        {
//            AmazonEntry::whereIn('sprint_id',$amazonEntriesSprintId)->
//            update(['joey_id'=>$request->input('joey_id'),'joey_name'=>$joey_data->first_name." ".$joey_data->last_name]);
//        }
//
//        $ctcEntriesSprintId=CTCEntry::whereIn('task_id',$task_ids)
//            ->whereNUll('deleted_at')
//            ->whereNotIn('task_status_id',[104,105,106,107,108,109,110,111,112,131,135,136,101,102,103])
//            ->pluck('sprint_id');
//        if ($ctcEntriesSprintId) {
//            CTCEntry::whereIn('sprint_id',$ctcEntriesSprintId)->
//            update(['joey_id'=>$request->input('joey_id'),'joey_name'=>$joey_data->first_name." ".$joey_data->last_name]);
//        }
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

    // mid mile route on map
    // get data for map route
    public function RouteMap(Request $request, $route_id){

        $hubId = JoeyRouteLocations::where('route_id',$route_id)->pluck('task_id');
        $sprintId = MicroHubOrder::where('hub_id', $hubId)->pluck('sprint_id');

        $routes = Sprint::join('sprint__tasks', 'sprint__sprints.id', '=', 'sprint__tasks.sprint_id')
            ->join('merchantids','merchantids.task_id','=','sprint__tasks.id')
            ->join('sprint__contacts','sprint__tasks.contact_id','=','sprint__contacts.id')
            ->join('locations','sprint__tasks.location_id','=','locations.id')
            ->where('sprint__tasks.type', '=', 'pickup')
            ->whereIn('sprint__sprints.status_id',[128])
            ->whereIn('sprint__sprints.id', $sprintId)
//            ->whereDate('sprint__sprints.created_at', '=', $request->get('date'))
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


    public function miJob(Request $request)
    {
        $date = $request->get('date');
        if (empty($date)) {
            $date = date('Y-m-d');
        }

        $hubId = auth()->user()->hub_id;

        $miJobId = AssignMiJob::where('hub_id', $hubId)->pluck('mi_job_id');

        $miJobs = MiJob::whereIn('id',$miJobId)
            ->where('created_at', 'like', $date . '%')
            ->where('type', 'micro_hub_mid_mile')
            ->get();

        return backend_view('mid_mile.mi_job.list', compact('miJobs'));
    }

    public function detail(MiJob $mi_job)
    {
        $miJobDetail = MiJobDetail::where('mi_job_id', $mi_job->id)->whereNull('deleted_at')->get();
        return backend_view('mid_mile.mi_job.detail', compact('miJobDetail', 'mi_job'));
    }

    public function destroy($id)
    {

        MiJob::where('id',$id)->update([
            'deleted_at' => date('Y-m-d H:i:s'),
        ]);

        MiJobDetail::where('mi_job_id', $id)->update([
            'deleted_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->route('mid.mile.mi.job')->with('error', 'Job deleted successfully');
    }

    public function createJob(Request $request)
    {
        $jobId = $request->get('job_id');
        $date = $request->get('create_date');

        $payload = [];
        $miJobs = MiJob::join('mi_job_details', 'mi_job_details.mi_job_id', '=', 'mi_jobs.id')
            ->where('mi_jobs.id', $jobId)
            ->where('mi_jobs.created_at', 'like', $date . '%')
            ->get(['mi_jobs.*', 'mi_jobs.type as mid_mile_type', 'mi_job_details.*']);

        $addres='';
        $hubId = 0;
        $visits=[];
        $fleets=[];
        foreach ($miJobs as $key => $miJob) {
            if ($miJob->type == 'pickup') {
                if ($miJob->location_type == 'hub') {
                    $microHUbOrder = MicroHubOrder::where('hub_id',$miJob->locationid)->count();

                    if($microHUbOrder > 0){

                        $hub = Hub::find($miJob->locationid);
                        if(isset($hub)){
                            $visits[$miJob->locationid] = [
                                "location" => [
                                    "name" => $hub->address,
                                    "lat" => $hub->hub_latitude,
                                    "lng" => $hub->hub_longitude,
                                ],
                                "duration" => 10,
                            ];

                            if($miJob->start_time != null){
                                $visits[$miJob->locationid]['start'] = date('H:i',strtotime($miJob->start_time));
                            }
                            if($miJob->end_time != null){
                                $visits[$miJob->locationid]['end'] = date('H:i',strtotime($miJob->end_time));
                            }

                        }
                    }

                }
            }
            if ($miJob->type == 'dropoff') {
                $hub = Hub::find($miJob->locationid);

                $fleets[$miJob->locationid] = array(
                    "start_location" => array(
                        "name" => $miJob->start_address,
                        "lat" => $miJob->start_latitude,
                        "lng" => $miJob->start_longitude
                    ),
                    "end_location" => array(
                        "name" => $hub->address,
                        "lat" => $hub->hub_latitude,
                        "lng" => $hub->hub_longitude
                    ),
                    "shift_start" => date('H:i',strtotime($miJob->start_time)),
                    "shift_end" => date('H:i',strtotime($miJob->end_time)),
                );
            }
        }

        $payload = array(
            "visits" => $visits,
            "fleet" => $fleets,
        );

        $client = new Client( '/vrp-long' );
        $client->setData($payload);
        $apiResponse= $client->send();

        if(!empty($apiResponse->error)){
            return json_encode([
                "status" => "Route Creation Error",
                "output" => $apiResponse->error
            ]);
        }


        $slotjob  = new  SlotJob();
        $slotjob->job_id=$apiResponse->job_id;
        $slotjob->hub_id=auth()->user()->hub_id;
        $slotjob->engine = 2;
        $slotjob->mile_type = 2;
        $slotjob->unserved=null;
        $slotjob->save();

        return response()->json(['status' => 200, "output" => 'Request Submitted Job_id ' . $apiResponse->job_id]);


    }


}
