<?php

namespace App\Http\Controllers\Backend;

use App\AmazonEnteries;
use App\DeliveryProcessType;
use App\Post;
use Illuminate\Http\Request;
use App\Sprint;
use App\Http\Requests;
use App\Http\Controllers\Backend\BackendController;

use App\User;
use App\Teachers;
use App\Institute;
use App\Amazon;
use App\Amazon_count;
use App\Ctc;
use App\Ctc_count;
use App\CoursesRequest;
use date;
use DB;
use whereBetween;
use Carbon\Carbon;
use PDFlib;

use App\FinanceVendorCity;
use App\HubStore;
use App\TorontoEntries;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Auth;
use App\HubProcess;
use App\AlertSystem;
use App\BrookerJoey;
use App\BrookerUser;
use App\CTCEntry;
use App\CtcVendor;
use App\CustomerRoutingTrackingId;
use App\FinanceVendorCityDetail;
use App\FlagHistory;
use App\Http\Traits\BasicModelFunctions;
use App\HubZones;
use App\Joey;
use App\JoeyRouteLocations;
use App\JoeyRoutes;
use App\MerchantIds;
use App\Setting;
use App\SprintTaskHistory;
use App\TrackingImageHistory;
use App\WarehouseJoeysCount;
use DateTime;
use DateTimeZone;


class MicroHubSettingController extends BackendController
{
    public $Global_Value;

    public function getAllDefinedPermissions(){


        //Query to fetch data with joins..
        $all_defined_permissions = DeliveryProcessType::whereNotNull('id')->get();

        return json_encode($all_defined_permissions);


    }//All Defined Permissions...


    public function getRequestedPermissions(){

        //User Data to get the logged in details...
        $auth_user = Auth::user();

        $all_requested_permissions = HubProcess::whereNull('hub_process.is_active')->orWhere('hub_process.is_active', 0)->where('hub_process.hub_id',$auth_user->hub_id)
            ->join('delivery_process_type', 'hub_process.process_id', '=', 'delivery_process_type.id')
            ->get();

        return json_encode($all_requested_permissions);


    }//All Requested Permissions


    public function RequestNewPermission(Request $request)
    {

        $new_permission_request = HubProcess::create([
            'hub_id' => $request->hub_id,
            'process_id' => $request->process_id
        ]);
        return $new_permission_request->id;

    }//Request a new Permission


    //Posting the new permission request data in micro_hub_permission table as well...
    public function PostToMicroHubPermissons(Request $request)
    {

        //User Data to get the logged in details...
        $auth_user = Auth::user();

        $new_permission_request = DB::table('micro_hub_permissions')->insert([
            'hub_process_id' => $request->hub_process_id,
            'micro_hub_user_id' => $auth_user->id
        ]);

        if($new_permission_request){
            return "Successfully Submit data to Micro Hub Permissions";
        }else{
            return "Failed to Submit data in Micro_Hub_Permission";
        }


    }//Posting the new permission request data in micro_hub_permission table as well...


    /**
     * Get Montreal ,Ottawa ,Ctc dashboard count and graph
     */
    public function getIndex(Request $request)
    {
        $auth_user = Auth::user();

        $all_requested_permissions = HubProcess::whereNull('hub_process.is_active')
            ->orWhere('hub_process.is_active', 0)
            ->where('hub_process.hub_id',$auth_user->hub_id)
            ->join('delivery_process_type', 'hub_process.process_id', '=', 'delivery_process_type.id')
            ->get();


        return backend_view('setting',compact('all_requested_permissions'));

    }//Get Index Page with Requested Permission



}
