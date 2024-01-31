<?php 
$status_id = array("136" => "Client requested to cancel the order",
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
?>
@extends( 'backend.layouts.app' )

@section('title', 'Unattempt Orders')

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
button.btn.btn {
    color: #333;
}
button.btn.btn-primary.bootprompt-accept {
    
    background-color: #c6dd38;
    border-color: #c6dd38;
  
}
table.dataTable thead > tr > th {
    padding-left: 8px !important;
    
}
    </style>

@endsection

@section('JSLibraries')
    <!-- DataTables JavaScript -->
    <script src="{{ backend_asset('libraries/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ backend_asset('libraries/galleria/jquery.colorbox.js') }}"></script>
    <script src="https://unpkg.com/bootprompt@6.0.2/bootprompt.js"></script>
    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="{{ backend_asset('js/jquery-ui.js') }}"></script>
    <link href="{{ backend_asset('js/jquery-ui.css') }}" rel="stylesheet"> -->

@endsection

@section('inlineJS')
<!-- <script type="text/javascript">
   $( function() {
    $( "#datepicker" ).datepicker({changeMonth: true,
      changeYear: true, showOtherMonths: true,
      selectOtherMonths: true}).attr('autocomplete','off');
  } );
  </script> -->
  <script>
$(document).on('click','#checkAll',function(){
            //  aler('s');
            $('input:checkbox').not(this).prop('checked', this.checked);
    });
$(document).on('click','.markIncomplete',function(){
    var ids = [];
    $.each($("input[name='check']:checked"), function(){
        ids.push($(this).val());
        });
        if(ids==0)
        {
            bootprompt.alert('Please  select the  order to mark them Attempt');
        }
        else
        {
            bootprompt.confirm({
            
            message: "Are you sure you want to mark these Orders Attempt?",
            callback: (result) => { 
            if(result)
            {
                $.ajax({
              type: "post",
              url: "{{ URL::to('mark/attampt')}}",
              data:{ids:ids},
              beforeSend: function (request) {
                        return request.setRequestHeader('X-CSRF-Token',"{{ csrf_token() }}");
                    },
              success: function (data) {
                $(".loader").hide();
                $(".loader-background").hide();
                if(data.status_code==200)
                 {
                    bootprompt.alert("Order mark attempt successfully!!", () => {
                        location.reload();
                            });
                  
                }
                else
                {
                    bootprompt.alert('Error.');
                }
               
               
              },
              error:function (error) {
                $(".loader").hide();
                $(".loader-background").hide();
              
                                bootprompt.alert('some error');
              }
          });
              

            }
        }
    });   
        }
       
        
        
    });
</script>
    <script type="text/javascript">
        <!-- Datatable -->
        $(document).ready(function () {

            $('#datatable').DataTable({
              "lengthMenu": [ 250, 500, 750, 1000 ]
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
    <script>
        $(document).ready(function() {
            $('#birthday').daterangepicker({
                singleDatePicker: true,
                calender_style: "picker_4"
            }, function(start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
        });
        function exportTableToCSV(filename) {
    var csv = [];
    var rows = document.querySelectorAll("#datatable tr");
    
    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");
        
        for (var j = 0; j < cols.length; j++) 
            row.push(cols[j].innerText.replace(',',' ').replace(',',' ').replace(',',' ').replace(',',' '));
        
        csv.push(row.join(","));        
    }

    // Download CSV file
    downloadCSV(csv.join("\n"), filename);
 }

 function downloadCSV(csv, filename) {
    var csvFile;
    var downloadLink;

    // CSV file
    csvFile = new Blob([csv], {type: "text/csv"});

    // Download link
    downloadLink = document.createElement("a");

    // File name
    downloadLink.download = filename;

    // Create a link to the file
    downloadLink.href = window.URL.createObjectURL(csvFile);

    // Hide download link
    downloadLink.style.display = "none";

    // Add the link to DOM
    document.body.appendChild(downloadLink);

    // Click download link
    downloadLink.click();



}
    </script>
    
@endsection

@section('content')

<!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left amazon-text">
                    <h1><b>Unattempt Orders</b><small></small></h1>
                </div>
            </div>

            <div class="clearfix"></div>
           
            {{--@include('backend.layouts.modal')
            @include( 'backend.layouts.popups')--}}
            <div class="row">
            <?php 
                                if(empty($_REQUEST['date'])){
                                    $date = date('Y-m-d');
                                }
                                else{
                                    $date = $_REQUEST['date'];
                                }
                                ?>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            
                          
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_title">
                          <form method="get" action="">
                                <label>Search By Date</label>
                               
                                 <input type="date" name="date" required="" placeholder="Search" value="<?php echo $date ?>">
                                 <button class="btn green-gradient" type="submit" style="margin-top: -3%,4%">Go</a> </button>
                                 <br>
                                 <button type='button'  style='background: #3e3e3e;background-color: #c6dd38;right: 50px;' class='btn btn markIncomplete'>Mark Attempt</button>
                                 <button  type='button' onclick="exportTableToCSV('unattempt-order-detail-reprt-<?php echo $date ?>.csv')" style="position: absolute;right: 10px;" class='btn green-gradient'>Generate Report in CSV</button>
                                </form>
                          
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">

                            @include( 'backend.layouts.notification_message' )

                    <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-bordered">
                    <thead stylesheet="color:black;">
                      <tr>
                      <th><input class='check' type='checkbox' name='check' value="0" id="checkAll"> </th> 
                                <th>Id</th>
                                <th>Tracking Id</th>
                                <th>Route No</th>
                                <th>Status </th>
                                <!-- <th>Address</th> -->
                      </tr>
                      </thead>
                      <tbody>
                      <?php
           
           $i = 1;
           
           foreach ($routes as $route) {
               echo "<tr>";
               echo "<td><input class='check' id='check' type='checkbox' name='check' value='".$route->id."'></td>";
               echo "<td>" . $i . "</td>";
               echo "<td>" . $route->tracking_id . "</td>";
               echo "<td>R-" . $route->route_id ."-".$route->ordinal. "</td>";
               echo "<td>" . $status_id[$route->status_id] . "</td>";
               //status_id
              // echo "<td>" . $route->address ." ".$route->postal_code. "</td>";
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
    <!-- /#page-wrapper -->


@endsection