<?php
$user = Auth::user();
if ($user->email != 'admin@gmail.com') {
    $data = explode(',', $user['rights']);
    $dataPermission = explode(',', $user['permissions']);
} else {
    $data = [];
    $dataPermission = [];
}
$userPermissoins = Auth::user()->getPermissions();
$user_id = Auth::user()->id;
$hubProcessPermission = \App\MicroHubPermission::where('micro_hub_user_id', $user_id)
    ->whereNull('deleted_at')
    ->pluck('hub_process_id')
    ->toArray();
$hubProcess = \App\HubProcess::whereIn('id', $hubProcessPermission)
    ->where('is_active', 1)
    ->whereNull('deleted_at')
    ->pluck('process_id')
    ->toArray();
$microHubPermissoins = \App\DeliveryProcessType::whereIn('id', $hubProcess)
    ->whereNull('deleted_at')
    ->pluck('process_label')
    ->toArray();

$order_processing = \App\BoradlessDashboard::where('creator_id', auth()->user()->id)
    ->where('created_at', '>=',  Carbon\Carbon::now()->subDays(30)->toDateTimeString())
    ->where('delivered_at', '<=', Carbon\Carbon::now()->toDateTimeString())
    ->get();
$delay_order = \App\BoradlessDashboard::where('creator_id', auth()->user()->id)
    ->where('delivered_at', '>', Carbon\Carbon::now()->toDateTimeString())
    ->get();


?>
@if (Request()->path()=='custom-run/csv-uploader')

    <style>
        li{
            margin: 0% !important;
        }
        .menu_section>ul {
    margin-top: 4px !important;
}
.nav {
    padding-left: 0 !important;
    margin-bottom: 0 !important;
    list-style: none !important;
}
ul {
    list-style: none !important;
    margin: 0 !important;
    padding: 0 !important;
}
    </style>
@endif
<div class="col-md-3 left_col navBar">
    <div class="left_col scroll-view">
        <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
        </div>
        <br />

        <!-- sidebar menu -->
        <!--<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">-->
        <!--    <div class="menu_section">-->
        <!--        <ul class="nav side-menu">-->
        <!--            <li class="active">-->
        <!--                <a href="{{ backend_url('dashboard') }}"><i class="fa fa-users"></i><label>Dashboard</label></a>-->
        <!--            </li>-->
        <!--            <li class="active">-->
        <!--                <a><i class="fa fa-tachometer"></i><label>Orders</label><i class="icofont-caret-up"></i><span-->
        <!--                        class="fa fa-chevron-down"></span></a>-->
        <!--                <ul class="nav child_menu">-->
        <!--                    <li><a href="{{ backend_url('reporting/not/scan') }}"> New Orders</a></li>-->
        <!--                    <li><a href="{{ backend_url('reporting/sorted') }}"> Active Order</a></li>-->
        <!--                    <li><a href="{{ backend_url('reporting/picked/up') }}">Out For Delivery</a></li>-->
        <!--                    <li><a href="{{ backend_url('reporting/delivered') }}"> Delivered Orders</a></li>-->
        <!--                    <li><a href="{{ backend_url('reporting/returned') }}">Returned Orders</a></li>-->
        <!--                    <li><a href="{{ backend_url('reporting/dashboard') }}"> Reporting</a></li>-->
        <!--                </ul>-->
        <!--            </li>-->
        <!--            <li class="active">-->
        <!--                <a><i class="fa fa-users"></i><label>Create Orders</label><i class="icofont-caret-up"></i><span-->
        <!--                        class="fa fa-chevron-down"></span></a>-->
        <!--                <ul class="nav child_menu">-->
        <!--                    <li><a href="{{ backend_url('custom-run/csv-uploader?vendor_token=' . base64_encode($user_id)) }}">Upload Csv</a></li>-->
        <!--                    <li><a href="{{ backend_url('step-form') }}"> Create Order</a></li>-->
        <!--                </ul>-->
        <!--            </li>-->
        <!--            <li class="active">-->
        <!--                <a><i class="fa fa-users"></i><label>Order Label</label><i class="icofont-caret-up"></i><span-->
        <!--                        class="fa fa-chevron-down"></span></a>-->
        <!--                <ul class="nav child_menu">-->
        <!--                    <li><a href="{{ backend_url('label-order/index') }}"> Order Label</a></li>-->
        <!--                </ul>-->

        <!--            </li>-->
        <!--            <li class="active">-->
        <!--                <a><i class="fa fa-users"></i><label>Order List</label><i class="icofont-caret-up"></i><span-->
        <!--                        class="fa fa-chevron-down"></span></a>-->
        <!--                <ul class="nav child_menu">-->
        <!--                    <li><a href="{{ backend_url('order/list') }}"> Order List</a></li>-->
        <!--                </ul>-->

        <!--            </li>-->
                    <!--<li class="active">-->
                    <!--    <a><i class="fa fa-users"></i><label>Step Form</label><i class="icofont-caret-up"></i><span-->
                    <!--            class="fa fa-chevron-down"></span></a>-->
                    <!--    <ul class="nav child_menu">-->
                    <!--        <li><a href="{{ backend_url('step-form') }}"> Step Form</a></li>-->
                    <!--    </ul>-->
                    <!--</li>-->
        <!--            <li>-->
        <!--                <a><i class="fa fa-tachometer"></i><label>Notification</label><i class="icofont-caret-up"></i><span-->
        <!--                        class="fa fa-chevron-down"></span></a>-->
        <!--                <ul class="nav child_menu">-->
    
        <!--                    <li><a href="{{ backend_url('order-processing') }}"> Order Processing-->
        <!--                          </a></li>-->
        <!--                    <li><a href="{{ backend_url('delay-order') }}"> Delay Order </a>-->
        <!--                    </li>-->
        <!--                </ul>-->
        <!--            </li>-->
        <!--            <li>-->
        <!--                <a><i class="fa fa-tachometer"></i><label>Client Setting</label><i-->
        <!--                        class="icofont-caret-up"></i><span class="fa fa-chevron-down"></span></a>-->
        <!--                <ul class="nav child_menu">-->
        <!--                    <li><a href="{{ backend_url('label-setting/' . $user_id) }}">Client Label Setting</a></li>-->
        <!--                    <li><a href="{{ backend_url('profile-setting/' . $user_id) }}">Profile Setting</a></li>-->
        <!--                    <li><a href="{{ backend_url('account-setting/' . $user_id) }}">Account Setting</a></li>-->

        <!--                </ul>-->
        <!--            </li>-->
        <!--        </ul>-->
                
        <!--    </div>-->
        <!--</div>-->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <ul class="nav side-menu">
                    <li class="{{ Request()->path() == 'dashboard' ? 'active' : '' }}">
                        <a href="{{ backend_url('dashboard') }}"><i class="fa fa-users"></i><label>Dashboard</label></a>
                    </li>
                    <li class="{{ Request()->path() == 'reporting/not/scan' ? 'active' : '' }}">
                        <a><i class="fa fa-tachometer"></i><label>Orders</label><i class="icofont-caret-up"></i><span
                                class="fa fa-chevron-down"></span>
                        </a>
                        <ul class="nav child_menu">
                            <li><a href="{{ backend_url('reporting/not/scan') }}"> New Orders</a></li>
                            <li><a href="{{ backend_url('reporting/sorted') }}"> Active Order</a></li>
                            <li><a href="{{ backend_url('reporting/picked/up') }}">Out For Delivery</a></li>
                            <li><a href="{{ backend_url('reporting/delivered') }}"> Delivered Orders</a></li>
                            <li><a href="{{ backend_url('reporting/returned') }}">Returned Orders</a></li>
                            <li><a href="{{ backend_url('reporting/dashboard') }}"> Reporting</a></li>
                        </ul>
                    </li>
                    <li class="{{ Request()->path() == 'custom-run/csv-uploader' ? 'active' : '' }}">
                        <a><i class="fa fa-users"></i><label>Create Orders</label><i class="icofont-caret-up"></i><span
                                class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu"
                            style="display:{{ Request()->path() == 'custom-run/csv-uploader' ? 'block' : '' }}">
                            <li><a
                                    href="{{ backend_url('custom-run/csv-uploader?vendor_token=' . base64_encode($user_id)) }}">Upload
                                    Csv</a></li>
                            <li><a href="{{ backend_url('step-form') }}"> Create Order Step Form</a></li>
                        </ul>
                    </li>
                    <li class="{{ Request()->path() == 'label-order/index' ? 'active' : '' }}">
                        <a><i class="fa fa-users"></i><label>Order Label</label><i class="icofont-caret-up"></i><span
                                class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ backend_url('label-order/index') }}"> Order Label</a></li>
                        </ul>
                    </li>
                    <li class="{{ Request()->path() == 'order/list' ? 'active' : '' }}">
                        <a><i class="fa fa-users"></i><label>Order List</label><i class="icofont-caret-up"></i><span
                                class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ backend_url('order/list') }}"> Order List</a></li>
                        </ul>
                    </li>
                    <li class="{{ Request()->path() == 'order-processing' ? 'active' : '' }}">
                        <a><i class="fa fa-tachometer"></i><label>Notification</label><i
                                class="icofont-caret-up"></i><span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ backend_url('order-processing') }}"> Order Processing
                                </a></li>
                            <li><a href="{{ backend_url('delay-order') }}"> Delay Order </a>
                            </li>
                        </ul>
                    </li>
                    <li class="{{ Request()->path() == 'label-setting' ? 'active' : '' }}">
                        <a><i class="fa fa-tachometer"></i><label>Client Setting</label><i
                                class="icofont-caret-up"></i><span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ backend_url('label-setting/' . $user_id) }}">Client Label Setting</a></li>
                            <li><a href="{{ backend_url('profile-setting/' . $user_id) }}">Profile Setting</a></li>
                            <li><a href="{{ backend_url('account-setting/' . $user_id) }}">Account Setting</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /sidebar menu -->
    </div>
</div>
<!-- top navigation -->
<div class="top_nav">

</div>
<!-- /top navigation -->
