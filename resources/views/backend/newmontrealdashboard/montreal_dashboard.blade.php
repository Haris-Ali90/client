@extends( 'backend.layouts.app' )

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

@section('JSLibraries')
    <!-- DataTables JavaScript -->
    <script src="{{ backend_asset('libraries/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ backend_asset('libraries/galleria/jquery.colorbox.js') }}"></script>
    <!--  <script src="{{ backend_asset('js/jquery-1.12.4.js') }}"></script>
    <script src="{{ backend_asset('js/jquery-ui.js') }}"></script>
    <link href="{{ backend_asset('js/jquery-ui.css') }}" rel="stylesheet"> -->
    <!-- Custom Light Box JS -->
    <script src="{{ backend_asset('js/custom_lightbox.js')}}"></script>
@endsection

<style>
    /* .ul.pagination {
       margin-left: 70% !important;

} */
th{
    color: #494949;
    background: #f6f2ef;
    font-family: "Helvetica Neue", Roboto, Arial, "Droid Sans", sans-serif;
    font-size: 13px;
}
.dataTables_filter {
    width: 13% !important;
    float: right;
    text-align: end;
}
div.dataTables_paginate{
    display:none;
}
</style>
@section('dataTablJs')
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
        $("#exampler").DataTable(
           
        );
        
      });
      </script>
@endsection
@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left amazon-text">
                    <h3 class="text-center">
                        Reporting Dashboard
                        <small></small>
                    </h3>
                </div>
            </div>
        @include('backend.newmontrealdashboard.montreal_cards')
            {{--@include('backend.layouts.modal')
            @include( 'backend.layouts.popups')--}}
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <!-- <h2><small>Dashboard</small></h2> -->
                        </div>
                        <div class="x_title">
                                <div class="row">
                            <form method="get" action="{{route('date.reporting')}}">
                               
                                    <div class="col-md-3">
                                        <label>Search By Date :</label>
                                        <input type="date" name="datepicker" class="data-selector form-control"
                                               required=""
                                               value="{{ isset($_GET['datepicker'])?$_GET['datepicker']: '' }}">
                                        <input type="hidden" name="type" value="total" id="type">
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn sub-ad c-color" type="submit" style="margin-top: 25px;">
                                            Go
                                        </button>
                                    </div>
                                </form>
                                    <div class="col-md-6 sm_custm" style="padding-top: 25px;">
                                        @if(can_access_route('newexport_Montreal.excel',$userPermissoins))
                                            <div class="excel-btn" style="float: right">
                                                <a href="{{ route('newexport_Montreal.excel') }}"
                                                   class="btn buttons-excel buttons-html5 btn-sm sub-ad c-color excelstyleclass">
                                                    Export to Excel
                                                </a>
                                            </div>
                                        @endif
                                        <div class="excel-btn" style="float: right">
                                            <a href="{{route('newmontreal.index')}}"
                                               class="btn buttons-reload buttons-html5 btn-sm sub-ad c-color excelstyleclass">
                                                Reload
                                            </a>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                             <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            @include( 'backend.layouts.notification_message' )

                            <table id="exampler" class="table table-striped table-bordered yajrabox">
                                <thead stylesheet="color:black;">
                                <tr>
                                    <th style="width: 79px">Sno #</th>
                                    <th style="width: 79px">Order #</th>
                                    <th>Route Number</th>
                                    <th>Driver</th>
                                    <th>Customer Address</th>
                                    <th>Out For Delivery</th>
                                    <th>Sorted Time</th>
                                    <th>Actual Arrival @ CX</th>
                                    <th>Image</th>
                                    <th>Tracking #</th>
                                    <th>Status</th>
                                    {{-- <th>Action</th> --}}
                                </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $a=1;
                                    @endphp
                                    @forelse($query as $index => $order)
                                        <tr>
                                            <td>  {{$index + $query->firstItem() }} </td>
                                            <td>{{ $order->sprint_id }}</td>
                                            <td>{{ $order->route_id }}</td>
                                            <td>{{ $order->joey_name }}</td>
                                            <td>{{ $order->address_line_1 }}</td>
                                            <td>{{ $order->picked_up_at }}</td>
                                            <td>{{ $order->sorted_at }}</td>
                                            <td>{{ $order->delivered_at}}</td>
                                            <td>{{ $order->order_image }}</td>
                                            <td>{{ $order->tracking_id}}</td>
                                            <td>{{ isset($status_code[$order->task_status_id])==1?$status_code[$order->task_status_id]:'Not Available'  }}</td>
                                            {{-- <td>{{ $order->action==''?'N/A':$order->sprint_id }}</td> --}}
                                        </tr>
                                    @empty
                                    <tr>     There Are no Data  </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="col-md-12 d-flex justify-content-end">
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