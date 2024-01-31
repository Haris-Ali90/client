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

    input[type=text],
    [type=email],
    [type=number] {
        width: 100% !important;
        margin-bottom: 9px !important;
        padding: 12px !important;
        border:1px solid #F8BB00 !important;
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
        margin-bottom: 5px;
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
   body, .container.body{
        padding:0 !important
    }
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
                <form role="form" name="basicform" data-toggle="validator" id="basicform" method="post"
                    action="{{ route('profile.store') }}">
                    <div class="row" >
                            <div class="container">
                                <div class="row x-shad" style="margin-top:50px">
                                    <div class="col-50 ">
                                        <h3>Billing Address</h3>
                                        <input class="c-border" type="text" id="fname" name="id"  placeholder="John M. Doe"
                                            value="{{ $id }}" hidden>

                                        <label for="fname"><i class="fa fa-user"></i> Full Name</label>
                                        <input class="c-border" type="text" id="fname" name="first_name" placeholder="John M. Doe" 
                                            value="{{ old('first_name') ?? ($profile==""?'':$profile->first_name ?? 'default') }}">
                                        @if ($errors->has('first_name'))
                                            <span class="help-block" style="color: red">
                                                {{ $errors->first('first_name') }}
                                            </span>
                                        @endif
                                        <label for="email"><i class="fa fa-envelope"></i> Email</label>
                                        <input class="c-border" type="text" id="email" name="email" placeholder="john@example.com"
                                            value="{{ old('email') ?? ($profile==""?'':$profile->email ?? 'default') }}">
                                        @if ($errors->has('email'))
                                            <span class="help-block" style="color: red">
                                                {{ $errors->first('email') }}
                                            </span>
                                        @endif
                                        <label for="adr"><i class="fa fa-address-card-o"></i> Address</label>
                                        <input class="c-border" type="text" id="adr" name="address" placeholder="542 W. 15th Street"
                                            value="{{ old('address') ?? ($profile==""?'':$profile->address ?? 'default') }}">
                                        @if ($errors->has('address'))
                                            <span class="help-block" style="color: red">
                                                {{ $errors->first('address') }}
                                            </span>
                                        @endif
                                        <label for="city"><i class="fa fa-institution"></i> City</label>
                                        <input class="c-border" type="text" id="city" name="city" placeholder="New York"
                                            value="{{ old('city') ?? ($profile==""?'':$profile->city ?? 'default') }}">
                                        @if ($errors->has('city'))
                                            <span class="help-block" style="color: red">
                                                {{ $errors->first('city') }}
                                            </span>
                                        @endif
                                        <div class="row">
                                            <div class="col-50">
                                                <label for="state">State</label>
                                                <input class="c-border" type="text" id="state" name="state" placeholder="NY"
                                                    value="{{ old('state') ?? ($profile==""?'':$profile->state ?? 'default') }}">
                                                @if ($errors->has('state'))
                                                    <span class="help-block" style="color: red">
                                                        {{ $errors->first('state') }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="col-50">
                                                <label for="zip">Zip</label>
                                                <input class="c-border" type="number" id="zip" name="zip" placeholder="10001"
                                                    value="{{ old('zip') ?? ($profile==""?'':$profile->zip ?? 'default') }}">
                                                @if ($errors->has('zip'))
                                                    <span class="help-block" style="color: red">
                                                        {{ $errors->first('zip') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-50">
                                        <h3>Payment</h3>
                                        <label for="fname">Accepted Cards</label>
                                        <div class="icon-container">
                                            <i class="fa fa-cc-visa" style="color:navy;"></i>
                                            <i class="fa fa-cc-amex" style="color:blue;"></i>
                                            <i class="fa fa-cc-mastercard" style="color:red;"></i>
                                            <i class="fa fa-cc-discover" style="color:orange;"></i>
                                        </div>
                                        <label for="cname">Name on Card</label>
                                        <input class="c-border" type="text" id="cname" name="card_name"
                                            placeholder="John More Doe"
                                            value="{{ old('card_name') ?? ($profile==""?'':$profile->card_name ?? 'default') }}">
                                        @if ($errors->has('card_name'))
                                            <span class="help-block" style="color: red">
                                                {{ $errors->first('card_name') }}
                                            </span>
                                        @endif
                                        <label for="ccnum">Credit card number</label>
                                        <input class="c-border" type="text" id="ccnum" name="card_number"
                                            placeholder="1234-1234-1234-1234"
                                            value="{{ old('card_number') ?? ($profile==""?'':$profile->card_number ?? 'default') }}">
                                        @if ($errors->has('card_number'))
                                            <span class="help-block" style="color: red">
                                                {{ $errors->first('card_number') }}
                                            </span>
                                        @endif
                                        <label for="expmonth">Month</label>
                                        <input class="c-border" type="number" id="expmonth" name="exp_month" placeholder="01"
                                            value="{{ old('exp_month') ?? ($profile==""?'':$profile->exp_month ?? 'default') }}">
                                        @if ($errors->has('exp_month'))
                                            <span class="help-block" style="color: red">
                                                {{ $errors->first('exp_month') }}
                                            </span>
                                        @endif
                                        <div class="row">
                                            <div class="col-50">
                                                <label for="expyear">Year</label>
                                                <input class="c-border" type="number" id="expyear" name="exp_year" placeholder="2023"
                                                    value="{{ old('exp_year') ?? ($profile==""?'':$profile->exp_year ?? 'default') }}">

                                                @if ($errors->has('exp_year'))
                                                    <span class="help-block" style="color: red">
                                                        {{ $errors->first('exp_year') }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="col-50">
                                                <label for="cvv">CVV</label>
                                                <input class="c-border" type="number" id="cvv" name="cvv" placeholder="123"
                                                    value="{{ old('cvv') ?? ($profile==""?'':$profile->cvv ?? 'default') }}">
                                                @if ($errors->has('cvv'))
                                                    <span class="help-block" style="color: red">
                                                        {{ $errors->first('cvv') }}
                                                    </span>
                                                @endif
                                            </div>
                                            
                                        </div>
                                      
                                    </div>
                                    <div class="col-lg-12 d-flex justify-content-end c-gap">
                                    <button  type="button" class="c-def sub-ad" value="cancel" disabled>Cancel</button>
                                            <input  type="submit" class="c-color sub-ad" value="Save">
                                            </div>
                                </div>
                                {{-- <label>
                                    <input class="c-border" type="checkbox" checked="checked" name="sameadr"> Shipping address same as billing
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
