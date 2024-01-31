<?php

use JoeyCo\Laravel\JoeyRouteLocations;

\Laravel\Asset::script('jquery-ui.js', '/js/jquery/jquery-ui-1.10.3.custom.min.js', array('jquery'));
\Laravel\Asset::style('jquery-ui.css', '/css/jqueryui/joeyco/jquery-ui-1.10.3.custom.min.css', array('fonts', 'reset', 'common', 'style'));

\Laravel\Asset::style('jquery-ui-1.10.3.custom.min.css', '/css/jqueryui/joeyco/jquery-ui-1.10.3.custom.min.css');
\Laravel\Asset::style('jquery-ui-timepicker.css', '/css/jqueryui/joeyco/jquery-ui-timepicker.css', array('jquery-ui-1.10.3.custom.min.css'));

\Laravel\Asset::script('jquery-ui-1.10.3.custom.min.js', '/js/jquery/jquery-ui-1.10.3.custom.min.js', array('jquery'));
\Laravel\Asset::script('jquery-ui-timepicker.js', '/js/jquery/jquery-ui-timepicker.js', array('jquery', 'jquery-ui-1.10.3.custom.min.js'));


\Laravel\Section::start('content');
if(!isset($_REQUEST['date']) || empty($_REQUEST['date'])){
    $date = "20".date('y-m-d');
}
else {
    $date = $_REQUEST['date'];
}

$routes = \JoeyCo\Laravel\JoeyRoute::join('joey_route_locations','joey_routes.id','=','route_id')
->join('sprint__tasks','task_id','=','sprint__tasks.id')
->join('sprint__sprints','sprint_id','=','sprint__sprints.id')
->where_null('joey_route_locations.deleted_at')
->where_null('joey_routes.deleted_at')
->where_null('sprint__sprints.deleted_at')
->where_in('sprint__sprints.creator_id','=',477255,477254)
->where('sprint__sprints.status_id','!=','17')
->where('joey_routes.date','like',$date.'%')
->distinct()
->get(['joey_routes.id']);
$joeys = \JoeyCo\Laravel\Joey::where_null('deleted_at')->where_not_null('hub_joey_type')->order_by('first_name')->get();
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

</style>
<section class="content">
    
       
            <h2 style="width:100%; background-color:#3e3e3e; color:white; text-align:center; font-size:20px;
             font-weight:bold; margin-bottom:25px; padding:10px 0px">Assign Hub Routes CTC</h2>
           
            <form id="filter" style="padding: 10px;" action="" method="get">
                    
                
                <input id="date"  name="date" type="text" placeholder="Select Date" class="" value="<?php echo $date ?>">
                
                <!-- <select class="form-control" name="hub_id">
                <option value="">Please Select Hub</option>
                <?php
                foreach($hubs as $hub){ ?>
                    <option <?php  if(Input::get('hub_id')==$hub->attributes['id']){ echo "selected"; } ?> value="<?php echo $hub->attributes['id'] ?>"><?php echo $hub->attributes['title'] ?></option>
                <?php }
                ?>
                </select> -->

                <button style="width:100px" id="search" type="submit" class="button green-gradient">Submit</button>
                
            </form>
        
            <a style="margin:10px" class="button green-gradient" href="#ex1" rel="modal:open">Assign Route</a>
            <a class='button green-gradient' href='sorter/graph' >Sort Dashboard</a>

        <div class="clear"></div>

        <table class="data-table orders-table">
            <thead>
               
                <tr>
                    <th>Id</th>
                    <th>Route Id</th>
                    <th>Joey Id</th>
                    <th>Joey Name</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Status</th>    
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                
             <?php 
            
             $i=1;
             foreach($hubroutes as $route) {
                 echo "<tr>";
                 echo "<td>".$i."</td>";
                 echo "<td>R-".$route->route_id."</td>";
                 echo "<td>".$route->joey_id."</td>";
                 echo "<td>".$route->first_name." ".$route->last_name."</td>";
                 echo "<td>".$route->date."</td>";
                 echo "<td>".$route->hub_joey_type."</td>";
     
                 $orders = \JoeyCo\Laravel\JoeyRoute::where('id','=',$route->route_id)->first(); 
                
                 $tasks = JoeyRouteLocations::join('sprint__tasks','task_id','=','sprint__tasks.id')
                 ->where('route_id','=',$route->route_id)
                 ->get(['status_id']);
                 
                 $atHUb = 0;
                 foreach($tasks as $task){

                     if($route->hub_joey_type=='first sort'){
                        if($task->status_id==125){
                            $atHUb++;
                        }
                     }

                     else if($route->hub_joey_type=='second sort'){
                        if($task->status_id==133){
                            $atHUb++;
                        }
                     }
                     
                 }
                 if($atHUb==count($tasks)){
                     $status = 'Completed';
                 }
                 else{
                     $status = 'Processing';
                 }

                 echo "<td>".$status."</td>";
                 echo "<td><button type='button' class='details button green-gradient' data-route-id=".$route->route_id."
                  data-joey-id=".$orders->joey_id." >Details</button> | 
                  <a class='button orange-gradient' href='hub/route/".
                  $route->attributes['route_id']."/joey/".$route->attributes['joey_id']."/delete'>Delete</a> | <a class='button red-gradient' href='hub/route/".$route->attributes['route_id']."/edit' >Edit</a></td>";
                 
                 echo "</tr>";
            
                 $i++;
             } ?>
            </tbody>
        </table>

<div id="ex1" class="modal" style="display: none"> 
  
 <form action="hub/route/assign/add" method="post">

     <div class="form-group">
        <label>Select Route</label>
        <select class="form-control" name="route_id">
            <option disabled selected>Please Select</option>
            <?php 
             foreach($routes as $route){
                 echo "<option value='".$route->id."'>R-".$route->id."</option>";
             }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label>Select Joey</label>
        <select class="form-control" name="joey_id">
         <option disabled selected>Please Select</option>
            <?php 
             foreach($joeys as $joey){
                 echo "<option value='".$joey->id."'>".$joey->first_name." ".$joey->last_name."</option>";
             }
            ?>
        </select>
    </div>
    
    <div class="form-actions">
    <button type="submit" class="button orange-gradient">
         Assign Route <i class="icon-plus icon-white"></i>
    </button>
    </div>
  </form>

</div>
    
</section>

<div id="details" class="modal hide" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-body">
        <p><strong class="order-id green"></strong></p>
        
        <div id="rows"></div>
    </div>
    <div class="modal-footer">
        <a class="button black-gradient" data-dismiss="modal" aria-hidden="true">Close</a>
    </div>
</div>

<script>

    $('#date').mask('9999-99-99');
    $('#date2').mask('9999-99-99');
    $('#date').datepicker({
        calendarWeeks: true,
        todayHighlight: true,
        autoclose: true,
        format: '20yy-mm-dd'
    });
    
    $(document).on('click', '.details', function(e) {
       
       e.preventDefault();
       $('#rows').html("");
        $('#details .count').text("");
        $('#details .order-id').text("");
      var routeId = this.getAttribute('data-route-id');
      var joeyId = this.getAttribute('data-joey-id');
       
   
       $.ajax({

           url : 'route/'+joeyId+'/joey',
           type : 'GET',
           dataType:'json',
           success : function(data) {
               var html="";
               var i=0;
               data.hub.forEach( function(route){
                   if(i>0){

                        var heading = "<h4>CR-"+route.sprint_id+"</h4>";
                        var sprint = "<p>Task : "+route.task_id;
                        var merchantorder = "<p>Merchant Order Number : "+route.merchant_order_num;
                        var tracking = "<p>Tracking Id : "+route.tracking_id;

                        html+= heading+sprint+merchantorder+tracking; 
                   }
                   i++;
                  
               });
               $('#rows').html(html);
           },
           error : function(request,error)
           {
               alert("Request: "+JSON.stringify(request));
           }
       });
       
       $('#details').modal();
       $('#details .order-id').text("R-"+routeId);
       
       return false;
   });

   setTimeout(function() {
  location.reload();
}, 120000);

</script>



<?php
\Laravel\Section::stop();
echo \Laravel\View::make('layouts.main')->with(get_defined_vars())->render();
