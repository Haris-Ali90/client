<?php


namespace App\Http\Controllers\Backend;
use App\AmazonEntry;
use App\Classes\Fcm;
use App\Client;
use App\CTCEntry;
use App\CustomRoutingTrackingId;
use App\Hub;
use App\Joey;
use App\JoeyHubRoute;
use App\JoeyRoute;
use App\JoeyRouteLocations;
use App\LogRoutes;
use App\RouteHistory;
use App\RouteTransferLocation;
use App\SlotJob;
use App\Slots;
use App\SlotsPostalCode;
use App\SprintReattempt;
use App\TaskHistory;
use App\UserDevice;
use App\UserNotification;
use Illuminate\Http\Request;
use App\Sprint;
use App\Task;
use App\MerchantIds;
use App\JobRoutes;
use DB;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;

class MicrohubRoutificController extends BackendController {

    public function getindex(Request $request){
        $date=$request->get('date');
        if(empty($date))
        {
            $date=date('Y-m-d');
        }

        $jobs =SlotJob::join('zones_routing','zone_id','=','zones_routing.id')
            ->whereNull('slots_jobs.deleted_at')
            ->where('slots_jobs.created_at','like',$date.'%')
            ->get();

        return backend_view('routific.routific_job',['datas'=>$jobs]);
    }

    public function getRouteMapLocation(Request $request)
    {
        $value=[];
        $ids=$request->input('ids');
        $i=0;
        foreach($ids as $id)
        {

            $location= JoeyRouteLocations::join('sprint__tasks','sprint__tasks.id','=','joey_route_locations.task_id')
                ->join('sprint__sprints','sprint__sprints.id','=','sprint__tasks.sprint_id')
                ->join('locations','locations.id','=','sprint__tasks.location_id')
                ->where('type','=','pickup')
                ->where('joey_route_locations.route_id','=',$id)
                ->get(['locations.longitude','locations.latitude','sprint__tasks.sprint_id','locations.address','joey_route_locations.ordinal']);


            $j=0;

            foreach($location as $loc)
            {
                $lat[0] = substr($loc->latitude, 0, 2);
                $lat[1] = substr($loc->latitude, 2);
                $value['data'][$i][$j]['latitude'] = floatval($lat[0].".".$lat[1]);

                $long[0] = substr($loc->longitude, 0, 3);
                $long[1] = substr($loc->longitude, 3);
                $value['data'][$i][$j]['longitude'] = floatval($long[0].".".$long[1]);


                $value['data'][$i][$j]['sprint_id']=$loc->sprint_id;
                $value['data'][$i][$j]['address']=$loc->address;
                $value['data'][$i][$j]['route_id']=$id."-".$loc->ordinal;
                $j++;
            }
            $i++;
        }

        return json_encode($value);

    }
    public function routeDetails($routeId){

        $routes = JoeyRouteLocations::join('sprint__tasks','task_id','=','sprint__tasks.id')
            ->join('merchantids','merchantids.task_id','=','sprint__tasks.id')
            ->join('sprint__contacts','contact_id','=','sprint__contacts.id')
            ->join('locations','location_id','=','locations.id')
            ->where('route_id','=',$routeId)
            ->whereNull('joey_route_locations.deleted_at')
            ->whereNotNull('merchantids.tracking_id')
            ->where('merchantids.tracking_id','!=','')
            ->orderBy('joey_route_locations.ordinal')
            ->get(['type','route_id','joey_route_locations.ordinal','sprint_id','merchant_order_num','tracking_id','name','phone','email','address','postal_code','latitude','longitude','distance']);


        return json_encode($routes);

    }

    public function RouteMap($route_id){

        $routes = JoeyRouteLocations::join('sprint__tasks','task_id','=','sprint__tasks.id')
            ->join('locations','location_id','=','locations.id')
            ->where('route_id','=',$route_id)
            ->whereNull('joey_route_locations.deleted_at')
            ->orderBy('joey_route_locations.ordinal')
            ->get(['type','route_id','joey_route_locations.ordinal','sprint_id','address','postal_code','latitude','longitude']);

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

    public function remainigRouteMap($route_id){

        $routes = JoeyRouteLocations::join('sprint__tasks','task_id','=','sprint__tasks.id')
            ->join('locations','location_id','=','locations.id')
            ->where('route_id','=',$route_id)
            ->whereNull('joey_route_locations.deleted_at')
            ->whereNotIn('status_id',[17,112,113,114,116,117,118,132,138,139,144,105,106,107,108,109,110,111,131,135])
            ->orderBy('joey_route_locations.ordinal')
            ->get(['type','route_id','joey_route_locations.ordinal','sprint_id','address','postal_code','latitude','longitude']);

        $i=0;
        foreach($routes as $route){

            $data[$i]['type'] = $route->type;
            $data[$i]['route_id'] = $route->route_id;
            $data[$i]['ordinal'] = $route->ordinal;
            $data[$i]['sprint_id'] = $route->sprint_id;
            $data[$i]['address'] = $route->address;
            $data[$i]['postal_code'] = $route->postal_code;

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


    public function getstatus(){
        return backend_view('routific.hub-updatestatus');
    }

    public function getstatusdesc($id){

        $status = array("136" => "Client requested to cancel the order",
            "137" => "Delay in delivery due to weather or natural disaster",
            "118" => "left at back door",
            "117" => "left with concierge",
            "135" => "Customer refused delivery",
            "108" => "Customer unavailable-Incorrect address",
            "106" => "Customer unavailable - delivery returned",
            "107" => "Customer unavailable - Left voice mail - order returned",
            "109" => "Customer unavailable - Incorrect phone number",
            "142" => "Damaged at hub (before going OFD)",
            "143" => "Damaged on road - undeliverable",
            "144" => "Delivery to mailroom",
            "103" => "Delay at pickup",
            "139" => "Delivery left on front porch",
            "138" => "Delivery left in the garage",
            "114" => "Successful delivery at door",
            "113" => "Successfully hand delivered",
            "120" => "Delivery at Hub",
            "110" => "Delivery to hub for re-delivery",
            "111" => "Delivery to hub for return to merchant",
            "121" => "Out for delivery",
            "102" => "Joey Incident",
            "104" => "Damaged on road - delivery will be attempted",
            "105" => "Item damaged - returned to merchant",
            "129" => "Joey at hub",
            "128" => "Package on the way to hub",
            "140" => "Delivery missorted, may cause delay",
            "116" => "Successful delivery to neighbour",
            "132" => "Office closed - safe dropped",
            "101" => "Joey on the way to pickup",
            "32" => "Order accepted by Joey",
            "14" => "Merchant accepted",
            "36" => "Cancelled by JoeyCo",
            "124" => "At hub - processing",
            "38" => "Draft",
            "18" => "Delivery failed",
            "56" => "Partially delivered",
            "17" => "Delivery success",
            "68" => "Joey is at dropoff location",
            "67" => "Joey is at pickup location",
            "13" => "Waiting for merchant to accept",
            "16" => "Joey failed to pickup order",
            "57" => "Not all orders were picked up",
            "15" => "Order is with Joey",
            "112" => "To be re-attempted",
            "131" => "Office closed - returned to hub",
            "125" => "Pickup at store - confirmed",
            "61" => "Scheduled order",
            "37" => "Customer cancelled the order",
            "34" => "Customer is editting the order",
            "35" => "Merchant cancelled the order",
            "42" => "Merchant completed the order",
            "54" => "Merchant declined the order",
            "33" => "Merchant is editting the order",
            "29" => "Merchant is unavailable",
            "24" => "Looking for a Joey",
            "23" => "Waiting for merchant(s) to accept",
            "28" => "Order is with Joey",
            "133" => "Packages sorted",
            "55" => "ONLINE PAYMENT EXPIRED",
            "12" => "ONLINE PAYMENT FAILED",
            "53" => "Waiting for customer to pay",
            "141" => "Lost package",
            "60" => "Task failure",
            "255" =>"Order delay");
        return $status[$id];
    }

    public function poststatusupdate(Request $request){

        $data=$request->all();


        foreach($data['tracking_id'] as $d){

            $task=MerchantIds::where('id','=',$d)->whereNull('deleted_at')->first(['task_id']);

            if(!empty($task->task_id)){
                $task_id=Task::where('id','=',$task->task_id)->whereNull('deleted_at')->first();
                $requestData['order_id'] = $task_id->sprint_id;
            }

            $statusDescription= $this->getstatusdesc($data['status_id']);
            $s=Sprint::where('id','=',$requestData['order_id'])->first();

            if(!empty($task->task_id)){

                //   DispatchData::where('order_id','=',$requestData['order_id'])->update(['status'=>$data['status_id']
                //   ,'status_copy'=>$statusDescription]);
                Sprint::where('id','=',$requestData['order_id'])->update(['status_id'=>$data['status_id']]);
                Task::where('id','=',$task->task_id)->update(['status_id'=>$data['status_id']]);
                $insert='INSERT INTO sprint__tasks_history (sprint_id, sprint__tasks_id,status_id,date,created_at)
                  VALUES ("'.$requestData['order_id'].'","'.$task->task_id.'","'. $data['status_id'].'","'.date('Y-m-d H:i:s').'","'.date('Y-m-d H:i:s').'")';
                DB::select($insert);
                $insert='INSERT INTO sprint__sprints_history (sprint__sprints_id, vehicle_id,status_id,date,created_at)
                  VALUES ("'.$requestData['order_id'].'",3,"'. $data['status_id'].'","'.date('Y-m-d H:i:s').'","'.date('Y-m-d H:i:s').'")';
                DB::select($insert);
            }

        }

        return back()->with('success','Status Updated Successfully!');
    }

    public function routeMontrealDeleted(Request $request){
        date_default_timezone_set("America/Toronto");

        if(empty($request->input('date'))){
            $date = date('Y-m-d');
        }
        else{
            $date = $request->input('date');
        }

        $countQry = "SELECT route_id,joey_routes.joey_id,
            CONCAT(zones_routing.title,'(',joey_routes.zone,')') AS zone,
            CONCAT(first_name,' ',last_name) AS joey_name,
            joey_routes.date,
            COUNT(joey_route_locations.id) AS counts,
            SUM(CASE WHEN sprint__sprints.status_id IN(17,112,113,114,116,117,118,132,138,139,144,105,106,107,108,109,110,111,131,135) THEN 0 ELSE 1 END) AS d_counts,
            SUM(joey_route_locations.distance) AS distance,
            SUM(CASE WHEN sprint__sprints.status_id IN(17,112,113,114,116,117,118,132,138,139,144,105,106,107,108,109,110,111,131,135) THEN 0 ELSE joey_route_locations.distance END) AS d_distance,
            SEC_TO_TIME(SUM(TIME_TO_SEC(finish_time)-TIME_TO_SEC(arrival_time))) AS duration,
            SEC_TO_TIME(SUM(CASE WHEN sprint__sprints.status_id IN(17,112,113,114,116,117,118,132,138,139,144,105,106,107,108,109,110,111,131,135) THEN 0 ELSE TIME_TO_SEC(finish_time)-TIME_TO_SEC(arrival_time) END)) AS d_duration
            FROM joey_route_locations 
            JOIN sprint__tasks ON(task_id=sprint__tasks.id) 
            JOIN sprint__sprints ON(sprint_id=sprint__sprints.id) 
            JOIN joey_routes ON(route_id=joey_routes.id)
            LEFT JOIN joeys ON joey_routes.joey_id=joeys.id
            JOIN locations ON(sprint__tasks.location_id=locations.id)
            LEFT JOIN  zones_routing ON (zones_routing.id=joey_routes.zone)
            #JOIN slots_postal_code ON(slots_postal_code.postal_code= SUBSTRING(locations.`postal_code`,1,3))
            #JOIN zones_routing ON(zone_id=zones_routing.id AND zones_routing.deleted_at IS NULL)
            WHERE creator_id=477260 
            AND joey_routes.date LIKE '".$date."%'
            AND zones_routing.deleted_at IS NULL
            #AND slots_postal_code.`deleted_at` IS NULL 
            AND joey_route_locations.`deleted_at` IS NULL 
            AND joey_routes.deleted_at IS Not NULL GROUP BY route_id";

        $counts = DB::select($countQry);


        //dd($counts);
        return backend_view('routific.montreal-routific-deleted',compact('counts'));
    }

    public function routeOttawaDeleted(Request $request){
        date_default_timezone_set("America/Toronto");

        if(empty($request->input('date'))){
            $date = date('Y-m-d');
        }
        else{
            $date = $request->input('date');
        }

        $countQry = "SELECT route_id,joey_routes.joey_id,
            CONCAT(zones_routing.title,'(',joey_routes.zone,')') AS zone,
            CONCAT(first_name,' ',last_name) AS joey_name,
            joey_routes.date,
            COUNT(joey_route_locations.id) AS counts,
            SUM(CASE WHEN sprint__sprints.status_id IN(17,112,113,114,116,117,118,132,138,139,144,105,106,107,108,109,110,111,131,135) THEN 0 ELSE 1 END) AS d_counts,
            SUM(joey_route_locations.distance) AS distance,
            SUM(CASE WHEN sprint__sprints.status_id IN(17,112,113,114,116,117,118,132,138,139,144,105,106,107,108,109,110,111,131,135) THEN 0 ELSE joey_route_locations.distance END) AS d_distance,
            SEC_TO_TIME(SUM(TIME_TO_SEC(finish_time)-TIME_TO_SEC(arrival_time))) AS duration,
            SEC_TO_TIME(SUM(CASE WHEN sprint__sprints.status_id IN(17,112,113,114,116,117,118,132,138,139,144,105,106,107,108,109,110,111,131,135) THEN 0 ELSE TIME_TO_SEC(finish_time)-TIME_TO_SEC(arrival_time) END)) AS d_duration
            FROM joey_route_locations 
            JOIN sprint__tasks ON(task_id=sprint__tasks.id) 
            JOIN sprint__sprints ON(sprint_id=sprint__sprints.id) 
            JOIN joey_routes ON(route_id=joey_routes.id)
            LEFT JOIN joeys ON joey_routes.joey_id=joeys.id
            JOIN locations ON(sprint__tasks.location_id=locations.id)
            LEFT JOIN  zones_routing ON (zones_routing.id=joey_routes.zone)
            #JOIN slots_postal_code ON(slots_postal_code.postal_code= SUBSTRING(locations.`postal_code`,1,3))
            #JOIN zones_routing ON(zone_id=zones_routing.id AND zones_routing.deleted_at IS NULL)
            WHERE creator_id IN (477282,477340,477341,477342,477343,477344,477345,477346,476592) 
            AND joey_routes.date LIKE '".$date."%'
            AND zones_routing.deleted_at IS NULL
            #AND slots_postal_code.`deleted_at` IS NULL 
            AND joey_route_locations.`deleted_at` IS NULL 
            AND joey_routes.deleted_at IS Not NULL GROUP BY route_id";

        $counts = DB::select($countQry);
        //dd($counts);
        return backend_view('routific.ottawa-routific-deleted',compact('counts'));
    }

    public function routectcdeleted(Request $request){
        date_default_timezone_set("America/Toronto");


        if(empty($request->input('date')))
        {
            $date = date('Y-m-d');
        }
        else{
            $date = $request->input('date');
        }

        $countQry = "SELECT route_id,joey_routes.joey_id,
            CONCAT(zones_routing.title,'(',joey_routes.zone,')') AS zone,
            CONCAT(first_name,' ',last_name) AS joey_name,
            joey_routes.date,
            COUNT(joey_route_locations.id) AS counts,
            SUM(CASE WHEN sprint__sprints.status_id IN(17,112,113,114,116,117,118,132,138,139,144,105,106,107,108,109,110,111,131,135) THEN 0 ELSE 1 END) AS d_counts,
            SUM(joey_route_locations.distance) AS distance,
            SUM(CASE WHEN sprint__sprints.status_id IN(17,112,113,114,116,117,118,132,138,139,144,105,106,107,108,109,110,111,131,135) THEN 0 ELSE joey_route_locations.distance END) AS d_distance,
            SEC_TO_TIME(SUM(TIME_TO_SEC(finish_time)-TIME_TO_SEC(arrival_time))) AS duration,
            SEC_TO_TIME(SUM(CASE WHEN sprint__sprints.status_id IN(17,112,113,114,116,117,118,132,138,139,144,105,106,107,108,109,110,111,131,135) THEN 0 ELSE TIME_TO_SEC(finish_time)-TIME_TO_SEC(arrival_time) END)) AS d_duration
            FROM joey_route_locations 
            JOIN sprint__tasks ON(task_id=sprint__tasks.id) 
            JOIN sprint__sprints ON(sprint_id=sprint__sprints.id) 
            JOIN joey_routes ON(route_id=joey_routes.id)
            LEFT JOIN joeys ON joey_routes.joey_id=joeys.id
            JOIN locations ON(sprint__tasks.location_id=locations.id)
            LEFT JOIN  zones_routing ON (zones_routing.id=joey_routes.zone)
            #JOIN slots_postal_code ON(slots_postal_code.postal_code= SUBSTRING(locations.`postal_code`,1,3))
            #JOIN zones_routing ON(zone_id=zones_routing.id AND zones_routing.deleted_at IS NULL)
            WHERE creator_id IN (477542,477171,477255,477254,477283,477284,477286,477287,477288,477289,477307,477308,477309,477310,477311,477312,477313,477314,477292,477294,477315,477317,477316,477295,477302,477303,477304,477305,477306,477296,477290,477297,477298,477299,477300,477320,477301,477318) 
            AND joey_routes.date LIKE '".$date."%'
            AND zones_routing.deleted_at IS NULL
            #AND slots_postal_code.`deleted_at` IS NULL 
            AND joey_route_locations.`deleted_at` IS NULL 
            AND joey_routes.deleted_at IS Not NULL GROUP BY route_id";



        $counts = DB::select($countQry);
        return backend_view('routific.ctc-deleted-routific',compact('counts'));
    }

    public function reAssignRoute($id){
        JoeyRoute::where('id',$id)->update(['deleted_at'=>NULL]);
        echo "<script> window.history.back(); </script>";
    }

    public function canadian_address($address){

        if(substr($address,-1)==' '){
            $postal_code = substr($address,-8,-1);
        }
        else {
            $postal_code = substr($address,-7);
        }

        if(substr($postal_code, 0, 1)==' '|| substr($postal_code, 0, 1)==','){
            $postal_code = substr($postal_code,-6);
        }

        if(substr($postal_code,-1)==' '){
            $postal_code = substr($postal_code,0,6);
        }

        $address1 =  substr($address,0,-7);

        //parsing address for suite-Component
        $address = explode(' ',trim($address));
        $address[0] = str_replace('-',' ', $address[0]);
        $address = implode(" ",$address);
        // url encode the address

        $address = urlencode($address);
        // google map geocode api url
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyDTK4viphUKcrJBSuoidDqRhVA4AWnHOo0";


        // get the json response
        $resp_json = file_get_contents($url);

        // decode the json
        $resp = json_decode($resp_json, true);

        // response status will be 'OK', if able to geocode given address
        if($resp['status']=='OK'){

            $completeAddress = [];
            $addressComponent = $resp['results'][0]['address_components'];

            // get the important data

            for ($i=0; $i < sizeof($addressComponent); $i++) {
                if ($addressComponent[$i]['types'][0] == 'administrative_area_level_1')
                {
                    $completeAddress['division'] = $addressComponent[$i]['short_name'];
                }
                elseif ($addressComponent[$i]['types'][0] == 'locality') {
                    $completeAddress['city'] = $addressComponent[$i]['short_name'];
                }
                else {
                    $completeAddress[$addressComponent[$i]['types'][0]] = $addressComponent[$i]['short_name'];
                }
                if($addressComponent[$i]['types'][0] == 'postal_code' && $addressComponent[$i]['short_name']!=$postal_code){
                    $completeAddress['postal_code'] =$postal_code;
                }
            }

            if (array_key_exists('subpremise', $completeAddress)) {
                $completeAddress['suite'] = $completeAddress['subpremise'];
                unset($completeAddress['subpremise']);
            }
            else {
                $completeAddress['suite'] = '';
            }

            if($resp['results'][0]['formatted_address'] == $address1){
                $completeAddress['address'] = $resp['results'][0]['formatted_address'];
            }
            else{
                $completeAddress['address'] = $address1;
            }



            $completeAddress['lat'] = $resp['results'][0]['geometry']['location']['lat'];
            $completeAddress['lng'] = $resp['results'][0]['geometry']['location']['lng'];

            unset($completeAddress['administrative_area_level_2']);
            unset($completeAddress['street_number']);


            return $completeAddress;

        }
        else{
            //  throw new GenericException($resp['status'],403);
        }


    }

    public function montrealRoutificControls(Request $request){

        date_default_timezone_set("America/Toronto");

        if(empty($request->input('date'))){
            $date = date('Y-m-d');
        }
        else{
            $date = $request->input('date');
        }

        /* $routes =  JoeyRoute::join('joey_route_locations','route_id','=','joey_routes.id')
         ->join('sprint__tasks','sprint__tasks.id','=','joey_route_locations.task_id')
         ->join('sprint__sprints','sprint_id','=','sprint__sprints.id')
         ->leftJoin('joeys','joey_routes.joey_id','=','joeys.id')
         ->whereNotIn('sprint__sprints.status_id',[36])
         ->whereNull('joey_routes.deleted_at')
         ->whereNull('joey_route_locations.deleted_at')
         ->where('joey_routes.date','like',$date."%")
         ->where('creator_id','=',477260)
         ->distinct()
         ->get(['joey_routes.joey_id as joey_id','date','joey_routes.id as route_id','first_name','last_name','joey_routes.total_travel_time',
         'joey_routes.total_distance']);
      */
        $countQry = "SELECT route_id,joey_routes.joey_id,
        CONCAT(zones_routing.title,'(',joey_routes.zone,')') AS zone,
        CONCAT(first_name,' ',last_name) AS joey_name,
        joey_routes.date,
        COUNT(joey_route_locations.id) AS counts,
        SUM(CASE WHEN sprint__sprints.status_id IN(17,113,114,116,117,118,132,138,139,144,104,105,106,107,108,109,110,111,112,131,135,136) THEN 0 ELSE 1 END) AS d_counts,
        SUM(joey_route_locations.distance) AS distance,
        SUM(CASE WHEN sprint__sprints.status_id IN(17,113,114,116,117,118,132,138,139,144,104,105,106,107,108,109,110,111,112,131,135,136) THEN 0 ELSE joey_route_locations.distance END) AS d_distance,
        SEC_TO_TIME(SUM(TIME_TO_SEC(finish_time)-TIME_TO_SEC(arrival_time))) AS duration,
        SEC_TO_TIME(SUM(CASE WHEN sprint__sprints.status_id IN(17,113,114,116,117,118,132,138,139,144,104,105,106,107,108,109,110,111,112,131,135,136) THEN 0 ELSE TIME_TO_SEC(finish_time)-TIME_TO_SEC(arrival_time) END)) AS d_duration
        FROM joey_route_locations 
        JOIN sprint__tasks ON(task_id=sprint__tasks.id) 
        JOIN sprint__sprints ON(sprint_id=sprint__sprints.id) 
        JOIN joey_routes ON(route_id=joey_routes.id)
        LEFT JOIN joeys ON joey_routes.joey_id=joeys.id
        JOIN locations ON(sprint__tasks.location_id=locations.id)
        LEFT JOIN  zones_routing ON (zones_routing.id=joey_routes.zone AND zones_routing.deleted_at IS NULL)
        #JOIN slots_postal_code ON(slots_postal_code.postal_code= SUBSTRING(locations.`postal_code`,1,3))
        #JOIN zones_routing ON(zone_id=zones_routing.id AND zones_routing.deleted_at IS NULL)
        WHERE creator_id=477260 
        AND joey_routes.date LIKE '".$date."%'
        #AND zones_routing.deleted_at IS NULL
        #AND slots_postal_code.`deleted_at` IS NULL 
        AND joey_route_locations.`deleted_at` IS NULL 
        AND joey_routes.deleted_at IS NULL GROUP BY route_id";

        $counts = DB::select($countQry);
        //dd($counts);
        //return backend_view('routific.montreal-routific',compact('routes','counts'));
        return backend_view('routific.montreal-routific-new',compact('counts'));

    }

    public function montrealRoutificCreate(Request $request){

        date_default_timezone_set('America/Toronto');
        $date = date('Y-m-d', strtotime($request['create_date']. ' -1 days'));

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $postals= SlotsPostalCode::where('zone_id','=',$request['zone'])->pluck('postal_code')->toArray();
        $sprints = Task::join('sprint__sprints','sprint__tasks.sprint_id','=','sprint__sprints.id')
            ->join('locations','location_id','=','locations.id')
            ->join('merchantids','task_id','=','sprint__tasks.id')
            ->whereIn('sprint__sprints.creator_id',[477260])
            ->where('type','=','pickup')
            ->whereIn(\DB::raw('SUBSTRING(locations.postal_code,1,3)'),$postals)
            ->Where('sprint__tasks.created_at','>',$start)
            ->Where('sprint__tasks.created_at','<',$end)
            ->where('sprint__sprints.in_hub_route',0)
            ->whereNull('sprint__sprints.deleted_at')
            ->whereIn('sprint__tasks.status_id',[61,13])
            ->whereNotNull('merchantids.tracking_id')
            ->orderBy('locations.postal_code')
            ->take(2000)
            ->get(['start_time','end_time','sprint__tasks.id','sprint__tasks.sprint_id','due_time','address','locations.latitude','locations.longitude','locations.postal_code','locations.city_id']);

        if(count($sprints)<1){

            return response()->json( ['status_code'=>400,"error"=>'No Order in this hub']);

        }


        $orders = array();

        foreach($sprints as $sprint){

            $lat[0] = substr($sprint->latitude, 0, 2);
            $lat[1] = substr($sprint->latitude, 2);
            $latitude=$lat[0].".".$lat[1];

            $long[0] = substr($sprint->longitude, 0, 3);
            $long[1] = substr($sprint->longitude, 3);
            $longitude=$long[0].".".$long[1];

            if(empty($sprint->city_id) || $sprint->city_id==NULL){
                $dropoffAdd = $this->canadian_address($sprint->address.','.$sprint->postal_code.',canada');
                $latitude = $dropoffAdd['lat'];
                $longitude = $dropoffAdd['lng'];
            }

            $start = $sprint->start_time;
            $end = $sprint->end_time;

            $orders[$sprint->id]= array(
                "location" => array(
                    "name" => $sprint->address,
                    "lat" => $latitude,
                    "lng" => $longitude
                ),
                //"start" => $start,
                //"end" => $end,
                "load" => 1,
                "duration" => 2
            );

        }

        //dd($orders);

        $hubPick = Hub::where('id','=',16)->first();
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


        $joeycounts=Slots::join('vehicles','slots.vehicle','=','vehicles.id')
            ->where('slots.zone_id','=',$request['zone'])
            ->whereNull('slots.deleted_at')
            ->get(['vehicles.capacity','vehicles.min_visits','slots.start_time','slots.end_time','slots.hub_id','slots.joey_count']);

        if(count($joeycounts)<1){

            return response()->json( ['status_code'=>400,"error"=>'No Slot defined']);
        }
        $i=0;
        foreach($joeycounts as $joe){
            if(!empty($joe->joey_count)){
                $joeycount= $joe->joey_count;
            }
            if(!isset($joeycount) || empty($joeycount)){

                return response()->json( ['status_code'=>400,"error"=>'No Joey Count should be greater than 1 in slot']);
            }
            for($j=0;$j<$joeycount;$j++){

                $shifts["joey_".$i] = array(
                    "start_location" => array(
                        "id" => $i,
                        "name" => $hubPick->address,
                        "lat" => $hubLat,
                        "lng" => $hubLong
                    ),
                    "shift_start" => date('H:i',strtotime($joe->start_time)),
                    "shift_end" => date('H:i',strtotime($joe->end_time)),
                    "capacity" => $joe->capacity,
                    "min_visits_per_vehicle" => $joe->min_visits
                );

                $i++;
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

        // dd(json_encode($payload));

        $client = new Client( '/vrp-long' );
        $client->setData($payload);
        $apiResponse= $client->send();

        if(!empty($apiResponse->error)){

            print_r($apiResponse->error);
            die();

        }


        $slotjob  = new  SlotJob();
        $slotjob->job_id=$apiResponse->job_id;
        $slotjob->hub_id=16;
        $slotjob->zone_id=$request['zone'];
        $slotjob->unserved=null;
        $slotjob->save();


        $job = "Request Submitted Job_id ".$apiResponse->job_id;

        return response()->json( ['status_code'=>200,"success"=> $job]);

    }

    public function montrealcreate(Request $request){

        $date = date('Y-m-d', strtotime($request['create_date']. ' -1 days'));
        $postals= SlotsPostalCode::where('zone_id','=',$request['zone'])->pluck('postal_code')->toArray();

        $allsprintIds = \DB::table('sprint__sprints')->whereIn('sprint__sprints.creator_id',[477260])
            ->whereIn('sprint__sprints.status_id',[61,13])
            ->where(\DB::raw("CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto')"),'like',$date."%")
            ->pluck('id');


        $filteredsprints = \DB::table('sprint__sprints')->whereIn('sprint__sprints.id',$allsprintIds)
            ->where('sprint__sprints.in_hub_route',0)
            ->pluck('id');

        $taskIds = \DB::table('sprint__tasks')->whereIn('sprint_id',$filteredsprints)
            ->where('type','=','pickup')
            ->pluck('id');


        $sprints = Task::join('locations','location_id','=','locations.id')
            ->whereIn('sprint__tasks.id',$taskIds)
            ->whereIn(\DB::raw('SUBSTRING(locations.postal_code,1,3)'),$postals)
            ->orderBy('locations.postal_code')
            ->take(2000)
            ->get(['sprint__tasks.id','sprint__tasks.sprint_id','address','locations.latitude','locations.longitude','locations.postal_code','locations.city_id']);

        if(count($sprints)<1){
            echo '<script>alert("No Order in this hub")</script>';
            echo "<script> window.history.back();</script>";
        }

        $orders = array();
        foreach($sprints as $sprint){
            if($sprint->taskMerchant){
                $lat[0] = substr($sprint->latitude, 0, 2);
                $lat[1] = substr($sprint->latitude, 2);
                $latitude=$lat[0].".".$lat[1];

                $long[0] = substr($sprint->longitude, 0, 3);
                $long[1] = substr($sprint->longitude, 3);
                $longitude=$long[0].".".$long[1];

                if(empty($sprint->city_id) || $sprint->city_id==NULL){
                    $dropoffAdd = $this->canadian_address($sprint->address.','.$sprint->postal_code.',canada');
                    $latitude = $dropoffAdd['lat'];
                    $longitude = $dropoffAdd['lng'];
                }

                $start = $sprint->taskMerchant->start_time;
                $end = $sprint->taskMerchant->end_time;

                $orders[$sprint->id]= array(
                    "location" => array(
                        "name" => $sprint->address,
                        "lat" => $latitude,
                        "lng" => $longitude
                    ),
                    //"start" => $start,
                    //"end" => $end,
                    "load" => 1,
                    "duration" => 2
                );
            }
        }
        $hubPick = Hub::where('id','=',16)->first();
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

        $joeycounts=Slots::join('vehicles','slots.vehicle','=','vehicles.id')
            ->where('slots.zone_id','=',$request['zone'])
            ->whereNull('slots.deleted_at')
            ->get(['vehicles.capacity','vehicles.min_visits','slots.start_time','slots.end_time','slots.hub_id','slots.joey_count']);

        if(count($joeycounts)<1){
            echo '<script>alert("No Slot defined")</script>';

            echo "<script> window.history.back(); </script>";

            // echo "<script> document.location.href='../../montreal/routes';</script>";
        }
        $i=0;
        foreach($joeycounts as $joe){
            if(!empty($joe->joey_count)){
                $joeycount= $joe->joey_count;
            }
            if(!isset($joeycount) || empty($joeycount)){
                echo '<script>alert("No Joey Count should be greater than 1 in slot")</script>';
                echo "<script> window.history.back(); </script>";
                // echo "<script> document.location.href='../../montreal/routes';</script>";
            }
            for($j=0;$j<$joeycount;$j++){

                $shifts["joey_".$i] = array(
                    "start_location" => array(
                        "id" => $i,
                        "name" => $hubPick->address,
                        "lat" => $hubLat,
                        "lng" => $hubLong
                    ),
                    "shift_start" => date('H:i',strtotime($joe->start_time)),
                    "shift_end" => date('H:i',strtotime($joe->end_time)),
                    "capacity" => $joe->capacity,
                    "min_visits_per_vehicle" => $joe->min_visits
                );

                $i++;
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

        // dd(json_encode($payload));

        $client = new Client( '/vrp-long' );
        $client->setData($payload);
        $apiResponse= $client->send();

        if(!empty($apiResponse->error)){
            print_r($apiResponse->error);
            die();
        }


        $slotjob  = new  SlotJob();
        $slotjob->job_id=$apiResponse->job_id;
        $slotjob->hub_id=16;
        $slotjob->zone_id=$request['zone'];
        $slotjob->unserved=null;
        $slotjob->save();

        $job = "Request Submited Job_id ".$apiResponse->job_id;
        echo "<script>alert('".$job."')</script>";
        echo "<script> window.history.back(); </script>";
        // echo "<script> document.location.href='../../montreal/routes';</script>";

    }

    public function ottawaRoutificControls(Request $request){

        date_default_timezone_set("America/Toronto");

        if(empty($request->input('date'))){
            $date = date('Y-m-d');
        }
        else{
            $date = $request->input('date');
        }

        // $routes =  JoeyRoute::join('joey_route_locations','route_id','=','joey_routes.id')
        // ->join('sprint__tasks','sprint__tasks.id','=','joey_route_locations.task_id')
        // ->join('sprint__sprints','sprint_id','=','sprint__sprints.id')
        // ->leftJoin('joeys','joey_routes.joey_id','=','joeys.id')
        // ->whereNotIn('sprint__sprints.status_id',[36])
        // ->whereNull('joey_routes.deleted_at')
        // ->whereNull('joey_route_locations.deleted_at')
        // ->where('joey_routes.date','like',$date."%")
        // ->whereIn('creator_id',[477282,477340,477341,477342,477343,477344,477345])
        // ->distinct()
        // ->get(['joey_routes.joey_id as joey_id','date','joey_routes.id as route_id','first_name','last_name','joey_routes.total_travel_time',
        // 'joey_routes.total_distance']);

        $countQry = "SELECT route_id,joey_routes.joey_id,
        CONCAT(zones_routing.title,'(',joey_routes.zone,')') AS zone,
        CONCAT(first_name,' ',last_name) AS joey_name,
        joey_routes.date,
        COUNT(joey_route_locations.id) AS counts,
        SUM(CASE WHEN sprint__sprints.status_id IN(17,113,114,116,117,118,132,138,139,144,104,105,106,107,108,109,110,111,112,131,135,136) THEN 0 ELSE 1 END) AS d_counts,
        SUM(joey_route_locations.distance) AS distance,
        SUM(CASE WHEN sprint__sprints.status_id IN(17,113,114,116,117,118,132,138,139,144,104,105,106,107,108,109,110,111,112,131,135,136) THEN 0 ELSE joey_route_locations.distance END) AS d_distance,
        SEC_TO_TIME(SUM(TIME_TO_SEC(finish_time)-TIME_TO_SEC(arrival_time))) AS duration,
        SEC_TO_TIME(SUM(CASE WHEN sprint__sprints.status_id IN(17,113,114,116,117,118,132,138,139,144,104,105,106,107,108,109,110,111,112,131,135,136) THEN 0 ELSE TIME_TO_SEC(finish_time)-TIME_TO_SEC(arrival_time) END)) AS d_duration
        FROM joey_route_locations 
        JOIN sprint__tasks ON(task_id=sprint__tasks.id) 
        JOIN sprint__sprints ON(sprint_id=sprint__sprints.id) 
        JOIN joey_routes ON(route_id=joey_routes.id)
        LEFT JOIN joeys ON joey_routes.joey_id=joeys.id
        JOIN locations ON(sprint__tasks.location_id=locations.id)
        LEFT JOIN  zones_routing ON (zones_routing.id=joey_routes.zone AND zones_routing.deleted_at IS NULL)
        #JOIN slots_postal_code ON(slots_postal_code.postal_code= SUBSTRING(locations.`postal_code`,1,3))
        #JOIN zones_routing ON(zone_id=zones_routing.id AND zones_routing.deleted_at IS NULL)
        WHERE creator_id in (476592,477282,477340,477341,477342,477343,477344,477345,477346)
        AND joey_routes.date LIKE '".$date."%'
        #AND zones_routing.deleted_at IS NULL
        #AND slots_postal_code.`deleted_at` IS NULL 
        AND joey_route_locations.`deleted_at` IS NULL 
        AND joey_routes.deleted_at IS NULL GROUP BY route_id";

        $counts = DB::select($countQry);
        //dd($counts);
        //return backend_view('routific.ottawa-routific',compact('routes','counts'));
        return backend_view('routific.ottawa-routific-new',compact('counts'));

    }

    public function ottawaRoutificCreate(Request $request){

        date_default_timezone_set('America/Toronto');
        $date = date('Y-m-d', strtotime($request['create_date']. ' -1 days'));

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $postals= SlotsPostalCode::where('zone_id','=',$request['zone'])->pluck('postal_code')->toArray();

        $amazon = Task::join('sprint__sprints','sprint__tasks.sprint_id','=','sprint__sprints.id')
            ->join('locations','location_id','=','locations.id')
            ->join('merchantids','task_id','=','sprint__tasks.id')
            ->whereIn('sprint__sprints.creator_id',[477282,476592])
            ->where('type','=','pickup')
            ->whereIn(\DB::raw('SUBSTRING(locations.postal_code,1,3)'),$postals)
            ->Where('sprint__tasks.created_at','>',$start)
            ->Where('sprint__tasks.created_at','<',$end)
            ->where('sprint__sprints.in_hub_route',0)
            ->whereNull('sprint__sprints.deleted_at')
            ->whereIn('sprint__tasks.status_id',[61,13])
            ->whereNotNull('merchantids.tracking_id')
            ->orderBy('locations.postal_code')
            ->take(2000)
            ->get(['start_time','end_time','sprint__tasks.id','sprint__tasks.sprint_id','due_time','address','locations.latitude','locations.longitude','locations.postal_code','locations.city_id'])->toArray();

        $ctc = Task::join('sprint__sprints','sprint__tasks.sprint_id','=','sprint__sprints.id')
            ->join('locations','location_id','=','locations.id')
            ->join('merchantids','task_id','=','sprint__tasks.id')
            ->whereIn('sprint__sprints.creator_id',[477340,477341,477342,477343,477344,477345,477346])
            ->where('type','=','pickup')
            ->whereIn(\DB::raw('SUBSTRING(locations.postal_code,1,3)'),$postals)
            ->where('sprint__sprints.in_hub_route',0)
            ->whereIn('sprint__sprints.status_id',[13,124])
            ->whereNotNull('merchantids.tracking_id')
            ->where(\DB::raw('date(sprint__tasks.created_at)'), '>', '2021-01-05')
            ->orderBy('locations.postal_code')
            ->get(['start_time','end_time','sprint__tasks.id','sprint__tasks.sprint_id','due_time','address','locations.latitude','locations.longitude','locations.postal_code','locations.city_id'])->toArray();

        $sprints = array_merge($amazon,$ctc);

        // if(is_array($amazon) && is_array($ctc)){
        //     $sprints = array_merge($amazon,$ctc);
        // }
        // else if(!is_array($amazon) && is_array($ctc)){
        //     $sprints = $ctc;
        // }
        // else if(is_array($amazon) && !is_array($ctc)){
        //         $sprints = $amazon;
        // }
        // else{
        //         $sprints = [];
        // }


        // dd($sprints);
        if(count($sprints)<1){
            return response()->json( ['status_code'=>400,"error"=>'No Order in this hub']);

            // echo '<script>alert("No Order in this hub")</script>';
            // echo "<script> window.history.back(); </script>";
            // echo "<script> document.location.href='../../ottawa/routes';</script>";
        }


        $orders = array();

        foreach($sprints as $sprint){

            $lat[0] = substr($sprint['latitude'], 0, 2);
            $lat[1] = substr($sprint['latitude'], 2);
            $latitude=$lat[0].".".$lat[1];

            $long[0] = substr($sprint['longitude'], 0, 3);
            $long[1] = substr($sprint['longitude'], 3);
            $longitude=$long[0].".".$long[1];

            if(empty($sprint['city_id']) || $sprint['city_id']==NULL){
                $dropoffAdd = $this->canadian_address($sprint['address'].','.$sprint['postal_code'].',canada');

                if(!empty($dropoffAdd)){
                    $latitude = $dropoffAdd['lat'];
                    $longitude = $dropoffAdd['lng'];
                }

            }

            // $start = $sprint->start_time;
            // $end = $sprint->end_time;

            $orders[$sprint['id']]= array(
                "location" => array(
                    "name" => $sprint['address'],
                    "lat" => $latitude,
                    "lng" => $longitude
                ),
                // "start" => $start,
                // "end" => $end,
                "load" => 1,
                "duration" => 2
            );

        }

        //dd($orders);

        $hubPick = Hub::where('id','=',19)->first();
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

        $joeycounts=Slots::join('vehicles','slots.vehicle','=','vehicles.id')
            ->where('slots.zone_id','=',$request['zone'])
            ->whereNull('slots.deleted_at')
            ->get(['vehicles.capacity','vehicles.min_visits','slots.start_time','slots.end_time','slots.hub_id','slots.joey_count']);

        if(count($joeycounts)<1){

            return response()->json( ['status_code'=>400,"error"=>'No Slot in this zone']);
            // echo '<script>alert("No Slot in this zone")</script>';
            // echo "<script> window.history.back(); </script>";
            // echo "<script> document.location.href='../../ottawa/routes';</script>";
        }

        $i=0;
        foreach($joeycounts as $joe){
            if(!empty($joe->joey_count)){
                $joeycount= $joe->joey_count;
            }
            if(!isset($joeycount) || empty($joeycount)){

                return response()->json( ['status_code'=>400,"error"=>'Joey Count should be greater than 1 in slot']);

                // echo '<script>alert("Joey Count should be greater than 1 in slot")</script>';
                // echo "<script> window.history.back(); </script>";
                // echo "<script> document.location.href='../../ottawa/routes';</script>";
            }
            for($j=0;$j<$joeycount;$j++){

                $shifts["joey_".$i] = array(
                    "start_location" => array(
                        "id" => $i,
                        "name" => $hubPick->address,
                        "lat" => $hubLat,
                        "lng" => $hubLong
                    ),
                    "shift_start" => date('H:i',strtotime($joe->start_time)),
                    "shift_end" => date('H:i',strtotime($joe->end_time)),
                    "capacity" => $joe->capacity,
                    "min_visits_per_vehicle" => $joe->min_visits
                );

                $i++;
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
            print_r($apiResponse->error);
            die();
        }


        $slotjob  = new  SlotJob();
        $slotjob->job_id=$apiResponse->job_id;
        $slotjob->hub_id=19;
        $slotjob->zone_id=$request['zone'];
        $slotjob->unserved=null;
        $slotjob->save();

        $job = "Request Submited Job_id ".$apiResponse->job_id;
        return response()->json( ['status_code'=>200,"success"=> $job]);

        //  echo "<script>alert('".$job."')</script>";
        //  echo "<script> window.history.back(); </script>";
        // echo "<script> document.location.href='../../ottawa/routes';</script>";

    }

    public function ctcRoutificControls(Request $request){

        date_default_timezone_set("America/Toronto");

        if(empty($request->input('date'))){
            $date = date('Y-m-d');
        }
        else{
            $date = $request->input('date');
        }

        $countQry = "SELECT route_id,joey_routes.joey_id,joey_routes.`date`,
        CONCAT(zones_routing.title,'(',joey_routes.zone,')') AS zone,
        COUNT(joey_route_locations.id) AS counts,
        SUM(CASE WHEN sprint__sprints.status_id in(17,113,114,116,117,118,132,138,139,144,104,105,106,107,108,109,110,111,112,131,135,136) THEN 0 ELSE 1 END) AS d_counts,
        SUM(joey_route_locations.distance) AS distance,
        SUM(CASE WHEN sprint__sprints.status_id in(17,113,114,116,117,118,132,138,139,144,104,105,106,107,108,109,110,111,112,131,135,136) THEN 0 ELSE joey_route_locations.distance END) AS d_distance,
        SEC_TO_TIME(SUM(TIME_TO_SEC(finish_time)-TIME_TO_SEC(arrival_time))) AS duration,
        SEC_TO_TIME(SUM(CASE WHEN sprint__sprints.status_id in(17,113,114,116,117,118,132,138,139,144,104,105,106,107,108,109,110,111,112,131,135,136) THEN 0 ELSE TIME_TO_SEC(finish_time)-TIME_TO_SEC(arrival_time) END)) AS d_duration
        FROM joey_route_locations 
        JOIN sprint__tasks ON(task_id=sprint__tasks.id) 
        JOIN sprint__sprints ON(sprint_id=sprint__sprints.id) 
        JOIN joey_routes ON(route_id=joey_routes.id) 
        JOIN locations ON(location_id=locations.id)
        LEFT JOIN  zones_routing ON (zones_routing.id=joey_routes.zone AND zones_routing.`deleted_at` IS NULL)
        -- JOIN slots_postal_code ON(slots_postal_code.postal_code= SUBSTRING(locations.`postal_code`,1,3))
        -- JOIN zones_routing ON(zone_id=zones_routing.id)
        WHERE creator_id IN(477542,477171,477255,477254,477283,477284,477286,477287,477288,477289,477307,477308,477309,477310,477311,477312,477313,
        477314,477292,477294,477315,477317,477316,477295,477302,477303,477304,477305,477306,477296,477290,477297,477298,477299,477300,
        477320,477301,477318,477328,476294,477334,477335,477336,477337,477338,477339) 
        AND joey_routes.date LIKE '".$date."%' 
        AND sprint__tasks.`deleted_at` IS NULL
        AND joey_route_locations.`deleted_at` IS NULL 
        #AND zones_routing.`deleted_at` IS NULL
        AND joey_routes.deleted_at IS NULL GROUP BY route_id";

        $counts = DB::select($countQry);


        return backend_view('routific.ctc-new',compact('counts'));

    }

    public function ctcRoutificCreate(Request $request){

        date_default_timezone_set('America/Toronto');

        $postals= SlotsPostalCode::where('zone_id','=',$request['zone'])->pluck('postal_code')->toArray();

        $sprints = Task::join('sprint__sprints','sprint__tasks.sprint_id','=','sprint__sprints.id')
            ->join('locations','location_id','=','locations.id')
            ->join('merchantids','task_id','=','sprint__tasks.id')
            ->whereIn('sprint__sprints.creator_id',[477542,476294,477171,477254,477255,477283,477284,477286,477287,477288,477289,477290,477292,477294,477295,477296,477297,477298,477299,477300,477301,477302,477303,477304,477305,477306,477307,477308,477309,477310,477311,477312,477313,477314,477315,477316,477317,477318,477320,477328,477334,477335,477336,477337,477338,477339])
            ->where('type','=','pickup')
            ->whereIn(\DB::raw('SUBSTRING(locations.postal_code,1,3)'),$postals)
            ->where('sprint__sprints.in_hub_route',0)
            ->where('sprint__sprints.is_reattempt',0)
            ->whereIn('sprint__sprints.status_id',[124,13])
            ->whereNotNull('merchantids.tracking_id')
            ->where(\DB::raw('date(sprint__tasks.created_at)'), '>', '2021-01-05')
            ->orderBy('locations.postal_code')
            ->take(2000)
            ->get(['start_time','end_time','sprint__tasks.id','sprint__tasks.sprint_id','due_time','address','locations.latitude','locations.longitude','locations.postal_code','locations.city_id']);

        if(count($sprints)<1){
            return response()->json( ['status_code'=>400,"error"=>'No Order in this hub']);

            // echo '<script>alert("No Order in this hub")</script>';
            // echo "<script> window.history.back(); </script>";
            // echo "<script> document.location.href='../../ctc/routes';</script>";
        }


        $orders = array();

        foreach($sprints as $sprint){

            $lat[0] = substr($sprint->latitude, 0, 2);
            $lat[1] = substr($sprint->latitude, 2);
            $latitude=$lat[0].".".$lat[1];

            $long[0] = substr($sprint->longitude, 0, 3);
            $long[1] = substr($sprint->longitude, 3);
            $longitude=$long[0].".".$long[1];

            // if(empty($sprint->city_id) || $sprint->city_id==NULL){
            //     $dropoffAdd = $this->canadian_address($sprint->address.','.$sprint->postal_code.',canada');
            //     $latitude = $dropoffAdd['lat'];
            //     $longitude = $dropoffAdd['lng'];
            // }

            $start = $sprint->start_time;
            $end = $sprint->end_time;

            $orders[$sprint->id]= array(
                "location" => array(
                    "name" => $sprint->address,
                    "lat" => $latitude,
                    "lng" => $longitude
                ),
                //"start" => $start,
                //"end" => $end,
                "load" => 1,
                "duration" => 2
            );

        }

        //dd($orders);

        $hubPick = Hub::where('id','=',17)->first();
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

        $joeycounts=Slots::join('vehicles','slots.vehicle','=','vehicles.id')
            ->where('slots.zone_id','=',$request['zone'])
            ->whereNull('slots.deleted_at')
            ->get(['vehicles.capacity','vehicles.min_visits','slots.start_time','slots.end_time','slots.hub_id','slots.joey_count','custom_capacity']);

        if(count($joeycounts)<1){

            return response()->json( ['status_code'=>400,"error"=>'No slot in this zone']);

            // echo '<script>alert("No slot in this zone")</script>';
            // echo "<script> window.history.back(); </script>";
            // echo "<script> document.location.href='../../ctc/routes';</script>";
        }



        foreach($joeycounts as $joe){
            if(!empty($joe->joey_count)){
                $joeycount= $joe->joey_count;
            }

            if(!isset($joeycount) || empty($joeycount)){

                return response()->json( ['status_code'=>400,"error"=>'Joey count should be greater than 1 in slot']);

                // echo '<script>alert("Joey count should be greater than 1 in slot")</script>';
                // echo "<script> window.history.back(); </script>";
                // echo "<script> document.location.href='../../ctc/routes';</script>";
            }

            for($i=1;$i<=$joeycount;$i++){

                if(empty($joe->custom_capacity)){
                    $capacity = $joe->capacity;
                }
                else{
                    $capacity = $joe->custom_capacity;
                }
                $shifts["joey_".$i] = array(
                    "start_location" => array(
                        "id" => $i,
                        "name" => $hubPick->address,
                        "lat" => $hubLat,
                        "lng" => $hubLong
                    ),
                    "shift_start" => date('H:i',strtotime($joe->start_time)),
                    "shift_end" => date('H:i',strtotime($joe->end_time)),
                    "capacity" => $capacity,
                    "min_visits_per_vehicle" => $joe->min_visits
                );
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
            // $this->setResponse($apiResponse->error,400);
            // return $this->getResponse();
            // return back()->with('error',$apiResponse->error);
            return response()->json( ['status_code'=>400,"error"=>$apiResponse->error]);
        }


        $slotjob  = new  SlotJob();
        $slotjob->job_id=$apiResponse->job_id;
        $slotjob->hub_id=17;
        $slotjob->zone_id=$request['zone'];
        $slotjob->unserved=null;
        $slotjob->save();

        $job = "Request Submited Job_id ".$apiResponse->job_id;

        //  echo "<script>alert('".$job."')</script>";
        //  echo "<script> window.history.back(); </script>";

        return response()->json( ['status_code'=>200,"success"=> $job]);
        // echo "<script> document.location.href='../../ctc/routes';</script>";

    }
    public function getdeleteRouteview()
    {
        return backend_view('routific.route-routific-deleted');
    }

    public function deleteRouteId(Request $request)
    {
        $id=$request->input('id');

        $route_id=JobRoutes::where('id','=',$id)->first();
        if(empty($route_id)){
            return back()->with('error','Route id not found');
        }
        JobRoutes::where('id','=',$id)->update(['deleted_at'=>date("Y-m-d H:i:s")]);
        return back()->with('success','Route Deleted Successfully!');

    }
    /*  public function getRouteHistory($id)
      {
         $routeData=RouteHistory::join('joeys','route_history.joey_id','=','joeys.id')
         ->leftjoin('merchantids','merchantids.task_id','=','route_history.task_id')
         ->where('route_history.route_id','=',$id)
         ->whereNull('route_history.deleted_at')
         ->orderBy('route_history.created_at')->
         get(['route_history.id','route_history.route_id','route_history.status','route_history.joey_id','route_history.route_location_id',\DB::raw("CONVERT_TZ(route_history.created_at,'UTC','America/Toronto') as created_at")
         ,'route_history.ordinal','joeys.first_name','joeys.last_name','merchantids.tracking_id']);

         return backend_view('routific.route-history-table',['routes'=>$routeData,'route_id'=>$id]);
      }*/

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

        return backend_view('routific.route-history-table',['routes'=>$routeData,'route_id'=>$id]);
    }

    public function totalOrderNotinroute(Request $request){

        $date=$request->get('date');
        $date=date('Y-m-d',strtotime("-1 day", strtotime($date)));

        $id=$request->get('id');
        if($id==16)
        {

            $query = AmazonEntry::where('creator_id',477260)
                ->where('created_at','like',$date."%")
                ->whereNull('deleted_at');

            $totalids = $query->pluck('id');
            $totalcounts = count($totalids);

            $notinroutids = $query->whereNull('route_id')->whereIn('task_status_id',[13,61])->pluck('id');
            $notinroutecounts = count($notinroutids);

        }
        else if($id==19)
        {
            $query = AmazonEntry::where('creator_id',477282)
                ->where('created_at','like',$date."%")
                ->whereNull('deleted_at');

            $totalids = $query->pluck('id');
            $totalcounts = count($totalids);

            $notinroutids = $query->whereNull('route_id')->whereIn('task_status_id',[13,61])->pluck('id');
            $notinroutecounts = count($notinroutids);

            $ctcQueryy = Sprint::whereIn('creator_id',[477340,477341,477342,477343,477344,477345,477346])
                ->whereNotIn('status_id',[38,101,102,103,104,105,106,107,108,109,110,111,112,131,135,136,17,113,114,116,117,118,132,138,139,144])
                ->where('created_at','>','2021-06-31');

            $totalctcids = $ctcQueryy->pluck('id');
            $totalctccounts = count($totalctcids);

            $notinroutectcids = $ctcQueryy->where('in_hub_route',0)->whereIn('status_id',[13,124])->pluck('id');
            $notinroutectccounts = count($notinroutectcids);

            $totalcounts = $totalcounts + $totalctccounts;
            $notinroutecounts = $notinroutecounts + $notinroutectccounts;
        }
        else
        {
            $ctcQueryy = Sprint::whereIn('creator_id',[477255,477254,477283,477284,477286,477287,477288,477289,477307,477308,477309,477310,477311,477312,477313,477314,477292,477294,477315,477317,477316,477295,
                477302,477303,477304,477305,477306,477296,477290,477297,477298,477299,477300,477320,477301,477318])
                ->whereNotIn('status_id',[38,101,102,103,104,105,106,107,108,109,110,111,112,131,135,136,17,113,114,116,117,118,132,138,139,144])
                ->where('created_at','>','2021-06-31');

            $totalctcids = $ctcQueryy->pluck('id');
            $totalcounts = count($totalctcids);

            $notinroutectcids = $ctcQueryy->where('in_hub_route',0)->whereIn('status_id',[13,124])->pluck('id');
            $notinroutecounts = count($notinroutectcids);

        }

        return response()->json(['status_code' => 200,'total_count'=>$totalcounts,'not_in_route_counts'=>$notinroutecounts]);
    }

    public function RouteTransfer(Request $request){

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

        $amazonEntriesSprintId=AmazonEntry::whereIn('task_id',$task_ids)
            ->whereNUll('deleted_at')
            ->whereNotIn('task_status_id',[104,105,106,107,108,109,110,111,112,131,135,136,101,102,103])
            ->pluck('sprint_id');

        if($amazonEntriesSprintId)
        {
            AmazonEntry::whereIn('sprint_id',$amazonEntriesSprintId)->
            update(['joey_id'=>$request->input('joey_id'),'joey_name'=>$joey_data->first_name." ".$joey_data->last_name]);
        }

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

    public function hubRouteEdit($routeId,$hubId){
        $route = JoeyRouteLocations::join('sprint__tasks','joey_route_locations.task_id','=','sprint__tasks.id')
            ->Join('merchantids','merchantids.task_id','=','sprint__tasks.id')
            ->join('locations','location_id','=','locations.id')
            ->join('sprint__sprints','sprint_id','=','sprint__sprints.id')
            // ->whereNotIn('sprint__sprints.status_id',[36,17])
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
                'start_time',
                'end_time',
                'address',
                'postal_code',
                'joey_route_locations.arrival_time',
                'joey_route_locations.finish_time',
                'joey_route_locations.distance',
                'sprint__sprints.status_id',
                'joey_route_locations.is_transfered',
                'joey_route_locations.ordinal'
            ]);

        return backend_view('routific.edit-hub-route',['route'=>$route,'hub_id'=>$hubId,"route_id"=>$routeId]);

    }

    public function action_hubRouteDelete($routeLocId){

        JoeyRouteLocations::where('id','=',$routeLocId)->delete();

        return Redirect::back();
    }

    public function deleteRoute($routeId){

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

    public function action_routeAssign($hub_id){

        if(!isset($_REQUEST['date']) || empty($_REQUEST['date'])){
            $date = "20".date('y-m-d');
        }
        else {
            $date = $_REQUEST['date'];
        }

        $routes = JoeyHubRoute::join('joey_routes','joey_routes.id','=','route_id')
            ->join('joeys','joeys.id','=','hub_routes.joey_id')
            ->whereNull('hub_routes.deleted_at')
            ->where('joey_routes.date','like',$date.'%')
            ->where('hub','=',$hub_id)
            ->get(['hub_routes.joey_id','hub_routes.route_id','date','first_name','last_name','hub_joey_type']);
        return backend_view('routific.hub-joey-assign',['hubroutes'=>$routes,'hub_id'=>$hub_id]);
    }

    public function action_routeDelete($routeId,$joeyId){

        JoeyHubRoute::where('joey_id','=',$joeyId)->where('route_id','=',$routeId)->delete();
        return Redirect::to_route('Hub-route-assign');

    }

    public function action_routeAssignCreate(){
        if(Request::method() === 'POST') {
            $hubRoute = new JoeyHubRoute();
            $hubRoute->route_id = Input::get('route_id');
            $hubRoute->joey_id = Input::get('joey_id');
            $hubRoute->save();
            echo "<script> window.history.back(); </script>";
            // echo "<script> document.location.href='../../route/assign';</script>";

        }
    }

    public function action_edit_routes($id){
        $data=JoeyRouteLocations::join('merchantids','merchantids.task_id','=','joey_route_locations.task_id')->join('sprint__tasks','sprint__tasks.id','=','merchantids.task_id')->join('sprint__sprints','sprint__sprints.id','=','sprint__tasks.sprint_id')->whereNull('sprint__sprints.deleted_at')
            ->where('joey_route_locations.route_id','=',$id)
            ->order_by('joey_route_locations.ordinal')->whereNull('sprint__tasks.deleted_at')->whereNull('joey_route_locations.deleted_at')->whereNull('merchantids.deleted_at')
            ->get(['merchantids.merchant_order_num','merchantids.tracking_id','merchantids.task_id','sprint__tasks.sprint_id as order_id']);

        return backend_view('routific.edit-route-routific',['datas'=>$data,'id'=>$id]);
    }

    public function action_deleteroutes($id){
        JoeyCo\Laravel\Sprint::where('id','=',$id)->delete();
        return ;
    }

    public function action_alllocationmap($id,$date){
        $date=date('Y-m-d',strtotime("-1 day", strtotime($date)));
        $datas=JoeyRouteLocations::join('sprint__tasks','sprint__tasks.id','=','joey_route_locations.task_id')
            ->join('sprint__sprints','sprint__sprints.id','=','sprint__tasks.sprint_id')
            ->where('sprint__sprints.created_at','like',$date."%")->distinct()->get(['joey_route_locations.route_id']);
        $value=[];
        $i=0;
        foreach($datas as $data)
        {
            $location= JoeyRouteLocations::join('sprint__tasks','sprint__tasks.id','=','joey_route_locations.task_id')
                ->join('sprint__sprints','sprint__sprints.id','=','sprint__tasks.sprint_id')
                ->join('locations','locations.id','=','sprint__tasks.location_id')
                ->where('type','=','pickup')
                ->where('sprint__sprints.creator_id','=',$id)
                ->where('joey_route_locations.route_id','=',$data->attributes['route_id'])
                ->get(['locations.longitude','locations.latitude','sprint__tasks.sprint_id','locations.address']);
            $j=0;
            foreach($location as $loc)
            {
                $value[$i][$j]['longitude']=$loc->attributes['longitude'];
                $value[$i][$j]['latitude']=$loc->attributes['latitude'];
                $value[$i][$j]['route_id']=$data->attributes['route_id'];
                $value[$i][$j]['sprint_id']=$loc->attributes['sprint_id'];
                $value[$i][$j]['address']=$loc->attributes['address'];
                $j++;
            }
            $i++;
        }

        return json_encode($value);

    }

    public function action_amazonindex(){
        return backend_view('routific.indexdate');
    }

    public function action_amazondate(){

        $date=Input::all();
        $date1= date('Y-m-d H:i:s',strtotime("-1 day", strtotime($date['date']." 17:00:00")));

        $data= Sprint::join('sprint__tasks','sprint_id','=','sprint__sprints.id')
            ->where('type','=','pickup')
            ->whereIn('creator_id',[477260,477282])
            ->where(\DB::raw("CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto')"),'like',$date['date'].'%')
            ->get(['sprint__sprints.id as sprint_id','sprint__tasks.id as task_id']);

        if(empty($data))
        {
            Session::flash('status_error',  'There is no  order to update');
            return Redirect::back();
        }

        foreach($data as $id)
        {
            Sprint::where("id",'=',$id->attributes['sprint_id'])->update(['created_at'=>$date1,'date_updated'=>1]);
            Task::where('id','=',$id->attributes['task_id'])->update(['created_at'=>$date1]);
            MerchantIds::where('task_id','=',$id->attributes['task_id'])->update(['created_at'=>$date1]);

        }

        Session::flash('status_success', 'Updated Successfully!');
        return Redirect::back();

    }

    public function routelocTransfer(Request $request){


        $last_route_location=JoeyRouteLocations::where('route_id','=',$request['route_id'][0])->orderBy('ordinal','DESC')->first();
        $ordinal=$last_route_location->ordinal+1;
        $locations =JoeyRouteLocations::whereIn('id',$request['locations'])->whereNull('deleted_at')->get();
        foreach($locations as $location)
        {
            $route_transfer=null;
            if($location->is_transfered)
            {
                $route_transfer=RouteTransferLocation::where('new_route_location_id','=',$location->id)->first();
            }

            JoeyRouteLocations::where('id','=',$location->id)->update(['deleted_at'=>date("Y-m-d H:i:s")]);
            $routeLoc = new JoeyRouteLocations();
            $routeLoc->route_id = $request['route_id'][0];
            $routeLoc->ordinal = $ordinal;
            $routeLoc->task_id = $location->task_id;
            $routeLoc->arrival_time = $location->arrival_time;
            $routeLoc->finish_time = $location->finish_time;
            $routeLoc->distance = $location->distance;
            $routeLoc->is_transfered=1;
            $routeLoc->save();

            $transfer_location= new  RouteTransferLocation();

            if($location->is_transfered && $route_transfer!=null )
            {
                $transfer_location->old_route_id=$route_transfer->old_route_id;
                $transfer_location->old_route_location_id=$route_transfer->old_route_location_id;
                $transfer_location->old_ordinal=$route_transfer->old_ordinal;
            }
            else
            {
                $transfer_location->old_route_id=$location->route_id;
                $transfer_location->old_route_location_id=$location->id;
                $transfer_location->old_ordinal=$location->ordinal;
            }

            $transfer_location->new_route_id=$request['route_id'][0];
            $transfer_location->new_route_location_id=$routeLoc->id;

            $transfer_location->save();
            $ordinal++;
        }

        return 'Locations Transferred Successfully';
    }

    public function reRoute($hubId,$routeId){

        $route = JoeyRouteLocations::join('sprint__tasks','joey_route_locations.task_id','=','sprint__tasks.id')
            ->join('locations','location_id','=','locations.id')
            ->join('merchantids','merchantids.task_id','=','joey_route_locations.task_id')
            ->whereNull('joey_route_locations.deleted_at')
            ->where('route_id','=',$routeId)
            ->whereNotIn('status_id',[17,36])
            ->get(['joey_route_locations.task_id','sprint_id','address','latitude','longitude','start_time','end_time']);


        if($route->count()<1){
            return "No order to route";
        }

        foreach($route as $routeLoc){

            $lat[0] = substr($routeLoc->latitude, 0, 2);
            $lat[1] = substr($routeLoc->latitude, 2);
            $latitude=$lat[0].".".$lat[1];

            $long[0] = substr($routeLoc->longitude, 0, 3);
            $long[1] = substr($routeLoc->longitude, 3);
            $longitude=$long[0].".".$long[1];

            $orders[$routeLoc->task_id]= array(
                "location" => array(
                    "name" => $routeLoc->address,
                    "lat" => $latitude,
                    "lng" => $longitude
                ),
                "start" => $routeLoc->start_time,
                "end" => $routeLoc->end_time,
                "load" => 1,
                "duration" => 2
            );
        }

        $hubPick = Hub::find($hubId);
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
        else{
            $error = new LogRoutes();
            $error->error = 'hub address or format is incorrect';
            $error->save();

            // $this->setResponse('hub address or format is incorrect',400);
            // return $this->getResponse();

            echo '<script>alert("hub address or format is incorrect")</script>';
            echo "<script> window.history.back(); </script>";

        }

        $joey['joey'] = array(
            "start_location" => array(
                "id" => 1,
                "name" => $hubPick->address,
                "lat" => $hubLat,
                "lng" => $hubLong
            ),
            "shift_start" => '00:00',
            "shift_end" => '23:00'
        );

        $options = array(
            "shortest_distance" => true,
            "polylines" => true
        );

        $payload = array(
            "visits" => $orders,
            "fleet" => $joey,
            "options" => $options
        );

        //dd($payload);

        $client = new Client( '/vrp' );
        $client->setData($payload);
        $apiResponse= $client->send();

        $apiResponse_data = array();
        foreach ($apiResponse->solution as $data){
            array_push($apiResponse_data,$data);
//            $apiResponse = $data;
        }

        if(!empty($apiResponse->solution)){
            foreach($apiResponse->solution as $solution){
                for($i=1;$i<count($apiResponse_data);$i++){
                    JoeyRouteLocations::where('task_id','=',$solution[$i]->location_id)->delete();

                    $routeLoc = new JoeyRouteLocations();
                    $routeLoc->route_id = $routeId;
                    $routeLoc->ordinal = $i;
                    $routeLoc->task_id = $solution[$i]->location_id;
                    $routeLoc->arrival_time = $solution[$i]->arrival_time;
                    $routeLoc->finish_time = $solution[$i]->finish_time;
                    $routeLoc->distance = $solution[$i]->distance;
                    $routeLoc->save();
                }
            }
            $return = "Route R-".$routeId." Rerouted Successfully";
        }

        return $return;
    }

    public function getroutificjob(Request $request,$id){
        $date=$request->get('date');
        $hub_id=$request->get('id');
        if(empty($date)){
            $date=date('Y-m-d');
        }

        $datas = SlotJob::leftJoin('zones_routing','zone_id','=','zones_routing.id')
            ->whereNull('slots_jobs.deleted_at')
            ->where('slots_jobs.created_at','like',$date.'%')
            ->where('slots_jobs.hub_id','=',$id)
            ->where('is_big_box','=',0)
            ->get(['job_id','title','status','slots_jobs.id','is_custom_route']);

        return backend_view('routific.routific_job',compact('datas','id'));
    }

    public function droutificjob(Request $request){

        SlotJob::where('id','=',$request->get('delete_id'))->update(['status'=>'finished','deleted_at'=>date('Y-m-d h:i:s')]);
        return redirect()->back();
    }

    public function getoutfordeliveryindex(Request $request){
        $date=$request->get('date');

        if(empty($date))
        {
            $date=date('Y-m-d');
        }

        $sprint=JoeyRoute::where('date','like',$date."%")->get();

        return backend_view('routific.outfordeliverypage',compact('sprint'));
    }

    public function postoutfordeliverystatus(Request $request){
        $inputs = $request->get('route_id');
        $route= Sprint::join('sprint__tasks','sprint__tasks.sprint_id','=','sprint__sprints.id')
            ->join('joey_route_locations','joey_route_locations.task_id','=','sprint__tasks.id')
            ->where('type','=','pickup')
            ->where('sprint__sprints.status_id','=',133)
            ->whereIn('joey_route_locations.route_id',$inputs)
            ->get(['sprint__sprints.id','sprint__tasks.id as task_id']);

        if(count($route)==0)
        {
            return back()->with('error','there is no order to update');
        }

        foreach($route as $id)
        {
            Task::where('id','=',$id->task_id)->update(['status_id'=>121]);
            Sprint::where('id','=',$id->id)->update(['status_id'=>121]);
            TaskHistory::insert(array('sprint_id'=>$id->id,'sprint__tasks_id'=>$id->task_id,
                'status_id'=>121,'date'=>date('Y-m-d H:i:s'),'created_at'=>date('Y-m-d H:i:s')));
        }

        return back()->with('success','Updated Successfully!');
    }

    public function postrouteAssignCreate(Request $request){

        foreach($request->get('route_id') as $id)
        {
            $hubRoute = new JoeyHubRoute();
            $hubRoute->route_id = $id;
            $hubRoute->joey_id = $request->get('joey_id');
            $hubRoute->save();
        }

        return back()->with('success','Route Assign Successfully!');


    }

    public function deleteroutes(Request $request){
        $id=$request->get('delete_id');
        Sprint::where('id','=',$id)->update(['deleted_at'=>date('Y-m-d H:i:s')]);
        return back()->with('success','Route deleted Successfully!');
    }

    public function getroutedetail($id){
        $response= JoeyRouteLocations::join('merchantids','merchantids.task_id','=','joey_route_locations.task_id')
            ->join('sprint__tasks','sprint__tasks.id','=','merchantids.task_id')
            ->join('sprint__sprints','sprint__sprints.id','=','sprint__tasks.sprint_id')
            ->where('route_id','=',$id)
            ->whereNull('joey_route_locations.deleted_at')
            ->orderby('sprint__tasks.id')
            ->get(['merchantids.tracking_id','merchantids.merchant_order_num','merchantids.task_id','sprint__tasks.sprint_id']);
        return json_encode($response);
    }

    public function getrouteAssign($id){

        if(!isset($_REQUEST['date']) || empty($_REQUEST['date'])){
            $date = "20".date('y-m-d');
        }
        else {
            $date = $_REQUEST['date'];
        }

        $routes = JoeyHubRoute::join('joey_routes','joey_routes.id','=','route_id')
            ->join('joey_route_locations','joey_route_locations.route_id','=','joey_routes.id')
            ->join('joeys','joeys.id','=','hub_routes.joey_id')
            ->whereNull('hub_routes.deleted_at')
            ->whereNull('joey_routes.deleted_at')
            ->whereNull('joey_route_locations.deleted_at')
            ->where('joey_routes.date','like',$date.'%')
            ->where('joey_routes.hub','=',$id)
            ->groupBy('joey_routes.id')
            ->get(['hub_routes.joey_id','hub_routes.route_id','date','first_name','last_name','hub_joey_type']);

        return backend_view('routific.hub-joey-assign',['data'=>$routes,'hub_id'=>$id]);
    }

    public function postjoeyroutedelete(Request $request){
        $data=$request->All();
        JoeyHubRoute::where('joey_id','=',$data['delete_idj'])->where('route_id','=',$data['delete_idr'])->update(['deleted_at'=>date('Y-m-d H:i:s')]);
        return back()->with('success','Route deleted Successfully!');
    }

    public function getedit_routes($id){

        $data=JoeyRouteLocations::join('merchantids','merchantids.task_id','=','joey_route_locations.task_id')
            ->join('sprint__tasks','sprint__tasks.id','=','merchantids.task_id')
            ->join('sprint__sprints','sprint__sprints.id','=','sprint__tasks.sprint_id')
            ->where('joey_route_locations.route_id','=',$id)
            ->orderby('joey_route_locations.ordinal')
            ->whereNull('sprint__tasks.deleted_at')
            ->whereNull('joey_route_locations.deleted_at')
            ->whereNull('merchantids.deleted_at')
            ->get(['merchantids.merchant_order_num','merchantids.tracking_id','merchantids.task_id','sprint__tasks.sprint_id as order_id']);
        //dd($data);
        // $data=['merchant_order_num'=>'sdasda','tracking_id'=>'asdas','task_id'=>'asda','order_id'=>'asd'];
        return backend_view('routific.edit-route-routific',['datas'=>$data,'id'=>$id]);
    }

    public function createroute($id){

        $url= "/jobs";

        $client = new Client($url);
        $client->setJobID($id);
        $apiResponse = $client->getJobResults();

        $job=SlotJob::where('job_id','=',$id)->first();

        SlotJob::where('job_id','=',$job->job_id)->update(['status'=>$apiResponse['status']]);

        if($apiResponse['status']=='finished'){

            $solution = $apiResponse['output']['solution'];

            if(!empty($solution)){

                foreach ($solution as $key => $value){

                    if(count($value)>1){

                        $Route = new JoeyRoute();

                        //$Route->joey_id = $key;
                        $Route->date =date('Y-m-d H:i:s');
                        $Route->hub = $job->hub_id;
                        $Route->zone = $job->zone_id;
                        // $Route->total_travel_time=$apiResponse['output']['total_travel_time'];
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

                        $Route->save();

                        for($i=0;$i<count($value);$i++){
                            if($i>0){

                                JoeyRouteLocations::where('task_id','=',$value[$i]['location_id'])->update(['deleted_at'=>date('Y-m-d H:i:s')]);

                                $routeLoc = new JoeyRouteLocations();
                                $routeLoc->route_id = $Route->id;
                                $routeLoc->ordinal = $i;
                                $routeLoc->task_id = $value[$i]['location_id'];

                                if(isset($value[$i]['distance']) && !empty($value[$i]['distance'])){
                                    $routeLoc->distance = $value[$i]['distance'];
                                }

                                if(isset($value[$i]['arrival_time']) && !empty($value[$i]['arrival_time'])){
                                    $routeLoc->arrival_time = $value[$i]['arrival_time'];
                                    $routeLoc->finish_time = $value[$i]['finish_time'];
                                }
                                $routeLoc->save();

                                $sprint = Task::where('id','=',$value[$i]['location_id'])->first();

                                $amazon_enteries = AmazonEntry::where('sprint_id','=',$sprint->sprint_id)->
                                whereNUll('deleted_at')->
                                first();
                                if($amazon_enteries!=null)
                                {
                                    $amazon_enteries->route_id=$Route->id;
                                    $amazon_enteries->ordinal=$i;
                                    $amazon_enteries->task_status_id=$sprint->status_id;
                                    $amazon_enteries->save();
                                }

                                $ctc_entries = CTCEntry::where('sprint_id','=',$sprint->sprint_id)->
                                whereNUll('deleted_at')->
                                first();
                                if($ctc_entries!=null)
                                {
                                    $ctc_entries->route_id=$Route->id;
                                    $ctc_entries->ordinal=$i;
                                    $ctc_entries->task_status_id=$sprint->status_id;
                                    $ctc_entries->save();
                                }

                                Sprint::where('id','=',$sprint->sprint_id)->update(['in_hub_route'=>1]);

                            }
                        }
                    }
                }

            }
        }

        else{

            $error = new LogRoutes();
            $error->error = $job->job_id." is in ".$apiResponse['status'];
            $error->save();

            return back()->with('error','Routes creation is in process');
        }
        return back()->with('success','Route Created Successfully!');

    }

    public function createCustomRoute($id){

        $url= "/jobs";

        $client = new Client($url);
        $client->setJobID($id);
        $apiResponse = $client->getJobResults();

        $job=SlotJob::where('job_id','=',$id)->first();


        SlotJob::where('job_id','=',$job->job_id)->update(['status'=>$apiResponse['status']]);

        if($apiResponse['status']=='finished'){

            $solution = $apiResponse['output']['solution'];

            if(!empty($solution)){

                foreach ($solution as $key => $value){

                    if(count($value)>1){

                        $Route = new JoeyRoute();

                        //$Route->joey_id = $key;
                        $Route->date =date('Y-m-d H:i:s');
                        $Route->hub = $job->hub_id;
                        $Route->zone = $job->zone_id;
                        // $Route->total_travel_time=$apiResponse['output']['total_travel_time'];
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

                        $Route->save();

                        for($i=0;$i<count($value);$i++){
                            if($i>0){


                                JoeyRouteLocations::where('task_id','=',$value[$i]['location_id'])->update(['deleted_at'=>date('Y-m-d H:i:s')]);
                                $trackingId=MerchantIds::where('task_id','=',$value[$i]['location_id'])->first();

                                CustomRoutingTrackingId::where('tracking_id',$trackingId->tracking_id)->update(['deleted_at'=>date('Y-m-d H:i:s')]);

                                $routeLoc = new JoeyRouteLocations();
                                $routeLoc->route_id = $Route->id;
                                $routeLoc->ordinal = $i;
                                $routeLoc->task_id = $value[$i]['location_id'];

                                if(isset($value[$i]['distance']) && !empty($value[$i]['distance'])){
                                    $routeLoc->distance = $value[$i]['distance'];
                                }

                                if(isset($value[$i]['arrival_time']) && !empty($value[$i]['arrival_time'])){
                                    $routeLoc->arrival_time = $value[$i]['arrival_time'];
                                    $routeLoc->finish_time = $value[$i]['finish_time'];
                                }
                                $routeLoc->save();

                                $sprint = Task::where('id','=',$value[$i]['location_id'])->first();

                                $amazon_enteries = AmazonEntry::where('sprint_id','=',$sprint->sprint_id)->
                                whereNUll('deleted_at')->
                                first();
                                if($amazon_enteries!=null)
                                {
                                    $amazon_enteries->route_id=$Route->id;
                                    $amazon_enteries->ordinal=$i;
                                    $amazon_enteries->task_status_id=$sprint->status_id;
                                    $amazon_enteries->save();
                                }

                                $ctc_entries = CTCEntry::where('sprint_id','=',$sprint->sprint_id)->
                                whereNUll('deleted_at')->
                                first();
                                if($ctc_entries!=null)
                                {
                                    $ctc_entries->route_id=$Route->id;
                                    $ctc_entries->ordinal=$i;
                                    $ctc_entries->task_status_id=$sprint->status_id;
                                    $ctc_entries->save();
                                }

                                Sprint::where('id','=',$sprint->sprint_id)->update(['in_hub_route'=>1]);

                            }
                        }
                    }
                }

            }
        }

        else{

            $error = new LogRoutes();
            $error->error = $job->job_id." is in ".$apiResponse['status'];
            $error->save();

            return back()->with('error','Routes creation is in process');
        }
        return back()->with('success','Route Created Successfully!');

    }

    public function getLocationMap($id,Request $request)
    {
        $date=$request->input('date');
        if($id==0)
        {
            $hub=17;
            $id=[477542,477255,477254,477283,477284,477286,477287,477288,477289,477307,477308,477309,477310,477311,477312,477313,477314,477292,477294,477315,477317,477316,477295
                ,477302,477303,477304,477305,477306,477296,477290,477297,477298,477299,477300,477320,477301,477318,477334,477335,477336,477337,477338,477339,477171];
        }
        elseif($id==477260)
        {
            $hub=16;
        }
        elseif($id==477282 || $id==477340 ||  $id==477341 ||  $id==477342 || $id==477343 ||  $id==477344 ||  $id==477345 || $id==477346 || $id== 476592)
        {
            $hub=19;
        }

        //$date=date('Y-m-d',strtotime("-3 day", strtotime($date)));
        $datas=JoeyRoute::
        where('hub','=',$hub)->
        where('joey_routes.date','like',$date."%")
            ->get(['joey_routes.id as route_id']);
        $value=[];
        $i=0;
        $key=[];
        foreach($datas as $data)
        {

            $location= JoeyRouteLocations::join('sprint__tasks','sprint__tasks.id','=','joey_route_locations.task_id')
                ->join('locations','locations.id','=','sprint__tasks.location_id')
                ->where('type','=','pickup')
                ->where('joey_route_locations.route_id','=',$data->route_id)
                ->get(['locations.longitude','locations.latitude','sprint__tasks.sprint_id','locations.address','joey_route_locations.ordinal']);
            if(!empty( $location))
            {
                $key[]=$data->route_id;
            }

            $j=0;
            foreach($location as $loc)
            {
                $lat[0] = substr($loc->latitude, 0, 2);
                $lat[1] = substr($loc->latitude, 2);
                $value['data'][$i][$j]['latitude'] = floatval($lat[0].".".$lat[1]);

                $long[0] = substr($loc->longitude, 0, 3);
                $long[1] = substr($loc->longitude, 3);
                $value['data'][$i][$j]['longitude'] = floatval($long[0].".".$long[1]);


                $value['data'][$i][$j]['sprint_id']=$loc->sprint_id;
                $value['data'][$i][$j]['address']=$loc->address;
                $value['data'][$i][$j]['route_id']=$data->route_id.'-'.$loc->ordinal;
                $j++;
            }
            //     $i=0;
            //     $k=0;
            //     for($i=0;$i<5;$i++){
            //     for($j=0;$j<5;$j++)
            //     {
            //         $value[$i][$j]['longitude']=-79.627221+$k;
            //         $value[$i][$j]['latitude']=43.630173+$k;
            //         $value[$i][$j]['route_id']=$i;
            //         $value[$i][$j]['sprint_id']=$j;
            //         $value[$i][$j]['address']='5030 Maingate Dr';
            //         $k++;

            //     }
            // }
            $i++;
        }

        $value['key']=$key;

        return json_encode($value);
    }
    public function assignMontrealRoute($routeId){
        JoeyRoute::where('id',$routeId)->update(['deleted_at'=> null]);
        return  "Route R-".$routeId." Undelete Successfully";
    }

    public function assignOttawaRoute($routeId){
        JoeyRoute::where('id',$routeId)->update(['deleted_at'=> null]);
        return  "Route R-".$routeId." Undelete Successfully";
    }

    public function assignCtcRoute($routeId){
        JoeyRoute::where('id',$routeId)->update(['deleted_at'=> null]);
        return  "Route R-".$routeId." Undelete Successfully";
    }


    public function getMarkIncomplete(Request $request)
    {
        $route_ids=[];

        $date = !empty($request->get('date')) ? $request->get('date') : date("Y-m-d");
        $postData = $request->all();
        $tracking_id_data=[];
        if(!empty($postData['hub']))
        {
            $tracking_id_data=JoeyRoute::join('joey_route_locations', 'joey_route_locations.route_id', '=', 'joey_routes.id')
                ->join('sprint__tasks', 'sprint__tasks.id', '=', 'joey_route_locations.task_id')
                ->join('sprint__sprints', 'sprint__sprints.id', '=', 'sprint__tasks.sprint_id')
                ->join('merchantids','merchantids.task_id','=','sprint__tasks.id')
                ->join('locations','locations.id','=','sprint__tasks.location_id')
                ->whereIn('sprint__sprints.status_id', [133,13, 61, 124,121,104,105,106,107,108,109,110,111,112,131,135,136,101,102,103])
                ->where('merchantids.tracking_id','NOT LIKE',"old%")
                ->whereNull('joey_route_locations.is_unattempted')
                ->whereNull('joey_route_locations.deleted_at')
                //->where('joey_routes.is_incomplete', 0)
                //->where("joey_routes.date",'like', $date ."%")
                ->where('joey_routes.hub',$postData['hub']);
            if(isset($postData['route_id']) && $postData['route_id'])
            {
                $tracking_id_data=$tracking_id_data->where('route_id','=',$postData['route_id']);
            }
            if(isset($postData['status_id']) && $postData['status_id'])
            {
                $tracking_id_data=$tracking_id_data->where('sprint__sprints.status_id','=',$postData['status_id']);
            }
            $route_ids=$tracking_id_data;
            $tracking_id_data=$tracking_id_data->get(['joey_route_locations.id','joey_route_locations.ordinal','joey_route_locations.route_id','sprint__sprints.status_id','merchantids.tracking_id','locations.address','locations.postal_code']);
            $route_ids=$route_ids->groupBy('route_id')->get(['route_id']);


        }
        return backend_view('routific.incomplete',compact('tracking_id_data','route_ids'));
    }

    public function markIncomplete(Request $request)
    {

        $ids = $request->get('ids');
        JoeyRouteLocations::whereIn('id',$ids)->update(['is_unattempted'=>1]);
        return response()->json( ['status_code'=>200,'success'=>'Route Mark Incomplete Successfully!']);

    }
    public function incompleteRoute($id,Request $request)
    {

        if(empty($request->input('date'))){
            $date = date('Y-m-d');
        }
        else{
            $date = $request->input('date');
        }

        if($id==16)
        {
            $vendor_id=[477260];
        }
        else if($id==19)
        {
            $vendor_id=[477282,477340,477341,477342,477343,477344,477345,477346,476592];
        }
        else
        {
            $vendor_id=[477542,475874,477255,477254,477283,477284,477286,477287,477288,477289,477307,477308,477309,477310,
                477311,477312,477313,477314,477292,477294,477315,477317,477316,477295,477302,477303,477304,477305,477306,477296,477290,477297,
                477298,477299,477300,477320,477301,477318,477334,477335,477336,477337,477338,477339,477171];
        }

        $routes =  JoeyRouteLocations::join('sprint__tasks','sprint__tasks.id','=','joey_route_locations.task_id')
            ->join('merchantids','merchantids.task_id','=','sprint__tasks.id')
            ->join('sprint__sprints','sprint_id','=','sprint__sprints.id')
            //->join('locations','locations.id','=','sprint__tasks.location_id')
            ->where('joey_route_locations.is_unattempted',1)
            ->whereNull('joey_route_locations.deleted_at')
//            ->whereBetween('joey_route_locations.created_at', [$date." 00:00:00", $date." 23:59:59"])
            // ->where("joey_route_locations.created_at",'like',$date."%")
            ->whereIN('creator_id',$vendor_id)
            ->distinct()
            ->get(['joey_route_locations.id','joey_route_locations.ordinal','joey_route_locations.route_id','merchantids.tracking_id','sprint__sprints.status_id']);

//        dd($routes);
        return backend_view('routific.incomplete-route',compact('routes'));

    }

    public function incompleteMontrealRoute(Request $request){
        date_default_timezone_set("America/Toronto");

        if(empty($request->input('date'))){
            $date = date('Y-m-d');
        }
        else{
            $date = $request->input('date');
        }

        $routes =  JoeyRoute::join('joey_route_locations','route_id','=','joey_routes.id')
            ->join('sprint__tasks','sprint__tasks.id','=','joey_route_locations.task_id')
            ->join('sprint__sprints','sprint_id','=','sprint__sprints.id')
            ->leftJoin('joeys','joey_routes.joey_id','=','joeys.id')
            ->whereNotIn('sprint__sprints.status_id',[36])
            /*->whereNOTNull('joey_routes.deleted_at')*/
            ->where('joey_routes.is_incomplete',1)
            ->whereNull('joey_route_locations.deleted_at')
            ->where(\DB::raw("CONVERT_TZ(joey_routes.date,'UTC','America/Toronto')"),'like',$date."%")
            ->where('creator_id','=',477260)
            ->distinct()
            ->get(['joey_routes.joey_id as joey_id','date','joey_routes.id as route_id','is_incomplete','first_name','last_name','joey_routes.total_travel_time',
                'joey_routes.total_distance']);

        $countQry = "SELECT route_id,
            COUNT(joey_route_locations.id) AS counts,
            SUM(CASE WHEN sprint__sprints.status_id = 17 THEN 0 ELSE 1 END) AS d_counts,
            SUM(joey_route_locations.distance) AS distance,
            SUM(CASE WHEN sprint__sprints.status_id = 17 THEN 0 ELSE joey_route_locations.distance END) AS d_distance,
            SEC_TO_TIME(SUM(TIME_TO_SEC(finish_time)-TIME_TO_SEC(arrival_time))) AS duration,
            SEC_TO_TIME(SUM(CASE WHEN sprint__sprints.status_id = 17 THEN 0 ELSE TIME_TO_SEC(finish_time)-TIME_TO_SEC(arrival_time) END)) AS d_duration
            FROM joey_route_locations 
            JOIN sprint__tasks ON(task_id=sprint__tasks.id) 
            JOIN sprint__sprints ON(sprint_id=sprint__sprints.id) 
            JOIN joey_routes ON(route_id=joey_routes.id) 
            WHERE creator_id=477260 
            /*AND joey_route_locations.created_at LIKE '".$date."%'*/
            AND sprint__tasks.`deleted_at` IS NULL
            AND joey_route_locations.`deleted_at` IS NULL 
            AND joey_routes.deleted_at IS NOT NULL GROUP BY route_id";

        $counts = DB::select($countQry);
        //dd($counts);
        return backend_view('routific.montreal-incomplete-route',compact('routes','counts'));
    }

    public function incompleteOttawaRoute(Request $request){
        date_default_timezone_set("America/Toronto");

        if(empty($request->input('date'))){
            $date = date('Y-m-d');
        }
        else{
            $date = $request->input('date');
        }

        $routes =  JoeyRoute::join('joey_route_locations','route_id','=','joey_routes.id')
            ->join('sprint__tasks','sprint__tasks.id','=','joey_route_locations.task_id')
            ->join('sprint__sprints','sprint_id','=','sprint__sprints.id')
            ->leftJoin('joeys','joey_routes.joey_id','=','joeys.id')
            ->whereNotIn('sprint__sprints.status_id',[36])
            /*->whereNotNull('joey_routes.deleted_at')*/
            ->where('joey_routes.is_incomplete',1)
            ->whereNull('joey_route_locations.deleted_at')
            ->where(\DB::raw("CONVERT_TZ(joey_routes.date,'UTC','America/Toronto')"),'like',$date."%")
            ->whereIn('creator_id',[477282,477340,477341,477342,477343,477344,477345,477346,476592])
            ->distinct()
            ->get(['joey_routes.joey_id as joey_id','is_incomplete','date','joey_routes.id as route_id','first_name','last_name','joey_routes.total_travel_time',
                'joey_routes.total_distance']);

        $countQry = "SELECT route_id,
            COUNT(joey_route_locations.id) AS counts,
            SUM(CASE WHEN sprint__sprints.status_id = 17 THEN 0 ELSE 1 END) AS d_counts,
            SUM(joey_route_locations.distance) AS distance,
            SUM(CASE WHEN sprint__sprints.status_id = 17 THEN 0 ELSE joey_route_locations.distance END) AS d_distance,
            SEC_TO_TIME(SUM(TIME_TO_SEC(finish_time)-TIME_TO_SEC(arrival_time))) AS duration,
            SEC_TO_TIME(SUM(CASE WHEN sprint__sprints.status_id = 17 THEN 0 ELSE TIME_TO_SEC(finish_time)-TIME_TO_SEC(arrival_time) END)) AS d_duration
            FROM joey_route_locations 
            JOIN sprint__tasks ON(task_id=sprint__tasks.id) 
            JOIN sprint__sprints ON(sprint_id=sprint__sprints.id) 
            JOIN joey_routes ON(route_id=joey_routes.id) 
            WHERE creator_id IN (477282,477340,477341,477342,477343,477344,477345,477346) 
            AND joey_routes.date LIKE '".$date."%'
            AND sprint__tasks.`deleted_at` IS NULL
            AND joey_route_locations.`deleted_at` IS NULL 
            AND joey_routes.deleted_at IS NOT NULL 
            GROUP BY route_id";

        $counts = DB::select($countQry);
        //dd($counts);
        return backend_view('routific.ottawa-incomplete-route',compact('routes','counts'));
    }

    public function incompleteCtcRoute(Request $request){
        date_default_timezone_set("America/Toronto");


        if(empty($request->input('date')))
        {
            $date = date('Y-m-d');
        }
        else{
            $date = $request->input('date');
        }

        $route =  JoeyRoute::join('joeys','joey_id','=','joeys.id')
            ->join('joey_route_locations','route_id','=','joey_routes.id')
            ->join('sprint__tasks','sprint__tasks.id','=','joey_route_locations.task_id')
            ->join('sprint__sprints','sprint_id','=','sprint__sprints.id')
            ->join('sprint__sprint_zone','sprint__sprint_zone.sprint_id','=','sprint__sprints.id')
            ->whereIn('zone_id',[88,89,95,97,103,106,107,109,110,114,115,116])
            ->whereNotIn('sprint__sprints.status_id',[36])
            ->whereNull('sprint__sprints.deleted_at')
            /*->whereNotNull('joey_routes.deleted_at')*/
            ->where('joey_routes.is_incomplete',1)
            ->where(\DB::raw("CONVERT_TZ(joey_routes.date,'UTC','America/Toronto')"),'like',$date."%")
            ->distinct()
            ->get(['joey_routes.joey_id as joey_id','date','is_incomplete','joey_routes.id as route_id','first_name','last_name','joey_routes.total_travel_time',
                'joey_routes.total_distance']);

        $countQry = "SELECT route_id,
        COUNT(joey_route_locations) AS counts,
        SUM(CASE WHEN sprint__sprints.status_id = 17 THEN 0 ELSE 1 END) AS d_counts,
        SUM(joey_route_locations.distance) AS distance,
        SUM(CASE WHEN sprint__sprints.status_id = 17 THEN 0 ELSE joey_route_locations.distance END) AS d_distance,
        SEC_TO_TIME(SUM(TIME_TO_SEC(finish_time)-TIME_TO_SEC(arrival_time))) AS duration,
        SEC_TO_TIME(SUM(CASE WHEN sprint__sprints.status_id = 17 THEN 0 ELSE TIME_TO_SEC(finish_time)-TIME_TO_SEC(arrival_time) END)) AS d_duration
        FROM joey_route_locations 
        JOIN sprint__tasks ON(task_id=sprint__tasks.id) 
        JOIN sprint__sprints ON(sprint_id=sprint__sprints.id) 
        JOIN joey_routes ON(route_id=joey_routes.id) 
        WHERE creator_id IN(477542,477171,477255,477254,477283,477284,477286,477287,477288,477289,477307,477308,477309,477310,477311,477312,477313,477314,477292,477294,477315,477317,477316,477295,477302,477303,477304,477305,477306,477296,477290,477297,477298,477299,477300,477320,477301,477318) 
        AND joey_routes.date LIKE '".$date."%' 
        AND sprint__tasks.`deleted_at` IS NULL
        AND joey_route_locations.`deleted_at` IS NULL 
        AND joey_routes.`deleted_at` IS NOT NULL 
        GROUP BY route_id";

        $counts = DB::select($countQry);
        return backend_view('routific.ctc-incomplete-route',['routes'=>$route,'counts'=>$counts]);
    }

    public function montrealRoutificControls1(Request $request)
    {
        return backend_view('routific.montreal-test-routific');
    }

    public function data(DataTables $datatables, Request $request)
    {


        date_default_timezone_set("America/Toronto");

        if(empty($request->input('date'))){
            $date = date('Y-m-d');
        }
        else{
            $date = $request->input('date');
        }
        $routes = JoeyRoute::join('joey_route_locations','route_id','=','joey_routes.id')
            ->join('sprint__tasks','sprint__tasks.id','=','joey_route_locations.task_id')
            ->join('sprint__sprints','sprint_id','=','sprint__sprints.id')
            ->join('locations','sprint__tasks.location_id','=','locations.id')
            ->join('slots_postal_code','slots_postal_code.postal_code','=',\DB::raw("SUBSTRING(locations.postal_code,1,3)"))
            ->join('zones_routing','slots_postal_code.zone_id','=','zones_routing.id')
            ->leftJoin('joeys','joey_routes.joey_id','=','joeys.id')
            ->whereNotIn('sprint__sprints.status_id',[36])
            ->whereNull('joey_routes.deleted_at')
            ->whereNull('joey_route_locations.deleted_at')
            ->whereNull('slots_postal_code.deleted_at')
            ->whereNull('zones_routing.deleted_at')
            ->where('joey_routes.date','like',$date."%")
            ->where('creator_id','=',477260)
            ->distinct()
            ->select([DB::raw('COUNT(joey_route_locations.id) AS counts'),
                DB::raw('SUM(CASE WHEN sprint__sprints.status_id in(113,114,17) THEN 0 ELSE 1 END) AS d_counts'),
                DB::raw('SUM(joey_route_locations.distance) AS distance'),
                DB::raw('SUM(CASE WHEN sprint__sprints.status_id in(113,114,17) THEN 0 ELSE joey_route_locations.distance END) AS d_distance'),
                DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(finish_time)-TIME_TO_SEC(arrival_time))) AS duration'),
                DB::raw('SEC_TO_TIME(SUM(CASE WHEN sprint__sprints.status_id in(113,114,17) THEN 0 ELSE TIME_TO_SEC(finish_time)-TIME_TO_SEC(arrival_time) END)) AS d_duration'),
                DB::raw('CONCAT(title,"(",zone_id,")") AS zone'),
                'joey_routes.joey_id as joey_id','date','joey_routes.id as route_id','first_name','last_name','joey_routes.total_travel_time',
                'joey_routes.total_distance'])
            ->groupBy('route_id')
//->get()
        ;



        return $datatables->eloquent($routes)
            ->addIndexColumn()

            ->editColumn('route_id', static function ($routes) {

                return 'R-'.$routes->route_id;
            })
            ->editColumn('first_name', static function ($routes) {
                return $routes->first_name.' '.$routes->last_name;
            })

            ->editColumn('zone', static function ($routes) {
                return $routes->zone;
            })
            ->editColumn('duration', static function ($routes) {
                $duration = (!empty($routes->duration) || $routes->duration != NULL) ?$routes->duration : 0 ;
                /* $duration = 0;
                if (!empty($routes->duration) || $routes->duration != NULL) {
                $duration = $routes->duration;
                }*/

                return $duration;
            })

            ->editColumn('distance', static function ($routes) {
                $distance = (!empty($routes->distance) || $routes->distance != NULL ) ? round($routes->distance / 1000, 2) : 0 ;
                $d_distance = (!empty($routes->d_distance) || $routes->d_distance != NULL ) ? round($routes->d_distance/1000,2) : 0 ;
                /*if (!empty($routes->distance) || $routes->distance != NULL) {
                return $distance = round($routes->distance / 1000, 2);
                } else {
                return $distance = 0;
                }
                if(!empty($routes->d_distance) || $routes->d_distance!=NULL ){
                return $d_distance = round($routes->d_distance/1000,2);
                }else*/
                return $d_distance."km/".$distance."km";
            })

            ->editColumn('order', static function ($routes) {
                return $routes->d_counts."/".$routes->counts;
            })

            ->addColumn('action', static function ($routes) {
                return backend_view('routific.montreal-action',compact('routes'));
            })
            ->make(true);
    }

    public function getOttawaRoutific(Request $request)
    {
        return backend_view('routific.ottawa-test-routific');
    }

    public function ottawaRoutificControlsData(Datatables $datatables, Request $request){
        /*dd('sada');*/
        date_default_timezone_set("America/Toronto");
        if(empty($request->input('date'))){
            $date = date('Y-m-d');
        }
        else{
            $date = $request->input('date');
        }
        $routes =  JoeyRoute::join('joey_route_locations','route_id','=','joey_routes.id')
            ->join('sprint__tasks','sprint__tasks.id','=','joey_route_locations.task_id')
            ->join('sprint__sprints','sprint_id','=','sprint__sprints.id')
            ->join('locations','sprint__tasks.location_id','=','locations.id')
            ->join('slots_postal_code','slots_postal_code.postal_code','=',\DB::raw("SUBSTRING(locations.postal_code,1,3)"))
            ->join('zones_routing','slots_postal_code.zone_id','=','zones_routing.id')
            ->leftJoin('joeys','joey_routes.joey_id','=','joeys.id')
            ->whereNotIn('sprint__sprints.status_id',[36])
            ->whereNull('sprint__tasks.deleted_at')
            ->whereNull('zones_routing.deleted_at')
            ->whereNull('joey_routes.deleted_at')
            ->whereNull('slots_postal_code.deleted_at')
            ->whereNull('joey_route_locations.deleted_at')
            ->where('joey_routes.date','like',$date."%")
            ->whereIn('creator_id',[477282,477340,477341,477342,477343,477344,477345,477346,476592])
            ->distinct()
            ->select([
                DB::raw('CONCAT(title,"(",zone_id,")") AS zone'),
                DB::raw('COUNT(joey_route_locations.id) AS counts'),
                DB::raw('SUM(CASE WHEN sprint__sprints.status_id in(113,114,17) THEN 0 ELSE 1 END) AS d_counts'),
                DB::raw('SUM(joey_route_locations.distance) AS distance'),
                DB::raw('SUM(CASE WHEN sprint__sprints.status_id in(113,114,17) THEN 0 ELSE joey_route_locations.distance END) AS d_distance'),
                DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(finish_time)-TIME_TO_SEC(arrival_time))) AS duration'),
                DB::raw('SEC_TO_TIME(SUM(CASE WHEN sprint__sprints.status_id in(113,114,17) THEN 0 ELSE TIME_TO_SEC(finish_time)-TIME_TO_SEC(arrival_time) END)) AS d_duration'),
                'joey_routes.joey_id as joey_id','date','joey_routes.id as route_id','first_name','last_name','joey_routes.total_travel_time',
                'joey_routes.total_distance'])
            ->groupBy('route_id');

        return $datatables->eloquent($routes)
            ->addIndexColumn()

            ->editColumn('route_id', static function ($routes) {

                return 'R-'.$routes->route_id;
            })
            ->editColumn('first_name', static function ($routes) {
                return $routes->first_name.'    '.$routes->last_name;
            })
            /*->editColumn('zone', static function ($routes)  {
                return $routes->zone;
            })*/

            ->filterColumn('zone', static function($routes, $keyword) {
                $routes->whereRaw('CONCAT(title,"(",zone_id")") like ?', ["%{$keyword}%"]);
            })

            ->editColumn('duration', static function ($routes)   {
                $duration = (!empty($routes->duration) || $routes->duration != NULL) ?$routes->duration : 0 ;
                return $duration;
            })
            ->editColumn('distance', static function ($routes)   {
                $distance = (!empty($routes->distance) || $routes->distance != NULL ) ? round($routes->distance / 1000, 2) : 0 ;
                $d_distance = (!empty($routes->d_distance) || $routes->d_distance != NULL ) ? round($routes->d_distance/1000,2) : 0 ;
                return $d_distance."km/".$distance."km";
            })
            ->editColumn('order', static function ($routes) {
                return $routes->d_counts."/".$routes->counts;
            })
            ->addColumn('action', static function ($routes) {
                return backend_view('routific.ottawa-action',compact('routes'));
            })
            ->make(true);


    }

    public function customMontrealRoutificCreate(Request $request){
        $user= Auth::user();

        $postals= SlotsPostalCode::where('zone_id','=',$request['zone'])->pluck('postal_code')->toArray();
        $tracking_ids=CustomRoutingTrackingId::where('hub_id','=',16)
            ->where('vendor_id','=',477260)->
            whereIn(\DB::raw('SUBSTRING(custom_routing_tracking_id.postal_code,1,3)'),$postals)->
            whereNotNull('tracking_id')
            ->where('user_id','=', $user->id)
            ->where('valid_id','=',1)
            ->whereNull('deleted_at')
            ->pluck('tracking_id')
            ->toArray();



        $sprints = Task::join('sprint__sprints','sprint__tasks.sprint_id','=','sprint__sprints.id')
            ->join('locations','location_id','=','locations.id')
            ->join('merchantids','task_id','=','sprint__tasks.id')
            ->whereIn('merchantids.tracking_id',$tracking_ids)
            ->whereIn('sprint__sprints.creator_id',[477260])
            ->where('type','=','pickup')
            ->orderBy('locations.postal_code')
            ->take(2000)
            ->get(['start_time','end_time','sprint__tasks.id','sprint__tasks.sprint_id','due_time','locations.address','locations.latitude','locations.longitude','locations.postal_code','locations.city_id']);

        if(count($sprints)<1){
            return back()->with('error','No Order in this hub');


        }


        $orders = array();

        foreach($sprints as $sprint){

            $lat[0] = substr($sprint->latitude, 0, 2);
            $lat[1] = substr($sprint->latitude, 2);
            $latitude=$lat[0].".".$lat[1];

            $long[0] = substr($sprint->longitude, 0, 3);
            $long[1] = substr($sprint->longitude, 3);
            $longitude=$long[0].".".$long[1];

            if(empty($sprint->city_id) || $sprint->city_id==NULL){
                $dropoffAdd = $this->canadian_address($sprint->address.','.$sprint->postal_code.',canada');
                $latitude = $dropoffAdd['lat'];
                $longitude = $dropoffAdd['lng'];
            }

            $start = $sprint->start_time;
            $end = $sprint->end_time;

            $orders[$sprint->id]= array(
                "location" => array(
                    "name" => $sprint->address,
                    "lat" => $latitude,
                    "lng" => $longitude
                ),
                //"start" => $start,
                //"end" => $end,
                "load" => 1,
                "duration" => 2
            );

        }

        $hubPick = Hub::where('id','=',16)->first();
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


        $joeycounts=Slots::join('vehicles','slots.vehicle','=','vehicles.id')
            ->where('slots.zone_id','=',$request['zone'])
            ->whereNull('slots.deleted_at')
            ->get(['vehicles.capacity','vehicles.min_visits','slots.start_time','slots.end_time','slots.hub_id','slots.joey_count']);

        if(count($joeycounts)<1){
            return back()->with('error','No Slot defined');

        }
        $i=0;
        foreach($joeycounts as $joe){
            if(!empty($joe->joey_count)){
                $joeycount= $joe->joey_count;
            }
            if(!isset($joeycount) || empty($joeycount)){
                return back()->with('error','No Joey Count should be greater than 1 in slot');

            }
            for($j=0;$j<$joeycount;$j++){

                $shifts["joey_".$i] = array(
                    "start_location" => array(
                        "id" => $i,
                        "name" => $hubPick->address,
                        "lat" => $hubLat,
                        "lng" => $hubLong
                    ),
                    "shift_start" => date('H:i',strtotime($joe->start_time)),
                    "shift_end" => date('H:i',strtotime($joe->end_time)),
                    "capacity" => $joe->capacity,
                    "min_visits_per_vehicle" => $joe->min_visits
                );

                $i++;
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

        // dd(json_encode($payload));

        $client = new Client( '/vrp-long' );
        $client->setData($payload);
        $apiResponse= $client->send();

        if(!empty($apiResponse->error)){

            print_r($apiResponse->error);
            die();

        }


        $slotjob  = new  SlotJob();
        $slotjob->job_id=$apiResponse->job_id;
        $slotjob->hub_id=16;
        $slotjob->zone_id=$request['zone'];
        $slotjob->unserved=null;
        $slotjob->save();


        $job = "Request Submited Job_id ".$apiResponse->job_id;

        return back()->with('success',$job);


    }

    public function customOttawaRoutificCreate(Request $request){
        $user= Auth::user();

        $postals= SlotsPostalCode::where('zone_id','=',$request['zone'])->pluck('postal_code')->toArray();
        $tracking_ids=CustomRoutingTrackingId::where('hub_id','=',19)
            ->whereIn('vendor_id',[477282,477340,477341,477342,477343,477344,477345,477346,475874,476592])->
            whereIn(\DB::raw('SUBSTRING(custom_routing_tracking_id.postal_code,1,3)'),$postals)->
            whereNotNull('tracking_id')
            ->where('user_id','=', $user->id)
            ->where('valid_id','=',1)
            ->whereNull('deleted_at')
            ->pluck('tracking_id')
            ->toArray();



        $sprints = Task::join('sprint__sprints','sprint__tasks.sprint_id','=','sprint__sprints.id')
            ->join('locations','location_id','=','locations.id')
            ->join('merchantids','task_id','=','sprint__tasks.id')
            ->whereIn('merchantids.tracking_id',$tracking_ids)
            ->whereIn('sprint__sprints.creator_id',[477282,477340,477341,477342,477343,477344,477345,477346,475874])
            ->where('type','=','pickup')
            ->where('sprint__sprints.in_hub_route',0)
            ->orderBy('locations.postal_code')
            ->get(['start_time','end_time','sprint__tasks.id','sprint__tasks.sprint_id','due_time','locations.address','locations.latitude','locations.longitude','locations.postal_code','locations.city_id'])->toArray();



        if(count($sprints)<1){
            return back()->with('error','No Order in this hub');

        }


        $orders = array();

        foreach($sprints as $sprint){

            $lat[0] = substr($sprint['latitude'], 0, 2);
            $lat[1] = substr($sprint['latitude'], 2);
            $latitude=$lat[0].".".$lat[1];

            $long[0] = substr($sprint['longitude'], 0, 3);
            $long[1] = substr($sprint['longitude'], 3);
            $longitude=$long[0].".".$long[1];

            if(empty($sprint['city_id']) || $sprint['city_id']==NULL){
                $dropoffAdd = $this->canadian_address($sprint['address'].','.$sprint['postal_code'].',canada');

                if(!empty($dropoffAdd)){
                    $latitude = $dropoffAdd['lat'];
                    $longitude = $dropoffAdd['lng'];
                }

            }

            // $start = $sprint->start_time;
            // $end = $sprint->end_time;

            $orders[$sprint['id']]= array(
                "location" => array(
                    "name" => $sprint['address'],
                    "lat" => $latitude,
                    "lng" => $longitude
                ),
                // "start" => $start,
                // "end" => $end,
                "load" => 1,
                "duration" => 2
            );

        }

        //dd($orders);

        $hubPick = Hub::where('id','=',19)->first();
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

        $joeycounts=Slots::join('vehicles','slots.vehicle','=','vehicles.id')
            ->where('slots.zone_id','=',$request['zone'])
            ->whereNull('slots.deleted_at')
            ->get(['vehicles.capacity','vehicles.min_visits','slots.start_time','slots.end_time','slots.hub_id','slots.joey_count']);

        if(count($joeycounts)<1){
            return back()->with('error','No Slot in this zone');
        }

        $i=0;
        foreach($joeycounts as $joe){
            if(!empty($joe->joey_count)){
                $joeycount= $joe->joey_count;
            }
            if(!isset($joeycount) || empty($joeycount)){
                return back()->with('error','Joey Count should be greater than 1 in slot');

            }
            for($j=0;$j<$joeycount;$j++){

                $shifts["joey_".$i] = array(
                    "start_location" => array(
                        "id" => $i,
                        "name" => $hubPick->address,
                        "lat" => $hubLat,
                        "lng" => $hubLong
                    ),
                    "shift_start" => date('H:i',strtotime($joe->start_time)),
                    "shift_end" => date('H:i',strtotime($joe->end_time)),
                    "capacity" => $joe->capacity,
                    "min_visits_per_vehicle" => $joe->min_visits
                );

                $i++;
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
            print_r($apiResponse->error);
            die();
        }


        $slotjob  = new  SlotJob();
        $slotjob->job_id=$apiResponse->job_id;
        $slotjob->hub_id=19;
        $slotjob->zone_id=$request['zone'];
        $slotjob->unserved=null;
        $slotjob->save();

        $job = "Request Submited Job_id ".$apiResponse->job_id;

        return back()->with('success', $job);
    }

    public function customCtcRoutificCreate(Request $request){

        $user= Auth::user();

        $postals= SlotsPostalCode::where('zone_id','=',$request['zone'])->pluck('postal_code')->toArray();
        $tracking_ids=CustomRoutingTrackingId::where('hub_id','=',17)
            ->whereIn('vendor_id',[477542,477255,477254,477283,477284,477286,477287,477288,477289,477307,477308,477309,477310,
                477311,477312,477313,477314,477292,477294,477315,477317,477316,477295,477302,477303,477304,477305,477306,477296,477290,477297,
                477298,477299,477300,477320,477301,477318,477334,477335,477336,477337,477338,477339,477171])->
            whereIn(\DB::raw('SUBSTRING(custom_routing_tracking_id.postal_code,1,3)'),$postals)->
            whereNotNull('tracking_id')
            ->where('user_id','=', $user->id)
            ->where('valid_id','=',1)
            ->whereNull('deleted_at')
            ->pluck('tracking_id')
            ->toArray();

        $sprints = Task::join('sprint__sprints','sprint__tasks.sprint_id','=','sprint__sprints.id')
            ->join('locations','location_id','=','locations.id')
            ->join('merchantids','task_id','=','sprint__tasks.id')
            ->whereIn('merchantids.tracking_id',$tracking_ids)
            ->whereIn('sprint__sprints.creator_id',[477255,477254,477283,477284,477286,477287,477288,477289,477307,477308,477309,477310,
                477311,477312,477313,477314,477292,477294,477315,477317,477316,477295,477302,477303,477304,477305,477306,477296,477290,477297,
                477298,477299,477300,477320,477301,477318,477334,477335,477336,477337,477338,477339,477171,477542])
            ->where('type','=','pickup')
            ->orderBy('locations.postal_code')
            ->take(2000)
            ->get(['start_time','end_time','sprint__tasks.id','sprint__tasks.sprint_id','due_time','locations.address','locations.latitude','locations.longitude','locations.postal_code','locations.city_id']);

        if(count($sprints)<1){
            return back()->with('error','No Order in this Zone');

        }


        $orders = array();

        foreach($sprints as $sprint){

            $lat[0] = substr($sprint->latitude, 0, 2);
            $lat[1] = substr($sprint->latitude, 2);
            $latitude=$lat[0].".".$lat[1];

            $long[0] = substr($sprint->longitude, 0, 3);
            $long[1] = substr($sprint->longitude, 3);
            $longitude=$long[0].".".$long[1];

            if(empty($sprint->city_id) || $sprint->city_id==NULL){
                $dropoffAdd = $this->canadian_address($sprint->address.','.$sprint->postal_code.',canada');
                $latitude = $dropoffAdd['lat'];
                $longitude = $dropoffAdd['lng'];
            }

            $start = $sprint->start_time;
            $end = $sprint->end_time;

            $orders[$sprint->id]= array(
                "location" => array(
                    "name" => $sprint->address,
                    "lat" => $latitude,
                    "lng" => $longitude
                ),
                //"start" => $start,
                //"end" => $end,
                "load" => 1,
                "duration" => 2
            );

            Sprint::where('id','=',$sprint->sprint_id)->update(['status_id'=>124,"in_hub_route"=>1]);
            Task::where('sprint_id','=',$sprint->sprint_id)->update(['status_id'=>124]);
            $checkforstatus=TaskHistory::where('sprint_id','=',$sprint->sprint_id)->where('status_id','=',125)->first();
            // checking if order is Reattempt
            $isReattempt=SprintReattempt::where('sprint_id','=',$sprint->sprint_id)->first();

            if(!$checkforstatus && $isReattempt==null)
            {
                $pickupstoretime_date=new \DateTime();
                $pickupstoretime_date->modify('-2 minutes');

                $taskhistory=new TaskHistory();
                $taskhistory->sprint_id=$sprint->sprint_id;
                $taskhistory->sprint__tasks_id=$sprint->id;
                $taskhistory->status_id=125;
                $taskhistory->created_at = $pickupstoretime_date->format('Y-m-d H:i:s');
                $taskhistory->date = $pickupstoretime_date->format('Y-m-d H:i:s');
                $taskhistory->save();
            }
            $taskhistory=new TaskHistory();
            $taskhistory->sprint_id=$sprint->sprint_id;
            $taskhistory->sprint__tasks_id=$sprint->id;
            $taskhistory->status_id=124;
            $taskhistory->created_at=date("Y-m-d H:i:s");
            $taskhistory->date=date("Y-m-d H:i:s");
            $taskhistory->save();

        }

        //dd($orders);

        $hubPick = Hub::where('id','=',17)->first();
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

        $joeycounts=Slots::join('vehicles','slots.vehicle','=','vehicles.id')
            ->where('slots.zone_id','=',$request['zone'])
            ->get(['vehicles.capacity','vehicles.min_visits','slots.start_time','slots.end_time','slots.hub_id','slots.joey_count','custom_capacity']);

        if(count($joeycounts)<1){
            return back()->with('error','No slot in this zone');

        }



        foreach($joeycounts as $joe){
            if(!empty($joe->joey_count)){
                $joeycount= $joe->joey_count;
            }

            if(!isset($joeycount) || empty($joeycount)){
                return back()->with('error','Joey count should be greater than 1 in slot');

            }

            for($i=1;$i<=$joeycount;$i++){

                if(empty($joe->custom_capacity)){
                    $capacity = $joe->capacity;
                }
                else{
                    $capacity = $joe->custom_capacity;
                }
                $shifts["joey_".$i] = array(
                    "start_location" => array(
                        "id" => $i,
                        "name" => $hubPick->address,
                        "lat" => $hubLat,
                        "lng" => $hubLong
                    ),
                    "shift_start" => date('H:i',strtotime($joe->start_time)),
                    "shift_end" => date('H:i',strtotime($joe->end_time)),
                    "capacity" => $capacity,
                    "min_visits_per_vehicle" => $joe->min_visits
                );
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
            return back()->with('error',$apiResponse->error);
        }


        $slotjob  = new  SlotJob();
        $slotjob->job_id=$apiResponse->job_id;
        $slotjob->hub_id=17;
        $slotjob->zone_id=$request['zone'];
        $slotjob->unserved=null;
        $slotjob->is_custom_route = 1;
        $slotjob->save();

        $job = "Request Submited Job_id ".$apiResponse->job_id;

        return back()->with('success',$job);


    }

    public function routeJoeyData(Request $request)
    {

        $currentDateTime = new \DateTime();
        $currentDateTime->modify("-5 days");
        $date_range=$currentDateTime->format("Y-m-d")." 00:00:00";
        $route_id=$request->get('route_id');
        $date=JoeyRoute::where('id','=',$route_id)->first();
        $date=date('Y-m-d',strtotime($date->date));
        $hub_id=$request->get('hub_id');
        $routes = JoeyRoute::
        JOIN('joey_route_locations','joey_routes.id','=','route_id')
            ->JOIN('sprint__tasks','task_id','=','sprint__tasks.id')
            ->JOIN('joeys','joeys.id','=','joey_routes.joey_id')
            ->whereNull('joey_routes.deleted_at')
            ->whereNull('joey_route_locations.deleted_at')
            ->whereNotIn('status_id',[36,17])
            // ->where('joey_routes.date','like',$date."%")
            ->where('joey_routes.date','>',$date_range)
            ->where('joey_routes.date','<',date('Y-m-d')." 23:59:59")
            ->where('hub','=',$hub_id)
            ->distinct()->get(['route_id','joey_routes.joey_id','joeys.first_name','joeys.last_name'])->toArray();
        return $routes;

    }

    public function getMarkReturned(Request $request){


        $route_ids=[];

        $date = !empty($request->get('date')) ? $request->get('date') : date("Y-m-d");

        $postData = $request->all();

        $tracking_id_data=[];

        if(!empty($postData['hub']))
        {

            $tracking_id_data=JoeyRoute::join('joey_route_locations', 'joey_route_locations.route_id', '=', 'joey_routes.id')
                ->join('sprint__tasks', 'sprint__tasks.id', '=', 'joey_route_locations.task_id')
                ->join('sprint__sprints', 'sprint__sprints.id', '=', 'sprint__tasks.sprint_id')
                ->join('merchantids','merchantids.task_id','=','sprint__tasks.id')
                ->join('locations','locations.id','=','sprint__tasks.location_id')
                ->whereIn('sprint__sprints.status_id', [104,105,106,107,108,109,110,111,112,131,135,136,101,102,103])
                ->where('merchantids.tracking_id','NOT LIKE',"old%")

                ->whereNull('joey_route_locations.deleted_at')
                //->where('joey_routes.is_incomplete', 0)
                ->where("joey_routes.date",'like', $date ."%")
                ->where('joey_routes.hub',$postData['hub']);


            if(isset($postData['route_id']) && $postData['route_id'])
            {
                $tracking_id_data=$tracking_id_data->where('route_id','=',$postData['route_id']);
            }
            if(isset($postData['status_id']) && $postData['status_id'])
            {
                $tracking_id_data=$tracking_id_data->where('sprint__sprints.status_id','=',$postData['status_id']);

            }

            $route_ids=$tracking_id_data->groupBy('route_id')->get(['route_id']);
            $status_id=$tracking_id_data->groupBy('sprint__sprints.status_id')->distinct()->get(['sprint__sprints.status_id']);
            $tracking_id_data=$tracking_id_data->get(['joey_route_locations.id','sprint__sprints.is_reattempt','joey_route_locations.ordinal','joey_route_locations.route_id','sprint__sprints.status_id','merchantids.tracking_id','locations.address','locations.postal_code']);





        }
        return backend_view('routific.returned',compact('tracking_id_data','route_ids','status_id'));
    }

    public function markReturned(Request $request)
    {

        $ids = $request->get('ids');
        JoeyRouteLocations::whereIn('id',$ids)->update(['is_unattempted'=>1]);
        return response()->json( ['status_code'=>200,'success'=>'Route Mark Incomplete Successfully!']);

    }

    public function markattampt(Request $request)
    {
        $ids = $request->get('ids');
        JoeyRouteLocations::whereIn('id',$ids)->update(['is_unattempted'=>null]);
        return response()->json( ['status_code'=>200,'success'=>'Route Mark Attampt Successfully!']);
    }

    public function flowerRoutificControls(Request $request){

        date_default_timezone_set("America/Toronto");

        if(empty($request->input('date'))){
            $date = date('Y-m-d');
        }
        else{
            $date = $request->input('date');
        }

        $countQry = "SELECT route_id,joey_routes.joey_id,
        CONCAT(zones_routing.title,'(',joey_routes.zone,')') AS zone,
        CONCAT(first_name,' ',last_name) AS joey_name,
        joey_routes.date,
        COUNT(joey_route_locations.id) AS counts,
        SUM(CASE WHEN sprint__sprints.status_id IN(17,113,114,116,117,118,132,138,139,144,104,105,106,107,108,109,110,111,112,131,135,136) THEN 0 ELSE 1 END) AS d_counts,
        SUM(joey_route_locations.distance) AS distance,
        SUM(CASE WHEN sprint__sprints.status_id IN(17,113,114,116,117,118,132,138,139,144,104,105,106,107,108,109,110,111,112,131,135,136) THEN 0 ELSE joey_route_locations.distance END) AS d_distance,
        SEC_TO_TIME(SUM(TIME_TO_SEC(finish_time)-TIME_TO_SEC(arrival_time))) AS duration,
        SEC_TO_TIME(SUM(CASE WHEN sprint__sprints.status_id IN(17,113,114,116,117,118,132,138,139,144,104,105,106,107,108,109,110,111,112,131,135,136) THEN 0 ELSE TIME_TO_SEC(finish_time)-TIME_TO_SEC(arrival_time) END)) AS d_duration
        FROM joey_route_locations 
        JOIN sprint__tasks ON(task_id=sprint__tasks.id) 
        JOIN sprint__sprints ON(sprint_id=sprint__sprints.id) 
        JOIN joey_routes ON(route_id=joey_routes.id)
        LEFT JOIN joeys ON joey_routes.joey_id=joeys.id
        JOIN locations ON(sprint__tasks.location_id=locations.id)
        LEFT JOIN  zones_routing ON (zones_routing.id=joey_routes.zone AND zones_routing.deleted_at IS NULL)
        WHERE creator_id=476674 
        AND joey_routes.date LIKE '".$date."%'
        AND joey_route_locations.`deleted_at` IS NULL 
        AND joey_routes.deleted_at IS NULL GROUP BY route_id";

        $counts = DB::select($countQry);

        return backend_view('routific.flower-routific-new',compact('counts'));

    }

    public function createRouteNew(Request $request){
        $data=$request->all();

        $url= "/jobs";

        $client = new Client($url);
        $client->setJobID($data['id']);
        $apiResponse = $client->getJobResults();

        $job=SlotJob::where('job_id','=',$data['id'])->first();

        SlotJob::where('job_id','=',$job->job_id)->update(['status'=>$apiResponse['status']]);

        if($apiResponse['status']=='finished'){

            $solution = $apiResponse['output']['solution'];

            if(!empty($solution)){

                foreach ($solution as $key => $value){

                    if(count($value)>1){

                        $Route = new JoeyRoute();

                        //$Route->joey_id = $key;
                        $Route->date =date('Y-m-d H:i:s');
                        $Route->hub = $job->hub_id;
                        $Route->zone = $job->zone_id;
                        // $Route->total_travel_time=$apiResponse['output']['total_travel_time'];
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

                        $Route->save();

                        for($i=0;$i<count($value);$i++){
                            if($i>0){

                                JoeyRouteLocations::where('task_id','=',$value[$i]['location_id'])->update(['deleted_at'=>date('Y-m-d H:i:s')]);

                                $routeLoc = new JoeyRouteLocations();
                                $routeLoc->route_id = $Route->id;
                                $routeLoc->ordinal = $i;
                                $routeLoc->task_id = $value[$i]['location_id'];

                                if(isset($value[$i]['distance']) && !empty($value[$i]['distance'])){
                                    $routeLoc->distance = $value[$i]['distance'];
                                }

                                if(isset($value[$i]['arrival_time']) && !empty($value[$i]['arrival_time'])){
                                    $routeLoc->arrival_time = $value[$i]['arrival_time'];
                                    $routeLoc->finish_time = $value[$i]['finish_time'];
                                }
                                $routeLoc->save();

                                $sprint = Task::where('id','=',$value[$i]['location_id'])->first();

                                $amazon_enteries = AmazonEntry::where('sprint_id','=',$sprint->sprint_id)->
                                whereNUll('deleted_at')->
                                first();
                                if($amazon_enteries!=null)
                                {
                                    $amazon_enteries->route_id=$Route->id;
                                    $amazon_enteries->ordinal=$i;
                                    $amazon_enteries->task_status_id=$sprint->status_id;
                                    $amazon_enteries->save();
                                }

                                Sprint::where('id','=',$sprint->sprint_id)->update(['in_hub_route'=>1]);

                            }
                        }
                    }
                }

            }
        }

        else{

            $error = new LogRoutes();
            $error->error = $job->job_id." is in ".$apiResponse['status'];
            $error->save();

            return back()->with('error','Routes creation is in process');
        }
        return back()->with('success','Route Created Successfully!');

    }

    public function jobsDetailsNew($id){
        $url= "/jobs";

        $client = new Client($url);

        $client->setJobID($id);

        $apiResponse = $client->getJobResults();

        return $apiResponse;

    }

    public function createCustomRouteNew(Request $request){

        $data=$request->all();

        $url= "/jobs";

        $client = new Client($url);

        $client->setJobID($data['id']);

        $apiResponse = $client->getJobResults();


        //   dd($apiResponse['status']=='finished');

        $job=SlotJob::where('job_id','=',$data['id'])->first();


        SlotJob::where('job_id','=',$job->job_id)->update(['status'=>$apiResponse['status']]);


        if($apiResponse['status']=='finished'){

            $solution = $apiResponse['output']['solution'];

            if(!empty($solution)){

                foreach ($solution as $key => $value){

                    if(count($value)>1){

                        $Route = new JoeyRoute();

                        //$Route->joey_id = $key;
                        $Route->date =date('Y-m-d H:i:s');
                        $Route->hub = $job->hub_id;
                        $Route->zone = $job->zone_id;
                        // $Route->total_travel_time=$apiResponse['output']['total_travel_time'];
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

                        $Route->save();

                        for($i=0;$i<count($value);$i++){
                            if($i>0){


                                JoeyRouteLocations::where('task_id','=',$value[$i]['location_id'])->update(['deleted_at'=>date('Y-m-d H:i:s')]);
                                $trackingId=MerchantIds::where('task_id','=',$value[$i]['location_id'])->first();

                                CustomRoutingTrackingId::where('tracking_id',$trackingId->tracking_id)->update(['deleted_at'=>date('Y-m-d H:i:s')]);

                                $routeLoc = new JoeyRouteLocations();
                                $routeLoc->route_id = $Route->id;
                                $routeLoc->ordinal = $i;
                                $routeLoc->task_id = $value[$i]['location_id'];

                                if(isset($value[$i]['distance']) && !empty($value[$i]['distance'])){
                                    $routeLoc->distance = $value[$i]['distance'];
                                }

                                if(isset($value[$i]['arrival_time']) && !empty($value[$i]['arrival_time'])){
                                    $routeLoc->arrival_time = $value[$i]['arrival_time'];
                                    $routeLoc->finish_time = $value[$i]['finish_time'];
                                }
                                $routeLoc->save();

                                $sprint = Task::where('id','=',$value[$i]['location_id'])->first();

                                $amazon_enteries = AmazonEntry::where('sprint_id','=',$sprint->sprint_id)->
                                whereNUll('deleted_at')->
                                first();
                                if($amazon_enteries!=null)
                                {
                                    $amazon_enteries->route_id=$Route->id;
                                    $amazon_enteries->ordinal=$i;
                                    $amazon_enteries->task_status_id=$sprint->status_id;
                                    $amazon_enteries->save();
                                }

                                Sprint::where('id','=',$sprint->sprint_id)->update(['in_hub_route'=>1]);

                            }
                        }
                    }
                }

            }
        }

        else{

            $error = new LogRoutes();
            $error->error = $job->job_id." is in ".$apiResponse['status'];
            $error->save();

            return back()->with('error','Routes creation is in process');
        }
        return back()->with('success','Route Created Successfully!');

    }

}