<?php

use JoeyCo\Laravel\Joey;
use JoeyCo\Laravel\MerchantIds;
use JoeyCo\Laravel\JoeyRoute;
use \Laravel\Input;

\Laravel\Asset::script('jquery-ui.js', '/js/jquery/jquery-ui-1.10.3.custom.min.js', array('jquery'));
\Laravel\Asset::style('jquery-ui.css', '/css/jqueryui/joeyco/jquery-ui-1.10.3.custom.min.css', array('fonts', 'reset', 'common', 'style'));

\Laravel\Asset::style('jquery-ui-1.10.3.custom.min.css', '/css/jqueryui/joeyco/jquery-ui-1.10.3.custom.min.css');
\Laravel\Asset::style('jquery-ui-timepicker.css', '/css/jqueryui/joeyco/jquery-ui-timepicker.css', array('jquery-ui-1.10.3.custom.min.css'));

\Laravel\Asset::script('jquery-ui-1.10.3.custom.min.js', '/js/jquery/jquery-ui-1.10.3.custom.min.js', array('jquery'));
\Laravel\Asset::script('jquery-ui-timepicker.js', '/js/jquery/jquery-ui-timepicker.js', array('jquery', 'jquery-ui-1.10.3.custom.min.js'));


\Laravel\Section::start('content');

$zones = \JoeyCo\Laravel\Zone::where_null('deleted_at')->get();
$shifts = \JoeyCo\Laravel\ZoneSchedule::where_null('deleted_at')->get();
$categories = \JoeyCo\Laravel\OrderCategory::where_null('deleted_at')->get();
$vendors = \JoeyCo\Laravel\Vendor::where_null('deleted_at')->get();
$hubs = \JoeyCo\Laravel\Hub::where_null('deleted_at')->get();
?>
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" > -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
<script src="https://assets.staging.joeyco.com/js/admin/dispatch/jquery.maskedinput-1.3.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker3.min.css">
    <script type='text/javascript' src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.min.js"></script>
<style>
.modal {
    top: 0 !important;
    left: 0;
    margin: 0px;
}
.modal.fade{
    opacity: 1
}
/* .modal a.close-modal{
    top: -5px;
    right: -5px
} */
.form-control {
    display: block;
    width: 100%;
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}
.form-control:focus {
    border-color: #66afe9;
    outline: 0;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102,175,233,.6);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102,175,233,.6);
}
.form-group {
    margin-bottom: 15px;
}
.orders-table tbody td {
    padding: 12px;
}
.data-table th, .data-table td {
    padding: 15px 5px;
    }
    .modal-header{
        font-size:16px;
    }
    .modal-body h4 {
    background: #f6762c;
    padding: 8px 10px;
    margin-bottom: 10px;
    font-weight: bold;
    color: #fff;
}

#filter {
    margin-top: 15px;
    display: flex;
}
#filter select.form-control {
    margin: 0px 10px;
    height: 48px;
}

.transfer-but {
    margin: 20px;
}

button[disabled], html input[disabled] {
    cursor: not-allowed !important;
}

</style>
<section class="content">
    
       
        <h2 style="width:100%; background-color:#3e3e3e; color:white; text-align:center; font-size:20px; font-weight:bold; margin-bottom:25px; padding:10px 0px">Edit Hub Route</h2>
        
        <button class="transfer-but transfer button orange-gradient" disabled>Transfer</button>
        <div class="clear"></div>

        <table class="data-table orders-table">
            <thead>
               
                <tr>
                    <th colspan="2">Id</th>
                    <th>Task Id</th>
                    <th>Sprint Id</th>
                    <th>Type</th>
                    <th>Tracking Id</th>
                    <th>Merchant Order Number</th>
                    <th>Delivery Window</th>
                    <th>Address</th>
                    <th>Distance</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                
             <?php 
             date_default_timezone_set('America/Toronto');

             $i=1;
             foreach($route as $routeLoc) {
                    echo "<tr>";
                    echo "<td><input class='check' type='checkbox' name='check' value='".$routeLoc->id."'></td>";
                    echo "<td>".$i."</td>";
                    echo "<td>".$routeLoc->task_id."</td>";
                    echo "<td>CR-".$routeLoc->sprint_id."</td>";
                    echo "<td>".$routeLoc->type."</td>";
                    echo "<td>".$routeLoc->tracking_id."</td>";
                    echo "<td>".$routeLoc->merchant_order_num."</td>";
                    echo "<td>".$routeLoc->arrival_time."-".$routeLoc->finish_time."</td>";
                    echo "<td>".$routeLoc->address.','.$routeLoc->postal_code."</td>";
                    echo "<td>".round($routeLoc->distance/1000,2)."km</td>";
                    echo "<td> <a class='button red-gradient' href='returnroute/".$routeLoc->id."/delete/hub'>Delete</a></td>";
                    echo "</tr>";
                    $i++;               
             }  ?>
            </tbody>
        </table>
    
</section>

<div id="transfer" class="modal hide" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-body">
        <p><strong class="order-id green">Transfer <span id="locs"></span> locations to</strong></p>
        <form action='returnroute/transfer/hub' method="POST">
            <label>Please Select a Route</label>
            <select id="route" name="route_id" class="form-control s" required>
                <?php 
                $routes = JoeyRoute::JOIN('joey_route_locations','joey_routes.id','=','route_id') 
                ->JOIN('sprint__tasks','task_id','=','sprint__tasks.id')
                ->where_null('joey_routes.deleted_at')
                ->where_null('joey_route_locations.deleted_at')
                ->where_not_in('status_id',[36,17])
                ->where('hub','=',Input::get('hub_id'))
                ->distinct()
                ->get(['route_id','joey_id']);
               
                foreach($routes as $route){
                    echo "<option value=".$route->route_id.">R-".$route->route_id."(Joey : ".$route->joey_id.")</option>";
                }
                ?>
            </select><br>
            <button type="button" onclick="transferLocs()" class="button green-gradient">Transfer</button>
        </form>
    </div>
    <div class="modal-footer">
        <a class="button black-gradient" data-dismiss="modal" aria-hidden="true">Close</a>
    </div>
</div>

<script>
var locs = [];
    $(document).on('click', '.transfer', function(e) {   

       e.preventDefault();
       
        $.each($("input[name='check']:checked"), function(){
            locs.push($(this).val());
        });
        
        $('#locs').html(locs.length);
        $('#transfer').modal();
        
        return false;
    
    });

    $(document).on('click', '.check', function(e) { 
    // e.preventDefault();
     $('.transfer').prop('disabled', false);
   });

   function transferLocs(){

    $.ajax({
        type: "POST",
        data : {
            'locations' : locs,
            'route_id' : $('#route').val(),
            'hub_id' : <?php echo input::get('hub_id') ?>
        },
        url: 'route/locations/transfer',
        success: function(){   
        // location.reload();  
        }
    });     
   }

</script>



<?php
\Laravel\Section::stop();
echo \Laravel\View::make('layouts.main')->with(get_defined_vars())->render();
