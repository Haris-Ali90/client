
@extends( 'backend.layouts.app' )

@section('title', 'Montreal Dashboard')

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

@section('JSLibraries')
    <!-- DataTables JavaScript -->
    <script src="{{ backend_asset('libraries/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ backend_asset('libraries/galleria/jquery.colorbox.js') }}"></script>
    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="{{ backend_asset('js/jquery-ui.js') }}"></script>
    <link href="{{ backend_asset('js/jquery-ui.css') }}" rel="stylesheet"> -->
    <!-- Custom Light Box JS -->
    <script src="{{ backend_asset('js/custom_lightbox.js')}}"></script>
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
                ajax: "{{ route('newmontrealReturned.data') }}",
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
                    {
                        data: 'datepicker',
                        name: jQuery("[name=datepicker]").val(),
                     
                    },
                ],
            
            });
        });

       /* $(document).ready(function(){
            setInterval(function(){
                $("#montrealCards").load(window.location.href + " #montreal-dashbord-tiles-id" );
                var ref = $('#yajra-reload').DataTable();
                ref.ajax.reload();
            }, 50000);
        });*/

        // function getTotalOrderData() {
        //     let selected_date = $('.data-selector').val();
        //     let type = $('#type').val();
        //     // show loader
        //     $('.total-order').addClass('show');
        //     $.ajax({
        //         type: "GET",
        //         url: "<?php echo URL::to('/'); ?>/newmontreal/totalcards/"+selected_date+"/"+type,
        //         data: {},
        //         success: function(data)
        //         {
        //             $('#total_orders').text(data['amazon_count']['total']);
        //             $('#return_orders').text(data['amazon_count']['return_orders']);
        //             $('#hub_return_scan').text(data['amazon_count']['hub_return_scan']);
        //             $('#hub_not_return_scan').text(data['amazon_count']['return_orders'] - data['amazon_count']['hub_return_scan']);
        //             $('#sorted_orders').text(data['amazon_count']['sorted']);
        //             $('#picked_orders').text(data['amazon_count']['pickup']);
        //             $('#delivered_orders').text(data['amazon_count']['delivered_order']);
        //             $('#notscan_orders').text(data['amazon_count']['notscan']);
        //             // hide loader
        //             $('.total-order').removeClass('show');
        //         },
        //         error:function (error) {
        //             console.log(error);
        //             // hide loader
        //             $('.total-order').removeClass('show');
        //         }
        //     });
        // }


        setTimeout(function(){
            // getTotalOrderData();

        }, 1000);


        // $('.buttons-reload').on('click',function(event){
        //     event.preventDefault();
        //     // show main loader
        //     showLoader();

        //     // update data table data
        //     var ref = $('#yajra-reload').DataTable();
        //     ref.ajax.reload(function(){
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

        $('.buttons-excel').on('click',function(event){
            event.preventDefault();
            let href = $(this).attr('href');
            let selected_date = $('.data-selector').val();
            window.location.href = href+'/'+selected_date;
        });
    </script>



@endsection

@section('content')


    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3 class="text-center">Returned Order<small></small></h3>
                </div>
            </div>

            <div class="clearfix"></div>
            <!--Count Div Row Open-->
        @include('backend.newmontrealdashboard.montreal_cards')
            <!--Count Div Row Close-->

            {{--@include('backend.layouts.modal')
            @include( 'backend.layouts.popups')--}}
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <form method="get" action="">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Search By Date :</label>
                                        <input type="date" name="datepicker" class="data-selector form-control" required=""
                                               value="{{ isset($_GET['datepicker'])?$_GET['datepicker']: '' }}">
                                        <input type="hidden" name="type" value="return" id="type">
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn  sub-ad c-color" type="submit" style="       margin-top: 25px;">
                                            Go
                                        </button>
                                    </div>
                                    <div class="col-md-6 sm_custm">
{{--                                        <h2>{{ getHubTitle() }} <small>Returned Order</small></h2>--}}
                                        @if(can_access_route('newexport_MontrealReturned.excel',$userPermissoins))
                                            <div class="excel-btn " style="float: right">
                                                <a href="{{ route('newexport_MontrealReturned.excel') }}"
                                                   class="btn buttons-excel buttons-html5 btn-sm sub-ad c-color excelstyleclass">
                                                    Export to Excel
                                                </a>
                                            </div>
                                        @endif
                                        <div class="excel-btn" style="float: right">
                                            <a href="{{route('newmontreal-returned.index')}}"
                                               class="btn buttons-reload buttons-html5 btn-sm sub-ad c-color excelstyleclass">
                                                Reload
                                            </a>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </form>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">

                            @include( 'backend.layouts.notification_message' )


                 <table class="table table-striped table-bordered">
                    <thead stylesheet="color:black;">
                    <tr>
                        <th>Order #</th>
                        <th>Route Number</th>
                        <th>Driver</th>
                        <th>Customer Address</th>
                        <th>Out For Delivery</th>
                        <th>Sorted Time</th>
                        <th>Driver Returned Scan</th>
                        <th>Image</th>
                        <th>Hub Returned Scan</th>
                        <th>Tracking #</th>
                        <th>Status</th>
                    </tr>
                      </thead>
                      <tbody>
                        @php
                            $a=1;
                        @endphp
                        @forelse($query as $index => $order)
                            <tr>
                                {{-- <td>  {{$index + $query->firstItem() }} </td> --}}
                                <td>{{ $order->sprint_id }}</td>
                                <td>{{ $order->route_id }}</td>
                                <td>{{ $order->joey_name }}</td>
                                <td>{{ $order->address_line_1}}</td>
                                <td>{{ $order->picked_up_at }}</td>
                                <td>{{ $order->sorted_at }}</td>
                                <td>{{ $order->returned_at }}</td>
                                <td>
                                    @if ($order->order_image)
                                    <img src="{{ $order->order_image }}" width="100%" height="50px">

                                    @else
                                    {{ $order->order_image }}
                                    @endif
                                </td>
                                <td>{{ $order->hub_return_scan }}</td>
                                <td>{{ $order->tracking_id }}</td>
                                <td>{{ isset($status_code[$order->task_status_id])==1?$status_code[$order->task_status_id]:'Not Available'  }}</td>
                                {{-- <td>{{ $order->action==''?'N/A':$order->sprint_id }}</td> --}}
                            </tr>
                        @empty
                   
                        @endforelse
                     
                    </tbody>
                
                </table>

                <div class="col-md-12">
                    @if ($query->isEmpty())
                    <p class="data_not">    No data available in table
                    </p>
                    @endif
                    {!! $query->links() !!}
                </div>

                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>
    <!-- /#page-wrapper -->

@endsection