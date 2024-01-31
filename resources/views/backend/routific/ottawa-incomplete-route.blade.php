@extends( 'backend.layouts.app' )

@section('title', 'Ottawa Incomplete Routes')

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
<!-- <script type="text/javascript">
   $( function() {
    $( "#datepicker" ).datepicker({changeMonth: true,
      changeYear: true, showOtherMonths: true,
      selectOtherMonths: true}).attr('autocomplete','off');
  } );
  </script> -->

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
                                url: '../ottawa/' + id + '/assign',
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
    </script>
    
@endsection

@section('content')

<!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left amazon-text">
                    <h3>Ottawa Incomplete Routes<small></small></h3>
                </div>
            </div>

            <div class="clearfix"></div>
           
            {{--@include('backend.layouts.modal')
            @include( 'backend.layouts.popups')--}}
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">

                            <a class="btn green-gradient" href="{{ backend_url('ottawa/routes') }}"></i>Complete Routes</a>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_title">
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
                                 <button class="btn btn-primary" type="submit" style="margin-top: -3%,4%">Go</a> </button>
                           </form>

                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">

                            @include( 'backend.layouts.notification_message' )

                    <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-bordered">
                    <thead stylesheet="color:black;">
                      <tr>
                      <th>Id</th>
                                <th>Route No</th>
                                <th>Joey Id</th>
                                <th>Joey</th>
                                <th>Duration</th>
                                <th>Distance </th>
                                <th>Orders Left</th>
                                <th>Date</th>
                                <th>Incomplete Route</th>
                                <th>Action</th>
                      </tr>
                      </thead>
                      <tbody>
                      <?php
           
           $i = 1;
           $j = 0;
           foreach ($routes as $route) {
               echo "<tr>";
               echo "<td>" . $i . "</td>";
               echo "<td>R-" . $route->route_id . "</td>";
               echo "<td>" . $route->joey_id . "</td>";
               echo "<td>" . $route->first_name . ' ' . $route->last_name . "</td>";

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
               echo "<td>" . date('Y-m-d', strtotime($route->date)) . "</td>";
               echo "<td>" . $route->is_incomplete . "</td>";
               echo "<td><button class='details green-gradient btn' data-route-id=" . $route->route_id . " data-joey-id=" . $route->joey_id . " title='Details'>D</button>
                <a class=' orange-gradient btn' target='_blank' href='route/" . $route->route_id . "/edit/hub?hub_id=16' title='Edit Route'>E</a>

                <button type='button' class=' red-gradient btn' data-toggle='modal' data-target='#ex5' onclick='initialize(" . $route->joey_id . "," . $route->route_id . ")' title='Map of Whole Route'>M</button>
                <button type='button' class=' orange-gradient btn' data-toggle='modal' data-target='#ex5' onclick='currentMap(" . $route->route_id . ")' title='Map of Current Route'>CM</button>
                <button type='button'  class='assign  red-gradient btn'  data-id='" . $route->route_id . "' title='Assign Route'>A</button>

                </td>";
               echo "</tr>";
               $j++;
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

    <div id="ex1" class="modal" style="display: none">
        <div class='modal-dialog'>
            <div class='modal-content'>
                <form action="routes/add" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label>Date</label>
                        <input required type="date" id="date2" name="create_date" class="form-control" placeholder="Select Date">
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn orange-gradient">
                            Create Routes <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection