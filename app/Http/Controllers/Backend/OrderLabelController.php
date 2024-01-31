<?php



namespace App\Http\Controllers\Backend;



use App\BoradlessDashboard;

use App\Classes\Fcm;

use App\CTCEntry;

use App\CustomerFlagCategories;

use App\FlagHistory;

use App\Http\Traits\BasicModelFunctions;

use App\JoeyRouteLocations;

use App\JoeyRoutes;

use App\Reason;

use App\Sprint;

use App\MerchantIds;

use App\SprintReattempt;

use App\SprintTaskHistory;

use App\TaskHistory;

use App\TrackingDelay;

use App\TrackingNote;

use App\UserDevice;

use App\UserNotification;

use Illuminate\Http\JsonResponse;

use Illuminate\Http\Request;

use App\Task;

use App\Notes;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Facades\Excel;

use Yajra\Datatables\Datatables;

use Carbon\Carbon;

use DateTime;

use DateTimeZone;

use App\ClientSetting;





class OrderLabelController extends BackendController
{



    use BasicModelFunctions;

    public static $status = array(
        "136" => "Client requested to cancel the order",

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

        "255" => 'Order Delay',

        "145" => 'Returned To Merchant',

        "146" => "Delivery Missorted, Incorrect Address",

        "147" => "Scanned at hub",

        "148" => "Scanned at Hub and labelled",

        "149" => "Bundle Pick From Hub",

        "150" => "Bundle Drop To Hub",

        '155' => 'To be re-attempted'
    );



    public function statusmap($id)
    {

        $statusid = array(
            "136" => "Client requested to cancel the order",

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

            "255" => 'Order Delay',

            "145" => 'Returned To Merchant',

            "146" => "Delivery Missorted, Incorrect Address",

            "147" => "Scanned at hub",

            "148" => "Scanned at Hub and labelled",

            "149" => "Bundle Pick From Hub",

            "150" => "Bundle Drop To Hub",

            '155' => 'To be re-attempted'
        );

        return $statusid[$id];

    }





    /**

     * Get Order Label Index

     */

    public function getOrderLabelIndex(Request $request)
    {



        return backend_view('label-order.index');

    }



    /**

     * Yajra call after Order Label Index

     */

    public function getOrderLabelData(Datatables $datatables, Request $request, $date)
    {
        // dd($request->all(),$date);

        $user = Auth::user();

        //dd($user->id);

        $today_date = !empty($date) ? $date : date("Y-m-d");



        $start_dt = new DateTime($today_date . " 00:00:00", new DateTimezone('America/Toronto'));

        $start_dt->setTimeZone(new DateTimezone('UTC'));

        $start = $start_dt->format('Y-m-d H:i:s');
        $end_dt = new DateTime($today_date . " 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');
        $query = Sprint::join('sprint__tasks', 'sprint__tasks.sprint_id', '=', 'sprint__sprints.id')
            ->where('sprint__sprints.creator_id', $user->id)
            ->whereBetween('sprint__sprints.created_at', [$start, $end])

            // ->whereNotIn('sprint__sprints.status_id', [36])

            ->where('sprint__tasks.type', '=', 'dropoff')

            ->whereNull('sprint__sprints.deleted_at')

            ->where('sprint__sprints.is_reattempt', 0)

            ->whereNull('sprint__tasks.deleted_at')

            ->groupBy('sprint__sprints.id')

            ->select([
                'sprint__sprints.*',
                'sprint__tasks.status_id as task_status_id'

            ]);

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->addColumn('id', static function ($record) { // sprint-id
                return $record->id ? $record->id : '';
            })
            ->editColumn('task_status_id', static function ($record) {
                $current_status = $record->task_status_id;
                if ($record->task_status_id == 17) {
                    $preStatus = \App\SprintTaskHistory
                        ::where('sprint_id', '=', $record->sprint_id)
                        ->where('status_id', '!=', '17')
                        ->orderBy('id', 'desc')->first();
                    if (!empty($preStatus)) {
                        $current_status = $preStatus->status_id;
                    }
                }
                if ($current_status == 13) {
                    return "At hub - processing";
                } else {
                    return self::$status[$current_status];
                }
            })
            ->addColumn('joey_id', static function ($record) {
                if (isset($record->joey)) {
                    return $record->joey->joey_name ? $record->joey->joey_name . ' (' . $record->joey->id . ')' : '';
                }
                return '';
            })
            ->addColumn('creator_type', static function ($record) {
                return isset($record->sprintTasks->task_Location->address) ? $record->sprintTasks->task_Location->address : '';
                ;
            })
            ->addColumn('updated_at', static function ($record) {
                return backend_view('label-order.action', compact('record'));
            })
            ->addColumn('checked_out_at', static function ($record) {
                return backend_view('label-order.checkbox', compact('record'));
            })->make(true);

    }



    public function labelOrderPrint($id, Request $request)
    {

        if ($id == 0) {



            $ids = $request->sprintIds[0];



            $id = explode(",", $ids);



        } else {

            $id = [$id];

        }



        $printSize = ClientSetting::where('user_id', Auth::user()->id)->whereNull('deleted_at')->pluck('print_size')->toArray();



        $printLabelData = Sprint::join('sprint__tasks', 'sprint__sprints.id', '=', 'sprint__tasks.sprint_id')

            ->join('vendors', 'vendors.id', '=', 'sprint__sprints.creator_id')

            ->join('locations', 'sprint__tasks.location_id', '=', 'locations.id')

            ->join('sprint__contacts', 'sprint__tasks.contact_id', '=', 'sprint__contacts.id')

            ->join('merchantids', 'merchantids.task_id', '=', 'sprint__tasks.id')

            ->whereIn('sprint__sprints.id', $id)

            ->where('sprint__sprints.is_reattempt', 0)

            ->whereNotNull('merchantids.tracking_id')

            // ->groupBy('sprint__sprints.id')

            ->select(['sprint__sprints.*', 'sprint__contacts.name as sprint_name', 'locations.address as sprint_address', 'locations.postal_code as sprint_postal_code', 'vendors.name as vendor_name', 'vendors.business_address as vendor_address', 'merchantids.tracking_id'])->get();

        return backend_view('label-order.print-lable.order_label', compact('printLabelData', 'printSize'));



    }



    public function orderList(Request $request)
    {

        $status_code = array(
            "136" => "Client requested to cancel the order",
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
            "121" => "Pickup from Hub",
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
            "255" => "Order delay",
            "145" => "Returned To Merchant",
            "146" => "Delivery Missorted, Incorrect Address",
            "147" => "Scanned at hub",
            "148" => "Scanned at Hub and labelled",
            "2" => "Scanned at Hub and labelled",
            "149" => "",
            "150" => "",
            "151" => "",
            "152" => "",
        );


        if ($request->datepicker) {
            $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
            $start_dt = new DateTime($today_date . " 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');
            $end_dt = new DateTime($today_date . " 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');
            $order_detail = Sprint::with('vehicle', 'tasks')->has('vehicle', '!=', '')->has('tasks', '!=', '')->where('created_at', '>', $start)->where('created_at', '<', $end)->where('creator_id', Auth::id())->paginate(10);
        } else {
            $order_detail = Sprint::with('vehicle', 'tasks')->has('vehicle', '!=', '')->has('tasks', '!=', '')->where('creator_id', Auth::id())->paginate(10);
        }
        return backend_view('label-order.order-list', compact('order_detail', 'status_code'));

    }





    public function orderShow(Request $request, $id)
    {

        $id;

        $order_detail = Sprint::with('vehicle', 'tasks')->where('id', $id)->first();

        return backend_view('label-order.order-show', compact('order_detail'));



    }





}