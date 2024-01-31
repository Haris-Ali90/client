@extends('backend.layouts.app')



@section('title', 'Toronto Dashboard')



@section('CSSLibraries')

    <!-- DataTables CSS -->

    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">

    <!-- DataTables Responsive CSS -->

    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">

    <!-- Image Viewer CSS -->

    <link href="{{ backend_asset('libraries/galleria/colorbox.css') }}" rel="stylesheet">

    <!-- Custom Light Box Css -->

    <link href="{{ backend_asset('css/custom_lightbox.css') }}" rel="stylesheet">

@endsection



@section('dataTablJs')
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
        $("#example").DataTable();
      });
      </script>
@endsection



@section('inlineJS')

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

    <script>
        $(function() {
                    var table = $('.yajrabox').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: "{{ route('newmontrealNotScan.data') }}",
                        columns: [{
                                data: 'sprint_id',
                                orderable: true,
                                searchable: true,
                                className: 'text-center'
                            },
                            {
                                data: 'route_id',
                                orderable: true,
                                searchable: true,
                                className: 'text-center'
                            },
                            {
                                data: 'joey_name',
                                orderable: true,
                                searchable: true
                            },
                            {
                                data: 'address_line_1',
                                orderable: true,
                                searchable: true,
                                className: 'text-center'
                            },
                            {
                                data: 'picked_up_at',
                                orderable: true,
                                searchable: false,
                                className: 'text-center'
                            },
                            {
                                data: 'sorted_at',
                                orderable: true,
                                searchable: false,
                                className: 'text-center'
                            },
                            {
                                data: 'delivered_at',
                                orderable: true,
                                searchable: true,
                                className: 'text-center'
                            },

                            {
                                data: 'order_image',
                                orderable: false,
                                searchable: false,
                                className: 'text-center'
                            },

                            {
                                data: 'tracking_id',
                                orderable: true,
                                searchable: true,
                                className: 'text-center'
                            },

                            {
                                data: 'task_status_id',
                                orderable: true,
                                searchable: true,
                                className: 'text-center'
                            },

                            {
                                data: 'action',
                                orderable: false,
                                searchable: false,
                                className: 'text-center'
                            },
                        ]
                    })});








                    // $('.buttons-reload').on('click', function(event) {

                    //     event.preventDefault();

                    //     // show main loader

                    //     showLoader();



                    //     // update data table data

                    //     var ref = $('#yajra-reload').DataTable();

                    //     ref.ajax.reload(function() {

                    //         // hide loader

                    //         hideLoader()

                    //     });



                    //     // updating cards data

                    //     getTotalOrderData();



                    // });

                    /*   $('.buttons-reload').on('click',function(event){

                           event.preventDefault();

                           showLoader();

                           var ref = $('#yajra-reload').DataTable();

                           $("#montrealCards").load(window.location.href +  " #montreal-dashbord-tiles-id" ,function(){

                               ref.ajax.reload(function(){

                                   // hide loader

                                   hideLoader()

                               });

                           });





                       });*/



                    $('.buttons-excel').on('click', function(event) {

                        event.preventDefault();

                        let href = $(this).attr('href');

                        let selected_date = $('.data-selector').val();

                        window.location.href = href + '/' + selected_date;

                    });
    </script>







@endsection



@section('content')
    <div class="right_col" role="main">

        <div class="">

            <div class="page-title">

                <div class="title_left">

                    <h3 class="text-center">Order Show<small></small></h3>
                </div>
            </div>



            <div class="clearfix"></div>

            {{-- @include('backend.layouts.modal')

            @include( 'backend.layouts.popups') --}}

            <div class="row">



                <div class="col-md-12 col-sm-12 col-xs-12">

                    <div class="x_panel">
<br>
<br>
<br>
<br>
                        <div class="x_content">



                            @include('backend.layouts.notification_message')


                            <table id="example" class="table table-striped table-bordered " id="yajra-reload">
                                {{--  --}}
                                <thead stylesheet="color:black;">

                                    <tr>
                                        <th>ID</th>
                                        <th>Type</th>
                                        <th>Eta Time</th>
                                        <th>Etc Time</th>
                                        <th>Due Time</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody style="overflow-X:scroll;">
                                            <?php $i = 1; ?>
                                            @foreach ($order_detail->tasks as $task)
                                                <tr>
                                                    <td style="width: 5%" class="text-center ">{{$i}}</td>
                                                    <td style="width: 5%" class="text-center ">{{ $task->type}}</td>
                                                    <td style="width: 5%" class="text-center ">{{ date('Y/m/d H:i:s', $task->eta_time)}}</td>
                                                    <td style="width: 5%" class="text-center ">{{ date('Y/m/d H:i:s', $task->etc_time)}}</td>
                                                    <td style="width: 5%" class="text-center ">{{date('Y/m/d H:i:s',  $task->due_time)}}</td>
                                                    <td style="width: 5%" class="text-center ">{{ $task->created_at}}</td>
                                                </tr>
                                                <?php $i++; ?>
                                            @endforeach
                                        </tbody>
                            </table>


                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>

@endsection
