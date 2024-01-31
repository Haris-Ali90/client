<?php 
use App\Joey;
use App\JoeyRoute;
use App\RoutingZones;

?>
@extends( 'backend.layouts.app' )

@section('title', 'Montreal Deleted Routes')

@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">
    <!-- Image Viewer CSS -->
    <link href="{{ backend_asset('libraries/galleria/colorbox.css') }}" rel="stylesheet">

    <style>
        .green-gradient, .green-gradient:hover {
    color: #fff;
    background: #bad709;
    background: -moz-linear-gradient(top, #bad709 0%, #afca09 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#bad709), color-stop(100%,#afca09));
    background: -webkit-linear-gradient(top, #bad709 0%,#afca09 100%);
    background: linear-gradient(to bottom, #bad709 0%,#afca09 100%);
}
.black-gradient,
.black-gradient:hover {
    color: #fff;
    background: #535353;
    background: -moz-linear-gradient(top,  #535353 0%, #353535 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#535353), color-stop(100%,#353535));
    background: -webkit-linear-gradient(top,  #535353 0%,#353535 100%);
    background: linear-gradient(to bottom,  #535353 0%,#353535 100%);
}

.red-gradient,
.red-gradient:hover {
    color: #fff;
    background: #da4927;
    background: -moz-linear-gradient(top,  #da4927 0%, #c94323 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#da4927), color-stop(100%,#c94323));
    background: -webkit-linear-gradient(top,  #da4927 0%,#c94323 100%);
    background: linear-gradient(to bottom,  #da4927 0%,#c94323 100%);
}

.orange-gradient,
.orange-gradient:hover {
    color: #fff;
    background: #f6762c;
    background: -moz-linear-gradient(top,  #f6762c 0%, #d66626 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#f6762c), color-stop(100%,#d66626));
    background: -webkit-linear-gradient(top,  #f6762c 0%,#d66626 100%);
    background: linear-gradient(to bottom,  #f6762c     0%,#d66626 100%);
}
.modal-dialog.map-model {
    width: 94%;
}
.btn{
    font-size : 12px;
}

    .modal.fade {
        opacity: 1
    }

    .modal-header {
        font-size: 16px;
    }

    .modal-body h4 {
        background: #f6762c;
        padding: 8px 10px;
        margin-bottom: 10px;
        font-weight: bold;
        color: #fff;
    }

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
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
        -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
        -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
        transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    }

    .form-control:focus {
        border-color: #66afe9;
        outline: 0;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(102, 175, 233, .6);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(102, 175, 233, .6);
    }

    .form-group {
        margin-bottom: 15px;
    }

    #ex1 form{
    padding: 10px;
}
div#transfer .modal-content, div#details .modal-content {
    padding: 20px;
    }

   #details .modal-content {
     overflow-y: scroll;
     height: 500px;
}
div#map5 {
    width: 100% !important;
}

.jconfirm .jconfirm-box{
    border : 5px solid #bad709
}
.btn-info {
    color: #fff;
    background-color: #cd692e;
}
.jconfirm .jconfirm-box .jconfirm-buttons button.btn-default {
    background-color: #b8452b;
    color: #fff !important;
}

.jconfirm-content {
    color: #535353;
    font-size: 16px;
}

.jconfirm button.btn:hover {
    background: #99af14 !important;
}

.select2 {
    width: 70% !important;
    margin: 0 0 5px 10px;
}

/* start */
.form-group label {
    width: 50px;
}
div#route .form-group {
    width: 25%;
    float: left;
}

div#route {
    position: absolute;
    z-index: 9999;
    top: 83px;
    width: 97%;
}

.
div {
    display: block;
}
.iycaQH {
    position: absolute;
    background-color: white;
    border-radius: 0.286em;
    box-shadow: rgba(86, 102, 108, 0.24) 0px 1px 5px 0px;
    overflow: hidden;
    margin: 1.429em 0px 0px;
    z-index: 9999;
    width: 30%;
    top: 70px;
    left: 26px;
}
.cBZXtz {
    display: flex;
    -webkit-box-pack: justify;
    justify-content: space-between;
    -webkit-box-align: center;
    align-items: center;
}
.bdDqgn {
    padding: 0.6em 1em;
    background-color: white;
    border-bottom-left-radius: 0.286em;
    border-bottom-right-radius: 0.286em;
    max-height: 28.571em;
    overflow: scroll;
}
.cBZXtz {
    display: flex;
    -webkit-box-pack: justify;
    justify-content: space-between;
    -webkit-box-align: center;
    align-items: center;
}
.kikQSm {
    display: inline-block;
    max-width: 100%;
    font-size: 0.857em;
    font-family: Lato;
    font-weight: 700;
    color: rgb(86, 102, 108);
    margin-bottom: 0.429em;
}
.gdoBAT {
    font-size: 12px;
    margin: 0px 0px 5px 10px;
    color: rgb(86, 102, 108);
}




/*boxes css*/
.montreal-dashbord-tiles h3 {
    color: #fff;
}
.montreal-dashbord-tiles .count {
    color: #fff;
}
.montreal-dashbord-tiles .tile-stats 
{
    border: 1px solid #c6dd38;
    background: #c6dd38;
}

.montreal-dashbord-tiles .tile-stats {
    border: 1px solid #c6dd38;
    background: #c6dd38;
}
.montreal-dashbord-tiles .icon {
    color: #e36d28;
}
.tile-stats .icon i {
    margin: 0;
    font-size: 60px;
    line-height: 0;
    vertical-align: bottom;
    padding: 0;
}
#confirm {
            display: none;
            background-color: #F3F5F6;
            color: #000000;
            border: 1px solid #aaa;
            position: fixed;
            width: 300px;
            height: 100px;
            left: 50%;
            margin-left: -100px;
            padding: 10px 20px 10px;
            box-sizing: border-box;
            text-align: center;
         }
         #confirm button {
            background-color: #FFFFFF;
            display: inline-block;
            border-radius: 12px;
            border: 4px solid #aaa;
            padding: 5px;
            text-align: center;
            width: 60px;
            cursor: pointer;
         }
         #confirm .message {
            text-align: left;
         }
@media only screen and (max-width: 1680px){
.top_tiles .tile-stats {
    padding-right: 70px;
}
.tile-stats .count {
    font-size: 30px;
    font-weight: bold;
    line-height: 1.65857;
    overflow: hidden;
    box-sizing: border-box;
    text-overflow: ellipsis;
}
.tile-stats h3 {
    font-size: 12px;
}
.top_tiles .icon {
    font-size: 40px;
    position: absolute;
    right: 10px;
    top: 0px;
    width: auto;
    height: auto;
    font-size: 40px;
}
.top_tiles .icon .fa {
    vertical-align: middle;
    font-size: inherit;
}
}
    </style>

@endsection

@section('JSLibraries')
    <!-- DataTables JavaScript -->
    <script src="{{ backend_asset('libraries/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ backend_asset('libraries/galleria/jquery.colorbox.js') }}"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="{{ backend_asset('js/jquery-ui.js') }}"></script>
    <link href="{{ backend_asset('js/jquery-ui.css') }}" rel="stylesheet"> -->

@endsection

@section('inlineJS')
<script>

$(document).ready(function() {

$('#joey_id').select2({
    dropdownParent: $("#transfer")
    });

$('#zone').select2({
    maximumSelectionLength: 1
 });
});

  	arr=['blu-blank','blu-circle','blu-diamond','blu-square','blu-stars',
      'red-blank','red-circle','red-diamond','red-square','red-stars',
      'grn-blank','grn-circle','grn-diamond','grn-square','grn-stars',
      'pink-blank','pink-circle','pink-diamond','pink-square','pink-stars',
      'purple-blank','purple-circle','purple-diamond','purple-square','purple-stars',
      'wht-blank','wht-circle','wht-diamond','wht-square','wht-stars'];
      $(document).ready(function(){
          $("map5").empty();
      });
      function Routemap(data)
{
    if(data['data'].length==1)
                        {
                            var mapmarker=1;
                        }
                        else
                        {
                            var mapmarker=0;
                        }
    var latlng;
        var geocoder;
        var directionsDisplay;
        var directionsService = new google.maps.DirectionsService();
        var map = null;
        var bounds = null;


        document.getElementById('map50').innerHTML = "";
        directionsDisplay = new google.maps.DirectionsRenderer();

        var bounds = new google.maps.LatLngBounds();

        var latlng = new google.maps.LatLng({
            lat: parseFloat(data['data'][0][0]['latitude']),
            lng: parseFloat(data['data'][0][0]['longitude'])
        });
       
        var myOptions = {
            zoom: 12,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById("map50"), myOptions);
        directionsDisplay.setMap(map);
        var infowindow = new google.maps.InfoWindow();

        var marker, i, j = 1;
        var request = {
            travelMode: google.maps.TravelMode.DRIVING
        };
        for (var i = 0; i < data['data'].length; i++) {
            for (var k = 0; k < data['data'][i].length; k++) {
          //  if (data[i]['type'] == "dropoff") {

                var latlng = new google.maps.LatLng({
                    lat: parseFloat(data['data'][i][k]['latitude']),
                    lng: parseFloat(data['data'][i][k]['longitude'])
                });
                    var a="R-"+data['data'][i][k]['route_id']+"\nCR-"+data['data'][i][k]['sprint_id']+"\n("+data['data'][i][k]['address']+")";
                bounds.extend(latlng);
                if(mapmarker==1)
                          {
                              var icon_marker="https://assets.joeyco.com/images/marker/marker_red"+(k+1)+".png";
                          }
                          else
                          {
                            var icon_marker="http://maps.google.com/mapfiles/kml/paddle/"+arr[i%30]+".png";
                          }
                    var marker = new google.maps.Marker({
                    position: latlng,
                    map: map,
                    icon: icon_marker,
                    //"http://maps.google.com/mapfiles/kml/paddle/"+arr[i%30]+".png",
                    //"https://assets.joeyco.com/images/marker/marker_red"+(k+1)+".png",
                    title:   "R-"+data['data'][i][k]['route_id']+"\nCR-"+data['data'][i][k]['sprint_id']+"\n("+data['data'][i][k]['address']+")"
                });
            
                
                google.maps.event.addListener(marker, 'click', (function(marker, j) {
                    return function() {
                        infowindow.setContent(marker.title);
                        infowindow.open(map, marker);
                    }
                })(marker, j));

                if (k == 0) request.origin = marker.getPosition();
                // else if (i == data['store'].length - 1) request.destination = marker.getPosition();
                else {
                    if (!request.waypoints) request.waypoints = [];
                    request.waypoints.push({
                        location: marker.getPosition(),
                        stopover: true
                    });
                }
                j++;
        //    }
            }
        }

        // zoom and center the map to show all the markers
        directionsService.route(request, function(result, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(result);
            }
        });

        map.fitBounds(bounds);
      //  google.maps.event.addDomListener(window, "load", initialize);
}


        function transfer() {
            let joey_id = $('#joey_id').val();
            let route_id = $('#route-id').val()
            //alert(joey_id+'---'+route_id);


            if(joey_id  == 'undefined'){
                alert('please select the joey');
            }
            else{

                $.ajax({
                    type: "POST",
                    url: '../../returnroute/transfer/hub',
                    headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                    data:{joey_id : joey_id,route_id:route_id},
                    success: function (data) {
                      // alert('data='+data)
                        if(data.status)
                        {
                            $('.route-tbl-tr-'+data.body.route_id).find('.routes-td-joye-name').val('update');
                            alert('Your route has been updated '+data.body.route_id+' please refresh your page to see changes')
                        }
                        else
                        {
                            alert('error');
                        }

                    },
                    error: function (error) {
                    }
                });
            }



        }
function fullmap() {
    var directionsService = new google.maps.DirectionsService();
    document.getElementById('map50').innerHTML = "";
    directionsDisplay = new google.maps.DirectionsRenderer();
    var bounds = new google.maps.LatLngBounds();
    <?php    if(empty($_REQUEST['date'])){
                                    $date= date('Y-m-d');
                                }
                                else{
                                    $date= $_REQUEST['date'];
                                }  ?>
                $.ajax({

                    url : '../../allroute/477260/location/joey?date=<?php echo $date; ?>',
                    type : 'GET',
                    dataType:'json',
                    success : function(data) {
                        console.log(data);
                        // initialize map center on first point
                        if(data['key'].length==1)
                        {
                            var mapmarker=1;
                        }
                        else
                        {
                            var mapmarker=0;
                        }
                        
                      
                               for(var i=0;i<data['key'].length;i++)
                            {
                                if(i==0)
                                {
                                    document.getElementById('mdd').innerHTML="";
                                }
                               
                               
                    document.getElementById('mdd').innerHTML =document.getElementById('mdd').innerHTML+"<div id='ooo'><input type='checkbox' data-id='"+data['key'][i]+"'  class='delete_check'  name='del[]'  >  <label class='sc-VigVT hFIOIT' id='"+data['key'][i]+"' for='unselect_all'>R-"+data['key'][i]+"</label></div>";
                               
                            }
                    var latlng = new google.maps.LatLng({
                        lat:parseFloat(data['data'][0][0]['latitude']),
                        lng:parseFloat(data['data'][0][0]['longitude'])
                    });
                   
            
                var myOptions = {
                    zoom: 20,
                    center: latlng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };

                                map = new google.maps.Map(document.getElementById("map50"), myOptions);
                                directionsDisplay.setMap(map);
                                var infowindow = new google.maps.InfoWindow();
                                
                                var marker,k=0;
                            
                                var request = {
                                    travelMode: google.maps.TravelMode.DRIVING
                                };
                                                
                                                for(var i=0;i< data['data'].length; i++){
                                                // if(data['hub'][i]['type']=="dropoff"){
                                                    for(var j=0;j<data['data'][i].length;j++){
                                                    

                                                    var latlng = new google.maps.LatLng({lat:parseFloat(data['data'][i][j]['latitude']),
                                                    lng:parseFloat(data['data'][i][j]['longitude'])});
                                bounds.extend(latlng);
                          var a="R-"+data['data'][i][j]['route_id']+"\nCR-"+data['data'][i][j]['sprint_id']+"\n("+data['data'][i][j]['address']+")";
                          if(mapmarker==1)
                          {
                              var icon_marker="https://assets.joeyco.com/images/marker/marker_red"+(j+1)+".png";
                          }
                          else
                          {
                            var icon_marker="http://maps.google.com/mapfiles/kml/paddle/"+arr[i%30]+".png";
                          }
                                var marker = new google.maps.Marker({
                                    position: latlng,
                                    map: map,
                                    icon:icon_marker,
                                    title: "R-"+data['data'][i][j]['route_id']+"\nCR-"+data['data'][i][j]['sprint_id']+"\n("+data['data'][i][j]['address']+")"
                                });
                                google.maps.event.addListener(marker, 'click', (function(marker, j) {
                                    return function() {
                                        infowindow.setContent(marker.title);
                                        infowindow.open(map, marker);
                                    }
                                    })(marker, j));

                                    if (j == 0) request.origin = marker.getPosition();
                                    // else if (i == data['store'].length - 1) 
                                    // request.destination = marker.getPosition();
                                    else {
                                    if (!request.waypoints) request.waypoints = [];
                                    request.waypoints.push({
                                        location: marker.getPosition(),
                                        stopover: true
                                    });
                                    }
                                    
                                    k++;
                                    // }
                                    }
                                 }

                                                            // zoom and center the map to show all the markers
                                directionsService.route(request, function(result, status) {
                                    if (status == google.maps.DirectionsStatus.OK) {
                                    directionsDisplay.setDirections(result);
                                    }
                                });
                                
                                map.fitBounds(bounds);
                                google.maps.event.addDomListener(window, "load", fullmap);
                                google.maps.event.trigger(map, 'resize');

                    
                        },
                        error : function(request,error)
                        {
                            
                        }
                    });
 
}

       $('#checkAll').click(function(){
          $('.delete_check').each(function(i,item){
              if($(this).prop('checked') == false)
                  $(item).prop('checked',true);
              else
                  $(item).prop('checked',false);
          })
      })
      
        $(".rselect").click(function(){
           
           var del_id=[];
          $('.delete_check').each(function(){
             if($(this).is(':checked')){
               var element = $(this);
               del_id.push(element.attr("data-id")); 
             }
          });
         
           if(del_id.length==0)
          {
              alert('Please Select Route Id:');
          }
          else{
           $.ajax({
               type: "POST",
               url: '../../route/map/location',
               headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
               data:{ids : del_id},
               success: function(data){
              data=JSON.parse(data);
             
                Routemap(data);
           }
       });
          }
         
        
       
         });
        </script>
<script>
    
    function selectSlots(){

        $('#slots').empty().trigger("change");
       zoneId = $('#zone').val();
       $.ajax({

        url: '../../zone/' + zoneId + '/slots',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            
            console.log(data);
            slots="";
            data.forEach(function(slot){
               slots+= "<option>SL-"+slot.id+"</option>";
            });
          
            $('#slots').html(slots);
               
        },
        error: function(request, error) {}
       });
    }

    function initialize( id) {
        
        $.ajax({

            url: '../../route/' + id + '/map',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // initialize map center on first point
               
                $('#ex5 .route-id').text("R-" + id);
                mapCreate(data);

            },
            error: function(request, error) {

            }
        });

    }

    function currentMap(id) {
        
        $.ajax({
            url: '../../route/' + id + '/remaining',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // initialize map center on first point
               
                $('#ex5 .route-id').text("R-" + id);
                mapCreate(data);
            },
            error: function(request, error) {}
        });
    }

    function mapCreate(data){

        var latlng;
        var geocoder;
        var directionsDisplay;
        var directionsService = new google.maps.DirectionsService();
        var map = null;
        var bounds = null;


        document.getElementById('map5').innerHTML = "";
        directionsDisplay = new google.maps.DirectionsRenderer();

        var bounds = new google.maps.LatLngBounds();

        var latlng = new google.maps.LatLng({
            lat: parseFloat(data[0]['latitude']),
            lng: parseFloat(data[0]['longitude'])
        });
        
        var myOptions = {
            zoom: 12,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById("map5"), myOptions);
        directionsDisplay.setMap(map);
        var infowindow = new google.maps.InfoWindow();

        var marker, i, j = 1;
        var request = {
            travelMode: google.maps.TravelMode.DRIVING
        };
        for (var i = 0; i < data.length; i++) {
            if (data[i]['type'] == "dropoff") {

                var latlng = new google.maps.LatLng({
                    lat: parseFloat(data[i]['latitude']),
                    lng: parseFloat(data[i]['longitude'])
                });

                bounds.extend(latlng);
                
                    var marker = new google.maps.Marker({
                    position: latlng,
                    map: map,
                    icon: "https://assets.joeyco.com/images/marker/marker_red"+j+".png",
                    title:   "JOEY"
                });
            
                
                google.maps.event.addListener(marker, 'click', (function(marker, i) {
                    return function() {
                        infowindow.setContent("CR-" + data[i]['sprint_id'] + "\n(" + data[i]['address'] + ")");
                        infowindow.open(map, marker);
                    }
                })(marker, i));

                if (i == 0) request.origin = marker.getPosition();
                // else if (i == data['store'].length - 1) request.destination = marker.getPosition();
                else {
                    if (!request.waypoints) request.waypoints = [];
                    request.waypoints.push({
                        location: marker.getPosition(),
                        stopover: true
                    });
                }
                j++;
            }
        }

        // zoom and center the map to show all the markers
        directionsService.route(request, function(result, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(result);
            }
        });

        map.fitBounds(bounds);
        google.maps.event.addDomListener(window, "load", initialize);
    }

   


    $(document).on('click', '.details', function(e) {

        e.preventDefault();

        var routeId = this.getAttribute('data-route-id');
        var joeyId = this.getAttribute('data-joey-id');


        $.ajax({

            url: '../../returnroute/' +  routeId + '/details',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                var i = 0;
                var html = "";

                $('#details .count').text("Count : " + data.length);

                data.forEach(function(route) {
                    var heading = "<h4>R-" + route.route_id +'-'+route.ordinal+ "</h4>";
                    if (route.type == 'dropoff') {
                        var sprint = "<p>Sprint : CR-" + route.sprint_id;
                        var merchantorder = "<p>Merchant Order Number : " + route.merchant_order_num;
                    } else {
                        var sprint = "",
                            merchantorder = "";
                    }

                    var type = "<p>Type : " + route.type + "</p>";
                    var name = "<p>Name : " + route.name + "</p>";
                    var phone = "<p>Phone : " + route.phone + "</p>";
                    var email = "<p>Email : " + route.email + "</p>";
                    var address = "<p>Address : " + route.address + "</p>";
                    //  var arrival = "<p>Arrival Time : "+route.arrival_time+"</p>";
                    //  var finish = "<p>Finish Time : "+route.finish_time+"</p>";
                    var distance = "<p>Distance : " + Math.round(route.distance / 1000).toFixed(2); +"KM</p>";
                    //  var duration = "<p>Duration : "+route.duration+"</p>";
                    html += heading + sprint + merchantorder + type + name + phone + email + address
                        // +arrival+finish
                        +
                        distance;
                    //+duration; 
                    i++;
                });
                $('#rows').html(html);
            },
            error: function(request, error) {
                alert("Request: " + JSON.stringify(request));
            }
        });

        $('#details').modal();
        $('#details .order-id').text("R-" + routeId);

        return false;
    });

    $(document).on('click', '.transfer', function(e) {

        e.preventDefault();

        var routeId = this.getAttribute('data-route-id');
        $('#route-id').val(routeId);

        $('#transfer').modal();
        $('#transfer .order-id').text("R-" + routeId);

        return false;
    });



    function pageReload() {
        location.reload();
    }
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTK4viphUKcrJBSuoidDqRhVA4AWnHOo0"></script>

    <script type="text/javascript">
        
        $(document).ready(function () {

            $('#datatable').DataTable({
              "lengthMenu": [25,50,100, 250, 500, 750, 1000 ]
            });

            $(".group1").colorbox({height:"50%",width:"50%"});

            $(document).on('click', '.reroute', function(e){

                var $form = $(this);
                $.confirm({
                    title: 'A secure action',
                    content: 'Are you sure you want to Reroute this route ?',
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    opacity: 0.5,
                    buttons: {
                        'confirm': {
                            text: 'Proceed',
                            btnClass: 'btn-info',
                            action: function () {
                                console.log($form.attr("data-re"));
                
                                 var id = $form.attr("data-re");
                                
                                $.ajax({
                                    type: "GET",
                                    url: '../hub/16/re-route/'+id,
                                    success: function(message){   
                                        $.alert(message);
                                       // location.reload();
                                    }
                                });
                            }
                        },
                        cancel: function () {
                            //$.alert('you clicked on <strong>cancel</strong>');
                        }
                    }
                });
            });

            $(document).on('click', '.delete', function(e){

                var $form = $(this);
                $.confirm({
                    title: 'A secure action',
                    content: 'Are you sure you want to delete this route ?',
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    opacity: 0.5,
                    buttons: {
                        'confirm': {
                            text: 'Proceed',
                            btnClass: 'btn-info',
                            action: function () {
                            var id = $form.attr("data-id");
                                
                            $.ajax({
                                type: "GET",
                                url: '../returnroute/' + id + '/delete/hub',
                                success: function(message){   
                                    $.alert(message);
                                    //location.reload();
                                }
                            });
                        }
                        },
                        cancel: function () {
                            //$.alert('you clicked on <strong>cancel</strong>');
                        }
                    }
                });
            });

        });
        
        $(document).on('click', '.assign', function(e){

var $form = $(this);
$.confirm({
    title: 'A secure action',
    content: 'Are you sure you want to complete this route ?',
    icon: 'fa fa-question-circle',
    animation: 'scale',
    closeAnimation: 'scale',
    opacity: 0.5,
    buttons: {
        'confirm': {
            text: 'Proceed',
            btnClass: 'btn-info',
            action: function () {
                var id = $form.attr("data-id");

                $.ajax({
                    type: "GET",
                    url: '../montreal/' + id + '/assign',
                    success: function(message){
                       alert(message);
                        location.reload();
                    }
                });
            }
        },
        cancel: function () {
            //$.alert('you clicked on <strong>cancel</strong>');
        }
    }
});
});

    </script>

    
@endsection

@section('content')
<meta type="hidden" name="csrf-token" content="{{ csrf_token() }}" />
<!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left amazon-text">
                    <h3>Montreal Deleted Routes<small></small></h3>
                </div>
            </div>

            <?php

                if(empty($_REQUEST['date'])){
                    $date = date('Y-m-d');
                }
                else{
                    $date = $_REQUEST['date'];
                }

                $date = date('Y-m-d', strtotime($date. ' -1 days'));
                // dd($date);
        
               /* $query = "SELECT COUNT(*) AS total_count,
                SUM(CASE WHEN in_hub_route IS NULL AND sprint__sprints.`status_id` IN (61,13) THEN 1 ELSE NULL END) AS not_in_route 
                FROM sprint__sprints
                JOIN sprint__tasks ON(sprint_id=sprint__sprints.id) 
                JOIN merchantids ON(task_id=sprint__tasks.id)
                WHERE creator_id=477260
                AND tracking_id IS NOT NULL
                AND tracking_id !='' 
                AND CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') LIKE '".$date . "%'";
                
                $amazon_counts = DB::select($query); */

            ?>

            <div class="clearfix"></div>
           
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                       
                        <div class="x_title">
                        <a class="btn green-gradient" href="{{ backend_url('montreal/routes') }}"></i>Actual  Routes</a>
                          <form method="get" action="">
                                <label>Search By Date</label>
                                <?php 
                                if(empty($_REQUEST['date'])){
                                    $date = date('Y-m-d');
                                }
                                else{
                                    $date = $_REQUEST['date'];
                                }
                                ?>
                                 <input type="date" name="date" required="" placeholder="Search" value="<?php echo $date ?>">
                                 <button class="btn green-gradient" type="submit" style="margin-top: -3%,4%">Go</a> </button>
                           </form>
                          
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">

                            @include( 'backend.layouts.notification_message' )

                    <div class="table-responsive">
                   {{-- <table id="datatable" class="table table-striped table-bordered">--}}
                   <table id="datatable" class="table table-striped table-bordered">
                                <thead stylesheet="color:black;">
                                <tr>
                                    <th>Id</th>
                                    <th>Route No</th>
                                  
                                    <th>Zone</th>
                                    <th>Joey Id</th>
                                    <th>Joey</th>
                                    <th>Duration</th>
                                    <th>Distance </th>
                                    <th>Orders Left</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php

$i=1;
for($j=0;$j<count($counts);$j++) {
    echo "<tr>";
    echo "<td>" . $i . "</td>";
    echo "<td>R-" .$counts[$j]->route_id . "</td>";
    // dd($counts[$j]->zone);
    
   
    echo "<td>" . $counts[$j]->zone . "</td>";
    //echo "<td></td>";
    echo "<td>" . $counts[$j]->joey_id . "</td>";
    echo "<td>" . $counts[$j]->joey_name . "</td>";
    //echo "<td></td>";
    if (!empty($counts[$j]->duration) || $counts[$j]->duration != NULL) {
        $duration = $counts[$j]->duration;
    } else {
        $duration = 0;
    }
    echo "<td>" . $duration . "</td>";

    if (!empty($counts[$j]->distance) || $counts[$j]->distance != NULL) {
        $distance = round($counts[$j]->distance / 1000, 2);
    } else {
        $distance = 0;
    }

    if(!empty($counts[$j]->d_distance) || $counts[$j]->d_distance!=NULL ){
        $d_distance = round($counts[$j]->d_distance/1000,2);
     }
     else{
         $d_distance = 0;
     }

    echo "<td>".$d_distance."km/".$distance."km</td>";
    echo "<td>".$counts[$j]->d_counts."/".$counts[$j]->counts."</td>";
    echo "<td>" . date('Y-m-d', strtotime($counts[$j]->date)) . "</td>";
    echo "<td><button  title='Details' class='details green-gradient btn' data-route-id=" . $counts[$j]->route_id . " data-joey-id=" . $counts[$j]->joey_id . " >D</button>
     <a class=' orange-gradient btn' target='_blank' href='../../returnroute/" . $counts[$j]->route_id . "/edit/hub/16' title='Edit Route'>E</a>
     
     <button type='button' class=' red-gradient btn' data-toggle='modal' data-target='#ex5' onclick='initialize(" . $counts[$j]->route_id . ")' title='Map of Whole Route'>M</button> 
     <button type='button' class=' orange-gradient btn' data-toggle='modal' data-target='#ex5' onclick='currentMap(" . $counts[$j]->route_id . ")' title='Map of Current Route'>CM</button> 
     <button type='button'  class='assign  red-gradient btn'  data-id='" . $counts[$j]->route_id. "' title='Assign Route'>A</button>
   
     </td>";
    echo "</tr>";
    $i++;
} ?>

                                </tbody>


                            </table>
                    </div>


                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>

    <div id="ex50" class='modal fade' role='dialog'> 
  <div class='modal-dialog map-model'>          
      <div class='modal-content'>
          <div class='modal-header'>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Map</h4>
          </div>
            <div class='modal-body'>
                    <div class="sc-iwsKbI iycaQH">
                          <div class="sc-dnqmqq bdDqgn" style="border-bottom: 1px solid rgb(236, 239, 241); border-radius: 0px; padding-top: 12px; padding-bottom: 12px;">
                                  <div class="sc-eNQAEJ cBZXtz">
                                      <div class="radio-button sc-gqjmRU fjplhj">
                                        <input class='check' type='checkbox' name='check' id="checkAll" type="radio" >
                                        <label class="sc-VigVT hFIOIT" for="unselect_all">Select All</label>
                                      </div>
                                      <button type='button'  class='rselect btn  red-gradient actBtn '  >Submit <i class='fa fa-search'></i></button>
                                  </div>
                          </div>
                          <div class="sc-dnqmqq bdDqgn">
                           <div id='mdd'> <p class="iii">No Data</p></div>
                          </div>
                    </div>
                    <div id='map50' style="width: 100%; height: 800px;" ></div>   
            </div>
      </div>
    </div>
  </div>
    <!-- /#page-wrapper -->

    <div id="ex5" class='modal fade' role='dialog'>
        <div class='modal-dialog'>

            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Map </h4>
					<p class='route-id'></p>
                </div>
                <div class='modal-body'>

                    <div id='map5' style="width: 430px; height: 380px;"></div>
                    <a class="btn black-gradient" data-dismiss="modal" aria-hidden="true">Close</a>

                </div>
            </div>
        </div>
    </div>

    <div id="ex1" class="modal" style="display: none">
        <div class='modal-dialog'>
            <div class='modal-content'>
                <form action="routes/add" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label>Date</label>
                        <input required type="date" id="date2" name="create_date" class="form-control" placeholder="Select Date">
                    </div>

                    <div class="form-group">
                    <label>Select Zone</label>
                    <select id="zone" multiple class="form-control chosen-select" name="zone[]" requried style="width: 100%;">
                        <?php 
                        $zones =  RoutingZones::where('hub_id','=',16)->whereNull('deleted_at')->get();
                        foreach ($zones as $zone) {
                            echo "<option value='" . $zone->id . "'>ZN-" . $zone->id ."</option>";
                        }
                    
                        ?>
                    </select>
            </div>

                    <!-- <div class="form-group">
                <label>Select Slots</label>
                <select id="slots" multiple class="form-control chosen-select" name="slots[]"  requried style="width: 100%;"></select>
            </div> -->

                    <div class="form-actions">
                        <button type="submit" class="btn orange-gradient">
                            Create Routes <i class="fa fa-plus"></i>
                        </button>
                        <a class="btn black-gradient" data-dismiss="modal" aria-hidden="true">Close</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="details" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-body">
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <p><strong class="order-id green"></strong></p>
                    <p><strong class="count orange"></strong></p>

                    <div id="rows"></div>
                    <div class="modal-footer">
                        <a class="btn black-gradient" data-dismiss="modal" aria-hidden="true">Close</a>
                    </div>
                </div>
            </div> 
        </div>
    </div>

    <div id="transfer" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-body">
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <p><strong class="order-id green"></strong></p>
                    <form method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <label>Please Select a Joey</label>
                        <select  id="joey_id"  name="joey_id" class="form-control ">
                            <?php

                            $joeys = Joey::whereNull('deleted_at')
                                ->where('is_enabled', '=', 1)
                                ->whereNull('email_verify_token')
                                ->whereNOtNull('plan_id')
                                ->orderBy('first_name')
                                ->get();

                            foreach ($joeys as $joey) {
                                echo "<option value=" . $joey->id . ">" . $joey->first_name . " " . $joey->last_name . "(" . $joey->id . ")</option>";
                            }
                            ?>
                        </select>
                        <input type="hidden" name="route_id" id="route-id">
                        <br>
                        <a type="submit" data-selected-row="false"  onclick="transfer()" class="btn green-gradient transfer-model-btn">Transfer</a>
                        <a class="btn black-gradient" data-dismiss="modal" aria-hidden="true">Close</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
<!-- map model -->
<div id="ex5" class='modal fade' role='dialog'> 
        <div class='modal-dialog'>
                 
                 <div class='modal-content'>
                    <div class='modal-header'>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Map</h4>
                    <p class="route-id"></p>
                    </div>
                   <div class='modal-body'>
                    
                    <div id='map5' style="width: 570px; height: 380px;" ></div>
                    <a class="btn black-gradient" data-dismiss="modal" aria-hidden="true">Close</a>
                    </div>
                   </div>    </div>
  </div>
  
@endsection