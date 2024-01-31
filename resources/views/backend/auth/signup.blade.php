@extends('backend.layouts.app-guest')

@section('title', 'Login')
<style>
    .divider-after:after {
        display: none !important;
    }

    .sigup {
        min-width: 150px;
    }

    .boxDivider {
        display: flex;
        flex-wrap: nowrap;
        justify-content: space-between;
        align-items: center;
    }

    .boxDivider p {
        margin-bottom: 0;
    }

    .submitButton {
        width: 100% !important;
    }

    label.form-check-label {
        padding-left: 25px;
    }

    button.button.text.large.fullWidth.css-1l9cpo9 {
        border: 1px solid #dee3e7;
        background: transparent;
        display: flex;
        width: 100%;
        justify-content: center;
        padding: 5px 10px;
        align-items: center;
        gap: 2%;
        min-height: 50px;
        margin-top: 10px;
    }

    button.button.text.large.fullWidth.css-1l9cpo9 img {
        width: 25px;
    }

    .page-login .right-column .inner {
        padding: 50px 0 0 !important;
    }

    .form-check {
        float: left;
        width: 100%;
        margin-bottom: 10px;
    }

    a:link {
        text-decoration: none;
    }
</style>
@section('content')
    <main id="main" class="page-login">
        {{--    <img src="{{ asset('images/under-maintenance.png')}}" style="height: 100vh; width: 100%;">--}}
        <div class="pg-container container-fluid">
            <div class="row_1 row align-items-top no-gutters justify-content-end">
                <!-- Login left column - [Start] -->
                <aside class="left-column col-12 col-md-5 full-h d-none d-sm-block">

                </aside>
                <!-- Login left column - [/end] -->

                <!-- Login right column - [Start] -->
                <aside class="right-column col-12 col-sm-7">
                    <div class="inner full-h-min flexbox flex-center">
                        <div class="full-w">
                            <div id="logo" class="dp-table marginauto mb-20">
                                <img src="{{ asset('images/logo.jpg')}}" alt="">
                            </div>

                            <!-- BEGIN LOGIN FORM -->
                            <div class="row no-gutters justify-content-center">
                                <div class="col-10 col-md-9 col-lg-5 col-xl-5">
                                    <div class="hgroup divider-after align-center">
                                        <h1>Sigup To Client Portal</h1>
                                        <p class="f14">To login please enter your signup credentials</p>
                                    </div>
                                    @if (\Session::has('success'))
                                        <div class="alert alert-success">
                                        <span class="text-left col-md-12">
                                            {!! \Session::get('success') !!}
                                        </span>
                                        </div>
                                @endif

                                <!-- Login Form -->
                                    <form method="post" action="{{ route('sign_up_post') }}" onkeydown="return event.key != 'Enter';">

                                        <div class="form-group">
                                            <label for="emailInput">First Name</label>
                                            <input type="text" name="first_name" id="first_name"
                                                   class="form-control form-control-lg" autofocus=""
                                                   value="{{ old('first_name') }}">
                                            @if ($errors->has('first_name'))
                                                <div class="invalid-feedback">{{ $errors->first('first_name') }}</div>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="emailInput">Last Name</label>
                                            <input type="text" name="last_name" class="form-control form-control-lg"
                                                   id="last_name" autofocus="" value="{{ old('last_name') }}">
                                            @if ($errors->has('last_name'))
                                                <div class="invalid-feedback">{{ $errors->first('last_name') }}</div>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="emailInput">Email</label>
                                            <input type="email" name="email" class="form-control form-control-lg"
                                                   id="email" autofocus="" value="{{ old('email') }}">
                                            @if ($errors->has('email'))
                                                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="emailInput">Phone Number</label>
                                            <input type="number" name="phone" class="form-control form-control-lg"
                                                   id="phone" autofocus="" value="{{ old('phone') }}">
                                            @if ($errors->has('phone'))
                                                <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="paswordInput">Password</label>
                                            <input type="password" name="password" class="form-control form-control-lg"
                                                   id="password">
                                        </div>


                                        <div class="form-group">
                                            <label for="paswordInput">Address</label>
                                            <input type="text" name="business_address"
                                                   class="form-control form-control-lg" id="business_address"
                                                   value="{{ old('business_address') }}">
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="checkbox">
                                            <label class="form-check-label" for="checkbox">
                                                By signing up I agree on OTO's Terms & Conditions and Privacy Policy
                                            </label>
                                        </div>

                                        <input type="hidden" id="address" name="address"/>
                                        <input type="hidden" id="locality" name="locality"/>
                                        <input type="hidden" id="city" name="city"/>
                                        <input type="hidden" id="state" name="state"/>
                                        <input type="hidden" id="postcode" name="postal_code"/>
                                        <input type="hidden" id="country" name="country"/>
                                        <input type="hidden" id="latitude" name="latitude"/>
                                        <input type="hidden" id="longitude" name="longitude"/>
                                        <input type="hidden" id="countryCode" name="countryCode"/>

                                        <div class="align-center mt-10">
                                            <button type="submit" class="btn btn-primary submitButton sigup">Sign up
                                            </button>
                                        </div>
                                        <fieldset>
                                            <a href="{{ url('redirect/google') }}">
                                                <button data-id="button_:r8:" type="button" color="white"
                                                        class="button text large   fullWidth   css-1l9cpo9">
                                                    <img src="{{ asset('images/GoogleLogo.svg')}}">
                                                    <span class="typography button-text heading3  css-11odxpb">
                                                        Sign Up with Google
                                                    </span>
                                                </button>
                                            </a>
                                        </fieldset>

                                        <fieldset>
                                            <a href="{{ url('redirect/facebook') }}">
                                                <button data-id="button_:r8:" type="button" color="white"
                                                        class="button text large   fullWidth   css-1l9cpo9">
                                                    <img src="{{ asset('images/Facebook_f_logo_(2021).svg.png')}}">
                                                    <span class="typography button-text heading3  css-11odxpb">
                                                        Sign Up with Facebook
                                                    </span>
                                                </button>
                                            </a>
                                        </fieldset>
                                    </form>

                                    <div class="extra-info boxDivider">
                                        <p class="forgot-pwd align-center">
                                            <a href="{{ backend_url('reset-password') }}"
                                               class="brd-bc1-light pr-10 none">Lost your password?</a>
                                        </p>
                                        <p>
                                            <a href="{{ backend_url('login') }}">Sign in</a>
                                        </p>
                                    </div>


                                </div>
                            </div>


                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </main>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&sensor=false&key=AIzaSyBX0Z04xF9br04EGbVWR3xWkMOXVeKvns8"></script>
    <script type="text/javascript">
        google.maps.event.addDomListener(window, 'load', function () {

            var places = new google.maps.places.Autocomplete(document.getElementById('business_address'), {
                componentRestrictions: { country: 'SA' } // Restrict autocomplete to Saudi Arabia
            });

            google.maps.event.addListener(places, 'place_changed', function () {
                var place = places.getPlace();
                var address = place.formatted_address;
                var latitude = place.geometry.location.lat();
                var longitude = place.geometry.location.lng();
                var latlng = new google.maps.LatLng(latitude, longitude);
                var geocoder = geocoder = new google.maps.Geocoder();
                geocoder.geocode({ 'latLng': latlng }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {

                            console.log(results[0]);

                            var address = results[0].formatted_address;
                            var pin = results[0].address_components[results[0].address_components.length - 1].long_name;
                            var country = results[0].address_components[results[0].address_components.length - 2].long_name;
                            var state = results[0].address_components[results[0].address_components.length - 3].long_name;
                            var city = results[0].address_components[results[0].address_components.length - 4].long_name;

                            var city2 = results[0].address_components.find(function (component) {
                                return component.types.includes('locality');
                            }).long_name;

                            var countryShortCode = results[0].address_components.find(function (component) {
                                return component.types.includes('country');
                            }).short_name;

                            console.log(latitude)
                            console.log(longitude)
                            console.log(address)
                            console.log(pin)
                            console.log(country)
                            console.log(state)
                            console.log(city)

                            document.getElementById('address').value = address;
                            document.getElementById('country').value = country;
                            document.getElementById('locality').value = city;
                            document.getElementById('city').value = city2;
                            document.getElementById('state').value = state;
                            document.getElementById('postcode').value = pin;
                            document.getElementById('latitude').value = latitude;
                            document.getElementById('longitude').value = longitude;
                            document.getElementById('countryCode').value = countryShortCode; // Set the country code to the desired input field

                        }
                    }
                });
            });


        });
    </script>
@endsection
