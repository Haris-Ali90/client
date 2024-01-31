@extends( 'backend.layouts.app' )

@section('title', 'CSV uploader')

@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">
    <!-- Image Viewer CSS -->
    <link href="{{ backend_asset('libraries/galleria/colorbox.css') }}" rel="stylesheet">
    <!-- Custom Light Box Css -->
    <link href="{{ backend_asset('css/custom_lightbox.css') }}" rel="stylesheet">
    <!-- Csv uploader css-->
    <link href="{{ asset('dist/main.f15ef672a693395e2dcb.css') }}" rel="stylesheet">
    <!--<link href="{{ asset('dist/fonts.css') }}" rel="stylesheet">-->
    <!--<link href="{{ asset('dist/font2.css') }}" rel="stylesheet">-->
    <style>
        .csvBox {
            padding: 20px;
            border: 2px solid #f7c703;
            background: #f6f2ef;
            max-width: 400px;
            min-width: 400px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 22px;
            min-height: 160px;
            border-radius: 20px;
            gap: 20px;
            cursor: pointer;
            position: relative;
        }
        .uploadfile {
            position: absolute;
            height: 100%;
            width: 100%;
            opacity: 0;
            display: flex;
            border-radius: 20px;
            cursor: pointer;
        }
        .csvBox i.fa {
            font-size: 35px;
            transform: translateY(-10px);
        }
    </style>

@endsection

@section('JSLibraries')
    <!-- DataTables JavaScript -->
    <script src="{{ backend_asset('libraries/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ backend_asset('libraries/galleria/jquery.colorbox.js') }}"></script>
    <!-- Custom Theme JavaScript -->
    <script src="{{ backend_asset('js/sweetalert2.all.min.js') }}"></script>
    <!-- Custom Light Box JS -->
    <script src="{{ backend_asset('js/custom_lightbox.js')}}"></script>
    <!-- Csv uploader js-->
   <!-- <script src="{{ asset('dist/main.6c02ed5f07d36080ff8c.js') }}"></script> -->
	<script src="{{ asset('dist/main.6c02ed5f07d36080ff8c.js') }}"></script>
	
@endsection

@section('content')


    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>CSV Uploader <small></small></h3>
                </div>
            </div>
                <div class="uploadcsv">
                    <div class="csvBox">
                        <i class="fa fa-upload"></i>
                        <h3>Please Upload CSV File</h3>
                        <input type="file" class="uploadfile">
                    </div>
                </div>    
            <div class="clearfix"></div>
            <noscript>You need to enable JavaScript to run this app.</noscript>
            <div class="main-wrapper" id="root">
                <div id="loader"></div>
            </div>
            <script>var myVar;function myFunction(){}</script>

            {{--@include('backend.layouts.modal')
            @include( 'backend.layouts.popups')--}}
        </div>
    </div>
    <!-- /#page-wrapper -->

@endsection