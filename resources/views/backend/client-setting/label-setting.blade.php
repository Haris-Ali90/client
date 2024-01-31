@extends( 'backend.layouts.app' )

@section('title', 'Label Setting')
@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
    <style>


    </style>
@endsection

@section('JSLibraries')
    <!-- DataTables JavaScript -->
    <script src="{{ backend_asset('libraries/moment/min/moment.min.js') }}"></script>
    <script src="{{ backend_asset('libraries//bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
@endsection

@section('inlineJS')
    <script>

    </script>
@endsection

@section('content')



    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="portlet box blue">

                <div class="portlet-title">
                    <div class="col-md-4">
                        <h3>Label Setting<small></small></h3>
                    </div>
                </div>

                <div class="portlet-body">

                    <form method="POST" action="{{ route('label-size-create') }}" class="form-horizontal"
                          role="form" enctype="multipart/form-data">
                        <input type="hidden" name="client_user_id" value="{{$client_user_name}}">
                        <div class="form-group col-md-12">
                            <div class="col-lg-4">
                            <label for="rights"  style="margin-bottom: 3px;">Label Print Size *</label>

                                <select class="js-example-basic-multiple form-control col-md-4 col-xs-4" name="print_size" required="required">
                                    <option value="" disabled="disabled" selected>Select Print Size</option>
                                    <option value="a4_size" {{(('a4_size' == $label_size)) ? 'Selected' : ''}}>A4</option>
                                    <option value="a5_size" {{(('a5_size' == $label_size)) ? 'Selected' : ''}}>A5</option>  
                                    <option value="letter_size" {{(('letter_size' == $label_size)) ? 'Selected' : ''}}>Letter</option>
                                    <option value="legal_size" {{(('legal_size' == $label_size)) ? 'Selected' : ''}}>Legal</option>
                                    <option value="executive_size" {{(('executive_size' == $label_size)) ? 'Selected' : ''}}>Executive</option>
                                </select>
                            </div>
                            @if ($errors->has('role'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('role') }}</strong>
                                </span>
                            @endif
                            <div class="col-lg-3">
                                <button class="btn sub-ad c-color" type="submit" style="margin-top:24px">
                                    Save
                                </button>
                            </div>

                        </div>
                    </form>
                    @include( 'backend.layouts.notification_message' )
                </div>
            </div>
        </div>
    </div>

    <!-- /page content -->
@endsection
