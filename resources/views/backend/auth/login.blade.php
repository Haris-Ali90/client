@extends('backend.layouts.app-guest')

@section('title', 'Login')
<style>
    .divider-after:after {
        display: none!important;
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
    a:link { text-decoration: none; }
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
                                <img src="{{ asset('images/logo.jpg')}}" alt="" id="filter-logo" class="filter">
                            </div>

<!-- BEGIN LOGIN FORM -->
<div class="row no-gutters justify-content-center">
    <div class="col-10 col-md-9 col-lg-5 col-xl-5">
        <div class="hgroup divider-after align-center">
            <h1>Login To Client Portal</h1>
            <p class="f14">To login please enter your login credentials</p>
        </div>


        <!-- Login Form -->
        <form method="POST" id="login-form"  class="needs-validation" novalidate>
        {{ csrf_field() }}
        @if (session('status'))
                    <div class="alert alert-success" style="font-size: 15px">
                        {{ session('status') }}
                    </div>
                @endif
            @if ( $errors->count() )
                <div class="alert alert-danger" style="font-size: 15px">
                    {!! implode('<br />', $errors->all()) !!}
                </div>
            @endif
            <div class="form-group">
                <label for="emailInput">Email / Username</label>
                <input type="email" name="email" class="form-control form-control-lg" id="emailInput" autofocus value="{{ old('email') }}" required>
            </div>
{{--        <div class="pg-container container-fluid">--}}
{{--            <div class="row_1 row align-items-top no-gutters justify-content-end">--}}
{{--                <!-- Login left column - [Start] -->--}}
{{--                <aside class="left-column col-12 col-md-5 full-h d-none d-sm-block">--}}

{{--                </aside>--}}
{{--                <!-- Login left column - [/end] -->--}}

{{--                <!-- Login right column - [Start] -->--}}
{{--                <aside class="right-column col-12 col-sm-7">--}}
{{--                    <div class="inner full-h-min flexbox flex-center">--}}
{{--                        <div class="full-w">--}}
{{--                            <div id="logo" class="dp-table marginauto mb-20">--}}
{{--                                <img src="{{ asset('images/logo.jpg')}}" alt="">--}}
{{--                            </div>--}}

{{--<!-- BEGIN LOGIN FORM -->--}}
{{--<div class="row no-gutters justify-content-center">--}}
{{--    <div class="col-10 col-md-9 col-lg-5 col-xl-5">--}}
{{--        <div class="hgroup divider-after align-center">--}}
{{--            <h1>Login To Client Portal</h1>--}}
{{--            <p class="f14">To login please enter your login credentials</p>--}}
{{--        </div>--}}


{{--        <!-- Login Form -->--}}
{{--        <form method="POST" id="login-form"  class="needs-validation" novalidate>--}}
{{--        {{ csrf_field() }}--}}
{{--        @if (session('status'))--}}
{{--                    <div class="alert alert-success" style="font-size: 15px">--}}
{{--                        {{ session('status') }}--}}
{{--                    </div>--}}
{{--                @endif--}}
{{--            @if ( $errors->count() )--}}
{{--                <div class="alert alert-danger" style="font-size: 15px">--}}
{{--                    {!! implode('<br />', $errors->all()) !!}--}}
{{--                </div>--}}
{{--            @endif--}}
{{--            <div class="form-group">--}}
{{--                <label for="emailInput">Email / Username</label>--}}
{{--                <input type="email" name="email" class="form-control form-control-lg" id="emailInput" autofocus value="{{ old('email') }}" required>--}}
{{--            </div>--}}

            <div class="form-group">
                <label for="paswordInput">Password</label>
                <input type="password" name="password" class="form-control form-control-lg" id="paswordInput" required>
            </div>

            <div class="align-center mt-10">
                <button type="submit" class="btn btn-primary submitButton" style="width: 100%;">Login</button>
                <fieldset>
                    <a href="{{ url('redirect/google') }}">
                    <button data-id="button_:r8:" type="button" color="white" class="button text large   fullWidth   css-1l9cpo9">
                        <img src="{{ asset('images/GoogleLogo.svg')}}">
                        <span class="typography button-text heading3  css-11odxpb">
                            Sign in with Google
                        </span>
                    </button>
                    </a>
                </fieldset>

                <fieldset>
                    <a href="{{ url('redirect/facebook') }}">
                        <button data-id="button_:r8:" type="button" color="white" class="button text large   fullWidth   css-1l9cpo9">
                            <img src="{{ asset('images/Facebook_f_logo_(2021).svg.png')}}">
                            <span class="typography button-text heading3  css-11odxpb">
                                Sign in with Facebook
                            </span>
                        </button>
                    </a>
                </fieldset>

{{--                <div class="flex items-center justify-end mt-4">--}}
{{--                    <a class="btn" href="{{ url('redirect/facebook') }}"--}}
{{--                       style="background: #3B5499; color: #ffffff; padding: 10px; width: 100%; text-align: center; display: block; border-radius:3px;">--}}
{{--                        <img src="{{ asset('images/Facebook_f_logo_(2021).svg.png')}}" width="25" height="25">--}}
{{--                          Login with Facebook--}}
{{--                    </a>--}}
{{--                </div>--}}

{{--                <div class="flex items-center justify-end mt-4">--}}
{{--                    <a class="btn" href="{{ url('redirect/facebook') }}"--}}
{{--                       style="background: #3B5499; color: #ffffff; padding: 10px; width: 100%; text-align: center; display: block; border-radius:3px;">--}}
{{--                        <img src="{{ asset('images/GoogleLogo.svg')}}" width="25" height="25">--}}
{{--                         Login with Google--}}
{{--                    </a>--}}
{{--                </div>--}}
            </div>

        </form>
        <div class="extra-info">
            <p class="forgot-pwd p-0 col-md-6">
                <a href="{{ backend_url('reset-password') }}" class="brd-bc1-light none">Lost your password?</a>
            </p>
            <p class="forgot-pwd p-0 text-right col-md-6">
                <a href="{{ backend_url('signup') }}" class="brd-bc1-light none">Sign Up</a>
            </p>
        </div>
    </div>
</div>
{{--            <div class="form-group">--}}
{{--                <label for="paswordInput">Password</label>--}}
{{--                <input type="password" name="password" class="form-control form-control-lg" id="paswordInput" required>--}}
{{--            </div>--}}

{{--            <div class="align-center mt-10">--}}
{{--                <button type="submit" class="btn btn-primary submitButton" style="width: 100%;">Login</button>--}}
{{--                <fieldset>--}}
{{--                    <a href="{{ url('redirect/google') }}">--}}
{{--                    <button data-id="button_:r8:" type="button" color="white" class="button text large   fullWidth   css-1l9cpo9">--}}
{{--                        <img src="{{ asset('images/GoogleLogo.svg')}}">--}}
{{--                        <span class="typography button-text heading3  css-11odxpb">--}}
{{--                                            Sign in with Google--}}
{{--                                        </span>--}}
{{--                    </button>--}}
{{--                    </a>--}}
{{--                </fieldset>--}}
{{--            </div>--}}

{{--        </form>--}}
{{--        <div class="extra-info">--}}
{{--            <p class="forgot-pwd p-0 col-md-6">--}}
{{--                <a href="{{ backend_url('reset-password') }}" class="brd-bc1-light none">Lost your password?</a>--}}
{{--            </p>--}}
{{--            <p class="forgot-pwd p-0 text-right col-md-6">--}}
{{--                <a href="{{ backend_url('signup') }}" class="brd-bc1-light none">Sign Up</a>--}}
{{--            </p>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
</main>
<script>
    let bsh = document.getElementById("filter-logo");
  bsh.classList.remove("filter");
  
</script>

@endsection