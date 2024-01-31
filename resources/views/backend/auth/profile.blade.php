

@extends( 'backend.layouts.app' )



@section('title', 'Montreal Dashboard')



@section('CSSLibraries')

{{--    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css'>--}}

{{--    <link rel='stylesheet' href='https://cdn.jsdelivr.net/gh/luxonauta/luxa@8a98/dist/compressed/luxa.css'>--}}

<style>

    .profile-pic.bs-md {

        width: auto;

        max-width: 28rem;

        margin: 3rem 2rem;

        padding: 2rem;

        display: flex;

        flex-flow: wrap column;

        align-items: center;

        justify-content: center;

        border-radius: 0.25rem;

         background: linear-gradient(161deg, rgb(235 231 226) 43%, rgb(246 242 239) 53%) !important;

        box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%);

    }

    .lx-column.column-user-pic {

        display: flex;

        align-items: flex-start;

        justify-content: flex-end;

    }

    .lx-column {

        width: auto;

        min-width: 15rem;

        max-width: 50rem;

        margin: 3rem 0 0 0;
        
        height: 1px

    }

    .lx-row.align-stretch {

        width: 100%;

        display: flex;

        flex-flow: wrap row;

        align-items: center;

        justify-content: center;

        gap: 0.75rem;

    }

    .profile-pic .pic:focus .lx-btn, .profile-pic .pic:focus-within .lx-btn, .profile-pic .pic:hover .lx-btn {

        opacity: 1;

        display: flex;

    }

    .lx-row {

        width: 100%;

        display: flex;

        flex-flow: wrap row;

        align-items: center;

        gap: 0.75rem;

        justify-content: center;

    }

    h1.pic-label {

        width: 100%;

        margin: 0 0 1rem 0;

        text-align: center;

        font-size: 1.4rem;

        font-weight: 700;

    }

    .pic.bs-md {

        width: 16rem;

        height: 16rem;

        position: relative;

        overflow: hidden;

        border-radius: 50%;

    }

    .pic.bs-md img {

        width: 100%;

        height: 100%;

        object-fit: cover;

        object-position: center;

    }

    .pic-info {

        width: 100%;

        margin: 2rem 0 0 0;

        font-size: 0.9rem;

        text-align: center;

    }

    .fieldset label {

        width: 100%;

        margin: 0 0 1rem 0;

        font-size: 1.2rem;

        font-weight: 700;

    }

    .fieldset .input-wrapper {

        width: 100%;

        display: flex;

        flex-flow: nowrap;

        align-items: stretch;

        justify-content: center;

    }

    .fieldset:first-child {

        margin-top: 0;

    }

    .fieldset {

        width: 100%;

        margin: 2rem 0;

        position: relative;

        display: flex;

        flex-wrap: wrap;

        align-items: center;

        justify-content: flex-start;

    }

    .fieldset .input-wrapper input, .fieldset .input-wrapper select {

        flex-grow: 1;

        padding: 0.375rem 0.75rem;

        display: block;

        border-top-left-radius: 0;

        border-bottom-left-radius: 0;

        border-top-right-radius: 0.25em;

        border-bottom-right-radius: 0.25em;

        border: 0.0625rem solid #ced4da;

        border-left: 0;

        font-size: 1.5rem;

        font-weight: 400;

        line-height: 1.5;

        color: #495057;

        min-height: 50px;

    }

    .fieldset .input-wrapper .icon {

        width: fit-content;

        margin: 0;

        padding: 0.375rem 0.75rem;

        display: flex;

        align-items: center;

        border-top-left-radius: 0.25em;

        border-bottom-left-radius: 0.25em;

        border-top-right-radius: 0;

        border-bottom-right-radius: 0;

        border: 0.0625rem solid #ced4da;

        font-size: 1.5rem;

        font-weight: 400;

        line-height: 1.5;

        min-width: 50px;

        justify-content: center;

        color: #495057;

        text-align: center;

        background-color: #e9ecef;

    }

    .actions .lx-btn {

        padding: 0.5rem 1rem;

        display: flex;

        align-items: center;

        justify-content: center;

        font-weight: 700;

        color: white;

        min-height: 44px;

        min-width: 115px;

        cursor: pointer;

    }

    .actions .lx-btn#cancel, .actions .lx-btn.cancel {

        background-color: #ff5555;

    }

    .actions .lx-btn#clear, .actions .lx-btn.clear {

        color: black;

        background-color: white;

    }

    .actions .lx-btn#save, .actions .lx-btn.save {

        background-color: #558AFE;

    }

    .actions {

        width: 100%;

        display: flex;

        align-items: center;

        justify-content: space-evenly;

    }

    .profile-pic .pic .lx-btn {

        opacity: 0;

        width: 100%;

        height: 100%;

        margin: 0;

        padding: 0;

        position: absolute;

        transform: translate(-50%, -50%);

        transition: all .5s ease-in-out;

        top: 50%;

        left: 50%;

        display: none;

        align-items: center;

        justify-content: center;

        text-transform: none;

        font-size: 1rem;

        color: white;

        background-color: rgba(0, 0, 0, 0.8);

    }



    .lx-btn {

        padding: 0.688rem 2.5rem;

        vertical-align: middle;

        text-align: center;

        border-radius: 0.262rem;

        border: none;

        text-transform: uppercase;

        box-shadow: 0 0.125rem 0.25rem rgb(0 0 0 / 8%);

    }

    a#change-avatar input[type="file"] {

        position: absolute;

        height: 100%;

        width: 100%;

        opacity: 0;

    }

</style>







@section('content')





    <div class="right_col" role="main">



        <section class="profile_sec">

            <div class="lx-container-70">

                <div class="lx-row">

                    <h1 class="title">Edit your profile</h1>

                </div>

                <div class="lx-row align-stretch">

                    <div class="lx-column column-user-pic">

                        <div class="profile-pic bs-md">

                            <h1 class="pic-label">Profile picture</h1>

                            <div class="pic bs-md">

                                <img src="https://bit.ly/3jRbrbp" alt="" width="4024" height="6048" loading="lazy">

                                <a id="change-avatar" class="lx-btn"><i class="fa fa-camera-retro"></i><input

                                            type="file">&nbsp;&nbsp;Change your profile picture.</a>

                            </div>

                            <div class="pic-info">

                                <p><i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;This photo will appear on the platform, in your contributions or where it is mentioned.</p>

                            </div>

                        </div>

                    </div>

                    <div class="lx-column">

                        @if (\Session::has('success'))

                            <div class="alert alert-success">

                                <span class="text-left col-md-12">

                                    {!! \Session::get('success') !!}

                                </span>

                            </div>

                        @endif

                        <form action="" method="post" enctype="multipart/form-data" onkeydown="return event.key != 'Enter';">

                            {{ csrf_field() }}

                            <div class="fieldset">

                                <label for="user-name">Name</label>

                                <div class="input-wrapper">

                                    <span class="icon"><i class="fa fa-user"></i></span>

                                    <input type="text" id="name" name="name" value="{{ $vendorData->name }}" autocomplete="username" required>

                                </div>

{{--                                <div id="user-name-helper" class="helper">--}}

{{--                                    <p>Your name can appear on the platform, in your contributions or where it is mentioned.</p>--}}

{{--                                </div>--}}

                            </div>

                            <div class="fieldset">

                                <label for="user-id">Phone No</label>

                                <div class="input-wrapper">

                                    <span class="icon"><i class="fa fa-phone"></i></span>

                                    <input type="number" id="phone" name="phone" value="{{ $vendorData->phone }}" required>

                                </div>

                                <div id="user-id-helper" class="helper"></div>

                            </div>

                            <div class="fieldset">

                                <label for="email">E-mail</label>

                                <div class="input-wrapper">

                                    <span class="icon"><i class="fa fa-envelope"></i></span>

                                    <input type="email" id="email" name="email" value="{{ $vendorData->email }}" disabled>

                                </div>

                                <div id="email-helper" class="helper"></div>

                            </div>

                            <div class="fieldset">

                                <label for="pass">Password</label>

                                <div class="input-wrapper">

                                    <span class="icon"><i class="fa fa-key"></i></span>

                                    <input type="password" id="password" name="password" value="">

                                </div>

                                <div id="pass-helper" class="helper"></div>

                            </div>

                            <div class="fieldset">

                                <label for="pass">Address</label>

                                <div class="input-wrapper">

                                    <span class="icon"><i class="fa fa-location-arrow"></i></span>

                                    <input type="text"

                                           name="business_address"

                                           id="business_address"

                                           class=" form-control-lg update-address-on-change google-address"

                                           value="{{ $vendorData->business_address }}"

                                           placeholder="Address" required>

                                </div>

                                @if ($errors->has('business_address'))

                                    <div class="invalid-feedback">{{ $errors->first('business_address') }}</div>

                                @endif

                                <div id="pass-helper" class="helper"></div>

                            </div>

                            <input type="hidden" id="address2" name="street"/>

                            <input type="hidden" id="locality" name="city"/>

                            <input type="hidden" id="state" name="city_id"/>

                            <input type="hidden" id="postcode" name="postal_code"/>

                            <input type="hidden" id="country" name="country"/>

                            <input type="hidden" id="latitude" name="latitude"/>

                            <input type="hidden" id="longitude" name="longitude"/>

                            <div class="actions">

{{--                                <a id="cancel" class="lx-btn"><i class="fa fa-ban"></i>&nbsp;&nbsp;Cancel</a>--}}

{{--                                <a id="clear" class="lx-btn"><i class="fa fa-broom"></i>&nbsp;&nbsp;Clean</a>--}}

                                <button type="submit" id="save" class="lx-btn"><i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </section>

    </div>





{{--    <div class="form-group no-min-h">--}}

{{--        <label for="license">Select Address *</label>--}}

{{--        <input type="text"--}}

{{--               id="address"--}}

{{--               class="form-control form-control-lg update-address-on-change google-address"--}}

{{--               placeholder="Address" name="address"--}}

{{--               value=""--}}

{{--               required>--}}

{{--</div>--}}



    <!-- /#page-wrapper -->



@endsection

@section('JSLibraries')

    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&sensor=false&key=AIzaSyBX0Z04xF9br04EGbVWR3xWkMOXVeKvns8"></script>

    <script type="text/javascript">

        google.maps.event.addDomListener(window, 'load', function () {

            var places = new google.maps.places.Autocomplete(document.getElementById('business_address'));

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

                            var address = results[0].formatted_address;

                            var pin = results[0].address_components[results[0].address_components.length - 1].long_name;

                            var country = results[0].address_components[results[0].address_components.length - 2].long_name;

                            var state = results[0].address_components[results[0].address_components.length - 3].long_name;

                            var city = results[0].address_components[results[0].address_components.length - 4].long_name;



                            console.log(latitude)

                            console.log(longitude)

                            console.log(address)

                            console.log(pin)

                            console.log(country)

                            console.log(state)

                            console.log(city)



                            document.getElementById('address2').value = address;

                            document.getElementById('country').value = country;

                            document.getElementById('locality').value = city;

                            document.getElementById('state').value = state;

                            document.getElementById('postcode').value = pin;

                            document.getElementById('latitude').value = latitude;

                            document.getElementById('longitude').value = longitude;



                        }

                    }

                });

            });





        });

    </script>

    <script src='https://code.jquery.com/jquery-3.4.1.min.js'></script>

{{--    <script src="https://use.fontawesome.com/releases/v5.14.0/js/all.js" defer crossorigin="anonymous" data-search-pseudo-elements></script>--}}

    <script src='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput-jquery.min.js'></script>

    <script>

        var phoneInput = $(".phone-input"),

            phoneForm = phoneInput.closest("form"),

            phoneEnterArrow = $(".phone-enter-arrow"),

            phoneError = $(".phone-error"),

            phoneSuccess = $(".phone-success"),

            phonePreventKeyup = false;

        // $(".phone-input").intlTelInput({

        //             initialCountry: "pk",

        //             separateDialCode: true,

        //             // utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js"

        //         });



        phoneInput.intlTelInput({

            initialCountry: "auto",

            geoIpLookup: function(callback) {

                $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {

                    var countryCode = (resp && resp.country) ? resp.country : "";

                    callback(countryCode);

                });

            },

            nationalMode: false,

            initialCountry: "pk",

            autoPlaceholder: false,

            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.6/js/utils.js"

        }).done(function() {

            phoneInput.focus();

        });



        function resetInput() {

            phoneEnterArrow.addClass("hide");

            phoneError.addClass("hide");

            phoneSuccess.addClass("hide");

            phoneInput.removeClass("valid invalid");

        }



        phoneInput.on("keyup", function() {

            //console.log("keyup");

            if (phonePreventKeyup) {

                phonePreventKeyup = false;



            } else {

                resetInput();

                if (phoneInput.val() && phoneInput.intlTelInput("isValidNumber")) {

                    phoneInput.addClass("valid");

                    phoneEnterArrow.removeClass("hide");

                    phoneSuccess.removeClass("hide");

                }

            }

        });



        function errorCheck() {

            var isError = (phoneInput.val() && !phoneInput.intlTelInput("isValidNumber"));

            if (isError) {

                phoneInput.addClass("invalid");

                phoneError.removeClass("hide");

            }

            return isError;

        }



        phoneInput.on("blur", function() {

            //console.log("blur");

            errorCheck();

        });



        phoneForm.on("submit", function(e) {

            //console.log("submit");

            resetInput();

            var isError = errorCheck();

            if (isError) {

                e.preventDefault();

                phonePreventKeyup = true;

            }

        });



    </script>

@endsection

@endsection

