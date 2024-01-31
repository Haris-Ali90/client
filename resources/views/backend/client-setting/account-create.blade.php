@extends('backend.layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    body {
        font-family: Arial;
        font-size: 17px;
        padding: 8px;
    }

    * {
        box-sizing: border-box;
    }

    .row {
        display: -ms-flexbox;
        /* IE10 */
        display: flex;
        -ms-flex-wrap: wrap;
        /* IE10 */
        flex-wrap: wrap;
        margin: 0 -16px;
    }
    /* .registercontpage{
        margin-top:150px ;
    } */

    .col-25 {
        -ms-flex: 25%;
        /* IE10 */
        flex: 25%;
    }

    .col-50 {
        -ms-flex: 50%;
        /* IE10 */
        flex: 50%;
    }

    .col-75 {
        -ms-flex: 75%;
        /* IE10 */
        flex: 75%;
    }

    .col-25,
    .col-50,
    .col-75 {
        padding: 0 16px;
    }

    .container {

        padding: 5px 20px 15px 20px;

        border-radius: 3px;
    }

   body, .container.body{
        padding:0 !important
    }


    input[type=text],
    [type=email],
    [type=number] {
        width: 100% !important;
        margin-bottom: 9px !important;
        padding: 12px !important;
        border: 1px solid #F8BB00 !important;
        border-radius: 3px !important;
    }

    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }

    label {
        margin-bottom: 10px;
        display: block;
    }

    .icon-container {
        margin-bottom: 20px;
        padding: 7px 0;
        font-size: 24px;
    }

    .btn {
        background-color: #04AA6D;
        color: white;
        padding: 12px;
        margin: 10px 0;
        border: none;
        width: 100%;
        border-radius: 3px;
        cursor: pointer;
        font-size: 17px;
    }

    .btn:hover {
        background-color: #45a049;
    }

    a {
        color: #2196F3;
    }

    hr {
        border: 1px solid lightgrey;
    }

    span.price {
        float: right;
        color: grey;
    }

    /* Responsive layout - when the screen is less than 800px wide, make the two columns stack on top of each other instead of next to each other (also change the direction - make the "cart" column go on top) */
    @media (max-width: 800px) {
        .row {
            flex-direction: column-reverse;
        }

        .col-25 {
            margin-bottom: 20px;
        }
    }
</style>
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
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTK4viphUKcrJBSuoidDqRhVA4AWnHOo0&&callback=show_map_func">
    </script>


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
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTK4viphUKcrJBSuoidDqRhVA4AWnHOo0&&callback=show_map_func">
    </script>


</head> -->
<style>
        .navbar-right li {
    display: flex !important;
    align-items: center;
}   
</style>
@section('content')
    <div class="right_col" role="main">
        <div class="dashboard_pg">
            <!-- partial:index.partial.html -->
            <div class="registercontpage">
                @if (\Session::has('message'))
                    <p style="color: #45a049">
                        {!! \Session::get('message') !!}
                    </p>
                @endif
                <h1 >Add Bank Account</h1>
                <form role="form" name="basicform" data-toggle="validator" id="basicform" method="post"
                    action="{{ route('account.store') }}">
                    <div class="row">
                        <input type="text" id="fname" name="id" placeholder="John M. Doe"
                        value="{{ $id }}" hidden>
                        <div class="row c-w d-flex justify-content-center ">
                            <div class="col-lg-8 x-shad2" style="margin-top:100px">
                                <div class="row ">
                                    <div class="col-50">
                                        <label for="fname"><i class="fa fa-user"></i> Account Type</label>
                                        <div class="">
                                            <select class="form-control" value="{{old('account_type') ?? ($profile==""?'':$profile->account_type?? 'default') }}" name="account_type">
                                                <option disabled>Select an account type</option>
                                                <option>Building Society Roll Number</option>
                                                <option>Checking</option>
                                                <option>Current Account</option>
                                                <option>Giro Account</option>
                                                <option>Regular</option>
                                                <option>Salary Account</option>
                                                <option>Savings</option>
                                            </select>
                                            @if ($errors->has('account_type'))
                                                <span class="help-block" style="color: red">
                                                    {{ $errors->first('account_type') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-50">
                                        <label for="fname"><i class="fa fa-user"></i> Country Type</label>
                                        <div class="">
                                            <input type="text" id="iban" value="{{ old('country_type') ?? ($profile==""?'':$profile->country_type?? 'default') }}" name="country_type"
                                                placeholder="e.g. 00019873738834">
                                            @if ($errors->has('country_type'))
                                                <span class="help-block" style="color: red">
                                                    {{ $errors->first('country_type') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-50">
                                        <label for="fname"><i class="fa fa-user"></i>IBAN</label>
                                        <div class="">
                                            <input type="number" id="iban" value="{{ old('iban') ?? ($profile==""?'':$profile->iban ?? 'default')}}" name="iban"
                                                placeholder="e.g. 00019873738834">
                                            @if ($errors->has('iban'))
                                                <span class="help-block" style="color: red">
                                                    {{ $errors->first('iban') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-50">
                                        <label for="fname"><i class="fa fa-user"></i>Account number</label>
                                        <div class="">
                                            <input type="number" id="iban" value="{{ old('account_number') ?? ($profile==""?'':$profile->account_number?? 'default') }}" name="account_number"
                                                placeholder="e.g. 00019873738834">
                                                @if ($errors->has('account_number'))
                                                <span class="help-block" style="color: red">
                                                    {{ $errors->first('account_number') }}
                                                </span>
                                            @endif
                                            </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-50">
                                        <label for="fname"><i class="fa fa-user"></i>Bank number</label>
                                        <div class="">
                                            <input type="number" id="iban" value="{{ old('bank_number') ?? ($profile==""?'':$profile->bank_number?? 'default') }}" name="bank_number"
                                                placeholder="e.g. 00019873738834">
                                                @if ($errors->has('bank_number'))
                                                <span class="help-block" style="color: red">
                                                    {{ $errors->first('bank_number') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-50">
                                        <label for="fname"><i class="fa fa-user"></i>Currency</label>
                                        <div class="">
                                            <input type="text" id="iban" value="{{ old('currency') ?? ($profile==""?'':$profile->currency?? 'default') }}" name="currency"
                                                placeholder="e.g. 00019873738834">
                                                @if ($errors->has('currency'))
                                                <span class="help-block" style="color: red">
                                                    {{ $errors->first('currency') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-50">
                                        <label for="fname"><i class="fa fa-user"></i>Branch ID</label>
                                        <div class="">
                                            <input type="number" id="iban" value="{{ old('branch_id') ?? ($profile==""?'':$profile->branch_id ?? 'default') }}" name="branch_id"
                                                placeholder="e.g. 00019873738834">
                                                @if ($errors->has('branch_id'))
                                                <span class="help-block" style="color: red">
                                                    {{ $errors->first('branch_id') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-50">
                                        <label for="fname"><i class="fa fa-user"></i>Check digit</label>
                                        <div class="">
                                            <input type="number" id="iban" value="{{ old('check_digit') ?? ($profile==""?'':$profile->check_digit?? 'default') }}" name="check_digit"
                                                placeholder="e.g. 00019873738834">
                                                @if ($errors->has('check_digit'))
                                                <span class="help-block" style="color: red">
                                                    {{ $errors->first('check_digit') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-end c-gap" style="padding: 6px 17px;">
                                <button  class="c-def sub-ad" disabled > Cancel </button>
                                    <input type="submit" value="Save" class="sub-ad c-color">
                                   
                                </div>
                            </div>

                        </div>
                        {{-- <label>
                                    <input type="checkbox" checked="checked" name="sameadr"> Shipping address same as billing
                                  </label> --}}

                </form>
            </div>
        </div>

        </form>
    </div>
    </div>
    </div>

    <!-- partial -->
    <!-- <script src='//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script> -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM="
        crossorigin="anonymous"></script>
                            <script src='https://1000hz.github.io/bootstrap-validator/dist/validator.min.js'></script>
                            <script src="{{ asset('backend/js/script.js') }}"></script> -->

    <script>
        $('input[name=card_number]').keypress(function() {
            var rawNumbers = $(this).val().replace(/-/g, '');
            var cardLength = rawNumbers.length;
            if (cardLength !== 0 && cardLength <= 12 && cardLength % 4 == 0) {
                $(this).val($(this).val() + '-');
            }
            if (cardLength > 15) {
                return false
            }
        })
    </script>
@endsection
