<?php

use JoeyCo\Laravel\Joey;

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
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker3.min.css">
    <script type='text/javascript' src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.min.js"></script>
<style>

 
span.select2.select2-container.select2-container--default{
    width: 100% !important;
}


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
    
       
            <h2 style="width:100%; background-color:#3e3e3e; color:white; text-align:center; font-size:20px; font-weight:bold; margin-bottom:25px; padding:10px 0px">Amazon Hub Routes</h2>
           
            <a style="margin:10px" class="button green-gradient" href="#ex1" rel="modal:open">Create Routes For Hub</a>
			 <a class="button green-gradient" href='dispatch/hub/routific/status'>Update Status</a>
             <a class="button green-gradient" href='searchorder/trackingid'>Search Order</a>
             <a class='button green-gradient' href='routific/job' >Routific Job Dashboard</a>
            <a class="button orange-gradient" style="float: right; margin-right: 10px;" href='dispatch/hub/routific/reset'>Reset Joey For Routes</a>
           
            <button class="button green-gradient" onclick="pageReload()">Refresh</button>

            <form id="filter"  style="padding: 10px;" action="" method="get">
                <?php 
                if(!isset($_REQUEST['date']) || empty($_REQUEST['date'])){
                    $date = "20".date('y-m-d');
                }
                else {
                    $date = $_REQUEST['date'];
                }
               
                ?>    
                <input id="date" name="date" type="text" placeholder="Select Date" style="padding:10px" value="<?php echo $date  ?>">
                
                <button style="width:100px" id="search" type="submit" class="button green-gradient">Filter</button>
                
            </form>
        
        
        <div class="clear"></div>

        <table class="data-table orders-table">
            <thead>
               
                <tr>
                    <th>Id</th>
                    <th>Route Number</th>
                    <th>Joey id</th>
                    <th>Joey </th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                
             <?php 
             $i=1;
             foreach($routes as $route) {
                 echo "<tr>";
                 echo "<td>".$i."</td>";
                 echo "<td>R-".$route->route_id."</td>";
                 echo "<td>".$route->joey_id."</td>";
                 echo "<td>".$route->first_name.' '.$route->last_name."</td>";
                 echo "<td>".$route->date."</td>";
                 echo "<td><button data-route-id=".$route->route_id." data-joey-id=".$route->joey_id." class='details button green-gradient'>Details</button> | <a class='button orange-gradient' target='_blank' href='route/".$route->route_id."/edit/hub'>Edit</a> | <button class='transfer button black-gradient'  data-route-id=".$route->route_id." >Transfer</button> | <button type='button' class='button red-gradient' data-toggle='modal' data-target='#ex5' onclick='initialize(".$route->joey_id.",".$i.")' >Map</button> | <button type='button'  class='delete button red-gradient'  data-id='".$route->route_id."'>Delete</button> </td>";
                 echo "</tr>";
              
                 $i++;
             } ?>
            </tbody>
			<tfoot>
             <tr>
                 <td></td>
                <td>Total Amazon Orders : </td>
                <td><?php
                 $date = date('Y-m-d', strtotime($date. ' -1 days'));
                 $total = \JoeyCo\Laravel\Sprint::join('sprint__tasks','sprint_id','=','sprint__sprints.id')
				 ->where('creator_id','=','477260')
                 ->where_null('sprint__sprints.deleted_at')
				 ->where('type','=','pickup')
				 ->where_not_in('sprint__sprints.status_id',[36,35,38])
                 //->where(\DB::raw("from_unixtime(due_time-14400)"),'like',$date."%")
                 ->where(\DB::raw("CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto')"),'like',$date."%")
                 ->count();
                 echo $total;
                ?></td>
                <td>Not in Routes: </td>
                <td>
                <?php
                 $inRoute = \JoeyCo\Laravel\Sprint::join('sprint__tasks','sprint_id','=','sprint__sprints.id')
				 ->where('creator_id','=','477260')
                 ->where_null('sprint__sprints.deleted_at')
				 ->where_not_null('in_hub_route')
                 ->where('type','=','pickup')
				 ->where_not_in('sprint__sprints.status_id',[36,35,38])
                 //->where(\DB::raw("from_unixtime(due_time-14400)"),'like',$date."%")
                 ->where(\DB::raw("CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto')"),'like',$date."%")
                 ->count();
                 echo $total-$inRoute;
                ?>
                </td>
             </tr>
            </tfoot>
        </table>
        <div id="ex5" class='modal fade' role='dialog'> 
        <div class='modal-dialog'>
                 
                 <div class='modal-content'>
                    <div class='modal-header'>
                      <h4 class='modal-title'>Map </h4>
                    </div>
                   <div class='modal-body'>
                    
                    <div id='map5' style="width: 430px; height: 380px;" ></div>
                    
                    </div>
                   </div>    </div>
  </div>

<div id="ex1" class="modal" style="display: none"> 
  
 <form action="dispatch/hub/routific/add" method="post">

     <div class="form-group">
        <label>Select Hub</label>
        <select class="form-control" name="hub" required>
            <option  value="">Please Select</option>
            <?php 
             foreach($hubs as $hub){
                 echo "<option value='".$hub->id."'>".$hub->title."</option>";
             }
            ?>
        </select>  
     </div> 
    
    <div class="form-group">
        <label>Date</label>
        <input required type="text" id="date2" name="create_date" class="form-control" placeholder="Select Date">
    </div>
    <div class="form-actions">
    <button type="submit" class="button orange-gradient">
         Create Routes <i class="icon-plus icon-white"></i>
    </button>
    </div>
  </form>

</div>
    
</section>

<div id="details" class="modal hide" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-body">
        <p><strong class="order-id green"></strong></p>
         <p><strong class="count orange"></strong></p>
        <div id="rows"></div>
    </div>
    <div class="modal-footer">
        <a class="button black-gradient" data-dismiss="modal" aria-hidden="true">Close</a>
    </div>
</div>

<div id="transfer" class="modal hide" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-body">
        <p><strong class="order-id green"></strong></p>
        <form action='returnroute/transfer/hub' method="POST">
            <label>Please Select a Joey</label>
            <select name="joey_id" class="form-control s">
                <?php 
                $joeys = Joey::where_null('deleted_at')->where('is_enabled','=',1)->where_null('email_verify_token')->order_by('first_name')->get();
                foreach($joeys as $joey){
                    echo "<option value=".$joey->id.">".$joey->first_name." ".$joey->last_name."(".$joey->id.")</option>";
                }
                ?>
            </select>
            <input type="hidden" name="route_id" id="route-id">
            <br>
            <button type="submit" class="button green-gradient">Transfer</button>
        </form>
        
          
 
    </div>
    <div class="modal-footer">
        <a class="button black-gradient" data-dismiss="modal" aria-hidden="true">Close</a>
    </div>
</div>

<script>
$(document).ready(function(){
    $("map5").empty();
});
$(".s").select2({
  tags: true
  });
var geocoder;
var directionsDisplay;
var directionsService = new google.maps.DirectionsService();
var map = null;
var bounds=null;
function initialize(joeyId,id) {
    document.getElementById('map5').innerHTML = "";
    directionsDisplay = new google.maps.DirectionsRenderer();

var bounds = new google.maps.LatLngBounds();
 $.ajax({

            url : 'route/'+joeyId+'/joey',
            type : 'GET',
            dataType:'json',
            success : function(data) {
                // initialize map center on first point
                console.log(data);
var latlng = new google.maps.LatLng({lat:parseFloat(data['hub'][1]['location']['latitude']),
                    lng:parseFloat(data['hub'][1]['location']['longitude'])});
var myOptions = {
    zoom: 12,
    center: latlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
};

map = new google.maps.Map(document.getElementById("map5"), myOptions);
directionsDisplay.setMap(map);
  var infowindow = new google.maps.InfoWindow();
  
  var marker, i;
  var request = {
    travelMode: google.maps.TravelMode.DRIVING
  };
                for(var i=1;i< data['hub'].length; i++){
                

					var latlng = new google.maps.LatLng({lat:parseFloat(data['hub'][i]['location']['latitude']),
                    lng:parseFloat(data['hub'][i]['location']['longitude'])});
  bounds.extend(latlng);
  var marker = new google.maps.Marker({
    position: latlng,
    map: map,
    icon: "https://raw.githubusercontent.com/Concept211/Google-Maps-Markers/master/images/marker_red"+(i)+".png",
    title: "Joey"
  });
  google.maps.event.addListener(marker, 'click', (function(marker, i) {
      return function() {
        infowindow.setContent('joey');
        infowindow.open(map, marker);
      }
    })(marker, i));

    if (i == 0) request.origin = marker.getPosition();
    else if (i == data['store'].length - 1) request.destination = marker.getPosition();
    else {
      if (!request.waypoints) request.waypoints = [];
      request.waypoints.push({
        location: marker.getPosition(),
        stopover: true
      });
    }
                }
               
            },
            error : function(request,error)
            {
                
            }
        });

  // zoom and center the map to show all the markers
  directionsService.route(request, function(result, status) {
    if (status == google.maps.DirectionsStatus.OK) {
      directionsDisplay.setDirections(result);
    }
  });
  
  map.fitBounds(bounds);
}

google.maps.event.addDomListener(window, "load", initialize);
   
 </script>
<script>

    $('#date').mask('9999-99-99');
    $('#date').datepicker({
        calendarWeeks: true,
        todayHighlight: true,
        autoclose: true,
        format: '20yy-mm-dd'
    });

    $('#date2').mask('9999-99-99');
    $('#date2').datepicker({
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
                var i=0;
                var html="";
				$('#details .count').text("Count : "+data.hub.length);
                data.hub.forEach( function(route){
                  var heading = "<h4>"+route.num+"</h4>";
                  if(i>0){
                    var sprint = "<p>Sprint : CR-"+route.sprint_id;
                    var merchantorder = "<p>Merchant Order Number : "+route.merchant_order_num;
                  }
                  else{
                    var sprint="",merchantorder="";
                  }
                  
                  var type = "<p>Type : "+route.type+"</p>";
                  var name = "<p>Name : "+route.contact.name+"</p>";
                  var phone = "<p>Phone : "+route.contact.phone+"</p>";
                  var email = "<p>Email : "+route.contact.email+"</p>";
                  var address = "<p>Address : "+route.location.address+"</p>";
                  html+= heading+sprint+merchantorder+type+name+phone+email+address; 
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

    $(document).on('click', '.transfer', function(e) {
       
       e.preventDefault();

       var routeId = this.getAttribute('data-route-id');
       $('#route-id').val(routeId);
    
       $('#transfer').modal();
       $('#transfer .order-id').text("R-"+routeId);
       
       return false;
   });
      
$(function() {
$(".delete").click(function(){
    var x = confirm("Are you sure you want to delete?");
  if(x)
      {
var element = $(this);
var del_id = element.attr("data-id");
    $.ajax({
        type: "GET",
        url: 'returnroute/'+del_id+'/delete/hub',
        success: function(){   
         location.reload();  

    }
});}
//return false;
});
});

// setTimeout(function() {
//   location.reload();
// }, 120000);

function pageReload(){
    location.reload();
}
</script>

<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTK4viphUKcrJBSuoidDqRhVA4AWnHOo0">
    </script>

<?php
\Laravel\Section::stop();
echo \Laravel\View::make('layouts.main')->with(get_defined_vars())->render();
