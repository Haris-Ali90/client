@extends( 'backend.layouts.app' )

@section('title', 'Dashboard')

@section('CSSLibraries')
<script src="https://code.jquery.com/jquery-latest.min.js"></script>
<link rel="stylesheet" href="{{ asset('backend/libraries/intl-tel-input-master/build/css/intlTelInput.css') }}">
<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css'>
<link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
@endsection
@section('JSLibraries')
<script src="{{ asset('backend/libraries/intl-tel-input-master/build/js/intlTelInput-jquery.min.js') }}"></script>
<script src="{{ asset('backend/libraries/intl-tel-input-master/build/js/intlTelInput.min.js') }}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTK4viphUKcrJBSuoidDqRhVA4AWnHOo0&&callback=show_map_func"></script>


<script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM="
    crossorigin="anonymous"></script>
<script src='https://1000hz.github.io/bootstrap-validator/dist/validator.min.js'></script>
<script src="{{ asset('backend/js/script.js') }}"></script>
@endsection
<!-- <!DOCTYPE html>
<html lang="en" > -->
<!-- <head>
    <meta charset="UTF-8">
    <title>CodePen - step form with validation</title>
    <script src="https://code.jquery.com/jquery-latest.min.js"></script>
    <link rel="stylesheet" href="{{ asset('backend/libraries/intl-tel-input-master/build/css/intlTelInput.css') }}">
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css'>
    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
    <script src="{{ asset('backend/libraries/intl-tel-input-master/build/js/intlTelInput-jquery.min.js') }}"></script>
    <script src="{{ asset('backend/libraries/intl-tel-input-master/build/js/intlTelInput.min.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTK4viphUKcrJBSuoidDqRhVA4AWnHOo0&&callback=show_map_func"></script>


</head> -->
<style>
    body {
        .container.body {
            padding: 0;
        }
    }

    .navbar-right li {
        display: flex !important
    }

    #search-show-map,
    #dropoff-search-show-map {
        top: 20px !important;
        background: #558AFE !important
    }

    #show-map,
    #dropoff-show-map {
        background: #558AFE !important
    }

    #map-canvas {
        height: 350px !important;
        width: 100% !important;
        margin-top: 20px !important;
    }
    div#dropoff-map-canvas {
    width: 100%;
    height: 350px;
    margin-top: 20px !important;
}

</style>
@section('content')
<div class="right_col" role="main">
    <div class="dashboard_pg">
        <!-- partial:index.partial.html -->
        <div class="registercontpage">
            <div class="stepwizard col-md-offset-3">
                <div class="stepwizard-row setup-panel">
                    <div class="stepwizard-step">
                        <a href="#step-1" type="button" class="btn btn-circle btn-default1 btn-primary">1</a>
                        <p>Step 1</p>
                    </div>
                    <div class="stepwizard-step">
                        <a href="#step-2" type="button" class="btn btn-default1 btn-circle" disabled="disabled">2</a>
                        <p>Step 2</p>
                    </div>
                </div>
            </div>
            <form role="form" name="basicform" data-toggle="validator" id="basicform" method="post"
                action="{{ route('step.form.save.data') }}">
                <div class="row setup-content" id="step-1" style="display: block;">
                    <div class="col-md-12">
                        <h3 style="padding-left: 30px;">Personal User</h3>
                    </div>
                    <div class="col-lg-12 d-flex justify-content-center">
                        <div class="col-lg-6 x-shad ">
                            <div class="col-lg-6">
                                <div class="form-group ">
                                    <label for="sel1" class=>Sender Name <span style="color:red;">*</span>:</label>

                                    <!-- <select class="form-control" name="usertitle" id="sel1" style="background: #fff none repeat scroll 0 0;border-color: #999999;width:100px;">
                                                <option>Mr</option>
                                                <option>Ms</option>
                                                <option>Mrs</option>
                                            </select> -->
                                    <input maxlength="100" minlength="3" type="text" id="reg_fname" name="reg_fname"
                                        required="required" class="form-control" placeholder="Enter Name"
                                        data-error="Minimum 3 character required">

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group  ">
                                    <label class="">Mobile Number<span style="color:red;">* </span>:</label>
                                    <!-- <input type="text" placeholder="Phone Number" onkeyup="numberOnly(this)" required="required" id="usr_telephone" name="usr_telephone" maxlength="10" class="form-control"> -->
                                    <input class="form-control" type="tel" id="phone" name="user_phone"
                                        placeholder="e.g. +1 702 123 4567" value="+1">
                                    <div class="help-block with-errors"></div>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group ">
                                    <label class="">Latitude<span style="color:red;">* </span>:</label>
                                    <input type="text" placeholder="Latitude" id="pickup_latitude"
                                        name="pickup_latitude" class="form-control" disabled required>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group ">
                                    <label class="">Longitude<span style="color:red;">* </span>:</label>

                                    <input type="text" placeholder="Longitude" id="pickup_longitude"
                                        name="pickup_longitude" class="form-control" disabled required>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group  ">
                                    <label class="">Postal Code<span style="color:red;">* </span>:</label>

                                    <input type="text" placeholder="Postal code" id="pickup_postal_code"
                                        name="pickup_postal_code" class="form-control" required>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group ">
                                    <label class="">City<span style="color:red;">* </span>:</label>
                                    <input type="text" placeholder="City" id="pickup_city" name="pickup_city"
                                        class="form-control" required>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group  ">
                                    <label class="">State<span style="color:red;">* </span>:</label>

                                    <input type="text" placeholder="State" id="pickup_state" name="pickup_state"
                                        class="form-control" required>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group  ">
                                    <label class="">Country<span style="color:red;">* </span>:</label>

                                    <input type="text" placeholder="Country" id="pickup_country" name="pickup_country"
                                        class="form-control" required>

                                </div>
                            </div>


                            <div class="col-lg-6">
                                <div class="form-group  ">
                                    <label class="">Email <span style="color:red;">* </span>:</label>

                                    <input type="email" placeholder="Enteremail id" id="usr_regemail"
                                        name="usr_regemail" class="form-control" required>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group  ">
                                    <label class="">Address :</label>

                                    <!-- <input type="text" name="address" class="form-control datepicker dt"> -->
                                    <input autocomplete="off" class="form-control" placeholder="Enter the location"
                                        id="pickup_address" maxlength="254" name="pickup_address" required>
                                    <button data-id="button_fillFromMap" id="search-show-map" type="button"
                                        class="button btn-primary ">Search</span></button>
                                    <button data-id="button_fillFromMap" id="show-map" type="button"
                                        class="button btn-primary ">
                                        <div class="icon small button-icon css-1vm66y2"><svg aria-hidden="true"
                                                focusable="false" data-prefix="fad" data-icon="location-crosshairs"
                                                class="svg-inline--fa fa-location-crosshairs fa-fw " role="img"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                <g class="fa-duotone-group">
                                                    <path class="fa-secondary" fill="currentColor"
                                                        d="M336 256C336 300.2 300.2 336 256 336C211.8 336 176 300.2 176 256C176 211.8 211.8 176 256 176C300.2 176 336 211.8 336 256z">
                                                    </path>
                                                    <path class="fa-primary" fill="currentColor"
                                                        d="M256 0C273.7 0 288 14.33 288 32V66.65C368.4 80.14 431.9 143.6 445.3 224H480C497.7 224 512 238.3 512 256C512 273.7 497.7 288 480 288H445.3C431.9 368.4 368.4 431.9 288 445.3V480C288 497.7 273.7 512 256 512C238.3 512 224 497.7 224 480V445.3C143.6 431.9 80.14 368.4 66.65 288H32C14.33 288 0 273.7 0 256C0 238.3 14.33 224 32 224H66.65C80.14 143.6 143.6 80.14 224 66.65V32C224 14.33 238.3 0 256 0zM128 256C128 326.7 185.3 384 256 384C326.7 384 384 326.7 384 256C384 185.3 326.7 128 256 128C185.3 128 128 185.3 128 256z">
                                                    </path>
                                                </g>
                                            </svg></div><span
                                            class="typography button-text sub-ad c-color button  css-11odxpb"
                                            font-weight="400">Fill from Map</span>
                                    </button>

                                </div>

                            </div>
                            <div class="col-lg-12 d-flex justify-content-center">
                                <button class="btn sub-ad c-color nextBtn btn-lg pull-right" type="button">Next</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div id="map-canvas"></div>
                    </div>


                </div>

                <div class="row setup-content" id="step-2" style="display: none;">

                    <div class="col-lg-12 d-flex justify-content-center">
                        <div class="col-lg-6 x-shad">

                            <div class="col-lg-6 ">
                                <div class="form-group  ">
                                    <label for="sel1" class="">Name <span style="color:red;">*</span>:</label>

                                    <!-- <select class="form-control" name="usertitle" id="sel1" style="background: #fff none repeat scroll 0 0;border-color: #999999;width:100px;">
                                                <option>Mr</option>
                                                <option>Ms</option>
                                                <option>Mrs</option>
                                            </select> -->
                                    <input maxlength="100" minlength="3" type="text" id="dropoff_reg_fname"
                                        name="dropoff_reg_fname" required="required" class="form-control"
                                        placeholder="Enter Name" data-error="Minimum 3 character required">

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group  ">
                                    <label class="">Mobile Number<span style="color:red;">* </span>:</label>

                                    <!-- <input type="text" placeholder="Phone Number" onkeyup="numberOnly(this)" required="required" id="usr_telephone" name="usr_telephone" maxlength="10" class="form-control"> -->
                                    <input class="form-control" type="tel" id="dropoff_phone" name="dropoff_user_phone"
                                        placeholder="e.g. +1 702 123 4567" value="+1">
                                    <div class="help-block with-errors"></div>

                                </div>
                            </div>



                            <!-- <div class="form-group field ">
                                        <label class="control-label main col-md-4">Marital Status :</label>
                                        <div class="col-md-8">
                                        <label class="radio-inline">
                                        <input type="radio" name="reg_marital">Single
                                        </label>
                                        <label class="radio-inline">
                                        <input type="radio" name="reg_marital">Married
                                        </label>
                                        </div>
                                    </div>		  -->
                            <div class="col-lg-6">
                                <div class="form-group  ">
                                    <label class="control-label main col-md-4">Latitude<span style="color:red;">*
                                        </span>:</label>

                                    <input type="text" placeholder="Latitude" id="dropoff_latitude"
                                        name="dropoff_latitude" class="form-control" disabled required>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group  ">
                                    <label class="">Longitude<span style="color:red;">* </span>:</label>

                                    <input type="text" placeholder="Longitude" id="dropoff_longitude"
                                        name="dropoff_longitude" class="form-control" disabled required>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group  ">
                                    <label class="">Postal Code<span style="color:red;">* </span>:</label>

                                    <input type="text" placeholder="Postal code" id="dropoff_postal_code"
                                        name="dropoff_postal_code" class="form-control" required>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group  ">
                                    <label class="">City<span style="color:red;">* </span>:</label>

                                    <input type="text" placeholder="City" id="dropoff_city" name="dropoff_city"
                                        class="form-control" required>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group  ">
                                    <label class="">State<span style="color:red;">* </span>:</label>

                                    <input type="text" placeholder="State" id="dropoff_state" name="dropoff_state"
                                        class="form-control" required>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group  ">
                                    <label class="">Country<span style="color:red;">* </span>:</label>

                                    <input type="text" placeholder="Country" id="dropoff_country" name="dropoff_country"
                                        class="form-control" required>

                                </div>
                            </div>
                            
                            <div class="col-lg-6">
                                <div class="form-group  ">
                                    <label class="">Address :</label>
                                    <!-- <input type="text" name="address" class="form-control datepicker dt"> -->
                                    <input autocomplete="off" class="form-control" placeholder="Enter the location"
                                        id="dropoff_address" maxlength="254" name="dropoff_address" required>
                                    <button data-id="button_fillFromMap" id="dropoff-search-show-map" type="button"
                                        class="button btn-primary ">Search</span></button>
                                   

                                </div>

                            </div>
                            <div class="col-lg-6">
                                <button data-id="button_fillFromMap" id="dropoff-show-map" type="button"
                                class="button btn-primary ">
                                <div class="icon small button-icon css-1vm66y2"><svg aria-hidden="true"
                                        focusable="false" data-prefix="fad" data-icon="location-crosshairs"
                                        class="svg-inline--fa fa-location-crosshairs fa-fw " role="img"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <g class="fa-duotone-group">
                                            <path class="fa-secondary" fill="currentColor"
                                                d="M336 256C336 300.2 300.2 336 256 336C211.8 336 176 300.2 176 256C176 211.8 211.8 176 256 176C300.2 176 336 211.8 336 256z">
                                            </path>
                                            <path class="fa-primary" fill="currentColor"
                                                d="M256 0C273.7 0 288 14.33 288 32V66.65C368.4 80.14 431.9 143.6 445.3 224H480C497.7 224 512 238.3 512 256C512 273.7 497.7 288 480 288H445.3C431.9 368.4 368.4 431.9 288 445.3V480C288 497.7 273.7 512 256 512C238.3 512 224 497.7 224 480V445.3C143.6 431.9 80.14 368.4 66.65 288H32C14.33 288 0 273.7 0 256C0 238.3 14.33 224 32 224H66.65C80.14 143.6 143.6 80.14 224 66.65V32C224 14.33 238.3 0 256 0zM128 256C128 326.7 185.3 384 256 384C326.7 384 384 326.7 384 256C384 185.3 326.7 128 256 128C185.3 128 128 185.3 128 256z">
                                            </path>
                                        </g>
                                    </svg></div><span class="typography button-text button  css-11odxpb"
                                    font-weight="400">Fill from Map</span>
                            </button>
                            </div>
                            <div class="col-lg-12 d-flex justify-content-center" style="margin-top:20px ">
                               
                                    <button id="finalsubmit" class="btn sub-ad c-color  btn-lg pull-right"
                                        type="button">submit</button>
                               
                            </div>
                           
                        </div>
                        
                    </div>
                    <div class="col-lg-12">
                        <div id="dropoff-map-canvas"></div>
                       </div>
                    
                </div>
                

            </form>
        </div>
    </div>
</div>
    <!--  -->
<!-- partial -->
<!-- <script src='//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script> -->
<!-- <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
<script src='https://1000hz.github.io/bootstrap-validator/dist/validator.min.js'></script>
<script  src="{{ asset('backend/js/script.js') }}"></script> -->


@endsection