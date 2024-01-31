<?php 
use App\JoeyRoute;
use App\JoeyRouteLocations;
use App\Joey;

if(!isset($_REQUEST['date']) || empty($_REQUEST['date'])){
    $date = "20".date('y-m-d');
}
else {
    $date = $_REQUEST['date'];
}
// if(!isset($_REQUEST['hub_id']) || empty($_REQUEST['hub_id'])){
//     $hub_id = 16;
// }
// else {
//     $hub_id = $_REQUEST['hub_id'];
// }



$routes =JoeyRoute::whereNull('deleted_at')->where('date','like',$date.'%')->where('hub','=',$hub_id)->get();
$joeys = Joey::whereNull('deleted_at')->whereNotNull('hub_joey_type')->where('is_enabled','=',1)->whereNull('email_verify_token')->orderby('first_name')->get();
?>
@extends( 'backend.layouts.app' )

@section('title', 'Montreal Dashboard')

@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">
    <!-- Image Viewer CSS -->
    <link href="{{ backend_asset('libraries/galleria/colorbox.css') }}" rel="stylesheet">
<style>
.green-gradient, .green-gradient:hover {
    /*color: #fff;*/
    color: #3e3e3e;
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
h4 {
    font-size: 18px;
    padding-left: 10px;
}
.btn{
    font-size : 12px;
}
p {
    margin: 0 0 10px;
    padding-left: 9px;
    font-family: fangsong;
    font: icon;
}
#div#rows {
    padding: 10px;
}

.modal-header .close 
{
    opacity: 1;
    margin: 5px 0;
    padding: 0;
}
.modal-footer .close 
{
    opacity: 1;
    margin: 5px 0;
    padding: 0;
}
.modal-header h4 {
    color: #000;
}
.modal-footer {
    padding: 0 10px;
    text-align: right;
    border-top: 1px solid #e5e5e5;
}
.modal-header {
    border-radius: 6px;
    padding: 5px 15px;
    border-bottom: 1px solid #e5e5e5;
    background: #c6dd38;
}

.form-group {
    width: 100%;
    margin: 10px 0;
    padding: 0 15px;
}
.form-group input, .form-group select {
    width: 65% !important;

}
.select2 
{
    width: 65% ! important;
}
.form-group label {
    width: 25%;
    float: left;
    clear: both;
}

.lineEdit {
    width: 100%;
    float: left;
    margin: 5px 0;
}
.addInputs {
    width: 75%;
    float: left;
}
.lineEdit input {
    width: 80% !important;
    float: left;
}
button.remScntedit {
    height: 30px;
    margin: 0 5px;
}
button.remScnt {
    height: 30px;
    margin-top: 2px;
}
.addMoresec {
    text-align: right;
    padding: 0 50px;
}
div#rows {
    height: 300px;
    overflow-y: auto;
}
    </style>
@endsection

@section('JSLibraries')
    <!-- DataTables JavaScript -->
    <script src="{{ backend_asset('libraries/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ backend_asset('libraries/galleria/jquery.colorbox.js') }}"></script>
    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="{{ backend_asset('js/jquery-ui.js') }}"></script>
    <link href="{{ backend_asset('js/jquery-ui.css') }}" rel="stylesheet"> -->

@endsection

@section('inlineJS')
    
@endsection

@section('content')

<div class="right_col" role="main">
        <div class="">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>    
                <strong>{{ $message }}</strong>
            </div>
            @endif
            
            @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>    
                <strong>{{ $message }}</strong>
            </div>
            @endif
            <div class="page-title">
                <div class="title_left amazon-text">
                    <h3>Assign Hub Routes to Sorter<small></small></h3>
                </div>
            </div>

            <div class="clearfix"></div>
           
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                    <form id="filter" style="padding: 10px;margin-left: 6px;" action="" method="get">  
                <input id="date" name="date" style="width:35%px" type="date" placeholder="date" value="<?php echo $date ?>"  class="form-control1">
                <button  id="search" type="submit" class="btn green-gradient">Submit</button>
                
            </form>
                       
                        <div class="x_title">
                            <button style="margin-left:10px" class="btn green-gradient" data-toggle="modal" data-target="#ex1"> <i class="fa fa-plus"></i> Assign Route</button>
                            <div class="clearfix"></div>
                        </div>


                        <div class="x_content">

                         @include( 'backend.layouts.notification_message' )

	                <div class="table-responsive">
	                    <table id="datatable" class="table table-striped table-bordered">
	                      <thead stylesheet="color:black;">
	                      		<tr>
	                      			<th>Id </th>
                                    <th>Route Id</th>
                                    <th>Joey Id </th> 
                                    <th>Joey Name</th> 
                                    <th>Date</th>          
                                    <th>Action</th>
	                      		</tr>
	                      </thead>
	                      <tbody>
	           <?php 
               $i=1;
               foreach($data as $slots) 
               {
                 echo "<tr>";
                 echo "<td>".$i."</td>";
                 echo "<td>";
                  		echo $slots->route_id;

                 echo"</td>";
                 echo "<td>";
                 echo $slots->joey_id;
                 echo"</td>";
                 echo "<td>".$slots->first_name." ".$slots->last_name."</td>";
                 echo "<td>".$slots->date."</td>";

                 $orders = JoeyRoute::where('id','=',$slots->route_id)->first(); 
                
                 echo "<td>";
                echo "<button type='button' class='details btn  green-gradient actBtn' data-route-id=".$slots->route_id."
                data-joey-id=".$orders->joey_id." >Details</button> <a type='button'  class='update btn  green-gradient actBtn ' href=".url('returnroute/' . $slots->route_id . '/edit/hub/'.$hub_id).">Edit <i class='fa fa-pencil'></i></a>";
                 echo "<button type='button'  class='delete btn  red-gradient actBtn ' data-idr=".$slots->route_id." data-idj=".$slots->joey_id." >Delete <i class='fa fa-trash'></i></button>";
                 echo "</td>";
                 echo "</tr>";
                $i++; 
                } 
                ?>
	                      </tbody>
	  					</table>
	                </div>


                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>
    <!-- /#page-wrapper -->
   
 <div id="detailss" class="modal" style="display: none">
        <div class='modal-dialog'>
            
            <div class='modal-content'>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title1"></h4>
                </div style="padding=10px;"  >
           
                 <div id="rows" style="padding=10px;"></div>
    
            </div>
        </div>
    </div>


    <div id="ex5" class='modal fade' role='dialog'>
        <div class='modal-dialog'>

            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Map </h4>
                </div>
                <div class='modal-body'>

                    <div id='map5' style="width: 430px; height: 380px;"></div>

                </div>
            </div>
        </div>
    </div>
    <!-- CreateSLotsModal -->
    <div id="ex1" class="modal" style="display: none">
        <div class='modal-dialog'>
        	
            <div class='modal-content'>
	            <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal">&times;</button>
			        <h4 class="modal-title">Assign Route </h4>
			    </div>
                <form action={{url('/hub/route/assign/add')}} method="post">

                	  <input type="hidden" name="_token" value="{{ csrf_token() }}">
			          <div class="form-group">
			                  <label >Select Route</label>
                              <select class="form-control" id='route_id' name="route_id[]" multiple class="form-control chosen-select" required>
            <!-- <option  >Please Select</option> -->
            <?php 
             foreach($routes as $route){
                 echo "<option value='".$route->id."'>R-".$route->id."</option>";
             }
            ?>
        </select>
			          </div> 

			          <div class="form-group">
                      <label>Select Joey</label>
        <select class="form-control " name="joey_id" required id="joey_id">
         <option  value=''>Please Select</option>
            <?php 
             foreach($joeys as $joey){
                 echo "<option value='".$joey->id."'>".$joey->first_name." ".$joey->last_name."</option>";
             }
            ?>
        </select>
			          </div>

			         

			          <div class="form-group">
			              <button type="submit" class="btn orange-gradient" >
                          Assign Route  <i class="fa fa-plus"></i>
			              </button>   
			          </div>   
    			</form>
            </div>
        </div>
    </div>

   <!-- DeleteSlotsModal -->
   <div id="ex4" class="modal" style="display: none">
        <div class='modal-dialog'>
            
            <div class='modal-content'>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Delete Route</h4>
                </div>
            <form action='../../joey/returnroute/delete' method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="delete_idr" name="delete_idr" value="">
                <input type="hidden" id="delete_idj" name="delete_idj" value="">


                    <div class="form-group">
                        <p><b>Are you sure you want to delete this?</b></p>
                    </div>
                    <div class="form-group">
                      <button type="submit" class="btn green-gradient btn-xs" >Yes</button>
                      <button type="button" class="btn red-gradient btn-xs" data-dismiss="modal" >No</button>

                    </div>  

           </form>  

    
            </div>
        </div>
    </div>



<script type="text/javascript">
  

// updateFunc


//DeleteFunc

</script>

 <script type="text/javascript">
       // Datatable 
        $(document).ready(function () {

            $('#datatable').DataTable({
              "lengthMenu": [25,50,100, 250, 500, 750, 1000 ]
            });

            $(".group1").colorbox({height:"50%",width:"50%"});

            $(document).on('click', '.status_change', function(e){
                var Uid = $(this).data('id');

                $.confirm({
                    title: 'A secure action',
                    content: 'Are you sure you want to change user status??',
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    opacity: 0.5,
                    buttons: {
                        'confirm': {
                            text: 'Proceed',
                            btnClass: 'btn-info',
                            action: function () {

                                $.ajax({
                                    type: "GET",
                                    url: "<?php echo URL::to('/'); ?>/api/changeUserStatus/"+Uid,
                                    data: {},
                                    success: function(data)
                                    {
                                        if(data== '0' || data== 0 )
                                        {
                                            var DataToset = '<button type="btn" class="btn btn-warning btn-xs status_change" data-toggle="modal" data-id="'+Uid+'" data-target=".bs-example-modal-sm">Blocked</button>';
                                            $('#CurerntStatusDiv'+Uid).html(DataToset);
                                        }
                                        else
                                        {
                                            var DataToset = '<button type="btn" class="btn btn-success btn-xs status_change" data-toggle="modal" data-id="'+Uid+'" data-target=".bs-example-modal-sm">Active</button>'
                                            $('#CurerntStatusDiv'+Uid).html(DataToset);
                                        }
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

            $(document).on('click', '.form-delete', function(e){

                var $form = $(this);
                $.confirm({
                    title: 'A secure action',
                    content: 'Are you sure you want to delete user ??',
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    opacity: 0.5,
                    buttons: {
                        'confirm': {
                            text: 'Proceed',
                            btnClass: 'btn-info',
                            action: function () {
                                $form.submit();
                            }
                        },
                        cancel: function () {
                            //$.alert('you clicked on <strong>cancel</strong>');
                        }
                    }
                });
            });

        });


        $(document).ready(function() {
$('#route_id').select2();

            $('#joey_id').select2({
                dropdownParent: $("#ex1")
            });

});


$(document).on('click', '.details', function(e) {
       
       e.preventDefault();

      var routeId = this.getAttribute('data-route-id');
      //var joeyId = this.getAttribute('data-joey-id');

       $.ajax({

           url : '../../../route/'+routeId+'/joey',
           type : 'GET',
           dataType:'json',
           success : function(data) {
               var html="";
               var i=0;
               data.forEach( function(route){
                //   if(i>0){

                        var heading = "<h4>CR-"+route.sprint_id+"</h4>";
                        var sprint = "<p>Task : "+route.task_id;
                        var merchantorder = "<p>Merchant Order Number : "+route.merchant_order_num;
                        var tracking = "<p>Tracking Id : "+route.tracking_id+"<hr>";

                        html+= heading+sprint+merchantorder+tracking; 
                  // }
                   i++;
                  
               });
               $('#rows').html(html);
           },
           error : function(request,error)
           {
               
               alert("Request: "+JSON.stringify(request));
           }
       });
       
       $('#detailss').modal();

       $('#detailss .modal-title1').html("R-"+routeId);
       
       return false;
   });
   //DeleteFunc
$(function() {
$(".delete").click(function(){
    // " href='hub/route/".
    //               $slots->route_id."/joey/".$slots->joey_id.
var element = $(this);
var del_id = element.attr("data-idr");
var del_idj = element.attr("data-idj");
$('#delete_idr').val(''+del_id);
$('#delete_idj').val(''+del_idj);

$('#ex4').modal();
});
});

 </script>
@endsection