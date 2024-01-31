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
    <script src="{{ backend_asset('js/custom_lightbox.js') }}"></script>
    <script>
        
    </script>
@endsection 

<style>
    div.dataTables_paginate{
        display: none;
    }
    .ul.pagination {
       margin-left: 70% !important;

}
    th {
        color: #494949;
        background: #f6f2ef;
        font-family: "Helvetica Neue", Roboto, Arial, "Droid Sans", sans-serif;
        font-size: 13px;
    }
    .data_not{
        text-align: center;
        font-weight: 700;
    }
</style>
@section('dataTablJs')
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
        $("#examples").DataTable(
           
        );
        
      });
      </script>
@endsection


@section('content')

    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3 class="text-center">Order List<small></small></h3>
                </div>
            </div>
            <div class="top_tiles montreal-dashbord-tiles" id="montreal-dashbord-tiles-id">
                <!--Animated-a Div Open-->
                <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 dashbords-conts-tiles-main-wrap">
                    <!--dashbords-conts-tiles-loader-main-wrap-open-->
                    <div class="dashbords-conts-tiles-loader-main-wrap  total-order">
                        <div class="dashbords-conts-tiles-loader-inner-wrap">
                            <div class="lds-roller">
                                <div class="dot-1"></div>
                                <div class="dot-2"></div>
                                <div class="dot-3"></div>
                                <div class="dot-4"></div>
                                <div class="dot-5"></div>
                                <div class="dot-6"></div>
                                <div class="dot-7"></div>
                                <div class="dot-8"></div>
                            </div>
                        </div>
                    </div>
                    <!--dashbords-conts-tiles-loader-main-wrap-close-->
                    <a href="">
                        <div class="tile-stats">
                            <div class="icon">
                                <i class="fa fa-cubes"></i>
                            </div>
                            <div class="count" id="total_orders">{{$order_detail->total()}}
                            </div>
                            <h3>Total Orders</h3>
                        </div>
                    </a>
                </div>
                <!--Animated-a Div Close-->
            </div>

            
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <div class="row">
                                <form method="get" action="{{route('order.list')}}">
                                    <div class="col-md-3">
                                        <label>Search By Date :</label>
                                        <input type="date" name="datepicker" class="data-selector form-control"
                                        required=""
                                        value="{{ isset($_GET['datepicker'])?$_GET['datepicker']: ''}}">
                                        <input type="hidden" name="type" value="total" id="type">
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn sub-ad c-color" type="submit" style="margin-top: 25px;">
                                            Go
                                        </button>
                                    </div>
                                </form>
                                <div class="col-md-6 sm_custm" style="padding-top: 25px;">
                                    <div class="excel-btn" style="float: right">
                                        <a href="{{route('order.list')}}"
                                            class="btn buttons-reload buttons-html5 btn-sm sub-ad c-color excelstyleclass">
                                            Reload
                                        </a>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="x_content">
                                    <table id="examples" class="table table-striped c-border " >
                                        <thead>
                                            <tr>
                                                <th style="width: 5%" class="text-center ">ID</th>
                                                {{-- <th style="width: 30%" class="text-center ">Name</th> --}}
                                                <th style="width: 30%" class="text-center ">Max Distance</th>
                                                <th style="width: 30%" class="text-center ">Pickup Price</th>
                                                <th style="width: 30%" class="text-center ">Distance Price</th>
                                                <th style="width: 30%" class="text-center ">Dropoff Price</th>
                                                <th style="width: 30%" class="text-center ">Distance Allowance</th>
                                                <th style="width: 30%" class="text-center ">Third Party Pickup Price</th>
                                                <th style="width: 30%" class="text-center ">Ordinal</th>
                                                <th style="width: 30%" class="text-center ">Capacity</th>
                                                <th style="width: 30%" class="text-center ">Min Visits</th>
                                                {{-- <th style="width: 10%" class="text-center ">Total </th> --}}
                                                <th style="width: 10%" class="text-center ">Vehicle Id </th>
                                                <th style="width: 10%" class="text-center ">Order Status </th>
                                                <th style="width: 20%" class="text-center ">Created at</th>
                                                <th style="width: 5%" class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            @forelse($order_detail as $index => $user_list)
                                                <tr>
                                                    <td style="width: 5%" class="text-center ">{{ $i }}</td>
                                                    {{-- <td style="width: 5%" class="text-center ">{{$user_list->vehicle['name']}}</td> --}}
                                                    <td style="width: 5%" class="text-center ">
                                                        {{ $user_list->vehicle['max_distance'] }}</td>
                                                    <td style="width: 5%" class="text-center ">
                                                        {{ $user_list->vehicle['pickup_price'] }}</td>
                                                    <td style="width: 5%" class="text-center ">
                                                        {{ $user_list->vehicle['distance_price'] }}</td>
                                                    <td style="width: 5%" class="text-center ">
                                                        {{ $user_list->vehicle['dropoff_price'] }}</td>
                                                    <td style="width: 5%" class="text-center ">
                                                        {{ $user_list->vehicle['distance_allowance '] == '' ? 'N/A' : $user_list->vehicle['distance_allowance '] }}
                                                    </td>
                                                    <td style="width: 5%" class="text-center ">
                                                        {{ $user_list->vehicle['third_party_pickup_price'] }}</td>
                                                    <td style="width: 5%" class="text-center ">
                                                        {{ $user_list->vehicle['ordinal'] }}</td>
                                                    <td style="width: 5%" class="text-center ">
                                                        {{ $user_list->vehicle['capacity'] }}</td>
                                                    <td style="width: 5%" class="text-center ">
                                                        {{ $user_list->vehicle['min_visits'] }}</td>
                                                    {{-- <td style="width: 5%" class="text-center ">{{ $user_list->total}}</td> --}}
                                                    <td style="width: 5%" class="text-center ">
                                                        {{ $user_list->vehicle_id }}
                                                    </td>
                                                    <td style="width: 5%" class="text-center ">
                                                        {{ isset($status_code[$user_list->status_id]) == 1 ? $status_code[$user_list->status_id] : 'Not Available' }}
                                                    </td>
                                                    <td style="width: 5%" class="text-center ">
                                                        {{ $user_list->created_at }}
                                                    </td>
                                                    <td style="width: 5%" class=" text-center"><a
                                                            href="{{ url('order/show/' . $user_list->id) }}"
                                                            title="Edit" class="btn btn-sm c-color btn-info">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                
                                             
                                                

                                                <?php $i++; ?>
                                                
                                               
                                            @empty
                                            
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="col-md-12 d-flex justify-content-end">
                                        @if ($order_detail->isEmpty())
                                            <p class="data_not"> No data available in table
                                            </p>
                                        @endif
                                        {!! $order_detail->links() !!}
                                    </div>
                                    <!-- <div class="col-lg-6">
                                        <div class="row">
                                            <div class="container">
                                                <div class="col-lg-6">
                                                    <div class="col-lg-6">
                                                        <div class="col-lg-6">
                                                            <di class="form-box">
                                                                <div class="row">
                                                                    <div class="form-control">
                                                                        <div class="x_content">
                                                                            <div class="labe">

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </di>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /#page-wrapper -->
@endsection
